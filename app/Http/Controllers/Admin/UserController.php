<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Daftar akun siswa & staf.
     */
    public function index(Request $request): View
    {
        $search = $request->input('q', '');
        $role   = $request->input('role', 'semua');

        $query = User::withCount('songs');

        if ($role !== 'semua' && in_array($role, ['siswa', 'admin'])) {
            $query->where('role', $role);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('role')->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users', 'search', 'role'));
    }

    /**
     * Form tambah akun siswa baru (satuan).
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Simpan akun baru (satuan).
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'nis'      => ['required', 'string', 'max:30', 'unique:users,nis'],
            'kelas'    => ['nullable', 'string', 'max:50'],
            'role'     => ['required', 'in:siswa,admin'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('success', "Akun \"{$data['name']}\" ({$data['nis']}) berhasil ditambahkan.");
    }

    /**
     * Form bulk create / import akun pengguna.
     */
    public function bulkCreate(): View
    {
        return view('admin.users.bulk');
    }

    /**
     * Proses penyimpanan massal (Bulk Store) akun pengguna.
     */
    public function bulkStore(Request $request): RedirectResponse
    {
        $request->validate([
            'csv_file' => ['nullable', 'file', 'mimes:csv,txt'],
            'raw_text' => ['nullable', 'string'],
            'role'     => ['required', 'in:siswa,admin'],
            'default_password' => ['nullable', 'string', 'min:6'],
        ]);

        $defaultPassword = $request->input('default_password') ?: 'password';
        $role = $request->input('role', 'siswa');

        $lines = [];

        // 1. Ambil baris data dari file CSV jika diunggah
        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');
            $content = file_get_contents($file->getRealPath());
            $lines = preg_split('/\r\n|\r|\n/', $content);
        }
        // 2. Atau ambil dari textarea jika file tidak diunggah
        elseif (!empty($request->input('raw_text'))) {
            $lines = preg_split('/\r\n|\r|\n/', $request->input('raw_text'));
        } else {
            return back()->with('error', 'Silakan unggah file CSV atau tempel teks data siswa pada formulir.');
        }

        $successCount = 0;
        $skippedNis   = [];
        $batchNis     = [];
        $insertData   = [];

        // Ambil semua NIS yang sudah ada di database untuk mencegah duplicate query berulang
        $existingNis = User::pluck('nis')->flip()->toArray();

        foreach ($lines as $lineIndex => $line) {
            $line = trim($line);
            if (empty($line)) continue;

            // Abaikan baris header umum
            if ($lineIndex === 0 && preg_match('/^(nis|nomor|no|username)/i', $line)) {
                continue;
            }

            // Pisahkan kolom dengan koma, titik-koma, atau tab
            $delimiter = str_contains($line, "\t") ? "\t" : (str_contains($line, ';') ? ';' : ',');
            $cols = array_map('trim', str_getcsv($line, $delimiter));

            $nis      = $cols[0] ?? '';
            $name     = $cols[1] ?? '';
            $kelas    = $cols[2] ?? '';
            $password = !empty($cols[3]) ? $cols[3] : $defaultPassword;

            // Validasi data minimal
            if (empty($nis) || empty($name)) {
                continue;
            }

            // Cek duplikasi di database atau dalam batch saat ini
            if (isset($existingNis[$nis]) || isset($batchNis[$nis])) {
                $skippedNis[] = $nis;
                continue;
            }

            $batchNis[$nis] = true;

            $insertData[] = [
                'nis'        => $nis,
                'name'       => $name,
                'kelas'      => $kelas,
                'role'       => $role,
                'password'   => Hash::make($password),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (empty($insertData)) {
            $pesan = 'Tidak ada akun baru yang dapat ditambahkan.';
            if (!empty($skippedNis)) {
                $pesan .= ' Semua NIS yang diinput sudah terdaftar: ' . implode(', ', array_slice($skippedNis, 0, 5));
            }
            return back()->withInput()->with('error', $pesan);
        }

        // Simpan sekaligus ke database
        DB::transaction(function () use ($insertData) {
            foreach (array_chunk($insertData, 100) as $chunk) {
                User::insert($chunk);
            }
        });

        $successCount = count($insertData);
        $message = "Berhasil menambahkan {$successCount} akun {$role} secara massal.";

        if (!empty($skippedNis)) {
            $previewSkipped = implode(', ', array_slice($skippedNis, 0, 5));
            $sisa = count($skippedNis) > 5 ? ' dan ' . (count($skippedNis) - 5) . ' lainnya' : '';
            $message .= " (" . count($skippedNis) . " NIS dilewati karena sudah terdaftar: {$previewSkipped}{$sisa})";
        }

        return redirect()->route('admin.users.index')->with('success', $message);
    }

    /**
     * Download template format CSV untuk bulk import.
     */
    public function downloadTemplate(): Response
    {
        $csvContent = "nis,nama,kelas,password\n" .
                      "14144001,Ahmad Fajar,XII RPL 1,password123\n" .
                      "14144002,Budi Santoso,XII RPL 1,password123\n" .
                      "14144003,Citra Lestari,XII RPL 2,password123\n";

        return response($csvContent, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_siswa_selada.csv"',
        ]);
    }

    /**
     * Form edit akun siswa.
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Perbarui akun siswa / reset password.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'nis'      => ['required', 'string', 'max:30', Rule::unique('users', 'nis')->ignore($user->id)],
            'kelas'    => ['nullable', 'string', 'max:50'],
            'role'     => ['required', 'in:siswa,admin'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', "Data akun \"{$user->name}\" berhasil diperbarui.");
    }

    /**
     * Hapus akun siswa.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Akun \"{$name}\" berhasil dihapus.");
    }
}
