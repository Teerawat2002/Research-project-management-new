<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto mt-4" x-data="{ showPdf: false, pdfUrl: '', pdfTitle: '', showImg: false, imgUrl: '' }">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div class="flex-1 min-w-0 pr-4">
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-file-pdf text-orange-500 shrink-0"></i> ข้อมูลไฟล์โครงงานวิจัย
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 leading-relaxed truncate">
                    รายละเอียดไฟล์เอกสารรูปเล่มโครงงานฉบับสมบูรณ์
                </p>
            </div>

            <a href="{{ route('advisor.upload.index')}}"
                class="shrink-0 whitespace-nowrap px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors flex items-center gap-2 text-sm font-medium">
                <i class="fa-solid fa-arrow-left"></i> ย้อนกลับ
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 md:p-8 transition-colors duration-200">

                    <div class="mb-8 pb-6 border-b border-gray-100 dark:border-gray-700">
                        <div
                            class="text-[11px] font-bold text-orange-500 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-book"></i> ชื่อโครงงานวิจัย
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white leading-tight">
                            {{ $propose->title ?? '-' }}
                        </h2>
                    </div>

                    <h3
                        class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-5 flex items-center gap-2">
                        <i class="fa-solid fa-folder-open text-orange-500"></i> ไฟล์เอกสารที่แนบมา
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div class="flex flex-col h-full">
                            <div class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-3">หน้าปกโครงงาน
                                (รูปภาพ)</div>
                            <div
                                class="flex-1 bg-gray-50 dark:bg-gray-900/50 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 flex items-center justify-center relative group">
                                @if ($coverPreviewUrl)
                                    <img src="{{ $coverPreviewUrl }}" alt="หน้าปกโครงงาน"
                                        class="max-h-[300px] w-auto object-contain rounded-lg shadow-sm cursor-zoom-in group-hover:opacity-90 transition-opacity"
                                        @click="imgUrl='{{ $coverPreviewUrl }}'; showImg=true">

                                    <div
                                        class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                                        <div
                                            class="bg-gray-900/70 text-white w-12 h-12 rounded-full flex items-center justify-center backdrop-blur-sm">
                                            <i class="fa-solid fa-magnifying-glass-plus text-xl"></i>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex flex-col items-center justify-center text-gray-400 py-12">
                                        <i class="fa-regular fa-image text-4xl mb-3"></i>
                                        <span class="text-sm">ไม่มีรูปหน้าปก</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-col gap-4">
                            <div>
                                <div class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-3">บทคัดย่อ
                                    (PDF)</div>
                                <div
                                    class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 flex items-center justify-between group hover:border-orange-200 dark:hover:border-orange-500/30 transition-all">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ asset('icons/pdf.png') }}" class="w-10 h-10 object-contain"
                                            alt="PDF">
                                        <div>
                                            <p class="text-sm font-bold text-gray-800 dark:text-white line-clamp-1">
                                                บทคัดย่อ.pdf</p>
                                        </div>
                                    </div>
                                    @if ($file?->abstract_file)
                                        <button type="button"
                                            @click="pdfUrl='{{ route('advisor.upload.preview', ['upload' => $upload->id, 'type' => 'abstract']) }}'; pdfTitle='บทคัดย่อ'; showPdf=true"
                                            class="shrink-0 p-2.5 bg-white dark:bg-gray-700 text-blue-600 dark:text-blue-400 rounded-xl shadow-sm hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors border border-gray-100 dark:border-gray-600 flex items-center justify-center gap-2 text-xs font-bold">
                                            <i class="fa-solid fa-eye"></i> เปิดดู
                                        </button>
                                    @else
                                        <span class="text-xs text-gray-400 font-medium px-3">ไม่มีไฟล์</span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <div class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-3">
                                    ไฟล์โครงงานฉบับสมบูรณ์ (PDF)</div>
                                <div
                                    class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 flex items-center justify-between group hover:border-orange-200 dark:hover:border-orange-500/30 transition-all">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ asset('icons/pdf.png') }}" class="w-10 h-10 object-contain"
                                            alt="PDF">
                                        <div>
                                            <p class="text-sm font-bold text-gray-800 dark:text-white line-clamp-1">
                                                เล่มโครงงาน.pdf</p>
                                        </div>
                                    </div>
                                    @if ($file?->project_file)
                                        <button type="button"
                                            @click="pdfUrl='{{ route('advisor.upload.preview', ['upload' => $upload->id, 'type' => 'project']) }}'; pdfTitle='ไฟล์โครงงานฉบับสมบูรณ์'; showPdf=true"
                                            class="shrink-0 p-2.5 bg-white dark:bg-gray-700 text-blue-600 dark:text-blue-400 rounded-xl shadow-sm hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors border border-gray-100 dark:border-gray-600 flex items-center justify-center gap-2 text-xs font-bold">
                                            <i class="fa-solid fa-eye"></i> เปิดดู
                                        </button>
                                    @else
                                        <span class="text-xs text-gray-400 font-medium px-3">ไม่มีไฟล์</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">

                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 transition-colors duration-200">
                    <h3
                        class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-tags text-indigo-500"></i> คำสำคัญ (Keyword)
                    </h3>
                    <div
                        class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                        <p class="text-sm font-medium text-gray-800 dark:text-gray-200 leading-relaxed">
                            {{ $upload->keyword ?? 'ไม่ได้ระบุคำสำคัญ' }}
                        </p>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 transition-colors duration-200 relative overflow-hidden">

                    @php $status = (int) ($upload->status ?? 1); @endphp

                    @if ($status == 0)
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-emerald-400 to-green-500">
                        </div>
                    @elseif($status == 1)
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-amber-400 to-yellow-500">
                        </div>
                    @elseif($status == 2)
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-rose-400 to-red-500"></div>
                    @else
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gray-300 dark:bg-gray-600"></div>
                    @endif

                    <h3
                        class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-5 mt-2 flex items-center gap-2">
                        <i class="fa-solid fa-gavel text-gray-400"></i> สถานะการอนุมัติไฟล์
                    </h3>

                    <div class="mb-6">
                        @switch($status)
                            @case(0)
                                <div
                                    class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20 w-full justify-center">
                                    <i class="fa-solid fa-circle-check mr-2"></i> อนุมัติแล้ว
                                </div>
                            @break

                            @case(1)
                                <div
                                    class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20 w-full justify-center">
                                    <i class="fa-solid fa-clock mr-2"></i> รอพิจารณา
                                </div>
                            @break

                            @case(2)
                                <div
                                    class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-500/10 dark:text-rose-400 dark:border-rose-500/20 w-full justify-center">
                                    <i class="fa-solid fa-circle-xmark mr-2"></i> ถูกปฏิเสธ
                                </div>
                            @break

                            @default
                                <div
                                    class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold bg-gray-100 text-gray-700 border border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 w-full justify-center">
                                    ไม่ทราบสถานะ
                                </div>
                        @endswitch
                    </div>

                    <div class="mb-6">
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">
                            ข้อเสนอแนะ / เหตุผล
                        </label>
                        <textarea rows="4" disabled
                            class="auto-expand block w-full p-4 text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-xl dark:bg-gray-900/50 dark:border-gray-700 dark:text-gray-300 resize-none cursor-not-allowed custom-scrollbar"
                            placeholder="ไม่มีข้อเสนอแนะ">{{ $upload->comment ?? '' }}</textarea>
                    </div>

                    <div class="pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <span class="text-[10px] font-bold text-gray-400 uppercase">อัปเดตล่าสุด</span>
                        <span class="text-xs font-bold text-gray-600 dark:text-gray-300">
                            {{ optional($upload->updated_at)->format('d/m/Y') ?? '-' }}
                        </span>
                    </div>

                </div>
            </div>

        </div>

        <div :class="{ 'hidden': !showImg }" x-transition.opacity
            class="hidden fixed inset-0 z-[100] bg-black/80 flex items-center justify-center p-4 backdrop-blur-sm">
            <div
                class="bg-white dark:bg-gray-900 rounded-2xl overflow-hidden w-full max-w-4xl shadow-2xl border border-gray-200 dark:border-gray-700 transform transition-all">
                <div
                    class="flex justify-between items-center bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                    <h2 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="fa-regular fa-image text-orange-500"></i> หน้าปกโครงงาน
                    </h2>
                    <button @click="showImg = false"
                        class="text-gray-400 hover:text-rose-500 dark:hover:text-rose-400 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                <div class="p-6 bg-gray-100 dark:bg-gray-900 flex justify-center items-center min-h-[50vh]">
                    <img :src="imgUrl" alt="preview"
                        class="max-h-[70vh] max-w-full object-contain drop-shadow-md rounded-lg">
                </div>
            </div>
        </div>

        <div :class="{ 'hidden': !showPdf }" x-transition.opacity
            class="hidden fixed inset-0 z-[100] bg-black/80 flex items-center justify-center p-4 backdrop-blur-sm">
            <div
                class="bg-white dark:bg-gray-900 rounded-2xl overflow-hidden w-full max-w-6xl h-[90vh] flex flex-col shadow-2xl border border-gray-200 dark:border-gray-700">
                <div
                    class="flex justify-between items-center bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4 shrink-0">
                    <h2 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-file-pdf text-orange-500"></i> <span x-text="pdfTitle"></span>
                    </h2>
                    <button @click="showPdf = false; pdfUrl=''"
                        class="text-gray-400 hover:text-rose-500 dark:hover:text-rose-400 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                <div class="flex-1 w-full bg-gray-100 dark:bg-gray-900 relative">
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 -z-10">
                        <i class="fa-solid fa-circle-notch fa-spin text-3xl mb-3 text-orange-500"></i>
                        <span class="text-sm font-medium">กำลังโหลดเอกสาร...</span>
                    </div>
                    <iframe :src="pdfUrl"
                        class="w-full h-full border-0 relative z-10 bg-transparent"></iframe>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                // Auto-expand textarea
                document.querySelectorAll('.auto-expand').forEach(textarea => {
                    const adjust = el => {
                        el.style.height = 'auto';
                        const lineHeight = parseInt(window.getComputedStyle(el).lineHeight) || 20;
                        el.style.height = (el.scrollHeight + lineHeight) + 'px';
                    };
                    setTimeout(() => adjust(textarea), 100);
                    window.addEventListener('resize', () => adjust(textarea));
                });
            });
        </script>
    @endpush
</x-app-layout>
