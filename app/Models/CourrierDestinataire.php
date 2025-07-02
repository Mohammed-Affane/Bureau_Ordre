<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourrierDestinataire extends Model
{
    protected $fillable = ['id_courrier', 'nom', 'type_source', 'adresse'];

    public function courrier()
    {
        return $this->belongsTo(Courrier::class, 'courrier_id');
    }
}
