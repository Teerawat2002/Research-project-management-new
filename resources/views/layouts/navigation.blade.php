<nav
    class="sticky top-0 z-20 w-full bg-white/80 backdrop-blur-md border-b border-gray-100 dark:bg-gray-900/80 dark:border-gray-800 transition-colors duration-200">
    <div class="px-4 py-3 lg:px-6">
        <div class="flex items-center justify-between">

            <div class="flex items-center justify-start gap-2 sm:gap-4">
                <button @click="sidebarOpen = !sidebarOpen"
                    class="inline-flex items-center p-2 text-gray-500 rounded-lg hover:text-orange-500 hover:bg-orange-50 focus:outline-none focus:ring-2 focus:ring-orange-200 dark:text-gray-400 dark:hover:text-orange-400 dark:hover:bg-gray-800 transition-colors">
                    <i class="fa-solid fa-bars-staggered fa-lg"
                        :class="sidebarOpen ? 'fa-bars-staggered' : 'fa-bars'"></i>
                </button>

                <div
                    class="hidden sm:flex items-center text-sm text-gray-500 dark:text-gray-400 space-x-2 border-l border-gray-200 dark:border-gray-700 pl-4">
                    <span class="font-medium text-gray-800 dark:text-gray-200">
                        {{ request()->route('title') ?? 'จัดการข้อมูล' }}
                    </span>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <button id="theme-toggle" type="button"
                    class="text-gray-400 hover:text-orange-500 dark:text-gray-400 dark:hover:text-orange-400 transition-colors focus:outline-none rounded-lg p-2.5">
                    <i id="theme-toggle-dark-icon" class="fa-solid fa-moon fa-lg hidden"></i>
                    <i id="theme-toggle-light-icon" class="fa-solid fa-sun fa-lg hidden"></i>
                </button>

                <div class="hidden sm:flex sm:items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="flex items-center text-sm font-medium text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white focus:outline-none transition-colors">
                                <div
                                    class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 dark:bg-orange-500/20 dark:text-orange-400 flex items-center justify-center font-bold mr-2">
                                    @if (Auth::guard('students')->check())
                                        {{ mb_substr(Auth::guard('students')->user()->name, 0, 1) }}
                                    @elseif(Auth::guard('advisors')->check())
                                        {{ mb_substr(Auth::guard('advisors')->user()->name, 0, 1) }}
                                    @endif
                                </div>
                                <i class="fa-solid fa-chevron-down text-xs text-gray-400 dark:text-gray-500"></i>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')"
                                class="dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white">
                                <i class="fa-regular fa-user mr-2"></i>Profile
                            </x-dropdown-link>

                            <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

                            @if (Auth::guard('students')->check())
                                <form method="POST" action="{{ route('studentLogout.logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('studentLogout.logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="text-red-600 dark:text-red-400 dark:hover:bg-gray-800">
                                        <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i>Log Out
                                    </x-dropdown-link>
                                </form>
                            @endif
                            @if (Auth::guard('advisors')->check())
                                <form method="POST" action="{{ route('advisorLogout.logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('advisorLogout.logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="text-red-600 dark:text-red-400 dark:hover:bg-gray-800">
                                        <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i>Log Out
                                    </x-dropdown-link>
                                </form>
                            @endif
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    // ส่วน Script ทำงานได้สมบูรณ์แล้ว ไม่ต้องแก้ไขครับ
    var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia(
            '(prefers-color-scheme: dark)').matches)) {
        themeToggleLightIcon.classList.remove('hidden');
    } else {
        themeToggleDarkIcon.classList.remove('hidden');
    }

    var themeToggleBtn = document.getElementById('theme-toggle');

    themeToggleBtn.addEventListener('click', function() {
        themeToggleDarkIcon.classList.toggle('hidden');
        themeToggleLightIcon.classList.toggle('hidden');

        if (localStorage.getItem('color-theme')) {
            if (localStorage.getItem('color-theme') === 'light') {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            }
        } else {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
        }
    });
</script>
