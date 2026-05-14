@props(['types' => collect(), 'typeId' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Research Exam</title>

    <link rel="icon" type="image/png" href="{{ asset('icons/logo_pcru.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Kanit', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://kit.fontawesome.com/c2d79c304b.js" crossorigin="anonymous"></script>
</head>

<body class="text-gray-900 antialiased bg-gray-50 dark:bg-gray-900 transition-colors duration-200">

    {{-- Header --}}
    <header
        class="sticky top-0 z-40 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm relative transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center gap-3">

            {{-- Menu button (mobile) --}}
            <button id="openSidebar"
                class="lg:hidden p-2 rounded-lg text-gray-500 hover:text-orange-500 hover:bg-orange-50 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors focus:outline-none"
                aria-label="เปิดเมนู">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            {{-- Logo --}}
            <a href="{{ route('welcome') }}"
                class="font-bold text-xl tracking-wide flex items-center gap-2 text-gray-800 dark:text-white group">
                <div
                    class="w-8 h-8 bg-orange-500 text-white rounded-lg flex items-center justify-center shadow-sm group-hover:bg-orange-600 transition-colors">
                    <i class="fa-solid fa-graduation-cap text-sm"></i>
                </div>
                <span>Research<span class="text-orange-500">Exam</span></span>
            </a>

            {{-- Search (desktop) --}}
            <form id="siteSearch" action="{{ route('welcome') }}" method="GET" class="hidden lg:flex ml-auto group">
                <div
                    class="relative flex items-center w-72 md:w-96 xl:w-[38rem] bg-gray-50 dark:bg-gray-900 rounded-full border border-gray-200 dark:border-gray-700 focus-within:ring-2 focus-within:ring-orange-500/20 focus-within:border-orange-500 transition-all duration-300 ease-in-out shadow-sm">

                    {{-- ไอคอนค้นหาด้านหน้า --}}
                    <div class="pl-4 pr-2 text-gray-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>

                    {{-- ช่องพิมพ์ --}}
                    <input id="qInput" type="text" name="q" value="{{ request('q') }}"
                        placeholder="พิมพ์คำที่ต้องการค้นหา..."
                        class="w-full bg-transparent text-gray-800 dark:text-white placeholder-gray-400 py-2.5 pr-2 border-none focus:ring-0 focus:outline-none text-sm"
                        autocomplete="off">

                    @php
                        $by = request('by', 'all'); // all|title|keyword|abstract
                        $labelMap = [
                            'all' => 'ตัวเลือก',
                            'title' => 'ชื่อโครงงาน',
                            'keyword' => 'คีย์เวิร์ด',
                            'abstract' => 'บทคัดย่อ',
                        ];
                    @endphp

                    {{-- กลุ่มปุ่มฝั่งขวา (หมวด + ค้นหา) --}}
                    <div id="searchRightGroup" class="flex items-center pr-1.5 shrink-0">

                        {{-- ปุ่ม Clear (X) Desktop --}}
                        <button type="button" id="clearQ"
                            class="hidden p-1.5 text-gray-400 hover:text-rose-500 focus:outline-none transition-colors"
                            aria-label="ล้างข้อความ">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>

                        {{-- เส้นคั่นบางๆ --}}
                        <div class="h-5 w-px bg-gray-300 dark:bg-gray-600 mx-1"></div>

                        {{-- ปุ่มเลือกหมวด --}}
                        <button type="button" id="searchByBtn"
                            class="inline-flex items-center gap-1.5 text-gray-500 dark:text-gray-400 hover:text-orange-500 dark:hover:text-orange-400 px-3 py-1.5 rounded-full transition-colors focus:outline-none hover:bg-orange-50 dark:hover:bg-orange-500/10"
                            aria-haspopup="true" aria-expanded="false">
                            <span id="searchByLabel" class="text-sm font-medium whitespace-nowrap">
                                {{ $labelMap[$by] ?? 'ตัวเลือก' }}
                            </span>
                            <i class="fa-solid fa-chevron-down text-xs text-gray-400"></i>
                        </button>

                        {{-- ปุ่ม Submit --}}
                        <button type="submit"
                            class="h-9 w-9 ml-1 rounded-full bg-orange-500 text-white flex items-center justify-center shadow-sm hover:bg-orange-600 transform hover:scale-105 transition-all duration-200 focus:outline-none"
                            aria-label="ค้นหา">
                            <i class="fa-solid fa-arrow-right text-sm"></i>
                        </button>
                    </div>

                    {{-- เมนูหมวด --}}
                    <div id="searchByMenu"
                        class="absolute right-0 top-full mt-2 w-48 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 py-2 hidden z-50 overflow-hidden">
                        <div
                            class="px-4 pb-2 pt-1 text-[11px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-50 dark:border-gray-700 mb-1">
                            Search in</div>

                        @php
                            $itemClass =
                                'w-full text-left text-sm text-gray-600 dark:text-gray-300 px-4 py-2.5 transition-colors hover:bg-orange-50 dark:hover:bg-gray-700 hover:text-orange-600 dark:hover:text-orange-400 focus:bg-orange-50 focus:text-orange-600';
                            $activeClass =
                                'font-bold bg-orange-50/50 text-orange-600 dark:bg-orange-500/10 dark:text-orange-400 border-l-2 border-orange-500';
                        @endphp

                        <button type="button" data-by="all"
                            class="{{ $itemClass }} {{ $by === 'all' ? $activeClass : 'border-l-2 border-transparent' }}">
                            ตัวเลือก (ค่าเริ่มต้น)
                        </button>
                        <button type="button" data-by="title"
                            class="{{ $itemClass }} {{ $by === 'title' ? $activeClass : 'border-l-2 border-transparent' }}">
                            ชื่อโครงงาน
                        </button>
                        <button type="button" data-by="keyword"
                            class="{{ $itemClass }} {{ $by === 'keyword' ? $activeClass : 'border-l-2 border-transparent' }}">
                            คีย์เวิร์ด
                        </button>
                        <button type="button" data-by="abstract"
                            class="{{ $itemClass }} {{ $by === 'abstract' ? $activeClass : 'border-l-2 border-transparent' }}">
                            บทคัดย่อ
                        </button>
                    </div>
                </div>

                {{-- ค่าโหมดการค้นหา --}}
                <input type="hidden" name="by" id="searchByInput" value="{{ $by }}">
                @if (request()->filled('type'))
                    <input type="hidden" name="type" value="{{ request('type') }}">
                @endif
            </form>

            <div class="flex items-center gap-2 ml-auto lg:ml-0 shrink-0">

                {{-- Mobile icon search --}}
                <button id="openSearch"
                    class="lg:hidden p-2.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:text-orange-500 hover:bg-orange-50 transition-colors focus:outline-none"
                    aria-label="ค้นหา">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>

                {{-- ปุ่มสลับ Dark/Light Mode --}}
                <button id="theme-toggle" type="button"
                    class="p-2.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:text-orange-500 hover:bg-orange-50 dark:hover:text-orange-400 dark:hover:bg-gray-600 transition-colors focus:outline-none"
                    aria-label="สลับโหมดมืดสว่าง">
                    <i id="theme-icon" class="fa-solid"></i>
                </button>

                <div class="w-px h-6 bg-gray-200 dark:bg-gray-600 mx-1 hidden md:block"></div>

                {{-- Login & Profile --}}
                @if (Auth::guard('students')->check() || Auth::guard('advisors')->check())
                    @php
                        $student = Auth::guard('students')->user();
                        $advisor = Auth::guard('advisors')->user();
                        $name = $student?->name ?? ($advisor?->name ?? '');
                    @endphp

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="flex items-center gap-2 bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-2 py-1.5 md:px-3 md:py-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600 transition-all shadow-sm border border-gray-200 dark:border-gray-600 focus:outline-none">
                                <div
                                    class="w-7 h-7 bg-orange-100 dark:bg-orange-500/20 text-orange-500 rounded-full flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-user text-xs"></i>
                                </div>
                                <div class="text-sm font-semibold truncate max-w-[80px] md:max-w-[150px]">
                                    {{ $name ?: 'ผู้ใช้งาน' }}
                                </div>
                                <div class="text-gray-400 shrink-0 pr-1">
                                    <i class="fa-solid fa-chevron-down text-[10px]"></i>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                <i class="fa-regular fa-id-badge w-5 text-center text-gray-400 mr-1"></i>
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('dashboard')">
                                <i class="fa-solid fa-gauge-high w-5 text-center text-gray-400 mr-1"></i> ระบบสอบ
                            </x-dropdown-link>

                            <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

                            @if ($student)
                                <form method="POST" action="{{ route('studentLogout.logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('studentLogout.logout')"
                                        class="text-rose-500 hover:bg-rose-50 hover:text-rose-600"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                        <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center mr-1"></i> Log
                                        Out
                                    </x-dropdown-link>
                                </form>
                            @endif

                            @if ($advisor)
                                <form method="POST" action="{{ route('advisorLogout.logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('advisorLogout.logout')"
                                        class="text-rose-500 hover:bg-rose-50 hover:text-rose-600"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                        <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center mr-1"></i> Log
                                        Out
                                    </x-dropdown-link>
                                </form>
                            @endif
                        </x-slot>
                    </x-dropdown>
                @else
                    {{-- ปุ่ม login สำหรับ Guest --}}
                    <a href="{{ route('login') }}"
                        class="group shrink-0 inline-flex items-center rounded-full bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400 hover:bg-orange-100 px-2 md:px-4 py-2 transition-all duration-300 ease-out whitespace-nowrap shadow-sm border border-orange-100 dark:border-orange-500/20"
                        aria-label="เข้าสู่ระบบ" title="เข้าสู่ระบบ">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span
                            class="inline-block overflow-hidden whitespace-nowrap max-w-0 opacity-0 group-hover:max-w-[8rem] group-hover:opacity-100 group-hover:ml-2 font-bold transition-all duration-300 ease-out md:max-w-none md:opacity-100 md:ml-2">
                            เข้าสู่ระบบ
                        </span>
                    </a>
                @endif

            </div>
        </div>

        <div id="mobileSearch"
            class="absolute inset-x-0 top-full lg:hidden px-4 py-3 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-lg transform -translate-y-4 opacity-0 pointer-events-none transition-all duration-300 ease-out z-50">
            <form action="{{ route('project.index') }}" method="GET"
                class="max-w-7xl mx-auto flex items-center gap-3">
                <div
                    class="relative flex-1 flex items-center bg-gray-50 dark:bg-gray-900 rounded-full border border-gray-200 dark:border-gray-700 focus-within:ring-2 focus-within:ring-orange-500/20 focus-within:border-orange-500 transition-all duration-200 shadow-sm">

                    <div class="pl-3.5 pr-1.5 text-gray-400">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </div>

                    <input id="mqInput" type="text" name="q" value="{{ request('q') }}"
                        placeholder="พิมพ์ค้นหา..."
                        class="w-full bg-transparent text-gray-800 dark:text-white placeholder-gray-400 py-2.5 pr-2 border-none focus:ring-0 focus:outline-none text-[15px]"
                        autocomplete="off" />

                    @php
                        $by = request('by', 'all');
                    @endphp

                    <div id="mSearchRightGroup" class="flex items-center pr-1 shrink-0">
                        <button type="button" id="mClearQ"
                            class="hidden p-1 text-gray-400 hover:text-rose-500 focus:outline-none transition-colors">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>

                        <div class="h-4 w-px bg-gray-300 dark:bg-gray-600 mx-1"></div>

                        <button type="button" id="mSearchByBtn"
                            class="inline-flex items-center gap-1.5 text-gray-500 hover:text-orange-500 px-2 py-1.5 rounded-full transition-colors focus:outline-none">
                            <span id="mSearchByLabel"
                                class="text-xs font-medium whitespace-nowrap truncate max-w-[80px]">
                                {{ $labelMap[$by] ?? 'ตัวเลือก' }}
                            </span>
                            <i class="fa-solid fa-chevron-down text-[10px]"></i>
                        </button>

                        <button type="submit"
                            class="h-8 w-8 ml-0.5 rounded-full bg-orange-500 text-white flex items-center justify-center shadow-sm active:scale-95 transition-transform focus:outline-none">
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    </div>

                    {{-- เมนูหมวด (Mobile) --}}
                    <div id="mSearchByMenu"
                        class="absolute right-0 top-full mt-2 w-48 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 py-2 hidden z-[60] overflow-hidden">
                        <div
                            class="px-4 pb-2 pt-1 text-[11px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-50 dark:border-gray-700 mb-1">
                            Search in</div>

                        @php
                            $mItemClass =
                                'w-full text-left text-sm text-gray-600 dark:text-gray-300 px-4 py-2.5 transition-colors hover:bg-orange-50 dark:hover:bg-gray-700 hover:text-orange-600 dark:hover:text-orange-400 focus:bg-orange-50 focus:text-orange-600 active:bg-orange-100';
                            $mActiveClass =
                                'font-bold bg-orange-50 text-orange-600 dark:bg-orange-500/10 dark:text-orange-400 border-l-2 border-orange-500';
                        @endphp

                        <button type="button" data-by="all"
                            class="{{ $mItemClass }} {{ $by === 'all' ? $mActiveClass : 'border-l-2 border-transparent' }}">
                            ตัวเลือก (ค่าเริ่มต้น)
                        </button>
                        <button type="button" data-by="title"
                            class="{{ $mItemClass }} {{ $by === 'title' ? $mActiveClass : 'border-l-2 border-transparent' }}">
                            ชื่อโครงงาน
                        </button>
                        <button type="button" data-by="keyword"
                            class="{{ $mItemClass }} {{ $by === 'keyword' ? $mActiveClass : 'border-l-2 border-transparent' }}">
                            คีย์เวิร์ด
                        </button>
                        <button type="button" data-by="abstract"
                            class="{{ $mItemClass }} {{ $by === 'abstract' ? $mActiveClass : 'border-l-2 border-transparent' }}">
                            บทคัดย่อ
                        </button>
                    </div>
                </div>

                {{-- ปุ่มปิด Mobile Search --}}
                <button type="button" id="closeSearch"
                    class="p-2.5 text-gray-500 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-gray-700 rounded-full transition-colors shrink-0 focus:outline-none">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>

                <input type="hidden" name="by" id="mSearchByInput" value="{{ $by }}">
                @if (request()->filled('type'))
                    <input type="hidden" name="type" value="{{ request('type') }}">
                @endif
            </form>
        </div>
    </header>

    {{-- Body with Sidebar + Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 grid grid-cols-12 gap-6">
        {{-- Sidebar --}}
        <aside id="sidebar"
            class="col-span-12 lg:col-span-3 lg:sticky lg:top-[85px] h-fit bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hidden lg:block">
            @include('layouts.sidebar-guest', ['types' => $types, 'typeId' => $typeId])
        </aside>

        {{-- Main content --}}
        <main class="col-span-12 lg:col-span-9">
            {{ $slot }}
        </main>
    </div>

    <div id="drawer"
        class="fixed inset-0 z-50 lg:hidden bg-black/50 backdrop-blur-sm hidden opacity-0 pointer-events-none transition-opacity duration-300 ease-out">
        <div id="drawerPanel"
            class="absolute left-0 top-0 w-80 h-full bg-white dark:bg-gray-800 shadow-2xl p-5 transform -translate-x-full transition-transform duration-300 ease-out will-change-transform flex flex-col">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100 dark:border-gray-700">
                <h3 class="font-bold text-lg text-gray-800 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-layer-group text-orange-500"></i> หมวดหมู่
                </h3>
                <button id="closeSidebar"
                    class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500 hover:bg-rose-50 hover:text-rose-500 transition-colors focus:outline-none"
                    aria-label="ปิดเมนู">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto pr-2">
                @include('layouts.sidebar-guest', ['types' => $types, 'typeId' => $typeId])
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // ====== Dark Mode Toggle ======
            const themeToggleBtn = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');

            // 1. ฟังก์ชันจัดการไอคอน
            function updateThemeIcon() {
                if (document.documentElement.classList.contains('dark')) {
                    // ถ้าอยู่ "โหมดมืด" ให้แสดงไอคอน "พระอาทิตย์" (เพื่อสื่อว่ากดแล้วจะสว่าง)
                    themeIcon.classList.remove('fa-moon');
                    themeIcon.classList.add('fa-sun');
                } else {
                    // ถ้าอยู่ "โหมดสว่าง" ให้แสดงไอคอน "พระจันทร์" (เพื่อสื่อว่ากดแล้วจะมืด)
                    themeIcon.classList.remove('fa-sun');
                    themeIcon.classList.add('fa-moon');
                }
            }

            // 2. เรียกใช้งานครั้งแรกตอนโหลดหน้าเว็บ เพื่อให้ไอคอนแสดงตรงกับโหมดปัจจุบัน
            updateThemeIcon();

            // 3. เมื่อคลิกปุ่มสลับโหมด
            themeToggleBtn.addEventListener('click', function() {
                // สลับคลาส 'dark' ที่แท็ก <html>
                document.documentElement.classList.toggle('dark');

                // บันทึกค่าที่เลือกลงใน LocalStorage
                if (document.documentElement.classList.contains('dark')) {
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    localStorage.setItem('color-theme', 'light');
                }

                // อัปเดตไอคอนทันทีหลังกด
                updateThemeIcon();
            });


            // ====== Drawer Script ======
            const drawer = document.getElementById('drawer');
            const drawerPanel = document.getElementById('drawerPanel');
            const openSidebarBtn = document.getElementById('openSidebar');
            const closeSidebarBtn = document.getElementById('closeSidebar');

            function openDrawer() {
                drawer.classList.remove('hidden');
                requestAnimationFrame(() => {
                    drawer.classList.remove('opacity-0', 'pointer-events-none');
                    drawer.classList.add('opacity-100', 'pointer-events-auto');
                    drawerPanel.classList.remove('-translate-x-full');
                    drawerPanel.classList.add('translate-x-0');
                });
            }

            function closeDrawer() {
                drawer.classList.add('opacity-0', 'pointer-events-none');
                drawer.classList.remove('opacity-100', 'pointer-events-auto');
                drawerPanel.classList.add('-translate-x-full');
                drawerPanel.classList.remove('translate-x-0');

                const onFadeEnd = (e) => {
                    if (e.target !== drawer) return;
                    drawer.classList.add('hidden');
                    drawer.removeEventListener('transitionend', onFadeEnd);
                };
                drawer.addEventListener('transitionend', onFadeEnd);
            }

            openSidebarBtn?.addEventListener('click', openDrawer);
            closeSidebarBtn?.addEventListener('click', closeDrawer);
            drawer?.addEventListener('click', (e) => {
                if (e.target === drawer) closeDrawer();
            });
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeDrawer();
            });


            // ====== Search Toggle Scripts ======
            function initSearchUI({
                qId,
                groupId,
                btnId,
                menuId,
                labelId,
                inputById,
                clearId
            }) {
                const q = document.getElementById(qId);
                const btn = document.getElementById(btnId);
                const menu = document.getElementById(menuId);
                const label = document.getElementById(labelId);
                const inputBy = document.getElementById(inputById);
                const clearBtn = document.getElementById(clearId);

                if (!q || !btn || !menu || !label || !inputBy) return;

                const labelMap = {
                    all: 'ตัวเลือก',
                    title: 'ชื่อโครงงาน',
                    keyword: 'คีย์เวิร์ด',
                    abstract: 'บทคัดย่อ'
                };

                function updateClearVisibility() {
                    if (!clearBtn) return;
                    if (q.value.length > 0) clearBtn.classList.remove('hidden');
                    else clearBtn.classList.add('hidden');
                }

                function openMenu() {
                    btn.setAttribute('aria-expanded', 'true');
                    menu.classList.remove('hidden');
                }

                function closeMenu() {
                    btn.setAttribute('aria-expanded', 'false');
                    menu.classList.add('hidden');
                }

                function toggleMenu() {
                    menu.classList.contains('hidden') ? openMenu() : closeMenu();
                }

                if (clearBtn) {
                    clearBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        q.value = '';
                        q.focus();
                        updateClearVisibility();
                    });
                }

                q.addEventListener('input', updateClearVisibility);

                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleMenu();
                });

                document.addEventListener('click', (e) => {
                    if (!menu.contains(e.target) && e.target !== btn) closeMenu();
                });

                menu.querySelectorAll('button[data-by]').forEach(el => {
                    el.addEventListener('click', () => {
                        const by = el.dataset.by;
                        inputBy.value = by;
                        label.textContent = labelMap[by] ?? 'ตัวเลือก';

                        menu.querySelectorAll('button[data-by]').forEach(b => {
                            b.classList.remove('font-bold', 'bg-orange-50/50',
                                'text-orange-600', 'dark:bg-orange-500/10',
                                'dark:text-orange-400', 'border-orange-500',
                                'bg-orange-50');
                            b.classList.add('border-transparent');
                        });

                        el.classList.remove('border-transparent');
                        el.classList.add('font-bold', 'bg-orange-50/50', 'text-orange-600',
                            'dark:bg-orange-500/10', 'dark:text-orange-400', 'border-orange-500'
                        );
                        closeMenu();
                    });
                });

                updateClearVisibility();
            }

            const mobileSearch = document.getElementById('mobileSearch');
            const openSearchBtn = document.getElementById('openSearch');
            const closeSearchBtn = document.getElementById('closeSearch');
            const mobileSearchInput = document.getElementById('mqInput');

            function showMobileSearch() {
                mobileSearch.classList.remove('-translate-y-4', 'opacity-0', 'pointer-events-none');
                mobileSearch.classList.add('translate-y-0', 'opacity-100', 'pointer-events-auto');
                setTimeout(() => mobileSearchInput?.focus(), 50);
            }

            function hideMobileSearch() {
                mobileSearch.classList.add('-translate-y-4', 'opacity-0', 'pointer-events-none');
                mobileSearch.classList.remove('translate-y-0', 'opacity-100', 'pointer-events-auto');
            }

            openSearchBtn?.addEventListener('click', (e) => {
                e.stopPropagation();
                showMobileSearch();
            });
            closeSearchBtn?.addEventListener('click', hideMobileSearch);
            document.addEventListener('click', (e) => {
                if (!mobileSearch.contains(e.target) && e.target !== openSearchBtn) hideMobileSearch();
            });
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') hideMobileSearch();
            });

            initSearchUI({
                qId: 'qInput',
                groupId: 'searchRightGroup',
                clearId: 'clearQ',
                btnId: 'searchByBtn',
                menuId: 'searchByMenu',
                labelId: 'searchByLabel',
                inputById: 'searchByInput'
            });
            initSearchUI({
                qId: 'mqInput',
                groupId: 'mSearchRightGroup',
                clearId: 'mClearQ',
                btnId: 'mSearchByBtn',
                menuId: 'mSearchByMenu',
                labelId: 'mSearchByLabel',
                inputById: 'mSearchByInput'
            });

        });
    </script>
</body>

</html>
