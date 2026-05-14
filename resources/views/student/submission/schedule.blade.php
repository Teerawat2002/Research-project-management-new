<x-app-layout>
    <div class="p-6 max-w-5xl mx-auto mt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div class="flex-1 min-w-0 pr-4">
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-calendar-check text-orange-500 shrink-0"></i> ตารางสอบ
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">
                    โครงงาน: <span class="font-bold text-orange-500">{{ $submission->propose->title }}</span>
                </p>
            </div>

            <div class="flex shrink-0 gap-3">
                <a href="{{ route('student.submission.index') }}"
                    class="shrink-0 whitespace-nowrap px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors flex items-center gap-2 text-sm font-medium">
                    <i class="fa-solid fa-arrow-left"></i> ย้อนกลับ
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 md:p-8 transition-colors duration-200">
                    <h3
                        class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-6 flex items-center gap-2 border-b border-gray-100 dark:border-gray-700 pb-3">
                        <i class="fa-solid fa-clock text-orange-500"></i> ข้อมูลวันและสถานที่สอบ
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div
                            class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-700">
                            <div
                                class="w-12 h-12 bg-white dark:bg-gray-800 rounded-xl flex flex-col items-center justify-center shadow-sm shrink-0 border border-gray-100 dark:border-gray-700">
                                <span
                                    class="text-[10px] font-bold text-orange-500 uppercase">{{ optional($submission->e_date)->format('M') ?? '-' }}</span>
                                <span
                                    class="text-lg font-bold text-gray-800 dark:text-white leading-none">{{ optional($submission->e_date)->format('d') ?? '-' }}</span>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">วันที่สอบ</p>
                                <p class="text-base font-bold text-gray-800 dark:text-white">
                                    @if ($submission->e_date)
                                        {{ $submission->e_date->format('d-m-Y') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div
                            class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-700">
                            <div
                                class="w-12 h-12 bg-white dark:bg-gray-800 rounded-xl flex items-center justify-center shadow-sm shrink-0 border border-gray-100 dark:border-gray-700 text-orange-500">
                                <i class="fa-regular fa-clock text-xl"></i>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">เวลาสอบ</p>
                                <p class="text-base font-bold text-gray-800 dark:text-white">
                                    @if ($submission->e_time)
                                        {{ $submission->e_time->format('H:i') }} น.
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div
                            class="md:col-span-2 flex items-center gap-4 p-4 bg-orange-50 dark:bg-orange-500/5 rounded-2xl border border-orange-100 dark:border-orange-500/20">
                            <div
                                class="w-12 h-12 bg-white dark:bg-gray-800 rounded-xl flex items-center justify-center shadow-sm shrink-0 border border-orange-100 dark:border-gray-700 text-orange-500">
                                <i class="fa-solid fa-door-open text-xl"></i>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold text-orange-400 uppercase tracking-widest">สถานที่ /
                                    ห้องสอบ</p>
                                <p class="text-lg font-bold text-gray-800 dark:text-white">
                                    {{ $submission->e_room ?? 'ยังไม่ระบุสถานที่' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 transition-colors duration-200">
                    <h3
                        class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-5 flex items-center gap-2 border-b border-gray-100 dark:border-gray-700 pb-3">
                        <i class="fa-solid fa-user-shield text-blue-500"></i> คณะกรรมการคุมสอบ
                    </h3>

                    @if ($submission->exam_invi_members->isEmpty())
                        <div class="py-8 text-center">
                            <i class="fa-solid fa-user-slash text-3xl text-gray-300 mb-2"></i>
                            <p class="text-sm text-gray-500">ยังไม่ได้กำหนดกรรมการ</p>
                        </div>
                    @else
                        <ul class="space-y-4">
                            @foreach ($submission->exam_invi_members as $member)
                                @php $adv = $member->invi_group_member->advisor; @endphp
                                <li
                                    class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-700 group hover:border-orange-200 dark:hover:border-orange-500/30 transition-all">
                                    <div
                                        class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-500/20 text-blue-600 flex items-center justify-center shadow-sm shrink-0">
                                        <i class="fa-solid fa-user-tie text-sm"></i>
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <span class="text-sm font-bold text-gray-800 dark:text-gray-200 truncate">
                                            {{ $adv->a_fname }} {{ $adv->a_lname }}
                                        </span>
                                        <span class="text-[10px] text-gray-500 uppercase">อาจารย์ผู้คุมสอบ</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
