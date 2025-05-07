<?php

namespace App\Http\Controllers\Words;

use App\Http\Controllers\Controller;
use App\Http\Requests\EntriesValidator;
use App\Services\Words\FavoriteWordsService;
use App\Services\Words\WordsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class WordsController extends Controller
{
    private const INTERNAL_SERVER_ERROR_MESSAGE = 'Internal server error.';

    public function __construct(
        private WordsService $wordsService,
        private FavoriteWordsService $favoriteWordsService
    ) {}

    /**
     * GET /entries/en?search=word&limit=10
     *
     * @param \App\Http\Requests\EntriesValidator $request
     * @return void
     */
    public function entries(EntriesValidator $request)
    {
        try {
            $searchedWord = $request->get('search');

            $wordsLimit = $request->get('limit', 10);
            
            $page = $request->get('page', 1);

            $entries = $this->wordsService->getEntries($searchedWord, $wordsLimit, $page);

            return response()->json($entries);
        } catch (Throwable $t) {
            Log::error($t);

            return response()->json([
                'message' => self::INTERNAL_SERVER_ERROR_MESSAGE,
            ], 400);
        }
    }

    /**
     * GET /entries/{word}
     *
     * @param string $word
     * @return JsonResponse
     */
    public function entry(string $word): JsonResponse
    {
        try {
            $entryData = $this->wordsService->getEntry($word);

            $entry = $entryData['wordData'];

            if (!$entry) {

                return response()->json([
                    'message' => 'Word not found',
                ], 400);
            }

            return response()
                ->json($entry)
                ->header('x-cache', $entryData['cacheStatus']);
        } catch (Throwable $t) {
            Log::error($t);

            return response()->json([
                'message' => self::INTERNAL_SERVER_ERROR_MESSAGE,
            ], 400);
        }
    }

    /**
     * POST /entries/en/{word}/favorite
     *
     * @param string $word
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function favorite(string $word): JsonResponse|Response
    {
        try {
            $favoriteStatus = $this->favoriteWordsService->setFavorite($word);

            if (!$favoriteStatus) {

                return response()->json([
                    'message' => 'Word not found',
                ], 400);
            }

            return response(status: 204);
        } catch (Throwable $t) {
            Log::error($t);

            return response()->json([
                'message' => self::INTERNAL_SERVER_ERROR_MESSAGE,
            ], 400);
        }
    }

    /**
     * DELETE /entries/en/{word}/unfavorite
     *
     * @param string $word
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function unfavorite(string $word): JsonResponse|Response
    {
        try {
            $this->favoriteWordsService->unsetFavorite($word);

            return response(status: 204);
        } catch (Throwable $t) {
            Log::error($t);

            return response()->json([
                'message' => self::INTERNAL_SERVER_ERROR_MESSAGE,
            ], 400);
        }
    }
}
