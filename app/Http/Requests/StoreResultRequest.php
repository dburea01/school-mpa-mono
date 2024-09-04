<?php

namespace App\Http\Requests;

use App\Models\Result;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreResultRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user() && $this->method() == 'POST') {
            return $this->user()->can('create', [Result::class, $this->route('work')]);
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
                        fn (Builder $query) => $query
                            ->where('classroom_id', $work->classroom_id)
                    ),
            ],
            'note' => [
                Rule::requiredIf(
                    
                    fn () => $this->appreciation_id != null
                        
                        || $this->comment != null
                ),
                'numeric',
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

            'note.required' => 'La note est obligatoire avec une appréciation ou un commentaire',
            'note.numeric' => 'La note doit être une valeur numérique',
            'note.between' => "La note doit etre comprise entre $work->note_min et $work->note_max",

            'appreciation_id.exists' => "L'appréciation est incorrecte",
            'comment.max' => 'Commentaire trop long (200 caractères max)',
        ];
    }
}
