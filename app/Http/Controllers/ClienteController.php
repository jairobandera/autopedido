<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Pago;
use App\Models\Promocion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::where('activo', 1)
            ->with(['categorias' => function ($q) {
                $q->where('activo', 1);
            }, 'ingredientes' => function ($q) {
                $q->where('activo', 1);
            }]);

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
    $ingredientes = is_array($request->input('ingredientes')) ? $request->input('ingredientes') : [];
    $cantidad = max(1, (int)$request->input('cantidad', 1));

    $producto = Producto::with(['ingredientes' => function ($q) {
        $q->where('activo', 1);
    }])->findOrFail($productoId);

    // Validar ingredientes obligatorios
    $obligatorios = $producto->ingredientes
        ->where('pivot.es_obligatorio', true)
        ->pluck('id')
        ->toArray();
    if (array_diff($obligatorios, $ingredientes)) {
        return response()->json([
            'success' => false,
            'message' => 'Faltan ingredientes obligatorios.'
        ], 422);
    }

    $carrito = session('carrito', []);

    $itemKey = $productoId . ':' . (empty($ingredientes) ? '' : implode(',', collect($ingredientes)->sort()->values()->all()));

    $carrito[$itemKey] = [
        'producto_id' => $productoId,
        'nombre' => $producto->nombre,
        'precio' => $producto->precio,
        'ingredientes' => $ingredientes,
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
        $cantidad = max(1, (int)$request->input('cantidad'));

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
        if (!in_array($metodoPago, ['Efectivo', 'MercadoPago'])) {
            return response()->json([
                'success' => false,
                'message' => 'Método de pago inválido.',
            ]);
        }

        $total = 0;
        $now = Carbon::now();

        foreach ($carrito as $item) {
            $producto = Producto::find($item['producto_id']);
            $precio = $item['precio'];
            
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
        }

        $codigo = 'PED-' . time() . '-' . rand(1000, 9999);

        $pedido = Pedido::create([
            'usuario_id' => Auth::id(),
            'total' => $total,
            'metodo_pago' => $metodoPago,
            'estado' => 'Recibido',
            'codigo' => $codigo,
        ]);

        foreach ($carrito as $item) {
            $producto = Producto::find($item['producto_id']);
            $precio = $item['precio'];
            
            if ($promocion = Promocion::where('activo', true)
                ->where('fecha_inicio', '<=', $now)
                ->where('fecha_fin', '>=', $now)
                ->whereHas('productos', function ($query) use ($item) {
                    $query->where('producto_id', $item['producto_id']);
                })
                ->first()) {
                $descuento = $promocion->descuento / 100;
                $precio = $precio * (1 - $descuento);
            }

            DetallePedido::create([
                'pedido_id' => $pedido->id,
                'producto_id' => $item['producto_id'],
                'fecha_hora' => $now,
                'cantidad' => $item['cantidad'],
                'subtotal' => $precio * $item['cantidad'],
            ]);
        }

        Pago::create([
            'pedido_id' => $pedido->id,
            'tipo' => $metodoPago,
            'monto' => $total,
            'fecha' => $now,
            'estado' => 'Pendiente',
        ]);

        session()->forget('carrito');

        return response()->json([
            'success' => true,
            'message' => 'Pedido creado exitosamente.',
            'codigo' => $codigo,
        ]);
    }
}