<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeAbonnement extends Model
{
    use HasFactory;
    protected $table = 'demande_abonnements';
    protected $fillable = [
        'date_reception',
        'date_cmd',
        'code_query',
        'status'
    ];

    public function gare()
    {
        return $this->belongsTo(Gare::class);
    }
}
