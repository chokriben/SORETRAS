<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Visiteur extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $table = 'visiteurs';
    protected $fillable = [
        'active',
        'identifiant_ministere',
        'date_naissance',
        'num_telephone',
        'photo_url',
        'email',
        'cin',
        'date_emission',
        'cin_parent'
    ];
    public $translatedAttributes = ['nom', 'prenom','nom_parent', 'prenom_parent','adresse', 'classe'];
    /*----------------- One To MAny ------------------*/
    public function Etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }
    public function TypeAbonne()
    {
        return $this->belongsTo(TypeAbonne::class);
    }

    /*----------------- Porteuses ------------------*/
    public function Abonnement()
    {
        return $this->hasMany(Abonnement::class);
    }
}
