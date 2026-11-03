<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class YouTubeService
{
    protected string $apiKey;
    protected string $apiBase = 'https://www.googleapis.com/youtube/v3';

    public function __construct()
    {
        $this->apiKey = config('services.youtube.key', '');
    }

    // ──────────────────────────────────────────────────────
    //  URL Parsing
    // ──────────────────────────────────────────────────────

    /**
     * Ekstrak YouTube Video ID dari berbagai format URL.
     *
     * Format yang didukung:
     *   https://www.youtube.com/watch?v=VIDEO_ID
     *   https://youtu.be/VIDEO_ID
     *   https://music.youtube.com/watch?v=VIDEO_ID
     *   https://www.youtube.com/embed/VIDEO_ID
     *   https://www.youtube.com/shorts/VIDEO_ID
     *   VIDEO_ID saja (11 karakter)
     *
     * @return string|null  Video ID atau null kalau format tidak dikenali
     */
    public function extractVideoId(string $input): ?string
    {
        $input = trim($input);

        // Kalau input sudah berupa ID langsung (11 karakter alphanumeric + - _)
        if (preg_match('/^[a-zA-Z0-9_\-]{11}$/', $input)) {
            return $input;
        }

        // Parse URL
        $parsed = parse_url($input);
        if (! isset($parsed['host'])) {
            return null;
        }

        $host = strtolower($parsed['host']);
        $host = preg_replace('/^www\./', '', $host);

        // youtu.be/VIDEO_ID
        if ($host === 'youtu.be') {
            $id = ltrim($parsed['path'] ?? '', '/');
            // buang query string kalau ada
            return $this->validateId(explode('?', $id)[0]);
        }

        // youtube.com/watch?v=VIDEO_ID  atau  music.youtube.com/watch?v=VIDEO_ID
        if (in_array($host, ['youtube.com', 'music.youtube.com', 'm.youtube.com'])) {
            $path = $parsed['path'] ?? '';

            // /watch?v=
            if (str_starts_with($path, '/watch')) {
                parse_str($parsed['query'] ?? '', $query);
                return $this->validateId($query['v'] ?? null);
            }

            // /embed/VIDEO_ID  atau  /shorts/VIDEO_ID  atau  /v/VIDEO_ID
            if (preg_match('#^/(embed|shorts|v)/([a-zA-Z0-9_\-]{11})#', $path, $m)) {
                return $this->validateId($m[2]);
            }
        }

        return null;
    }

    /** Validasi format Video ID (11 karakter valid). */
    private function validateId(?string $id): ?string
    {
        if ($id && preg_match('/^[a-zA-Z0-9_\-]{11}$/', $id)) {
            return $id;
        }
        return null;
    }

    // ──────────────────────────────────────────────────────
    //  YouTube Data API v3
    // ──────────────────────────────────────────────────────

    /**
     * Ambil detail video dari YouTube API.
     *
     * @return array{
     *   youtube_id: string,
     *   judul: string,
     *   channel: string,
     *   thumbnail: string,
     *   durasi: int,
     *   embeddable: bool
     * }|null  null kalau API error atau video tidak ditemukan
     */
    public function getVideoDetails(string $videoId): ?array
    {
        if (empty($this->apiKey)) {
            Log::warning('YouTube API key belum dikonfigurasi.');
            return null;
        }

        try {
            $response = Http::timeout(10)->get("{$this->apiBase}/videos", [
                'key'  => $this->apiKey,
                'id'   => $videoId,
                'part' => 'snippet,contentDetails,status',
            ]);

            if (! $response->successful()) {
                Log::error('YouTube API error', ['status' => $response->status(), 'body' => $response->body()]);
                return null;
            }

            $items = $response->json('items', []);
            if (empty($items)) {
                return null; // Video tidak ditemukan
            }

            $item      = $items[0];
            $snippet   = $item['snippet'] ?? [];
            $content   = $item['contentDetails'] ?? [];
            $status    = $item['status'] ?? [];

            return [
                'youtube_id' => $videoId,
                'judul'      => $snippet['title'] ?? 'Judul tidak tersedia',
                'channel'    => $snippet['channelTitle'] ?? '',
                'thumbnail'  => $snippet['thumbnails']['medium']['url']
                             ?? $snippet['thumbnails']['default']['url']
                             ?? '',
                'durasi'     => $this->iso8601ToSeconds($content['duration'] ?? 'PT0S'),
                'embeddable' => (bool) ($status['embeddable'] ?? false),
            ];
        } catch (\Exception $e) {
            Log::error('YouTube API exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Konversi durasi ISO 8601 (PT4M13S) ke detik.
     * Contoh: PT1H2M3S → 3723, PT4M13S → 253
     */
    public function iso8601ToSeconds(string $duration): int
    {
        preg_match('/PT(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?/', $duration, $m);
        return (int) ($m[1] ?? 0) * 3600
             + (int) ($m[2] ?? 0) * 60
             + (int) ($m[3] ?? 0);
    }
}
