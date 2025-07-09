<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourrierRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'type_courrier' => ['required', Rule::in(['arrive', 'depart', 'visa', 'decision', 'interne'])],
            'objet' => 'required|string|max:255',
            'date_enregistrement' => 'required|date|before_or_equal:today',
            'Nbr_piece' => 'required|integer|min:1|max:999',
            'priorite' => ['nullable', Rule::in(['normale', 'urgent', 'confidentiel', 'A reponse obligatoire'])],
            'id_agent_en_charge' => 'nullable|exists:users,id',
            'document_files' => 'nullable|array',
            'document_files.*' => 'file|mimes:pdf,jpg,jpeg,png,gif,bmp,tiff,webp|max:10240',
        ];

        // Rules based on courrier type
        switch ($this->input('type_courrier')) {
            case 'arrive':
                $rules += [
                    'id_expediteur' => 'required|exists:expediteurs,id',
                    'reference_arrive' => 'nullable|integer|min:1',
                    'reference_bo' => 'nullable|integer|min:1',
                    'date_reception' => 'nullable|date|before_or_equal:today',
                    'destinataires_entite' => 'required|array|min:1',
                    'destinataires_entite.*' => 'exists:entites,id',
                ];
                break;

            case 'depart':
                $rules += [
                    'entite_id' => 'required|exists:entites,id',
                    'reference_depart' => 'nullable|integer|min:1',
                    'date_depart' => 'nullable|date|before_or_equal:today',
                    'destinataires_externe' => 'required|array|min:1',
                    'destinataires_externe.*' => 'exists:expediteurs,id',
                ];
                break;

            case 'visa':
                $rules += [
                    'reference_visa' => 'nullable|integer|min:1',
                    'date_reception' => 'nullable|date|before_or_equal:today',
                    'destinataires_externe' => 'nullable|array',
                    'destinataires_externe.*' => 'exists:expediteurs,id',
                    'destinataires_entite' => 'nullable|array',
                    'destinataires_entite.*' => 'exists:entites,id',
                ];
                break;

            case 'decision':
                $rules += [
                    'entite_id' => 'required|exists:entites,id',
                    'reference_dec' => 'nullable|integer|min:1',
                    'date_reception' => 'nullable|date|before_or_equal:today',
                    'destinataires_externe' => 'required|array|min:1',
                    'destinataires_externe.*' => 'exists:expediteurs,id',
                ];
                break;

            case 'interne':
                $rules += [
                    'entite_id' => 'required|exists:entites,id',
                    'reference_depart' => 'nullable|integer|min:1',
                    'date_depart' => 'nullable|date|before_or_equal:today',
                    'destinataires_entite' => 'required|array|min:1',
                    'destinataires_entite.*' => 'exists:entites,id',
                ];
                break;
        }

        // Additional rules for new sender/destinataire
        if ($this->has('nom_expediteur') && $this->filled('nom_expediteur')) {
            $rules += [
                'nom_expediteur' => 'required|string|max:255',
                'type_source_expediteur' => 'required|string|max:255',
                'adresse_expediteur' => 'required|string|max:255',
                'telephone' => 'required|string|max:20',
            ];
        }
        if($this->has('nom_destinataire') && $this->filled('nom_destinataire')){
            $rules += [
                'nom_destinataire' => 'required|string|max:255',
                'type_source_destinataire' => 'required|string|max:255',
                'adresse_destinataire' => 'required|string|max:255',
            ];
        }


        return $rules;
    }

    public function messages()
    {
        return [
            'id_expediteur.required' => 'Le champ expéditeur est obligatoire pour les courriers arrivés.',
            'entite_id.required' => 'Le champ entité expéditrice est obligatoire.',
            'destinataires_entite.required' => 'Veuillez sélectionner au moins un destinataire interne.',
            'destinataires_externe.required' => 'Veuillez sélectionner au moins un destinataire externe.',
            'document_files.*.max' => 'Le fichier ne doit pas dépasser 10MB.',
            'document_files.*.mimes' => 'Seuls les fichiers PDF, JPG, PNG, GIF, BMP, TIFF et WebP sont autorisés.',
        ];
    }
}