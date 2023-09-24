<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisiteurTranslation extends Model
{
    use HasFactory;
    public $fillable = ['nom', 'prenom','nom_parent', 'prenom_parent','adresse', 'classe'];
   
    public $timestamps = false;
}
