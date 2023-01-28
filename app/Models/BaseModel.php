<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
* @OA\Schema(
* @OA\Property(property="created_at", type="string", format="date-time", description="Initial creation timestamp", readOnly="true"),
* @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp", readOnly="true"),
* @OA\Property(property="deleted_at", type="string", format="date-time", description="Soft delete timestamp", readOnly="true"),
* )
*/
abstract class BaseModel extends Model {}
