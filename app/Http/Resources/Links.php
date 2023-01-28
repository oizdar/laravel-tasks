<?php

namespace App\Http\Resources;

use OpenApi\Annotations as OA;

/**
* @OA\Schema(
*     @OA\Xml(name="Links"),
*     @OA\Property(property="first", type="string",  example="http://example.com/api/element?page=1"),
*     @OA\Property(property="last", type="string",  example="http://example.com/api/element?page=10"),
*     @OA\Property(property="next", type="string",  example="http://example.com/api/element?page=5"),
*     @OA\Property(property="prev", type="string",  example="http://example.com/api/element?page=3"),
* )
*/
class Links
{
}
