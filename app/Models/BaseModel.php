<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    required: ['title', 'completed'],
    properties: [
        new OA\Property(property: 'created_at', description: 'Initial creation timestamp', type: 'string', format: 'date-time', readOnly: true),
        new OA\Property(property: 'updated_at', description: 'Last update timestamp', type: 'string', format: 'date-time', readOnly: true),
        new OA\Property(property: 'deleted_at', description: 'Soft delete timestamp', type: 'string', format: 'date-time', readOnly: true),
    ]
)]
abstract class BaseModel extends Model
{
}
