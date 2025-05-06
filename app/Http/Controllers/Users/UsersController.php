<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchHistoryValidator;
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

    /**
     * GET /user/me/history
     *
     * Retrieves the current user's search history.
     *
     * @return JsonResponse|mixed
     */
    public function getHistory(SearchHistoryValidator $request): JsonResponse
    {
        try {

            $limit = $request->get('limit', 10);

            $page = $request->get('page', 1);

            $history = $this->usersService->getHistory($limit, $page);

            return response()->json($history);
        } catch (Throwable $t) {

            Log::error($t);

            return response()->json([
                'message' => 'Internal server error.',
            ], 400);
        }
    }
}
