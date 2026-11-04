<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    protected $fillable = [
        'user_id',
        'kategori',
        'judul',
        'pesan',
        'is_read',
        'balasan',
        'pic_osis',
        'status_tindak_lanjut',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getJudulAttribute($value): string
    {
        if (!empty($value)) {
            return $value;
        }

        // Generate auto title from first line or first 60 characters of pesan
        $lines = explode("\n", trim($this->pesan ?? ''));
        $firstLine = trim($lines[0] ?? '');
        if (mb_strlen($firstLine) > 60) {
            return mb_substr($firstLine, 0, 57) . '...';
        }
        return $firstLine ?: 'Aspirasi Tanpa Judul';
    }

    public function getInitialsAttribute(): string
    {
        $name = $this->user ? $this->user->name : 'Siswa';
        $words = explode(' ', trim($name));
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($name, 0, 2));
    }
}
