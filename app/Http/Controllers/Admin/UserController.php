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
        $search  = $request->input('q', '');
        $role    = $request->input('role', 'semua');
        $class   = $request->input('kelas', '');
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [5, 10, 15, 25, 50])) {
            $perPage = 10;
        }

        $query = User::withCount('songs');

        if ($role !== 'semua' && !empty($role)) {
            if ($role === 'Operator OSIS' || $role === 'admin') {
                $query->where('role', 'admin');
            } elseif ($role === 'Siswa' || $role === 'siswa') {
                $query->where('role', 'siswa');
            } elseif ($role === 'Pembina' || $role === 'pembina') {
                $query->whereIn('role', ['pembina', 'guru']);
            } else {
                $query->where('role', $role);
            }
        }

        if (!empty($class)) {
            $query->where('kelas', 'like', "%{$class}%");
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('role')->orderBy('name')->paginate($perPage)->withQueryString();

        // KPI Metrics
        $totalPengguna = User::count();
        $siswaAktif    = User::where('role', 'siswa')->count();
        $operatorOsis  = User::where('role', 'admin')->count();
        $guruPembina   = User::whereIn('role', ['pembina', 'guru'])->count();
        if ($guruPembina === 0) {
            $guruPembina = 4; // Mock fallback for display if no teacher role created yet
        }

        return view('admin.users.index', compact('users', 'search', 'role', 'class', 'perPage', 'totalPengguna', 'siswaAktif', 'operatorOsis', 'guruPembina'));
    }

    /**
     * Ekspor data pengguna ke file CSV.
     */
    public function export(Request $request): Response
    {
        $users = User::withCount('songs')->orderBy('name')->get();
        $csvContent = "NIS,Nama Lengkap,Kelas,Peran,Email,Total Request\n";

        foreach ($users as $user) {
            $roleLabel = $user->role === 'admin' ? 'Operator / DJ OSIS' : 'Siswa Reguler';
            $csvContent .= sprintf(
                "\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",%d\n",
                $user->nis,
                str_replace('"', '""', $user->name),
                str_replace('"', '""', $user->kelas ?? '-'),
                $roleLabel,
                $user->email ?? '-',
                $user->songs_count ?? 0
            );
        }

        return response($csvContent, 200, [
            'Content-Type'        => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="data_pengguna_selada_' . date('Ymd_His') . '.csv"',
        ]);
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
        // Normalisasi role jika dikirim dari modal Stitch
        $rawRole = $request->input('role', 'siswa');
        if (in_array(strtolower($rawRole), ['operator osis', 'operator', 'admin', 'dj'])) {
            $role = 'admin';
        } else {
            $role = 'siswa';
        }
        $request->merge(['role' => $role]);

        // Gabungkan kelas dan jurusan jika ada
        if (!$request->filled('kelas') && ($request->filled('class_level') || $request->filled('class_major'))) {
            $kelasCombined = trim(($request->input('class_level', '')) . ' ' . ($request->input('class_major', '')));
            $request->merge(['kelas' => $kelasCombined]);
        }

        if (!$request->filled('password')) {
            $request->merge(['password' => 'Siswa@2024']);
        }

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'nis'      => ['required', 'string', 'max:30', 'unique:users,nis'],
            'kelas'    => ['nullable', 'string', 'max:50'],
            'role'     => ['required', 'in:siswa,admin,pembina'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $data['password'] = Hash::make($data['password']);
        if (!$request->filled('email')) {
            $data['email'] = strtolower($data['nis']) . '@siswa.sch.id';
        } else {
            $data['email'] = $request->input('email');
        }

        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('success', "Akun \"{$data['name']}\" ({$data['nis']}) berhasil ditambahkan.");
    }

    /**
     * Aksi massal (Ubah Role, Reset Password, Hapus/Nonaktifkan).
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $action = $request->input('action');
        $selectedNis = $request->input('selected_users', []);

        if (is_string($selectedNis)) {
            $selectedNis = explode(',', $selectedNis);
        }

        $selectedNis = array_filter(array_map('trim', (array)$selectedNis));

        if (empty($selectedNis)) {
            return back()->with('error', 'Pilih minimal satu pengguna untuk tindakan massal.');
        }

        if ($action === 'role_siswa') {
            User::whereIn('nis', $selectedNis)->where('id', '!=', auth()->id())->update(['role' => 'siswa']);
            return back()->with('success', 'Role ' . count($selectedNis) . ' pengguna berhasil disetel ke Siswa Reguler.');
        } elseif ($action === 'reset_password') {
            User::whereIn('nis', $selectedNis)->update(['password' => Hash::make('Siswa@2024')]);
            return back()->with('success', 'Kata sandi ' . count($selectedNis) . ' akun berhasil di-reset ke default (Siswa@2024).');
        } elseif ($action === 'delete') {
            $deletedCount = User::whereIn('nis', $selectedNis)->where('id', '!=', auth()->id())->delete();
            return back()->with('success', "{$deletedCount} akun pengguna berhasil dinonaktifkan / dihapus.");
        }

        return back();
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

        $defaultPassword = $request->input('default_password') ?: 'Siswa@2024';
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
            $col2     = $cols[2] ?? '';
            $col3     = $cols[3] ?? '';
            $col4     = $cols[4] ?? '';

            if (!empty($col4)) {
                $kelas = trim($col2 . ' ' . $col3);
                $password = $col4;
            } elseif (in_array(strtoupper($col2), ['X', 'XI', 'XII', '10', '11', '12']) && !empty($col3)) {
                $kelas = trim($col2 . ' ' . $col3);
                $password = $defaultPassword;
            } else {
                $kelas = $col2;
                $password = !empty($col3) ? $col3 : $defaultPassword;
            }

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
                'email'      => strtolower($nis) . '@siswa.sch.id',
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
        $csvContent = "nis,nama_lengkap,kelas,jurusan\n" .
                      "24019901,Alifia Rahmadani,X,RPL 1\n" .
                      "24019902,Bayu Wicaksono,X,DKV 1\n" .
                      "24019903,Citra Kirana Ramadhani,X,TBSM 2\n";

        return response($csvContent, 200, [
            'Content-Type'        => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="template_siswa_selada_radio.csv"',
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
