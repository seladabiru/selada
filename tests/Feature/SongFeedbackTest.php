<?php

namespace Tests\Feature;

use App\Models\Feedback;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SongFeedbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_send_feedback_and_suggestion(): void
    {
        $student = User::factory()->create(['role' => 'siswa']);

        $response = $this->actingAs($student)->post('/profile/feedback', [
            'kategori' => 'Saran',
            'pesan'    => 'Mohon putar lagu genre jazz santai saat istirahat siang.',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('feedback_status');

        $this->assertDatabaseHas('feedbacks', [
            'user_id'  => $student->id,
            'kategori' => 'Saran',
            'pesan'    => 'Mohon putar lagu genre jazz santai saat istirahat siang.',
            'is_read'  => false,
        ]);
    }

    public function test_admin_can_view_feedbacks_and_toggle_status(): void
    {
        $admin   = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'siswa']);

        $feedback = Feedback::create([
            'user_id'  => $student->id,
            'kategori' => 'Kritik',
            'pesan'    => 'Volume speaker di koridor lantai 2 terlalu kecil.',
            'is_read'  => false,
        ]);

        // Admin lihat list
        $response = $this->actingAs($admin)->get('/admin/feedbacks');
        $response->assertStatus(200);
        $response->assertSee('Volume speaker di koridor lantai 2 terlalu kecil.');

        // Admin tandai sudah dibaca
        $this->actingAs($admin)->post("/admin/feedbacks/{$feedback->id}/toggle");
        $this->assertTrue($feedback->fresh()->is_read);
    }
}
