<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeEtablissementTranslation extends Model
{
    use HasFactory;
    public $fillable = ['labelle'];
    
    public $timestamps = false;
}

