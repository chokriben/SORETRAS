<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
class Gouvernorat extends Model  implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $table = 'gouvernorats';
    protected $fillable = [
        
        'active',
    ];
    public $translatedAttributes = ['name', 'description'];
    public function ville()
    {
        return $this->hasMany(Ville::class);
    }
}
