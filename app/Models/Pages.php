<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Pages extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $table = 'pages';
    protected $fillable = [
        'active', 'slug'
    ];
    public $translatedAttributes = ['name', 'description'];
    /*----------------- Morph ------------------*/
    public function medias()
    {
        return $this->morphMany(Media::class, 'mediatable');
    }
}
