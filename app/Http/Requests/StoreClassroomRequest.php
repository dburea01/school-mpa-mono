<?php

namespace App\Http\Requests;

use App\Models\Classroom;
use App\Models\Period;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClassroomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user() && $this->method() == 'POST') {
            return $this->user()->can('create', Classroom::class);
        }

        if ($this->user() && $this->method() == 'PUT') {
            return $this->user()->can('update', $this->route('classroom'));
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

        /** @var Period $period */
        $period = $this->route('period');

        if ($this->method() == 'PUT') {
            /** @var Classroom $classroom */
            $classroom = $this->route('classroom');
            $rules['short_name'][] = Rule::unique('classrooms')
                ->where(fn (Builder $query) => $query->where('period_id', $period->id))
                ->ignore($classroom->id);
        }
        if ($this->method() == 'POST') {
            $rules['short_name'][] = Rule::unique('classrooms')
                ->where(fn (Builder $query) => $query->where('period_id', $period->id));
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
            'short_name.required' => 'Le nom court de la classe est obligatoire',
            'short_name.max' => '50 caractères max pour le nom',
            'short_name.unique' => 'Ce nom court de classe est déjà utilisé pour cette année scolaire',

            'name.required' => 'Le nom de la classe est obligatoire',
            'name.max' => '50 caractères max pour le nom',

            'comment.max' => 'Commentaire trop long',
        ];
    }
}
