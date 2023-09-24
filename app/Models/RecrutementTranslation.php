<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecrutementTranslation extends Model
{
    use HasFactory;
    
    public $fillable = ['name', 'description'];
    
    public $timestamps = false;
}
