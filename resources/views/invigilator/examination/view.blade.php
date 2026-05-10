<x-app-layout>
    <div class="p-6 max-w-5xl mx-auto mt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div class="flex-1 min-w-0 pr-4">
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-file-contract text-orange-500 shrink-0"></i> รายละเอียดการยื่นขอสอบ
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 leading-relaxed truncate">
                    ข้อมูลคำร้องและการขอสอบโครงงานวิจัยของนักศึกษา
                </p>
            </div>

            <a href="{{ route('invigilator.examination.index') }}"
                class="shrink-0 whitespace-nowrap px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors flex items-center gap-2 text-sm font-medium">
                <i class="fa-solid fa-arrow-left"></i> ย้อนกลับ
            </a>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-colors duration-200">

            <div class="p-6 md:p-8 border-b border-gray-100 dark:border-gray-700">
                <div
                    class="text-[11px] font-bold text-orange-500 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                    <i class="fa-solid fa-book"></i> ชื่อโครงงาน
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white leading-tight">
                    {{ $examsubmission->propose->title ?? '-' }}
                </h2>
            </div>

            <div class="p-6 md:p-8 grid grid-cols-1 sm:grid-cols-3 gap-6 bg-gray-50/50 dark:bg-gray-900/30">

                <div class="flex flex-col gap-2">
                    <div class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">รายวิชา</div>
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-500/20 text-blue-600 flex items-center justify-center shadow-sm shrink-0">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <span class="font-bold text-gray-800 dark:text-gray-200">
                            {{ $examsubmission->exam_type->name ?? '-' }}
                        </span>
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <div class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">ประเภทโครงงาน</div>
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-500/20 text-purple-600 flex items-center justify-center shadow-sm shrink-0">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <span class="font-bold text-gray-800 dark:text-gray-200">
                            {{ $examsubmission->propose->project_type->name ?? '-' }}
                        </span>
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <div class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">ครั้งที่สอบ</div>
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-500/20 text-amber-600 flex items-center justify-center shadow-sm shrink-0">
                            <i class="fa-solid fa-hashtag"></i>
                        </div>
                        <span class="font-bold text-gray-800 dark:text-gray-200">
                            ครั้งที่ {{ $examsubmission->attempt ?? '-' }}
                        </span>
                    </div>
                </div>

            </div>

            <div class="p-6 md:p-8 border-t border-gray-100 dark:border-gray-700">
                <div
                    class="text-[11px] font-bold text-indigo-500 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                    <i class="fa-solid fa-users"></i> สมาชิกกลุ่มโครงงาน
                </div>

                @if ($members->isEmpty())
                    <div
                        class="text-sm text-gray-500 italic bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700 text-center">
                        ไม่พบสมาชิกในกลุ่มนี้
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ($members as $member)
                            <div
                                class="flex items-center gap-4 p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                                <div
                                    class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-500/20 text-indigo-600 flex items-center justify-center text-sm font-bold shrink-0">
                                    {{ mb_substr($member->student->s_fname ?? 'U', 0, 1, 'UTF-8') }}
                                </div>
                                <div class="flex flex-col overflow-hidden">
                                    <span class="text-sm font-bold text-gray-800 dark:text-gray-200 truncate">
                                        {{ $member->student->s_fname }} {{ $member->student->s_lname }}
                                    </span>
                                    <span class="text-[10px] text-gray-500 uppercase">นักศึกษา</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="p-6 md:p-8 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
                <div class="text-[11px] font-bold text-red-500 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                    <i class="fa-solid fa-file-pdf"></i> เอกสารแนบ
                </div>

                <div
                    class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4 group hover:border-orange-200 dark:hover:border-orange-500/30 transition-all shadow-sm">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('icons/pdf.png') }}" alt="PDF Icon" class="w-10 h-10 object-contain">
                        <div>
                            <p class="text-sm font-bold text-gray-800 dark:text-white line-clamp-1">
                                เอกสารคำร้องขอสอบ.pdf</p>
                            <p class="text-[10px] text-gray-500 uppercase tracking-wider">ไฟล์โครงงาน</p>
                        </div>
                    </div>
                    <a href="{{ route('invigilator.examination.subDownload', ['id' => $examsubmission->id]) }}"
                        class="shrink-0 px-4 py-2.5 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-colors border border-blue-100 dark:border-blue-500/20 flex items-center justify-center gap-2 text-xs font-bold">
                        <i class="fa-solid fa-download"></i> ดาวน์โหลด PDF
                    </a>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // แจ้งเตือนข้อความ Success
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
