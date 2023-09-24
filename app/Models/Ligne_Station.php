<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ligne_Station extends Model
{
    use HasFactory;
    protected $table = 'ligne_station';
    protected $fillable = [
        'ligne_id',
        'station_id'
        
    ];
    public function Ligne_Station()
    {
        return $this->hasMany(Ligne_Station::class);
    }
}
