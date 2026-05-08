<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto mt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-clipboard-check text-orange-500"></i> พิจารณาข้อเสนอโครงงาน
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    ตรวจสอบรายละเอียดและดำเนินการบันทึกผลการพิจารณาหัวข้อ
                </p>
            </div>
            <a href="{{ route('advisor.propose.index') }}"
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
                            {{ $proposal->title }}
                        </h2>
                        <div class="mt-4">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 border border-blue-200 dark:border-blue-500/30">
                                {{ $proposal->project_type->name ?? 'ไม่ระบุประเภท' }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <div>
                            <h3
                                class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-bullseye text-orange-500 text-xs"></i> วัตถุประสงค์
                            </h3>
                            <div
                                class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed bg-gray-50 dark:bg-gray-900/50 p-5 rounded-2xl border border-gray-100 dark:border-gray-700">
                                {!! nl2br(e($proposal->objective ?: 'ไม่มีข้อมูล')) !!}
                            </div>
                        </div>

                        <div>
                            <h3
                                class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-crop-simple text-blue-500 text-xs"></i> ขอบเขต
                            </h3>
                            <div
                                class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed bg-gray-50 dark:bg-gray-900/50 p-5 rounded-2xl border border-gray-100 dark:border-gray-700">
                                {!! nl2br(e($proposal->scope ?: 'ไม่มีข้อมูล')) !!}
                            </div>
                        </div>

                        <div>
                            <h3
                                class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-screwdriver-wrench text-purple-500 text-xs"></i> เครื่องมือที่ใช้
                            </h3>
                            <div
                                class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed bg-gray-50 dark:bg-gray-900/50 p-5 rounded-2xl border border-gray-100 dark:border-gray-700">
                                {!! nl2br(e($proposal->tools ?: 'ไม่มีข้อมูล')) !!}
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="space-y-6">

                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <h3
                        class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-4 border-b border-gray-100 dark:border-gray-700 pb-2 flex items-center gap-2">
                        <i class="fa-solid fa-users text-gray-400"></i> สมาชิกกลุ่ม
                    </h3>
                    <ul class="space-y-3">
                        @forelse ($groupMembers as $member)
                            <li class="flex items-center gap-3 text-sm text-gray-700 dark:text-gray-300">
                                <div
                                    class="w-8 h-8 rounded-full bg-orange-100 dark:bg-orange-500/20 flex items-center justify-center text-orange-500 shadow-sm shrink-0">
                                    <i class="fa-solid fa-user text-xs"></i>
                                </div>
                                <span class="font-medium">{{ $member->student->name ?? 'ไม่ระบุชื่อ' }}</span>
                            </li>
                        @empty
                            <li class="text-sm text-gray-500 dark:text-gray-400 py-2">ไม่มีสมาชิกในกลุ่มนี้</li>
                        @endforelse
                    </ul>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-orange-200 dark:border-orange-500/30 p-6 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-orange-400 to-red-500"></div>

                    <h3
                        class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider mb-5 mt-2 flex items-center gap-2">
                        <i class="fa-solid fa-gavel text-gray-400"></i> ผลการพิจารณา
                    </h3>

                    <form method="POST" action="{{ route('advisor.propose.approve', $proposal->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="flex flex-col gap-3 mb-6">
                            <label
                                class="relative flex items-center p-4 rounded-xl border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-emerald-50 dark:hover:bg-emerald-500/5 transition-all group has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 dark:has-[:checked]:bg-emerald-500/10">
                                <input name="approval" type="radio" value="approved" id="approved"
                                    class="w-4 h-4 text-emerald-600 bg-white border-gray-300 focus:ring-emerald-500"
                                    {{ in_array($proposal->status, [0, 5]) ? 'checked' : '' }}
                                    {{ in_array($proposal->status, [0, 2]) ? 'disabled' : '' }}>
                                <span
                                    class="ml-3 text-sm font-bold text-gray-700 dark:text-gray-200 group-hover:text-emerald-700 dark:group-hover:text-emerald-400">อนุมัติข้อเสนอ</span>
                            </label>

                            <label
                                class="relative flex items-center p-4 rounded-xl border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-rose-50 dark:hover:bg-rose-500/5 transition-all group has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50 dark:has-[:checked]:bg-rose-500/10">
                                <input name="approval" type="radio" value="rejected" id="rejected"
                                    class="w-4 h-4 text-rose-600 bg-white border-gray-300 focus:ring-rose-500"
                                    {{ $proposal->status == 2 ? 'checked' : '' }}
                                    {{ in_array($proposal->status, [0, 2]) ? 'disabled' : '' }}>
                                <span
                                    class="ml-3 text-sm font-bold text-gray-700 dark:text-gray-200 group-hover:text-rose-700 dark:group-hover:text-rose-400">ไม่อนุมัติ
                                    (ส่งแก้ไข)</span>
                            </label>
                        </div>
                        @error('approval')
                            <p class="text-rose-500 text-xs mb-4">{{ $message }}</p>
                        @enderror

                        <div id="rejection-reason" style="display: none;" class="mb-6 animate-fade-in-up">
                            <label for="reason"
                                class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wider">
                                ความคิดเห็น / ข้อเสนอแนะ <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="reason" id="reason" rows="4" placeholder="ระบุสิ่งที่ต้องการให้แก้ไข..."
                                class="block w-full px-4 py-3 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:placeholder-gray-500 dark:text-white transition-colors custom-scrollbar"
                                {{ in_array($proposal->status, [0, 2]) ? 'disabled' : '' }}>{{ $proposal->comments }}</textarea>
                            @error('reason')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        @if (!in_array($proposal->status, [0, 2]))
                            <button type="submit"
                                class="w-full px-5 py-3 text-sm font-bold text-white bg-orange-500 rounded-xl hover:bg-orange-600 shadow-md shadow-orange-500/20 flex items-center justify-center gap-2 transition-colors">
                                <i class="fa-solid fa-paper-plane"></i> บันทึกผลการพิจารณา
                            </button>
                        @else
                            <div
                                class="text-center text-sm font-medium text-gray-500 bg-gray-100 dark:bg-gray-700/50 py-3 rounded-xl border border-dashed border-gray-300 dark:border-gray-600">
                                <i class="fa-solid fa-lock mr-1.5"></i> บันทึกข้อมูลแล้ว
                            </div>
                        @endif

                    </form>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const rejectedRadio = document.getElementById('rejected');
                const approvedRadio = document.getElementById('approved');
                const rejectionReason = document.getElementById('rejection-reason');

                // ฟังก์ชันเช็คสถานะการแสดงผลกล่องเหตุผล
                const checkStatus = () => {
                    if (rejectedRadio.checked) {
                        rejectionReason.style.display = 'block';
                    } else {
                        rejectionReason.style.display = 'none';
                    }
                };

                // เริ่มต้นตรวจสอบครั้งแรก
                checkStatus();

                // ผูกเหตุการณ์เมื่อเปลี่ยนตัวเลือก
                rejectedRadio.addEventListener('change', checkStatus);
                approvedRadio.addEventListener('change', checkStatus);

                // สคริปต์ปรับขนาด textarea อัตโนมัติ (ที่คุณต้องการ)
                document.querySelectorAll('.auto-expand').forEach(textarea => {
                    const adjust = el => {
                        el.style.height = 'auto';
                        const lineHeight = parseInt(window.getComputedStyle(el).lineHeight);
                        el.style.height = (el.scrollHeight + lineHeight) + 'px';
                    };
                    adjust(textarea);
                    textarea.addEventListener('input', () => adjust(textarea));
                });

                // SweetAlert สำหรับแจ้งเตือน
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'บันทึกสำเร็จ',
                        text: "{{ session('success') }}",
                        confirmButtonColor: '#f97316'
                    });
                @endif
                @if (session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: {!! json_encode(session('error')) !!},
                        confirmButtonColor: '#f97316'
                    });
                @endif
            });
        </script>
    @endpush
</x-app-layout>
