<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourrierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if (auth()->user()->hasRole('cab')) {
            return [
                'priorite' => ['required', 'string', Rule::in(['normale', 'urgent', 'confidentiel', 'A reponse obligatoire'])],
                'delais' => ['nullable', 'date', 'after:date_enregistrement'],
            ];
        }

        $rules = [
            'type_courrier' => ['required', 'string', Rule::in(['arrive', 'depart', 'visa', 'decision', 'interne'])],
            'objet' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\p{N}\s\-_.,]+$/u'],
            'date_enregistrement' => ['required', 'date', 'before_or_equal:today'],
            'Nbr_piece' => ['required', 'integer', 'min:1', 'max:999'],
            'date_reception' => ['nullable', 'date', 'before_or_equal:today'],
            'date_depart' => ['nullable', 'date', 'before_or_equal:today'],
            'id_expediteur' => ['nullable', 'integer', 'exists:expediteurs,id'],
            'exp_nom' => ['nullable', 'string', 'max:255'],
            'exp_type_source' => ['nullable', 'string', Rule::in(['citoyen', 'administration'])],
            'exp_adresse' => ['nullable', 'string', 'max:500'],
            'exp_telephone' => ['nullable', 'string', 'max:20'],
            'exp_CIN' => ['nullable', 'string', 'max:50'],
            'entite_id' => ['nullable', 'integer', 'exists:entites,id'],

            'fichier_scan' => [$this->isMethod('POST') ? 'required' : 'nullable', 'file', 'mimes:pdf', 'max:2048'],

            // Références (validées plus bas conditionnellement)
            'reference_arrive' => ['nullable', 'string', 'max:50', 'regex:/^[A-Za-z0-9\/\-\._]+$/',
                Rule::unique('courriers', 'reference_arrive')
                        ->ignore($this->route('courrier'))
                        ->where(function ($query) {
                            $query->whereYear('date_enregistrement', now()->year);
                                })],
            'reference_bo' => ['nullable', 'string', 'max:50',
                Rule::unique('courriers', 'reference_bo')
                        ->ignore($this->route('courrier'))
                        ->where(function($query){
                            $query->whereYear('date_enregistrement',now()->year);
                                })],
            'reference_visa' => ['nullable', 'integer', 'min:1',
                Rule::unique('courriers', 'reference_visa')
                        ->ignore($this->route('courrier'))
                        ->where(function($query){
                            $query->whereYear('date_enregistrement',now()->year);
                                })],
            'reference_dec' => ['nullable', 'integer', 'min:1',
                Rule::unique('courriers', 'reference_dec')
                        ->ignore($this->route('courrier'))
                        ->where(function($query){
                            $query->whereYear('date_enregistrement',now()->year);
                                })],
            'reference_depart' => ['nullable', 'integer', 'min:1',
                Rule::unique('courriers', 'reference_depart')
                        ->ignore($this->route('courrier'))
                        ->where(function($query){
                            $query->whereYear('date_enregistrement',now()->year);
                                })],
        ];

        return $rules;
    }
  


    
    public function messages(): array
{
    return [
        'reference_bo.unique' => 'Cette référence BO existe déjà pour cette année.',
        'reference_arrive.unique' => 'Cette référence d\'arrivée existe déjà pour cette année.',
        'reference_visa.unique' => 'Cette référence visa existe déjà pour cette année.',
        'reference_dec.unique' => 'Cette référence décision existe déjà pour cette année.',
        'reference_depart.unique' => 'Cette référence de départ existe déjà pour cette année.',

        // tes messages existants
        'type_courrier.required' => 'Le type de courrier est obligatoire.',
        'objet.required' => 'L\'objet du courrier est obligatoire.',
        'date_enregistrement.required' => 'La date d\'enregistrement est obligatoire.',
        'Nbr_piece.required' => 'Le nombre de pièces est obligatoire.',
        'fichier_scan.required' => 'Le fichier scanné est obligatoire lors de la création.',
        'fichier_scan.mimes' => 'Le fichier scanné doit être un PDF.',
        'fichier_scan.max' => 'Le fichier scanné ne peut pas dépasser 2 Mo.',
    ];
}


    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $this->validateConditionalFields($validator);
        });
    }

    protected function validateConditionalFields($validator): void
    {
        $type = $this->input('type_courrier');

        // Vérification expéditeur selon type
        if (in_array($type, ['arrive', 'visa']) &&
            !$this->filled('id_expediteur') && !$this->filled('exp_nom')) {
            $validator->errors()->add('id_expediteur', 'Vous devez sélectionner un expéditeur ou en créer un nouveau.');
        }

        // Vérification entité pour certains types
        if (in_array($type, ['depart', 'decision', 'interne']) && !$this->filled('entite_id')) {
            $validator->errors()->add('entite_id', 'L\'entité expéditrice est obligatoire pour ce type de courrier.');
        }

        // === Validation des références selon type de courrier ===
        switch ($type) {
            case 'arrive':
                if (!$this->filled('reference_arrive')) {
                    $validator->errors()->add('reference_arrive', 'La référence d\'arrivée est obligatoire pour un courrier d\'arrivée.');
                }
                if (!$this->filled('reference_bo')) {
                    $validator->errors()->add('reference_bo', 'La référence BO est obligatoire pour un courrier d\'arrivée.');
                }
                if(!$this->filled('date_reception')){
                    $validator->errors()->add('date_reception','La Date reception est obligatoire pour un courrier d\'arrivée.');
                }
                break;
            case 'depart':
                if (!$this->filled('reference_depart')) {
                    $validator->errors()->add('reference_depart', 'La référence de départ est obligatoire pour un courrier de départ.');
                }
                if (!$this->filled('date_depart')) {
                    $validator->errors()->add('date_depart', 'La date de départ est obligatoire pour un courrier de départ.');
                }
                break;
            case 'visa':
                if (!$this->filled('reference_visa')) {
                    $validator->errors()->add('reference_visa', 'La référence visa est obligatoire pour un courrier de type visa.');
                }
                if (!$this->filled('reference_arrive')) {
                    $validator->errors()->add('reference_arrive', 'La référence d\'arrivée est obligatoire pour un courrier d\'arrivée.');
                }
                if(!$this->filled('date_reception')){
                    $validator->errors()->add('date_reception','La Date reception est obligatoire pour un courrier d\'arrivée.');
                }
                break;
            case 'decision':
                if (!$this->filled('reference_dec')) {
                    $validator->errors()->add('reference_dec', 'La référence décision est obligatoire pour un courrier de type décision.');
                }
                if (!$this->filled('date_depart')) {
                    $validator->errors()->add('date_depart', 'La date de départ est obligatoire pour un courrier de départ.');
                }
                break;
            case 'interne':
                if (!$this->filled('reference_depart')) {
                    $validator->errors()->add('reference_depart', 'La référence depart est obligatoire pour un courrier d\'arrivée.');
                }
                if (!$this->filled('date_depart')) {
                    $validator->errors()->add('date_depart', 'La date de depart est obligatoire pour un courrier d\'arrivée.');
                }
                break;
        }
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'objet' => trim($this->input('objet', '')),
            'exp_nom' => trim($this->input('exp_nom', '')),
            'exp_adresse' => trim($this->input('exp_adresse', '')),
            'exp_telephone' => trim($this->input('exp_telephone', '')),
            'exp_CIN' => trim($this->input('exp_CIN', '')),
        ]);
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
{
    throw new \Illuminate\Validation\ValidationException($validator);
}

}
