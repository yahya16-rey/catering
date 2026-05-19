<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_menu',
        'kategori',
        'deskripsi',
        'harga',
        'gambar',
        'rating',
        'stok',
        'kalori',
        'protein',
        'is_available'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
