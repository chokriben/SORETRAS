<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class TypeAbonne extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $table = 'type_abonnes';
    protected $fillable = [
        
        'active',
    ];
    public $translatedAttributes = ['labelle'];
    public function visiteur()
    {
        return $this->hasMany(Visiteur::class);
    }
}
