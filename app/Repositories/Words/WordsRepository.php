<?php

namespace App\Repositories\Words;

use App\Models\Words\Word;

class WordsRepository
{
    /**
     * Bulk insert the array of data into the database.
     *
     * @param array $data
     * @return void
     */
    public function bulkInsert(array $data)
    {
        Word::insert($data);
    }

    /**
     * Get the paginated entries.
     *
     * @param string $searchedWord
     * @param int $limit
     * @param int $page
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function getPaginatedEntries(string $searchedWord, int $limit = 10, int $page = 1)
    {
        return Word::where('word', 'like', '%' . $searchedWord . '%')
            ->select('word')
            ->paginate($limit, ['*'], 'page', $page);
    }

    /**
     * Get the id of a word.
     *
     * @param string $word
     * @return int
     */
    public function getIdByWord(string $word): int|null
    {
        return Word::where('word', $word)->select('id')->first()?->id;
    }
}
