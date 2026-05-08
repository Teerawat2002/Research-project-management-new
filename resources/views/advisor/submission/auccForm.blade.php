<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto mt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-star text-orange-500"></i> ให้คะแนนประเมิน AUCC
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    บันทึกผลการประเมินโครงงานสำหรับนักศึกษาในกลุ่ม (เฉพาะประเภท AUCC)
                </p>
            </div>
            <a href="{{ route('advisor.submission.index') }}"
                class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors flex items-center gap-2 text-sm font-medium">
                <i class="fa-solid fa-arrow-left"></i> ย้อนกลับ
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 lg:p-8">

                    <div class="mb-8 pb-6 border-b border-gray-100 dark:border-gray-700">
                        <div
                            class="text-[11px] font-bold text-orange-500 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-book"></i> ชื่อหัวข้อโครงงานวิจัย
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white leading-tight">
                            {{ optional($submission)->propose->title ?: 'ไม่ระบุชื่อโครงงาน' }}
                        </h2>
                    </div>

                    <div>
                        <h3
                            class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-users text-orange-500"></i> สมาชิกในกลุ่ม
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @forelse ($groupMembers as $member)
                                <div
                                    class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-700">
                                    <div
                                        class="w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-500/20 text-orange-500 flex items-center justify-center text-sm font-bold shrink-0 shadow-sm">
                                        {{ mb_substr($member->student->name ?? 'U', 0, 1, 'UTF-8') }}
                                    </div>
                                    <div class="flex flex-col overflow-hidden">
                                        <span class="font-bold text-gray-800 dark:text-gray-200 text-sm truncate">
                                            {{ $member->student->name ?? 'Unknown Student' }}
                                        </span>
                                        <span class="text-[10px] text-gray-500 uppercase tracking-tighter mt-0.5">
                                            รหัสนักศึกษา: {{ $member->student->s_id ?? '-' }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 italic text-sm col-span-2">ไม่มีสมาชิกในกลุ่มนี้</p>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>

            <div class="lg:col-span-1">
                <form method="POST" action="{{ route('advisor.submission.saveAucc', $submission->id) }}"
                    class="sticky top-24">
                    @csrf

                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-orange-200 dark:border-orange-500/30 p-6 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-orange-400 to-yellow-500">
                        </div>

                        <h3
                            class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider mb-5 mt-2 flex items-center gap-2">
                            <i class="fa-solid fa-star text-yellow-400"></i> ให้เกรดประเมิน
                        </h3>

                        <div class="space-y-4 mb-6">
                            @foreach ($groupMembers as $member)
                                <div
                                    class="bg-gray-50 dark:bg-gray-900/50 p-3 rounded-xl border border-gray-100 dark:border-gray-700">
                                    <label
                                        class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 truncate">
                                        {{ $member->student->name }}
                                    </label>
                                    <div class="relative">
                                        <select name="advisor_score[{{ $member->id }}]" required
                                            class="block w-full px-4 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white transition-colors cursor-pointer appearance-none">
                                            <option value="" disabled selected>-- เลือกเกรด --</option>
                                            @foreach (['A', 'B+', 'B', 'C+', 'C', 'D+', 'D'] as $g)
                                                <option value="{{ $g }}">{{ $g }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div
                            class="p-4 bg-amber-50 dark:bg-amber-500/5 border border-amber-100 dark:border-amber-500/20 rounded-xl mb-6">
                            <p class="text-xs text-amber-700 dark:text-amber-400 leading-relaxed">
                                <i class="fa-solid fa-circle-exclamation mr-1"></i>
                                <strong>หมายเหตุ:</strong> คะแนนนี้จะถูกส่งไปยังระบบส่วนกลาง
                                ไม่สามารถแก้ไขได้หลังจากยืนยัน (ขึ้นอยู่กับนโยบาย)
                            </p>
                        </div>

                        <div class="flex flex-col gap-3">
                            <button type="button" onclick="confirmSubmit(this.form)"
                                class="w-full px-5 py-3 text-sm font-bold text-white bg-orange-500 rounded-xl hover:bg-orange-600 shadow-md shadow-orange-500/20 flex items-center justify-center gap-2 transition-colors">
                                <i class="fa-solid fa-save"></i> บันทึกคะแนน
                            </button>
                            <button type="button"
                                onclick="window.location.href='{{ route('advisor.submission.index') }}'"
                                class="w-full px-5 py-3 text-sm font-bold text-gray-500 bg-gray-100 dark:bg-gray-700/50 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-xl transition-colors">
                                ยกเลิก
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            // ฟังก์ชันยืนยันการบันทึก
            function confirmSubmit(form) {
                let selects = document.querySelectorAll("select[name^='advisor_score']");
                let incomplete = false;

                selects.forEach(function(select) {
                    if (!select.value) {
                        incomplete = true;
                    }
                });

                if (incomplete) {
                    Swal.fire({
                        icon: 'error',
                        title: 'ข้อมูลไม่ครบ',
                        text: 'กรุณาเลือกเกรดให้นักศึกษาครบทุกคนก่อนบันทึก',
                        confirmButtonColor: '#f97316'
                    });
                    return;
                }

                Swal.fire({
                    title: 'ยืนยันการบันทึก?',
                    text: "คุณต้องการบันทึกเกรดของนักศึกษาทั้งหมดใช่หรือไม่",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f97316',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }

            // แจ้งเตือนสถานะต่างๆ
            document.addEventListener("DOMContentLoaded", function() {
                @if ($errors->any())
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        html: `
                        <ul style="text-align:left;">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                        `,
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#f97316'
                    });
                @endif

                @if (session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'ผิดพลาด',
                        text: "{{ session('error') }}",
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#f97316'
                    });
                @endif

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
