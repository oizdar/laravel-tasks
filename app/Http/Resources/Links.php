<?php

namespace App\Http\Resources;

use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(property: 'first', type: 'string', example: 'http://example.com/api/element?page=1'),
        new OA\Property(property: 'last', type: 'string', example: 'http://example.com/api/element?page=10'),
        new OA\Property(property: 'next', type: 'string', format: 'http://example.com/api/element?page=5'),
        new OA\Property(property: 'prev', type: 'string', example: 'http://example.com/api/element?page=3'),
    ],
    xml: new OA\Xml(name: "Links")
)]
class Links
{
}
