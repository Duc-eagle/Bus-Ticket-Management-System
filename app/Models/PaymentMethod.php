<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'payment_methods';
    protected $primaryKey = 'id';
    protected $fillable = ['method_name' , 'method_description'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
