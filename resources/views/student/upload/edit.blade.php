<x-app-layout>
    <div class="p-6 max-w-6xl mx-auto mt-4" x-data="{ showPdf: false, pdfUrl: '', pdfTitle: '', showImg: false, imgUrl: '' }">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-file-pen text-orange-500"></i> แก้ไขไฟล์โครงงานวิจัย
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                    อัปเดตไฟล์โครงงานฉบับสมบูรณ์ รูปหน้าปก หรือแก้ไขคำสำคัญ
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

                <form id="editForm" action="{{ route('student.upload.update', $upload->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                        <div class="lg:col-span-2 space-y-6">

                            <div
                                class="bg-gray-50 dark:bg-gray-900/50 p-5 rounded-xl border border-gray-200 dark:border-gray-700">
                                <label for="cover_file"
                                    class="block text-sm font-bold text-gray-800 dark:text-gray-200 tracking-wide flex items-center gap-2 mb-2">
                                    <i class="fa-solid fa-image text-emerald-500"></i> อัปโหลดหน้าปกใหม่ (รูปภาพ)
                                </label>
                                <input id="cover_file" name="cover_file" type="file" accept="image/*"
                                    class="w-full px-3 py-2 text-sm text-gray-900 bg-white border border-gray-200 rounded-lg cursor-pointer focus:outline-none focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-colors shadow-sm">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 pl-1">*
                                    ปล่อยว่างไว้หากต้องการใช้ไฟล์เดิม</p>

                                <div id="cover_new_preview_wrap" class="mt-4 hidden">
                                    <p
                                        class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">
                                        ตัวอย่างหน้าปกใหม่ที่จะแทนที่:</p>
                                    <div class="relative inline-block">
                                        <img id="cover_new_preview"
                                            class="max-h-48 rounded-lg border border-gray-200 dark:border-gray-600 shadow-sm"
                                            alt="พรีวิวหน้าปกใหม่">
                                        <button type="button" id="cover_new_remove" title="ยกเลิกการเลือกไฟล์"
                                            class="absolute -top-3 -right-3 w-8 h-8 bg-rose-500 text-white rounded-full flex items-center justify-center hover:bg-rose-600 shadow-md transition-colors border-2 border-white dark:border-gray-800">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                    <div id="cover_new_meta"
                                        class="mt-2 text-xs font-medium text-gray-500 dark:text-gray-400"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between items-end mb-2">
                                    <label for="abstract"
                                        class="block text-sm font-bold text-gray-800 dark:text-gray-200 tracking-wide flex items-center gap-2">
                                        <i class="fa-solid fa-file-pdf text-rose-500"></i> อัปโหลดบทคัดย่อใหม่ (PDF)
                                    </label>
                                    @if ($file?->abstract_file)
                                        <button type="button"
                                            @click="pdfUrl='{{ route('student.upload.preview', ['upload' => $upload->id, 'type' => 'abstract']) }}' + '?v=' + Date.now(); pdfTitle='บทคัดย่อปัจจุบัน'; showPdf=true"
                                            class="text-xs font-bold text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors flex items-center gap-1 bg-blue-50 dark:bg-blue-500/10 px-2 py-1 rounded-md">
                                            <i class="fa-solid fa-eye"></i> ดูไฟล์ปัจจุบัน
                                        </button>
                                    @endif
                                </div>
                                <input name="abstract" id="abstract" type="file" accept=".pdf"
                                    class="w-full px-3 py-2 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-lg cursor-pointer focus:outline-none focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 transition-colors shadow-sm">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 pl-1">*
                                    ปล่อยว่างไว้หากต้องการใช้ไฟล์เดิม</p>
                            </div>

                            <div>
                                <div class="flex justify-between items-end mb-2">
                                    <label for="project_file"
                                        class="block text-sm font-bold text-gray-800 dark:text-gray-200 tracking-wide flex items-center gap-2">
                                        <i class="fa-solid fa-file-pdf text-rose-500"></i> อัปโหลดไฟล์โครงงานใหม่ (PDF)
                                    </label>
                                    @if ($file?->project_file)
                                        <button type="button"
                                            @click="pdfUrl='{{ route('student.upload.preview', ['upload' => $upload->id, 'type' => 'project']) }}' + '?v=' + Date.now(); pdfTitle='ไฟล์โครงงานปัจจุบัน'; showPdf=true"
                                            class="text-xs font-bold text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors flex items-center gap-1 bg-blue-50 dark:bg-blue-500/10 px-2 py-1 rounded-md">
                                            <i class="fa-solid fa-eye"></i> ดูไฟล์ปัจจุบัน
                                        </button>
                                    @endif
                                </div>
                                <input name="project_file" id="project_file" type="file" accept=".pdf"
                                    class="w-full px-3 py-2 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-lg cursor-pointer focus:outline-none focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 transition-colors shadow-sm">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 pl-1">*
                                    ปล่อยว่างไว้หากต้องการใช้ไฟล์เดิม</p>
                            </div>

                            <div>
                                <label for="keyword"
                                    class="block text-sm font-bold text-gray-800 dark:text-gray-200 tracking-wide flex items-center gap-2 mb-2">
                                    <i class="fa-solid fa-key text-blue-500"></i> คำสำคัญ (Keywords) <span
                                        class="text-rose-500">*</span>
                                </label>
                                <input id="keyword" name="keyword" type="text" required
                                    class="w-full px-4 py-3 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-lg focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white transition-colors shadow-sm"
                                    value="{{ old('keyword', $upload->keyword) }}" />
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
                                            <i class="fa-solid fa-comment-dots text-orange-500"></i>
                                            ข้อเสนอแนะจากอาจารย์
                                        </label>
                                        <div
                                            class="w-full px-3 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 whitespace-pre-wrap">
                                            {{ $upload->comment ?? '-' }}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div
                                class="bg-gray-50 dark:bg-gray-900/50 p-5 rounded-xl border border-gray-200 dark:border-gray-700">
                                <h3
                                    class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                                    <i class="fa-solid fa-image"></i> รูปหน้าปกปัจจุบัน
                                </h3>
                                @if ($coverPreviewUrl)
                                    <div class="relative group cursor-zoom-in"
                                        @click="imgUrl='{{ $coverPreviewUrl }}'; showImg=true">
                                        <img src="{{ $coverPreviewUrl }}" alt="หน้าปกปัจจุบัน"
                                            class="w-full object-cover rounded-lg border border-gray-200 dark:border-gray-600 shadow-sm transition-transform duration-300 group-hover:scale-[1.02]">
                                        <div
                                            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-lg">
                                            <i class="fa-solid fa-magnifying-glass-plus text-white text-2xl"></i>
                                        </div>
                                    </div>
                                @else
                                    <div
                                        class="h-40 flex flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-400">
                                        <i class="fa-regular fa-image text-3xl mb-2"></i>
                                        <span class="text-sm font-medium">ไม่มีรูปหน้าปก</span>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>

                    <div
                        class="border-t border-gray-100 dark:border-gray-700 pt-6 mt-8 flex justify-end gap-3 items-center">
                        <a href="{{ route('student.upload.index') }}"
                            class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">
                            ยกเลิก
                        </a>
                        <button type="submit" id="submitBtn"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-orange-500 rounded-lg hover:bg-orange-600 transition-colors flex items-center gap-2 shadow-sm">
                            <i class="fa-solid fa-save"></i> บันทึกการแก้ไข
                        </button>
                    </div>

                </form>

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
                    <button @click="showPdf = false" class="text-white/80 hover:text-white transition-colors">
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

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const form = document.getElementById('editForm');
                const keywordInput = document.querySelector('input[name="keyword"]');
                const submitBtn = document.getElementById('submitBtn');

                // ===== พรีวิวรูปใหม่ของหน้าปก =====
                const coverInput = document.getElementById('cover_file');
                const previewWrap = document.getElementById('cover_new_preview_wrap');
                const previewImg = document.getElementById('cover_new_preview');
                const previewMeta = document.getElementById('cover_new_meta');
                const removeBtn = document.getElementById('cover_new_remove');
                let currentUrl = null;

                function bytesToSize(bytes) {
                    const s = ['Bytes', 'KB', 'MB', 'GB'];
                    if (!bytes) return '0 Byte';
                    const i = Math.floor(Math.log(bytes) / Math.log(1024));
                    return Math.round(bytes / Math.pow(1024, i)) + ' ' + s[i];
                }

                function clearPreview() {
                    if (currentUrl) URL.revokeObjectURL(currentUrl);
                    currentUrl = null;
                    previewImg.removeAttribute('src');
                    previewMeta.textContent = '';
                    previewWrap.classList.add('hidden');
                    if (coverInput) coverInput.value = '';
                }

                function showPreview(file) {
                    if (!file.type.startsWith('image/')) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'ไฟล์ไม่ใช่รูปภาพ',
                            text: 'กรุณาเลือกเฉพาะไฟล์รูปภาพ',
                            confirmButtonColor: '#f97316'
                        });
                        clearPreview();
                        return;
                    }
                    const maxSize = 10 * 1024 * 1024; // 10MB
                    if (file.size > maxSize) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'ไฟล์ใหญ่เกินไป',
                            text: 'ขนาดสูงสุดไม่เกิน 10MB',
                            confirmButtonColor: '#f97316'
                        });
                        clearPreview();
                        return;
                    }
                    if (currentUrl) URL.revokeObjectURL(currentUrl);
                    currentUrl = URL.createObjectURL(file);
                    previewImg.src = currentUrl;
                    previewMeta.textContent = `${file.name} • ${bytesToSize(file.size)}`;
                    previewWrap.classList.remove('hidden');
                }

                if (coverInput) {
                    coverInput.addEventListener('change', e => {
                        const file = e.target.files?.[0];
                        if (!file) {
                            clearPreview();
                            return;
                        }
                        showPreview(file);
                    });
                }

                if (removeBtn) removeBtn.addEventListener('click', clearPreview);

                // ===== ระบบ Submit ผ่าน Ajax (Fetch) =====
                if (form) {
                    form.addEventListener('submit', (e) => {
                        e.preventDefault();

                        if (!keywordInput.value.trim()) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'กรุณากรอกคำสำคัญ',
                                confirmButtonColor: '#f97316'
                            });
                            return;
                        }

                        // เปลี่ยนปุ่มเป็น Loading
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> กำลังอัปเดต...';
                        submitBtn.classList.add('opacity-70', 'cursor-not-allowed');

                        const fd = new FormData(form);
                        fd.append('_method', 'PUT');

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
                                    if (data.errors) {
                                        const first = Object.values(data.errors)[0]?.[0] ??
                                            'อัปเดตไม่สำเร็จ';
                                        throw new Error(first);
                                    }
                                    throw new Error(data.message || 'อัปเดตไม่สำเร็จ');
                                }
                                return data;
                            })
                            .then(() => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'อัปเดตข้อมูลสำเร็จ',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => window.location.href =
                                    "{{ route('student.upload.index') }}");
                            })
                            .catch(err => {
                                // คืนค่าปุ่มกลับมา
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = '<i class="fa-solid fa-save"></i> บันทึกการแก้ไข';
                                submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');

                                Swal.fire({
                                    icon: 'error',
                                    title: 'ผิดพลาด',
                                    text: err.message || 'โปรดลองอีกครั้ง',
                                    confirmButtonColor: '#f97316'
                                });
                            });
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
