<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto mt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div class="flex-1 min-w-0 pr-4">
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-file-lines text-orange-500 shrink-0"></i> รายละเอียดการยื่นแก้ไข
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 leading-relaxed truncate">
                    โครงงาน: <span
                        class="font-bold text-orange-500">{{ $revision->exam_submission->propose->title }}</span>
                </p>
            </div>

            <a href="javascript:void(0);" onclick="window.history.back();"
                class="shrink-0 whitespace-nowrap px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors flex items-center gap-2 text-sm font-medium">
                <i class="fa-solid fa-arrow-left"></i> ย้อนกลับ
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">

                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 md:p-8 transition-colors duration-200">

                    <div class="mb-6">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 border border-blue-200 dark:border-blue-500/30 uppercase mb-4">
                            <i class="fa-solid fa-tag mr-1.5"></i> รายวิชา:
                            {{ $revision->exam_submission->exam_type->name }}
                        </span>
                    </div>

                    <div class="mb-8">
                        <h3
                            class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-file-pdf text-red-500"></i> ไฟล์เอกสารที่แก้ไข
                        </h3>
                        <div
                            class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4 group hover:border-orange-200 dark:hover:border-orange-500/30 transition-all">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset('icons/pdf.png') }}" class="w-10 h-10 object-contain" alt="PDF">
                                <div>
                                    <p class="text-sm font-bold text-gray-800 dark:text-white line-clamp-1">
                                        เอกสารฉบับแก้ไข.pdf</p>
                                    <p class="text-[10px] text-gray-500 uppercase tracking-wider">PDF DOCUMENT</p>
                                </div>
                            </div>
                            <a href="{{ route('student.revision.download', $revision->id) }}" target="_blank"
                                class="shrink-0 p-2.5 bg-white dark:bg-gray-700 text-blue-600 dark:text-blue-400 rounded-xl shadow-sm hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors border border-gray-100 dark:border-gray-600 flex items-center justify-center gap-2 text-xs font-bold">
                                <i class="fa-solid fa-download"></i> ดาวน์โหลด
                            </a>
                        </div>
                    </div>

                    <div>
                        <h3
                            class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-list-check text-blue-500"></i> รายละเอียดการแก้ไข (จากนักศึกษา)
                        </h3>
                        <textarea disabled rows="5"
                            class="auto-expand block w-full p-4 text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-2xl dark:bg-gray-900/50 dark:border-gray-700 dark:text-gray-300 resize-none cursor-not-allowed custom-scrollbar"
                            placeholder="ไม่มีรายละเอียดเพิ่มเติม">{{ old('edit_detail', $revision->edit_detail) }}</textarea>
                    </div>

                </div>

            </div>

            <div class="lg:col-span-1">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-colors duration-200 h-full">
                    <div class="p-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
                        <h3
                            class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-users-viewfinder text-indigo-500"></i> สถานะการพิจารณา
                        </h3>
                    </div>

                    <div class="p-5">
                        <ul class="space-y-4">
                            @forelse ($invigilators as $inv)
                                <li
                                    class="flex flex-col gap-2 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-700 transition-all">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex flex-col min-w-0">
                                            <span
                                                class="font-bold text-gray-900 dark:text-white text-sm truncate">{{ $inv['name'] }}</span>
                                            <div class="mt-1">
                                                @if ($inv['role'] == 1)
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 border border-blue-200 dark:border-blue-500/30">
                                                        อาจารย์ที่ปรึกษา
                                                    </span>
                                                @elseif($inv['role'] == 2)
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                                        กรรมการคุมสอบ
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-1 flex items-center">
                                        @switch($inv['status'])
                                            @case('1')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-50 text-emerald-600 border border-emerald-200 dark:bg-emerald-500/10 dark:border-emerald-500/20 dark:text-emerald-400 w-full justify-center">
                                                    <i class="fa-solid fa-circle-check mr-1.5"></i> อนุมัติแล้ว
                                                </span>
                                            @break

                                            @case('2')
                                            @case(null)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-amber-50 text-amber-600 border border-amber-200 dark:bg-amber-500/10 dark:border-amber-500/20 dark:text-amber-400 w-full justify-center">
                                                    <i class="fa-solid fa-clock mr-1.5"></i> รอพิจารณา
                                                </span>
                                            @break

                                            @case('3')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-rose-50 text-rose-600 border border-rose-200 dark:bg-rose-500/10 dark:border-rose-500/20 dark:text-rose-400 w-full justify-center">
                                                    <i class="fa-solid fa-circle-xmark mr-1.5"></i> ไม่อนุมัติ
                                                </span>
                                            @break

                                            @default
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 w-full justify-center">
                                                    ไม่ทราบสถานะ
                                                </span>
                                        @endswitch
                                    </div>
                                </li>
                                @empty
                                    <li class="text-center text-sm text-gray-500 dark:text-gray-400 py-4">
                                        ไม่พบข้อมูลคณะกรรมการ</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        @push('scripts')
            <script>
                // สคริปต์ปรับขนาด textarea อัตโนมัติ
                document.addEventListener("DOMContentLoaded", () => {
                    document.querySelectorAll('.auto-expand').forEach(textarea => {
                        const adjust = el => {
                            el.style.height = 'auto';
                            const lineHeight = parseInt(window.getComputedStyle(el).lineHeight) || 20;
                            el.style.height = (el.scrollHeight + lineHeight) + 'px';
                        };
                        // ดีเลย์เล็กน้อยเพื่อให้ UI เรนเดอร์เสร็จก่อนคำนวณความสูง
                        setTimeout(() => adjust(textarea), 100);
                        window.addEventListener('resize', () => adjust(textarea));
                    });
                });
            </script>
        @endpush
    </x-app-layout>
