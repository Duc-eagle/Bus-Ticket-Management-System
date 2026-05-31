<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $table = 'provinces';
    protected $primaryKey = 'id';
    protected $fillable = ['province_name', 'image_path'];

    public function busStations()
    {
        return $this->hasMany(BusStation::class);
    }
}
