<x-app-layout>
    <div class="p-6 max-w-5xl mx-auto mt-4">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200">
                    Project Details
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                    ข้อมูลรายละเอียดของโครงงานวิจัยและการเสนอหัวข้อ
                </p>
            </div>

            <a href="{{ route('teacher.propose.proposeIndex') }}"
                class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200 flex items-center gap-2 text-sm font-medium">
                <i class="fa-solid fa-arrow-left"></i> ย้อนกลับ
            </a>
        </div>

        <!-- Main Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors duration-200 overflow-hidden">

            <!-- โซนข้อมูลทั่วไป (ด้านบน) -->
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <!-- ฝั่งซ้าย: ข้อมูลหลัก -->
                    <div class="space-y-6">
                        <div>
                            <div
                                class="text-[11px] font-bold text-orange-500 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                                <i class="fa-solid fa-book text-orange-400"></i> ชื่อหัวข้อโครงงานวิจัย
                            </div>
                            <div class="text-lg font-semibold text-gray-900 dark:text-white leading-snug">
                                {{ $propose->title }}
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div
                                    class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1">
                                    ประเภทโครงงาน</div>
                                <div class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                    {{ $propose->project_type->name ?? '-' }}
                                </div>
                            </div>
                            <div>
                                <div
                                    class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1">
                                    ปีการศึกษา</div>
                                <div class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                    {{ $propose->project_group->academic_year->year ?? '-' }}
                                </div>
                            </div>
                        </div>

                        <div>
                            <div
                                class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1 flex items-center gap-1">
                                <i class="fa-solid fa-user-tie text-gray-400"></i> อาจารย์ที่ปรึกษา
                            </div>
                            <div
                                class="text-sm font-medium text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 inline-flex shadow-sm mt-1">
                                {{ $propose->advisor->name ?? 'ยังไม่ระบุ' }}
                            </div>
                        </div>
                    </div>

                    <!-- ฝั่งขวา: สมาชิกในกลุ่ม -->
                    <div>
                        <div
                            class="text-[11px] font-bold text-orange-500 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                            <i class="fa-solid fa-users text-orange-400"></i> สมาชิกในกลุ่ม
                        </div>
                        <div
                            class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden bg-white dark:bg-gray-800 shadow-sm">
                            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th
                                            class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 w-12 text-center">
                                            #</th>
                                        <th
                                            class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400">
                                            รหัสนักศึกษา</th>
                                        <th
                                            class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400">
                                            ชื่อ - สกุล</th>
                                        <th
                                            class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 w-20">
                                            สถานะ</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                                    @forelse($members as $i => $m)
                                        @php $stu = $m->student; @endphp
                                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors">
                                            <td
                                                class="px-4 py-2.5 text-xs text-gray-500 dark:text-gray-400 text-center">
                                                {{ $i + 1 }}</td>
                                            <td class="px-4 py-2.5 text-sm text-gray-900 dark:text-white font-medium">
                                                {{ $stu->s_id ?? '-' }}</td>
                                            <td class="px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300">
                                                {{ trim(($stu->s_fname ?? '') . ' ' . ($stu->s_lname ?? '')) ?: '-' }}
                                            </td>
                                            <td class="px-4 py-2.5 text-center">
                                                <span
                                                    class="inline-flex items-center justify-center px-2 py-0.5 text-[10px] font-bold rounded-full {{ $stu->status ?? 1 ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                                                    {{ $stu->status ?? 1 ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4"
                                                class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                                ไม่พบสมาชิกในกลุ่มนี้
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

            <!-- โซนรายละเอียดเนื้อหา (ด้านล่าง) -->
            <div class="p-6 space-y-8">

                <!-- วัตถุประสงค์ -->
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-orange-100 dark:bg-orange-500/20 text-orange-500 flex items-center justify-center">
                            <i class="fa-solid fa-bullseye"></i>
                        </div>
                        <h3 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider">
                            วัตถุประสงค์ (Objective)</h3>
                    </div>
                    <div
                        class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700 text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                        {!! nl2br(e($propose->objective ?? 'ไม่มีข้อมูลระบุไว้')) !!}
                    </div>
                </div>

                <!-- ขอบเขต -->
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 text-blue-500 flex items-center justify-center">
                            <i class="fa-solid fa-crop-simple"></i>
                        </div>
                        <h3 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider">ขอบเขต
                            (Scope)</h3>
                    </div>
                    <div
                        class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700 text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                        {!! nl2br(e($propose->scope ?? 'ไม่มีข้อมูลระบุไว้')) !!}
                    </div>
                </div>

                <!-- เครื่องมือที่ใช้ -->
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-500/20 text-purple-500 flex items-center justify-center">
                            <i class="fa-solid fa-screwdriver-wrench"></i>
                        </div>
                        <h3 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider">
                            เครื่องมือที่ใช้ (Tools)</h3>
                    </div>
                    <div
                        class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700 text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                        {!! nl2br(e($propose->tools ?? 'ไม่มีข้อมูลระบุไว้')) !!}
                    </div>
                </div>

                <!-- ความเห็นเพิ่มเติม -->
                @if (!empty($propose->comments))
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 flex items-center justify-center">
                                <i class="fa-solid fa-comment-dots"></i>
                            </div>
                            <h3 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider">
                                ความเห็นเพิ่มเติม (Comments)</h3>
                        </div>
                        <div
                            class="bg-yellow-50 dark:bg-yellow-500/10 p-4 rounded-xl border border-yellow-200 dark:border-yellow-500/20 text-sm text-yellow-800 dark:text-yellow-200 leading-relaxed">
                            {!! nl2br(e($propose->comments)) !!}
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
