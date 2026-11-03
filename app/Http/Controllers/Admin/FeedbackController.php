<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    public function index(Request $request): View
    {
        $query = Feedback::with('user')->latest();

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->where('is_read', false);
            } elseif ($request->status === 'read') {
                $query->where('is_read', true);
            }
        }

        $feedbacks = $query->paginate(15)->withQueryString();

        $unreadCount = Feedback::where('is_read', false)->count();

        return view('admin.feedbacks.index', compact('feedbacks', 'unreadCount'));
    }

    public function toggleRead(Feedback $feedback): RedirectResponse
    {
        $feedback->update([
            'is_read' => !$feedback->is_read,
        ]);

        return back()->with('success', 'Status kritik & saran berhasil diperbarui.');
    }

    public function destroy(Feedback $feedback): RedirectResponse
    {
        $feedback->delete();

        return back()->with('success', 'Pesan kritik & saran telah dihapus.');
    }
}

