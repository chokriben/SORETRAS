<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
class Station extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $table = 'stations';
    protected $fillable = [
        'code',
        'latitude',
        'longitude',
        'active',

    ];
    public $translatedAttributes = ['name', 'description'];
    public function ville()
    {
        return $this->belongsTo(Ville::class);
    }
    public function ligne()
    {
        return $this->belongsToMany(Ligne::class);
    }
  
    public function medias()
    {
        return $this->morphMany(Media::class, 'mediatable');
    }
   
}
