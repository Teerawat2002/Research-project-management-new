<x-app-layout>
    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200">
                Profile Management
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                จัดการข้อมูลส่วนตัวและรหัสผ่าน
            </p>
        </div>

        <div class="max-w-4xl space-y-6">
            <div
                class="p-6 bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl transition-colors duration-200">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div
                class="p-6 bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl transition-colors duration-200">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
