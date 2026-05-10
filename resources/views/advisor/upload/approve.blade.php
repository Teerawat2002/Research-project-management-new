<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto mt-4" x-data="{ showPdf: false, pdfUrl: '', pdfTitle: '', showImg: false, imgUrl: '' }">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div class="flex-1 min-w-0 pr-4">
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-file-signature text-orange-500 shrink-0"></i> พิจารณาอนุมัติไฟล์โครงงานวิจัย
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 leading-relaxed truncate">
                    ตรวจสอบไฟล์เอกสารรูปเล่มและพิจารณาให้การอนุมัติ
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

                    <div class="mb-8 pb-6 border-b border-gray-100 dark:border-gray-700">
                        <div
                            class="text-[11px] font-bold text-orange-500 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-book"></i> ชื่อโครงงานวิจัย
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white leading-tight mb-6">
                            {{ $propose->title ?? '-' }}
                        </h2>

                        <div
                            class="text-[11px] font-bold text-indigo-500 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-tags"></i> คำสำคัญ (Keyword)
                        </div>
                        <div
                            class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                            <p class="text-sm font-medium text-gray-800 dark:text-gray-200 leading-relaxed">
                                {{ $upload->keyword ?? 'ไม่ได้ระบุคำสำคัญ' }}
                            </p>
                        </div>
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
                                            class="bg-gray-900/70 text-white w-12 h-12 rounded-full flex items-center justify-center backdrop-blur-sm shadow-lg">
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

            <div class="lg:col-span-1">
                <form id="approvalForm" action="{{ route('advisor.upload.update', $upload->id) }}" method="POST"
                    class="sticky top-24">
                    @csrf
                    {{-- JS จะแทรก Method PUT เข้าไป --}}

                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-orange-200 dark:border-orange-500/30 p-6 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-orange-400 to-red-500">
                        </div>

                        <h3
                            class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider mb-5 mt-2 flex items-center gap-2">
                            <i class="fa-solid fa-gavel text-gray-400"></i> ผลการพิจารณา
                        </h3>

                        <div class="flex flex-col gap-3 mb-6">
                            <label
                                class="relative flex items-center p-4 rounded-xl border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-emerald-50 dark:hover:bg-emerald-500/5 transition-all group has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 dark:has-[:checked]:bg-emerald-500/10">
                                <input name="approval" type="radio" value="approved"
                                    class="w-4 h-4 text-emerald-600 bg-white border-gray-300 focus:ring-emerald-500 cursor-pointer">
                                <span
                                    class="ml-3 text-sm font-bold text-gray-700 dark:text-gray-200 group-hover:text-emerald-700 dark:group-hover:text-emerald-400">อนุมัติไฟล์เอกสาร</span>
                            </label>

                            <label
                                class="relative flex items-center p-4 rounded-xl border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-rose-50 dark:hover:bg-rose-500/5 transition-all group has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50 dark:has-[:checked]:bg-rose-500/10">
                                <input name="approval" type="radio" value="rejected"
                                    class="w-4 h-4 text-rose-600 bg-white border-gray-300 focus:ring-rose-500 cursor-pointer">
                                <span
                                    class="ml-3 text-sm font-bold text-gray-700 dark:text-gray-200 group-hover:text-rose-700 dark:group-hover:text-rose-400">ปฏิเสธ
                                    / ส่งแก้ไข</span>
                            </label>
                        </div>
                        <p id="approvalError" class="text-rose-500 text-xs mb-4 hidden"></p>

                        <div class="mb-6">
                            <label for="comment"
                                class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">
                                ข้อเสนอแนะ / เหตุผล <span
                                    class="text-rose-500 font-normal normal-case">(ควรระบุเมื่อปฏิเสธ)</span>
                            </label>
                            <textarea id="comment" name="comment" rows="4" placeholder="ระบุสิ่งที่ต้องการให้นักศึกษาแก้ไข..."
                                class="auto-expand block w-full p-4 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:placeholder-gray-500 dark:text-white transition-colors custom-scrollbar resize-none"></textarea>
                            <p id="commentError" class="text-rose-500 text-xs mt-1 hidden"></p>
                        </div>

                        <div class="flex flex-col gap-3 pt-2">
                            <button type="submit"
                                class="w-full px-5 py-3 text-sm font-bold text-white bg-orange-500 rounded-xl hover:bg-orange-600 shadow-md shadow-orange-500/20 flex items-center justify-center gap-2 transition-colors">
                                <i class="fa-solid fa-paper-plane"></i> บันทึกผลการพิจารณา
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
            document.addEventListener('DOMContentLoaded', () => {
                // Auto-expand textarea
                document.querySelectorAll('.auto-expand').forEach(textarea => {
                    const adjust = el => {
                        el.style.height = 'auto';
                        const lineHeight = parseInt(window.getComputedStyle(el).lineHeight) || 20;
                        el.style.height = (el.scrollHeight + lineHeight) + 'px';
                    };
                    setTimeout(() => adjust(textarea), 100);
                    window.addEventListener('resize', () => adjust(textarea));
                    textarea.addEventListener('input', () => adjust(textarea));
                });

                // Form Submission Logic
                const form = document.getElementById('approvalForm');

                form.addEventListener('submit', (e) => {
                    e.preventDefault();

                    const fd = new FormData(form);
                    fd.append('_method', 'PUT');

                    const approval = fd.get('approval');
                    const comment = fd.get('comment')?.trim();

                    // 1. เช็คว่าเลือก อนุมัติ หรือ ปฏิเสธ หรือยัง
                    if (!approval) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'ข้อมูลไม่ครบถ้วน',
                            text: 'กรุณาเลือกผลการพิจารณา (อนุมัติ หรือ ปฏิเสธ)',
                            confirmButtonColor: '#f97316'
                        });
                        return;
                    }

                    // 2. เช็คว่าถ้า ปฏิเสธ ต้องกรอกเหตุผล (แสดง SweetAlert)
                    if (approval === 'rejected' && !comment) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'ข้อมูลไม่ครบถ้วน',
                            text: 'กรุณาระบุข้อเสนอแนะ หรือ เหตุผลที่ปฏิเสธไฟล์นี้',
                            confirmButtonColor: '#f97316'
                        });

                        // โฟกัสไปที่กล่องข้อความให้พิมพ์ได้เลย
                        document.getElementById('comment').focus();
                        return;
                    }

                    // 3. ส่งข้อมูลไปบันทึก
                    fetch(form.action, {
                            method: 'POST',
                            body: fd,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(async (res) => {
                            const data = await res.json().catch(() => ({}));
                            if (!res.ok || data.success === false) {
                                const msg = data.errors ? Object.values(data.errors)[0][0] : (data
                                    .message || 'บันทึกไม่สำเร็จ');
                                throw new Error(msg);
                            }
                            return data;
                        })
                        .then((data) => {
                            Swal.fire({
                                icon: 'success',
                                title: data.message || 'บันทึกสำเร็จ',
                                timer: 1800,
                                showConfirmButton: false
                            }).then(() => window.location.href = "{{ route('advisor.upload.index') }}");
                        })
                        .catch(err => {
                            Swal.fire({
                                icon: 'error',
                                title: 'ผิดพลาด',
                                text: err.message || 'โปรดลองอีกครั้ง',
                                confirmButtonColor: '#f97316'
                            });
                        });
                });
            });
        </script>
    @endpush
</x-app-layout>
