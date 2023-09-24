<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;


class Categorie extends Model implements TranslatableContract

{
    use HasFactory;
    use Translatable;
    protected $table = 'categories';
    protected $fillable = [
        'klm_debut',
        'klm_fin',
        'active'
    ];
    public $translatedAttributes = ['name', 'description'];
    public function Ligne()
    {
        return $this->belongsTo(Ligne::class);
    }
}
