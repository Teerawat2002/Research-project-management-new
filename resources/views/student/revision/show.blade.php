<x-app-layout>
    <div class="p-6 max-w-4xl mx-auto mt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-file-invoice text-orange-500"></i> รายละเอียดการยื่นแก้ไข
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                    ข้อมูลและสถานะการตรวจสอบการแก้ไขโครงงานของคุณ
                </p>
            </div>

            <a href="{{ route('student.revision.index') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors text-sm font-medium w-full md:w-auto">
                <i class="fa-solid fa-arrow-left mr-2"></i> ย้อนกลับ
            </a>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-200 overflow-hidden">

            <div class="p-6 md:p-8 space-y-8">

                <div
                    class="bg-orange-50 dark:bg-orange-500/10 border border-orange-100 dark:border-orange-500/20 p-5 rounded-xl">
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="flex-1">
                            <h3
                                class="text-sm font-bold text-orange-800 dark:text-orange-400 mb-1 flex items-center gap-2">
                                <i class="fa-solid fa-book-open"></i> ชื่อโครงงาน
                            </h3>
                            <p class="text-base font-semibold text-gray-900 dark:text-white pl-6">
                                {{ $revision->exam_submission->propose->title ?? '-' }}
                            </p>
                        </div>
                        <div class="md:w-1/3">
                            <h3
                                class="text-sm font-bold text-orange-800 dark:text-orange-400 mb-1 flex items-center gap-2">
                                <i class="fa-solid fa-layer-group"></i> รายวิชา
                            </h3>
                            <p class="text-sm font-medium text-gray-900 dark:text-white pl-6">
                                {{ $revision->exam_submission->exam_type->name ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">

                    <div
                        class="bg-gray-50 dark:bg-gray-900/50 p-5 rounded-xl border border-gray-200 dark:border-gray-700">
                        <h3
                            class="text-sm font-bold text-gray-800 dark:text-gray-200 tracking-wide flex items-center gap-2 mb-3">
                            <i class="fa-regular fa-file-pdf text-rose-500"></i> ไฟล์เอกสารที่แก้ไขแล้ว (PDF)
                        </h3>
                        <a href="{{ route('student.revision.download', $revision->id) }}" target="_blank"
                            class="inline-flex items-center gap-3 px-5 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl hover:bg-rose-50 dark:hover:bg-rose-500/10 hover:border-rose-200 dark:hover:border-rose-500/30 transition-all shadow-sm group w-full md:w-auto">
                            <img src="{{ asset('icons/pdf.png') }}" alt="PDF Icon"
                                class="w-8 h-8 group-hover:scale-110 transition-transform">
                            <div class="flex flex-col">
                                <span
                                    class="text-sm font-bold text-gray-700 dark:text-gray-200 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">ดาวน์โหลดไฟล์เอกสาร</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">คลิกเพื่อดูไฟล์ PDF
                                    ที่คุณแนบไว้</span>
                            </div>
                            <i
                                class="fa-solid fa-download ml-4 text-gray-400 group-hover:text-rose-500 transition-colors ml-auto md:ml-4"></i>
                        </a>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-bold text-gray-800 dark:text-gray-200 tracking-wide flex items-center gap-2 mb-2">
                            <i class="fa-solid fa-list-check text-blue-500"></i> รายละเอียดการแก้ไข
                        </label>
                        <div class="w-full px-4 py-3 text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-lg dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 min-h-[100px] whitespace-pre-wrap leading-relaxed">{{ $revision->edit_detail ?? 'ไม่มีข้อมูลรายละเอียดการแก้ไข' }}</div>
                    </div>

                </div>

                <div class="border-t border-gray-100 dark:border-gray-700 pt-6">
                    <label
                        class="block text-sm font-bold text-gray-800 dark:text-gray-200 tracking-wide flex items-center gap-2 mb-3">
                        <i class="fa-solid fa-user-check text-emerald-500"></i> สถานะการอนุมัติของคณะกรรมการ
                    </label>
                    <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-xl">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-600 uppercase bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 font-bold">กรรมการ</th>
                                    <th scope="col" class="px-6 py-3 font-bold text-center w-40">สถานะ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse ($invigilators as $inv)
                                    <tr
                                        class="bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <span
                                                    class="font-medium text-gray-900 dark:text-white">{{ $inv['name'] }}</span>
                                                @if ($inv['role'] == 1)
                                                    <span
                                                        class="bg-blue-50 text-blue-600 border border-blue-200 text-[10px] font-bold px-2 py-0.5 rounded-full dark:bg-blue-500/10 dark:border-blue-500/20 dark:text-blue-400">อาจารย์ที่ปรึกษา</span>
                                                @elseif($inv['role'] == 2)
                                                    <span
                                                        class="bg-gray-100 text-gray-600 border border-gray-200 text-[10px] font-bold px-2 py-0.5 rounded-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">กรรมการ</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @switch($inv['status'])
                                                @case(null)
                                                @case('2')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-600 border border-amber-200 dark:bg-amber-500/10 dark:border-amber-500/20 dark:text-amber-400 whitespace-nowrap">
                                                        <i class="fa-solid fa-hourglass-half mr-1.5 opacity-70"></i> รออนุมัติ
                                                    </span>
                                                @break

                                                @case('1')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200 dark:bg-emerald-500/10 dark:border-emerald-500/20 dark:text-emerald-400 whitespace-nowrap">
                                                        <i class="fa-solid fa-circle-check mr-1.5 opacity-70"></i> อนุมัติแล้ว
                                                    </span>
                                                @break

                                                @case('3')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-600 border border-rose-200 dark:bg-rose-500/10 dark:border-rose-500/20 dark:text-rose-400 whitespace-nowrap">
                                                        <i class="fa-solid fa-circle-xmark mr-1.5 opacity-70"></i> ไม่อนุมัติ
                                                    </span>
                                                @break

                                                @default
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 whitespace-nowrap">
                                                        ไม่ทราบสถานะ
                                                    </span>
                                            @endswitch
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="px-6 py-8 text-center text-gray-500">
                                                ไม่พบข้อมูลกรรมการ</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </x-app-layout>
