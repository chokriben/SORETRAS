<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StationTranslation extends Model
{
    use HasFactory;
    public $fillable = ['name', 'description'];
    public $timestamps = false;
}
