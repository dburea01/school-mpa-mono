<?php

namespace App\Http\Requests;

use App\Models\Appreciation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAppreciationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user() && $this->method() == 'POST') {
            return $this->user()->can('create', Appreciation::class);
        }

        if ($this->user() && $this->method() == 'PUT') {
            return $this->user()->can('update', $this->route('appreciation'));
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
            'position' => 'required|numeric|integer|gt:0',
            'short_name' => [
                'required',
                'max:10',
            ],
            'name' => 'required|max:50',
            'comment' => 'max:500',
        ];

        if ($this->method() == 'PUT') {
            /** @var Appreciation $appreciation */
            $appreciation = $this->route('appreciation');
            $rules['short_name'][] = Rule::unique('appreciations')->ignore($appreciation->id);
        }
        if ($this->method() == 'POST') {
            $rules['short_name'][] = Rule::unique('appreciations');
        }

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
            'short_name.required' => "Le nom court de l'appréciation est obligatoire",
            'short_name.max' => "10 caractères max pour le nom court de l'appréciation",
            'short_name.unique' => 'Le nom court existe déjà',

            'name.required' => "Le nom de l'appréciation est obligatoire",
            'name.max' => "50 caractères max pour le nom de l'appréciation",

            'comment.max' => 'Commentaire trop long',
        ];
    }
}
