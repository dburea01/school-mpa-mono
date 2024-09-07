<?php

namespace App\Http\Requests;

use App\Models\Result;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreResultRequest extends FormRequest
{
    /**
     * Prepare inputs for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'is_absent' => $this->toBoolean($this->is_absent),
        ]);
    }

    /**
     * Convert to boolean
     */
    private function toBoolean(mixed $booleable): ?bool
    {
        return filter_var($booleable, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user() && $this->method() == 'POST') {
            return $this->user()->can('create', Result::class);
        }

        if ($this->user() && $this->method() == 'PUT') {
            return $this->user()->can('update', $this->route('result'));
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
        /** @var \App\Models\Work $work */
        $work = $this->route('work');

        return [
            'user_id' => [
                'required',
                'uuid',
                Rule::exists('assignments', 'user_id')
                    ->where(
                        fn(Builder $query) => $query
                            ->where('classroom_id', $work->classroom_id)
                    ),
            ],
            'is_absent' => 'boolean',
            'note' => [
                'bail',
                // Rule::requiredIf($request->is_absent === false),
                'nullable',
                'required_if:is_absent,false',
                'prohibited_if:is_absent,true',
                'numeric',
                'gte:0',
                //'between:$work->note_min,$work->note_max',
            ],
            'appreciation_id' => [
                'nullable',
                Rule::exists('appreciations', 'id'),
            ],
            'comment' => 'max:200',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        /** @var \App\Models\Work $work */
        $work = $this->route('work');

        return [
            'user_id.exists' => 'Utilisateur inconnu. What are you doing ? ....',

            'is_absent.required' => 'Absence non précisée',
            'is_absent.boolean' => 'Absence incorrecte (true / false exigé)',

            'note.required_if' => "La note est obligatoire si l'élève est déclaré non absent",
            'note.prohibited_if' => "La note est interdite si l'élève est déclaré absent",
            'note.numeric' => 'La note doit être une valeur numérique',
            'note.gte' => 'La note doit être positive ou nulle',
            'note.between' => "La note doit etre comprise entre $work->note_min et $work->note_max",

            'appreciation_id.exists' => "L'appréciation est incorrecte",
            'comment.max' => 'Commentaire trop long (200 caractères max)',
        ];
    }
}
