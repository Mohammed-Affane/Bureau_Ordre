<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles,SoftDeletes;

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

  // 1 utilisateur peut avoir plusieurs courriers à sa charge
    public function courriersEnCharges()
    {
        return $this->hasMany(Courrier::class, 'id_agent_en_charge');
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

     public function entite()
    {
        return $this->hasOne(Entite::class, 'responsable_id');
    }

    
}
