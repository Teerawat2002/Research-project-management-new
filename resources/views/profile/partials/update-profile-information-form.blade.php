<section>
    <header>
        <h2 class="text-lg font-bold text-gray-900 dark:text-white transition-colors duration-200">
            {{ __('ข้อมูลโปรไฟล์') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 transition-colors duration-200">
            {{ __('อัปเดตชื่อและนามสกุลบัญชีของคุณ') }}
        </p>
    </header>

    <form method="POST" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        {{-- First Name --}}
        <div>
            <label for="fname"
                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5 transition-colors duration-200">{{ __('ชื่อ') }}</label>
            <input id="fname" name="fname" type="text" required
                class="block w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-500 transition-colors duration-200"
                value="{{ old('fname', $user->a_fname ?? $user->s_fname) }}" />
            <x-input-error :messages="$errors->get('fname')" class="mt-2" />
        </div>

        {{-- Last Name --}}
        <div>
            <label for="lname"
                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5 transition-colors duration-200">{{ __('นามสกุล') }}</label>
            <input id="lname" name="lname" type="text" required
                class="block w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-500 transition-colors duration-200"
                value="{{ old('lname', $user->a_lname ?? $user->s_lname) }}" />
            <x-input-error :messages="$errors->get('lname')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                class="px-5 py-2.5 text-sm font-medium text-white bg-orange-500 rounded-xl hover:bg-orange-600 shadow-sm flex items-center gap-2 transition-colors duration-200">
                <i class="fa-solid fa-save"></i> {{ __('บันทึกข้อมูล') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)"
                    class="text-sm text-emerald-600 dark:text-emerald-400 font-medium flex items-center">
                    <i class="fa-solid fa-circle-check mr-1.5"></i> {{ __('บันทึกข้อมูลสำเร็จแล้ว') }}
                </p>
            @endif
        </div>
    </form>
</section>
