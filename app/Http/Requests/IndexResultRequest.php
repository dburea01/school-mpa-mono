<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexResultRequest extends FormRequest
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
            'sort' => 'in:name,note,appreciation',
            'direction' => 'in:asc,desc',
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
            'sort.in' => 'Valeur de tri non autorisée',
            'direction.in' => 'Valeur de direction non autorisée',
        ];
    }
}
