<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Affectation extends Model
{
     protected $fillable = [
        'id_courrier',
        'id_affecte_a_utilisateur',
        'id_affecte_par_utilisateur',
        'statut_affectation',
        'Instruction',
        'date_affectation',
        'traite',
        'actions'
    ];

    public function courrier()
    {
        return $this->belongsTo(Courrier::class, 'id_courrier');
    }

    public function affecteA()
    {
        return $this->belongsTo(User::class, 'id_affecte_a_utilisateur');
    }

    public function affectePar()
    {
        return $this->belongsTo(User::class, 'id_affecte_par_utilisateur');
    }
    public function traitements()
    {
        return $this->hasMany(Traitement::class, 'id_affectation');
    }
}
