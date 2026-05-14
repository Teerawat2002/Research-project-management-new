<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto mt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-file-signature text-orange-500"></i> รายการยื่นแก้ไขโครงงาน
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                    จัดการข้อมูลการยื่นแบบรับรองการแก้ไขโครงงานวิจัยของคุณ
                </p>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto mt-2 md:mt-0">
                @if ($submission->status == 0)
                    <a href="{{ route('student.revision.create', $submission->id) }}"
                        class="inline-flex items-center justify-center px-5 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium rounded-lg shadow-sm transition-colors w-full sm:w-auto">
                        <i class="fa-solid fa-plus mr-2"></i> สร้างรายการยื่นแก้ไข
                    </a>
                @else
                    <button type="button"
                        onclick="Swal.fire({
                            icon: 'warning',
                            title: 'ไม่สามารถสร้างได้',
                            text: 'กรุณายื่นหัวข้อหรือทำการสอบให้เสร็จสิ้นก่อน',
                            confirmButtonText: 'ตกลง',
                            confirmButtonColor: '#f97316'
                        })"
                        class="inline-flex items-center justify-center px-5 py-2.5 bg-gray-100 text-gray-500 border border-gray-200 hover:bg-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 text-sm font-medium rounded-lg shadow-sm transition-colors w-full sm:w-auto cursor-pointer">
                        <i class="fa-solid fa-lock mr-2"></i> สร้างรายการยื่นแก้ไข
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
                            <th scope="col" class="px-6 py-4 font-semibold min-w-[250px]">ชื่อโครงงาน</th>
                            <th scope="col" class="px-6 py-4 font-semibold min-w-[150px]">รายวิชา</th>
                            <th scope="col" class="px-6 py-4 font-semibold min-w-[200px]">สถานะการอนุมัติ</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center w-32">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($revisions as $revision)
                            <tr
                                class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors duration-200 group bg-white dark:bg-gray-800">

                                <td class="px-6 py-4">
                                    <div
                                        class="font-medium text-gray-900 dark:text-white text-sm group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors line-clamp-2">
                                        {{ $revision->exam_submission->propose->title ?? '–' }}
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                        {{ $revision->exam_submission->exam_type->name ?? '–' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    @if ($revision->status === '0')
                                        <span
                                            class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 w-28">
                                            <i class="fa-solid fa-check-double mr-1.5"></i> อนุมัติแล้ว
                                        </span>
                                    @else
                                        @php
                                            $approved = $revision->approve_count;
                                            $rejected = $revision->rejected_count;
                                            $waiting = $total_invigilator - ($approved + $rejected);
                                        @endphp
                                        <div class="flex flex-wrap gap-1.5">
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-600 border border-amber-200 dark:bg-amber-500/10 dark:border-amber-500/20 dark:text-amber-400">
                                                รอ: {{ $waiting ?? '0' }}
                                            </span>
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200 dark:bg-emerald-500/10 dark:border-emerald-500/20 dark:text-emerald-400">
                                                ผ่าน: {{ $approved ?? '0' }}
                                            </span>
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-600 border border-rose-200 dark:bg-rose-500/10 dark:border-rose-500/20 dark:text-rose-400">
                                                แก้: {{ $rejected ?? '0' }}
                                            </span>
                                        </div>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @if ($revision->status === '0')
                                            <button type="button" title="รายละเอียด"
                                                onclick="window.location.href='{{ route('student.revision.show', ['revisionId' => $revision->id]) }}'"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-400 hover:bg-blue-50 hover:text-blue-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-500 dark:hover:bg-gray-700 transition-colors">
                                                <i class="fa-solid fa-eye text-xs"></i>
                                            </button>
                                        @else
                                            <button type="button" title="แก้ไข"
                                                onclick="window.location.href='{{ route('student.revision.edit', ['revision' => $revision->id]) }}'"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-400 hover:bg-orange-50 hover:text-orange-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-500 dark:hover:bg-gray-700 transition-colors">
                                                <i class="fa-solid fa-pen text-xs"></i>
                                            </button>
                                            <button type="button" title="รายละเอียด"
                                                onclick="window.location.href='{{ route('student.revision.show', ['revisionId' => $revision->id]) }}'"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-400 hover:bg-blue-50 hover:text-blue-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-500 dark:hover:bg-gray-700 transition-colors">
                                                <i class="fa-solid fa-eye text-xs"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <i
                                            class="fa-solid fa-folder-open text-5xl text-gray-300 dark:text-gray-600 mb-3"></i>
                                        <p class="text-sm font-medium">ยังไม่มีรายการยื่นแก้ไข</p>
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

                // แจ้งเตือนข้อผิดพลาด (Validation/Error)
                @if ($errors->any())
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: "{{ $errors->first() }}",
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#f97316'
                    });
                @endif

                @if (session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: "{{ session('error') }}",
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#f97316'
                    });
                @endif
            });
        </script>
    @endpush
</x-app-layout>
