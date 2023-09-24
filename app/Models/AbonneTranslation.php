<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbonneTranslation extends Model
{
    use HasFactory;
    public $fillable = [ 'prenom','nom_parent','adresse', 'classe'];
   
    public $timestamps = false;
}
