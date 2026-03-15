<x-app-layout>
    <div class="mt-16 py-8" x-data="{ showPdf: false, pdfUrl: '', pdfTitle: '', showImg: false, imgUrl: '' }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl overflow-hidden">
                <div class="p-8 text-gray-900">
                    <h2 class="text-3xl font-bold mb-6 text-blue-700">แก้ไขไฟล์โครงงานวิจัย</h2>

                    <form id="editForm" action="{{ route('student.upload.update', $upload->id) }}" method="POST"
                        enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div>
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-800 dark:text-white">
                                {{ $propose->title ?? '-' }}
                            </h5>
                        </div>



                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                            <div class="space-y-6">
                                {{-- หน้าปก (รูปภาพ) --}}
                                <div>
                                    <x-input-default-label :value="__('อัปโหลดหน้าปก (รูปภาพ)')" />
                                    <input id="cover_file" name="cover_file" type="file" accept="image/*"
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">ปล่อยว่าง = ใช้ไฟล์เดิม</p>

                                    {{-- พรีวิวรูปใหม่หลังเลือกไฟล์ --}}
                                    <div id="cover_new_preview_wrap" class="mt-2 hidden">
                                        <img id="cover_new_preview" class="max-h-40 rounded-md border"
                                            alt="พรีวิวหน้าปกใหม่">
                                        <div id="cover_new_meta" class="mt-1 text-xs text-gray-600"></div>
                                        <button type="button" id="cover_new_remove"
                                            class="mt-2 px-3 py-1 rounded-md border text-gray-700 bg-white hover:bg-gray-100">
                                            ลบไฟล์ที่เลือก
                                        </button>
                                    </div>
                                </div>

                                {{-- บทคัดย่อ (PDF) --}}
                                <div>
                                    <x-input-default-label :value="__('บทคัดย่อ (PDF)')" />
                                    @if ($file?->abstract_file)
                                        <button type="button"
                                            @click="pdfUrl='{{ route('student.upload.preview', ['upload' => $upload->id, 'type' => 'abstract']) }}' + '?v=' + Date.now(); pdfTitle='บทคัดย่อ'; showPdf=true"
                                            class="text-blue-600 hover:text-blue-800 font-semibold mb-1">
                                            <i class="fa-solid fa-eye"></i> เปิดดู
                                        </button>
                                    @endif
                                    <input name="abstract" type="file" accept=".pdf"
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">ปล่อยว่าง = ใช้ไฟล์เดิม</p>
                                </div>

                                {{-- ไฟล์โครงงาน (PDF) --}}
                                <div>
                                    <x-input-default-label :value="__('ไฟล์โครงงาน (PDF)')" />
                                    @if ($file?->project_file)
                                        <button type="button"
                                            @click="pdfUrl='{{ route('student.upload.preview', ['upload' => $upload->id, 'type' => 'project']) }}' + '?v=' + Date.now(); pdfTitle='ไฟล์โครงงาน'; showPdf=true"
                                            class="text-blue-600 hover:text-blue-800 font-semibold mb-1">
                                            <i class="fa-solid fa-eye"></i> เปิดดู
                                        </button>
                                    @endif
                                    <input name="project_file" type="file" accept=".pdf"
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">ปล่อยว่าง = ใช้ไฟล์เดิม</p>
                                </div>

                                <div>
                                    <x-input-default-label for="keyword" :value="__('คำสำคัญ')" />
                                    <x-text-input id="keyword" name="keyword" type="text" class="mt-1 block w-full"
                                        value="{{ old('keyword', $upload->keyword) }}" />
                                </div>
                            </div>

                            <div class="md:pl-4 md:sticky md:top-6">
                                <x-input-default-label :value="__('หน้าปกปัจจุบัน (รูปภาพ)')" />
                                @if ($coverPreviewUrl)
                                    <div class="mt-2">
                                        <img src="{{ $coverPreviewUrl }}" alt="หน้าปกปัจจุบัน"
                                            class="w-full max-h-[420px] object-cover rounded-lg border shadow cursor-zoom-in"
                                            @click="imgUrl='{{ $coverPreviewUrl }}'; showImg=true">
                                        <div class="text-xs text-gray-600 dark:text-gray-300 mt-1">คลิกที่รูปเพื่อขยาย</div>
                                    </div>
                                @else
                                    <div
                                        class="mt-2 h-64 flex items-center justify-center rounded-lg border border-dashed text-gray-400">
                                        ไม่มีรูปปก
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- สถานะการอนุมัติ (คงเดิม) --}}
                        <div class="mb-2">
                            @php $status = (int) ($upload->status ?? 1); @endphp
                            <div class="mb-2 text-sm text-gray-600 dark:text-white">สถานะการอนุมัติ</div>
                            <div class="mb-4">
                                @switch($status)
                                    @case(0)
                                        <span
                                            class="px-3 py-1 text-sm rounded-md bg-green-100 text-green-800">อนุมัติแล้ว</span>
                                    @break

                                    @case(1)
                                        <span
                                            class="px-3 py-1 text-sm rounded-md bg-yellow-100 text-yellow-800">รออนุมัติ</span>
                                    @break

                                    @case(2)
                                        <span class="px-3 py-1 text-sm rounded-md bg-red-100 text-red-800">ปฏิเสธ</span>
                                    @break

                                    @default
                                        <span class="px-3 py-1 text-sm rounded-md bg-gray-100 text-gray-800">ไม่ทราบสถานะ</span>
                                @endswitch
                            </div>
                        </div>

                        <div>
                            <label for="comment" class="block text-sm font-medium text-white">ข้อเสนอแนะ/เหตุผล</label>
                            <textarea id="comment" name="comment" rows="4"
                                class="mt-1 block w-full rounded-md border border-gray-300 bg-gray-100 text-gray-800" readonly>{{ $upload->comment ?? '-' }}</textarea>
                        </div>

                        <div class="flex justify-end items-center space-x-4">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg shadow transition">
                                บันทึก
                            </button>
                            <a href="{{ route('student.upload.index') }}"
                                class="bg-red-500 hover:bg-red-600 text-white font-semibold px-6 py-2 rounded-lg shadow transition">
                                ยกเลิก
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL PDF Preview (เดิม) --}}
        <div :class="{ 'hidden': !showPdf }" x-transition.opacity
            class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center">
            <div class="bg-white rounded-xl overflow-hidden w-11/12 max-w-5xl h-[90%] flex flex-col shadow-lg">
                <div class="flex justify-between items-center bg-blue-600 text-white px-6 py-3">
                    <h2 class="font-bold text-lg" x-text="pdfTitle"></h2>
                    <button @click="showPdf = false"
                        class="text-white hover:text-gray-200 text-2xl font-bold">&times;</button>
                </div>
                <iframe :src="pdfUrl" class="flex-1 w-full" frameborder="0"></iframe>
            </div>
        </div>

        {{-- MODAL Image Preview (ใหม่) --}}
        <div :class="{ 'hidden': !showImg }" x-transition.opacity
            class="hidden fixed inset-0 z-50 bg-black/70 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl overflow-hidden w-full max-w-4xl shadow-lg">
                <div class="flex justify-between items-center bg-gray-800 text-white px-6 py-3">
                    <h2 class="font-bold text-lg">หน้าปก</h2>
                    <button @click="showImg = false"
                        class="text-white hover:text-gray-200 text-2xl font-bold">&times;</button>
                </div>
                <div class="p-4">
                    <img :src="imgUrl" alt="preview" class="max-h-[75vh] mx-auto">
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('editForm');
            const keywordInput = document.querySelector('input[name="keyword"]');

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
                        text: 'กรุณาเลือกเฉพาะไฟล์รูปภาพ'
                    });
                    clearPreview();
                    return;
                }
                const maxSize = 10 * 1024 * 1024; // 10MB
                if (file.size > maxSize) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'ไฟล์ใหญ่เกินไป',
                        text: 'ขนาดสูงสุด 10MB'
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

            // ===== ส่งฟอร์ม (คงโค้ดคุณเดิม) =====
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                if (!keywordInput.value.trim()) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'กรุณากรอกคำสำคัญ'
                    });
                    return;
                }
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
                                title: 'บันทึกการแก้ไขสำเร็จ',
                                timer: 2000,
                                showConfirmButton: false
                            })
                            .then(() => window.location.href = "{{ route('student.upload.index') }}");
                    })
                    .catch(err => {
                        Swal.fire({
                            icon: 'error',
                            title: 'ผิดพลาด',
                            text: err.message || 'โปรดลองอีกครั้ง'
                        });
                    });
            });
        });
    </script>
</x-app-layout>
