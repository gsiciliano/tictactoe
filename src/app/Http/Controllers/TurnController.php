<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Requests\TurnRequest;
use App\Http\Services\GameService;
use App\Http\Resources\GameResource;
use App\Http\Resources\TurnResource;
use App\Http\Repositories\GameRepository;
use App\Http\Repositories\TurnRepository;

class TurnController extends Controller
{
    private $turnRepository;
    private $gameRepository;

    public function __construct(TurnRepository $turnRepository, GameRepository $gameRepository)
    {
        $this->turnRepository = $turnRepository;
        $this->gameRepository = $gameRepository;
    }

    /**
     * @OA\Get(
     *     path="/v1/games/{uuid}/turns",
     *     summary="returns all turns from a game UUID",
     *     description="returns all turns from a game UUID",
     *     operationId="index",
     *     security={{"passport":{}}},
     *     tags={"Turns"},
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
     * Get all turns from a game UUID.
     *
     * @param String $uuid
     * @return \Illuminate\Http\Response
     */
    public function index($uuid)
    {
        return response()->json(
            TurnResource::collection($this->turnRepository->get($uuid)), Response::HTTP_OK
        );
    }

    /**
     * @OA\Post(
     *     path="/v1/games/{uuid}/turns",
     *     summary="Create a new turn.",
     *     description="Create a new turn",
     *     operationId="store",
     *     tags={"Turns"},
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
     *      @OA\RequestBody(
     *          description="Turn",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/turnRequest")
     *          )
     *      ),
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
     * Create a new turn.
     * @param App\Http\Requests\TurnRequest
     * @param String $uuid
     * @return \Illuminate\Http\Response
     */
    public function store(TurnRequest $request, $uuid)
    {
        $validated = $request->validated();
        $new_turn = $this->turnRepository->create($validated);

        $gameService = new GameService($new_turn->game);
        $game_winner = $gameService->getWinner();
        $game_status = $gameService->getStatus($game_winner);
        $game = $this->gameRepository->update($uuid, $game_winner, $game_status);

        return response()->json(
            ['game' => [GameResource::make($game)]], Response::HTTP_CREATED
        );
    }

}


