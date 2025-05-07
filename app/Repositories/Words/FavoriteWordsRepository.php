<?php

namespace App\Repositories\Words;

use App\Models\Words\FavoriteWord;
use Illuminate\Pagination\LengthAwarePaginator;

class FavoriteWordsRepository
{
    /**
     * Save a favorite word, if it already exists, nothing will be done.
     *
     * @param int $userId
     * @param int $wordId
     * @return void
     */
    public function save(int $userId, int $wordId): void
    {
        FavoriteWord::createOrFirst([
            'user_id' => $userId,
            'word_id' => $wordId,
        ]);
    }

    /**
     * Delete a favorite word.
     *
     * @param int $userId
     * @param int $wordId
     * @return void
     */
    public function delete(int $userId, int $wordId): void
    {
        FavoriteWord::where('user_id', $userId)
            ->where('word_id', $wordId)
            ->delete();
    }

    /**
     * Get paginated favorites.
     *
     * @param int $userId
     * @param int $page
     * @param int $limit
     * @return LengthAwarePaginator
     */
    public function getPaginatedFavorites(int $userId, int $page, int $limit): LengthAwarePaginator
    {
        return FavoriteWord::where('user_id', $userId)
            ->with('word')
            ->orderBy('created_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page);
    }
}
