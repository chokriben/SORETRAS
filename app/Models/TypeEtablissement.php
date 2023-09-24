<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class TypeEtablissement extends Model
{
    use HasFactory;
    use Translatable;
    protected $table = 'type_etablissements';
    protected $translationForeignKey = 't_etablissement_id';
    protected $fillable = [
        'active',
    ];
    public $translatedAttributes = ['labelle'];
    public function etablissement()
    {
        return $this->hasMany(Etablissement::class);
    }
}
