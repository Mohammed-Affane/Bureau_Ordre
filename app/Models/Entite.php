<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entite extends Model
{
    protected $fillable = ['nom', 'type', 'code', 'parent_id', 'responsable_id'];
 //  Tous les utilisateurs membres (via table utilisateur_entites)
    public function utilisateurs()
    {
        return $this->belongsToMany(Utilisateur::class, 'utilisateur_entite', 'id_entite', 'id_utilisateur');
    }
//  Chef de cette entité
    public function responsable()
    {
        return $this->belongsTo(Utilisateur::class, 'responsable_id');
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
}
