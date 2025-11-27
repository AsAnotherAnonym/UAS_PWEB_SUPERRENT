<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Penyewaan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'penyewaan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'unit_motor_id',
        'lokasi_rental_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke UnitMotor
     */
    public function unitMotor()
    {
        return $this->belongsTo(UnitMotor::class, 'unit_motor_id');
    }

    /**
     * Relasi ke LokasiRental
     */
    public function lokasiRental()
    {
        return $this->belongsTo(LokasiRental::class, 'lokasi_rental_id');
    }

    /**
     * Check if penyewaan is pending
     */
    public function isPending()
    {
        return $this->status === 'Pending';
    }

    /**
     * Check if penyewaan is accepted
     */
    public function isAccepted()
    {
        return $this->status === 'Accept';
    }

    /**
     * Check if penyewaan is rejected
     */
    public function isTolak()
    {
        return $this->status === 'Tolak';
    }

    /**
     * Get total days of rental
     */
    public function getTotalHari()
    {
        return Carbon::parse($this->tanggal_mulai)->diffInDays(Carbon::parse($this->tanggal_selesai)) + 1;
    }

    /**
     * Check if penyewaan is currently active (accepted and within date range)
     */
    public function isActive()
    {
        $today = Carbon::today();
        return $this->isAccepted() && 
               $today->greaterThanOrEqualTo($this->tanggal_mulai) && 
               $today->lessThanOrEqualTo($this->tanggal_selesai);
    }
}