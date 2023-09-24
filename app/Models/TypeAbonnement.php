<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class TypeAbonnement extends Model
{
    use HasFactory;
    use Translatable;
    protected $table = 'type_abonnements';
    protected $fillable = [
        'active',

    ];
    public $translatedAttributes = ['labelle'];
    public function abonnement()
    {
        return $this->hasMany(Abonnement::class);
    }
}
