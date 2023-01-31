<?php

namespace App\Http\Resources;

use App\Models\Task;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;
use Spatie\Activitylog\Models\Activity;

#[OA\Schema(
    required: ['id', 'title', 'completed'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', readOnly: true, example: 1),
        new OA\Property(property: 'title', type: 'string', example: 'Example task title'),
        new OA\Property(property: 'description', type: 'string', example: 'Example task title', nullable: true),
        new OA\Property(property: 'due_date', type: 'string', format: 'date', example: '2025-02-25', nullable: true),
        new OA\Property(property: 'completed', type: 'boolean', example: true, nullable: false),
        new OA\Property(property: 'activities', type: 'integer', readOnly: true, example: 1),
    ],
    xml: new OA\Xml(name: "TaskResource")
)]
class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>|Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $activitiesCount = Activity::where('subject_type', '=', Task::class)
            ->where('subject_id', '=', $this->id)
            ->count();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'due_date' => $this->due_date,
            'completed' => $this->completed,
            'activities' => $activitiesCount,
        ];
    }
}
