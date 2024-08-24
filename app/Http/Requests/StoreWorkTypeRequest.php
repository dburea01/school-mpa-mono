<?php

namespace App\Http\Requests;

use App\Models\WorkType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWorkTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user() && $this->method() == 'POST') {
            return $this->user()->can('create', WorkType::class);
        }

        if ($this->user() && $this->method() == 'PUT') {
            return $this->user()->can('update', $this->route('work_type'));
        }

        return false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            /** @phpstan-ignore-next-line */
            'short_name' => strtoupper($this->short_name),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'short_name' => ['required', 'max:10'],
            'name' => 'required|max:50',
            'comment' => 'max:500',
        ];

        /** @var WorkType $workType */
        $workType = $this->route('work_type');

        if ($this->method() == 'PUT') {
            $rules['short_name'][] = Rule::unique('work_types', 'short_name')->ignore($workType->id);
        }
        if ($this->method() == 'POST') {
            $rules['short_name'][] = Rule::unique('work_types', 'short_name');
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
            'short_name.required' => 'Le nom court est obligatoire',
            'short_name.max' => '10 caractères max pour le nom court',
            'short_name.unique' => 'Ce nom court est déjà utilisé',

            'name.required' => 'Le nom est obligatoire',
            'name.max' => '50 caractères max pour le nom',

            'comment.max' => 'Commentaire trop long (500 caractères maximum)',
        ];
    }
}
