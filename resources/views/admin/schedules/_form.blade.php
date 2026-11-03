@php
    $hari = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
    $hariAktif = old('hari_aktif', $schedule->hari_aktif ?? []);
@endphp

<div class="space-y-5">

    {{-- Nama sesi --}}
    <div>
        <x-input-label for="nama_sesi" value="Nama Sesi" />
        <x-text-input id="nama_sesi" name="nama_sesi" type="text" class="mt-1 block w-full"
            value="{{ old('nama_sesi', $schedule->nama_sesi ?? '') }}"
            placeholder="Istirahat 1" required />
        <x-input-error :messages="$errors->get('nama_sesi')" class="mt-1" />
    </div>

    {{-- Jam mulai & selesai --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <x-input-label for="jam_mulai" value="Jam Mulai" />
            <x-text-input id="jam_mulai" name="jam_mulai" type="time" class="mt-1 block w-full"
                value="{{ old('jam_mulai', isset($schedule) ? substr($schedule->jam_mulai, 0, 5) : '') }}"
                required />
            <x-input-error :messages="$errors->get('jam_mulai')" class="mt-1" />
        </div>
        <div>
            <x-input-label for="jam_selesai" value="Jam Selesai" />
            <x-text-input id="jam_selesai" name="jam_selesai" type="time" class="mt-1 block w-full"
                value="{{ old('jam_selesai', isset($schedule) ? substr($schedule->jam_selesai, 0, 5) : '') }}"
                required />
            <x-input-error :messages="$errors->get('jam_selesai')" class="mt-1" />
        </div>
    </div>

    {{-- Hari aktif --}}
    <div>
        <x-input-label value="Hari Aktif" />
        <div class="mt-2 flex flex-wrap gap-2">
            @foreach ($hari as $h)
                <label class="flex items-center gap-1.5 cursor-pointer select-none">
                    <input type="checkbox"
                        name="hari_aktif[]"
                        value="{{ $h }}"
                        {{ in_array($h, $hariAktif) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="text-sm text-gray-700">{{ $h }}</span>
                </label>
            @endforeach
        </div>
        <x-input-error :messages="$errors->get('hari_aktif')" class="mt-1" />
    </div>

    {{-- Status aktif --}}
    <div class="flex items-center gap-2">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" id="is_active" name="is_active" value="1"
            {{ old('is_active', $schedule->is_active ?? true) ? 'checked' : '' }}
            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
        <label for="is_active" class="text-sm text-gray-700">Jadwal aktif</label>
    </div>

</div>
