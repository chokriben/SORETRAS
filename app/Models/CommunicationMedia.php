<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class CommunicationMedia extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;

    protected $table = 'communication_media';
    protected $translationForeignKey = 'c_media_id';

    protected $fillable = [
        'active'
    ];
    public $translatedAttributes = ['name', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*----------------- Morph ------------------*/
    public function medias()
    {
        return $this->morphMany(Media::class, 'mediatable');
    }
}
