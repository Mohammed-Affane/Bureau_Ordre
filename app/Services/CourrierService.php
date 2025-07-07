<?php

namespace App\Services;

use App\Models\Courrier;
use App\Models\Expediteur;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
class CourrierService
{
    public function createCourrier(array $data): Courrier
    {
        return DB::transaction(function () use ($data) {
            $expediteur = $this->handleExpediteur($data);
            $courrierData = $this->prepareCourrierData($data, $expediteur->id);
            
            return Courrier::create($courrierData);
        });
    }

    private function handleExpediteur(array $data): Expediteur
    {
        if (!empty($data['expediteur_id'])) {
            return Expediteur::findOrFail($data['expediteur_id']);
        }

        return Expediteur::firstOrCreate(
            ['nom' => trim($data['exp_nom']), 'type_source' => trim($data['exp_type_source'])],
            [
                'adresse' => $data['exp_adresse'] ? trim($data['exp_adresse']) : null,
                'telephone' => $data['exp_telephone'] ? trim($data['exp_telephone']) : null,
                'created_by' => Auth::id(),
            ]
        );
    }

    private function prepareCourrierData(array $data, int $expediteurId): array
    {
        $courrierData = [
            'type_courrier' => $data['type_courrier'],
            'objet' => trim($data['objet']),
            'reference_arrive' => $data['reference_arrive'] ?? null,
            'reference_BO' => $data['reference_BO']?? null,
            'reference_visa' => $data['reference_visa']?? null,
            'reference_dec' => $data['reference_dec']?? null,
            'reference_depart' => $data['reference_depart']?? null,
            'date_reception' => $data['date_reception'] ?? null,
            'date_enregistrement' => $data['date_enregistrement'],
            'Nbr_piece' => $data['Nbr_piece'],
            'priorite' => $data['priorite'] ?? 'normale',
            'id_expediteur' => $expediteurId,
            'id_agent_en_charge' => $data['id_agent_en_charge'] ?? null,
            'id_entite_par' => $data['id_entite_par'] ?? null,
            'id_entite_a' => $data['id_entite_a'] ?? null,
            'created_by' => auth()->id(),
            'status' => 'pending',
            'is_interne' => $data['is_interne'] ?? false,
        ];

        return $courrierData;
    }
}