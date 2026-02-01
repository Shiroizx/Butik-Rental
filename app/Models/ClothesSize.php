<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClothesSize extends Model
{
    use HasFactory;

    protected $fillable = [
        'clothes_id',
        'ukuran',
        'stok'
    ];

    public function cloth()
    {
        return $this->belongsTo(Cloth::class, 'clothes_id');
    }
}
