<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Ligne extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $table = 'lignes';
    protected $fillable = [
        'active',
    ];
    public $translatedAttributes = ['name'];
    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }
    public function abonnement()
    {
        return $this->hasMany(Abonnement::class);
    }
    public function station()
    {
        return $this->belongsToMany(station::class);
    }
}