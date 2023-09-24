<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Setting extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $table = 'settings';

    protected $fillable = [

        'email',
        'num_tel_p',
        'num_tel_s',
        'fax_p',
        'fax_s',
        'facebook',
        'youtube',
        'twitter',
    ];
    public $translatedAttributes = ['raison_sociale', 'adresse'];
}
