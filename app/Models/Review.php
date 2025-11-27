<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reviews';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'lokasi_rental_id',
        'kategori',
        'rating',
        'review',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke LokasiRental
     */
    public function lokasiRental()
    {
        return $this->belongsTo(LokasiRental::class, 'lokasi_rental_id');
    }

    /**
     * Get available kategori options
     */
    public static function getKategoriOptions()
    {
        return [
            'tempat sewa',
            'motor',
            'website',
            'lainnya',
        ];
    }
}