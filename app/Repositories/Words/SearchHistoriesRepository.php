<?php

namespace App\Repositories\Words;

use App\Models\Words\SearchHistory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchHistoriesRepository
{
    /**
     * Save the search history for a user.
     *
     * @param int $userId
     * @param string $word
     * @return void
     */
    public function save(int $userId, string $word)
    {
        SearchHistory::create([
            'user_id' => $userId,
            'word' => $word,
        ]);
    }

    /**
     * Get the search history for a user.
     *
     * @param int $userId
     * @param int $limit
     * @param int $page
     * @return LengthAwarePaginator
     */
    public function getPaginatedByUserId(int $userId, int $limit, int $page): LengthAwarePaginator
    {
        return SearchHistory::where('user_id', $userId)
            ->select('word', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page);
    }
}
