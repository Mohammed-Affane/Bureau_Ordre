<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class Courrier extends Model
{

     protected $fillable = [
        'reference_arrive', 'reference_bo','reference_visa','reference_dec' ,'reference_depart',
        'type_courrier','objet','date_reception','date_enregistrement','Nbr_piece',
        'priorite','id_expediteur','id_agent_en_charge','fichier_scan','statut',
        'date_depart','entite_id','delais'
    ];


protected $casts = [
    'date_reception' => 'datetime',
    'date_enregistrement' => 'datetime',
    'date_depart' => 'datetime',
    'delais' => 'datetime',
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
        return $this->belongsToMany(CourrierDestinataire::class,
         'courrier_destinataire_pivot', 'id_courrier', 'id_destinataire_courrier');
    }
    protected static function booted()
    {
        static::creating(function ($courrier) {
        if (!$courrier->delais) {
            $courrier->delais = Carbon::now()->addDays(60);
        }
        });
    }

public function scopeCourrierByUserRole($query, $user = null)
{
    $user = $user ?: auth()->user();
    if (!$user) {
        return $query; // no user -> no extra filter
    }

    // SG: courriers affectés au SG
    if ($user->hasRole('sg')) {
        return $query->whereHas('affectations', function ($q) {
            $q->where('statut_affectation', 'a_sg')
              ->whereHas('AffecteA', function ($sub) {
                  $sub->whereNull('deleted_at'); // optionnel, si SoftDeletes
              });
        })->where('statut', 'en_traitement');
    }

    // CAB: courriers affectés au CAB
    if ($user->hasRole('cab')) {
        return $query->whereHas('affectations', function ($q) {
            $q->where('statut_affectation', 'a_cab')
              ->whereHas('AffecteA', function ($sub) {
                  $sub->whereNull('deleted_at');
              });
        })->where('statut', 'en_cours');
    }

    // DAI: courriers affectés à la DAI
    if ($user->hasRole('dai')) {
        return $query->whereHas('affectations', function ($q) use ($user) {
            $q->where('statut_affectation', 'a_div')
              ->where('id_affecte_a_utilisateur', $user->id) // doit être affecté à ce user DAI
              ->whereHas('AffecteA', function ($sub) {
                  $sub->whereNull('deleted_at');
              });
        })->where('statut', 'en_traitement');
    }

    // BO: si vous voulez que BO voie tout, ne filtrez pas
    if ($user->hasRole('bo')) {
        return $query;
        // Ou limiter : 
        // return $query->whereHas('affectations', fn($q) => $q->where('statut_affectation', 'a_cab'));
    }

    // Chef de division
    if ($user->hasRole('chef_division')) {
        return $query->whereHas('affectations', function ($q) use ($user) {
            $q->where('statut_affectation', 'a_div')
              ->whereHas('AffecteA', function ($sub) use ($user) {
                  $sub->whereHas('entite', function ($sub2) use ($user) {
                      $sub2->where('responsable_id', $user->id);
                  });
              });
        })->where('statut', 'arriver');
    }

    return $query;
}



}
