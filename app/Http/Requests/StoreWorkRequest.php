<?php

namespace App\Http\Requests;

use App\Models\Work;
use Illuminate\Foundation\Http\FormRequest;

class StoreWorkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user() && $this->method() == 'POST') {
            return $this->user()->can('create', Work::class);
        }

        if ($this->user() && $this->method() == 'PUT') {
            return $this->user()->can('update', $this->route('work'));
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'required|max:50',
            'classroom_id' => 'required|exists:classrooms,id',
            'work_type_id' => 'required|exists:work_types,id',
            'subject_id' => 'required|exists:subjects,id',
            'work_status_id' => 'required|exists:work_statuses,id',

            'estimated_duration' => 'numeric|min:0|max:180',
            'expected_at' => 'date_format:d/m/Y',
            'note_min' => 'numeric|min:0',
            'note_max' => 'numeric|max:100|gt:note_min',
            'note_increment' => 'numeric|min:0.1|max:1',

            'instruction' => 'max:500',
            'comment' => 'max:500',
        ];

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est obligatoire',
            'title.max' => 'Le titre est trop long (50 caractères max)',

            'classroom_id.required' => 'La classe est obligatoire',
            'classroom_id.exists' => 'La classe est incorrecte',

            'work_type_id.required' => 'Le type de travail est obligatoire',
            'work_type_id.exists' => 'Le type de travail est incorrect',

            'subject_id.required' => 'La matière est obligatoire',
            'subject_id.exists' => 'La matière est incorrecte',

            'work_status_id.required' => 'Le status est obligatoire',
            'work_status_id.exists' => 'Le status est incorrect',

            'estimated_duration.numeric' => 'Veuillez saisir une valeur numérique',
            'estimated_duration.min' => 'Veuillez saisir une valeur positive',
            'estimated_duration.max' => 'Veuillez saisir une valeur inférieure à 180 minutes',

            'expected_at.date_format' => 'Format de date incorrect (jj/mm/aaaaa)',
            
            'note_min.numeric' => 'Veuillez saisir une valeur numérique',
            'note_min.min' => 'Veuillez saisir une valeur positive',
            
            'note_max.numeric' => 'Veuillez saisir une valeur numérique',
            'note_max.max' => 'Veuillez saisir une valeur inférieure à 100',
            'note_max.gt' => 'Veuillez saisir une valeur supérieure au minimum',
            
            'note_increment.numeric' => 'Veuillez saisir une valeur numérique',
            'note_increment.min' => 'Veuillez saisir une valeur supérieure ou égale à 0.1',
            'note_increment.max' => 'Veuillez saisir une valeur inférieure ou égale à 1.0',

            'instruction.max' => 'Instructions trop longues (500 caractères max)',
            'comment.max' => 'Commentaire trop long (500 caractères max)',
        ];
    }
}
