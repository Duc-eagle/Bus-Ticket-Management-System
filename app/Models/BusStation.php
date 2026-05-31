<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusStation extends Model
{
    use HasFactory;

    protected $table = 'bus_stations';
    protected $primaryKey = 'id';
    protected $fillable = ['province_id', 'station_name', 'address', 'phone'];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function departureRoutes()
    {
        return $this->hasMany(Route::class, 'departure_location');
    }

    public function arrivalRoutes()
    {
        return $this->hasMany(Route::class, 'arrival_location');
    }
}
