<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $table = 'trips';
    protected $primaryKey = 'id';
    protected $fillable = ['trip_name', 'bus_id', 'route_id', 'trip_date', 'departure_time', 'arrival_time', 'base_price', 'status'];

    public function setBasePriceAttribute($value)
    {
        if (is_string($value)) {
            $value = str_replace(',', '', $value);
        }
        $this->attributes['base_price'] = $value;
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
