<?php

namespace App\Models;

use App\Models\Affectation;
use Illuminate\Database\Eloquent\Model;

class Traitement extends Model
{
    protected $fillable = ['id_affectation', 'action', 'commentaire', 'date_traitement'];

    public function affectation()
    {
        return $this->belongsTo(Affectation::class, 'id_affectation');
    }
}
