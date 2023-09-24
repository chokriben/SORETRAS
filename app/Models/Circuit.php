<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Circuit extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $table = 'circuits';
    protected $fillable = [
        'active',
        

    ];
    public $translatedAttributes = ['name', 'description'];
    public function ligne()
    {
        return $this->hasMany(ligne::class);
    }
    
}
