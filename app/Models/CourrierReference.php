<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourrierReference extends Model
{
    protected $fillable = ['id_courrier_source', 'id_courrier_cible'];

    public function source()
    {
        return $this->belongsTo(Courrier::class, 'id_courrier_source');
    }

    public function cible()
    {
        return $this->belongsTo(Courrier::class, 'id_courrier_cible');
    }
}
