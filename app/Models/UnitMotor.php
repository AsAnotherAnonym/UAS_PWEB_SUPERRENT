<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitMotor extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'unit_motor';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'motor_id',
        'lokasi_rental_id',
        'plat_nomor',
        'status',
        'foto_path',
    ];

    /**
     * Relasi ke Motor
     */
    public function motor()
    {
        return $this->belongsTo(Motor::class, 'motor_id');
    }

    /**
     * Relasi ke LokasiRental
     */
    public function lokasiRental()
    {
        return $this->belongsTo(LokasiRental::class, 'lokasi_rental_id');
    }

    /**
     * Relasi ke Penyewaan
     */
    public function penyewaan()
    {
        return $this->hasMany(Penyewaan::class, 'unit_motor_id');
    }

    /**
     * Check if unit is available
     */
    public function isTersedia()
    {
        return $this->status === 'tersedia';
    }

    /**
     * Check if unit is rented
     */
    public function isDisewa()
    {
        return $this->status === 'disewa';
    }

    /**
     * Check if unit is in maintenance
     */
    public function isMaintenance()
    {
        return $this->status === 'maintenance';
    }
}