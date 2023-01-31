<?php

namespace App\Http\Resources;

use App\Models\Task;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Activitylog\Models\Activity;

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
