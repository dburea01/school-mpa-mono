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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        /** @var \App\Models\Work $work */
        $work = $this->route('work');

        return [
            'index' => 'required|int',
            'user_id' => [
                'required',
                'uuid',
                Rule::exists('assignments', 'user_id')
                    ->where(
                        fn (Builder $query) => $query
                            ->where('classroom_id', $work->classroom_id)
                    ),
            ],
            'result.*.note' => [
                'nullable',
                Rule::requiredIf(
                    /** @phpstan-ignore-next-line */
                    fn () => $this->result[$this->index]['appreciation_id'] != null
                        /** @phpstan-ignore-next-line */
                        || $this->result[$this->index]['comment'] != null
                ),
                'numeric',
                //'between:$work->note_min,$work->note_max',
            ],
            'result.*.appreciation_id' => [
                'nullable',
                Rule::exists('appreciations', 'id'),
            ],
            'result.*.comment' => 'max:200',
        ];
    }

    public function messages(): array
    {
        /** @var \App\Models\Work $work */
        $work = $this->route('work');

        return [
            'user_id.exists' => 'Utilisateur inconnu. What are you doing ? ....',
            'result.*.note.required' => 'La note est obligatoire avec une appréciation ou un commentaire',
            'result.*.note.between' => "La note doit etre comprise entre $work->note_min et $work->note_max",

            'result.*.appreciation_id.exists' => "L'appréciation est incorrecte",
            'result.*.comment.max' => 'Commentaire trop long (200 caractères max)',
        ];
    }
}
