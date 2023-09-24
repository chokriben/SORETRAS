<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Gallerie extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $table = 'galleries';


    protected $fillable = [
        'active',
        'file',
    ];
    public $translatedAttributes = ['name', 'description'];

    /*----------------- Morph ------------------*/
    public function medias()
    {
        return $this->morphMany(Media::class, 'mediatable');
    }
}
