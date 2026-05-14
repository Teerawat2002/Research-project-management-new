<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>เข้าสู่ระบบ - Research Exam</title>
    <link rel="icon" type="image/png" href="{{ asset('icons/logo_pcru.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Kanit', sans-serif;
        }
    </style>
</head>

<body class="text-gray-900 antialiased bg-gray-50 dark:bg-gray-900 selection:bg-orange-500 selection:text-white">

    <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8 relative overflow-hidden">

        <div class="absolute top-0 left-0 w-full h-96 bg-gradient-to-b from-orange-500/20 to-transparent -z-10"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-orange-400/20 rounded-full blur-3xl -z-10"></div>
        <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-orange-400 to-yellow-500 z-50"></div>

        <main class="w-full max-w-5xl relative z-10">
            <div
                class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="grid grid-cols-1 lg:grid-cols-2">

                    {{-- LEFT: แบนเนอร์/รูป --}}
                    <a href="{{ route('welcome') }}" class="block group relative bg-orange-50 dark:bg-gray-900"
                        aria-label="ไปหน้ารวมโครงงาน" title="ไปหน้ารวมโครงงาน">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent z-10"></div>
                        <img src="{{ asset('images/CSIT.jpg') }}" alt="Research Exam"
                            class="w-full h-full object-cover aspect-[4/3] lg:aspect-auto group-hover:scale-105 transition-transform duration-700 ease-in-out opacity-90 group-hover:opacity-100" />

                        <div
                            class="absolute bottom-0 left-0 p-8 z-20 w-full transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                            <h2 class="text-3xl font-bold text-white mb-2 drop-shadow-md">Research Exam</h2>
                            <p class="text-white/80 text-sm font-medium">ระบบการจัดการโครงงานวิจัย
                                มหาวิทยาลัยราชภัฏเพชรบูรณ์</p>
                            <div
                                class="mt-4 flex items-center gap-2 text-white/90 text-sm font-bold opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <i class="fa-solid fa-arrow-left"></i> กลับสู่หน้าหลัก
                            </div>
                        </div>
                    </a>

                    {{-- RIGHT: ฟอร์มล็อกอิน --}}
                    <div class="p-8 sm:p-12 lg:p-14 flex flex-col justify-center bg-white dark:bg-gray-800">

                        <div class="mb-8 text-center lg:text-left">
                            <div
                                class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-orange-100 text-orange-500 mb-4 lg:mx-0 mx-auto dark:bg-orange-500/10 dark:text-orange-400">
                                <i class="fa-solid fa-right-to-bracket text-2xl"></i>
                            </div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">ยินดีต้อนรับกลับมา</h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 font-medium">
                                เข้าสู่ระบบเพื่อจัดการข้อมูลโครงงานวิจัยของคุณ</p>
                        </div>

                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate class="space-y-5">
                            @csrf

                            {{-- รหัสผู้ใช้งาน --}}
                            <div>
                                <label for="id"
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-300 tracking-wide mb-1.5">
                                    รหัสผู้ใช้งาน <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <i class="fa-regular fa-user text-gray-400 dark:text-gray-500"></i>
                                    </div>
                                    <input id="id" name="id" type="text" autocomplete="username"
                                        value="{{ old('id') }}"
                                        class="block w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:placeholder-gray-500 dark:text-white transition-colors"
                                        placeholder="รหัสนักศึกษา หรือ รหัสอาจารย์" required>
                                </div>
                                <x-input-error :messages="$errors->get('id')" class="mt-2 text-xs" />
                            </div>

                            {{-- รหัสผ่าน --}}
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label for="password"
                                        class="block text-sm font-bold text-gray-700 dark:text-gray-300 tracking-wide">
                                        รหัสผ่าน <span class="text-rose-500">*</span>
                                    </label>
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}"
                                            class="text-xs font-bold text-orange-600 hover:text-orange-700 dark:text-orange-400 dark:hover:text-orange-300 transition-colors">
                                            ลืมรหัสผ่าน?
                                        </a>
                                    @endif
                                </div>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <i class="fa-solid fa-lock text-gray-400 dark:text-gray-500"></i>
                                    </div>
                                    <input id="password" name="password" type="password"
                                        autocomplete="current-password"
                                        class="block w-full pl-10 pr-10 py-3 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:placeholder-gray-500 dark:text-white transition-colors"
                                        placeholder="••••••••" required>

                                    <button type="button"
                                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none"
                                        onclick="const p = document.getElementById('password'); const i = this.querySelector('i'); if(p.type === 'password'){ p.type = 'text'; i.classList.remove('fa-eye'); i.classList.add('fa-eye-slash'); } else { p.type = 'password'; i.classList.remove('fa-eye-slash'); i.classList.add('fa-eye'); }">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
                            </div>

                            <button id="loginBtn" type="submit"
                                class="w-full rounded-xl py-3.5 mt-2 font-bold text-white bg-orange-500 hover:bg-orange-600 shadow-sm shadow-orange-500/30 disabled:bg-gray-300 disabled:shadow-none disabled:cursor-not-allowed transition-all flex items-center justify-center gap-2">
                                <span>เข้าสู่ระบบ</span>
                                <i class="fa-solid fa-arrow-right-to-bracket"></i>
                            </button>

                        </form>

                        @if (session('swal'))
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    Swal.fire({
                                        ...@json(session('swal')),
                                        confirmButtonColor: '#f97316'
                                    });
                                });
                            </script>
                        @endif
                    </div>
                </div>
            </div>

            <p class="text-center text-xs text-gray-500 dark:text-gray-400 mt-6 font-medium">
                &copy; {{ date('Y') }} CS&IT Phetchabun Rajabhat University. All rights reserved.
            </p>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const idInput = document.getElementById('id');
            const pwdInput = document.getElementById('password');
            const btn = document.getElementById('loginBtn');

            function updateBtn() {
                // เช็คว่ากรอกข้อมูลครบหรือยัง
                if (idInput.value.trim() && pwdInput.value.trim()) {
                    btn.disabled = false;
                } else {
                    btn.disabled = true;
                }
            }

            idInput.addEventListener('input', updateBtn);
            pwdInput.addEventListener('input', updateBtn);
            updateBtn(); // รันครั้งแรกเผื่อเบราว์เซอร์จำรหัสผ่านไว้

            // เปลี่ยนปุ่มเป็น Loading ตอนกด Submit
            const form = document.getElementById('loginForm');
            form.addEventListener('submit', function() {
                btn.disabled = true;
                btn.innerHTML =
                    '<i class="fa-solid fa-spinner fa-spin"></i> <span>กำลังเข้าสู่ระบบ...</span>';
            });
        });
    </script>
</body>

</html>
