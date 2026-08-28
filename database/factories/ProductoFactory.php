<?php


namespace Database\Factories;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition(): array
    {
        return [
            'nombre_producto' => $this->faker->randomElement([
                'Coca-Cola Original',
                'Pepsi Original',
                'Agua Mineral',
                'Jugo de Naranja',
                'Papas Fritas',
                'Galletas de Chocolate',
                'Chocolate con Leche',
                'Cafe Instantaneo',
                'Te Helado',
                'Bebida Energetica',
            ]),
            'descripcion' => $this->faker->paragraph(),
            'precio_unitario' => $this->faker->randomFloat(2, 10, 500),
            'stock' => $this->faker->randomNumber(2),
            'iva_porcentaje'=> $this->faker->randomFloat(2, 4, 2), 
            'imagen' => 'https://picsum.photos/seed/' . $this->faker->uuid() . '/500/300', 
        ];
    }
}
