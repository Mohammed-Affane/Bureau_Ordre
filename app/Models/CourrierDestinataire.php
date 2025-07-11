<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourrierDestinataire extends Model
{
    protected $fillable = ['nom', 'type_source','CIN','telephone', 'adresse','type_courrier','entite_id'];

    // relation pivot 
    public function courrierDestinatairePivot()
    {
        return $this->belongsToMany(Courrier::class, 'courrier_destinataire_pivot', 'id_destinataire_courrier','id_courrier');
    }
    public function entite()
    {
    return $this->belongsTo(Entite::class, 'entite_id');
    }

    
}
