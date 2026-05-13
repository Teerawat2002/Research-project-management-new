<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto mt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div class="flex-1 min-w-0 pr-4">
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-orange-500 shrink-0"></i> ประวัติการเสนอหัวข้อ
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 leading-relaxed truncate">
                    โครงงาน: <span class="font-bold text-orange-500">{{ $proposal->title }}</span>
                </p>
            </div>

            <a href="{{ route('student.propose.index') }}"
                class="shrink-0 whitespace-nowrap px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors flex items-center gap-2 text-sm font-medium">
                <i class="fa-solid fa-arrow-left"></i> ย้อนกลับ
            </a>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors duration-200 overflow-hidden relative">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-orange-400 to-yellow-500"></div>

            <div class="overflow-x-auto mt-1.5">
                <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400 transition-colors duration-200">
                    <thead
                        class="text-xs text-gray-500 uppercase bg-gray-50/50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 transition-colors duration-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold w-16 text-center">ลำดับ</th>
                            <th scope="col" class="px-6 py-4 font-semibold w-40 text-center">สถานะ</th>
                            <th scope="col" class="px-6 py-4 font-semibold min-w-[300px]">ความคิดเห็นจากอาจารย์</th>
                            <th scope="col" class="px-6 py-4 font-semibold w-40">วันที่พิจารณา</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @forelse ($history as $record)
                            <tr
                                class="hover:bg-orange-50/50 dark:hover:bg-gray-700/50 transition-colors duration-200 group">

                                <td class="px-6 py-5 text-center font-medium text-gray-500 dark:text-gray-400">
                                    {{ $loop->iteration + ($history->currentPage() - 1) * $history->perPage() }}
                                </td>

                                <td class="px-6 py-5 text-center">
                                    @switch((string)$record->status)
                                        @case('0')
                                        @case('approved')
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200 dark:bg-emerald-500/10 dark:border-emerald-500/20 dark:text-emerald-400 whitespace-nowrap">
                                                <i class="fa-solid fa-circle-check mr-1.5 opacity-70"></i> อนุมัติ
                                            </span>
                                        @break

                                        @case('1')
                                        @case('pending')
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-amber-50 text-amber-600 border border-amber-200 dark:bg-amber-500/10 dark:border-amber-500/20 dark:text-amber-400 whitespace-nowrap">
                                                <i class="fa-solid fa-clock mr-1.5 opacity-70"></i> กำลังดำเนินการ
                                            </span>
                                        @break

                                        @case('2')
                                        @case('rejected')
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-rose-50 text-rose-600 border border-rose-200 dark:bg-rose-500/10 dark:border-rose-500/20 dark:text-rose-400 whitespace-nowrap">
                                                <i class="fa-solid fa-circle-xmark mr-1.5 opacity-70"></i> ไม่อนุมัติ
                                            </span>
                                        @break

                                        @default
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 whitespace-nowrap">
                                                <i class="fa-solid fa-circle-question mr-1.5 opacity-70"></i> ไม่ทราบสถานะ
                                            </span>
                                    @endswitch
                                </td>

                                <td class="px-6 py-5">
                                    <div class="text-sm text-gray-800 dark:text-gray-200 font-medium">
                                        @if (!empty($record->comments))
                                            <div class="flex items-start gap-2">
                                                <i
                                                    class="fa-solid fa-comment-dots text-gray-300 dark:text-gray-600 mt-1 shrink-0"></i>
                                                <span class="leading-relaxed">{{ $record->comments }}</span>
                                            </div>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500 italic">- ไม่มีข้อความ
                                                -</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-800 dark:text-gray-200">
                                            {{ \Carbon\Carbon::parse($record->created_at)->format('d/m/Y') }}
                                        </span>
                                        <span
                                            class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            เวลา {{ \Carbon\Carbon::parse($record->created_at)->format('H:i') }} น.
                                        </span>
                                    </div>
                                </td>

                            </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <i
                                                class="fa-solid fa-clock-rotate-left text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                                            <p class="text-base font-medium">ยังไม่มีประวัติการพิจารณาหัวข้อโครงงานนี้</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($history->hasPages())
                    <div
                        class="p-4 border-t border-gray-100 bg-gray-50 dark:border-gray-700 dark:bg-gray-800 transition-colors duration-200 rounded-b-xl">
                        {{ $history->links() }}
                    </div>
                @endif

            </div>

        </div>
    </x-app-layout>
