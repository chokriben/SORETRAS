<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abonnement extends Model
{
    use HasFactory;
    protected $table = 'abonnements';
    protected $fillable = [
        'reference',
        'cin',// a ajouter
        'code_a_bare',
        'date_debut',
        'date_fin',
        'tarif',
        'is_vdf',
        'is_free',
        'date_reception',
        'status',
    ];
    /*----------------- One To MAny ------------------*/
    public function visiteur()
    {
        return $this->belongsTo(Visiteur::class);
    }
    public function impression()
    {
        return $this->belongsTo(Impression::class);
    }
    public function circuit()
    {
        return $this->belongsTo(Circuit::class);
    }
    public function typeAbonnement()
    {
        return $this->belongsTo(TypeAbonnement::class);
    }
    public function dureeAbonnement()
    {
        return $this->belongsTo(DureeAbonnement::class);
    }
    public function ligne()
    {
        return $this->belongsTo(Ligne::class);
    }
}
