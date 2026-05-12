<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto mt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div class="flex-1 min-w-0 pr-4">
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-file-contract text-orange-500 shrink-0"></i> รายละเอียดการยื่นแก้ไข
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 leading-relaxed truncate">
                    ข้อมูลเอกสารและรายละเอียดการแก้ไขโครงงาน
                </p>
            </div>

            <a href="{{ route('invigilator.revision.index') }}"
                class="shrink-0 whitespace-nowrap px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors flex items-center gap-2 text-sm font-medium">
                <i class="fa-solid fa-arrow-left"></i> ย้อนกลับ
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 md:p-8 transition-colors duration-200">

                    <div class="mb-8 pb-6 border-b border-gray-100 dark:border-gray-700">
                        <div
                            class="text-[11px] font-bold text-orange-500 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-book"></i> ชื่อโครงงานวิจัย
                        </div>
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white leading-tight mb-4">
                            {{ $revision->exam_submission->propose->title ?? '-' }}
                        </h2>
                        <div class="flex items-center gap-2">
                            <span
                                class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400 border border-blue-100 dark:border-blue-500/20">
                                <i class="fa-solid fa-graduation-cap mr-1.5"></i>
                                {{ $revision->exam_submission->exam_type->name ?? 'ไม่ระบุรายวิชา' }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3
                            class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-list-check text-orange-500"></i> รายละเอียดการแก้ไข
                        </h3>
                        <textarea disabled rows="4"
                            class="auto-expand block w-full p-4 text-sm text-gray-800 bg-gray-50 border border-gray-200 rounded-xl dark:bg-gray-900/50 dark:border-gray-700 dark:text-gray-200 resize-none cursor-default custom-scrollbar leading-relaxed">{{ old('edit_detail', $revision->edit_detail) }}</textarea>
                    </div>

                    <div class="border-t border-gray-100 dark:border-gray-700 pt-6">
                        <h3
                            class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-file-pdf text-red-500"></i> ไฟล์เอกสารที่แก้ไข
                        </h3>
                        <div
                            class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 group hover:border-blue-200 dark:hover:border-blue-500/30 transition-all">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset('icons/pdf.png') }}" class="w-10 h-10 object-contain" alt="PDF">
                                <div>
                                    <p class="text-sm font-bold text-gray-800 dark:text-white line-clamp-1">
                                        เอกสารฉบับแก้ไข.pdf</p>
                                    <p class="text-[10px] text-gray-500 uppercase tracking-wider">อัปโหลดโดยนักศึกษา</p>
                                </div>
                            </div>
                            <a href="{{ route('student.revision.download', $revision->id) }}" target="_blank"
                                class="shrink-0 w-full sm:w-auto px-5 py-2.5 bg-blue-600 text-white rounded-xl shadow-sm hover:bg-blue-700 transition-colors flex items-center justify-center gap-2 text-xs font-bold">
                                <i class="fa-solid fa-download"></i> ดาวน์โหลดไฟล์
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <div class="lg:col-span-1">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 relative overflow-hidden transition-colors duration-200">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-indigo-400 to-blue-500"></div>

                    <h3
                        class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-5 mt-2 flex items-center gap-2 border-b border-gray-100 dark:border-gray-700 pb-3">
                        <i class="fa-solid fa-users text-indigo-500"></i> สถานะการอนุมัติ
                    </h3>

                    @if (empty($invigilators))
                        <div class="py-8 text-center">
                            <i class="fa-solid fa-user-slash text-3xl text-gray-300 mb-2"></i>
                            <p class="text-sm text-gray-500">ไม่พบข้อมูลกรรมการ</p>
                        </div>
                    @else
                        <ul class="space-y-4">
                            @foreach ($invigilators as $inv)
                                <li
                                    class="flex flex-col gap-3 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-700 group hover:border-indigo-200 dark:hover:border-indigo-500/30 transition-all">
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div
                                                class="w-10 h-10 rounded-full bg-white dark:bg-gray-800 text-gray-400 flex items-center justify-center shadow-sm shrink-0 border border-gray-200 dark:border-gray-600">
                                                <i class="fa-solid fa-user-tie text-sm"></i>
                                            </div>
                                            <div class="flex flex-col min-w-0">
                                                <span
                                                    class="text-sm font-bold text-gray-800 dark:text-gray-200 truncate">
                                                    {{ $inv['name'] }}
                                                </span>
                                                <div>
                                                    @if ($inv['role'] == 1)
                                                        <span
                                                            class="text-[10px] font-bold text-indigo-500 uppercase tracking-wider">อาจารย์ที่ปรึกษา</span>
                                                    @elseif($inv['role'] == 2)
                                                        <span
                                                            class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">กรรมการคุมสอบ</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="flex items-center justify-end border-t border-gray-200 dark:border-gray-700 pt-3">
                                        @switch($inv['status'])
                                            @case('1')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200 dark:bg-emerald-500/10 dark:border-emerald-500/20 dark:text-emerald-400 whitespace-nowrap">
                                                    <i class="fa-solid fa-circle-check mr-1.5"></i> อนุมัติแล้ว
                                                </span>
                                            @break

                                            @case('2')
                                            @case(null)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-amber-50 text-amber-600 border border-amber-200 dark:bg-amber-500/10 dark:border-amber-500/20 dark:text-amber-400 whitespace-nowrap">
                                                    <i class="fa-solid fa-clock mr-1.5"></i> รอพิจารณา
                                                </span>
                                            @break

                                            @case('3')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-rose-50 text-rose-600 border border-rose-200 dark:bg-rose-500/10 dark:border-rose-500/20 dark:text-rose-400 whitespace-nowrap">
                                                    <i class="fa-solid fa-circle-xmark mr-1.5"></i> ไม่อนุมัติ
                                                </span>
                                            @break
                                        @endswitch
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                // Auto-expand textarea
                document.querySelectorAll('.auto-expand').forEach(textarea => {
                    const adjust = el => {
                        el.style.height = 'auto';
                        const lineHeight = parseInt(window.getComputedStyle(el).lineHeight) || 20;
                        el.style.height = (el.scrollHeight + lineHeight) + 'px';
                    };
                    adjust(textarea);
                    window.addEventListener('resize', () => adjust(textarea));
                });
            });
        </script>
    @endpush
</x-app-layout>
