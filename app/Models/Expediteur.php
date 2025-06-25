<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expediteur extends Model
{
    protected $fillable = ['nom', 'type_source', 'adresse', 'telephone'];

    public function courriers()
    {
        return $this->hasMany(Courrier::class, 'id_expediteur');
    }
}
