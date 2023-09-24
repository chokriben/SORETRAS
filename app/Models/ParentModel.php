<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class ParentModel extends Model implements TranslatableContract
{
    use HasFactory;
    
    use Translatable;
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'parents';
    protected $fillable = [
        'active',
        
        'jour_naissance',
        'num_telephone',
        'email',
        'cin',
        'mois_naissance',
        'annee_naissance',
        'password',
        'code'
        
    ];
    public $translatedAttributes = ['name', 'prenom'];
    
    
}
