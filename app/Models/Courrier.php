<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courrier extends Model
{

     protected $fillable = [
        'reference_arrive',
        'reference_BO',
        'type_courrier',
        'objet',
        'date_reception',
        'date_enregistrement',
        'Nbr_piece',
        'priorite',
        'id_expediteur',
        'id_agent_en_charge',
    ];
    
// Expéditeur externe
    public function expediteur()
    {
        return $this->belongsTo(Expediteur::class, 'id_expediteur');
    }
// Agent en charge du courrier
    public function agent()
    {
        return $this->belongsTo(User::class, 'id_agent_en_charge');
    }
// Liste des destinataires (pour courrier depart)
    public function destinataires()
    {
        return $this->hasMany(CourrierDestinataire::class, 'courrier_id');
    }
// Affectations liées à ce courrier
    public function affectations()
    {
        return $this->hasMany(Affectation::class, 'id_courrier');
    }
// Traitements liés à ce courrier
    public function traitements()
    {
        return $this->hasMany(Traitement::class, 'id_courrier');
    }
// Courriers auxquels celui-ci répond
    public function referencesSources()
    {
        return $this->hasMany(CourrierReference::class, 'id_courrier_cible');
    }
// Courriers qui sont des réponses à celui-ci
    public function referencesReponses()
    {
        return $this->hasMany(CourrierReference::class, 'id_courrier_source');
    }
}
