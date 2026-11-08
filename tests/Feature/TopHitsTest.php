<?php

namespace Tests\Feature;

use App\Models\Song;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TopHitsTest extends TestCase
{
    use RefreshDatabase;

    public function test_champion_is_ranked_and_counted_by_actual_on_air_plays_without_preview_buttons(): void
    {
        $student = User::factory()->create(['role' => 'siswa']);

        foreach (['selesai', 'selesai', 'diputar'] as $status) {
            Song::create([
                'user_id' => $student->id,
                'youtube_id' => 'champion123',
                'judul' => 'Lagu Juara',
                'durasi' => 180,
                'status' => $status,
                'urutan' => 1,
            ]);
        }

        Song::create([
            'user_id' => $student->id,
            'youtube_id' => 'otherSong123',
            'judul' => 'Lagu Lain',
            'durasi' => 180,
            'status' => 'selesai',
            'urutan' => 2,
        ]);

        Song::create([
            'user_id' => $student->id,
            'youtube_id' => 'champion123',
            'judul' => 'Lagu Juara',
            'durasi' => 180,
            'status' => 'disetujui',
            'urutan' => 3,
        ]);

        $this->actingAs($student)
            ->get(route('songs.top-hits', ['period' => 'alltime']))
            ->assertOk()
            ->assertSee('Lagu Juara')
            ->assertSee('3x On Air')
            ->assertDontSee('Cuplikan')
            ->assertDontSee('preview-btn')
            ->assertDontSee('Live Voting Tally');
    }
}
