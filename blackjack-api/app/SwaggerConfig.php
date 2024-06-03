<?php

namespace App;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     description="This is a simple Blackjack API. For more information, please visit the developer's portfolio at [Ignacio Albiol's Portfolio](https://ignacioalbiol.tech/).",
 *     title="Blackjack API",
 *     version="1.0.0",
 * )
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Blackjack API Server"
 * )
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Bearer token authentication",
 *     name="Bearer",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth"
 * )
 * @OA\Tag(
 *     name="Register",
 *     description="API Endpoints for Registering Users"
 * )
 * @OA\Tag(
 *     name="Login",
 *     description="API Endpoints for Logging in Users"
 * )
 * @OA\Tag(
 *     name="Users",
 *     description="API Endpoints of Users"
 * )
 * @OA\Tag(
 *     name="Games",
 *     description="API Endpoints of Games"
 * )
 * @OA\Tag(
 *     name="Ranking",
 *     description="API Endpoints of Games"
 * )
 */

class SwaggerConfig
{
}
