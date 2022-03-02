<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Services\GameService;
use App\Http\Resources\GameResource;
use App\Http\Resources\TurnResource;
use App\Rules\ItsNotPlayerTurnRule;
use App\Rules\LocationAlreadyFilledRule;
use App\Http\Repositories\GameRepository;
use App\Http\Repositories\TurnRepository;
use Illuminate\Support\Facades\Validator;

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
    *     @OA\Schema(
    *         schema="turnRequest",
    *         type="object",
    *         @OA\Property(
    *             property="player_nr",
    *             type="string",
    *             example = "1"
    *         ),
    *         @OA\Property(
    *             property="location",
    *              type="string",
    *              example="1"
    *         ),
    *     )
    */
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
     * @param \Illuminate\Http\Request
     * @param String $uuid
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $uuid)
    {
        $game = $this->gameRepository->find($uuid);
        if (!$game){
            return response()->json([], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(),[
            'location' => ['required','integer','gte:1','lte:9', new LocationAlreadyFilledRule($game)],
            'player_nr' => ['required','integer','gte:1','lte:2', new ItsNotPlayerTurnRule($game)],
        ]);

        $validator->after(function ($validator) use ($game) {
            if ($game->status) {
                $validator->errors()->add('game', 'Game over!');
            }
        });
        $validated = $validator->validated();

        $new_turn = $this->turnRepository->create(array_merge($validated,['game_id' => $game->id]));

        $gameService = new GameService($new_turn->game);
        $game_winner = $gameService->getWinner();
        $game_status = $gameService->getStatus($game_winner);
        $game = $this->gameRepository->update($uuid, $game_winner, $game_status);

        return response()->json(
            ['game' => [GameResource::make($game)]], Response::HTTP_CREATED
        );
    }

}


