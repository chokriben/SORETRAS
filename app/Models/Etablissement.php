<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
class Etablissement extends  Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $table = 'etablissements';
    protected $fillable = [
        'active',
        'is_prive'

    ];
    public $translatedAttributes = ['labelle'];
    // public function abonne()
    // {
    //     return $this->hasMany(Abonne::class);
    // }
    public function TypeEtablissement()
    {
        return $this->belongsTo(TypeEtablissement::class);
    }
}
