<?php

namespace App\Http\Controllers;

use App\Events\PedidoEstadoActualizado;
use App\Models\Pedido;
use Illuminate\Http\Request;

class CocinaController extends Controller
{
    public function index()
    {
        // Traigo todos los pedidos en preparación, con sus detalles y productos
        $pedidos = Pedido::with([
            'detalles.producto',
            'detalles.ingredientesQuitados'
        ])
            ->where('estado', 'En Preparacion')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('Cocina.dashboard', compact('pedidos'));
    }

    public function marcarListo(Request $request, Pedido $pedido)
    {
        $pedido->estado = 'Listo';
        $pedido->save();

        event(new PedidoEstadoActualizado($pedido));

        return response()->json([
            'success' => true,
            'id' => $pedido->id,
        ]);
    }

    // app/Http/Controllers/CocinaController.php

    public function nuevos(Request $request)
    {
        // Leer el order (por defecto 'asc'), y asegurar que sea 'asc' o 'desc'
        $order = $request->get('order', 'asc') === 'desc' ? 'desc' : 'asc';

        $pedidos = Pedido::with(['detalles.producto', 'detalles.ingredientesQuitados'])
            ->where('estado', 'En Preparacion')
            ->orderBy('created_at', $order)
            ->get();

        return response()->json($pedidos);
    }

    public function show(Pedido $pedido)
    {
        $pedido->load('detalles.producto', 'detalles.ingredientesQuitados');
        return response()->json($pedido);
    }


}
