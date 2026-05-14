<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto mt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-file-export text-orange-500"></i> การยื่นสอบโครงงาน
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                    จัดการข้อมูลการยื่นขอสอบโครงงานวิจัยของคุณ
                </p>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto mt-2 md:mt-0">
                @if ($proposes->status == 0)
                    {{-- ถ้าอนุมัติหัวข้อแล้ว --}}
                    <a href="{{ route('student.submission.create', $proposes->id) }}"
                        class="inline-flex items-center justify-center px-5 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium rounded-lg shadow-sm transition-colors w-full sm:w-auto">
                        <i class="fa-solid fa-plus mr-2"></i> สร้างการยื่นสอบ
                    </a>
                @else
                    {{-- ถ้าหัวข้อยังไม่ผ่านอนุมัติ --}}
                    <button type="button"
                        onclick="Swal.fire({
                            icon: 'warning',
                            title: 'ยังไม่ผ่านการอนุมัติ',
                            text: 'กรุณายื่นหัวข้อและรอการอนุมัติก่อนทำการยื่นสอบ',
                            confirmButtonText: 'ตกลง',
                            confirmButtonColor: '#f97316'
                        })"
                        class="inline-flex items-center justify-center px-5 py-2.5 bg-gray-100 text-gray-500 border border-gray-200 hover:bg-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 text-sm font-medium rounded-lg shadow-sm transition-colors w-full sm:w-auto cursor-pointer">
                        <i class="fa-solid fa-lock mr-2"></i> สร้างการยื่นสอบ
                    </button>
                @endif
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-200 overflow-hidden">

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400 transition-colors duration-200">
                    <thead
                        class="text-xs text-gray-400 uppercase bg-gray-50/50 dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 transition-colors duration-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold w-16 text-center">ลำดับ</th>
                            <th scope="col" class="px-6 py-4 font-semibold min-w-[250px]">ชื่อโครงงาน</th>
                            <th scope="col" class="px-6 py-4 font-semibold min-w-[160px]">อาจารย์ที่ปรึกษา</th>
                            <th scope="col" class="px-6 py-4 font-semibold min-w-[140px]">ประเภทรายวิชา</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center w-40">สถานะ</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center w-36">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($examsubmissions as $submission)
                            <tr
                                class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors duration-200 group bg-white dark:bg-gray-800">

                                <td class="px-6 py-4 text-center font-medium text-gray-500 dark:text-gray-400">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-6 py-4">
                                    <div
                                        class="font-medium text-gray-900 dark:text-white text-sm group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors line-clamp-2">
                                        {{ $submission->propose->title }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-gray-700 dark:text-gray-200">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-6 h-6 rounded-full bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400 flex items-center justify-center shrink-0">
                                            <i class="fa-solid fa-user-tie text-[10px]"></i>
                                        </div>
                                        <span class="truncate">
                                            {{ $submission->propose->advisor->a_fname }}
                                            {{ $submission->propose->advisor->a_lname }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                        {{ $submission->exam_type->name }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @switch($submission->status)
                                        @case(0)
                                            <span
                                                class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 w-32">
                                                <i class="fa-solid fa-check-double mr-1.5"></i> สอบเสร็จสิ้น
                                            </span>
                                        @break

                                        @case(1)
                                            <span
                                                class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 w-32">
                                                <i class="fa-solid fa-hourglass-half mr-1.5"></i> รอการอนุมัติ
                                            </span>
                                        @break

                                        @case(2)
                                            <span
                                                class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400 w-32">
                                                <i class="fa-solid fa-xmark mr-1.5"></i> ยื่นสอบไม่ผ่าน
                                            </span>
                                        @break

                                        @case(3)
                                            <span
                                                class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 w-32">
                                                <i class="fa-solid fa-calendar-plus mr-1.5"></i> รอจัดตารางสอบ
                                            </span>
                                        @break

                                        @case(4)
                                            <span
                                                class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400 w-32">
                                                <i class="fa-solid fa-spinner fa-spin mr-1.5"></i> กำลังดำเนินการสอบ
                                            </span>
                                        @break

                                        @default
                                            <span
                                                class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 w-32">
                                                <i class="fa-solid fa-circle-question mr-1.5"></i> ยังไม่ได้ยื่นสอบ
                                            </span>
                                    @endswitch
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @switch($submission->status)
                                            @case(0)
                                                <button type="button" title="รายละเอียด"
                                                    onclick="window.location.href='{{ route('student.submission.view', ['submissionId' => $submission->id, 'proposeId' => $submission->propose->id]) }}'"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-blue-50 hover:text-blue-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors">
                                                    <i class="fa-solid fa-eye text-xs"></i>
                                                </button>
                                                <button type="button" title="ประวัติ"
                                                    onclick="window.location.href='{{ route('student.submission.history', $submission->id) }}'"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-purple-50 hover:text-purple-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors">
                                                    <i class="fa-solid fa-clock-rotate-left text-xs"></i>
                                                </button>
                                                <button type="button" title="ดูข้อมูลการสอบ"
                                                    onclick="window.location.href='{{ route('student.submission.schedule', $submission->id) }}'"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors">
                                                    <i class="fa-solid fa-calendar-days text-xs"></i>
                                                </button>
                                            @break

                                            @case(1)
                                                @if (strtolower($submission->exam_type->name ?? '') !== 'aucc')
                                                    <button type="button" title="แก้ไข"
                                                        onclick="window.location.href='{{ route('student.submission.edit', ['submissionId' => $submission->id, 'proposeId' => $submission->propose->id]) }}'"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-orange-50 hover:text-orange-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors">
                                                        <i class="fa-solid fa-pen text-xs"></i>
                                                    </button>
                                                @endif
                                                <button type="button" title="รายละเอียด"
                                                    onclick="window.location.href='{{ route('student.submission.view', ['submissionId' => $submission->id, 'proposeId' => $submission->propose->id]) }}'"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-blue-50 hover:text-blue-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors">
                                                    <i class="fa-solid fa-eye text-xs"></i>
                                                </button>
                                                <button type="button" title="ประวัติ"
                                                    onclick="window.location.href='{{ route('student.submission.history', $submission->id) }}'"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-purple-50 hover:text-purple-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors">
                                                    <i class="fa-solid fa-clock-rotate-left text-xs"></i>
                                                </button>
                                            @break

                                            @case(2)
                                                <button type="button" title="แก้ไข"
                                                    onclick="window.location.href='{{ route('student.submission.edit', ['submissionId' => $submission->id, 'proposeId' => $submission->propose->id]) }}'"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-orange-50 hover:text-orange-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors">
                                                    <i class="fa-solid fa-pen text-xs"></i>
                                                </button>
                                                <button type="button" title="ประวัติ"
                                                    onclick="window.location.href='{{ route('student.submission.history', $submission->id) }}'"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-purple-50 hover:text-purple-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors">
                                                    <i class="fa-solid fa-clock-rotate-left text-xs"></i>
                                                </button>
                                            @break

                                            @case(3)
                                                <button type="button" title="รายละเอียด"
                                                    onclick="window.location.href='{{ route('student.submission.view', ['submissionId' => $submission->id, 'proposeId' => $submission->propose->id]) }}'"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-blue-50 hover:text-blue-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors">
                                                    <i class="fa-solid fa-eye text-xs"></i>
                                                </button>
                                                <button type="button" title="ประวัติ"
                                                    onclick="window.location.href='{{ route('student.submission.history', $submission->id) }}'"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-purple-50 hover:text-purple-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors">
                                                    <i class="fa-solid fa-clock-rotate-left text-xs"></i>
                                                </button>
                                            @break

                                            @case(4)
                                                <button type="button" title="รายละเอียด"
                                                    onclick="window.location.href='{{ route('student.submission.view', ['submissionId' => $submission->id, 'proposeId' => $submission->propose->id]) }}'"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-blue-50 hover:text-blue-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors">
                                                    <i class="fa-solid fa-eye text-xs"></i>
                                                </button>
                                                <button type="button" title="ประวัติ"
                                                    onclick="window.location.href='{{ route('student.submission.history', $submission->id) }}'"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-purple-50 hover:text-purple-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors">
                                                    <i class="fa-solid fa-clock-rotate-left text-xs"></i>
                                                </button>
                                                <button type="button" title="ดูข้อมูลการสอบ"
                                                    onclick="window.location.href='{{ route('student.submission.schedule', $submission->id) }}'"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors">
                                                    <i class="fa-solid fa-calendar-days text-xs"></i>
                                                </button>
                                            @break

                                            @default
                                                <span class="text-gray-400">-</span>
                                        @endswitch
                                    </div>
                                </td>

                            </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <i
                                                class="fa-solid fa-folder-open text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                                            <p class="text-sm font-medium">ไม่พบข้อมูลการยื่นขอสอบโครงงาน</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        @push('scripts')
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    // แจ้งเตือนข้อผิดพลาด (Session Error)
                    @if (session('error'))
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: "{{ session('error') }}",
                            confirmButtonText: 'ตกลง',
                            confirmButtonColor: '#f97316'
                        });
                    @endif

                    // แจ้งเตือนความสำเร็จ
                    @if (session('success'))
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ',
                            text: "{{ session('success') }}",
                            confirmButtonText: 'ตกลง',
                            confirmButtonColor: '#f97316'
                        });
                    @endif
                });
            </script>
        @endpush
    </x-app-layout>
