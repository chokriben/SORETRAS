<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Impression extends Model
{
    use HasFactory;

    public function abonnement()
    {
        return $this->belongsTo(Abonnement::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
