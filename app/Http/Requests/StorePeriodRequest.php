<?php

namespace App\Http\Requests;

use App\Models\Period;
use Illuminate\Foundation\Http\FormRequest;

class StorePeriodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user() && $this->method() == 'POST') {
            return $this->user()->can('create', Period::class);
        }

        if ($this->user() && $this->method() == 'PUT') {
            return $this->user()->can('update', $this->route('period'));
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

        return [
            'name' => 'required|max:50',
            'start_date' => 'required|date_format:d/m/Y',
            'end_date' => 'required|date_format:d/m/Y|after:start_date',
            'comment' => 'max:500',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => "Le nom de l'année scolaire est obligatoire",
            'name.max' => '50 caractères max pour le nom',

            'start_date.required' => 'Date de début obligatoire',
            'start_date.date_format' => 'Format de date doit être jj/mm/aaaa (ex : 01/09/2024)',

            'end_date.required' => 'Date de fin obligatoire',
            'end_date.date_format' => 'Format de date doit être jj/mm/aaaa (ex : 30/06/2025)',
            'end_date.after' => 'La date de fin doit être supérieure à la date de début',

            'comment.max' => 'Commentaire trop long',
        ];
    }
}
