<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
protected $table = 'stocks';

    protected $fillable = [
        'code_debut',
        'code_fin',
        'code_actuel',
    ];

    // Définition de la relation avec le modèle User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
