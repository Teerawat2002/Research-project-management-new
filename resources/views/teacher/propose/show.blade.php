<x-app-layout>
    <div class="p-4 sm:p-6 lg:p-8 max-w-6xl mx-auto font-sans">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white tracking-tight">
                    Project Details
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    ข้อมูลรายละเอียดของโครงงานวิจัยและการเสนอหัวข้อ
                </p>
            </div>

            <a href="{{ route('teacher.propose.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white shadow-sm transition-all text-sm font-medium focus:outline-none focus:ring-2 focus:ring-orange-500/20">
                <i class="fa-solid fa-arrow-left"></i> ย้อนกลับ
            </a>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6 transition-colors">

            <div class="p-6 sm:p-8 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                <div
                    class="text-xs font-bold text-orange-500 dark:text-orange-400 uppercase tracking-wider mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-book"></i> หัวข้อโครงงานวิจัย
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white leading-snug mb-6">
                    {{ $propose->title }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">ประเภทโครงงาน</div>
                        <div class="text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ $propose->project_type->name ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">ปีการศึกษา</div>
                        <div class="text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ $propose->project_group->academic_year->year ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">อาจารย์ที่ปรึกษา</div>
                        <div
                            class="inline-flex items-center gap-2 text-sm font-medium text-gray-800 dark:text-gray-200">
                            <div
                                class="w-6 h-6 rounded-full bg-orange-100 dark:bg-orange-500/20 text-orange-600 dark:text-orange-400 flex items-center justify-center text-xs">
                                <i class="fa-solid fa-user-tie"></i>
                            </div>
                            {{ $propose->advisor->name ?? 'ยังไม่ระบุ' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 sm:p-8 border-b border-gray-100 dark:border-gray-700">
                <div class="text-sm font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-users text-gray-400 dark:text-gray-500"></i> สมาชิกในกลุ่ม
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse($members as $i => $m)
                        @php $stu = $m->student; @endphp
                        <div
                            class="flex items-center p-4 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl hover:border-orange-200 dark:hover:border-orange-500/50 hover:shadow-sm transition-all group">
                            <div
                                class="w-10 h-10 rounded-full bg-gray-50 dark:bg-gray-900/50 text-gray-400 dark:text-gray-500 group-hover:bg-orange-50 dark:group-hover:bg-orange-500/20 group-hover:text-orange-500 dark:group-hover:text-orange-400 flex items-center justify-center text-sm font-bold mr-4 transition-colors">
                                {{ $i + 1 }}
                            </div>

                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200 truncate">
                                    {{ trim(($stu->s_fname ?? '') . ' ' . ($stu->s_lname ?? '')) ?: '-' }}
                                </h4>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    รหัสนักศึกษา: <span
                                        class="font-medium text-gray-700 dark:text-gray-300">{{ $stu->s_id ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="ml-4">
                                @if (($stu->status ?? 1) == 'active' || ($stu->status ?? 1) == 1)
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-medium bg-orange-500 text-white">
                                        Active
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-600">
                                        Inactive
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div
                            class="col-span-full py-8 text-center text-sm text-gray-400 dark:text-gray-500 bg-gray-50/50 dark:bg-gray-900/30 rounded-xl border border-dashed border-gray-200 dark:border-gray-700">
                            ไม่พบข้อมูลสมาชิก
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-8 bg-white dark:bg-gray-800">

                <div class="flex gap-4">
                    <div class="mt-1">
                        <div
                            class="w-8 h-8 rounded-lg bg-orange-50 dark:bg-orange-500/10 text-orange-500 dark:text-orange-400 flex items-center justify-center">
                            <i class="fa-solid fa-bullseye"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200 mb-2">วัตถุประสงค์ (Objective)
                        </h3>
                        <div
                            class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg border border-gray-100 dark:border-gray-700">
                            {!! nl2br(e($propose->objective ?? 'ไม่มีข้อมูลระบุไว้')) !!}
                        </div>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="mt-1">
                        <div
                            class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-500/10 text-blue-500 dark:text-blue-400 flex items-center justify-center">
                            <i class="fa-solid fa-crop-simple"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200 mb-2">ขอบเขต (Scope)</h3>
                        <div
                            class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg border border-gray-100 dark:border-gray-700">
                            {!! nl2br(e($propose->scope ?? 'ไม่มีข้อมูลระบุไว้')) !!}
                        </div>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="mt-1">
                        <div
                            class="w-8 h-8 rounded-lg bg-purple-50 dark:bg-purple-500/10 text-purple-500 dark:text-purple-400 flex items-center justify-center">
                            <i class="fa-solid fa-screwdriver-wrench"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200 mb-2">เครื่องมือที่ใช้ (Tools)
                        </h3>
                        <div
                            class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg border border-gray-100 dark:border-gray-700">
                            {!! nl2br(e($propose->tools ?? 'ไม่มีข้อมูลระบุไว้')) !!}
                        </div>
                    </div>
                </div>

            </div>
        </div>

        @if (!empty($propose->comments))
            <div
                class="bg-orange-50/50 dark:bg-orange-500/5 rounded-xl border border-orange-100 dark:border-orange-500/20 p-6 flex gap-4 transition-colors">
                <div class="mt-1">
                    <div
                        class="w-8 h-8 rounded-full bg-orange-100 dark:bg-orange-500/20 text-orange-500 dark:text-orange-400 flex items-center justify-center">
                        <i class="fa-solid fa-comment-dots"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200 mb-2">ความเห็นเพิ่มเติมจากอาจารย์</h3>
                    <div class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                        {!! nl2br(e($propose->comments)) !!}
                    </div>
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
