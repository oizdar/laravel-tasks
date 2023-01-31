<?php

namespace App\Http\Resources;

use App\Models\Task;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;
use Spatie\Activitylog\Models\Activity;

/**
 * @OA\Schema(
 *     @OA\Xml(name="TaskResourceForCollection"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="title", type="string",  example="Example task title"),
 *     @OA\Property(property="due_date", type="string", format="date", example="2019-02-25"),
 *     @OA\Property(property="completed", type="boolean", example=false),
 *     @OA\Property(property="activities", type="int", example=9),
 * )
 */
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
            'completed' => $this->completed
        ];
    }
}
