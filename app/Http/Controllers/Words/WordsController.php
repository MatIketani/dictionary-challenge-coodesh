<?php

namespace App\Http\Controllers\Words;

use App\Http\Controllers\Controller;
use App\Http\Requests\EntriesValidator;
use App\Services\Words\WordsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class WordsController extends Controller
{
    public function __construct(
        private WordsService $wordsService
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
                'message' => 'Internal server error',
            ], 400);
        }
    }

    /**
     * GET /entries/{word}
     *
     * @param string $word
     * @return void
     */
    public function entry(string $word)
    {
        try {

            $entry = $this->wordsService->getEntry($word);

            if (!$entry) {

                return response()->json([
                    'message' => 'Word not found',
                ], 400);
            }

            return response()->json($entry);
        } catch (Throwable $t) {
            Log::error($t);

            return response()->json([
                'message' => 'Internal server error',
            ], 400);
        }
    }
}
