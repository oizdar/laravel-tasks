<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     @OA\Xml(name="Task"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="title", type="string",  example="Example task title"),
 *     @OA\Property(property="description", type="text",  example="Example task description", nullable=true),
 *     @OA\Property(property="due_date", type="string", format="date-time", example="2019-02-25 12:59:20"),
 *     @OA\Property(property="completed", type="boolean", example=false),
 *     @OA\Property(property="created_at", ref="#/components/schemas/BaseModel/properties/created_at"),
 *     @OA\Property(property="updated_at", ref="#/components/schemas/BaseModel/properties/updated_at"),
 *     @OA\Property(property="deleted_at", ref="#/components/schemas/BaseModel/properties/deleted_at")
 * )
 */
class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'due_date',
    ];

    protected $attributes = [
        'completed' => false
    ];
}
