<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Horaire extends Model 
{
    use HasFactory;
  
    protected $table = 'horaires';
    protected $fillable = [
        'active',
        'depart',
        'arrive',
        'h_depart',
        'h_arrive',
        
    ];
    
    /*----------------- One To MAny ------------------*/
    
   
}
