<?php

namespace App\Services\Words;

use App\Repositories\Words\WordsRepository;

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
        private WordsRepository $wordsRepository
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
}
