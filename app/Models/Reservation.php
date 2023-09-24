<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Reservation extends Model 
{
    use HasFactory;
 
    protected $table = 'reservations';
    protected $fillable = [
    'cin',
    'nom',
    'email',
    'date_reservation',
    'depart',
    'destination'
    ];
    
    
    public function mediatable()
    {
        return $this->morphTo();
    }
}

