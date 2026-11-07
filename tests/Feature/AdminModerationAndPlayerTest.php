<?php

namespace Tests\Feature;

use App\Models\Song;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminModerationAndPlayerTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_cannot_access_admin_panel(): void
    {
        $student = User::factory()->create(['role' => 'siswa']);

        $response = $this->actingAs($student)->get('/admin');
        $response->assertRedirect(route('login'));

        $response = $this->actingAs($student)->get('/admin/player');
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_dashboard_and_player(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/admin/player');
        $response->assertStatus(200);
    }

    public function test_admin_can_approve_song(): void
    {
        $admin   = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'siswa']);

        $song = Song::create([
            'user_id'    => $student->id,
            'youtube_id' => 'songApprove',
            'judul'      => 'Lagu Menunggu',
            'durasi'     => 200,
            'status'     => 'menunggu',
            'urutan'     => 0,
        ]);

        $response = $this->actingAs($admin)->post("/admin/songs/{$song->id}/approve");

        $response->assertRedirect();
        $this->assertDatabaseHas('songs', [
            'id'     => $song->id,
            'status' => 'disetujui',
        ]);
    }

    public function test_admin_can_reject_song_with_reason(): void
    {
        $admin   = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'siswa']);

        $song = Song::create([
            'user_id'    => $student->id,
            'youtube_id' => 'songReject',
            'judul'      => 'Lagu Kurang Pantas',
            'durasi'     => 200,
            'status'     => 'menunggu',
            'urutan'     => 0,
        ]);

        $response = $this->actingAs($admin)->post("/admin/songs/{$song->id}/reject", [
            'alasan' => 'Lirik tidak mendidik.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('songs', [
            'id'             => $song->id,
            'status'         => 'ditolak',
            'alasan_ditolak' => 'Lirik tidak mendidik.',
        ]);
    }

    public function test_player_auto_next_advances_to_next_approved_song(): void
    {
        $admin   = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'siswa']);

        $song1 = Song::create([
            'user_id'    => $student->id,
            'youtube_id' => 'songNowPlay',
            'judul'      => 'Lagu Sekarang',
            'durasi'     => 180,
            'status'     => 'diputar',
            'urutan'     => 1,
        ]);

        $song2 = Song::create([
            'user_id'    => $student->id,
            'youtube_id' => 'songNextPlay',
            'judul'      => 'Lagu Berikutnya',
            'durasi'     => 210,
            'status'     => 'disetujui',
            'urutan'     => 2,
        ]);

        $response = $this->actingAs($admin)
            ->postJson("/admin/player/{$song1->id}/finish");

        $response->assertStatus(200);
        $response->assertJson([
            'success'  => true,
            'has_next' => true,
            'song'     => [
                'id'         => $song2->id,
                'youtube_id' => 'songNextPlay',
            ],
        ]);

        $this->assertDatabaseHas('songs', ['id' => $song1->id, 'status' => 'selesai']);
        $this->assertDatabaseHas('songs', ['id' => $song2->id, 'status' => 'diputar']);
    }

    public function test_admin_can_bulk_create_students_via_raw_text(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $rawText = "14144091, Dimas Anggara, XII RPL 1\n" .
                   "14144092, Eka Rahayu, XII RPL 2, rahasia123";

        $response = $this->actingAs($admin)->post('/admin/users/bulk', [
            'raw_text'         => $rawText,
            'role'             => 'siswa',
            'default_password' => 'password',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['nis' => '14144091', 'name' => 'Dimas Anggara', 'kelas' => 'XII RPL 1']);
        $this->assertDatabaseHas('users', ['nis' => '14144092', 'name' => 'Eka Rahayu', 'kelas' => 'XII RPL 2']);
    }
}

