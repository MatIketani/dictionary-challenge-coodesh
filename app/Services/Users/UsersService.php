<?php

namespace App\Services\Users;

use App\Models\User;
use App\Models\Words\SearchHistory;
use App\Repositories\Auth\UsersRepository;
use App\Repositories\Words\SearchHistoriesRepository;

class UsersService
{
    /**
     * Constructor for the UsersService.
     *
     * Injects the UsersRepository dependency.
     *
     * @param UsersRepository $usersRepository
     */
    public function __construct(
        private readonly UsersRepository $usersRepository,
        private readonly SearchHistoriesRepository $searchHistoriesRepository
    ) {}

    /**
     * Get the current user's profile.
     *
     * @return array{email: string, id: string, name: string}
     */
    public function getUserProfile(): array
    {
        $user = $this->usersRepository->getCurrentUser();

        return [
            'id' => encrypt($user->id),
            'name' => $user->name,
            'email' => $user->email,
        ];
    }

    /**
     * Get the current user's search history.
     *
     * @param int $limit
     * @param int $page
     * @return array{hasNext: bool, hasPrev: bool, page: int, results: array, totalDocs: int, totalPages: int}
     */
    public function getHistory(int $limit = 10, int $page = 1): array
    {
        $user = $this->usersRepository->getCurrentUser();

        $paginatedHistory = $this->searchHistoriesRepository->getPaginatedByUserId(
            userId: $user->id,
            limit: $limit,
            page: $page
        );

        $results = collect($paginatedHistory->items())->map(
            fn(SearchHistory $history) => [
                'word' => $history->word,
                'added' => $history->created_at->toIso8601ZuluString()
            ]
        );

        return [
            'results' => $results->toArray(),
            'totalDocs' => $paginatedHistory->total(),
            'page' => $paginatedHistory->currentPage(),
            'totalPages' => $paginatedHistory->lastPage(),
            'hasNext' => $paginatedHistory->hasMorePages(),
            'hasPrev' => $paginatedHistory->currentPage() > 1
        ];
    }
}
