<?php

namespace App\Http\Resources;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $id
 * @property string $first_name
 * @property string $last_name
 * @property string $city
 * @property Role $role
 */
class UserResourceBasic extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'city' => $this->city != null ? $this->city : '?',
            'role' => $this->role->name,
        ];
    }
}
