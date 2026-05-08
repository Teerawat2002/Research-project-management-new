<x-app-layout>
    <div class="p-6 max-w-5xl mx-auto mt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-orange-500"></i> ประวัติการพิจารณา
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    โครงงาน: <span class="font-bold text-orange-500">{{ $proposal->title }}</span>
                </p>
            </div>

            <a href="{{ route('advisor.propose.index') }}"
                class="shrink-0 whitespace-nowrap px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors flex items-center gap-2 text-sm font-medium">
                <i class="fa-solid fa-arrow-left"></i> ย้อนกลับ
            </a>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 md:p-8 transition-colors duration-200">

            @if ($history->isEmpty())
                <div class="text-center py-16">
                    <div
                        class="w-20 h-20 bg-gray-50 dark:bg-gray-700/50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-inbox text-3xl text-gray-300 dark:text-gray-500"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300 mb-1">ไม่พบประวัติการพิจารณา</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">โครงงานนี้ยังไม่เคยถูกบันทึกผลการพิจารณา</p>
                </div>
            @else
                <div class="relative border-l-2 border-gray-200 dark:border-gray-700 ml-3 md:ml-4 space-y-8 mt-4 mb-4">

                    @foreach ($history as $record)
                        <div class="relative pl-6 md:pl-8 group">

                            @if ($record->status === 'approved')
                                <div
                                    class="absolute -left-[17px] top-1 w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-500/20 border-4 border-white dark:border-gray-800 flex items-center justify-center text-emerald-500 dark:text-emerald-400 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700">
                                    <i class="fa-solid fa-check text-xs"></i>
                                </div>
                            @elseif ($record->status === 'rejected')
                                <div
                                    class="absolute -left-[17px] top-1 w-8 h-8 rounded-full bg-rose-100 dark:bg-rose-500/20 border-4 border-white dark:border-gray-800 flex items-center justify-center text-rose-500 dark:text-rose-400 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700">
                                    <i class="fa-solid fa-xmark text-xs"></i>
                                </div>
                            @else
                                <div
                                    class="absolute -left-[17px] top-1 w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 border-4 border-white dark:border-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700">
                                    <i class="fa-solid fa-minus text-xs"></i>
                                </div>
                            @endif

                            <div
                                class="bg-gray-50 dark:bg-gray-900/50 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 hover:border-orange-200 dark:hover:border-orange-500/30 hover:shadow-md transition-all duration-200">

                                <div
                                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 border-b border-gray-200 dark:border-gray-700 pb-3">
                                    <div>
                                        @if ($record->status === 'approved')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">
                                                <i class="fa-solid fa-circle-check mr-1.5"></i> ได้รับการอนุมัติ
                                            </span>
                                        @elseif ($record->status === 'rejected')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400">
                                                <i class="fa-solid fa-circle-xmark mr-1.5"></i> ไม่อนุมัติ / ส่งแก้ไข
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                                <i class="fa-solid fa-circle-question mr-1.5"></i> ไม่ทราบสถานะ
                                            </span>
                                        @endif
                                    </div>

                                    <div
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5 bg-white dark:bg-gray-800 px-3 py-1 rounded-lg border border-gray-200 dark:border-gray-700">
                                        <i class="fa-regular fa-calendar text-orange-500"></i>
                                        {{ \Carbon\Carbon::parse($record->created_at)->format('d/m/Y • H:i') }} น.
                                    </div>
                                </div>

                                <div class="text-sm text-gray-700 dark:text-gray-300">
                                    @if (!empty($record->comments))
                                        <div class="flex gap-3">
                                            <i
                                                class="fa-solid fa-quote-left text-gray-300 dark:text-gray-600 text-lg mt-0.5"></i>
                                            <div class="leading-relaxed">
                                                {!! nl2br(e($record->comments)) !!}
                                            </div>
                                        </div>
                                    @else
                                        <span class="italic text-gray-400 dark:text-gray-500 flex items-center gap-2">
                                            <i class="fa-regular fa-comment-dots"></i> ไม่มีข้อเสนอแนะเพิ่มเติม
                                        </span>
                                    @endif
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($history->hasPages())
                    <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
                        {{ $history->links() }}
                    </div>
                @endif
            @endif

        </div>
    </div>
</x-app-layout>
