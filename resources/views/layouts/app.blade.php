<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Research exam</title>

    <link rel="icon" type="image/png" href="{{ asset('icons/logo_pcru.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <script src="https://kit.fontawesome.com/c2d79c304b.js" crossorigin="anonymous"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        // เช็ก Theme ทันทีที่โหลดหน้าเว็บ
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>

<body
    class="font-sans text-gray-800 antialiased bg-gray-50 dark:bg-gray-900 dark:text-gray-200 transition-colors duration-200">

    <div x-data="{ sidebarOpen: window.innerWidth >= 640 }" @resize.window="if(window.innerWidth < 640) sidebarOpen = false"
        class="flex min-h-screen flex-col">

        <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"
            class="fixed inset-0 z-30 bg-gray-900/50 sm:hidden backdrop-blur-sm" aria-hidden="true">
        </div>

        @include('layouts.sidebar')

        <div :class="sidebarOpen ? 'sm:ml-[260px]' : 'ml-0'" class="flex-1 transition-all duration-300 ease-in-out">
            @include('layouts.navigation')

            @isset($header)
                <header
                    class="bg-white shadow-sm border-b border-gray-100 dark:bg-gray-800 dark:border-gray-700 transition-colors duration-200">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 text-sm text-gray-500 dark:text-gray-400">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scripts')
</body>

</html>
