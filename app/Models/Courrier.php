<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courrier extends Model
{

     protected $fillable = [
        'reference_arrive', 'reference_BO','reference_visa','reference_dec' ,'reference_depart',
        'type_courrier','objet','date_reception','date_enregistrement','Nbr_piece',
        'priorite','id_expediteur','id_agent_en_charge','fichier_scan','date_depart','statut','id_entite_a',
        'id_entite_par','date_depart','is_interne'
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
    public function entiteDestinataire()
    {
        return $this->belongsTo(Entite::class, 'id_entite_a');
    }

    public function entiteExpediteur()
    {
        return $this->belongsTo(Entite::class, 'id_entite_par');
    }

// Affectations liées à ce courrier
    public function affectations()
    {
        return $this->hasMany(Affectation::class, 'id_courrier');
    }
    

}
