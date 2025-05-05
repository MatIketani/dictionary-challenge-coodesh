<?php

namespace App\Services\Auth;

use App\Repositories\Auth\UsersRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Token;

/**
 * Class that contains the business logic for the authentication process.
 */
class AuthService
{
    /**
     * Constructor for the AuthService.
     *
     * Injects the UsersRepository dependency.
     *
     * @param UsersRepository $usersRepository
     */
    public function __construct(
        private readonly UsersRepository $usersRepository
    ) {}

    /**
     * Handle the sign up process.
     *
     * @param array $requestData
     * @return void
     */
    public function signUp(array $requestData)
    {
        $userData = $requestData;

        $userData['password'] = Hash::make($userData['password']);

        $this->usersRepository->create($userData);
    }

    /**
     * Handle the sign in process.
     *
     * @param array $requestData
     * @return bool|array
     */
    public function signIn(array $requestData): array|false
    {
        if (!Auth::attempt($requestData)) {

            return false;
        }

        $user = auth()->user();

        // Revoke all the existing tokens, since a new one is being generated.
        $user->tokens->each(fn (Token $token) => $token->revoke());

        return [
            'id' => encrypt($user->id),
            'name' => $user->name,
            'token' => $user->createToken('Dictionary API Token')->accessToken,
        ];
    }
}
