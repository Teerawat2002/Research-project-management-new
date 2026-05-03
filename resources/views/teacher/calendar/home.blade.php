<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto mt-4">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200">
                    Academic Calendar
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                    ปฏิทินการศึกษาและกำหนดการที่สำคัญ
                </p>
            </div>
        </div>

        <!-- Main Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors duration-200 overflow-hidden">

            <!-- Filter Area -->
            @if (Auth::guard('advisors')->check())
                <div
                    class="p-5 border-b border-gray-50 dark:border-gray-700 bg-white dark:bg-gray-800 transition-colors duration-200 flex justify-end">

                    <form method="GET" action="{{ route('teacher.calendar.home') }}" id="calendar-form"
                        class="w-full sm:w-auto">
                        <!-- Hidden Input สำหรับเก็บค่าปีที่เลือก -->
                        <input type="hidden" name="ac_id" id="hidden_ac_id" value="{{ request('ac_id') }}">

                        <div class="flex items-center gap-3">
                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 shrink-0">
                                <i class="fa-solid fa-calendar-day mr-1 text-orange-500"></i> ปีการศึกษา:
                            </label>

                            <!-- Custom Dropdown Component -->
                            @php
                                $selectedYearName = '-- เลือกปีการศึกษา --';
                                if (request('ac_id')) {
                                    foreach ($academicYears as $year) {
                                        if (request('ac_id') == $year->id) {
                                            $selectedYearName = $year->year;
                                            break;
                                        }
                                    }
                                }
                            @endphp

                            <div class="relative w-full sm:w-48">
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button type="button"
                                            class="flex items-center justify-between w-full py-2.5 px-4 text-sm text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-orange-400 hover:border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:border-gray-500">
                                            <span class="truncate">{{ $selectedYearName }}</span>
                                            <i
                                                class="fa-solid fa-chevron-down text-xs text-gray-400 dark:text-gray-500 ml-2"></i>
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        <!-- ปุ่มรีเซ็ต / แสดงทั้งหมด -->
                                        <x-dropdown-link href="#"
                                            onclick="event.preventDefault(); document.getElementById('hidden_ac_id').value=''; document.getElementById('calendar-form').submit();"
                                            class="{{ request('ac_id') == '' ? 'bg-orange-50 text-orange-600 font-bold dark:bg-gray-700 dark:text-orange-400' : 'text-gray-700 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white' }}">
                                            -- เลือกปีการศึกษา --
                                        </x-dropdown-link>

                                        <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

                                        <!-- รายชื่อปีการศึกษา -->
                                        @foreach ($academicYears as $year)
                                            <x-dropdown-link href="#"
                                                onclick="event.preventDefault(); document.getElementById('hidden_ac_id').value='{{ $year->id }}'; document.getElementById('calendar-form').submit();"
                                                class="{{ request('ac_id') == $year->id ? 'bg-orange-50 text-orange-600 font-bold dark:bg-gray-700 dark:text-orange-400' : 'text-gray-700 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white' }}">
                                                {{ $year->year }}
                                            </x-dropdown-link>
                                        @endforeach
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        </div>
                    </form>

                </div>
            @endif

            <!-- Table Area -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400 transition-colors duration-200">
                    <thead
                        class="text-xs text-gray-500 uppercase bg-gray-50/50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 transition-colors duration-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold whitespace-nowrap">
                                <i class="fa-regular fa-clock mr-1"></i> วันที่ (Date)
                            </th>
                            <th scope="col" class="px-6 py-4 font-semibold">
                                <i class="fa-solid fa-list-check mr-1"></i> รายการ (Event)
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @forelse ($calendarData as $data)
                            <tr
                                class="hover:bg-orange-50/50 dark:hover:bg-gray-700/50 transition-colors duration-200 group">
                                <td class="px-6 py-5 align-top whitespace-nowrap">
                                    <div
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-orange-50 text-orange-700 border border-orange-200 dark:bg-orange-500/10 dark:text-orange-400 dark:border-orange-500/20">
                                        {{ \Carbon\Carbon::parse($data->start_date)->format('d/m/Y') }}

                                        <!-- เช็คว่าถ้าวันเริ่มต้นกับวันสิ้นสุดไม่ใช่วันเดียวกัน ถึงจะโชว์ลูกศรและวันสิ้นสุด -->
                                        @if (
                                            \Carbon\Carbon::parse($data->start_date)->format('Y-m-d') !==
                                                \Carbon\Carbon::parse($data->end_date)->format('Y-m-d'))
                                            <span class="mx-2 text-orange-400 dark:text-orange-600">
                                                <i class="fa-solid fa-arrow-right"></i>
                                            </span>
                                            {{ \Carbon\Carbon::parse($data->end_date)->format('d/m/Y') }}
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div
                                        class="font-bold text-gray-900 dark:text-white text-base mb-1.5 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">
                                        {{ $data->title }}
                                    </div>
                                    @if ($data->description)
                                        <div class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                                            {{ $data->description }}
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-16 text-center text-gray-500 dark:text-gray-400" colspan="2">
                                    <div class="flex flex-col items-center justify-center">
                                        <i
                                            class="fa-regular fa-calendar-xmark text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                                        <p class="text-base font-medium">ไม่พบข้อมูลปฏิทินการศึกษาในปีนี้</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
