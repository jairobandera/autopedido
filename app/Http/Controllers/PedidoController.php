<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Producto;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource (Dashboard Cajero).
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Pedido::with(['usuario', 'pago'])
            ->where('estado', '!=', 'Entregado');

        // Si viene algo en search y es un dígito, filtramos por ID
        if ($search !== null && ctype_digit($search)) {
            $query->where('id', (int) $search);
        }

        $pedidos = $query
            ->orderBy('id', 'desc')
            ->paginate(5)
            ->withQueryString(); // mantiene "search" en la query

        return view('Caja.dashboard', compact('pedidos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Paginamos 10 productos activos y pasamos a la vista
        $productos = Producto::where('activo', 1)->paginate(10);

        return view('Caja.create', compact('productos'));
    }

    /**
     * Store a newly created resource in storage.
     * Recibe JSON: { items: [{id, cantidad}], metodo_pago }
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:productos,id',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.quitados' => 'array',
            'items.*.quitados.*' => 'integer|exists:ingredientes,id',
            'metodo_pago' => 'required|in:Efectivo,MercadoPago',
        ]);

        do {
            $code = 'PED-' . strtoupper(Str::random(4));
        } while (Pedido::where('codigo', $code)->exists());


        // 1) Creamos el pedido en estado 'Recibido'
        $pedido = Pedido::create([
            'usuario_id' => Auth::id(),
            'total' => 0,  // lo calculamos abajo
            'metodo_pago' => $data['metodo_pago'],
            'estado' => 'Recibido',
            'codigo' => $code,
        ]);

        // 2) Creamos los detalles y sumamos el total
        $total = 0;
        foreach ($data['items'] as $item) {
            $producto = Producto::findOrFail($item['id']);
            $subtotal = $producto->precio * $item['cantidad'];

            $detalle = DetallePedido::create([
                'pedido_id' => $pedido->id,
                'producto_id' => $producto->id,
                'fecha_hora' => now(),
                'cantidad' => $item['cantidad'],
                'subtotal' => $subtotal,
            ]);

            $total += $subtotal;
            if (!empty($item['quitados'])) {
                $detalle->ingredientesQuitados()->attach($item['quitados']);
            }

        }
        $pedido->update(['total' => $total]);

        //$pedido->total = $total;
        //$pedido->save();

        // 3) Creamos el pago asociado
        Pago::create([
            'pedido_id' => $pedido->id,
            'tipo' => $data['metodo_pago'],
            'monto' => $total,
            'fecha' => now(),
            'estado' => $data['metodo_pago'] === 'Efectivo' ? 'Completado' : 'Pendiente',
        ]);

        // 4) Devolvemos JSON para tu JS
        return response()->json([
            'success' => true,
            'pedido_id' => $pedido->id,
        ]);
    }

    // en app/Models/Pedido.php
    public function pago()
    {
        return $this->hasOne(Pago::class, 'pedido_id');
    }
    public function show($id)
    {
        // Carga el pedido + detalles + producto dentro de cada detalle
        $pedido = Pedido::with('detalles.producto')->findOrFail($id);

        return response()->json([
            'codigo' => $pedido->codigo,
            'total' => $pedido->total,
            'detalles' => $pedido->detalles->map(fn($d) => [
                'cantidad' => $d->cantidad,
                'subtotal' => (float) $d->subtotal,
                'producto' => ['nombre' => $d->producto->nombre],
                'quitados' => $d->ingredientesQuitados->pluck('nombre')->all(),
            ]),
        ]);
    }

    public function cambiarEstado(Request $request, $id)
    {
        // Valida que venga un estado permitido
        $data = $request->validate([
            'estado' => 'required|in:Cancelado,Recibido,En Preparacion,Listo,Entregado'
        ]);

        $pedido = Pedido::findOrFail($id);
        $pedido->estado = $data['estado'];
        $pedido->save();

        return response()->json([
            'success' => true,
            'nuevo_estado' => $pedido->estado
        ]);
    }

    public function entregados()
    {
        // Sólo los entregados hoy
        $pedidos = Pedido::with('usuario', 'pago')
            ->where('estado', 'Entregado')
            ->whereDate('updated_at', Carbon::today())
            ->get();

        return view('Caja.entregados', compact('pedidos'));
    }

    public function buscarProductos(Request $request)
    {
        // Toma el parámetro de búsqueda (q) y la página
        $q = $request->query('q', '');
        $productos = Producto::where('activo', 1)
            ->when($q, fn($query) => $query->where('nombre', 'like', "%{$q}%"))
            ->paginate(5)
            ->withQueryString();

        // Si es petición AJAX JSON, devolvemos data + links HTML
        if ($request->wantsJson()) {
            return response()->json([
                'data' => $productos->items(),
                'links' => $productos->links()->render()
            ]);
        }

        // En caso contrario, podrías devolver una vista (no necesario aquí)
        return view('Caja.create', compact('productos'));
    }

    public function detalleProducto($id)
    {
        $producto = Producto::with(['ingredientes'])->findOrFail($id);

        // Formatea la respuesta JSON con id, nombre, precio, imagen y lista de ingredientes
        return response()->json([
            'id' => $producto->id,
            'nombre' => $producto->nombre,
            'precio' => (float) $producto->precio,
            'imagen' => $producto->imagen,
            'ingredientes' => $producto->ingredientes->map(fn($ing) => [
                'id' => $ing->id,
                'nombre' => $ing->nombre,
                'es_obligatorio' => (bool) $ing->pivot->es_obligatorio,
            ]),
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:productos,id',
            'items.*.cantidad' => 'required|integer|min:1',
            'metodo_pago' => 'required|in:Efectivo,MercadoPago',
        ]);

        $pedido = Pedido::findOrFail($id);
        // 1) Borrar detalles antiguos
        $pedido->detalles()->delete();

        // 2) Recalcular total
        $total = 0;
        foreach ($data['items'] as $item) {
            $producto = Producto::findOrFail($item['id']);
            $subtotal = $producto->precio * $item['cantidad'];
            $pedido->detalles()->create([
                'producto_id' => $producto->id,
                'fecha_hora' => now(),
                'cantidad' => $item['cantidad'],
                'subtotal' => $subtotal
            ]);
            $total += $subtotal;
        }

        // 3) Actualizar pedido y pago
        $pedido->update([
            'total' => $total,
            'metodo_pago' => $data['metodo_pago']
        ]);
        $pedido->pago()->update([
            'monto' => $total,
            'tipo' => $data['metodo_pago'],
            'estado' => $data['metodo_pago'] === 'Efectivo' ? 'Completado' : 'Pendiente',
        ]);

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $pedido = Pedido::with('detalles.producto')->findOrFail($id);

        // Preparamos el array “detalles” para JS:
        $detalles = $pedido->detalles->map(function ($d) {
            return [
                'id' => $d->producto->id,
                'nombre' => $d->producto->nombre,
                'precio' => (float) $d->subtotal / $d->cantidad,
                'cantidad' => $d->cantidad,
                'quitados' => $d->ingredientesQuitados
                    ->map(fn($i) => ['id' => $i->id, 'nombre' => $i->nombre])
                    ->all(),
                'subtotal' => (float) $d->subtotal,
            ];
        });

        return view('Caja.edit', compact('pedido', 'detalles'));
    }

    /**
     * Devuelve el ID del pedido más reciente
     */
    public function latest()
    {
        $ultimo = Pedido::orderBy('id', 'desc')->first();
        return response()->json([
            'id' => $ultimo ? $ultimo->id : 0,
            'estado' => $ultimo ? $ultimo->estado : null,
        ]);
    }



}
