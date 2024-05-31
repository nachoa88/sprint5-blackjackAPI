<?php

namespace App;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     description="This is a simple Blackjack API.",
 *     title="Blackjack API",
 *     version="1.0.0",
 *     @OA\Contact(
 *         email="your-email@example.com"
 *     ),
 * )
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Blackjack API Server"
 * )
 * @OA\Tag(
 *     name="Auth",
 *     description="API Endpoints for Authentication"
 * )
 * @OA\Tag(
 *     name="Users",
 *     description="API Endpoints of Users"
 * )
 * @OA\Tag(
 *     name="Games",
 *     description="API Endpoints of Games"
 * )
 */

class SwaggerConfig
{
}
