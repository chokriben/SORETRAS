<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Securite extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $table = 'securites';
    protected $fillable = [
        'active',
        'libelle'
    ];
    public $translatedAttributes = ['name', 'description'];
    /*----------------- Morph ------------------*/
    public function medias()
    {
        return $this->morphMany(Media::class, 'mediatable');
    }
}
