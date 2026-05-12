<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto mt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div class="flex-1 min-w-0 pr-4">
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-clipboard-check text-orange-500 shrink-0"></i> บันทึกคะแนนสอบโครงงาน
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 leading-relaxed truncate">
                    โครงงาน: <span class="font-bold text-orange-500">{{ $examsubmission->propose->title }}</span>
                </p>
            </div>

            <a href="{{ route('advisor.submission.index') }}"
                class="shrink-0 whitespace-nowrap px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors flex items-center gap-2 text-sm font-medium">
                <i class="fa-solid fa-arrow-left"></i> ย้อนกลับ
            </a>
        </div>

        <form action="{{ route('advisor.score.save', ['id' => $examsubmission->id]) }}" method="POST" id="scoringForm">
            @csrf

            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden transition-colors duration-200">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-orange-400 to-yellow-500"></div>

                <div class="overflow-visible mt-1.5 pb-32 relative z-10">
                    <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400">
                        <thead
                            class="text-xs text-gray-500 uppercase bg-gray-50/80 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-bold min-w-[300px]">หัวข้อการประเมิน</th>
                                <th scope="col" class="px-4 py-4 font-bold text-center w-24">คะแนนเต็ม</th>

                                @foreach ($groupMembers as $member)
                                    <th scope="col" class="px-4 py-4 text-center min-w-[140px]">
                                        <div class="flex flex-col items-center justify-center gap-1">
                                            <div
                                                class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-500/20 text-blue-600 flex items-center justify-center shadow-sm">
                                                <i class="fa-solid fa-user-graduate text-xs"></i>
                                            </div>
                                            <span class="font-bold text-gray-800 dark:text-gray-200 truncate w-full"
                                                title="{{ $member->student->s_fname }} {{ $member->student->s_lname }}">
                                                {{ $member->student->s_fname }} <br> {{ $member->student->s_lname }}
                                            </span>
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($mainTopics as $main)
                                @php
                                    // ดูว่ามี SubTopic หรือไม่
                                    $subs = $subTopics->where('mtopic_id', $main->id);
                                    $hasSubs = $subs->isNotEmpty();
                                @endphp

                                {{-- Main topic row --}}
                                <tr
                                    class="bg-gray-50/30 dark:bg-gray-800/30 hover:bg-orange-50/50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-gray-800 dark:text-gray-100 text-base">
                                        {{ $main->name }}
                                    </td>
                                    <td
                                        class="px-4 py-4 text-center font-bold text-orange-600 dark:text-orange-400 text-base">
                                        {{ $main->score }}
                                    </td>

                                    @if (!$hasSubs)
                                        {{-- ถ้าไม่มี SubTopic ให้แสดง input ที่นี่ --}}
                                        @foreach ($groupMembers as $gm)
                                            <td class="px-4 py-4 text-center align-middle">
                                                <div x-data="{
                                                    open: false,
                                                    maxScore: Math.floor({{ $main->score }}),
                                                    setValue(val) {
                                                        $refs.inputField.value = val;
                                                        $refs.inputField.dispatchEvent(new Event('input'));
                                                        this.open = false;
                                                    }
                                                }"
                                                    class="relative flex justify-center items-center"
                                                    @click.outside="open = false">

                                                    <input type="number" step="any" x-ref="inputField"
                                                        name="scores[main][{{ $main->id }}][{{ $gm->id }}]"
                                                        class="w-20 text-center text-sm font-bold text-gray-900 bg-white border border-gray-200 rounded-xl focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white transition-colors py-2 shadow-sm cursor-pointer relative z-10"
                                                        min="0" max="{{ $main->score }}" placeholder="0"
                                                        required autocomplete="off"
                                                        @focus="if(maxScore <= 20) open = true">

                                                    <div x-show="open" style="display: none;" x-transition.opacity
                                                        class="absolute top-full left-1/2 -translate-x-1/2 mt-2 w-[180px] p-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.3)] flex flex-wrap gap-1 justify-center z-[100]">
                                                        <div
                                                            class="w-full text-[10px] text-center text-gray-400 font-bold mb-1.5 uppercase tracking-wider">
                                                            เลือกคะแนน</div>
                                                        <template x-for="i in (maxScore + 1)">
                                                            <button type="button" @click="setValue(maxScore - (i - 1))"
                                                                class="w-8 h-8 flex items-center justify-center text-xs font-bold text-gray-700 dark:text-gray-200 bg-gray-50 dark:bg-gray-700 hover:bg-orange-500 hover:text-white dark:hover:bg-orange-500 rounded-lg transition-all active:scale-90"
                                                                x-text="maxScore - (i - 1)"></button>
                                                        </template>
                                                    </div>
                                                </div>
                                            </td>
                                        @endforeach
                                    @else
                                        {{-- ถ้ามี SubTopic ให้เว้นคอลัมน์นี้หมด --}}
                                        <td colspan="{{ $groupMembers->count() }}" class="px-4 py-4"></td>
                                    @endif
                                </tr>

                                {{-- ถ้ามี SubTopic ให้ลงคะแนนที่ SubTopic แทน --}}
                                @if ($hasSubs)
                                    @foreach ($subs as $sub)
                                        @php
                                            $subsubs = $subSubTopics->where('stopic_id', $sub->id);
                                            $hasSubSubs = $subsubs->isNotEmpty();
                                        @endphp

                                        <tr
                                            class="bg-white dark:bg-gray-800 hover:bg-orange-50/30 dark:hover:bg-gray-700/30 transition-colors">
                                            <td
                                                class="px-6 py-3 pl-10 font-semibold text-gray-700 dark:text-gray-300 text-sm flex items-start gap-2">
                                                <i
                                                    class="fa-solid fa-level-up-alt rotate-90 text-gray-300 dark:text-gray-600 mt-1 shrink-0"></i>
                                                <span>{{ $sub->name }}</span>
                                            </td>
                                            <td
                                                class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-400">
                                                {{ $sub->score }}
                                            </td>

                                            @if (!$hasSubSubs)
                                                @foreach ($groupMembers as $gm)
                                                    <td class="px-4 py-3 text-center align-middle">
                                                        <div x-data="{
                                                            open: false,
                                                            maxScore: Math.floor({{ $sub->score }}),
                                                            setValue(val) {
                                                                $refs.inputField.value = val;
                                                                $refs.inputField.dispatchEvent(new Event('input'));
                                                                this.open = false;
                                                            }
                                                        }"
                                                            class="relative flex justify-center items-center"
                                                            @click.outside="open = false">

                                                            <input type="number" step="any" x-ref="inputField"
                                                                name="scores[sub][{{ $sub->id }}][{{ $gm->id }}]"
                                                                class="w-20 text-center text-sm font-bold text-gray-900 bg-white border border-gray-200 rounded-xl focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white transition-colors py-2 shadow-sm cursor-pointer relative z-10"
                                                                min="0" max="{{ $sub->score }}"
                                                                placeholder="0" required autocomplete="off"
                                                                @focus="if(maxScore <= 20) open = true">

                                                            <div x-show="open" style="display: none;"
                                                                x-transition.opacity
                                                                class="absolute top-full left-1/2 -translate-x-1/2 mt-2 w-[180px] p-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.3)] flex flex-wrap gap-1 justify-center z-[100]">
                                                                <div
                                                                    class="w-full text-[10px] text-center text-gray-400 font-bold mb-1.5 uppercase tracking-wider">
                                                                    เลือกคะแนน</div>
                                                                <template x-for="i in (maxScore + 1)">
                                                                    <button type="button"
                                                                        @click="setValue(maxScore - (i - 1))"
                                                                        class="w-8 h-8 flex items-center justify-center text-xs font-bold text-gray-700 dark:text-gray-200 bg-gray-50 dark:bg-gray-700 hover:bg-orange-500 hover:text-white dark:hover:bg-orange-500 rounded-lg transition-all active:scale-90"
                                                                        x-text="maxScore - (i - 1)"></button>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </td>
                                                @endforeach
                                            @else
                                                <td colspan="{{ $groupMembers->count() }}" class="px-4 py-3"></td>
                                            @endif
                                        </tr>

                                        {{-- Sub-Sub Topics --}}
                                        @if ($hasSubSubs)
                                            @foreach ($subsubs as $ss)
                                                <tr
                                                    class="bg-white dark:bg-gray-800 hover:bg-orange-50/30 dark:hover:bg-gray-700/30 transition-colors">
                                                    <td
                                                        class="px-6 py-3 pl-16 text-sm text-gray-600 dark:text-gray-400 flex items-start gap-2">
                                                        <i
                                                            class="fa-solid fa-circle text-[6px] text-gray-300 dark:text-gray-600 mt-2 shrink-0"></i>
                                                        <span>{{ $ss->name }}</span>
                                                    </td>
                                                    <td
                                                        class="px-4 py-3 text-center font-medium text-gray-500 dark:text-gray-500">
                                                        {{ $ss->score }}
                                                    </td>
                                                    @foreach ($groupMembers as $gm)
                                                        <td class="px-4 py-3 text-center align-middle">

                                                            <div x-data="{
                                                                open: false,
                                                                maxScore: Math.floor({{ $ss->score }}),
                                                                setValue(val) {
                                                                    $refs.inputField.value = val;
                                                                    $refs.inputField.dispatchEvent(new Event('input'));
                                                                    this.open = false;
                                                                }
                                                            }"
                                                                class="relative flex justify-center items-center"
                                                                @click.outside="open = false">

                                                                <input type="number" step="any" x-ref="inputField"
                                                                    name="scores[subsub][{{ $ss->id }}][{{ $gm->id }}]"
                                                                    class="w-20 text-center text-sm font-bold text-gray-900 bg-white border border-gray-200 rounded-xl focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white transition-colors py-1.5 shadow-sm cursor-pointer relative z-10"
                                                                    min="0" max="{{ $ss->score }}"
                                                                    placeholder="0" required autocomplete="off"
                                                                    @focus="if(maxScore <= 20) open = true">

                                                                <div x-show="open" style="display: none;"
                                                                    x-transition.opacity
                                                                    class="absolute top-full left-1/2 -translate-x-1/2 mt-2 w-[180px] p-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.3)] flex flex-wrap gap-1 justify-center z-[100]">
                                                                    <div
                                                                        class="w-full text-[10px] text-center text-gray-400 font-bold mb-1.5 uppercase tracking-wider">
                                                                        เลือกคะแนน</div>
                                                                    <template x-for="i in (maxScore + 1)">
                                                                        <button type="button"
                                                                            @click="setValue(maxScore - (i - 1))"
                                                                            class="w-8 h-8 flex items-center justify-center text-xs font-bold text-gray-700 dark:text-gray-200 bg-gray-50 dark:bg-gray-700 hover:bg-orange-500 hover:text-white dark:hover:bg-orange-500 rounded-lg transition-all active:scale-90"
                                                                            x-text="maxScore - (i - 1)"></button>
                                                                    </template>
                                                                </div>
                                                            </div>

                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div
                    class="px-6 py-4 bg-gray-50/80 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3 items-center">
                    <button type="button" onclick="window.location.href='{{ route('advisor.submission.index') }}'"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">
                        ยกเลิก
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-bold text-white bg-orange-500 rounded-xl hover:bg-orange-600 shadow-sm flex items-center gap-2 transition-colors">
                        <i class="fa-solid fa-save"></i> บันทึกคะแนน
                    </button>
                </div>

            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Real-time Max Value Validation
                document.querySelectorAll('input[type="number"][max]').forEach(input => {
                    input.addEventListener('input', () => {
                        const mx = parseFloat(input.max);
                        if (input.value !== '' && parseFloat(input.value) > mx) {
                            input.value = mx;

                            Swal.fire({
                                icon: 'warning',
                                title: 'คะแนนเกินกำหนด',
                                text: `หัวข้อนี้ให้คะแนนได้สูงสุด ${mx} คะแนน`,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }
                    });
                });

                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: "{{ session('success') }}",
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#f97316'
                    });
                @endif

                @if ($errors->any())
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: "{{ $errors->first() }}",
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#f97316'
                    });
                @endif
            });
        </script>
    @endpush
</x-app-layout>
