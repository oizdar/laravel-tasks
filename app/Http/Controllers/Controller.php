<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *    title="Laravel Tasks Management API",
 *    version="1.0.0",
 *    @OA\Contact(
 *      email="malinowski.rad@gmail.com"
 *    )
 * )
 * @OA\Server(
 *      url="/api",
 *      description="Demo API Server"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
}
