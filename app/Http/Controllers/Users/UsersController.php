<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Repositories\Auth\UsersRepository;
use App\Services\Users\UsersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use \Throwable;

class UsersController extends Controller
{
    /**
     * Constructor for the UsersController.
     *
     * Injects the UsersService dependency.
     *
     * @param UsersService $usersService
     */
    public function __construct(
        private readonly UsersService $usersService
    ) {}

    /**
     * GET /user/me
     *
     * Retrieves the current user's profile.
     *
     * @return JsonResponse|mixed
     */
    public function me(): JsonResponse
    {
        try {
            $userProfile = $this->usersService->getUserProfile();

            return response()->json($userProfile);
        } catch (Throwable $t) {

            Log::error($t);

            return response()->json([
                'message' => 'Internal server error.',
            ], 400);
        }
    }
}
