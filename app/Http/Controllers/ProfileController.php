<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $feedbacks = Feedback::where('user_id', $request->user()->id)
            ->latest()
            ->take(5)
            ->get();

        return view('profile.edit', [
            'user' => $request->user(),
            'feedbacks' => $feedbacks,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Kirim kritik dan saran untuk radio sekolah/OSIS.
     */
    public function storeFeedback(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kategori' => 'required|string|in:Saran,Kritik,Laporan Masalah,Permintaan Fitur',
            'pesan'    => 'required|string|min:5|max:1000',
        ], [
            'kategori.required' => 'Pilih kategori masukan.',
            'pesan.required'    => 'Pesan kritik atau saran tidak boleh kosong.',
            'pesan.min'         => 'Pesan minimal berisi 5 karakter.',
            'pesan.max'         => 'Pesan maksimal 1000 karakter.',
        ]);

        Feedback::create([
            'user_id'  => $request->user()->id,
            'kategori' => $validated['kategori'],
            'pesan'    => $validated['pesan'],
        ]);

        return Redirect::route('profile.edit')->with('feedback_status', 'Terima kasih! Kritik dan saran kamu telah terkirim ke pengurus OSIS.');
    }
}
