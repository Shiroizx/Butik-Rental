<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_id',
        'return_id',
        'jenis_denda',
        'jumlah_denda',
        'deskripsi',
        'status_denda',
        'tanggal_bayar_denda'
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
}
