<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Pago;
use App\Models\Promocion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Events\PedidoCreado;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::where('activo', 1)
            ->with([
                'categorias' => function ($q) {
                    $q->where('activo', 1);
                },
                'ingredientes' => function ($q) {
                    $q->where('activo', 1);
                }
            ]);

        if ($request->filled('categoria_id')) {
            $query->whereHas('categorias', function ($q) use ($request) {
                $q->where('categorias.id', $request->categoria_id)
                    ->where('activo', 1);
            });
        }

        if ($request->filled('precio_min')) {
            $query->where('precio', '>=', $request->precio_min);
        }
        if ($request->filled('precio_max')) {
            $query->where('precio', '<=', $request->precio_max);
        }

        if ($request->filled('buscar')) {
            $busqueda = strtolower($request->buscar);
            $query->whereRaw('LOWER(nombre) LIKE ?', ['%' . $busqueda . '%']);
        }

        $orden = $request->input('orden', 'nombre_asc');
        switch ($orden) {
            case 'precio_asc':
                $query->orderBy('precio', 'asc');
                break;
            case 'precio_desc':
                $query->orderBy('precio', 'desc');
                break;
            case 'nombre_desc':
                $query->orderBy('nombre', 'desc');
                break;
            default:
                $query->orderBy('nombre', 'asc');
        }

        $productos = $query->paginate(12)->appends($request->query());
        $categorias = Categoria::where('activo', 1)->orderBy('nombre')->get();
        $carrito = session('carrito', []);

        if ($request->expectsJson()) {
            return response()->json([
                'productos' => $productos,
                'categorias' => $categorias,
                'carrito' => $carrito,
            ]);
        }

        return view('Cliente.dashboard', compact('productos', 'categorias', 'orden', 'carrito'));
    }

    public function addToCart(Request $request)
    {
        $productoId = $request->input('producto_id');
        $quitados = is_array($request->input('quitados')) ? $request->input('quitados') : [];
        $cantidad = max(1, (int) $request->input('cantidad', 1));

        $producto = Producto::with([
            'ingredientes' => function ($q) {
                $q->where('activo', 1);
            }
        ])->findOrFail($productoId);

        // Validar que no se intenten quitar ingredientes obligatorios
        $obligatorios = $producto->ingredientes
            ->where('pivot.es_obligatorio', true)
            ->pluck('id')
            ->toArray();
        if (!empty(array_intersect($quitados, $obligatorios))) {
            return response()->json([
                'success' => false,
                'message' => 'No se pueden quitar ingredientes obligatorios.'
            ], 422);
        }

        $carrito = session('carrito', []);

        // Crear una clave única para el item en el carrito (basada en producto_id y quitados)
        $quitadosSorted = collect($quitados)->sort()->values()->all();
        $itemKey = $productoId . ':' . (empty($quitadosSorted) ? '' : implode(',', $quitadosSorted));

        $carrito[$itemKey] = [
            'producto_id' => $productoId,
            'nombre' => $producto->nombre,
            'precio' => $producto->precio,
            'quitados' => $quitadosSorted,
            'cantidad' => isset($carrito[$itemKey]) ? $carrito[$itemKey]['cantidad'] + $cantidad : $cantidad,
        ];

        session(['carrito' => $carrito]);

        return response()->json([
            'success' => true,
            'message' => 'Producto añadido al carrito',
            'carrito' => $carrito,
        ]);
    }

    public function updateCart(Request $request)
    {
        $itemKey = $request->input('item_key');
        $cantidad = max(1, (int) $request->input('cantidad'));

        $carrito = session('carrito', []);

        if (isset($carrito[$itemKey])) {
            $carrito[$itemKey]['cantidad'] = $cantidad;
            session(['carrito' => $carrito]);

            return response()->json([
                'success' => true,
                'message' => 'Carrito actualizado',
                'carrito' => $carrito,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Ítem no encontrado en el carrito',
        ]);
    }

    public function removeFromCart(Request $request)
    {
        $itemKey = $request->input('item_key');
        $carrito = session('carrito', []);

        if (isset($carrito[$itemKey])) {
            unset($carrito[$itemKey]);
            session(['carrito' => $carrito]);

            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado del carrito',
                'carrito' => $carrito,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Ítem no encontrado en el carrito',
        ]);
    }

    public function procesarPago(Request $request)
    {
        $carrito = session('carrito', []);
        if (empty($carrito)) {
            return response()->json([
                'success' => false,
                'message' => 'El carrito está vacío.',
            ]);
        }

        $metodoPago = $request->input('metodo_pago');
        if (!in_array($metodoPago, ['Efectivo', 'Tarjeta'])) {
            return response()->json([
                'success' => false,
                'message' => 'Método de pago inválido.',
            ]);
        }

        $total = 0;
        $now = Carbon::now();

        // Crear el pedido
        $codigo = strtoupper('ORD-' . rand(100, 999) . '-' . Str::random(2));
        $pedido = Pedido::create([
            'usuario_id' => Auth::id(),
            'total' => 0,
            'metodo_pago' => $metodoPago,
            'estado' => 'Recibido',
            'codigo' => $codigo,
        ]);

        foreach ($carrito as $item) {
            $producto = Producto::findOrFail($item['producto_id']);
            $precio = $item['precio'];

            // Aplicar promoción si existe
            $promocion = Promocion::where('activo', true)
                ->where('fecha_inicio', '<=', $now)
                ->where('fecha_fin', '>=', $now)
                ->whereHas('productos', function ($query) use ($item) {
                    $query->where('producto_id', $item['producto_id']);
                })
                ->first();

            if ($promocion) {
                $descuento = $promocion->descuento / 100;
                $precio = $precio * (1 - $descuento);
            }

            $subtotal = $precio * $item['cantidad'];
            $total += $subtotal;

            // Crear detalle del pedido
            $detalle = DetallePedido::create([
                'pedido_id' => $pedido->id,
                'producto_id' => $item['producto_id'],
                'fecha_hora' => $now,
                'cantidad' => $item['cantidad'],
                'subtotal' => $subtotal,
            ]);

            // Asignar ingredientes quitados
            if (!empty($item['quitados'])) {
                $detalle->ingredientesQuitados()->attach($item['quitados']);
            }
        }

        // Actualizar el total del pedido
        $pedido->update(['total' => $total]);

        // Crear el pago
        Pago::create([
            'pedido_id' => $pedido->id,
            'tipo' => $metodoPago,
            'monto' => $total,
            'fecha' => $now,
            'estado' => 'Pendiente',
        ]);

        // Limpiar el carrito
        session()->forget('carrito');

        event(new PedidoCreado($pedido));

        return response()->json([
            'success' => true,
            'message' => 'Pedido creado exitosamente.',
            'codigo' => $codigo,
        ]);
    }

    public function search(Request $request)
    {
        $q = $request->query('cedula', '');

        // Validamos mínimo 3 caracteres, por ejemplo:
        if (strlen($q) < 3) {
            return response()->json([], 200);
        }

        // Buscamos coincidencias (puede ser “like” para búsqueda parcial)
        $clientes = Cliente::where('cedula', 'like', "%{$q}%")
            ->take(10)
            ->get(['id', 'nombre', 'apellido', 'cedula', 'telefono', 'puntos', 'activo']);

        return response()->json($clientes);
    }

    public function store(Request $request)
    {
        // 1) Validación de entrada
        $data = $request->validate([
            'cedula' => ['required', 'string', 'max:20', 'unique:clientes,cedula'],
            'nombre' => ['required', 'string', 'max:50'],
            'apellido' => ['required', 'string', 'max:50'],
            'telefono' => ['nullable', 'string', 'max:20'],
            // Agrega aquí cualquier otro campo necesario (por ejemplo: estado)
        ]);

        // 2) Creación del cliente
        $cliente = Cliente::create([
            'cedula' => $data['cedula'],
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'telefono' => $data['telefono'] ?? null,
            'puntos' => 0,
            'estado' => 'activo',
        ]);

        // 3) Retornar JSON de éxito
        return response()->json([
            'success' => true,
            'cliente' => [
                'id' => $cliente->id,
                'cedula' => $cliente->cedula,
                'nombre' => $cliente->nombre,
                'apellido' => $cliente->apellido,
                'telefono' => $cliente->telefono,
                'puntos' => $cliente->puntos,
                'estado' => $cliente->estado,
            ],
        ]);
    }

    public function obtenerPuntosPorCedula(Request $request)
    {
        $request->validate([
            'cedula' => ['required', 'string', 'max:20'],
        ]);

        $cedula = $request->input('cedula');
        $cliente = Cliente::where('cedula', $cedula)->first();

        if (!$cliente) {
            // Ya no devolvemos 404; regresamos success=false pero HTTP 200.
            return response()->json([
                'success' => false,
                'message' => 'No se encontró ningún cliente con esa cédula.'
            ]);
        }

        return response()->json([
            'success' => true,
            'cliente' => [
                'id' => $cliente->id,
                'cedula' => $cliente->cedula,
                'nombre' => $cliente->nombre,
                'apellido' => $cliente->apellido,
                'telefono' => $cliente->telefono,
                'puntos' => $cliente->puntos,
                'estado' => $cliente->estado,
            ],
        ]);
    }

    public function update(Request $request, $id)
    {
        // 1) Validar que exista el cliente
        $cliente = Cliente::findOrFail($id);

        // 2) Validar campos de entrada
        //    (cedula no se puede cambiar aquí, suponemos que es PK lógica)
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:50'],
            'apellido' => ['required', 'string', 'max:50'],
            'telefono' => ['nullable', 'string', 'max:20'],
            // Agrega otros campos editables si fuera necesario
        ]);

        // 3) Actualizar el cliente
        $cliente->update($data);

        // 4) Retornar JSON con el cliente actualizado
        return response()->json([
            'success' => true,
            'cliente' => [
                'id' => $cliente->id,
                'cedula' => $cliente->cedula,
                'nombre' => $cliente->nombre,
                'apellido' => $cliente->apellido,
                'telefono' => $cliente->telefono,
                'puntos' => $cliente->puntos,
                'estado' => $cliente->estado,
            ],
        ]);
    }

    public function llamado()
    {
        // Fecha de hoy en Montevideo
        $hoy = Carbon::now('America/Montevideo')->toDateString();

        $pedidos = Pedido::with('cliente')
            ->where('estado', 'Listo')
            ->whereDate('updated_at', $hoy)        // ← sólo hoy
            ->orderBy('updated_at', 'desc')
            ->get();                               // ya no necesitas take(5)

        return view('Cliente.llamado', compact('pedidos'));
    }

    // app/Http/Controllers/ClienteController.php

    public function showPublic(Pedido $pedido)
    {
        // Solo si ya está Entregado (opcional):
        if ($pedido->estado !== 'Listo') {
            abort(404);
        }

        // Carga la relación cliente (puede ser null)
        $pedido->load('cliente');

        // Devuelve JSON público
        return response()->json([
            'id' => $pedido->id,
            'codigo' => $pedido->codigo,
            'updated_at' => $pedido->updated_at,
            'cliente' => $pedido->cliente ? [
                'nombre' => $pedido->cliente->nombre,
                'apellido' => $pedido->cliente->apellido,
                'cedula' => $pedido->cliente->cedula,
            ] : null,
        ]);
    }

}