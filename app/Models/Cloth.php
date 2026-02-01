<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cloth extends Model
{
    use HasFactory;

    protected $table = 'clothes'; // Explicitly define table name just in case

    protected $fillable = [
        'nama_baju',
        'category_id',
        'warna',
        'harga_sewa',
        'gambar',
        'deskripsi',
        'is_available'
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sizes()
    {
        return $this->hasMany(ClothesSize::class, 'clothes_id');
    }
}
