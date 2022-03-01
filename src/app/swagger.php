<?php
//Generic API description for documentation purposes. Each call's documentation is contained in the relevant controller.
/**
 *
 * @OA\Info(
 *     version="1.0.1",
 *     title="Tic Tac Toe",
 *     description="REST Api",
 *     @OA\Contact(
 *         name="Gianluca Siciliano",
 *         email="gianluca.siciliano.79@gmail.com"
 *     ),
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="API Server"
 * )
 */
/**
 * @OA\SecurityScheme(
 *     type="oauth2",
 *     name="passport",
 *     securityScheme="passport",
 *     in="header",
 *     scheme={"http","https"},
 *     @OA\Flow(
 *         flow="clientCredentials",
 *         tokenUrl="/oauth/token",
 *         scopes={}
 *     )
 *  )
 */
