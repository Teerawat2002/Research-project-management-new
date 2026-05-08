<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto mt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-file-signature text-orange-500"></i> พิจารณาการยื่นสอบโครงงาน
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    ตรวจสอบความถูกต้องของไฟล์และรายละเอียดก่อนดำเนินการอนุมัติสอบ
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
                        <div class="flex flex-wrap gap-2 mb-3">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 border border-blue-200 dark:border-blue-500/30 uppercase">
                                {{ $propose->project_type->name }}
                            </span>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-orange-50 text-orange-700 dark:bg-orange-500/10 dark:text-orange-400 border border-orange-200 dark:border-orange-500/30 uppercase">
                                ครั้งที่สอบ: {{ $submission->attempt }}
                            </span>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white leading-tight">
                            {{ $propose->title }}
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3
                                class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-users text-orange-500"></i> สมาชิกกลุ่มโครงงาน
                            </h3>
                            <ul class="space-y-3">
                                @foreach ($members as $member)
                                    <li class="flex items-center gap-3 text-sm text-gray-700 dark:text-gray-300">
                                        <div
                                            class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 shrink-0">
                                            <i class="fa-solid fa-user text-xs"></i>
                                        </div>
                                        <span class="font-medium">{{ $member->student->s_fname }}
                                            {{ $member->student->s_lname }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div>
                            <h3
                                class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-file-pdf text-red-500"></i> เอกสารประกอบการยื่นสอบ
                            </h3>                            
                            <div
                                class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 flex items-center justify-between group hover:border-orange-200 dark:hover:border-orange-500/30 transition-all">
                                <div class="flex items-center gap-3">
                                    <img src="{{ asset('icons/pdf.png') }}" class="w-10 h-10 object-contain"
                                        alt="PDF">
                                    <div>
                                        <p class="text-sm font-bold text-gray-800 dark:text-white line-clamp-1">
                                            เอกสารโครงงาน.pdf</p>
                                        <p class="text-[10px] text-gray-500 uppercase tracking-wider">PDF DOCUMENT
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ route('student.submission.download', ['id' => $submission->id]) }}"
                                    class="p-2.5 bg-white dark:bg-gray-700 text-blue-600 dark:text-blue-400 rounded-xl shadow-sm hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors border border-gray-100 dark:border-gray-600 flex items-center gap-2 text-xs font-bold">
                                    <i class="fa-solid fa-download"></i> ดาวน์โหลด
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="space-y-6">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-orange-200 dark:border-orange-500/30 p-6 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-orange-400 to-red-500"></div>

                    <h3
                        class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider mb-5 mt-2 flex items-center gap-2">
                        <i class="fa-solid fa-gavel text-gray-400"></i> ผลการตรวจสอบ
                    </h3>

                    <form method="POST" action="{{ route('advisor.submission.save', ['id' => $submission->id]) }}">
                        @csrf
                        @method('PUT')

                        <div class="flex flex-col gap-3 mb-6">
                            <label
                                class="relative flex items-center p-4 rounded-xl border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-emerald-50 dark:hover:bg-emerald-500/5 transition-all group has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 dark:has-[:checked]:bg-emerald-500/10">
                                <input name="approval" type="radio" value="approved" id="approved"
                                    class="w-4 h-4 text-emerald-600 bg-white border-gray-300 focus:ring-emerald-500"
                                    {{ old('approval', $submission->status === 0 ? 'approved' : '') === 'approved' ? 'checked' : '' }}>
                                <span
                                    class="ml-3 text-sm font-bold text-gray-700 dark:text-gray-200 group-hover:text-emerald-700 dark:group-hover:text-emerald-400">อนุมัติการยื่นสอบ</span>
                            </label>

                            <label
                                class="relative flex items-center p-4 rounded-xl border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-rose-50 dark:hover:bg-rose-500/5 transition-all group has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50 dark:has-[:checked]:bg-rose-50/10">
                                <input name="approval" type="radio" value="rejected" id="rejected"
                                    class="w-4 h-4 text-rose-600 bg-white border-gray-300 focus:ring-rose-500"
                                    {{ old('approval', $submission->status === 2 ? 'rejected' : '') === 'rejected' ? 'checked' : '' }}>
                                <span
                                    class="ml-3 text-sm font-bold text-gray-700 dark:text-gray-200 group-hover:text-rose-700 dark:group-hover:text-rose-400">ไม่อนุมัติ
                                    (ส่งแก้ไข)</span>
                            </label>
                        </div>
                        @error('approval')
                            <p class="text-rose-500 text-xs mb-4">{{ $message }}</p>
                        @enderror

                        <div id="reason-wrapper"
                            class="mb-6 animate-fade-in-up {{ old('approval', $submission->status === 2 ? 'rejected' : '') === 'rejected' ? '' : 'hidden' }}">
                            <label for="reason"
                                class="block text-[11px] font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wider">
                                เหตุผลการไม่อนุมัติ / สิ่งที่ต้องแก้ไข <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="reason" id="reason" rows="4" placeholder="ระบุสิ่งที่นักศึกษาต้องปรับปรุง..."
                                class="block w-full px-4 py-3 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:placeholder-gray-500 dark:text-white transition-colors custom-scrollbar">{{ old('reason', $submission->reason) }}</textarea>
                            @error('reason')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-3">
                            <button type="submit"
                                class="w-full px-5 py-3 text-sm font-bold text-white bg-orange-500 rounded-xl hover:bg-orange-600 shadow-md shadow-orange-500/20 flex items-center justify-center gap-2 transition-colors">
                                <i class="fa-solid fa-save"></i> บันทึกผลการตรวจสอบ
                            </button>
                            <button type="button"
                                onclick="window.location.href='{{ route('advisor.submission.index') }}'"
                                class="w-full px-5 py-3 text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors">
                                ยกเลิก
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const approved = document.querySelector('input[value="approved"]');
                const rejected = document.querySelector('input[value="rejected"]');
                const wrapper = document.getElementById('reason-wrapper');

                // Toggle visibility
                approved.addEventListener('change', () => wrapper.classList.add('hidden'));
                rejected.addEventListener('change', () => wrapper.classList.remove('hidden'));

                // SweetAlert
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
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
