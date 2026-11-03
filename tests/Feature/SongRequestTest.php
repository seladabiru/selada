<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\Song;
use App\Models\User;
use App\Services\YouTubeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class SongRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Default setting
        Setting::set('batas_request_per_hari', '1');
        Setting::set('maks_durasi_detik', '600');
        Setting::set('blacklist_kata', 'kasar,jorok');
        Setting::set('moderasi_otomatis', '0');
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/songs');
        $response->assertRedirect('/login');

        $response = $this->get('/songs/create');
        $response->assertRedirect('/login');
    }

    public function test_student_can_view_songs_queue(): void
    {
        $student = User::factory()->create(['role' => 'siswa']);

        $response = $this->actingAs($student)->get('/songs');

        $response->assertStatus(200);
        $response->assertSee('Antrian Aktif');
    }

    public function test_student_can_submit_song_request(): void
    {
        $student = User::factory()->create(['role' => 'siswa']);

        // Mock YouTube Service
        $mockYoutube = Mockery::mock(YouTubeService::class);
        $mockYoutube->shouldReceive('extractVideoId')->andReturn('dQw4w9WgXcQ');
        $mockYoutube->shouldReceive('getVideoDetails')->andReturn([
            'youtube_id' => 'dQw4w9WgXcQ',
            'judul'      => 'Never Gonna Give You Up',
            'channel'    => 'Rick Astley',
            'thumbnail'  => 'https://example.com/thumb.jpg',
            'durasi'     => 213,
            'embeddable' => true,
        ]);
        $this->app->instance(YouTubeService::class, $mockYoutube);

        $response = $this->actingAs($student)->post('/songs', [
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'pesan'       => 'Semangat untuk XII RPL 1!',
        ]);

        $response->assertRedirect('/songs');
        $this->assertDatabaseHas('songs', [
            'youtube_id' => 'dQw4w9WgXcQ',
            'judul'      => 'Never Gonna Give You Up',
            'user_id'    => $student->id,
            'pesan'      => 'Semangat untuk XII RPL 1!',
            'status'     => 'menunggu',
        ]);
    }

    public function test_student_cannot_exceed_daily_quota_of_once_per_day(): void
    {
        $student = User::factory()->create(['role' => 'siswa']);

        // Request pertama hari ini
        Song::create([
            'user_id'    => $student->id,
            'youtube_id' => 'firstSong11',
            'judul'      => 'Lagu Pertama',
            'channel'    => 'Artis 1',
            'durasi'     => 180,
            'status'     => 'disetujui',
            'urutan'     => 1,
        ]);

        $response = $this->actingAs($student)
            ->from('/songs/create')
            ->post('/songs', [
                'youtube_url' => 'https://www.youtube.com/watch?v=secondSong2',
            ]);

        $response->assertRedirect('/songs/create');
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('songs', ['youtube_id' => 'secondSong2']);
    }

    public function test_duplicate_song_in_active_queue_is_rejected(): void
    {
        $student1 = User::factory()->create(['role' => 'siswa']);
        $student2 = User::factory()->create(['role' => 'siswa']);

        Song::create([
            'user_id'    => $student1->id,
            'youtube_id' => 'dQw4w9WgXcQ',
            'judul'      => 'Never Gonna Give You Up',
            'durasi'     => 213,
            'status'     => 'disetujui',
            'urutan'     => 1,
        ]);

        $mockYoutube = Mockery::mock(YouTubeService::class);
        $mockYoutube->shouldReceive('extractVideoId')->andReturn('dQw4w9WgXcQ');
        $this->app->instance(YouTubeService::class, $mockYoutube);

        $response = $this->actingAs($student2)
            ->from('/songs/create')
            ->post('/songs', [
                'youtube_url' => 'https://youtu.be/dQw4w9WgXcQ',
            ]);

        $response->assertRedirect('/songs/create');
        $response->assertSessionHas('error', 'Lagu ini sudah ada di antrian. Pilih lagu lain.');
    }

    public function test_song_with_blacklisted_word_in_title_or_message_is_rejected(): void
    {
        $student = User::factory()->create(['role' => 'siswa']);

        $mockYoutube = Mockery::mock(YouTubeService::class);
        $mockYoutube->shouldReceive('extractVideoId')->andReturn('badSong1111');
        $mockYoutube->shouldReceive('getVideoDetails')->andReturn([
            'youtube_id' => 'badSong1111',
            'judul'      => 'Lagu Kasar Banget',
            'channel'    => 'Unknown',
            'thumbnail'  => 'https://example.com/thumb.jpg',
            'durasi'     => 180,
            'embeddable' => true,
        ]);
        $this->app->instance(YouTubeService::class, $mockYoutube);

        $response = $this->actingAs($student)
            ->from('/songs/create')
            ->post('/songs', [
                'youtube_url' => 'https://youtu.be/badSong1111',
            ]);

        $response->assertRedirect('/songs/create');
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('songs', ['youtube_id' => 'badSong1111']);
    }
}

