<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalReturn extends Model
{
    use HasFactory;

    protected $table = 'returns'; // Explicitly define table name since class name is different

    protected $fillable = [
        'rental_id',
        'rental_detail_id',
        'tanggal_kembali_aktual',
        'kondisi_baju',
        'catatan_kondisi',
        'diterima_oleh',
    ];

    protected $casts = [
        'tanggal_kembali_aktual' => 'date',
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function detail()
    {
        return $this->belongsTo(RentalDetail::class, 'rental_detail_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'diterima_oleh');
    }
}
