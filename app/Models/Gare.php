<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Gare extends Model  implements TranslatableContract
{
    use HasFactory;
    protected $table = 'gares';
    use Translatable;
    protected $fillable = [
        'active',
        'latitude',
        'longitude'

    ];
    public $translatedAttributes = ['name', 'description'];
    public function demandeAbonnement()
    {
        return $this->hasMany(DemandeAbonnement::class);
    }
    public function ville()
    {
        return $this->belongsTo(Ville::class);
    }
}