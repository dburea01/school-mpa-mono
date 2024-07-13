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
        ];
    }
}
