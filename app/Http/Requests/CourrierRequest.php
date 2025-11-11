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
        // CAB users have limited rights
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
            'Nbr_piece' => ['required', 'integer', 'min:1'],
            'date_reception' => ['nullable', 'date', 'before_or_equal:today'],
            'date_depart' => ['nullable', 'date', 'before_or_equal:today'],

            'id_expediteur' => ['nullable', 'integer', 'exists:expediteurs,id'],
            'exp_nom' => ['nullable', 'string', 'max:255'],
            'exp_type_source' => ['nullable', 'string', Rule::in(['citoyen', 'administration'])],
            'exp_adresse' => ['nullable', 'string', 'max:500'],
            'exp_telephone' => ['nullable', 'string', 'max:20'],
            'exp_CIN' => ['nullable', 'string', 'max:50'],

            'entite_id' => ['nullable', 'integer', 'exists:entites,id'],

            // === Fichier ===
            'fichier_scan' => [
                $this->isMethod('POST') ? 'required' : 'nullable',
                'file',
                'mimes:pdf',
                'max:2048'
            ],

            // === Références (avec unicité annuelle) ===
            'reference_arrive' => [
                'nullable', 'string', 'max:50', 'regex:/^[A-Za-z0-9\/\-\._]+$/',
                Rule::unique('courriers', 'reference_arrive')
                    ->ignore($this->route('courrier'))
                    ->where(fn($q) => $q->whereYear('date_enregistrement', now()->year))
            ],
            'reference_bo' => [
                'nullable', 'string', 'max:50',
                Rule::unique('courriers', 'reference_bo')
                    ->ignore($this->route('courrier'))
                    ->where(fn($q) => $q->whereYear('date_enregistrement', now()->year))
            ],
            'reference_visa' => [
                'nullable', 'integer', 'min:1',
                Rule::unique('courriers', 'reference_visa')
                    ->ignore($this->route('courrier'))
                    ->where(fn($q) => $q->whereYear('date_enregistrement', now()->year))
            ],
            'reference_dec' => [
                'nullable', 'integer', 'min:1',
                Rule::unique('courriers', 'reference_dec')
                    ->ignore($this->route('courrier'))
                    ->where(fn($q) => $q->whereYear('date_enregistrement', now()->year))
            ],
            'reference_depart' => [
                'nullable', 'integer', 'min:1',
                Rule::unique('courriers', 'reference_depart')
                    ->ignore($this->route('courrier'))
                    ->where(fn($q) => $q->whereYear('date_enregistrement', now()->year))
            ],

            // === Destinataires ===
            'destinataires_entite' => ['nullable', 'array'],
            'destinataires_entite.*' => ['integer', 'exists:entites,id'],
            'destinataires_externe' => ['nullable', 'array'],
            'destinataires_externe.*' => ['integer', 'exists:courrier_destinataires,id'],
            'dest_nom' => ['nullable', 'array'],
            'dest_nom.*' => ['nullable', 'string', 'max:255'],
            'dest_type_source' => ['nullable', 'array'],
            'dest_type_source.*' => ['nullable', 'string', Rule::in(['citoyen', 'administration'])],
            'dest_adresse' => ['nullable', 'array'],
            'dest_adresse.*' => ['nullable', 'string', 'max:500'],
            'dest_CIN' => ['nullable', 'array'],
            'dest_CIN.*' => ['nullable', 'string', 'max:50'],
            'dest_telephone' => ['nullable', 'array'],
            'dest_telephone.*' => ['nullable', 'string', 'max:20'],
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'type_courrier.required' => 'Le type de courrier est obligatoire.',
            'objet.required' => 'L\'objet du courrier est obligatoire.',
            'objet.regex' => 'L\'objet contient des caractères non autorisés.',
            'date_enregistrement.required' => 'La date d\'enregistrement est obligatoire.',
            'Nbr_piece.required' => 'Le nombre de pièces est obligatoire.',
            'fichier_scan.required' => 'Le fichier scanné est obligatoire lors de la création.',
            'fichier_scan.mimes' => 'Le fichier scanné doit être un PDF.',
            'fichier_scan.max' => 'Le fichier scanné ne peut pas dépasser 2 Mo.',
            'destinataires_entite.*.exists' => 'Une des entités destinataires n’existe pas.',
            'dest_nom.*.max' => 'Le nom d’un destinataire ne peut pas dépasser 255 caractères.',
            'dest_type_source.*.in' => 'Le type de source d’un destinataire n’est pas valide.',
            'dest_adresse.*.max' => 'L’adresse d’un destinataire ne peut pas dépasser 500 caractères.',
            'dest_CIN.*.max' => 'Le CIN d’un destinataire ne peut pas dépasser 50 caractères.',
            'dest_telephone.*.max' => 'Le téléphone d’un destinataire ne peut pas dépasser 20 caractères.',
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

        // Expediteur obligatoire pour arrivée ou visa
        if (in_array($type, ['arrive', 'visa']) &&
            !$this->filled('id_expediteur') && !$this->filled('exp_nom')) {
            $validator->errors()->add('id_expediteur', 'Vous devez sélectionner un expéditeur ou en créer un nouveau.');
        }

        // CIN => nécessite citoyen
        if ($this->filled('exp_CIN') && $this->input('exp_type_source') !== 'citoyen') {
            $validator->errors()->add('exp_type_source', 'Le type de source doit être "citoyen" si un CIN est fourni.');
        }

        // Entité obligatoire pour certains types
        if (in_array($type, ['depart', 'decision', 'interne']) && !$this->filled('entite_id')) {
            $validator->errors()->add('entite_id', 'L\'entité expéditrice est obligatoire pour ce type de courrier.');
        }

        // === Références spécifiques ===
        switch ($type) {
            case 'arrive':
                if (!$this->filled('reference_arrive'))
                    $validator->errors()->add('reference_arrive', 'La référence d\'arrivée est obligatoire.');
                if (!$this->filled('reference_bo'))
                    $validator->errors()->add('reference_bo', 'La référence BO est obligatoire.');
                if (!$this->filled('date_reception'))
                    $validator->errors()->add('date_reception', 'La date de réception est obligatoire.');
                break;

            case 'depart':
                if (!$this->filled('reference_depart'))
                    $validator->errors()->add('reference_depart', 'La référence de départ est obligatoire.');
                if (!$this->filled('date_depart'))
                    $validator->errors()->add('date_depart', 'La date de départ est obligatoire.');
                break;

            case 'visa':
                if (!$this->filled('reference_visa'))
                    $validator->errors()->add('reference_visa', 'La référence visa est obligatoire.');
                if (!$this->filled('reference_arrive'))
                    $validator->errors()->add('reference_arrive', 'La référence d\'arrivée est obligatoire.');
                if (!$this->filled('date_reception'))
                    $validator->errors()->add('date_reception', 'La date de réception est obligatoire.');
                break;

            case 'decision':
                if (!$this->filled('reference_dec'))
                    $validator->errors()->add('reference_dec', 'La référence décision est obligatoire.');
                if (!$this->filled('date_depart'))
                    $validator->errors()->add('date_depart', 'La date de départ est obligatoire.');
                break;

            case 'interne':
                if (!$this->filled('reference_depart'))
                    $validator->errors()->add('reference_depart', 'La référence de départ est obligatoire.');
                if (!$this->filled('date_depart'))
                    $validator->errors()->add('date_depart', 'La date de départ est obligatoire.');
                break;
        }

        // === Vérifie au moins un destinataire ===
        $requiresDestinataires = in_array($type, ['depart', 'decision', 'interne', 'visa']);
        $hasDestinataires = $this->filled('destinataires_entite')
            || $this->filled('destinataires_externe')
            || $this->hasFilledDestinatairesManuels();

        if ($requiresDestinataires && !$hasDestinataires) {
            $validator->errors()->add('destinataires', 'Au moins un destinataire doit être sélectionné.');
        }

        // === Validation manuelle destinataires ===
        if ($this->has('dest_nom')) {
            foreach ($this->input('dest_nom', []) as $index => $nom) {
                if (!empty($nom)) {
                    $cin = $this->input("dest_CIN.$index");
                    $typeSource = $this->input("dest_type_source.$index");
                    if (!empty($cin) && $typeSource !== 'citoyen') {
                        $validator->errors()->add("dest_type_source.$index", 'Le type de source doit être "citoyen" si un CIN est fourni.');
                    }
                }
            }
        }
    }

    protected function hasFilledDestinatairesManuels(): bool
    {
        if (!$this->has('dest_nom')) return false;
        foreach ($this->input('dest_nom', []) as $nom) {
            if (!empty(trim($nom))) return true;
        }
        return false;
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

        if ($this->has('dest_nom')) {
            $this->merge([
                'dest_nom' => array_map('trim', $this->input('dest_nom', []))
            ]);
        }

        // Ensure BO reference is only considered for 'arrive' type
        if ($this->input('type_courrier') !== 'arrive') {
            $this->merge(['reference_bo' => null]);
        }
    }
}
