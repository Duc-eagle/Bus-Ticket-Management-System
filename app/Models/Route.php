<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $table = 'routes';
    protected $primaryKey = 'id';
    protected $fillable = ['route_name', 'departure_location', 'arrival_location', 'distance', 'estimate_time', 'image_path'];

    public function departureStation()
    {
        return $this->belongsTo(BusStation::class, 'departure_location');
    }

    public function arrivalStation()
    {
        return $this->belongsTo(BusStation::class, 'arrival_location');
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
}
