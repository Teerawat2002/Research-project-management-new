<section>
    <header>
        <h2 class="text-lg font-bold text-gray-900 dark:text-white transition-colors duration-200">
            {{ __('เปลี่ยนรหัสผ่าน') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 transition-colors duration-200">
            {{ __('ตรวจสอบให้แน่ใจว่าบัญชีของคุณใช้รหัสผ่านแบบสุ่มที่ยาวและคาดเดายากเพื่อความปลอดภัย') }}
        </p>
    </header>

    <form method="POST" action="{{ route('profile.update.password') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        {{-- Current Password --}}
        <div>
            <label for="current_password"
                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5 transition-colors duration-200">{{ __('รหัสผ่านเดิม') }}</label>
            <input id="current_password" name="current_password" type="password" autocomplete="current-password"
                class="block w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-500 transition-colors duration-200" />
            <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
        </div>

        {{-- New Password --}}
        <div>
            <label for="password"
                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5 transition-colors duration-200">{{ __('รหัสผ่านใหม่') }}</label>
            <input id="password" name="password" type="password" autocomplete="new-password"
                class="block w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-500 transition-colors duration-200" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="password_confirmation"
                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5 transition-colors duration-200">{{ __('ยืนยันรหัสผ่านใหม่') }}</label>
            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                class="block w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-500 transition-colors duration-200" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                class="px-5 py-2.5 text-sm font-medium text-white bg-orange-500 rounded-xl hover:bg-orange-600 shadow-sm flex items-center gap-2 transition-colors duration-200">
                <i class="fa-solid fa-key"></i> {{ __('เปลี่ยนรหัสผ่าน') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)"
                    class="text-sm text-emerald-600 dark:text-emerald-400 font-medium flex items-center">
                    <i class="fa-solid fa-circle-check mr-1.5"></i> {{ __('เปลี่ยนรหัสผ่านสำเร็จแล้ว') }}
                </p>
            @endif
        </div>
    </form>
</section>
