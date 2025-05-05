<?php

namespace App\Services\Users;

use App\Models\User;
use App\Repositories\Auth\UsersRepository;

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
        private readonly UsersRepository $usersRepository
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
}
