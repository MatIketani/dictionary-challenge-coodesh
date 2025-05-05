<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    /**
     * GET /
     *
     * Returns a JSON response with the message "Fullstack Challenge 🏅 - Dictionary".
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json([
            'message' => 'Fullstack Challenge 🏅 - Dictionary'
        ]);
    }
}
