<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto mt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-file-invoice text-orange-500"></i> Exam Submissions
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                    รายการยื่นสอบ การจัดตาราง และการประเมินผลโครงงาน
                </p>
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors duration-200">

            <div
                class="p-5 border-b border-gray-50 dark:border-gray-700 bg-white dark:bg-gray-800 transition-colors duration-200">
                <form method="GET" action="{{ route('advisor.submission.index') }}" id="filterForm"
                    class="flex flex-col md:flex-row justify-between items-center gap-4 w-full">
                    @csrf
                    <input type="hidden" name="status" id="hidden_status" value="{{ request('status') }}">

                    <div class="flex gap-2 w-full md:max-w-md">
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fa-solid fa-magnifying-glass text-gray-400 dark:text-gray-500"></i>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="block w-full p-2.5 pl-10 text-sm text-gray-900 border border-gray-200 rounded-xl bg-gray-50 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-orange-500 transition-colors duration-200"
                                placeholder="ค้นหาด้วยชื่อโครงงาน...">
                        </div>

                        <button type="submit"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-orange-500 rounded-xl hover:bg-orange-600 shadow-sm transition-colors duration-200 shrink-0 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-search"></i> ค้นหา
                        </button>
                    </div>

                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 shrink-0 hidden sm:block">
                            <i class="fa-solid fa-filter mr-1 text-orange-500"></i> สถานะ:
                        </label>

                        @php
                            // รายการสถานะทั้งหมดของ Exam Submission
                            $statusList = [
                                'null' => 'ยังไม่ได้ยื่นสอบ', // สมมติว่าต้องการหากรณีที่เป็น default หรือ NULL ถ้าต้องการให้ลบออกได้ครับ
                                '1' => 'รอการอนุมัติ',
                                '3' => 'รอจัดตารางสอบ',
                                '4' => 'กำลังดำเนินการสอบ',
                                '0' => 'การสอบเสร็จสิ้น',
                                '2' => 'ยื่นสอบไม่ผ่าน',
                            ];
                            $currentStatus = request('status');
                            $selectedStatusName =
                                $currentStatus !== null && $currentStatus !== ''
                                    ? $statusList[$currentStatus] ?? 'สถานะทั้งหมด'
                                    : 'สถานะทั้งหมด';
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
                                    <x-dropdown-link href="#"
                                        onclick="event.preventDefault(); document.getElementById('hidden_status').value=''; document.getElementById('filterForm').submit();"
                                        class="{{ $currentStatus === null || $currentStatus === '' ? 'bg-orange-50 text-orange-600 font-bold dark:bg-gray-700 dark:text-orange-400' : 'text-gray-700 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white' }}">
                                        สถานะทั้งหมด
                                    </x-dropdown-link>

                                    <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

                                    @foreach ($statusList as $key => $label)
                                        <x-dropdown-link href="#"
                                            onclick="event.preventDefault(); document.getElementById('hidden_status').value='{{ $key === 'null' ? 'default_null_value' : $key }}'; document.getElementById('filterForm').submit();"
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

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400 transition-colors duration-200">
                    <thead
                        class="text-xs text-gray-500 uppercase bg-gray-50/50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 transition-colors duration-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold w-20 text-center">ลำดับ</th>
                            <th scope="col" class="px-6 py-4 font-semibold min-w-[250px]">ชื่อโครงงาน</th>
                            <th scope="col" class="px-6 py-4 font-semibold whitespace-nowrap">รายวิชา</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center whitespace-nowrap">สถานะ</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center w-40">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @forelse ($examSubmissions as $submission)
                            <tr
                                class="hover:bg-orange-50/50 dark:hover:bg-gray-700/50 transition-colors duration-200 group">

                                <td class="px-6 py-5 text-center text-gray-500 dark:text-gray-400 font-medium">
                                    {{ $loop->iteration + ($examSubmissions->currentPage() - 1) * $examSubmissions->perPage() }}
                                </td>

                                <td class="px-6 py-5">
                                    <div
                                        class="font-bold text-gray-900 dark:text-white text-sm group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors line-clamp-2">
                                        {{ $submission->propose->title ?? 'ไม่มีชื่อโครงงาน' }}
                                    </div>
                                </td>

                                <td class="px-6 py-5 text-gray-700 dark:text-gray-300 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                        {{ $submission->exam_type->name ?? '-' }}
                                    </span>
                                </td>

                                <td class="px-6 py-5 text-center">
                                    @switch($submission->status)
                                        @case(0)
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-50 text-emerald-600 border border-emerald-200 dark:bg-emerald-500/10 dark:border-emerald-500/20 dark:text-emerald-400 whitespace-nowrap">
                                                <i class="fa-solid fa-circle-check mr-1.5"></i> การสอบเสร็จสิ้น
                                            </span>
                                        @break

                                        @case(1)
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-amber-50 text-amber-600 border border-amber-200 dark:bg-amber-500/10 dark:border-amber-500/20 dark:text-amber-400 whitespace-nowrap">
                                                <i class="fa-solid fa-clock mr-1.5"></i> รอการอนุมัติ
                                            </span>
                                        @break

                                        @case(2)
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-rose-50 text-rose-600 border border-rose-200 dark:bg-rose-500/10 dark:border-rose-500/20 dark:text-rose-400 whitespace-nowrap">
                                                <i class="fa-solid fa-circle-xmark mr-1.5"></i> ยื่นสอบไม่ผ่าน
                                            </span>
                                        @break

                                        @case(3)
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-indigo-50 text-indigo-600 border border-indigo-200 dark:bg-indigo-500/10 dark:border-indigo-500/20 dark:text-indigo-400 whitespace-nowrap">
                                                <i class="fa-solid fa-calendar-day mr-1.5"></i> รอจัดตารางสอบ
                                            </span>
                                        @break

                                        @case(4)
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-purple-50 text-purple-600 border border-purple-200 dark:bg-purple-500/10 dark:border-purple-500/20 dark:text-purple-400 whitespace-nowrap">
                                                <i class="fa-solid fa-spinner fa-spin mr-1.5"></i> กำลังดำเนินการสอบ
                                            </span>
                                        @break

                                        @default
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 whitespace-nowrap">
                                                ยังไม่ได้ยื่นสอบ
                                            </span>
                                    @endswitch
                                </td>

                                <td class="px-6 py-5 text-center">
                                    <div class="flex items-center justify-center gap-2">

                                        @switch($submission->status)
                                            @case(0)
                                                <button type="button" title="รายละเอียด"
                                                    onclick="window.location.href='{{ route('advisor.submission.view', $submission->id) }}'"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-blue-50 hover:text-blue-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-blue-400 transition-colors">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                                @if (strtolower($submission->exam_type->name ?? '') !== 'aucc')
                                                    <button type="button" title="ประวัติ"
                                                        onclick="window.location.href='{{ route('advisor.submission.history', $submission->id) }}'"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-gray-200 hover:text-gray-700 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200 transition-colors">
                                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                                    </button>
                                                    <button type="button" title="ดูตารางสอบ"
                                                        onclick="window.location.href='{{ route('advisor.schedule.show', $submission->id) }}'"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-cyan-50 hover:text-cyan-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-cyan-400 transition-colors">
                                                        <i class="fa-solid fa-calendar"></i>
                                                    </button>
                                                @endif
                                                <button type="button" title="ดูเกรด / ให้คะแนน"
                                                    onclick="window.location.href='{{ route('advisor.score.view', $submission->id) }}'"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-emerald-400 transition-colors">
                                                    <i class="fa-solid fa-graduation-cap"></i>
                                                </button>
                                            @break

                                            @case(1)
                                                @if (strtolower($submission->exam_type->name ?? '') == 'aucc')
                                                    <button type="button" title="ให้คะแนน AUCC"
                                                        onclick="window.location.href='{{ route('advisor.submission.auccForm', $submission->id) }}'"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-amber-50 hover:text-amber-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-amber-400 transition-colors">
                                                        <i class="fa-solid fa-pen-fancy"></i>
                                                    </button>
                                                    <button type="button" title="ประวัติ"
                                                        onclick="window.location.href='{{ route('advisor.submission.history', $submission->id) }}'"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-blue-50 hover:text-blue-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-blue-400 transition-colors">
                                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                                    </button>
                                                @else
                                                    <button type="button" title="การอนุมัติ"
                                                        onclick="window.location.href='{{ route('advisor.submission.submission', $submission->propose_id) }}'"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-orange-50 hover:text-orange-500 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-orange-400 transition-colors">
                                                        <i class="fa-solid fa-clipboard-check"></i>
                                                    </button>
                                                    <button type="button" title="ประวัติ"
                                                        onclick="window.location.href='{{ route('advisor.submission.history', $submission->id) }}'"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-blue-50 hover:text-blue-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-blue-400 transition-colors">
                                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                                    </button>
                                                @endif
                                            @break

                                            @case(2)
                                            @case(4)
                                                <button type="button" title="รายละเอียด"
                                                    onclick="window.location.href='{{ route('advisor.submission.view', $submission->id) }}'"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-blue-50 hover:text-blue-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-blue-400 transition-colors">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                                <button type="button" title="ดูตารางสอบ"
                                                    onclick="window.location.href='{{ route('advisor.schedule.show', $submission->id) }}'"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-cyan-50 hover:text-cyan-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-cyan-400 transition-colors">
                                                    <i class="fa-solid fa-calendar"></i>
                                                </button>
                                                <button type="button" title="ประวัติ"
                                                    onclick="window.location.href='{{ route('advisor.submission.history', $submission->id) }}'"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-gray-200 hover:text-gray-700 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200 transition-colors">
                                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                                </button>
                                            @break

                                            @case(3)
                                                <button type="button" title="รายละเอียด"
                                                    onclick="window.location.href='{{ route('advisor.submission.view', $submission->id) }}'"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-blue-50 hover:text-blue-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-blue-400 transition-colors">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                                <button type="button" title="ประวัติ"
                                                    onclick="window.location.href='{{ route('advisor.submission.history', $submission->id) }}'"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-gray-200 hover:text-gray-700 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200 transition-colors">
                                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                                </button>
                                                <button type="button" title="จัดตารางสอบ"
                                                    onclick="window.location.href='{{ route('advisor.schedule.create', $submission->id) }}'"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-indigo-50 hover:text-indigo-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-indigo-400 transition-colors">
                                                    <i class="fa-solid fa-calendar-plus"></i>
                                                </button>
                                            @break
                                        @endswitch

                                    </div>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <i
                                                class="fa-solid fa-folder-open text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                                            <p class="text-base font-medium">ไม่พบการยื่นสอบ</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($examSubmissions->hasPages())
                    <div
                        class="p-4 border-t border-gray-100 bg-gray-50 dark:border-gray-700 dark:bg-gray-800 transition-colors duration-200 rounded-b-xl">
                        {{ $examSubmissions->links() }}
                    </div>
                @endif

            </div>
        </div>

        @push('scripts')
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    @if (session('success'))
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ',
                            text: "{{ session('success') }}",
                            confirmButtonText: 'ตกลง',
                            confirmButtonColor: '#f97316'
                        });
                    @endif

                    @if ($errors->any())
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: "{{ $errors->first() }}",
                            confirmButtonText: 'ตกลง',
                            confirmButtonColor: '#f97316'
                        });
                    @endif
                });
            </script>
        @endpush
    </x-app-layout>
