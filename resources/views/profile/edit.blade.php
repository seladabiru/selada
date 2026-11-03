<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{ __('Profil Siswa') }}
            </h2>
            <span class="text-xs px-2.5 py-1 rounded-full font-semibold {{ auth()->user()->isAdmin() ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                {{ auth()->user()->isAdmin() ? '👑 Operator OSIS' : '🎓 Siswa' }}
            </span>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- 1. Kartu Info Profil & Form Edit Info --}}
            <div class="p-5 sm:p-8 bg-white shadow-sm border border-gray-100 rounded-2xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- 2. Form Update Password --}}
            <div class="p-5 sm:p-8 bg-white shadow-sm border border-gray-100 rounded-2xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- 3. Form Kritik & Saran (Pengganti Hapus Akun) --}}
            <div class="p-5 sm:p-8 bg-white shadow-sm border border-gray-100 rounded-2xl">
                <div class="max-w-xl">
                    @include('profile.partials.feedback-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
