<?php
namespace App\Docs;

use OpenApi\Annotations as OA;
/**
 * @OA\Info(
 *     description="This is a sample TasksList"
 *     version="1.0.0",
 *     title="Laravel Tasks",
 *     @OA\Contact(
 *         email="malinowski.rad@gmail.com"
 *     ),
 * )
 * @OA\Tag(
 *     name="pet",
 *     description="Everything about your Pets",
 *     @OA\ExternalDocumentation(
 *         description="Find out more",
 *         url="http://swagger.io"
 *     )
 * )
 * @OA\Tag(
 *     name="store",
 *     description="Access to Petstore orders",
 * )
 * @OA\Tag(
 *     name="user",
 *     description="Operations about user",
 *     @OA\ExternalDocumentation(
 *         description="Find out more about store",
 *         url="http://swagger.io"
 *     )
 * )
 * @OA\Server(
 *     description="SwaggerHUB API Mocking",
 *     url="https://virtserver.swaggerhub.com/swagger/Petstore/1.0.0"
 * )
 * @OA\ExternalDocumentation(
 *     description="Find out more about Swagger",
 *     url="http://swagger.io"
 * )
 */
class LaravelTasks
{
}
