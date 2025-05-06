<?php

namespace App\Http\Clients;

use Illuminate\Support\Facades\Http;

class FreeDictionaryApiClient
{
    private const BASE_URL = 'https://api.dictionaryapi.dev/api/v2/entries/en';

    /**
     * Get word from Free Dictionary API.
     *
     * @param string $word
     * @return array|null
     */
    public static function getWord(string $word): array|null
    {
        $url = self::BASE_URL . '/' . $word;

        $response = Http::get($url);

        if ($response->notFound()) {

            return null;
        }

        return $response->json();
    }
}
