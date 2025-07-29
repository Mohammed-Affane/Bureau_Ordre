<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourrierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Type de courrier - obligatoire
            'type_courrier' => ['required', 'string', Rule::in(['arrive', 'depart', 'visa', 'decision', 'interne'])],
            
            // Informations du courrier - obligatoires
            'objet' => ['required', 'string', 'max:255'],
            'date_enregistrement' => ['required', 'date', 'before_or_equal:today'],
            'Nbr_piece' => ['required', 'integer', 'min:1', 'max:999'],
            
            // Priorité
            'priorite' => ['required', 'string', Rule::in(['normale', 'urgent', 'confidentiel', 'A reponse obligatoire'])],
            
            // Date Delais
            'delais' => ['nullable', 'date', 'after:date_enregistrement'],
            
            // Références conditionnelles selon le type
            'reference_arrive' => ['nullable', 'integer', 'min:1'],
            'reference_bo' => ['nullable', 'integer', 'min:1'],
            'reference_visa' => ['nullable', 'integer', 'min:1'],
            'reference_dec' => ['nullable', 'integer', 'min:1'],
            'reference_depart' => ['nullable', 'integer', 'min:1'],
            
            // Dates conditionnelles
            'date_reception' => ['nullable', 'date', 'before_or_equal:today'],
            'date_depart' => ['nullable', 'date', 'before_or_equal:today'],
            
            // Expéditeur existant (pour courrier arrivé)
            'id_expediteur' => ['nullable', 'integer', 'exists:expediteurs,id'],
            
            // Nouvel expéditeur (pour courrier arrivé)
            'exp_nom' => ['nullable', 'string', 'max:255'],
            'exp_type_source' => ['nullable', 'string', Rule::in(['citoyen', 'administration'])],
            'exp_adresse' => ['nullable', 'string', 'max:500'],
            'exp_telephone' => ['nullable', 'string', 'max:20'],
            'exp_CIN' => ['nullable', 'string', 'max:50'],
            
            // Entité expéditrice (pour courrier départ/décision/interne)
            'entite_id' => ['nullable', 'integer', 'exists:entites,id'],
            
            // Destinataires internes
            'destinataires_entite' => ['nullable', 'array'],
            'destinataires_entite.*' => ['integer', 'exists:entites,id'],
            
            // Destinataires externes existants
            'destinataires_externe' => ['nullable', 'array'],
            'destinataires_externe.*' => ['integer', 'exists:courrier_destinataires,id'],
            
            // Nouveaux destinataires externes
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
            
            // Fichier scan
           'fichier_scan' => $this->isMethod('POST') 
            ? ['required', 'file', 'mimes:jpg,jpeg,png,pdf,gif,bmp,tiff,webp', 'max:2048']
            : ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,gif,bmp,tiff,webp', 'max:2048'],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'type_courrier.required' => 'Le type de courrier est obligatoire.',
            'type_courrier.in' => 'Le type de courrier sélectionné n\'est pas valide.',
            
            'objet.required' => 'L\'objet du courrier est obligatoire.',
            'objet.max' => 'L\'objet ne peut pas dépasser 255 caractères.',
            
            'date_enregistrement.required' => 'La date d\'enregistrement est obligatoire.',
            'date_enregistrement.before_or_equal' => 'La date d\'enregistrement ne peut pas être dans le futur.',
            
            'date_reception.before_or_equal' => 'La date de réception ne peut pas être dans le futur.',
            'date_depart.before_or_equal' => 'La date de départ ne peut pas être dans le futur.',
            
            'Nbr_piece.required' => 'Le nombre de pièces est obligatoire.',
            'Nbr_piece.integer' => 'Le nombre de pièces doit être un nombre entier.',
            'Nbr_piece.min' => 'Le nombre de pièces doit être au moins 1.',
            'Nbr_piece.max' => 'Le nombre de pièces ne peut pas dépasser 999.',
            
            'priorite.in' => 'La priorité sélectionnée n\'est pas valide.',
            'delais.after' => 'La date de délais doit être postérieure à la date d\'enregistrement.',

            
            'reference_arrive.integer' => 'La référence d\'arrivée doit être un nombre entier.',
            'reference_arrive.min' => 'La référence d\'arrivée doit être au moins 1.',
            
            'reference_bo.integer' => 'La référence BO doit être un nombre entier.',
            'reference_bo.min' => 'La référence BO doit être au moins 1.',
            
            'reference_visa.integer' => 'La référence visa doit être un nombre entier.',
            'reference_visa.min' => 'La référence visa doit être au moins 1.',
            
            'reference_dec.integer' => 'La référence décision doit être un nombre entier.',
            'reference_dec.min' => 'La référence décision doit être au moins 1.',
            
            'reference_depart.integer' => 'La référence départ doit être un nombre entier.',
            'reference_depart.min' => 'La référence départ doit être au moins 1.',
            
            'exp_nom.max' => 'Le nom de l\'expéditeur ne peut pas dépasser 255 caractères.',
            'exp_type_source.in' => 'Le type de source de l\'expéditeur n\'est pas valide.',
            'exp_adresse.max' => 'L\'adresse de l\'expéditeur ne peut pas dépasser 500 caractères.',
            'exp_telephone.max' => 'Le téléphone de l\'expéditeur ne peut pas dépasser 20 caractères.',
            'exp_CIN.max' => 'Le CIN de l\'expéditeur ne peut pas dépasser 50 caractères.',
            
            'destinataires_entite.*.exists' => 'Une des entités destinataires sélectionnées n\'existe pas.',
            'destinataires_externe.*.exists' => 'Un des destinataires externes sélectionnés n\'existe pas.',
            
            'dest_nom.*.max' => 'Le nom d\'un destinataire ne peut pas dépasser 255 caractères.',
            'dest_type_source.*.in' => 'Le type de source d\'un destinataire n\'est pas valide.',
            'dest_adresse.*.max' => 'L\'adresse d\'un destinataire ne peut pas dépasser 500 caractères.',
            'dest_CIN.*.max' => 'Le CIN d\'un destinataire ne peut pas dépasser 50 caractères.',
            'dest_telephone.*.max' => 'Le téléphone d\'un destinataire ne peut pas dépasser 20 caractères.',
            
            'fichier_scan.file' => 'Le fichier scanné doit être un fichier valide.',
            'fichier_scan.mimes' => 'Le fichier scanné doit être de type: jpg, jpeg, png, pdf, gif, bmp, tiff, webp.',
            'fichier_scan.max' => 'Le fichier scanné ne peut pas dépasser 2MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'type_courrier' => 'type de courrier',
            'objet' => 'objet',
            'date_enregistrement' => 'date d\'enregistrement',
            'date_reception' => 'date de réception',
            'date_depart' => 'date de départ',
            'Nbr_piece' => 'nombre de pièces',
            'priorite' => 'priorité',
            'delais' => 'date de délais',
            'id_expediteur' => 'expéditeur',
            'entite_id' => 'entité expéditrice',
            'reference_arrive' => 'référence d\'arrivée',
            'reference_bo' => 'référence BO',
            'reference_visa' => 'référence visa',
            'reference_dec' => 'référence décision',
            'reference_depart' => 'référence départ',
            'exp_nom' => 'nom de l\'expéditeur',
            'exp_type_source' => 'type de source de l\'expéditeur',
            'exp_adresse' => 'adresse de l\'expéditeur',
            'exp_telephone' => 'téléphone de l\'expéditeur',
            'exp_CIN' => 'CIN de l\'expéditeur',
            'destinataires_entite' => 'destinataires internes',
            'destinataires_externe' => 'destinataires externes',
            'fichier_scan' => 'fichier scanné',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $this->validateConditionalFields($validator);
        });
    }

    /**
     * Validate conditional fields based on courier type.
     */
    protected function validateConditionalFields($validator): void
    {
        $type = $this->input('type_courrier');

        // Validation pour courrier arrivé
        if ($type === 'arrive') {
            // Doit avoir soit un expéditeur existant, soit un nouvel expéditeur
            if (!$this->filled('id_expediteur') && !$this->filled('exp_nom')) {
                $validator->errors()->add('id_expediteur', 'Vous devez sélectionner un expéditeur existant ou créer un nouveau.');
            }

            // Si CIN est fourni, vérifier que le type_source est 'citoyen'
            if ($this->filled('exp_CIN') && $this->input('exp_type_source') !== 'citoyen') {
                $validator->errors()->add('exp_type_source', 'Le type de source doit être "citoyen" si un CIN est fourni.');
            }
        }

        // Validation pour courrier départ/décision/interne
        if (in_array($type, ['depart', 'decision', 'interne'])) {
            if (!$this->filled('entite_id')) {
                $validator->errors()->add('entite_id', 'L\'entité expéditrice est obligatoire pour ce type de courrier.');
            }
        }

        // Validation pour les nouveaux destinataires externes
        if ($this->has('dest_nom')) {
            foreach ($this->input('dest_nom', []) as $index => $nom) {
                if (!empty($nom)) {
                    $cin = $this->input("dest_CIN.{$index}");
                    $typeSource = $this->input("dest_type_source.{$index}");
                    
                    if (!empty($cin) && $typeSource !== 'citoyen') {
                        $validator->errors()->add("dest_type_source.{$index}", 
                            'Le type de source doit être "citoyen" si un CIN est fourni.');
                    }
                }
            }
        }

        // Validation des destinataires (au moins un destinataire requis)
        $hasDestinataires = $this->filled('destinataires_entite') || 
                           $this->filled('destinataires_externe') || 
                           $this->hasFilledDestinatairesManuels();

        if (!$hasDestinataires) {
            $validator->errors()->add('destinataires', 'Au moins un destinataire doit être sélectionné.');
        }
    }

    /**
     * Check if manual destinataires are filled.
     */
    protected function hasFilledDestinatairesManuels(): bool
    {
        if (!$this->has('dest_nom')) {
            return false;
        }

        foreach ($this->input('dest_nom', []) as $nom) {
            if (!empty(trim($nom))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Nettoyer les données avant validation
        $this->merge([
            'objet' => trim($this->input('objet', '')),
            'exp_nom' => trim($this->input('exp_nom', '')),
            'exp_adresse' => trim($this->input('exp_adresse', '')),
            'exp_telephone' => trim($this->input('exp_telephone', '')),
            'exp_CIN' => trim($this->input('exp_CIN', '')),
        ]);

        // Nettoyer les noms des destinataires manuels
        if ($this->has('dest_nom')) {
            $destNoms = array_map('trim', $this->input('dest_nom', []));
            $this->merge(['dest_nom' => $destNoms]);
        }
    }
}