<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Promocion;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DetallePedidoSeeder extends Seeder
{
    public function run()
    {
        $pedidos = Pedido::all();
        $productos = Producto::all();
        $promociones = Promocion::all()->keyBy('id');
        // Consultar la tabla pivote producto_promocion directamente
        $productoPromocion = DB::table('producto_promocion')
            ->select('producto_id', 'promocion_id')
            ->get()
            ->groupBy('producto_id');

        if ($pedidos->isEmpty() || $productos->isEmpty()) {
            return;
        }

        foreach ($pedidos as $pedido) {
            $max = min(4, $productos->count());
            $items = $productos->random(rand(1, $max));
            $totalPedido = 0;

            foreach ($items as $producto) {
                $cantidad = rand(1, 3);
                $subtotal = $producto->precio * $cantidad;

                // Aplicar descuentos si el producto tiene promociones
                $descuentoAplicado = 0;
                if (isset($productoPromocion[$producto->id])) {
                    $promocionesProducto = $productoPromocion[$producto->id]
                        ->pluck('promocion_id')
                        ->toArray();
                    foreach ($promocionesProducto as $promocionId) {
                        if (isset($promociones[$promocionId]) && $promociones[$promocionId]->activo) {
                            $promocion = $promociones[$promocionId];
                            $fecha = Carbon::parse($pedido->created_at);
                            $esVigente = (!$promocion->fecha_inicio || $fecha->gte($promocion->fecha_inicio)) &&
                                         (!$promocion->fecha_fin || $fecha->lte($promocion->fecha_fin));

                            if ($esVigente) {
                                // Asumir que descuentos >= 100 son fijos, menores son porcentuales
                                if ($promocion->descuento >= 100) {
                                    $descuentoAplicado += min((float)$promocion->descuento, $subtotal);
                                } else {
                                    $descuentoAplicado += ($promocion->descuento / 100) * $subtotal;
                                }
                            }
                        }
                    }
                }

                $subtotal -= $descuentoAplicado;
                $subtotal = max(0, $subtotal); // Evitar subtotales negativos

                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $producto->id,
                    'fecha_hora' => $pedido->created_at,
                    'cantidad' => $cantidad,
                    'subtotal' => round($subtotal, 2),
                ]);

                $totalPedido += $subtotal;
            }

            // Actualizar el total del pedido
            $pedido->update(['total' => round($totalPedido, 2)]);
        }
    }
}