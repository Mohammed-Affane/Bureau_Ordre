<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Entite extends Model
{
    protected $fillable = ['nom', 'type', 'code', 'parent_id', 'responsable_id'];
 //  Tous les utilisateurs membres (via table utilisateur_entites)
    public function utilisateurs()
    {
        return $this->belongsToMany(User::class, 'utilisateur_entite', 'id_entite', 'id_utilisateur');
    }
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
}
