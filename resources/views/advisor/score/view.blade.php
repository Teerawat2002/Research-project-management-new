<x-app-layout>
    <div class="p-6 max-w-5xl mx-auto mt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div class="flex-1 min-w-0 pr-4">
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-star text-orange-500 shrink-0"></i> สรุปผลประเมินเกรด
                </h1>
                <div
                    class="text-sm text-gray-500 dark:text-gray-400 mt-2 leading-relaxed flex flex-wrap items-center gap-2">
                    <span>โครงงาน: <span
                            class="font-bold text-orange-500">{{ $submission->propose->title }}</span></span>
                    <span class="hidden md:inline text-gray-300 dark:text-gray-600">|</span>
                    <span
                        class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-100 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20 uppercase tracking-wider">
                        <i class="fa-solid fa-graduation-cap mr-1.5"></i> {{ $submission->exam_type->name }}
                    </span>
                </div>
            </div>

            <a href="{{ route('advisor.submission.index') }}"
                class="shrink-0 whitespace-nowrap px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors flex items-center gap-2 text-sm font-medium">
                <i class="fa-solid fa-arrow-left"></i> ย้อนกลับ
            </a>
        </div>

        @php
            // จัดกลุ่มเกรดตาม invigilator
            $gradesByInvigilator = $grades->groupBy('exam_invi_id');
        @endphp

        <div class="space-y-6">
            @forelse($gradesByInvigilator as $examInviId => $groupedGrades)
                @php
                    // ดึง record ของกรรมการ
                    $invRecord = $groupedGrades->first()->exam_invi_member;
                    $invMember = $invRecord->invi_group_member->advisor;
                    $isAdvisor = $invRecord->role == 1;
                @endphp

                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden transition-colors duration-200">

                    @if ($isAdvisor)
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-indigo-400 to-blue-500">
                        </div>
                    @else
                        <div
                            class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-gray-400 to-slate-500 dark:from-gray-500 dark:to-gray-400">
                        </div>
                    @endif

                    <div
                        class="p-5 md:p-6 border-b border-gray-50 dark:border-gray-700 flex items-center justify-between gap-4 bg-gray-50/30 dark:bg-gray-800/30">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-full {{ $isAdvisor ? 'bg-indigo-50 text-indigo-500 dark:bg-indigo-500/20 dark:text-indigo-400' : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-300' }} flex items-center justify-center shadow-sm shrink-0 border {{ $isAdvisor ? 'border-indigo-100 dark:border-indigo-500/30' : 'border-gray-200 dark:border-gray-600' }}">
                                <i class="fa-solid fa-user-tie text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white leading-tight">
                                    {{ $invMember->a_fname }} {{ $invMember->a_lname }}
                                </h3>
                                <span
                                    class="text-[11px] font-bold uppercase tracking-wider {{ $isAdvisor ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}">
                                    {{ $isAdvisor ? 'อาจารย์ที่ปรึกษา' : 'กรรมการคุมสอบ' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-500 uppercase bg-gray-50/50 dark:bg-gray-700/30 border-b border-gray-100 dark:border-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-4 font-bold w-40 text-center">รหัสนักศึกษา</th>
                                    <th scope="col" class="px-6 py-4 font-bold">ชื่อ - สกุล</th>
                                    <th scope="col" class="px-6 py-4 font-bold text-center w-32">เกรดที่ได้</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                                @foreach ($groupedGrades as $grade)
                                    <tr
                                        class="bg-white dark:bg-gray-800 hover:bg-orange-50/30 dark:hover:bg-gray-700/30 transition-colors group">

                                        <td
                                            class="px-6 py-4 text-center font-medium text-gray-600 dark:text-gray-400 tracking-wider">
                                            {{ $grade->group_member->student->s_id }}
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-full bg-blue-50 text-blue-500 dark:bg-blue-500/10 dark:text-blue-400 flex items-center justify-center shrink-0 border border-blue-100 dark:border-blue-500/20">
                                                    <i class="fa-solid fa-user-graduate text-xs"></i>
                                                </div>
                                                <span class="font-bold text-gray-800 dark:text-gray-200">
                                                    {{ $grade->group_member->student->s_fname }}
                                                    {{ $grade->group_member->student->s_lname }}
                                                </span>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            <span
                                                class="inline-flex items-center justify-center min-w-[3rem] px-3 py-1.5 rounded-lg text-sm font-black bg-orange-100 text-orange-700 border border-orange-200 dark:bg-orange-500/20 dark:text-orange-400 dark:border-orange-500/30 shadow-sm group-hover:scale-110 transition-transform">
                                                {{ $grade->grade }}
                                            </span>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-12 text-center transition-colors duration-200">
                    <div
                        class="w-20 h-20 mx-auto bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4 border border-gray-100 dark:border-gray-600">
                        <i class="fa-solid fa-clipboard-question text-3xl text-gray-400 dark:text-gray-500"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-1">ยังไม่มีข้อมูลเกรด</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        ยังไม่มีการบันทึกประเมินเกรดจากคณะกรรมการท่านใดในขณะนี้</p>
                </div>
            @endforelse
        </div>

    </div>
</x-app-layout>
