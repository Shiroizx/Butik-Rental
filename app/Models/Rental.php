<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_transaksi',
        'customer_id',
        'employee_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'total_biaya',
        'deposit',
        'status', // aktif, selesai, telat
        'catatan'
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function details()
    {
        return $this->hasMany(RentalDetail::class);
    }

    public function returns()
    {
        return $this->hasOne(Returns::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
