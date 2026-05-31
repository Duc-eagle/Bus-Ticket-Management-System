<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $table = 'seats';
    protected $primaryKey = 'id';
    protected $fillable = ['bus_id', 'seat_code', 'status'];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
