<x-app-layout>
    <div class="p-6 max-w-4xl mx-auto mt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-file-invoice text-orange-500"></i> รายละเอียดการยื่นขอสอบ
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                    ข้อมูลรายละเอียดและเอกสารประกอบการยื่นสอบโครงงาน
                </p>
            </div>

            <a href="{{ route('advisor.submission.index') }}"
                class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200 flex items-center gap-2 text-sm font-medium">
                <i class="fa-solid fa-arrow-left"></i> ย้อนกลับ
            </a>
        </div>

        @if (session('success'))
            <div
                class="mb-4 p-4 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20">
                <i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}
            </div>
        @endif

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors duration-200 overflow-hidden">

            <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
                <div class="text-[11px] font-bold text-orange-500 uppercase tracking-wider mb-2">ชื่อหัวข้อโครงงานวิจัย
                </div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white leading-tight">
                    {{ $examsubmission->propose->title ?? '-' }}
                </h2>

                <div class="flex flex-wrap gap-2 mt-4">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 border border-blue-200 dark:border-blue-500/30 uppercase">
                        <i class="fa-solid fa-tag mr-1.5"></i> {{ $examsubmission->propose->project_type->name ?? '-' }}
                    </span>
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-orange-50 text-orange-700 dark:bg-orange-500/10 dark:text-orange-400 border border-orange-200 dark:border-orange-500/30 uppercase">
                        <i class="fa-solid fa-book-open mr-1.5"></i> {{ $examsubmission->exam_type->name ?? '-' }}
                    </span>
                </div>
            </div>

            <div class="p-6 md:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <div>
                        <h3
                            class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-users text-gray-400"></i> สมาชิกกลุ่มโครงงาน
                        </h3>
                        @if ($members->isEmpty())
                            <p class="text-sm text-gray-500 italic">ไม่พบสมาชิกในกลุ่มนี้</p>
                        @else
                            <ul class="space-y-3">
                                @foreach ($members as $member)
                                    <li class="flex items-center gap-3 text-sm text-gray-700 dark:text-gray-300">
                                        <div
                                            class="w-8 h-8 rounded-full bg-orange-100 dark:bg-orange-500/20 text-orange-500 flex items-center justify-center text-xs shrink-0 shadow-sm">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-semibold">{{ $member->student->s_fname }}
                                                {{ $member->student->s_lname }}</span>
                                            <span
                                                class="text-[10px] text-gray-500 uppercase tracking-tighter">รหัสนักศึกษา:
                                                {{ $member->student->s_id }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <div class="space-y-6">

                        @if (strtolower($examsubmission->exam_type->name ?? '') !== 'aucc')
                            <div>
                                <h3
                                    class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-2 flex items-center gap-2">
                                    <i class="fa-solid fa-arrow-up-9-1 text-gray-400"></i> ครั้งที่สอบ
                                </h3>
                                <p class="text-base font-semibold text-gray-700 dark:text-gray-300 pl-7">
                                    ครั้งที่ {{ $examsubmission->attempt ?? '1' }}
                                </p>
                            </div>

                            <div>
                                <h3
                                    class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-3 flex items-center gap-2">
                                    <i class="fa-solid fa-paperclip text-gray-400"></i> เอกสารแนบ
                                </h3>
                                <div
                                    class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 flex items-center justify-between group hover:border-orange-200 dark:hover:border-orange-500/30 transition-all">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ asset('icons/pdf.png') }}" class="w-10 h-10 object-contain"
                                            alt="PDF">
                                        <div>
                                            <p class="text-sm font-bold text-gray-800 dark:text-white line-clamp-1">
                                                เอกสารโครงงาน.pdf</p>
                                            <p class="text-[10px] text-gray-500 uppercase tracking-wider">PDF DOCUMENT
                                            </p>
                                        </div>
                                    </div>
                                    <a href="{{ route('student.submission.download', ['id' => $examsubmission->id]) }}"
                                        class="p-2.5 bg-white dark:bg-gray-700 text-blue-600 dark:text-blue-400 rounded-xl shadow-sm hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors border border-gray-100 dark:border-gray-600 flex items-center gap-2 text-xs font-bold">
                                        <i class="fa-solid fa-download"></i> ดาวน์โหลด
                                    </a>
                                </div>
                            </div>
                        @else
                            <div
                                class="p-4 bg-amber-50 dark:bg-amber-500/5 border border-amber-100 dark:border-amber-500/20 rounded-2xl">
                                <p class="text-xs text-amber-700 dark:text-amber-400 leading-relaxed">
                                    <i class="fa-solid fa-circle-info mr-1"></i> รายวิชานี้เป็นประเภท AUCC
                                    ข้อมูลครั้งที่สอบและไฟล์เล่มโครงงานจะถูกจัดการผ่านระบบส่วนกลาง
                                </p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

            <div
                class="px-6 py-4 bg-gray-50/50 dark:bg-gray-900/30 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                <button type="button" onclick="location.href='{{ route('advisor.submission.index') }}'"
                    class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors font-bold text-sm">
                    ย้อนกลับ
                </button>
            </div>

        </div>
    </div>
</x-app-layout>
