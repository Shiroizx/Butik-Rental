<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Returns extends Model
{
    use HasFactory;
    
    // Explicitly define table because 'returns' is a reserved word in PHP/SQL sometimes, but Laravel handles plural fine. 
    // However, class name 'Returns' might conflict with keywords if not careful. Let's use 'RentalReturn' to be safe? 
    // No, user asked for 'Returns' table. Model name 'ReturnModel' or just 'Returns'. 
    // Let's use 'Returns' as class name.
    
    protected $table = 'returns';

    protected $fillable = [
        'rental_id',
        'rental_detail_id',
        'tanggal_kembali_aktual',
        'kondisi_baju',
        'catatan_kondisi',
        'diterima_oleh'
    ];

    protected $casts = [
        'tanggal_kembali_aktual' => 'date',
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function fine()
    {
        return $this->hasOne(Fine::class, 'return_id');
    }
}
