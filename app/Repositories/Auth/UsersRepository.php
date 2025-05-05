<?php

namespace App\Repositories\Auth;

use App\Models\User;

/**
 * Repository for user operations.
 *
 * Responsible for handling all the database operations related to the User model.
 */
class UsersRepository
{
    /**
     * Create a new user in the database.
     *
     * @param array $userData
     * @return void
     */
    public function create(array $userData)
    {
        User::create($userData);
    }

    /**
     * Get the current user.
     *
     * @return User
     */
    public function getCurrentUser(): User
    {
        return auth()->user();
    }
}
