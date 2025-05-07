<?php

namespace App\Services\Words;

use App\Http\Clients\FreeDictionaryApiClient;
use App\Repositories\Auth\UsersRepository;
use App\Repositories\Words\SearchHistoriesRepository;
use App\Repositories\Words\WordsRepository;
use Cache;

class WordsService
{
    /**
     * Constructor method.
     *
     * Injects the words repository.
     *
     * @param \App\Repositories\Words\WordsRepository $wordsRepository
     */
    public function __construct(
        private WordsRepository $wordsRepository,
        private SearchHistoriesRepository $searchHistoriesRepository,
        private UsersRepository $usersRepository
    ) {}

    /**
     * Gets the entries of a searched word.
     *
     * @param string $searchedWord
     * @param int $limit
     * @param int $page
     * @return array{hasNext: bool, hasPrev: bool, page: int, results: array, totalDocs: int, totalPages: int}
     */
    public function getEntries(string $searchedWord, int $limit = 10, int $page = 1)
    {
        $paginatedEntries = $this->wordsRepository->getPaginatedEntries($searchedWord, $limit, $page);

        $items = $paginatedEntries->items();

        return [
            'results' => collect($items)->pluck('word')->toArray(),
            'totalDocs' => $paginatedEntries->total(),
            'page' => $paginatedEntries->currentPage(),
            'totalPages' => $paginatedEntries->lastPage(),
            'hasNext' => $paginatedEntries->hasMorePages(),
            'hasPrev' => $paginatedEntries->currentPage() > 1,
        ];
    }

    /**
     * Get word from Free Dictionary API.
     *
     * @param string $word
     * @return array{isCached: bool, wordData: array|null}
     */
    public function getEntry(string $word): array
    {
        $cacheStatus = 'HIT';

        $apiResponse = Cache::remember("words_{$word}", 60 * 60 * 24, function () use ($word, &$cacheStatus) {

            $cacheStatus = 'MISS';

            return FreeDictionaryApiClient::getWord($word);
        });

        if (!is_array($apiResponse)) {

            return [
                'cacheStatus' => null,
                'wordData' => null,
            ];
        }

        $currentUser = $this->usersRepository->getCurrentUser();

        $this->searchHistoriesRepository->save($currentUser->id, $word);

        return [
            'cacheStatus' => $cacheStatus,
            'wordData' => $apiResponse,
        ];
    }
}
