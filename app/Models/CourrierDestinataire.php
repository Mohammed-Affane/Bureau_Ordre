<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourrierDestinataire extends Model
{
    protected $fillable = ['entite_id','CIN', 'nom', 'type_source', 'adresse','type_courrier'];

    // relation pivot 
    public function courrierDestinatairePivot()
    {
        return $this->belongsToMany(Courrier::class, 'courrier_destinataire_pivot', 'id_destinataire_courrier','id_courrier');
    }

    
}
