<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user() && $this->method() == 'POST') {
            return $this->user()->can('create', User::class);
        }

        if ($this->user() && $this->method() == 'PUT') {
            return $this->user()->can('update', $this->route('user'));
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
            'role_id' => 'required|exists:roles,id',
            'first_name' => 'required',
            'last_name' => 'required',
            // 'image_user' => 'mimes:jpg,bmp,png|max:1024',
            'email' => [
                'email',
                'nullable',
                // 'required_unless:role_id,STUDENT',
                Rule::unique('users', 'email')->ignore($this->user),
            ],
            // 'login_status_id' => 'required|exists:login_statuses',
            'gender_id' => [
                'nullable',
                Rule::requiredIf($this->role_id == 'STUDENT'),
                'in:1,2',
            ],
            'civility_id' => 'nullable|exists:civilities,id',
            'birth_date' => [
                'nullable',
                Rule::requiredIf($this->role_id == 'STUDENT'),
                'date_format:d/m/Y',
                'before:today',
            ],
            'country_id' => 'nullable|exists:countries,id',
            'other_comment' => 'max:500',
            'health_comment' => 'max:500'
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
            'role_id.required' => 'Le rôle est obligatoire',
            'role_id.exists' => 'Le rôle est incorrect',

            'last_name.required' => 'Le nom de famille est obligatoire',
            'first_name.required' => 'Le prénom est obligatoire',

            'email.email' => 'Email incorrect',
            'email.unique' => 'Email déjà utilisé par un autre utilisateur',

            'gender_id.required' => 'Le genre est obligatoire pour ce rôle',
            'gender_id.in' => 'Le genre est incorrect (1 / 2)',

            'civility_id' => 'Civilité incorrecte',

            'birth_date.required' => 'La date de naissance est obligatoire pour ce rôle',
            'birth_date.date_format' => 'Date incorrecte (jj/mm/aaaa)',
            'birth_date.before' => 'Date dans le futur non autorisée',

            'country_id.exists' => 'Pays incorrect',

            'health_comment.max' => 'Informations santé trop long (500 caractères max)',
            'other_comment.max' => 'Commentaire trop long (500 caractères max)'
        ];
    }
}
