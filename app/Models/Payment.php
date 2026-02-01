<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_id',
        'metode_bayar',
        'jumlah_bayar',
        'status_bayar',
        'tanggal_bayar',
        'catatan'
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
}
