<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto mt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div class="flex-1 min-w-0 pr-4">
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-file-signature text-orange-500 shrink-0"></i> พิจารณาการแก้ไขโครงงาน
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 leading-relaxed truncate">
                    ตรวจสอบไฟล์เอกสารที่นักศึกษาทำการแก้ไขและพิจารณาผล
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
                            <i class="fa-solid fa-list-check text-orange-500"></i> รายละเอียดการแก้ไขจากนักศึกษา
                        </h3>
                        <textarea disabled rows="3"
                            class="auto-expand block w-full p-4 text-sm text-gray-800 bg-gray-50 border border-gray-200 rounded-xl dark:bg-gray-900/50 dark:border-gray-700 dark:text-gray-200 resize-none cursor-default custom-scrollbar leading-relaxed"
                            placeholder="ไม่มีรายละเอียดการแก้ไข">{{ $revisions->edit_detail }}</textarea>
                    </div>

                    <div class="mb-8 border-t border-gray-100 dark:border-gray-700 pt-6">
                        <h3
                            class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-file-pdf text-red-500"></i> ไฟล์เอกสารที่แก้ไข
                        </h3>
                        <div
                            class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 group hover:border-blue-200 dark:hover:border-blue-500/30 transition-all">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset('icons/pdf.png') }}" class="w-10 h-10 object-contain" alt="PDF">
                                <div>
                                    <p class="text-sm font-bold text-gray-800 dark:text-white line-clamp-1">
                                        เอกสารฉบับแก้ไข.pdf</p>
                                    <p class="text-[10px] text-gray-500 uppercase tracking-wider">
                                        ตรวจสอบความถูกต้องก่อนอนุมัติ</p>
                                </div>
                            </div>
                            <a href="{{ route('invigilator.revision.download', $revisions->id) }}" target="_blank"
                                class="shrink-0 w-full sm:w-auto px-5 py-2.5 bg-blue-600 text-white rounded-xl shadow-sm hover:bg-blue-700 transition-colors flex items-center justify-center gap-2 text-xs font-bold">
                                <i class="fa-solid fa-download"></i> ดาวน์โหลด
                            </a>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 dark:border-gray-700 pt-6">
                        <h3
                            class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-users text-indigo-500"></i> รายละเอียดการอนุมัติของคณะกรรมการ
                        </h3>
                        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                            <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-500 uppercase bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                                    <tr>
                                        <th class="px-6 py-4 font-bold">รายชื่อกรรมการ</th>
                                        <th class="px-6 py-4 font-bold text-center w-40">สถานะการพิจารณา</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach ($invigilators as $inv)
                                        <tr
                                            class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="flex flex-col gap-1">
                                                    <span
                                                        class="font-bold text-gray-800 dark:text-gray-200">{{ $inv['name'] }}</span>
                                                    <div>
                                                        @if ($inv['role'] == 1)
                                                            <span
                                                                class="inline-flex px-2 py-0.5 bg-indigo-50 text-indigo-600 border border-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-400 dark:border-indigo-500/20 text-[10px] font-bold rounded-md uppercase tracking-wider">
                                                                อาจารย์ที่ปรึกษา
                                                            </span>
                                                        @elseif($inv['role'] == 2)
                                                            <span
                                                                class="inline-flex px-2 py-0.5 bg-gray-100 text-gray-600 border border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 text-[10px] font-bold rounded-md uppercase tracking-wider">
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
                                                            <i class="fa-solid fa-circle-check mr-1.5"></i> อนุมัติแล้ว
                                                        </span>
                                                    @break

                                                    @case('2')
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
                                                            class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-amber-50 text-amber-600 border border-amber-200 dark:bg-amber-500/10 dark:border-amber-500/20 dark:text-amber-400 whitespace-nowrap">
                                                            <i class="fa-solid fa-clock mr-1.5"></i> รอพิจารณา
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
            </div>

            <div class="lg:col-span-1">
                <form action="{{ route('invigilator.revision.update', $revisions->id) }}" method="POST"
                    class="sticky top-24">
                    @csrf
                    @method('PUT')

                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-orange-200 dark:border-orange-500/30 p-6 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-orange-400 to-red-500">
                        </div>

                        <h3
                            class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider mb-5 mt-2 flex items-center gap-2">
                            <i class="fa-solid fa-gavel text-gray-400"></i> ผลการตรวจสอบ
                        </h3>

                        <div class="flex flex-col gap-3 mb-2">
                            <label
                                class="relative flex items-center p-4 rounded-xl border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-emerald-50 dark:hover:bg-emerald-500/5 transition-all group has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 dark:has-[:checked]:bg-emerald-500/10">
                                <input name="status" type="radio" value="1"
                                    class="w-4 h-4 text-emerald-600 bg-white border-gray-300 focus:ring-emerald-500 cursor-pointer">
                                <span
                                    class="ml-3 text-sm font-bold text-gray-700 dark:text-gray-200 group-hover:text-emerald-700 dark:group-hover:text-emerald-400">
                                    อนุมัติการแก้ไข
                                </span>
                            </label>

                            <label
                                class="relative flex items-center p-4 rounded-xl border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-rose-50 dark:hover:bg-rose-500/5 transition-all group has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50 dark:has-[:checked]:bg-rose-500/10">
                                <input name="status" type="radio" value="3"
                                    class="w-4 h-4 text-rose-600 bg-white border-gray-300 focus:ring-rose-500 cursor-pointer">
                                <span
                                    class="ml-3 text-sm font-bold text-gray-700 dark:text-gray-200 group-hover:text-rose-700 dark:group-hover:text-rose-400">
                                    ไม่อนุมัติ / ให้แก้ไขใหม่
                                </span>
                            </label>
                        </div>

                        @error('status')
                            <p class="text-rose-500 text-xs mt-2 mb-4"><i class="fa-solid fa-circle-exclamation"></i>
                                {{ $message }}</p>
                        @enderror

                        <div class="flex flex-col gap-3 pt-6 mt-4 border-t border-gray-100 dark:border-gray-700">
                            <button type="submit"
                                class="w-full px-5 py-3 text-sm font-bold text-white bg-orange-500 rounded-xl hover:bg-orange-600 shadow-md shadow-orange-500/20 flex items-center justify-center gap-2 transition-colors">
                                <i class="fa-solid fa-save"></i> บันทึกผลการพิจารณา
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
            document.addEventListener("DOMContentLoaded", () => {
                // Auto-expand textarea (เพื่อให้กล่องพิมพ์ปรับความสูงตามเนื้อหา)
                document.querySelectorAll('.auto-expand').forEach(textarea => {
                    const adjust = el => {
                        el.style.height = 'auto';
                        const lineHeight = parseInt(window.getComputedStyle(el).lineHeight) || 20;
                        el.style.height = (el.scrollHeight + lineHeight) + 'px';
                    };
                    adjust(textarea);
                    textarea.addEventListener('input', () => adjust(textarea));
                    window.addEventListener('resize', () => adjust(textarea));
                });
            });
        </script>
    @endpush
</x-app-layout>
