<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Traitement extends Model
{
    protected $fillable = ['id_courrier', 'id_utilisateur', 'action', 'commentaire', 'date_action'];

    public function courrier()
    {
        return $this->belongsTo(Courrier::class, 'id_courrier');
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur');
    }
}
