<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Ville extends Model implements TranslatableContract
{
    use HasFactory;
    protected $table = 'villes';
    use Translatable;
    protected $fillable = [
        'active'
    ];
    public $translatedAttributes = ['name', 'description'];

    public function gouvernorat()
    {
        return $this->belongsTo(Gouvernorat::class);
    }
    public function station()
    {
        return $this->hasMany(Station::class);
    }
}