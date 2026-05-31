<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    protected $table = 'buses';
    protected $primaryKey = 'id';
    protected $fillable = ['license_plate', 'bus_name', 'total_seats', 'has_beds'];

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function images()
    {
        return $this->hasMany(BusImage::class);
    }
}
