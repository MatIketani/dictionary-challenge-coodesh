<?php

namespace App\Services\Words;

use App\Models\Words\FavoriteWord;
use App\Repositories\Auth\UsersRepository;
use App\Repositories\Words\FavoriteWordsRepository;
use App\Repositories\Words\WordsRepository;

class FavoriteWordsService
{
    /**
     * Constructor method.
     *
     * @param \App\Repositories\Words\WordsRepository $wordsRepository
     */
    public function __construct(
        private WordsRepository $wordsRepository,
        private UsersRepository $usersRepository,
        private FavoriteWordsRepository $favoriteWordsRepository
    ) {}

    /**
     * Set a word as favorite.
     *
     * @param string $word
     * @return bool
     */
    public function setFavorite(string $word): bool
    {
        $currentUser = $this->usersRepository->getCurrentUser();

        $wordId = $this->wordsRepository->getIdByWord($word);

        if (!$wordId) {

            return false;
        }

        $this->favoriteWordsRepository->save($currentUser->id, $wordId);

        return true;
    }

    /**
     * Unset a word as favorite.
     *
     * @param string $word
     * @return void
     */
    public function unsetFavorite(string $word): void
    {
        $currentUser = $this->usersRepository->getCurrentUser();

        $wordId = $this->wordsRepository->getIdByWord($word);

        if (!$wordId) {

            return;
        }

        $this->favoriteWordsRepository->delete($currentUser->id, $wordId);
    }

    /**
     * Get paginated favorite words for the current user.
     *
     * @param int $page
     * @param int $limit
     * @return array{hasNextPage: bool, hasPrevPage: bool, page: int, results: array, totalDocs: int, totalPages: int}
     */
    public function getPaginatedFavorites(int $page, int $limit): array
    {
        $currentUser = $this->usersRepository->getCurrentUser();

        $favoritesPaginator = $this->favoriteWordsRepository->getPaginatedFavorites($currentUser->id, $page, $limit);

        $favorites = collect($favoritesPaginator->items())->map(fn (FavoriteWord $favorite) => [
            'word' => $favorite->word->word,
            'created_at' => $favorite->created_at,
        ]);

        return [
            'results' => $favorites->toArray(),
            'totalDocs' => $favoritesPaginator->total(),
            'page' => $favoritesPaginator->currentPage(),
            'totalPages' => $favoritesPaginator->lastPage(),
            'hasNext' => $favoritesPaginator->hasMorePages(),
            'hasPrev' => $favoritesPaginator->currentPage() > 1,
        ];
    }
}
