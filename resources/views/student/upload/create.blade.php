<x-app-layout>
    <div class="p-6 max-w-4xl mx-auto mt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-cloud-arrow-up text-orange-500"></i> อัปโหลดไฟล์โครงงานฉบับสมบูรณ์
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                    อัปโหลดรูปหน้าปก บทคัดย่อ และไฟล์โครงงานฉบับสมบูรณ์ของคุณ
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
                        {{ $proposes->title ?? 'ไม่พบข้อมูลชื่อโครงงาน' }}
                    </p>
                </div>

                <form id="uploadForm" action="{{ route('student.upload.store', ['proposeId' => $proposes->id]) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-6">

                        <div
                            class="bg-gray-50 dark:bg-gray-900/50 p-5 rounded-xl border border-gray-200 dark:border-gray-700">
                            <label for="cover_file"
                                class="block text-sm font-bold text-gray-800 dark:text-gray-200 tracking-wide flex items-center gap-2 mb-2">
                                <i class="fa-solid fa-image text-emerald-500"></i> หน้าปก (รูปภาพ) <span
                                    class="text-rose-500">*</span>
                            </label>
                            <input id="cover_file" name="cover_file" type="file" accept="image/*" required
                                class="w-full md:w-2/3 px-3 py-2 text-sm text-gray-900 bg-white border border-gray-200 rounded-lg cursor-pointer focus:outline-none focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-colors shadow-sm">

                            <div class="mt-4 hidden" id="cover_preview_wrap">
                                <p
                                    class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">
                                    ตัวอย่างหน้าปก:</p>
                                <div class="relative inline-block">
                                    <img id="cover_preview"
                                        class="max-h-64 rounded-lg border border-gray-200 dark:border-gray-600 shadow-sm"
                                        alt="ตัวอย่างรูปหน้าปก">
                                    <button type="button" id="cover_remove_btn" title="ลบรูปภาพ"
                                        class="absolute -top-3 -right-3 w-8 h-8 bg-rose-500 text-white rounded-full flex items-center justify-center hover:bg-rose-600 shadow-md transition-colors border-2 border-white dark:border-gray-800">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                                <div class="mt-2 text-xs font-medium text-gray-500 dark:text-gray-400" id="cover_meta">
                                </div>
                            </div>

                            @error('cover_file')
                                <p class="text-rose-500 text-xs mt-2 flex items-center gap-1"><i
                                        class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="abstract"
                                class="block text-sm font-bold text-gray-800 dark:text-gray-200 tracking-wide flex items-center gap-2 mb-2">
                                <i class="fa-solid fa-file-pdf text-rose-500"></i> บทคัดย่อ (PDF) <span
                                    class="text-rose-500">*</span>
                            </label>
                            <input name="abstract" id="abstract" type="file" accept=".pdf" required
                                class="w-full md:w-2/3 px-3 py-2 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-lg cursor-pointer focus:outline-none focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 transition-colors shadow-sm">
                            @error('abstract')
                                <p class="text-rose-500 text-xs mt-2 flex items-center gap-1"><i
                                        class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="project_file"
                                class="block text-sm font-bold text-gray-800 dark:text-gray-200 tracking-wide flex items-center gap-2 mb-2">
                                <i class="fa-solid fa-file-pdf text-rose-500"></i> ไฟล์โครงงาน <span
                                    class="text-gray-500 dark:text-gray-400 font-normal text-xs ml-1">(บทที่ 1–5 รวมคำนำ
                                    สารบัญ ฯลฯ)</span> <span class="text-rose-500">*</span>
                            </label>
                            <input name="project_file" id="project_file" type="file" accept=".pdf" required
                                class="w-full md:w-2/3 px-3 py-2 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-lg cursor-pointer focus:outline-none focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 transition-colors shadow-sm">
                            <p class="mt-2 text-xs font-medium text-rose-500 dark:text-rose-400 pl-1">
                                * กรุณาอัปโหลดไฟล์บทคัดย่อและไฟล์โครงงานที่มีนามสกุล .pdf เท่านั้น (ขนาดไม่เกิน 50MB)
                            </p>
                            @error('project_file')
                                <p class="text-rose-500 text-xs mt-2 flex items-center gap-1"><i
                                        class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="keyword"
                                class="block text-sm font-bold text-gray-800 dark:text-gray-200 tracking-wide flex items-center gap-2 mb-2">
                                <i class="fa-solid fa-key text-blue-500"></i> คำสำคัญ (Keywords) <span
                                    class="text-rose-500">*</span>
                            </label>
                            <input id="keyword" name="keyword" type="text" value="{{ old('keyword') }}" required
                                placeholder="เช่น Web Application, IoT, AI..."
                                class="w-full px-4 py-3 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-lg focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white transition-colors shadow-sm">
                            @error('keyword')
                                <p class="text-rose-500 text-xs mt-2 flex items-center gap-1"><i
                                        class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                            @enderror
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
                            <i class="fa-solid fa-cloud-arrow-up"></i> อัปโหลดไฟล์
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // ====== พรีวิวรูปหน้าปก ======
                const coverInput = document.getElementById('cover_file');
                const previewWrap = document.getElementById('cover_preview_wrap');
                const previewImg = document.getElementById('cover_preview');
                const previewMeta = document.getElementById('cover_meta');
                const removeBtn = document.getElementById('cover_remove_btn');
                let currentObjectUrl = null;

                function bytesToSize(bytes) {
                    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                    if (bytes === 0) return '0 Byte';
                    const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)), 10);
                    return Math.round(bytes / Math.pow(1024, i)) + ' ' + sizes[i];
                }

                function clearPreview() {
                    if (currentObjectUrl) {
                        URL.revokeObjectURL(currentObjectUrl);
                        currentObjectUrl = null;
                    }
                    previewImg.removeAttribute('src');
                    previewMeta.textContent = '';
                    previewWrap.classList.add('hidden');
                    coverInput.value = ''; // ล้างไฟล์ใน input
                }

                function showPreview(file) {
                    // ตรวจชนิดไฟล์รูปภาพ
                    if (!file.type.startsWith('image/')) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'ไฟล์ไม่ใช่รูปภาพ',
                            text: 'กรุณาเลือกไฟล์รูปภาพเท่านั้น',
                            confirmButtonColor: '#f97316'
                        });
                        clearPreview();
                        return;
                    }

                    // จำกัดขนาดไฟล์ (10MB)
                    const maxSize = 10 * 1024 * 1024;
                    if (file.size > maxSize) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'ไฟล์ใหญ่เกินไป',
                            text: 'ขนาดรููปภาพสูงสุดไม่เกิน 10MB',
                            confirmButtonColor: '#f97316'
                        });
                        clearPreview();
                        return;
                    }

                    if (currentObjectUrl) URL.revokeObjectURL(currentObjectUrl);
                    currentObjectUrl = URL.createObjectURL(file);

                    previewImg.src = currentObjectUrl;
                    previewMeta.textContent = `${file.name} (${bytesToSize(file.size)})`;
                    previewWrap.classList.remove('hidden');
                }

                if (coverInput) {
                    coverInput.addEventListener('change', (e) => {
                        const file = e.target.files?.[0];
                        if (!file) {
                            clearPreview();
                            return;
                        }
                        showPreview(file);
                    });
                }

                if (removeBtn) {
                    removeBtn.addEventListener('click', clearPreview);
                }

                // =====================================
                // แจ้งเตือนความสำเร็จและ Error ด้วย SweetAlert
                // =====================================
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
                        text: 'กรุณาตรวจสอบความถูกต้องของข้อมูลหรือไฟล์ที่อัปโหลด',
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#f97316'
                    });
                @endif

                @if (session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: "{{ session('error') }}",
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#f97316'
                    });
                @endif

                // ป้องกันการกด Submit ซ้ำรัวๆ
                const form = document.getElementById('uploadForm');
                const submitBtn = document.getElementById('submitBtn');
                if (form) {
                    form.addEventListener('submit', function() {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> กำลังอัปโหลด...';
                        submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
