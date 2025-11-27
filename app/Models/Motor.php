<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motor extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'motor';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_motor',
        'merk',
        'tipe',
        'cc',
        'deskripsi',
    ];

    /**
     * Relasi ke UnitMotor
     */
    public function unitMotor()
    {
        return $this->hasMany(UnitMotor::class, 'motor_id');
    }
}