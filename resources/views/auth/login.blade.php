<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Research exam</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.0.379/pdf.min.js"></script>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="font-sans text-gray-900 antialiased bg-gray-50">

    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <main class="w-full max-w-5xl">
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2">
                    {{-- LEFT: แบนเนอร์/รูป --}}
                    <a href="{{ route('welcome') }}" class="block group" aria-label="ไปหน้ารวมโครงงาน"
                        title="ไปหน้ารวมโครงงาน">
                        <div class="relative">
                            <img src="{{ asset('images/CSIT.jpg') }}" alt="Research Exam"
                                class="w-full h-full object-cover aspect-[4/3] lg:aspect-auto group-hover:opacity-90 transition" />
                        </div>
                    </a>

                    {{-- RIGHT: ฟอร์มล็อกอิน --}}
                    <div class="p-8 lg:p-10">
                        <h1 class="text-3xl font-extrabold text-gray-900 text-center mb-6">เข้าสู่ระบบ</h1>

                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                            @csrf
                            <div class="mb-4">
                                <label for="id"
                                    class="block text-sm font-medium text-gray-700">รหัสผู้ใช้งาน</label>
                                <input id="id" name="id" type="text" autocomplete="username"
                                    value="{{ old('id') }}"
                                    class="mt-1 block w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500"
                                    placeholder="รหัสนักศึกษาหรือรหัสอาจารย์" required>
                                <x-input-error :messages="$errors->get('id')" class="mt-2" />
                            </div>

                            <div class="mb-5">
                                <div class="flex items-center justify-between">
                                    <label for="password"
                                        class="block text-sm font-medium text-gray-700">รหัสผ่าน</label>
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}"
                                            class="text-xs font-medium text-red-600 hover:text-red-700">ลืมรหัสผ่าน?</a>
                                    @endif
                                </div>

                                <div class="mt-1 relative">
                                    <input id="password" name="password" type="password"
                                        autocomplete="current-password"
                                        class="block w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500"
                                        placeholder="รหัสผ่าน" required>
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <button id="loginBtn" type="submit"
                                class="w-full rounded-full py-3 font-semibold text-white
                                       bg-red-500 hover:bg-red-600 disabled:bg-gray-300 disabled:text-gray-600 transition">
                                เข้าสู่ระบบ
                            </button>
                        </form>

                        @if (session('swal'))
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    Swal.fire(@json(session('swal')));
                                });
                            </script>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- Mobile drawer --}}
    <div id="drawer" class="fixed inset-0 bg-black/40 z-50 hidden">
        <div class="absolute left-0 top-0 w-80 h-full bg-white shadow-xl p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-lg">หมวดหมู่</h3>
                <button id="closeSidebar" class="p-2 rounded hover:bg-gray-100" aria-label="ปิดเมนู">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            @include('layouts.sidebar-guest')
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const idInput = document.getElementById('id');
            const pwdInput = document.getElementById('password');
            const btn = document.getElementById('loginBtn');

            function updateBtn() {
                btn.disabled = !(idInput.value.trim() && pwdInput.value.trim());
            }

            idInput.addEventListener('input', updateBtn);
            pwdInput.addEventListener('input', updateBtn);
            updateBtn();
        });
    </script>
</body>

</html>
