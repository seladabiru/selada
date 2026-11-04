<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    public function index(Request $request): View
    {
        $query = Feedback::with('user')->latest();

        // Search filter
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('judul', 'like', "%{$q}%")
                    ->orWhere('pesan', 'like', "%{$q}%")
                    ->orWhere('kategori', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($userQuery) use ($q) {
                        $userQuery->where('name', 'like', "%{$q}%")
                            ->orWhere('nis', 'like', "%{$q}%")
                            ->orWhere('kelas', 'like', "%{$q}%");
                    });
            });
        }

        // Category / tab filter
        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $cat = strtolower($request->kategori);
            if ($cat === 'unread') {
                $query->where('is_read', false);
            } elseif ($cat === 'saran') {
                $query->where('kategori', 'like', '%saran%');
            } elseif ($cat === 'kritik') {
                $query->where('kategori', 'like', '%kritik%');
            } elseif ($cat === 'masalah') {
                $query->where(function ($q) {
                    $q->where('kategori', 'like', '%masalah%')
                      ->orWhere('kategori', 'like', '%teknis%');
                });
            } elseif ($cat === 'fitur') {
                $query->where(function ($q) {
                    $q->where('kategori', 'like', '%fitur%')
                      ->orWhere('kategori', 'like', '%ide%');
                });
            } else {
                $query->where('kategori', $request->kategori);
            }
        }

        $feedbacks = $query->get();

        // Counter metrics
        $totalAspirasi = Feedback::count();
        $unreadCount = Feedback::where('is_read', false)->count();
        $saranCount = Feedback::where('kategori', 'like', '%saran%')->count();
        $masalahCount = Feedback::where('kategori', 'like', '%masalah%')
            ->orWhere('kategori', 'like', '%teknis%')->count();
        $ideCount = Feedback::where('kategori', 'like', '%fitur%')
            ->orWhere('kategori', 'like', '%ide%')->count();
        $kritikCount = Feedback::where('kategori', 'like', '%kritik%')->count();

        // Selected feedback for detail view
        $selectedFeedback = $feedbacks->first();

        return view('admin.feedbacks.index', compact(
            'feedbacks',
            'totalAspirasi',
            'unreadCount',
            'saranCount',
            'masalahCount',
            'ideCount',
            'kritikCount',
            'selectedFeedback'
        ));
    }

    public function toggleRead(Feedback $feedback): RedirectResponse
    {
        $feedback->update([
            'is_read' => !$feedback->is_read,
        ]);

        $status = $feedback->is_read ? 'telah dibaca' : 'belum dibaca';
        return back()->with('success', "Aspirasi ditandai sebagai {$status}.");
    }

    public function markAllRead(): RedirectResponse
    {
        Feedback::where('is_read', false)->update(['is_read' => true]);

        return back()->with('success', 'Semua aspirasi berhasil ditandai sebagai telah dibaca.');
    }

    public function reply(Request $request, Feedback $feedback): RedirectResponse
    {
        $validated = $request->validate([
            'balasan'              => ['nullable', 'string', 'max:2000'],
            'pic_osis'             => ['nullable', 'string', 'max:100'],
            'status_tindak_lanjut' => ['nullable', 'string', 'max:100'],
        ]);

        $updateData = [
            'is_read' => true,
        ];

        if ($request->has('balasan')) {
            $updateData['balasan'] = $validated['balasan'];
        }
        if ($request->filled('pic_osis')) {
            $updateData['pic_osis'] = $validated['pic_osis'];
        }
        if ($request->filled('status_tindak_lanjut')) {
            $updateData['status_tindak_lanjut'] = $validated['status_tindak_lanjut'];
        }

        $feedback->update($updateData);

        return back()->with('success', 'Status tindak lanjut dan balasan aspirasi berhasil disimpan.');
    }

    public function destroy(Feedback $feedback): RedirectResponse
    {
        $feedback->delete();

        return back()->with('success', 'Pesan kritik & saran telah dihapus.');
    }

    public function export()
    {
        $feedbacks = Feedback::with('user')->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="Aspirasi_Radio_OSIS_' . date('Ymd_His') . '.csv"',
        ];

        $callback = function () use ($feedbacks) {
            $output = fopen('php://output', 'w');
            // Add UTF-8 BOM
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($output, [
                'ID',
                'Nama Siswa',
                'NIS',
                'Kelas',
                'Kategori',
                'Judul',
                'Isi Pesan',
                'PIC OSIS',
                'Status Tindak Lanjut',
                'Status Baca',
                'Balasan / Catatan',
                'Tanggal Masuk'
            ]);

            foreach ($feedbacks as $f) {
                fputcsv($output, [
                    $f->id,
                    $f->user ? $f->user->name : 'Anonim',
                    $f->user ? $f->user->nis : '-',
                    $f->user ? $f->user->kelas : '-',
                    $f->kategori,
                    $f->judul,
                    $f->pesan,
                    $f->pic_osis ?? 'DJ OSIS Audio',
                    $f->status_tindak_lanjut ?? 'Menunggu Tinjauan',
                    $f->is_read ? 'Sudah Dibaca' : 'Belum Dibaca',
                    $f->balasan ?? '',
                    $f->created_at ? $f->created_at->format('Y-m-d H:i:s') : '',
                ]);
            }

            fclose($output);
        };

        return Response::stream($callback, 200, $headers);
    }
}
