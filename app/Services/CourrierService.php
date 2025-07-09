<?php

namespace App\Services;

use App\Models\Courrier;
use App\Models\Expediteur;
use App\Models\CourrierDestinataire;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CourrierService
{
    public function createCourrier(array $data)
    {
        return DB::transaction(function () use ($data) {
            try{
            // Handle file upload
            $filePaths = $this->handleFileUpload($data['document_files'] ?? []);

            // Handle destinataires
            $this->handleDestinataires($courrier, $data);

            // Handle new sender if provided
            if (isset($data['nom']) && $data['type_courrier'] === 'arrive') {
                $this->handleNewSender($data, $courrier);
            }

            // Handle new destinataire if provided
            if (isset($data['nom']) && in_array($data['type_courrier'], ['depart', 'decision', 'interne', 'visa'])) {
                $this->handleNewDestinataire($data, $courrier);
            }

            // Create the courrier
            $courrierData = $this->prepareCourrierData($data, $filePaths);
            $courrier = Courrier::create($courrierData);

            return $courrier;
            } catch (\Throwable $e) {
            // Rollback is automatic
            logger()->error('Failed to create courrier: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);
            throw $e; // Re-throw so controller sees it
        }
        });
    }

    private function prepareCourrierData(array $data, array $filePaths): array
    {
        $courrierData = [
            'type_courrier' => $data['type_courrier'],
            'objet' => $data['objet'],
            'date_enregistrement' => $data['date_enregistrement'],
            'Nbr_piece' => $data['Nbr_piece'],
            'priorite' => $data['priorite'] ?? 'normale',
            'id_agent_en_charge' => $data['id_agent_en_charge'] ?? auth()->user()->id,
            'fichier_scan' => !empty($filePaths) ? json_encode($filePaths) : null,
            'is_interne' => false,
        ];

        // Set specific fields based on courrier type
        switch ($data['type_courrier']) {
            case 'arrive':
                $courrierData += [
                    'id_expediteur' => $data['id_expediteur'],
                    'reference_arrive' => $data['reference_arrive'] ?? null,
                    'reference_bo' => $data['reference_bo'] ?? null,
                    'date_reception' => $data['date_reception'] ?? null,
                ];
                break;

            case 'depart':
                $courrierData += [
                    'entite_id' => $data['entite_id'],
                    'reference_depart' => $data['reference_depart'] ?? null,
                    'date_depart' => $data['date_depart'] ?? null,
                ];
                break;

            case 'visa':
                $courrierData += [
                    'reference_visa' => $data['reference_visa'] ?? null,
                    'date_reception' => $data['date_reception'] ?? null,
                ];
                break;

            case 'decision':
                $courrierData += [
                    'entite_id' => $data['entite_id'],
                    'reference_dec' => $data['reference_dec'] ?? null,
                    'date_reception' => $data['date_reception'] ?? null,
                ];
                break;

            case 'interne':
                $courrierData += [
                    'entite_id' => $data['entite_id'],
                    'reference_depart' => $data['reference_depart'] ?? null,
                    'date_depart' => $data['date_depart'] ?? null,
                    'is_interne' => true,
                ];
                break;
        }

        return $courrierData;
    }

    private function handleFileUpload(array $files): array
    {
        $filePaths = [];
        
        foreach ($files as $file) {
            $path = $file->store('courriers/documents', 'public');
            $filePaths[] = $path;
        }

        return $filePaths;
    }

    private function handleDestinataires(Courrier $courrier, array $data)
    {
        $destinataires = [];

        // Handle internal destinataires
        if (isset($data['destinataires_entite'])) {
            foreach ($data['destinataires_entite'] as $entiteId) {
                $destinataires[] = [
                    'entite_id' => $entiteId,
                    'type_courrier' => 'interne',
                    'id_courrier' => $courrier->id,
                ];
            }
        }

        // Handle external destinataires
        if (isset($data['destinataires_externe'])) {
            foreach ($data['destinataires_externe'] as $expediteurId) {
                $destinataires[] = [
                    'nom' => Expediteur::find($expediteurId)->nom,
                    'type_courrier' => 'externe',
                    'id_courrier' => $courrier->id,
                ];
            }
        }

        if (!empty($destinataires)) {
            CourrierDestinataire::insert($destinataires);
        }
    }

    private function handleNewSender(array $data, Courrier $courrier)
    {
        $expediteur = Expediteur::create([
            'nom' => $data['nom'],
            'type_source' => $data['type_source'],
            'adresse' => $data['adresse'] ?? null,
            'telephone' => $data['telephone'] ?? null,
        ]);

        $courrier->update(['id_expediteur' => $expediteur->id]);
    }

    private function handleNewDestinataire(array $data, Courrier $courrier)
    {
        CourrierDestinataire::create([
            'nom' => $data['nom'],
            'type_source' => $data['type_source'],
            'adresse' => $data['adresse'] ?? null,
            'type_courrier' => 'externe',
            'id_courrier' => $courrier->id,
        ]);
    }
}