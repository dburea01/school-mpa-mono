<?php

namespace App\Http\Requests;

use App\Models\UserGroup;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user() && $this->method() == 'POST') {
            return $this->user()->can('create', UserGroup::class);
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

        /** @var \App\Models\Group $group */
        $group = $this->route('group');

        return [
            'user_id' => [
                'uuid',
                'required',
                Rule::exists('users', 'id'),
                Rule::unique('user_groups')
                    ->where(
                        fn (Builder $query) => $query->where('group_id', $group->id)
                    ),
            ],
        ];
    }
}
