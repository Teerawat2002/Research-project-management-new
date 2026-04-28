@props(['types' => collect(), 'typeId' => null])

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

    <style>
        [x-cloak] {
            display: none !important
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.0.379/pdf.min.js"></script> --}}

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/c2d79c304b.js" crossorigin="anonymous"></script>

</head>

<body class="font-sans text-gray-900 antialiased bg-gray-50">
    {{-- Header --}}
    <header class="sticky top-0 z-40 bg-red-600 text-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center gap-3">
            {{-- Menu button (mobile) --}}
            <button id="openSidebar" class="lg:hidden p-2 rounded hover:bg-red-700" aria-label="เปิดเมนู">
                <!-- icon hamburger -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            {{-- Logo --}}
            <a href="{{ route('welcome') }}" class="font-bold text-xl tracking-wide">
                Research Exam
            </a>

            {{-- Search (desktop) --}}
            <form id="siteSearch" action="{{ route('welcome') }}" method="GET" class="hidden lg:flex ml-auto group">
                <div
                    class="relative flex items-center w-72 md:w-96 xl:w-[38rem] bg-white rounded-full shadow-sm border border-transparent focus-within:ring-4 focus-within:ring-white/30 focus-within:shadow-md transition-all duration-300 ease-in-out">

                    {{-- ไอคอนค้นหาด้านหน้า (เพิ่มความสวยงาม) --}}
                    <div class="pl-4 pr-2 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    {{-- ช่องพิมพ์ --}}
                    <input id="qInput" type="text" name="q" value="{{ request('q') }}"
                        placeholder="พิมพ์คำที่ต้องการค้นหา..."
                        class="w-full bg-transparent text-gray-800 placeholder-gray-400 py-2.5 pr-2 border-none focus:ring-0 focus:outline-none text-sm"
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
                            class="hidden p-1.5 text-gray-400 hover:text-red-600 focus:outline-none transition-colors"
                            aria-label="ล้างข้อความ">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        {{-- เส้นคั่นบางๆ --}}
                        <div class="h-5 w-px bg-gray-200 mx-1"></div>

                        {{-- ปุ่มเลือกหมวด --}}
                        <button type="button" id="searchByBtn"
                            class="inline-flex items-center gap-1.5 text-gray-500 hover:text-red-600 px-3 py-1.5 rounded-full transition-colors focus:outline-none hover:bg-red-50"
                            aria-haspopup="true" aria-expanded="false">
                            <span id="searchByLabel" class="text-sm font-medium whitespace-nowrap">
                                {{ $labelMap[$by] ?? 'ตัวเลือก' }}
                            </span>
                            <svg class="w-4 h-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        {{-- ปุ่ม Submit (ปรับให้ดูมีมิติ) --}}
                        <button type="submit"
                            class="h-9 w-9 ml-1 rounded-full bg-gradient-to-r from-red-500 to-red-600 text-white flex items-center justify-center shadow-md hover:shadow-lg hover:from-red-600 hover:to-red-700 transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-white/50"
                            aria-label="ค้นหา">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </button>
                    </div>

                    {{-- เมนูหมวด (ปรับดีไซน์ให้ขอบมนและนุ่มนวลขึ้น) --}}
                    <div id="searchByMenu"
                        class="absolute right-0 top-full mt-3 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 hidden z-50">
                        <div class="px-4 pb-2 pt-1 text-xs font-bold text-gray-400 uppercase tracking-wider">Search in
                        </div>

                        @php
                            $itemClass =
                                'w-full text-left text-sm text-gray-600 px-4 py-2.5 transition-colors hover:bg-red-50 hover:text-red-700 focus:bg-red-50 focus:text-red-700';
                        @endphp

                        <button type="button" data-by="all"
                            class="{{ $itemClass }} {{ $by === 'all' ? 'font-semibold bg-red-50/50 text-red-600' : '' }}">
                            ตัวเลือก (ค่าเริ่มต้น)
                        </button>
                        <button type="button" data-by="title"
                            class="{{ $itemClass }} {{ $by === 'title' ? 'font-semibold bg-red-50/50 text-red-600' : '' }}">
                            ชื่อโครงงาน
                        </button>
                        <button type="button" data-by="keyword"
                            class="{{ $itemClass }} {{ $by === 'keyword' ? 'font-semibold bg-red-50/50 text-red-600' : '' }}">
                            คีย์เวิร์ด
                        </button>
                        <button type="button" data-by="abstract"
                            class="{{ $itemClass }} {{ $by === 'abstract' ? 'font-semibold bg-red-50/50 text-red-600' : '' }}">
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

            {{-- Mobile icon (ปรับให้ดูเข้ากับปุ่มอื่นๆ บน Header) --}}
            <button id="openSearch"
                class="ml-auto lg:hidden p-2.5 rounded-full bg-white/10 text-white hover:bg-white/20 transition-colors focus:outline-none focus:ring-2 focus:ring-white/50"
                aria-label="ค้นหา">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>

            {{-- Login (ตัวเลือกที่คอมเมนต์ไว้) --}}
            {{--
            <a href="{{ route('login') }}"
               class="shrink-0 inline-flex items-center gap-0 md:gap-2 bg-white text-red-600 px-2 md:px-4 py-2 rounded-full hover:bg-gray-100 whitespace-nowrap"
               aria-label="เข้าสู่ระบบ" title="เข้าสู่ระบบ">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M10 3a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-7 16a7 7 0 1 1 14 0v2H3v-2Z" />
                </svg>
                <span class="hidden md:inline">เข้าสู่ระบบ</span>
            </a>
            --}}

            {{-- Login --}}

            @if (Auth::guard('students')->check() || Auth::guard('advisors')->check())
                {{-- dropdown user --}}
                @php
                    $student = Auth::guard('students')->user();
                    $advisor = Auth::guard('advisors')->user();
                    $name = $student?->name ?? ($advisor?->name ?? '');
                @endphp

                <x-dropdown align="right" width="48">

                    <!-- Trigger -->
                    <x-slot name="trigger">
                        <button
                            class="flex items-center gap-2 bg-white text-red-600 px-2 py-1.5 md:px-3 md:py-2 rounded-full hover:bg-gray-100 transition-all shadow-sm border border-transparent focus:outline-none focus:ring-2 focus:ring-white/60">

                            <div
                                class="w-7 h-7 bg-red-100 text-red-500 rounded-full flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>

                            <div class="text-sm font-semibold truncate max-w-[80px] md:max-w-[150px]">
                                {{ $name ?: 'ทดสอบชื่อผู้ใช้' }}
                            </div>

                            <div class="text-gray-400 shrink-0 pr-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <!-- Dropdown Content -->
                    <x-slot name="content">

                        <!-- Profile Link -->
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- ระบบสอบ -->
                        <x-dropdown-link :href="route('dashboard')">
                            ระบบสอบ
                        </x-dropdown-link>

                        <!-- Logout Student -->
                        @if ($student)
                            <form method="POST" action="{{ route('studentLogout.logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('studentLogout.logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Log Out
                                </x-dropdown-link>
                            </form>
                        @endif

                        <!-- Logout Advisor -->
                        @if ($advisor)
                            <form method="POST" action="{{ route('advisorLogout.logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('advisorLogout.logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Log Out
                                </x-dropdown-link>
                            </form>
                        @endif

                    </x-slot>
                </x-dropdown>
            @else
                {{-- ปุ่ม login --}}
                <a href="{{ route('login') }}"
                    class="group shrink-0 inline-flex items-center rounded-full bg-white text-red-600 hover:bg-gray-100 px-2 md:px-4 py-2 transition-all duration-300 ease-out whitespace-nowrap"
                    aria-label="เข้าสู่ระบบ" title="เข้าสู่ระบบ">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M10 3a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-7 16a7 7 0 1 1 14 0v2H3v-2Z" />
                    </svg>

                    <span
                        class="inline-block overflow-hidden whitespace-nowrap max-w-0 opacity-0
                           group-hover:max-w-[8rem] group-hover:opacity-100 group-hover:ml-2
                           group-focus:max-w-[8rem] group-focus:opacity-100 group-focus:ml-2
                           transition-all duration-300 ease-out
                           md:max-w-none md:opacity-100 md:ml-2">
                        เข้าสู่ระบบ
                    </span>
                </a>
            @endif
        </div>

        <!-- Mobile expanding search panel -->
        <div id="mobileSearch"
            class="absolute inset-x-0 top-full lg:hidden px-4 py-3 bg-red-600 shadow-lg transform -translate-y-4 opacity-0 pointer-events-none transition-all duration-300 ease-out z-50">
            <form action="{{ route('project.index') }}" method="GET"
                class="max-w-7xl mx-auto flex items-center gap-3">

                {{-- กรอบค้นหาหลัก (Pill Design) --}}
                <div
                    class="relative flex-1 flex items-center bg-white rounded-full shadow-sm border border-transparent focus-within:ring-4 focus-within:ring-white/30 transition-all duration-200">

                    {{-- ไอคอนค้นหาด้านหน้า --}}
                    <div class="pl-3.5 pr-1.5 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    {{-- ช่องพิมพ์ --}}
                    <input id="mqInput" type="text" name="q" value="{{ request('q') }}"
                        placeholder="พิมพ์ค้นหา..."
                        class="w-full bg-transparent text-gray-800 placeholder-gray-400 py-2.5 pr-2 border-none focus:ring-0 focus:outline-none text-[15px]"
                        autocomplete="off" />

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
                    <div id="mSearchRightGroup" class="flex items-center pr-1 shrink-0">

                        {{-- ปุ่ม Clear (X) Mobile --}}
                        <button type="button" id="mClearQ"
                            class="hidden p-1 text-gray-400 hover:text-red-600 focus:outline-none transition-colors"
                            aria-label="ล้างข้อความ">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        {{-- เส้นคั่นบางๆ --}}
                        <div class="h-4 w-px bg-gray-200 mx-1"></div>

                        {{-- ปุ่มเลือกหมวด --}}
                        <button type="button" id="mSearchByBtn"
                            class="inline-flex items-center gap-1 text-gray-500 hover:text-red-600 px-2 py-1.5 rounded-full transition-colors focus:outline-none"
                            aria-haspopup="true" aria-expanded="false">
                            <span id="mSearchByLabel"
                                class="text-sm font-medium whitespace-nowrap hidden sm:inline-block">
                                {{ $labelMap[$by] ?? 'ตัวเลือก' }}
                            </span>
                            <span class="sm:hidden text-xs font-medium">{{ $by === 'all' ? 'หมวดหมู่' : '✔' }}</span>
                            <svg class="w-3.5 h-3.5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        {{-- ปุ่ม Submit (Mobile) --}}
                        <button type="submit"
                            class="h-8 w-8 ml-0.5 rounded-full bg-gradient-to-r from-red-500 to-red-600 text-white flex items-center justify-center shadow-md active:scale-95 transition-transform focus:outline-none"
                            aria-label="ค้นหา">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </button>
                    </div>

                    {{-- เมนูหมวด (Mobile) --}}
                    <div id="mSearchByMenu"
                        class="absolute right-0 top-full mt-2 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 hidden z-[60]">
                        <div class="px-4 pb-2 pt-1 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Search
                            in</div>

                        @php
                            $mItemClass =
                                'w-full text-left text-sm text-gray-600 px-4 py-2.5 transition-colors hover:bg-red-50 hover:text-red-700 focus:bg-red-50 focus:text-red-700 active:bg-red-100';
                        @endphp

                        <button type="button" data-by="all"
                            class="{{ $mItemClass }} {{ $by === 'all' ? 'font-semibold bg-red-50 text-red-600' : '' }}">
                            ตัวเลือก (ค่าเริ่มต้น)
                        </button>
                        <button type="button" data-by="title"
                            class="{{ $mItemClass }} {{ $by === 'title' ? 'font-semibold bg-red-50 text-red-600' : '' }}">
                            ชื่อโครงงาน
                        </button>
                        <button type="button" data-by="keyword"
                            class="{{ $mItemClass }} {{ $by === 'keyword' ? 'font-semibold bg-red-50 text-red-600' : '' }}">
                            คีย์เวิร์ด
                        </button>
                        <button type="button" data-by="abstract"
                            class="{{ $mItemClass }} {{ $by === 'abstract' ? 'font-semibold bg-red-50 text-red-600' : '' }}">
                            บทคัดย่อ
                        </button>
                    </div>
                </div>

                {{-- ปุ่มปิด Mobile Search --}}
                <button type="button" id="closeSearch"
                    class="p-2.5 text-white/80 hover:text-white rounded-full hover:bg-white/10 transition-colors shrink-0 focus:outline-none"
                    aria-label="ปิด">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                {{-- hidden params --}}
                <input type="hidden" name="by" id="mSearchByInput" value="{{ $by }}">
                @if (request()->filled('type'))
                    <input type="hidden" name="type" value="{{ request('type') }}">
                @endif
            </form>
        </div>
    </header>

    {{-- Body with Sidebar + Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 grid grid-cols-12 gap-6">
        {{-- Sidebar (col-span 3 on lg) --}}
        <aside id="sidebar"
            class="col-span-12 lg:col-span-3 lg:sticky lg:top-[76px] h-fit bg-white rounded-xl shadow-sm border border-gray-100 p-4 hidden lg:block">
            @include('layouts.sidebar-guest', ['types' => $types, 'typeId' => $typeId])
        </aside>

        {{-- Main content --}}
        <main class="col-span-12 lg:col-span-9">
            {{ $slot }}
        </main>
    </div>

    <!-- Mobile drawer (animate) -->
    <div id="drawer"
        class="fixed inset-0 z-50 lg:hidden bg-black/40 hidden opacity-0 pointer-events-none transition-opacity duration-300 ease-out">
        <div id="drawerPanel"
            class="absolute left-0 top-0 w-80 h-full bg-white shadow-xl p-4 transform -translate-x-full transition-transform duration-300 ease-out will-change-transform">
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

            @include('layouts.sidebar-guest', ['types' => $types, 'typeId' => $typeId])
        </div>
    </div>

    <script>
        // ====== Drawer ======
        const drawer = document.getElementById('drawer');
        const drawerPanel = document.getElementById('drawerPanel');
        const openSidebarBtn = document.getElementById('openSidebar');
        const closeSidebarBtn = document.getElementById('closeSidebar');

        function openDrawer() {
            // แสดง element ก่อน เพื่อให้ transition ทำงาน
            drawer.classList.remove('hidden');

            // รอ 1 เฟรมให้เบราว์เซอร์คำนวณสไตล์ แล้วค่อยใส่คลาสแสดง
            requestAnimationFrame(() => {
                drawer.classList.remove('opacity-0', 'pointer-events-none');
                drawer.classList.add('opacity-100', 'pointer-events-auto');
                drawerPanel.classList.remove('-translate-x-full');
                drawerPanel.classList.add('translate-x-0');
            });
        }

        function closeDrawer() {
            // ใส่คลาสซ่อนแบบมีแอนิเมชัน
            drawer.classList.add('opacity-0', 'pointer-events-none');
            drawer.classList.remove('opacity-100', 'pointer-events-auto');
            drawerPanel.classList.add('-translate-x-full');
            drawerPanel.classList.remove('translate-x-0');

            // รอ transition จบแล้วค่อยใส่ hidden เพื่อตัดออกจาก flow/Tab
            const onFadeEnd = (e) => {
                if (e.target !== drawer) return; // กัน event จากลูก
                drawer.classList.add('hidden');
                drawer.removeEventListener('transitionend', onFadeEnd);
            };

            drawer.addEventListener('transitionend', onFadeEnd);
        }

        openSidebarBtn?.addEventListener('click', openDrawer);
        closeSidebarBtn?.addEventListener('click', closeDrawer);

        // กดนอกแผงให้ปิด
        drawer?.addEventListener('click', (e) => {
            if (e.target === drawer) closeDrawer();
        });

        // กด ESC เพื่อปิด
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeDrawer();
        });

        // ====== Mobile Search Toggle ======
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

            // ฟังก์ชันซ่อน/แสดงปุ่มล้างข้อความ (X)
            function updateClearVisibility() {
                if (!clearBtn) return;
                if (q.value.length > 0) {
                    clearBtn.classList.remove('hidden');
                } else {
                    clearBtn.classList.add('hidden');
                }
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

            // กดที่ปุ่ม X เพื่อล้างข้อความ
            if (clearBtn) {
                clearBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    q.value = '';
                    q.focus();
                    updateClearVisibility();
                });
            }

            // ตรวจจับการพิมพ์เพื่อแสดง/ซ่อนปุ่ม X
            q.addEventListener('input', updateClearVisibility);

            // คลิกปุ่มเลือกหมวด
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                toggleMenu();
            });

            document.addEventListener('click', (e) => {
                if (!menu.contains(e.target) && e.target !== btn) closeMenu();
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeMenu();
            });

            // เมื่อเลือกเมนู: อัปเดตหมวด
            menu.querySelectorAll('button[data-by]').forEach(el => {
                el.addEventListener('click', () => {
                    const by = el.dataset.by;
                    inputBy.value = by;
                    label.textContent = labelMap[by] ?? 'ตัวเลือก';

                    menu.querySelectorAll('button[data-by]').forEach(b => {
                        b.classList.remove('font-semibold', 'bg-red-50', 'text-red-600');
                    });

                    el.classList.add('font-semibold', 'bg-red-50', 'text-red-600');
                    closeMenu();
                });
            });

            // init รอบแรกเช็คว่ามีข้อความค้างอยู่ไหม
            updateClearVisibility();
        }

        /* ====== Mobile Search Toggle (คงตามของเดิม) ====== */
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

        /* ====== ผูกใช้งานกับทั้ง Desktop และ Mobile ====== */
        initSearchUI({
            qId: 'qInput',
            groupId: 'searchRightGroup',
            clearId: 'clearQ',
            btnId: 'searchByBtn',
            menuId: 'searchByMenu',
            labelId: 'searchByLabel',
            inputById: 'searchByInput',
        });
        initSearchUI({
            qId: 'mqInput',
            groupId: 'mSearchRightGroup',
            clearId: 'mClearQ',
            btnId: 'mSearchByBtn',
            menuId: 'mSearchByMenu',
            labelId: 'mSearchByLabel',
            inputById: 'mSearchByInput',
        });
    </script>
</body>

</html>
