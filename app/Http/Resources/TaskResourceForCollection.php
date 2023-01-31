<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    required: ['id', 'title', 'completed'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', readOnly: true, example: 1),
        new OA\Property(property: 'title', type: 'string', example: 'Example task title'),
        new OA\Property(property: 'description', type: 'string', example: 'Example task title', nullable: true),
        new OA\Property(property: 'due_date', type: 'string', format: 'date', example: '2025-02-25', nullable: true),
        new OA\Property(property: 'completed', type: 'boolean', example: true, nullable: false),
    ],
    xml: new OA\Xml(name: "TaskResourceForCollection")
)]
class TaskResourceForCollection extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>|Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'due_date' => $this->due_date,
            'completed' => $this->completed,
            'activities' => $this->activitiesCount,
        ];
    }
}
