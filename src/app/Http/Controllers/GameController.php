<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Resources\GameResource;
use App\Http\Repositories\GameRepository;

class GameController extends Controller
{

    private $gameRepository;

    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    /**
     * @OA\Get(
     *     path="/v1/games",
     *     summary="returns all the games",
     *     description="returns all the games stored in database",
     *     operationId="index",
     *     tags={"Games"},
     *     security={{"passport":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Operation successful",
     *         @OA\MediaType(
     *            mediaType="application/json",
     *          )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     * )
     */
    /**
     * Return list of all games
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(
            GameResource::collection($this->gameRepository->all()), Response::HTTP_OK
        );
    }
    /**
     * @OA\Get(
     *     path="/v1/games/{uuid}",
     *     summary="returns data about a game.",
     *     description="returns all information about a given game",
     *     tags={"Games"},
     *     security={{"passport":{}}},
     *     @OA\Parameter(
     *          name="uuid",
     *          in="path",
     *          required=true,
     *          description="game uuid",
     *          @OA\Schema(
     *              type="string",
     *              format="uuid",
     *              example="5ba1c4e3-5305-4beb-bff1-38f7eb6f1850"
     *          )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="Operation successful",
     *         @OA\MediaType(
     *            mediaType="application/json",
     *          )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     * )
     */

    /**
     * Return a game
     *
     * @param String $uuid
     * @return \Illuminate\Http\Response
     */
    public function find($uuid)
    {
        $game = $this->gameRepository->find($uuid);
        if ($game)
        {
            return response()->json(GameResource::make($game), Response::HTTP_OK );
        }
        return response()->json(null, Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *     path="/v1/games",
     *     summary="This endpoint is called to create a new game.",
     *     description="This endpoint is called to create a new game.",
     *     operationId="store",
     *     tags={"Games"},
     *     security={{"passport":{}}},
     *      @OA\Response(
     *         response=201,
     *         description="Resource created",
     *         @OA\MediaType(
     *            mediaType="application/json",
     *          )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     * )
     */
    /**
     * Create a new game.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $newGame = $this->gameRepository->create();
        return response()->json(
            ['uuid' => $newGame->uuid], Response::HTTP_CREATED
        );
    }
}
