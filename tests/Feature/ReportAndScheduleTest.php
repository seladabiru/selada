<?php

namespace Tests\Feature;

use App\Models\Schedule;
use App\Models\Song;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportAndScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_reports_and_print_view(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/reports');
        $response->assertStatus(200);
        $response->assertSee('Laporan', false);
        $response->assertSee('Rekapitulasi Siaran', false);

        $response = $this->actingAs($admin)->get('/admin/reports/print');
        $response->assertStatus(200);
        $response->assertSee('ORGANISASI SISWA INTRA SEKOLAH');
    }

    public function test_admin_can_filter_report_by_status(): void
    {
        $admin   = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'siswa']);

        Song::create([
            'user_id'    => $student->id,
            'youtube_id' => 'songReport1',
            'judul'      => 'Lagu Selesai Diputar',
            'durasi'     => 180,
            'status'     => 'selesai',
            'urutan'     => 1,
        ]);

        Song::create([
            'user_id'    => $student->id,
            'youtube_id' => 'songReport2',
            'judul'      => 'Lagu Ditolak Admin',
            'durasi'     => 200,
            'status'     => 'ditolak',
            'urutan'     => 0,
        ]);

        $response = $this->actingAs($admin)->get('/admin/reports?status=selesai');
        $response->assertStatus(200);
        $response->assertSee('Lagu Selesai Diputar');
        $response->assertDontSee('Lagu Ditolak Admin');
    }

    public function test_admin_can_create_and_toggle_schedule(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Tambah jadwal baru
        $response = $this->actingAs($admin)->post('/admin/schedules', [
            'nama_sesi'   => 'Istirahat Siang',
            'jam_mulai'   => '11:45',
            'jam_selesai' => '12:30',
            'hari_aktif'  => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
            'is_active'   => true,
        ]);

        $response->assertRedirect(route('admin.schedules.index'));
        $schedule = Schedule::where('nama_sesi', 'Istirahat Siang')->first();
        $this->assertNotNull($schedule);
        $this->assertTrue($schedule->is_active);

        // Toggle jadwal (nonaktifkan)
        $this->actingAs($admin)->post("/admin/schedules/{$schedule->id}/toggle");
        $this->assertFalse($schedule->fresh()->is_active);
    }
}

