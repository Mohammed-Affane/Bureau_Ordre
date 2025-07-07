<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourrierDestinataire extends Model
{
    protected $fillable = ['id_courrier','entite_id', 'nom_ext', 'type_source_ext', 'adresse_ext','type_courrier'];

    public function courrier()
    {
        return $this->belongsTo(Courrier::class, 'courrier_id');
    }
}
