<?php

namespace App\Http\Requests;

use App\Models\Assignment;
use Illuminate\Foundation\Http\FormRequest;

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
        $rules = [
            'classroom_id' => 'required|exists:classroom,id',
            
            'name' => 'required|max:50',
            'comment' => 'max:500',
        ];
    }
}
