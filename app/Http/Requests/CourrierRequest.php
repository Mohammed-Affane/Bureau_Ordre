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
        return [
            'type_courrier' => ['required', Rule::in(['arrive', 'depart', 'interne'])],
            'objet' => 'required|string|max:255',
            'reference_arrive' => 'nullable|integer|min:1',
            'reference_BO' => 'nullable|integer|min:1',
            'date_reception' => 'nullable|date|before_or_equal:today',
            'date_enregistrement' => 'required|date|before_or_equal:today',
            'Nbr_piece' => 'required|integer|min:1|max:999',
            'priorite' => ['nullable', Rule::in(['normale', 'urgent', 'confidentiel', 'A reponse obligatoire'])],
            'id_agent_en_charge' => 'nullable|exists:users,id',
            'id_entite' => 'nullable|exists:entites,id',
            'expediteur_id' => 'nullable|exists:expediteurs,id',
            'exp_nom' => 'required_without:expediteur_id|string|max:255',
            'exp_type_source' => 'required_without:expediteur_id|string|max:100',
            'exp_adresse' => 'nullable|string|max:500',
            'exp_telephone' => 'nullable|string|max:20|regex:/^[0-9+\-\s\(\)]*$/',
        ];
    }

    public function messages()
    {
        return [
            'type_courrier.required' => 'Le type de courrier est obligatoire.',
            'type_courrier.in' => 'Le type de courrier doit être : arrivé, départ ou interne.',
            'objet.required' => 'L\'objet du courrier est obligatoire.',
            'objet.max' => 'L\'objet ne peut pas dépasser 255 caractères.',
            'reference_arrive.integer' => 'La référence d\'arrivée doit être un nombre entier.',
            'reference_arrive.min' => 'La référence d\'arrivée doit être supérieure à 0.',
            'reference_BO.integer' => 'La référence BO doit être un nombre entier.',
            'reference_BO.min' => 'La référence BO doit être supérieure à 0.',
            'date_reception.date' => 'La date de réception n\'est pas valide.',
            'date_reception.before_or_equal' => 'La date de réception ne peut pas être dans le futur.',
            'date_enregistrement.required' => 'La date d\'enregistrement est obligatoire.',
            'date_enregistrement.date' => 'La date d\'enregistrement n\'est pas valide.',
            'date_enregistrement.before_or_equal' => 'La date d\'enregistrement ne peut pas être dans le futur.',
            'Nbr_piece.required' => 'Le nombre de pièces est obligatoire.',
            'Nbr_piece.integer' => 'Le nombre de pièces doit être un nombre entier.',
            'Nbr_piece.min' => 'Le nombre de pièces doit être au moins 1.',
            'Nbr_piece.max' => 'Le nombre de pièces ne peut pas dépasser 999.',
            'priorite.in' => 'La priorité sélectionnée n\'est pas valide.',
            'id_agent_en_charge.exists' => 'L\'agent sélectionné n\'existe pas.',
            'id_entite.exists' => 'L\'entité sélectionnée n\'existe pas.',
            'exp_nom.required_without' => 'Le nom de l\'expéditeur est obligatoire pour un nouvel expéditeur.',
            'exp_type_source.required_without' => 'Le type de source est obligatoire pour un nouvel expéditeur.',
        ];
    }
}