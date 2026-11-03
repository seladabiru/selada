<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Song extends Model
{
    protected $fillable = [
        'user_id',
        'youtube_id',
        'judul',
        'channel',
        'thumbnail',
        'pesan',
        'durasi',
        'status',
        'alasan_ditolak',
        'urutan',
    ];

    protected $casts = [
        'durasi' => 'integer',
        'urutan' => 'integer',
    ];

    // ──────────────── Relationships ────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(SongVote::class);
    }

    public function isVotedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        // Cek apakah relasi votes sudah di-load agar hemat query
        if ($this->relationLoaded('votes')) {
            return $this->votes->contains('user_id', $user->id);
        }

        return $this->votes()->where('user_id', $user->id)->exists();
    }

    // ──────────────── Scopes ────────────────

    /** Lagu yang sedang aktif di antrian (belum selesai/ditolak) */
    public function scopeAntrian($query)
    {
        return $query->whereIn('status', ['menunggu', 'disetujui'])
                     ->orderBy('urutan')
                     ->orderBy('created_at');
    }

    /** Lagu yang sedang diputar */
    public function scopeSedangDiputar($query)
    {
        return $query->where('status', 'diputar');
    }

    // ──────────────── Helpers ────────────────

    /** Konversi detik ke format M:SS */
    public function durasiFormatted(): string
    {
        $menit = intdiv($this->durasi, 60);
        $detik = $this->durasi % 60;
        return sprintf('%d:%02d', $menit, $detik);
    }

    /** Label status dalam Bahasa Indonesia */
    public function statusLabel(): string
    {
        return match ($this->status) {
            'menunggu'  => 'Menunggu',
            'disetujui' => 'Disetujui',
            'ditolak'   => 'Ditolak',
            'diputar'   => 'Sedang Diputar',
            'selesai'   => 'Selesai',
            'gagal'     => 'Gagal',
            default     => ucfirst($this->status),
        };
    }

    /** Warna badge Bootstrap sesuai status */
    public function statusColor(): string
    {
        return match ($this->status) {
            'menunggu'  => 'warning',
            'disetujui' => 'primary',
            'ditolak'   => 'danger',
            'diputar'   => 'success',
            'selesai'   => 'secondary',
            'gagal'     => 'dark',
            default     => 'light',
        };
    }
}
