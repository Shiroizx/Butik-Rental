<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_id',
        'clothes_id',
        'clothes_size_id',
        'jumlah',
        'harga_satuan',
        'subtotal'
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function cloth()
    {
        return $this->belongsTo(Cloth::class, 'clothes_id');
    }

    public function clothesSize()
    {
        return $this->belongsTo(ClothesSize::class, 'clothes_size_id');
    }
}
