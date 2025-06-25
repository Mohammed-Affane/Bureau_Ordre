<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourrierDestinataire extends Model
{
    protected $fillable = ['courrier_id', 'nom', 'type_source', 'adresse'];

    public function courrier()
    {
        return $this->belongsTo(Courrier::class, 'courrier_id');
    }
}
