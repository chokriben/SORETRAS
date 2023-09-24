<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class AbonnePrix extends Model
{
    use HasFactory;

    protected $table = 'abonnesprix';
    protected $fillable = [
        'active',
        'prix',
        'nom',
        'code',
        'annuel',
        'klm',
    ];

    /*----------------- One To MAny ------------------*/
}
