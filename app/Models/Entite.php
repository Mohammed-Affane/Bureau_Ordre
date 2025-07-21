<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Entite extends Model
{
    protected $fillable = ['nom', 'type', 'code', 'parent_id', 'responsable_id'];

//  Chef de cette entité
    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }
    
//  Entité mère 
    public function parent()
    {
        return $this->belongsTo(Entite::class, 'parent_id');
    }
//  Entités filles
    public function enfants()
    {
        return $this->hasMany(Entite::class, 'parent_id');
    }
    public function expediteurCourriers()
    {
        return $this->hasMany(CourrierExpediteur::class);
    }

    public function destinataireCourriers()
    {
        return $this->hasMany(CourrierDestinataire::class);
    }
}
