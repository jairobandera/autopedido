<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Producto;
use App\Models\Pago;
use App\Models\Cliente;
use App\Models\PuntoPedido;
use App\Models\ReglaPunto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

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
                'cliente_id' => $data['cliente_id'] ?? null,
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
        $pedido = Pedido::with([
            'detalles.producto',
            'detalles.ingredientesQuitados'
        ])->findOrFail($id);

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

        // Si acaban de marcarlo como "Entregado", generamos puntos
        if ($data['estado'] === 'Entregado' && $pedido->cliente_id) {
            Log::debug("Pedido {$pedido->id}: cambiando a Entregado; total = {$pedido->total}; cliente_id = {$pedido->cliente_id}");

            // 1) Evitar duplicar puntos
            $existe = PuntoPedido::where('pedido_id', $pedido->id)->exists();
            Log::debug("Pedido {$pedido->id}: existe PuntoPedido? " . ($existe ? 'sí' : 'no'));

            if (!$existe) {
                // 2) Buscar tramo
                $total = (float) $pedido->total;
                $regla = ReglaPunto::where('monto_min', '<=', $total)
                    ->where('monto_max', '>=', $total)
                    ->first();

                if (!$regla) {
                    Log::debug("Pedido {$pedido->id}: no se encontró tramo para total {$total}");
                } else {
                    Log::debug("Pedido {$pedido->id}: tramo encontrado id={$regla->id} rango={$regla->monto_min}-{$regla->monto_max}, puntos_base={$regla->puntos_base}");
                }

                // 3) Determinar puntos
                $puntosAGenerar = $regla ? $regla->puntos_base : 1;
                Log::debug("Pedido {$pedido->id}: puntos a generar = {$puntosAGenerar}");

                // 4) Crear registro de puntos
                PuntoPedido::create([
                    'cliente_id' => $pedido->cliente_id,
                    'pedido_id' => $pedido->id,
                    'cantidad' => $puntosAGenerar,
                    'tipo' => 'Canjeo',
                    'fecha' => now(),
                ]);
                Log::debug("Pedido {$pedido->id}: PuntoPedido creado");

                // 5) Actualizar puntos en cliente
                $cliente = Cliente::find($pedido->cliente_id);
                if ($cliente) {
                    $cliente->increment('puntos', $puntosAGenerar);
                    Log::debug("Cliente {$cliente->id}: puntos actualizados a {$cliente->puntos}");
                } else {
                    Log::debug("Cliente {$pedido->cliente_id} no encontrado");
                }
            }
        }

        return response()->json([
            'success' => true,
            'nuevo_estado' => $pedido->estado,
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
            'cliente_id' => 'nullable|exists:clientes,id'
        ]);

        $pedido = Pedido::findOrFail($id);
        $pedido->detalles()->delete();

        // Si recibes 'cliente_id', lo asociamos:
        if (isset($data['cliente_id'])) {
            $pedido->cliente_id = $data['cliente_id'];
        }

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
            'cliente',       // <-- cliente directo (basado en cliente_id)
            'puntoPedido'    // <-- relación a PuntoPedido, si ya existe
            // 'detalles.producto'  // opcional, si quieres listar productos en el comprobante
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
