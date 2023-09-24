<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunicationMediaTranslation extends Model
{
    use HasFactory;
    protected $table = 'c_media_translations';
    public $fillable = ['name', 'description'];

    public $timestamps = false;
}
