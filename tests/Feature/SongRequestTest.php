<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\Song;
use App\Models\User;
use App\Services\PlaybackProgress;
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

        $this->app->make(PlaybackProgress::class)->reset();

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
        $response->assertSee('Antrian Berikutnya');
    }

    public function test_currently_playing_song_is_not_shown_in_the_next_up_queue(): void
    {
        $student = User::factory()->create(['role' => 'siswa']);

        Song::create([
            'user_id'    => $student->id,
            'youtube_id' => 'playingSong1',
            'judul'      => 'Lagu Sedang Diputar',
            'durasi'     => 180,
            'status'     => 'diputar',
            'urutan'     => 1,
        ]);

        Song::create([
            'user_id'    => $student->id,
            'youtube_id' => 'nextSong1111',
            'judul'      => 'Lagu Berikutnya',
            'durasi'     => 180,
            'status'     => 'disetujui',
            'urutan'     => 2,
        ]);

        $this->actingAs($student)
            ->get('/songs')
            ->assertOk()
            ->assertSee('Lagu Sedang Diputar');

        $this->actingAs($student)
            ->get('/songs?poll=1', ['HTTP_X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()
            ->assertSee('Lagu Berikutnya')
            ->assertDontSee('Lagu Sedang Diputar');
    }

    public function test_playback_status_tracks_actual_player_progress_and_pause_state(): void
    {
        $student = User::factory()->create(['role' => 'siswa']);
        $admin = User::factory()->create(['role' => 'admin']);
        $song = Song::create([
            'user_id'    => $student->id,
            'youtube_id' => 'playingSong1',
            'judul'      => 'Lagu Sedang Diputar',
            'durasi'     => 180,
            'status'     => 'diputar',
            'urutan'     => 1,
        ]);

        $this->actingAs($admin)
            ->postJson(route('admin.player.progress', $song), [
                'current_time' => 42,
                'duration' => 210,
                'is_playing' => true,
            ])
            ->assertOk();

        $this->travel(2)->seconds();
        $this->actingAs($student)
            ->getJson(route('songs.playback'))
            ->assertOk()
            ->assertJsonPath('song.id', $song->id)
            ->assertJsonPath('song.current_time', 44)
            ->assertJsonPath('song.duration', 210)
            ->assertJsonPath('song.is_playing', true)
            ->assertJsonPath('is_on_air', true);

        $this->actingAs($admin)
            ->postJson(route('admin.player.progress', $song), [
                'current_time' => 75,
                'duration' => 210,
                'is_playing' => false,
            ])
            ->assertOk();

        $this->travel(5)->seconds();
        $this->actingAs($student)
            ->getJson(route('songs.playback'))
            ->assertOk()
            ->assertJsonPath('song.current_time', 75)
            ->assertJsonPath('song.duration', 210)
            ->assertJsonPath('song.is_playing', false)
            ->assertJsonPath('is_on_air', false);

        $this->actingAs($admin)
            ->postJson(route('admin.player.progress', $song), [
                'current_time' => 80,
                'duration' => 210,
                'is_playing' => true,
            ])
            ->assertOk();

        $this->travel(6)->seconds();
        $this->actingAs($student)
            ->getJson(route('songs.playback'))
            ->assertOk()
            ->assertJsonPath('song.is_playing', true)
            ->assertJsonPath('is_on_air', true);

        $this->travel(25)->seconds();
        $this->actingAs($student)
            ->getJson(route('songs.playback'))
            ->assertOk()
            ->assertJsonPath('song.is_playing', false)
            ->assertJsonPath('is_on_air', false);
    }

    public function test_public_radio_status_follows_dj_playback_and_stops_after_player_disconnects(): void
    {
        $student = User::factory()->create(['role' => 'siswa']);
        $admin = User::factory()->create(['role' => 'admin']);
        $song = Song::create([
            'user_id'    => $student->id,
            'youtube_id' => 'playingSong1',
            'judul'      => 'Lagu Sedang Diputar',
            'durasi'     => 180,
            'status'     => 'diputar',
            'urutan'     => 1,
        ]);

        $this->getJson(route('radio.status'))
            ->assertOk()
            ->assertJsonPath('is_on_air', false);

        $this->actingAs($admin)
            ->postJson(route('admin.player.progress', $song), [
                'current_time' => 20,
                'duration' => 180,
                'is_playing' => true,
            ])
            ->assertOk();

        $this->getJson(route('radio.status'))
            ->assertOk()
            ->assertJsonPath('is_on_air', true);

        $this->actingAs($student)
            ->get('/songs')
            ->assertOk()
            ->assertSee('data-on-air-indicator="on"', false);

        $this->actingAs($admin)
            ->get('/admin/player')
            ->assertOk()
            ->assertSee('data-on-air-indicator="on"', false);

        auth()->logout();
        $this->get('/login')
            ->assertOk()
            ->assertSee('data-on-air-indicator="on"', false);

        $this->travel(6)->seconds();
        $this->getJson(route('radio.status'))
            ->assertOk()
            ->assertJsonPath('is_on_air', true);

        $this->travel(25)->seconds();
        $this->getJson(route('radio.status'))
            ->assertOk()
            ->assertJsonPath('is_on_air', false);
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
