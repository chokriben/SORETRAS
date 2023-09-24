<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Abonne extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $table = 'abonnes';
    protected $fillable = [
        'active',
        'etat',
        'prix',
        'date_naissance',
        'num_telephone',
        'email',
        'type_periode',
        'type_zone',
        'type_validite',
        'type_eleve',
        'type_inscrit',
        'type_institut',
        'type_paiment',
        'type_abonne',
        'cin',
        'date_emission',
        'date_debut',
        'date_fin',
        'id_uniq',
        'code',
        'src',
        'order_id'
    ];
    public $translatedAttributes = ['prenom', 'nom_parent', 'adresse', 'profession', 'classe'];
    /*----------------- One To MAny ------------------*/
    public function Etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }
    public function Ligne()
    {
        return $this->belongsTo(Ligne::class);
    }
}
