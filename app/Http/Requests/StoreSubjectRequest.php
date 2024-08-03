<?php

namespace App\Http\Requests;

use App\Models\Subject;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user() && $this->method() == 'POST') {
            return $this->user()->can('create', Subject::class);
        }

        if ($this->user() && $this->method() == 'PUT') {
            return $this->user()->can('update', $this->route('subject'));
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
            'short_name' => [
                'required',
                'max:10',
            ],
            'name' => 'required|max:50',
            'comment' => 'max:500',
        ];

        if ($this->method() == 'PUT') {
            /** @var Subject $subject */
            $subject = $this->route('subject');
            $rules['short_name'][] = Rule::unique('subjects')->ignore($subject->id);
        }
        if ($this->method() == 'POST') {
            $rules['short_name'][] = Rule::unique('subjects');
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
            'short_name.required' => 'Le nom court de la matière est obligatoire',
            'short_name.max' => '10 caractères max pour le nom court de la matière',
            'short_name.unique' => 'Le nom court existe déjà',

            'name.required' => 'Le nom de la matière est obligatoire',
            'name.max' => '50 caractères max pour le nom de la matière',

            'comment.max' => 'Commentaire trop long',
        ];
    }
}
