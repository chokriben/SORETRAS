<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
class Media extends Model
{
    use HasFactory;
      public function getImageAttribute($value)
    {
        return Storage::url("medias/" . $value);
    }
    protected $table = 'medias';
    protected $fillable = [
        'src',
        'nbr_vues',
        'legende',
        'path',
        'datetime',
        'type',
        'foreinkey'
    ];

    public function mediatable()
    {
        return $this->morphTo();
    }
}
