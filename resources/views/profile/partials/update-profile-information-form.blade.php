<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informasi Akun Siswa') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Lihat identitas NIS dan ubah nama panggilan atau alamat email akun kamu.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <x-input-label for="nis" :value="__('Nomor Induk Siswa (NIS)')" />
                <x-text-input id="nis" type="text" class="mt-1 block w-full bg-gray-50 text-gray-500 cursor-not-allowed border-gray-200" :value="$user->nis" disabled />
                <p class="mt-1 text-[11px] text-gray-400">NIS diatur oleh pihak sekolah.</p>
            </div>

            <div>
                <x-input-label for="kelas" :value="__('Kelas')" />
                <x-text-input id="kelas" type="text" class="mt-1 block w-full bg-gray-50 text-gray-500 cursor-not-allowed border-gray-200" :value="$user->kelas ?? '-'" disabled />
                <p class="mt-1 text-[11px] text-gray-400">Kelas terdaftar.</p>
            </div>
        </div>

        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email (Opsional)')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="w-full sm:w-auto justify-center">{{ __('Simpan Perubahan') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm text-emerald-600 font-medium"
                >{{ __('Tersimpan!') }}</p>
            @endif
        </div>
    </form>
</section>
