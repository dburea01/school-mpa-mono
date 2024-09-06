<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @property int $id
 * @property string $work_id
 * @property string $user_id
 * @property int $is_absent
 * @property float $note
 * @property int $appreciation_id
 * @property string $comment
 * @property string $created_by
 * @property string $updated_by
 * @property string $created_at
 */
class ResultResource extends JsonResource
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
            'work_id' => $this->work_id,
            'user_id' => $this->user_id,
            'is_absent' => $this->is_absent,
            'note' => $this->note,
            'appreciation_id' => $this->appreciation_id,
            'comment' => $this->comment,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
