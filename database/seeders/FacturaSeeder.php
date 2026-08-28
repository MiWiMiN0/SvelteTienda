<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Factura;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class FacturaSeeder extends Seeder
{
    public function run(): void
    {
        $clientes = Cliente::all();
        $productos = Producto::all();

        if ($clientes->isEmpty() || $productos->isEmpty()) {
            return;
        }

        $faker = fake();

        foreach ($clientes as $cliente) {
            $facturasPendientes = max(0, 3 - Factura::where('cliente_id', $cliente->cliente_id)->count());

            for ($facturaNumero = 0; $facturaNumero < $facturasPendientes; $facturaNumero++) {
                $productosFactura = $productos->random(min($productos->count(), rand(1, 4)));
                $detalles = [];
                $subtotal = 0;

                foreach ($productosFactura as $producto) {
                    $cantidad = rand(1, 5);
                    $precioVenta = $producto->precio_unitario;
                    $subtotalLinea = round($cantidad * $precioVenta, 2);

                    $detalles[$producto->producto_id] = [
                        'cantidad' => $cantidad,
                        'precio_venta' => $precioVenta,
                        'subtotal_linea' => $subtotalLinea,
                    ];
                    $subtotal += $subtotalLinea;
                }

                $totalIva = round($productosFactura->sum(function ($producto) use ($detalles) {
                    return $detalles[$producto->producto_id]['subtotal_linea'] * ($producto->iva_porcentaje / 100);
                }), 2);

                $factura = Factura::create([
                    'numero_factura' => $faker->unique()->numerify('FAC-########'),
                    'fecha_emision' => $faker->dateTimeBetween('-1 year', 'now'),
                    'cliente_id' => $cliente->cliente_id,
                    'subtotal' => $subtotal,
                    'total_iva' => $totalIva,
                    'total_pagar' => $subtotal + $totalIva,
                    'metodo_pago' => $faker->randomElement([
                        'Efectivo',
                        'Tarjeta',
                        'Transferencia',
                        'Nequi/Daviplata',
                    ]),
                ]);

                $factura->productos()->attach($detalles);
            }
        }
    }
}