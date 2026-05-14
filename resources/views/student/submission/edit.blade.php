<x-app-layout>
    <div class="p-6 max-w-4xl mx-auto mt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-orange-500"></i> แก้ไขการยื่นสอบโครงงาน
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                    อัปเดตข้อมูลและเอกสารประกอบการยื่นขอสอบโครงงานวิจัย
                </p>
            </div>

            <a href="{{ route('student.submission.index') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors text-sm font-medium w-full md:w-auto">
                <i class="fa-solid fa-arrow-left mr-2"></i> ย้อนกลับ
            </a>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-200 overflow-hidden">

            <div class="p-6 md:p-8">

                <div
                    class="bg-orange-50 dark:bg-orange-500/10 border border-orange-100 dark:border-orange-500/20 p-5 rounded-xl mb-8">

                    <div class="flex flex-col md:flex-row gap-6 mb-4">
                        <div class="flex-1">
                            <h3
                                class="text-sm font-bold text-orange-800 dark:text-orange-400 mb-1 flex items-center gap-2">
                                <i class="fa-solid fa-book-open"></i> ชื่อโครงงาน
                            </h3>
                            <p class="text-base font-semibold text-gray-900 dark:text-white pl-6">{{ $propose->title }}
                            </p>
                        </div>
                        <div class="md:w-1/3">
                            <h3
                                class="text-sm font-bold text-orange-800 dark:text-orange-400 mb-1 flex items-center gap-2">
                                <i class="fa-solid fa-laptop-code"></i> ประเภทโครงงาน
                            </h3>
                            <p class="text-sm font-medium text-gray-900 dark:text-white pl-6">
                                {{ $propose->project_type->name }}</p>
                        </div>
                    </div>

                    <div class="border-t border-orange-200/50 dark:border-orange-500/20 pt-4">
                        <h3 class="text-sm font-bold text-orange-800 dark:text-orange-400 mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-users"></i> สมาชิกกลุ่มโครงงาน
                        </h3>
                        <div class="flex flex-wrap gap-2 pl-6">
                            @forelse ($members as $member)
                                <span
                                    class="inline-flex items-center gap-2 px-3 py-1.5 bg-white dark:bg-gray-800 border border-orange-200 dark:border-orange-500/30 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 shadow-sm">
                                    <div
                                        class="w-6 h-6 rounded-full bg-orange-100 text-orange-500 dark:bg-orange-500/20 dark:text-orange-400 flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-user text-[10px]"></i>
                                    </div>
                                    {{ $member->student->s_fname }} {{ $member->student->s_lname }}
                                </span>
                            @empty
                                <span class="text-sm text-gray-500">ไม่พบสมาชิกในกลุ่มนี้</span>
                            @endforelse
                        </div>
                    </div>
                </div>

                <form method="POST"
                    action="{{ route('student.submission.update', ['submissionId' => $submission->id, 'proposeId' => $submission->propose->id]) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">

                        @if (!empty($submission->comments))
                            <div
                                class="bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-500/30 p-4 rounded-xl">
                                <label for="comments"
                                    class="block text-sm font-bold text-rose-800 dark:text-rose-400 tracking-wide flex items-center gap-2 mb-2">
                                    <i class="fa-solid fa-comment-dots"></i> ข้อเสนอแนะจากอาจารย์ / เหตุผลที่ต้องแก้ไข
                                </label>
                                <textarea name="comments" id="comments" rows="3" readonly
                                    class="w-full px-4 py-3 text-sm text-rose-900 bg-white/50 border border-rose-200 rounded-lg focus:ring-0 dark:bg-gray-900/50 dark:border-rose-500/30 dark:text-rose-200 cursor-not-allowed resize-none">{{ old('comments', $submission->comments) }}</textarea>
                            </div>
                        @endif

                        <div>
                            <label for="exam_type_id"
                                class="block text-sm font-bold text-gray-800 dark:text-gray-200 tracking-wide flex items-center gap-2 mb-2">
                                <i class="fa-solid fa-layer-group text-orange-500"></i> ประเภทรายวิชา
                            </label>
                            <select name="exam_type_id" id="exam_type_id" required
                                class="w-full md:w-1/2 px-4 py-3 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-lg focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white transition-colors cursor-pointer">
                                <option value="" disabled>-- เลือกประเภทรายวิชา --</option>
                                @foreach ($examTypes as $type)
                                    <option value="{{ $type->id }}" data-name="{{ $type->name }}"
                                        {{ old('exam_type_id', $submission->exam_type_id) == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('exam_type_id')
                                <p class="text-rose-500 text-xs mt-2 flex items-center gap-1"><i
                                        class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        <div id="attemptSection">
                            <label for="attempt"
                                class="block text-sm font-bold text-gray-800 dark:text-gray-200 tracking-wide flex items-center gap-2 mb-2">
                                <i class="fa-solid fa-hashtag text-orange-500"></i> ครั้งที่สอบ
                            </label>
                            <input type="number" name="attempt" id="attempt" min="1"
                                value="{{ old('attempt', $submission->attempt) }}"
                                placeholder="ระบุครั้งที่สอบ (เช่น 1, 2, 3)"
                                class="w-full md:w-1/2 px-4 py-3 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-lg focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white transition-colors">
                            @error('attempt')
                                <p class="text-rose-500 text-xs mt-2 flex items-center gap-1"><i
                                        class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        <div id="fileSection">
                            <div
                                class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-200 dark:border-gray-700">

                                <div class="mb-4">
                                    <label
                                        class="block text-sm font-bold text-gray-800 dark:text-gray-200 tracking-wide flex items-center gap-2 mb-2">
                                        <i class="fa-regular fa-file-pdf text-gray-500"></i> ไฟล์โครงงานปัจจุบัน
                                    </label>
                                    @if ($submission->file_path)
                                        <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg text-sm font-medium text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors shadow-sm">
                                            <i class="fa-solid fa-file-pdf text-rose-500"></i> ดูไฟล์ PDF ที่อัปโหลดไว้
                                        </a>
                                    @else
                                        <span class="text-sm text-gray-500">ยังไม่มีการอัปโหลดไฟล์</span>
                                    @endif
                                </div>

                                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                    <label for="file"
                                        class="block text-sm font-bold text-gray-800 dark:text-gray-200 tracking-wide flex items-center gap-2 mb-2">
                                        <i class="fa-solid fa-file-arrow-up text-rose-500"></i> อัพโหลดไฟล์โครงงานใหม่
                                        <span class="text-xs font-normal text-gray-500">(หากต้องการเปลี่ยน)</span>
                                    </label>
                                    <input type="file" name="file" id="file" accept=".pdf"
                                        class="w-full md:w-2/3 px-3 py-2 text-sm text-gray-900 bg-white border border-gray-200 rounded-lg cursor-pointer focus:outline-none focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 transition-colors shadow-sm">
                                    <p class="mt-2 text-xs font-medium text-orange-500 dark:text-orange-400">
                                        *** หากไม่เลือกไฟล์ใหม่ ระบบจะใช้ไฟล์เดิมโดยอัตโนมัติ ***
                                    </p>
                                    @error('file')
                                        <p class="text-rose-500 text-xs mt-2 flex items-center gap-1"><i
                                                class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                                    @enderror
                                </div>

                            </div>
                        </div>

                    </div>

                    <div
                        class="border-t border-gray-100 dark:border-gray-700 pt-6 mt-8 flex justify-end gap-3 items-center">
                        <button type="button" onclick="window.location.href='{{ route('student.submission.index') }}'"
                            class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">
                            ยกเลิก
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-orange-500 rounded-lg hover:bg-orange-600 transition-colors flex items-center gap-2 shadow-sm">
                            <i class="fa-solid fa-save"></i> บันทึกการแก้ไข
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const examTypeSelect = document.getElementById("exam_type_id");
                const attemptSection = document.getElementById("attemptSection");
                const fileSection = document.getElementById("fileSection");

                function toggleFields() {
                    const selectedOption = examTypeSelect.options[examTypeSelect.selectedIndex];
                    const typeName = selectedOption ? selectedOption.getAttribute("data-name") : "";

                    if (typeName === "Aucc") {
                        attemptSection.style.display = "none";
                        fileSection.style.display = "none";

                        document.getElementById("attempt").required = false;
                        // ตอนแก้ไขไฟล์ไม่ได้บังคับอยู่แล้ว แต่ใส่ไว้กันเหนียว
                        document.getElementById("file").required = false;
                    } else {
                        attemptSection.style.display = "block";
                        fileSection.style.display = "block";

                        document.getElementById("attempt").required = true;
                        // สำหรับหน้า Edit ไฟล์ไม่จำเป็นต้องบังคับอัปโหลดใหม่ (มีไฟล์เก่าอยู่แล้ว)
                        document.getElementById("file").required = false;
                    }
                }

                examTypeSelect.addEventListener("change", toggleFields);

                // run ครั้งแรกเพื่อจัดหน้าตาให้ตรงกับข้อมูลที่มี
                toggleFields();

                // แจ้งเตือนข้อผิดพลาด (Session Error)
                @if (session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: "{{ session('error') }}",
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#f97316'
                    });
                @endif

                // แจ้งเตือนข้อผิดพลาด (Validation)
                @if ($errors->any())
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'กรุณาตรวจสอบความถูกต้องของข้อมูลที่กรอก',
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#f97316'
                    });
                @endif

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
            });
        </script>
    @endpush
</x-app-layout>
