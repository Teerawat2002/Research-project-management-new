<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto mt-4">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200">
                    Project Tracking Status
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                    สถานะกลุ่มโครงงานและการเสนอหัวข้อ
                </p>
            </div>
        </div>

        <!-- Main Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors duration-200 overflow-hidden">

            <div
                class="p-5 border-b border-gray-50 dark:border-gray-700 bg-white dark:bg-gray-800 transition-colors duration-200">
                <form method="GET" action="{{ route('teacher.propose.index') }}" id="filterForm"
                    class="flex flex-col sm:flex-row justify-between items-center gap-4 w-full">

                    <!-- Hidden Input สำหรับเก็บค่าสถานะที่เลือก -->
                    <input type="hidden" name="status" id="hidden_status" value="{{ request('status') }}">

                    <!-- ส่วนค้นหาด้วยข้อความ -->
                    <div class="flex items-center gap-2 w-full sm:w-auto flex-1 max-w-md">

                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                <i class="fa-solid fa-magnifying-glass text-gray-400 dark:text-gray-500"></i>
                            </div>
                            <input type="text" name="search" id="table-search" value="{{ request('search') }}"
                                class="block w-full py-2.5 pl-10 pr-4 text-sm text-gray-900 border border-gray-200 rounded-xl bg-gray-50 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white transition-colors duration-200"
                                placeholder="ค้นหาชื่อโครงงาน, อาจารย์ที่ปรึกษา...">
                        </div>

                        <button type="submit"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-orange-500 rounded-xl hover:bg-orange-600 shadow-sm transition-colors duration-200 shrink-0 flex items-center justify-center gap-2 focus:ring-2 focus:ring-orange-500/50 outline-none">
                            <i class="fa-solid fa-search"></i>
                            <span class="hidden md:inline">ค้นหา</span>
                        </button>

                    </div>

                    <!-- ส่วน Dropdown สถานะ -->
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 shrink-0 hidden md:block">
                            <i class="fa-solid fa-filter mr-1 text-orange-500"></i> สถานะ:
                        </label>

                        @php
                            // รายการสถานะทั้งหมด
                            $statusList = [
                                '0' => 'อัพโหลดโครงงานแล้ว',
                                '1' => 'กำลังยื่นหัวข้อโครงงาน',
                                '2' => 'กำลังยื่นสอบโครงงาน',
                                '3' => 'กำลังดำเนินการสอบ',
                                '4' => 'กำลังแก้ไข',
                                '5' => 'กำลังอัพโหลดโครงงาน',
                            ];

                            // ดึงค่า status ออกมาเก็บไว้ในตัวแปร
                            $currentStatus = request('status');
                            $selectedStatusName = 'สถานะทั้งหมด';

                            // เช็คว่าไม่ใช่ null และไม่ใช่ค่าว่าง
                            if ($currentStatus !== null && $currentStatus !== '') {
                                $selectedStatusName = $statusList[$currentStatus] ?? 'สถานะทั้งหมด';
                            }
                        @endphp

                        <div class="relative w-full sm:w-56">
                            <x-dropdown align="right" width="56">
                                <x-slot name="trigger">
                                    <button type="button"
                                        class="flex items-center justify-between w-full py-2.5 px-4 text-sm text-gray-700 bg-white border border-gray-200 rounded-xl shadow-sm transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-orange-400 hover:border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:border-gray-500">
                                        <span class="truncate">{{ $selectedStatusName }}</span>
                                        <i
                                            class="fa-solid fa-chevron-down text-xs text-gray-400 dark:text-gray-500 ml-2"></i>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <!-- ตัวเลือก: สถานะทั้งหมด (Reset) -->
                                    <x-dropdown-link href="#"
                                        onclick="event.preventDefault(); document.getElementById('hidden_status').value=''; document.getElementById('filterForm').submit();"
                                        class="{{ $currentStatus === null || $currentStatus === '' ? 'bg-orange-50 text-orange-600 font-bold dark:bg-gray-700 dark:text-orange-400' : 'text-gray-700 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white' }}">
                                        สถานะทั้งหมด
                                    </x-dropdown-link>

                                    <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

                                    <!-- วนลูปแสดงตัวเลือกสถานะ -->
                                    @foreach ($statusList as $key => $label)
                                        <x-dropdown-link href="#"
                                            onclick="event.preventDefault(); document.getElementById('hidden_status').value='{{ $key }}'; document.getElementById('filterForm').submit();"
                                            class="{{ $currentStatus === (string) $key ? 'bg-orange-50 text-orange-600 font-bold dark:bg-gray-700 dark:text-orange-400' : 'text-gray-700 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white' }}">
                                            {{ $label }}
                                        </x-dropdown-link>
                                    @endforeach
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table Area -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400 transition-colors duration-200">
                    <thead
                        class="text-xs text-gray-500 uppercase bg-gray-50/50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 transition-colors duration-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold w-16 text-center">No.</th>
                            <th scope="col" class="px-6 py-4 font-semibold">ชื่อโครงงาน</th>
                            <th scope="col" class="px-6 py-4 font-semibold whitespace-nowrap">อาจารย์ที่ปรึกษา</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center whitespace-nowrap">สถานะ</th>
                            <th scope="col" class="px-6 py-4 font-semibold whitespace-nowrap">วันที่เสนอหัวข้อ</th>
                            <th scope="col" class="px-6 py-4 font-semibold whitespace-nowrap">อัปเดตล่าสุด</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center w-24">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @forelse ($propose as $proposal)
                            <tr
                                class="hover:bg-gray-50/80 dark:hover:bg-gray-700/50 transition-colors duration-200 group">

                                <!-- ลำดับ -->
                                <td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    {{ $loop->iteration + ($propose->currentPage() - 1) * $propose->perPage() }}
                                </td>

                                <!-- ชื่อโครงงาน -->
                                <td class="px-6 py-4">
                                    <div
                                        class="font-bold text-gray-900 dark:text-white text-sm group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors line-clamp-2">
                                        {{ $proposal->title }}
                                    </div>
                                </td>

                                <!-- อาจารย์ที่ปรึกษา -->
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-6 h-6 rounded-full bg-orange-100 dark:bg-orange-500/20 text-orange-500 flex items-center justify-center text-xs shrink-0">
                                            <i class="fa-solid fa-user-tie"></i>
                                        </div>
                                        <span class="truncate max-w-[150px]"
                                            title="{{ $proposal->advisor->name ?? 'N/A' }}">
                                            {{ $proposal->advisor->name ?? 'N/A' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- สถานะ -->
                                <td class="px-6 py-4 text-center">
                                    @switch($proposal->project_group->status)
                                        @case(0)
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-50 text-emerald-600 border border-emerald-200 dark:bg-emerald-500/10 dark:border-emerald-500/20 dark:text-emerald-400 whitespace-nowrap">
                                                <i class="fa-solid fa-circle-check mr-1.5"></i> อัพโหลดโครงงานแล้ว
                                            </span>
                                        @break

                                        @case(1)
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-amber-50 text-amber-600 border border-amber-200 dark:bg-amber-500/10 dark:border-amber-500/20 dark:text-amber-400 whitespace-nowrap">
                                                <i class="fa-solid fa-file-signature mr-1.5"></i> กำลังยื่นหัวข้อโครงงาน
                                            </span>
                                        @break

                                        @case(2)
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-600 border border-blue-200 dark:bg-blue-500/10 dark:border-blue-500/20 dark:text-blue-400 whitespace-nowrap">
                                                <i class="fa-solid fa-file-export mr-1.5"></i> กำลังยื่นสอบโครงงาน
                                            </span>
                                        @break

                                        @case(3)
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-indigo-50 text-indigo-600 border border-indigo-200 dark:bg-indigo-500/10 dark:border-indigo-500/20 dark:text-indigo-400 whitespace-nowrap">
                                                <i class="fa-solid fa-spinner fa-spin mr-1.5"></i> กำลังดำเนินการสอบ
                                            </span>
                                        @break

                                        @case(4)
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-rose-50 text-rose-600 border border-rose-200 dark:bg-rose-500/10 dark:border-rose-500/20 dark:text-rose-400 whitespace-nowrap">
                                                <i class="fa-solid fa-pen-ruler mr-1.5"></i> กำลังแก้ไข
                                            </span>
                                        @break

                                        @case(5)
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-purple-50 text-purple-600 border border-purple-200 dark:bg-purple-500/10 dark:border-purple-500/20 dark:text-purple-400 whitespace-nowrap">
                                                <i class="fa-solid fa-cloud-arrow-up mr-1.5"></i> กำลังอัพโหลดโครงงาน
                                            </span>
                                        @break

                                        @default
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 whitespace-nowrap">
                                                Unknown status
                                            </span>
                                    @endswitch
                                </td>

                                <!-- วันที่เสนอหัวข้อ -->
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400 text-sm whitespace-nowrap">
                                    {{ $proposal->created_at->format('d/m/Y') }}
                                </td>

                                <!-- อัปเดตล่าสุด -->
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400 text-sm whitespace-nowrap">
                                    {{ $proposal->updated_at->format('d/m/Y') }}
                                </td>

                                <!-- Action -->
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('teacher.propose.show', $proposal->id) }}" title="ดูรายละเอียด"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-orange-50 hover:text-orange-500 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-orange-400 transition-colors">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </td>

                            </tr>
                            @empty
                                <tr>
                                    <td class="px-6 py-16 text-center text-gray-500 dark:text-gray-400" colspan="7">
                                        <div class="flex flex-col items-center justify-center">
                                            <i
                                                class="fa-solid fa-clipboard-list text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                                            <p class="text-base font-medium">ไม่พบข้อมูลกลุ่มโครงงาน</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($propose->hasPages())
                    <div
                        class="p-4 border-t border-gray-100 bg-gray-50 dark:border-gray-700 dark:bg-gray-800 transition-colors duration-200 rounded-b-xl">
                        {{ $propose->links() }}
                    </div>
                @endif

            </div>
        </div>
    </x-app-layout>
