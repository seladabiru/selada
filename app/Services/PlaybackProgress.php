<?php

namespace App\Services;

use App\Models\Song;
use Illuminate\Support\Facades\Cache;

class PlaybackProgress
{
    private const CACHE_KEY = 'radio_playback_progress';
    private const HEARTBEAT_TIMEOUT_SECONDS = 30;

    public function reset(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    public function update(Song $song, int $currentTime, int $duration, bool $isPlaying): void
    {
        Cache::put(self::CACHE_KEY, [
            'song_id' => $song->id,
            'current_time' => $currentTime,
            'duration' => $duration,
            'is_playing' => $isPlaying,
            'synced_at' => now()->timestamp,
        ], now()->addHours(12));
    }

    public function isOnAir(): bool
    {
        $song = Song::where('status', 'diputar')->first();

        return $song !== null && $this->forSong($song)['is_playing'];
    }

    /**
     * @return array{current_time: int, duration: int, is_playing: bool}
     */
    public function forSong(Song $song): array
    {
        $progress = Cache::get(self::CACHE_KEY);

        if (! is_array($progress) || ($progress['song_id'] ?? null) !== $song->id) {
            return [
                'current_time' => 0,
                'duration' => (int) $song->durasi,
                'is_playing' => false,
            ];
        }

        $currentTime = max(0, (int) ($progress['current_time'] ?? 0));
        $duration = max(0, (int) ($progress['duration'] ?? 0));
        $syncAge = max(0, now()->timestamp - (int) ($progress['synced_at'] ?? 0));

        $isPlaying = (bool) ($progress['is_playing'] ?? false)
            && $syncAge <= self::HEARTBEAT_TIMEOUT_SECONDS;

        if ($isPlaying) {
            $currentTime += $syncAge;
        }

        if ($duration > 0) {
            $currentTime = min($currentTime, $duration);
        }

        return [
            'current_time' => $currentTime,
            'duration' => $duration,
            'is_playing' => $isPlaying,
        ];
    }
}
