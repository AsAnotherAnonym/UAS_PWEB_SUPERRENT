<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LokasiRental extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lokasi_rental';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_cabang',
        'alamat',
        'deskripsi',
        'latitude',
        'longitude',
        'foto_path',
    ];

    /**
     * Relasi ke UnitMotor
     */
    public function unitMotor()
    {
        return $this->hasMany(UnitMotor::class, 'lokasi_rental_id');
    }

    /**
     * Relasi ke Karyawan (users dengan role karyawan)
     */
    public function karyawan()
    {
        return $this->hasMany(User::class, 'lokasi_rental_id')->where('role', 'karyawan');
    }

    /**
     * Relasi ke Penyewaan
     */
    public function penyewaan()
    {
        return $this->hasMany(Penyewaan::class, 'lokasi_rental_id');
    }

    /**
     * Relasi ke Reviews
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'lokasi_rental_id');
    }
}