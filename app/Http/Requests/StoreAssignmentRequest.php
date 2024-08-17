<?php

namespace App\Http\Requests;

use App\Models\Assignment;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAssignmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user() && $this->method() == 'POST') {
            return $this->user()->can('create', Assignment::class);
        }

        if ($this->user() && $this->method() == 'PUT') {
            return $this->user()->can('update', $this->route('assignment'));
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

        $userId = $this->user_id;
        $subjectId = $this->subject_id;
        $classroomId = $this->classroom_id;

        $rules = [
            'classroom_id' => 'required|uuid|exists:classrooms,id',
            'comment' => 'max:500',
            'start_date' => 'nullable|date_format:d/m/Y',
            'end_date' => 'nullable|date_format:d/m/Y|after:start_date',
            'user_id' => [
                'required',
                'uuid',
                'exists:users,id',
            ],
        ];

        if ($this->method() == 'POST') {
            $rules['user_id'][] = Rule::unique('assignments')
                ->where(function (Builder $query) use ($classroomId, $userId, $subjectId) {
                    return $query->where('user_id', $userId)
                        ->where('classroom_id', $classroomId)
                        ->where('subject_id', $subjectId);
                });
        }

        if ($this->method() == 'PUT') {
            $rules['user_id'][] = Rule::unique('assignments')
                ->where(function (Builder $query) use ($classroomId, $userId, $subjectId) {
                    return $query->where('user_id', $userId)
                        ->where('classroom_id', $classroomId)
                        ->where('subject_id', $subjectId);
                })
                ->ignore($this->route('assignment'));
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
            'classroom_id.required' => 'La classe est obligatoire',
            'classroom_id.uuid' => 'Classe incorrecte (uuid)',
            'classroom_id.exists' => 'Classe inconnue',

            'user_id.required' => 'Utilisateur est obligatoire',
            'user_id.uuid' => 'Utilisateur incorrect (uuid)',
            'user_id.exists' => 'Utilisateur inconnu',
            'user_id.unique' => 'Utilisateur déjà affecté à cette classe',

            'start_date.date_format' => 'Date incorrecte (format = jj/mm/aaaa)',
            'end_date.date_format' => 'Date incorrecte (format = jj/mm/aaaa)',
            'end_date.after' => 'Date de fin doit être supérieure à date de début',

            'comment.max' => 'Commentaire trop long (500 caractères max)',
        ];
    }
}
