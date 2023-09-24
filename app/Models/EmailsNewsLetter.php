<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailsNewsletter extends Model
{
    use HasFactory;
    protected $table = 'emailsnewsletters';
    protected $fillable = [
        'email',
        'status'
    ];
}