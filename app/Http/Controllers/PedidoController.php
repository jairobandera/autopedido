<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Producto;
use App\Models\Pago;
use App\Models\Cliente;
use App\Models\PuntoPedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        // Fecha de hoy en Montevideo
        $today = Carbon::now('America/Montevideo')->toDateString();

        $query = Pedido::with(['usuario', 'pago'])
            ->where('estado', '!=', 'Entregado')
            ->whereDate('created_at', $today);  // sólo hoy

        if (!empty($search)) {
            $query->where('codigo', 'like', "%{$search}%");
        }

        $pedidos = $query
            ->orderBy('id', 'desc')
            ->paginate(5)
            ->withQueryString(); // mantiene "search" en la query

        // Para el dashboard en tiempo real
        $ultimo = Pedido::orderBy('id', 'desc')->first();
        $lastCreatedAt = $ultimo
            ? $ultimo->created_at->toIso8601String()
            : null;

        return view('Caja.dashboard', compact('pedidos', 'lastCreatedAt'));
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
     * Recibe JSON: { cliente_id?, items: [{ id, cantidad, quitados }], metodo_pago }
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:productos,id',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.quitados' => 'array',
            'items.*.quitados.*' => 'integer|exists:ingredientes,id',
            'metodo_pago' => 'required|in:Efectivo,Tarjeta',
        ]);

        DB::beginTransaction();
        try {
            // 1) Generar código único PED-XXXX
            do {
                $code = 'PED-' . strtoupper(Str::random(4));
            } while (Pedido::where('codigo', $code)->exists());

            // 2) Crear el pedido en estado 'Recibido'
            $pedido = Pedido::create([
                'usuario_id' => Auth::id(),
                'total' => 0,  // se actualiza luego
                'metodo_pago' => $data['metodo_pago'],
                'estado' => 'Recibido',
                'codigo' => $code,
            ]);

            // 3) Crear los detalles y acumular total
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
            // Actualizamos el total en el pedido
            $pedido->update(['total' => $total]);

            // 4) Crear el pago asociado
            Pago::create([
                'pedido_id' => $pedido->id,
                'tipo' => $data['metodo_pago'],
                'monto' => $total,
                'fecha' => now(),
                'estado' => $data['metodo_pago'] === 'Efectivo' ? 'Pendiente' : 'Pendiente',
            ]);

            // 5) Si se recibió cliente_id, crear registro en punto_pedido
            if (!empty($data['cliente_id'])) {
                // Lógica de puntos: 1 punto cada $10 de total, mínimo 10, máximo 100
                $puntos = floor($total / 10);
                $puntos = max(10, min($puntos, 100));
                \Log::debug('DEBUG en store(): puntos generados = ' . $puntos . ' para cliente_id=' . $data['cliente_id'] . ' y pedido_id=' . $pedido->id);

                PuntoPedido::create([
                    'cliente_id' => $data['cliente_id'],
                    'pedido_id' => $pedido->id,
                    'cantidad' => $puntos,
                    'tipo' => 'Canjeo', // o 'Redencion' si corresponde
                    'fecha' => now(),
                ]);

                // (Opcional) Actualizar total de puntos en la tabla clientes
                $cliente = Cliente::find($data['cliente_id']);
                if ($cliente) {
                    $cliente->puntos += $puntos;
                    $cliente->save();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'pedido_id' => $pedido->id,
                'codigo' => $pedido->codigo,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
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
        $pedidos = Pedido::with('usuario', 'pago')
            ->where('estado', 'Entregado')
            ->whereDate('updated_at', Carbon::today())
            ->get();

        return view('Caja.entregados', compact('pedidos'));
    }

    public function buscarProductos(Request $request)
    {
        $q = $request->query('q', '');
        $productos = Producto::where('activo', 1)
            ->when($q, fn($query) => $query->where('nombre', 'like', "%{$q}%"))
            ->paginate(5)
            ->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $productos->items(),
                'links' => $productos->links()->render(),
            ]);
        }

        return view('Caja.create', compact('productos'));
    }

    public function detalleProducto($id)
    {
        $producto = Producto::with(['ingredientes'])->findOrFail($id);

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
            'items.*.quitados' => 'nullable|array',
            'items.*.quitados.*' => 'integer|exists:ingredientes,id',
            'metodo_pago' => 'required|in:Efectivo,Tarjeta',
        ]);

        $pedido = Pedido::findOrFail($id);
        $pedido->detalles()->delete();

        $total = 0;
        foreach ($data['items'] as $item) {
            $producto = Producto::findOrFail($item['id']);
            $subtotal = $producto->precio * $item['cantidad'];

            // Creamos un nuevo DetallePedido
            $detalle = DetallePedido::create([
                'pedido_id' => $pedido->id,
                'producto_id' => $producto->id,
                'fecha_hora' => now(),
                'cantidad' => $item['cantidad'],
                'subtotal' => $subtotal,
            ]);

            $total += $subtotal;

            // ADJUNTAR los ingredientes quitados sobre el $detalle, NO sobre $pedido
            if (!empty($item['quitados'])) {
                // Asegúrate de que DetallePedido tenga la relación ingredientesQuitados()
                $detalle->ingredientesQuitados()->attach($item['quitados']);
            }
        }

        $pedido->update([
            'total' => $total,
            'metodo_pago' => $data['metodo_pago']
        ]);
        $pedido->pago()->update([
            'monto' => $total,
            'tipo' => $data['metodo_pago'],
            //'estado' => $data['metodo_pago'] === 'Efectivo' ? 'Completado' : 'Pendiente',
        ]);

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $pedido = Pedido::with('detalles.producto')->findOrFail($id);

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

        if (!$ultimo) {
            return response()->json([
                'id' => 0,
                'created_at' => null,
            ]);
        }

        return response()->json([
            'id' => $ultimo->id,
            'created_at' => $ultimo->created_at->toIso8601String(),
        ]);
    }

    /**
     * Cambia el estado de un pago.
     */
    public function cambiarPagoEstado(Request $request, Pago $pago)
    {
        $data = $request->validate([
            'estado' => 'required|in:Completado,Pendiente,Fallido'
        ]);

        $pago->estado = $data['estado'];
        $pago->save();

        return response()->json(['success' => true]);
    }

    /**
     * Muestra el comprobante printable con código de barras.
     */
    public function comprobante(Pedido $pedido)
    {
        // Cargamos el pedido junto con su PuntoPedido y el Cliente asociado:
        $pedido = Pedido::with([
            'puntoPedido.cliente'
        ])->findOrFail($pedido->id);

        \Log::debug('DEBUG en comprobante():');
        \Log::debug('  pedido->id = ' . $pedido->id);
        \Log::debug('  existe puntoPedido? ' . ($pedido->puntoPedido ? 'sí' : 'no'));
        if ($pedido->puntoPedido) {
            \Log::debug('  puntoPedido->cantidad = ' . $pedido->puntoPedido->cantidad);
            \Log::debug('  cliente asociado? ' . ($pedido->puntoPedido->cliente ? 'sí' : 'no'));
            if ($pedido->puntoPedido->cliente) {
                \Log::debug('    cliente->id = ' . $pedido->puntoPedido->cliente->id);
                \Log::debug('    cliente->puntos = ' . $pedido->puntoPedido->cliente->puntos);
            }
        }


        // Ahora, $pedido->puntoPedido puede ser null si no se asoció cliente.
        // Si existe, $pedido->puntoPedido->cliente es el modelo Cliente.

        return view('Caja.comprobante', compact('pedido'));
    }
}
