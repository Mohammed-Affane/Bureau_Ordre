<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class Courrier extends Model
{

     protected $fillable = [
        'reference_arrive', 'reference_bo','reference_visa','reference_dec' ,'reference_depart',
        'type_courrier','objet','date_reception','date_enregistrement','Nbr_piece',
        'priorite','id_expediteur','id_agent_en_charge','fichier_scan','statut',
        'date_depart','entite_id'
    ];
    // app/Models/Courrier.php

protected $casts = [
    'date_reception' => 'datetime',
    'date_enregistrement' => 'datetime',
    'date_depart' => 'datetime',
    // other casts...
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


// Affectations liées à ce courrier
    public function affectations()
    {
        return $this->hasMany(Affectation::class, 'id_courrier', 'id');
    }
    //relation entre entite et courrier

    public function entiteExpediteur()
    {
        return $this->belongsTo(Entite::class, 'entite_id');
    }

    // relation pivot 
    public function courrierDestinatairePivot()
    {
        return $this->belongsToMany(CourrierDestinataire::class, 'courrier_destinataire_pivot', 'id_courrier', 'id_destinataire_courrier');
    }


    

}
