<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignInValidator;
use App\Http\Requests\SignUpValidator;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Constructor for the AuthController.
     *
     * Injects the AuthService dependency.
     *
     * @param AuthService $authService
     */
    public function __construct(
        private readonly AuthService $authService
    ) {}

    /**
     * POST /auth/signup
     *
     * Creates a new user.
     *
     * @param SignUpValidator $request
     * @return JsonResponse
     */
    public function signUp(SignUpValidator $request): JsonResponse
    {
        try {
            $requestData = $request->validated();

            $this->authService->signUp($requestData);

            $signInData = $this->authService->signIn($requestData);

            return response()->json([
                'id' => $signInData['id'],
                'name' => $signInData['name'],
                'token' => 'Bearer ' . $signInData['token'],
            ]);
        } catch (Throwable $t) {

            Log::error($t);

            return response()->json([
                'message' => 'Internal server error.',
            ], 500);
        }
    }

    /**
     * POST /auth/signin
     *
     * Signs in a user.
     *
     * @param SignInValidator $request
     * @return JsonResponse
     */
    public function signIn(SignInValidator $request): JsonResponse
    {
        try {
            $requestData = $request->validated();
            
            $signInData = $this->authService->signIn($requestData);

            if (!$signInData) {

                return response()->json([
                    'message' => 'Invalid credentials.',
                ], 400);
            }

            return response()->json([
                'id' => $signInData['id'],
                'name' => $signInData['name'],
                'token' => 'Bearer ' . $signInData['token'],
            ]);
        } catch (Throwable $t) {

            Log::error($t);

            return response()->json([
                'message' => 'Internal server error.',
            ], 500);
        }
    }
}
