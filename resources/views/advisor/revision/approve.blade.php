<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto mt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div class="flex-1 min-w-0 pr-4">
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-file-signature text-orange-500 shrink-0"></i> พิจารณาอนุมัติการแก้ไขโครงงาน
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 leading-relaxed truncate">
                    ตรวจสอบไฟล์เอกสารและรายละเอียดการแก้ไขที่นักศึกษาส่งมา
                </p>
            </div>

            <a href="javascript:void(0);" onclick="window.history.back();"
                class="shrink-0 whitespace-nowrap px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors flex items-center gap-2 text-sm font-medium">
                <i class="fa-solid fa-arrow-left"></i> ย้อนกลับ
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">

                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 md:p-8 transition-colors duration-200">

                    <div class="mb-8">
                        <h3
                            class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-file-pdf text-red-500"></i> ไฟล์เอกสารที่แก้ไข
                        </h3>
                        <div
                            class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 flex items-center justify-between group hover:border-orange-200 dark:hover:border-orange-500/30 transition-all">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset('icons/pdf.png') }}" class="w-10 h-10 object-contain" alt="PDF">
                                <div>
                                    <p class="text-sm font-bold text-gray-800 dark:text-white line-clamp-1">
                                        เอกสารฉบับแก้ไข.pdf</p>
                                    <p class="text-[10px] text-gray-500 uppercase tracking-wider">PDF DOCUMENT</p>
                                </div>
                            </div>
                            <a href="{{ route('advisor.revision.download', $revisions->id) }}" target="_blank"
                                class="p-2.5 bg-white dark:bg-gray-700 text-blue-600 dark:text-blue-400 rounded-xl shadow-sm hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors border border-gray-100 dark:border-gray-600 flex items-center gap-2 text-xs font-bold">
                                <i class="fa-solid fa-download"></i> ดาวน์โหลด
                            </a>
                        </div>
                    </div>

                    <div>
                        <h3
                            class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-list-check text-blue-500"></i> รายละเอียดการแก้ไข (จากนักศึกษา)
                        </h3>
                        <textarea disabled rows="4"
                            class="auto-expand block w-full p-4 text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-2xl dark:bg-gray-900/50 dark:border-gray-700 dark:text-gray-300 resize-none cursor-not-allowed custom-scrollbar">{{ $revisions->edit_detail }}</textarea>
                    </div>

                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-colors duration-200">
                    <div class="p-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
                        <h3
                            class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-users-viewfinder text-indigo-500"></i>
                            รายละเอียดการอนุมัติของคณะกรรมการ
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-500 uppercase bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                                <tr>
                                    <th class="px-6 py-4 font-semibold">รายชื่อกรรมการ</th>
                                    <th class="px-6 py-4 font-semibold text-center w-40">สถานะ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                                @foreach ($invigilators as $inv)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span
                                                    class="font-medium text-gray-900 dark:text-white">{{ $inv['name'] }}</span>
                                                <div class="mt-1">
                                                    @if ($inv['role'] == 1)
                                                        <span
                                                            class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 border border-blue-200 dark:border-blue-500/30">
                                                            อาจารย์ที่ปรึกษา
                                                        </span>
                                                    @elseif($inv['role'] == 2)
                                                        <span
                                                            class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                                            กรรมการคุมสอบ
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @switch($inv['status'])
                                                @case('1')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-50 text-emerald-600 border border-emerald-200 dark:bg-emerald-500/10 dark:border-emerald-500/20 dark:text-emerald-400 whitespace-nowrap">
                                                        <i class="fa-solid fa-circle-check mr-1.5"></i> อนุมัติ
                                                    </span>
                                                @break

                                                @case('2')
                                                @case(null)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-amber-50 text-amber-600 border border-amber-200 dark:bg-amber-500/10 dark:border-amber-500/20 dark:text-amber-400 whitespace-nowrap">
                                                        <i class="fa-solid fa-clock mr-1.5"></i> รอพิจารณา
                                                    </span>
                                                @break

                                                @case('3')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-rose-50 text-rose-600 border border-rose-200 dark:bg-rose-500/10 dark:border-rose-500/20 dark:text-rose-400 whitespace-nowrap">
                                                        <i class="fa-solid fa-circle-xmark mr-1.5"></i> ไม่อนุมัติ
                                                    </span>
                                                @break

                                                @default
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 whitespace-nowrap">
                                                        ไม่ทราบสถานะ
                                                    </span>
                                            @endswitch
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <form action="{{ route('advisor.revision.update', $revisions->id) }}" method="POST"
                    class="sticky top-24">
                    @csrf
                    @method('PUT')

                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-orange-200 dark:border-orange-500/30 p-6 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-orange-400 to-red-500">
                        </div>

                        <h3
                            class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider mb-5 mt-2 flex items-center gap-2">
                            <i class="fa-solid fa-gavel text-gray-400"></i> ผลการพิจารณาของคุณ
                        </h3>

                        <div class="flex flex-col gap-3 mb-6">
                            <label
                                class="relative flex items-center p-4 rounded-xl border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-emerald-50 dark:hover:bg-emerald-500/5 transition-all group has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 dark:has-[:checked]:bg-emerald-500/10">
                                <input name="status" type="radio" value="1" id="approved"
                                    class="w-4 h-4 text-emerald-600 bg-white border-gray-300 focus:ring-emerald-500 cursor-pointer">
                                <span
                                    class="ml-3 text-sm font-bold text-gray-700 dark:text-gray-200 group-hover:text-emerald-700 dark:group-hover:text-emerald-400">อนุมัติการแก้ไข</span>
                            </label>

                            <label
                                class="relative flex items-center p-4 rounded-xl border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-rose-50 dark:hover:bg-rose-500/5 transition-all group has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50 dark:has-[:checked]:bg-rose-500/10">
                                <input name="status" type="radio" value="3" id="rejected"
                                    class="w-4 h-4 text-rose-600 bg-white border-gray-300 focus:ring-rose-500 cursor-pointer">
                                <span
                                    class="ml-3 text-sm font-bold text-gray-700 dark:text-gray-200 group-hover:text-rose-700 dark:group-hover:text-rose-400">ไม่อนุมัติ</span>
                            </label>
                        </div>
                        @error('status')
                            <p class="text-rose-500 text-xs mb-4">{{ $message }}</p>
                        @enderror

                        <div class="flex flex-col gap-3 pt-2">
                            <button type="submit"
                                class="w-full px-5 py-3 text-sm font-bold text-white bg-orange-500 rounded-xl hover:bg-orange-600 shadow-md shadow-orange-500/20 flex items-center justify-center gap-2 transition-colors">
                                <i class="fa-solid fa-paper-plane"></i> ยืนยันผลการพิจารณา
                            </button>
                            <button type="button" onclick="window.history.back();"
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
            // สคริปต์ปรับขนาด textarea อัตโนมัติ
            document.addEventListener("DOMContentLoaded", () => {
                document.querySelectorAll('.auto-expand').forEach(textarea => {
                    const adjust = el => {
                        el.style.height = 'auto';
                        const lineHeight = parseInt(window.getComputedStyle(el).lineHeight) || 20;
                        el.style.height = (el.scrollHeight + lineHeight) + 'px';
                    };
                    adjust(textarea);
                    // Resize on window resize to keep it neat
                    window.addEventListener('resize', () => adjust(textarea));
                });

                // แจ้งเตือน SweetAlert
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
