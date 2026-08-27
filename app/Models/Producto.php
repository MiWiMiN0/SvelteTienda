<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    
    use HasFactory;

    
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'imagen',
    ];

    public $timestamps = false;

    public function facturas()
    {
        return $this->belongsToMany(Factura::class, 'detalle_facturas', 'producto_id', 'factura_id')
            ->withPivot('detalle_id', 'cantidad', 'precio_venta', 'subtotal_linea');
    }

    public function getImagenUrlAttribute()
    {
        $path = $this->imagen_path ?: 'productos/default.png';
        return Storage::disk('public')->url($path);
    }

    protected $appends = ['imagen_url'];

}
