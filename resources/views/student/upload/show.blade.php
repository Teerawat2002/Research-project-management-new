<x-app-layout>
    <div class="p-6 max-w-6xl mx-auto mt-4" x-data="{ showPdf: false, pdfUrl: '', pdfTitle: '', showImg: false, imgUrl: '' }">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-file-invoice text-orange-500"></i> รายละเอียดไฟล์โครงงานวิจัย
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                    ข้อมูลและสถานะการตรวจสอบไฟล์โครงงานฉบับสมบูรณ์ของคุณ
                </p>
            </div>

            <a href="{{ route('student.upload.index') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors text-sm font-medium w-full md:w-auto">
                <i class="fa-solid fa-arrow-left mr-2"></i> ย้อนกลับ
            </a>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-200 overflow-hidden">

            <div class="p-6 md:p-8">

                <div
                    class="bg-orange-50 dark:bg-orange-500/10 border border-orange-100 dark:border-orange-500/20 p-5 rounded-xl mb-8">
                    <h3 class="text-sm font-bold text-orange-800 dark:text-orange-400 mb-1 flex items-center gap-2">
                        <i class="fa-solid fa-book-open"></i> ชื่อโครงงานวิจัย
                    </h3>
                    <p class="text-base font-semibold text-gray-900 dark:text-white pl-6">
                        {{ $propose->title ?? '-' }}
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                    <div class="lg:col-span-2 space-y-6">

                        <div
                            class="bg-gray-50 dark:bg-gray-900/50 p-5 rounded-xl border border-gray-200 dark:border-gray-700">
                            <h3
                                class="text-sm font-bold text-gray-800 dark:text-gray-200 tracking-wide flex items-center gap-2 mb-4 border-b border-gray-200 dark:border-gray-700 pb-3">
                                <i class="fa-solid fa-folder-open text-blue-500"></i> ไฟล์ที่อัปโหลด
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <div
                                        class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                                        หน้าปก (รูปภาพ)</div>
                                    @if ($coverPreviewUrl)
                                        <div class="relative group cursor-zoom-in"
                                            @click="imgUrl='{{ $coverPreviewUrl }}'; showImg=true">
                                            <img src="{{ $coverPreviewUrl }}" alt="หน้าปกโครงงาน"
                                                class="w-full max-h-48 object-cover rounded-lg border border-gray-200 dark:border-gray-600 shadow-sm transition-transform duration-300 group-hover:scale-[1.02]">
                                            <div
                                                class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-lg">
                                                <i class="fa-solid fa-magnifying-glass-plus text-white text-2xl"></i>
                                            </div>
                                        </div>
                                        <div class="text-xs font-medium text-gray-500 mt-2 pl-1">คลิกที่รูปเพื่อขยาย
                                        </div>
                                    @else
                                        <div
                                            class="h-24 flex items-center justify-center rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-400">
                                            <span class="text-sm font-medium">ไม่มีไฟล์หน้าปก</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <div
                                            class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                                            บทคัดย่อ (PDF)</div>
                                        @if ($file?->abstract_file)
                                            <button type="button"
                                                @click="pdfUrl='{{ route('student.upload.preview', ['upload' => $upload->id, 'type' => 'abstract']) }}' + '?v=' + Date.now(); pdfTitle='บทคัดย่อ'; showPdf=true"
                                                class="inline-flex items-center gap-3 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl hover:bg-blue-50 dark:hover:bg-blue-500/10 hover:border-blue-200 dark:hover:border-blue-500/30 transition-all shadow-sm group w-full text-left">
                                                <i
                                                    class="fa-solid fa-file-pdf text-rose-500 text-xl group-hover:scale-110 transition-transform"></i>
                                                <span
                                                    class="text-sm font-semibold text-gray-700 dark:text-gray-200 group-hover:text-blue-600 dark:group-hover:text-blue-400">เปิดดูบทคัดย่อ</span>
                                            </button>
                                        @else
                                            <div
                                                class="px-4 py-2 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-400 text-sm font-medium">
                                                ไม่มีไฟล์บทคัดย่อ
                                            </div>
                                        @endif
                                    </div>

                                    <div>
                                        <div
                                            class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                                            ไฟล์โครงงาน (PDF)</div>
                                        @if ($file?->project_file)
                                            <button type="button"
                                                @click="pdfUrl='{{ route('student.upload.preview', ['upload' => $upload->id, 'type' => 'project']) }}' + '?v=' + Date.now(); pdfTitle='ไฟล์โครงงานฉบับสมบูรณ์'; showPdf=true"
                                                class="inline-flex items-center gap-3 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl hover:bg-blue-50 dark:hover:bg-blue-500/10 hover:border-blue-200 dark:hover:border-blue-500/30 transition-all shadow-sm group w-full text-left">
                                                <i
                                                    class="fa-solid fa-file-pdf text-rose-500 text-xl group-hover:scale-110 transition-transform"></i>
                                                <span
                                                    class="text-sm font-semibold text-gray-700 dark:text-gray-200 group-hover:text-blue-600 dark:group-hover:text-blue-400">เปิดดูไฟล์โครงงาน</span>
                                            </button>
                                        @else
                                            <div
                                                class="px-4 py-2 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-400 text-sm font-medium">
                                                ไม่มีไฟล์โครงงาน
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-bold text-gray-800 dark:text-gray-200 tracking-wide flex items-center gap-2 mb-2">
                                <i class="fa-solid fa-key text-orange-500"></i> คำสำคัญ (Keywords)
                            </label>
                            <div
                                class="w-full px-4 py-3 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-lg dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                                {{ $upload->keyword ?? '-' }}
                            </div>
                        </div>

                        <div class="text-xs font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2">
                            <i class="fa-solid fa-clock-rotate-left"></i> อัปเดตข้อมูลล่าสุด:
                            <span class="text-gray-700 dark:text-gray-300">
                                {{ optional($upload->updated_at)->timezone('Asia/Bangkok')->format('d/m/Y • H:i') ?? '-' }}
                                น.
                            </span>
                        </div>

                    </div>

                    <div class="lg:col-span-1 space-y-6 lg:sticky lg:top-6">

                        <div
                            class="bg-gray-50 dark:bg-gray-900/50 p-5 rounded-xl border border-gray-200 dark:border-gray-700">
                            <h3
                                class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">
                                สถานะการอนุมัติล่าสุด</h3>
                            @php $status = (int) ($upload->status ?? 1); @endphp
                            <div>
                                @switch($status)
                                    @case(0)
                                        <span
                                            class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 w-full justify-center">
                                            <i class="fa-solid fa-check-double mr-2"></i> อนุมัติแล้ว
                                        </span>
                                    @break

                                    @case(1)
                                        <span
                                            class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-bold bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 w-full justify-center">
                                            <i class="fa-solid fa-hourglass-half mr-2"></i> รอการตรวจสอบ
                                        </span>
                                    @break

                                    @case(2)
                                        <span
                                            class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-bold bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400 w-full justify-center">
                                            <i class="fa-solid fa-xmark mr-2"></i> ถูกปฏิเสธ / ส่งแก้ไข
                                        </span>
                                    @break

                                    @default
                                        <span
                                            class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-bold bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 w-full justify-center">
                                            ไม่ทราบสถานะ
                                        </span>
                                @endswitch
                            </div>

                            @if (!empty($upload->comment))
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <label
                                        class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2 flex items-center gap-2">
                                        <i class="fa-solid fa-comment-dots text-orange-500"></i> ข้อเสนอแนะ/เหตุผล
                                    </label>
                                    <div
                                        class="w-full px-3 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 whitespace-pre-wrap">
                                        {{ $upload->comment ?? '-' }}</div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <div x-show="showPdf" x-transition.opacity style="display: none;"
            class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div @click.away="showPdf = false"
                class="bg-white dark:bg-gray-800 rounded-2xl overflow-hidden w-full max-w-5xl h-[90vh] flex flex-col shadow-2xl">
                <div class="flex justify-between items-center bg-blue-600 text-white px-6 py-4">
                    <h2 class="font-bold text-lg flex items-center gap-2">
                        <i class="fa-solid fa-file-pdf"></i> <span x-text="pdfTitle"></span>
                    </h2>
                    <button @click="showPdf = false; pdfUrl=''"
                        class="text-white/80 hover:text-white transition-colors">
                        <i class="fa-solid fa-xmark text-2xl"></i>
                    </button>
                </div>
                <div class="flex-1 w-full bg-gray-100 dark:bg-gray-900">
                    <iframe :src="pdfUrl" class="w-full h-full border-0" frameborder="0"></iframe>
                </div>
            </div>
        </div>

        <div x-show="showImg" x-transition.opacity style="display: none;"
            class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
            <div @click.away="showImg = false"
                class="bg-white dark:bg-gray-800 rounded-2xl overflow-hidden w-full max-w-3xl shadow-2xl">
                <div class="flex justify-between items-center bg-gray-900 text-white px-6 py-4">
                    <h2 class="font-bold text-lg flex items-center gap-2">
                        <i class="fa-solid fa-image"></i> หน้าปก
                    </h2>
                    <button @click="showImg = false" class="text-white/80 hover:text-white transition-colors">
                        <i class="fa-solid fa-xmark text-2xl"></i>
                    </button>
                </div>
                <div
                    class="p-4 bg-gray-100 dark:bg-gray-900 flex justify-center items-center overflow-auto max-h-[80vh]">
                    <img :src="imgUrl" alt="preview" class="max-w-full h-auto rounded-lg shadow-sm">
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
