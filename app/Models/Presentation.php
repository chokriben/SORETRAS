<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Presentation extends Model  implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $table = 'presentations';
    protected $fillable = [
        
        'active'
    ];
    public $translatedAttributes = ['name', 'description'];
    public function medias()
    {
        return $this->morphTo();
    }
   
}
