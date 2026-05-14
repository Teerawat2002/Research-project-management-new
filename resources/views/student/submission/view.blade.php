<x-app-layout>
    <div class="p-6 max-w-4xl mx-auto mt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-orange-500"></i> รายละเอียดการยื่นขอสอบ
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                    ข้อมูลและเอกสารประกอบการยื่นขอสอบโครงงานวิจัย
                </p>
            </div>

            <a href="{{ route('student.submission.index') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors text-sm font-medium w-full md:w-auto">
                <i class="fa-solid fa-arrow-left mr-2"></i> ย้อนกลับ
            </a>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-200 overflow-hidden">

            <div class="p-6 md:p-8 space-y-8">

                <div
                    class="bg-orange-50 dark:bg-orange-500/10 border border-orange-100 dark:border-orange-500/20 p-5 rounded-xl">
                    <div class="mb-4">
                        <h3 class="text-sm font-bold text-orange-800 dark:text-orange-400 mb-1 flex items-center gap-2">
                            <i class="fa-solid fa-book-open text-orange-500"></i> ชื่อโครงงาน
                        </h3>
                        <p class="text-base font-semibold text-gray-900 dark:text-white pl-6">
                            {{ $examsubmission->propose->title ?? '-' }}
                        </p>
                    </div>

                    <div class="border-t border-orange-200/50 dark:border-orange-500/20 pt-4">
                        <h3 class="text-sm font-bold text-orange-800 dark:text-orange-400 mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-users text-orange-500"></i> สมาชิกกลุ่มโครงงาน
                        </h3>
                        <div class="flex flex-wrap gap-2 pl-6">
                            @if ($members->isEmpty())
                                <span class="text-sm text-gray-500 dark:text-gray-400">ไม่พบสมาชิกในกลุ่มนี้</span>
                            @else
                                @foreach ($members as $member)
                                    <span
                                        class="inline-flex items-center gap-2 px-3 py-1.5 bg-white dark:bg-gray-800 border border-orange-200 dark:border-orange-500/30 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 shadow-sm">
                                        <div
                                            class="w-6 h-6 rounded-full bg-orange-100 text-orange-500 dark:bg-orange-500/20 dark:text-orange-400 flex items-center justify-center shrink-0">
                                            <i class="fa-solid fa-user text-[10px]"></i>
                                        </div>
                                        {{ $member->student->s_fname }} {{ $member->student->s_lname }}
                                    </span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div
                        class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                        <dt class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                            รายวิชาที่ยื่นสอบ</dt>
                        <dd class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-layer-group text-blue-500"></i>
                            {{ $examsubmission->exam_type->name ?? '-' }}
                        </dd>
                    </div>

                    <div
                        class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                        <dt class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                            ประเภทโครงงาน</dt>
                        <dd class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-laptop-code text-emerald-500"></i>
                            {{ $examsubmission->propose->project_type->name ?? '-' }}
                        </dd>
                    </div>

                    @if (strtolower($examsubmission->exam_type->name ?? '') !== 'aucc')
                        <div
                            class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                            <dt
                                class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                                ยื่นสอบครั้งที่</dt>
                            <dd class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                <i class="fa-solid fa-hashtag text-purple-500"></i>
                                {{ $examsubmission->attempt ?? '-' }}
                            </dd>
                        </div>
                    @endif

                </div>

                @if (strtolower($examsubmission->exam_type->name ?? '') !== 'aucc')
                    <div class="border-t border-gray-100 dark:border-gray-700 pt-6">
                        <h3
                            class="text-sm font-bold text-gray-800 dark:text-gray-200 tracking-wide flex items-center gap-2 mb-4">
                            <i class="fa-solid fa-file-pdf text-rose-500"></i> เอกสารรูปเล่มโครงงาน (PDF)
                        </h3>
                        <div class="flex items-center">
                            <a href="{{ route('student.submission.download', ['id' => $examsubmission->id]) }}"
                                class="inline-flex items-center gap-3 px-5 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-rose-50 dark:hover:bg-rose-500/10 hover:border-rose-200 dark:hover:border-rose-500/30 transition-all shadow-sm group">
                                <img src="{{ asset('icons/pdf.png') }}" alt="PDF Icon"
                                    class="w-8 h-8 group-hover:scale-110 transition-transform">
                                <div class="flex flex-col">
                                    <span
                                        class="text-sm font-bold text-gray-700 dark:text-gray-200 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">ดาวน์โหลดเอกสาร</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">คลิกเพื่อดูไฟล์ PDF
                                        ที่อัปโหลดไว้</span>
                                </div>
                                <i
                                    class="fa-solid fa-download ml-4 text-gray-400 group-hover:text-rose-500 transition-colors"></i>
                            </a>
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // แจ้งเตือนความสำเร็จ (ถ้ามี)
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
