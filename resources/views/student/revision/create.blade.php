<x-app-layout>
    <div class="p-6 max-w-4xl mx-auto mt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-file-pen text-orange-500"></i> สร้างรายการยื่นแก้ไข
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                    อัปโหลดไฟล์โครงงานที่แก้ไขแล้วและระบุรายละเอียดการแก้ไขตามที่คณะกรรมการเสนอแนะ
                </p>
            </div>

            <a href="{{ route('student.revision.index', $submission->id) }}"
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
                        <i class="fa-solid fa-book-open"></i> โครงงานที่ต้องการยื่นแก้ไข
                    </h3>
                    <p class="text-base font-semibold text-gray-900 dark:text-white pl-6">
                        {{ $submission->propose->title ?? 'ไม่พบข้อมูลชื่อโครงงาน' }}
                    </p>
                </div>

                <form action="{{ route('student.revision.store', ['submission' => $submission->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-6">

                        <div>
                            <label for="file_path"
                                class="block text-sm font-bold text-gray-800 dark:text-gray-200 tracking-wide flex items-center gap-2 mb-2">
                                <i class="fa-solid fa-file-pdf text-rose-500"></i> แนบไฟล์เอกสารที่แก้ไขแล้ว (PDF) <span
                                    class="text-rose-500">*</span>
                            </label>
                            <input type="file" name="file_path" id="file_path" accept=".pdf" required
                                class="w-full md:w-2/3 px-3 py-2 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-lg cursor-pointer focus:outline-none focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 transition-colors shadow-sm">
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400 pl-1">
                                รองรับไฟล์นามสกุล <span class="font-bold text-rose-500">.pdf</span> เท่านั้น
                            </p>
                            @error('file_path')
                                <p class="text-rose-500 text-xs mt-2 flex items-center gap-1"><i
                                        class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="edit_detail"
                                class="block text-sm font-bold text-gray-800 dark:text-gray-200 tracking-wide flex items-center gap-2 mb-2">
                                <i class="fa-solid fa-list-check text-blue-500"></i> รายละเอียดการแก้ไข <span
                                    class="text-rose-500">*</span>
                            </label>
                            <textarea name="edit_detail" id="edit_detail" rows="6" required
                                class="w-full px-4 py-3 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-lg focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white transition-colors shadow-sm overflow-hidden"
                                placeholder="อธิบายสิ่งที่ได้ทำการแก้ไขตามที่คณะกรรมการเสนอแนะมา เช่น 1. แก้ไขบทที่ 1 เรื่อง... 2. ปรับปรุงฐานข้อมูล...">{{ old('edit_detail') }}</textarea>
                            @error('edit_detail')
                                <p class="text-rose-500 text-xs mt-2 flex items-center gap-1"><i
                                        class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div
                        class="border-t border-gray-100 dark:border-gray-700 pt-6 mt-8 flex justify-end gap-3 items-center">
                        <a href="{{ route('student.revision.index', $submission->id) }}"
                            class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">
                            ยกเลิก
                        </a>
                        <button type="submit"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-orange-500 rounded-lg hover:bg-orange-600 transition-colors flex items-center gap-2 shadow-sm">
                            <i class="fa-solid fa-save"></i> บันทึกข้อมูล
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                // สคริปต์สำหรับให้ Textarea ยืดตามบรรทัดอัตโนมัติ
                const textarea = document.getElementById('edit_detail');
                if (textarea) {
                    const autoResize = function() {
                        this.style.height = 'auto';
                        this.style.height = this.scrollHeight + 'px';
                    };

                    textarea.addEventListener('input', autoResize);

                    // เรียกใช้ฟังก์ชันทันทีตอนโหลดหน้า เผื่อมีข้อมูลเก่า (old data) ค้างอยู่
                    autoResize.call(textarea);
                }

                // แจ้งเตือนความสำเร็จ
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: "{{ session('success') }}",
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#f97316'
                    });
                @endif

                // แจ้งเตือนข้อผิดพลาด (Validation/Error)
                @if ($errors->any())
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: "{{ $errors->first() }}",
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
            });
        </script>
    @endpush
</x-app-layout>
