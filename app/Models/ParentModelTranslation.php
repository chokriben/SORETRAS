<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentModelTranslation extends Model
{
    use HasFactory;
    public $fillable = ['name', 'prenom'];
   
    public $timestamps = false;
}
