<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    

    // ---------------------------------Relations-------------------


     //  Un utilisateur peut appartenir à plusieurs entités (via table pivot utilisateur_entites)
    public function entites()
    {
        return $this->belongsToMany(Entite::class, 'utilisateur_entite', 'id_utilisateur', 'id_entite');
    }

//  Un utilisateur peut être responsable de plusieurs entités (division/service)
    public function entitesResponsables()
    {
        return $this->hasMany(Entite::class, 'responsable_id');
    }

  // 1 utilisateur peut avoir plusieurs courriers à sa charge
    public function courriersEnCharges()
    {
        return $this->hasMany(Courrier::class, 'id_agent_en_charge');
    }
//1 utilisateur peut effectuer plusieurs traitements
    public function traitements()
    {
        return $this->hasMany(Traitement::class, 'id_utilisateur');
    }
//Affectations créées par l'utilisateur
    public function affectationsDonnees()
    {
        return $this->hasMany(Affectation::class, 'id_affecte_par_utilisateur');
    }
//  Affectations reçues par l'utilisateur
    public function affectationsRecues()
    {
        return $this->hasMany(Affectation::class, 'id_affecte_a_utilisateur');
    }
}
