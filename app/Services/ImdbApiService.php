<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ImdbApiService
{
    protected string $baseUrl;

    protected string $imageUrl;

    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.api_key');
        $this->baseUrl = config('services.tmdb.base_url', 'https://api.themoviedb.org/3');
        $this->imageUrl = config('services.tmdb.image_url', 'https://image.tmdb.org/t/p');
    }

    public function search(string $query): array
    {
        $cacheKey = 'tmdb_search_' . md5($query);

        return Cache::remember($cacheKey, 3600, function () use ($query) {
            $response = Http::timeout(10)->get("{$this->baseUrl}/search/movie", [
                'api_key' => $this->apiKey,
                'query' => $query,
                'language' => 'en-US',
                'page' => 1,
            ]);

            if ($response->failed()) {
                throw new \RuntimeException('TMDB API error: ' . $response->body());
            }

            $results = $response->json()['results'] ?? [];

            return array_map(fn($item) => [
                'tmdb_id' => $item['id'],
                'title' => $item['title'],
                'year' => $item['release_date'] ? substr($item['release_date'], 0, 4) : null,
                'poster_url' => $item['poster_path']
                    ? "{$this->imageUrl}/w500{$item['poster_path']}"
                    : null,
            ], $results);
        });
    }

    public function titleDetails(int $tmdbId): array
    {
        $cacheKey = 'tmdb_details_' . $tmdbId;

        return Cache::remember($cacheKey, 604800, function () use ($tmdbId) {
            $response = Http::timeout(10)->get("{$this->baseUrl}/movie/{$tmdbId}", [
                'api_key' => $this->apiKey,
                'language' => 'en-US',
                'append_to_response' => 'credits',
            ]);

            if ($response->failed()) {
                throw new \RuntimeException('TMDB API error: ' . $response->body());
            }

            $data = $response->json();

            $directors = collect($data['credits']['crew'] ?? [])
                ->where('job', 'Director')
                ->pluck('name')
                ->implode(', ');

            $actors = collect($data['credits']['cast'] ?? [])
                ->take(5)
                ->pluck('name')
                ->implode(', ');

            return [
                'tmdb_id' => $data['id'],
                'imdb_id' => $data['imdb_id'],
                'title' => $data['title'],
                'original_title' => $data['original_title'],
                'year' => $data['release_date'] ? substr($data['release_date'], 0, 4) : null,
                'release_date' => $data['release_date'],
                'runtime' => $data['runtime'] ? $data['runtime'] . ' min' : null,
                'plot' => $data['overview'],
                'poster_url' => $data['poster_path']
                    ? "{$this->imageUrl}/w500{$data['poster_path']}"
                    : null,
                'backdrop_url' => $data['backdrop_path']
                    ? "{$this->imageUrl}/w1280{$data['backdrop_path']}"
                    : null,
                'imdb_rating' => isset($data['vote_average'])
                    ? (string) $data['vote_average']
                    : null,
                'imdb_votes' => isset($data['vote_count'])
                    ? (string) $data['vote_count']
                    : null,
                'genres' => isset($data['genres'])
                    ? implode(', ', array_column($data['genres'], 'name'))
                    : null,
                'directors' => $directors,
                'actors' => $actors,
                'language' => $data['spoken_languages'][0]['iso_639_1'] ?? null,
                'country' => $data['production_countries'][0]['iso_3166_1'] ?? null,
            ];
        });
    }
}
