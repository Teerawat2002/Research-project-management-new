<x-app-layout>
    <div class="p-6 max-w-4xl mx-auto mt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-file-pen text-orange-500"></i> แก้ไขรายการยื่นแก้ไข
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                    อัปเดตไฟล์โครงงานและรายละเอียดการแก้ไขของคุณ
                </p>
            </div>

            <a href="{{ route('student.revision.index') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors text-sm font-medium w-full md:w-auto">
                <i class="fa-solid fa-arrow-left mr-2"></i> ย้อนกลับ
            </a>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-200 overflow-hidden">

            <div class="p-6 md:p-8">

                <form action="{{ route('student.revision.update', $revision->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="space-y-8">

                        <div
                            class="bg-gray-50 dark:bg-gray-900/50 p-5 rounded-xl border border-gray-200 dark:border-gray-700">

                            <div class="mb-5">
                                <label
                                    class="block text-sm font-bold text-gray-800 dark:text-gray-200 tracking-wide flex items-center gap-2 mb-2">
                                    <i class="fa-regular fa-file-pdf text-gray-500"></i> ไฟล์เอกสารปัจจุบัน
                                </label>
                                <a href="{{ route('student.revision.download', ['id' => $revision->first()?->id ?? $revision->id]) }}"
                                    target="_blank"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg text-sm font-medium text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors shadow-sm">
                                    <i class="fa-solid fa-file-pdf text-rose-500"></i> ดูไฟล์ PDF ที่อัปโหลดไว้
                                </a>
                            </div>

                            <div class="border-t border-gray-200 dark:border-gray-700 pt-5">
                                <label for="file_path"
                                    class="block text-sm font-bold text-gray-800 dark:text-gray-200 tracking-wide flex items-center gap-2 mb-2">
                                    <i class="fa-solid fa-file-arrow-up text-rose-500"></i> แนบไฟล์เอกสารใหม่ <span
                                        class="text-xs font-normal text-gray-500">(ถ้าต้องการเปลี่ยน)</span>
                                </label>
                                <input type="file" name="file_path" id="file_path" accept=".pdf"
                                    class="w-full md:w-2/3 px-3 py-2 text-sm text-gray-900 bg-white border border-gray-200 rounded-lg cursor-pointer focus:outline-none focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 transition-colors shadow-sm">
                                @error('file_path')
                                    <p class="text-rose-500 text-xs mt-2 flex items-center gap-1"><i
                                            class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="edit_detail"
                                class="block text-sm font-bold text-gray-800 dark:text-gray-200 tracking-wide flex items-center gap-2 mb-2">
                                <i class="fa-solid fa-list-check text-blue-500"></i> รายละเอียดการแก้ไข <span
                                    class="text-rose-500">*</span>
                            </label>
                            <textarea name="edit_detail" id="edit_detail" rows="4" required
                                class="w-full px-4 py-3 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-lg focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white transition-colors shadow-sm overflow-hidden"
                                placeholder="อธิบายสิ่งที่ได้ทำการแก้ไขตามที่คณะกรรมการเสนอแนะมา...">{{ old('edit_detail', $revision->edit_detail) }}</textarea>
                            @error('edit_detail')
                                <p class="text-rose-500 text-xs mt-2 flex items-center gap-1"><i
                                        class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="block text-sm font-bold text-gray-800 dark:text-gray-200 tracking-wide flex items-center gap-2 mb-3">
                                <i class="fa-solid fa-user-check text-emerald-500"></i> สถานะการอนุมัติของคณะกรรมการ
                            </label>
                            <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-xl">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead
                                        class="text-xs text-gray-600 uppercase bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 font-bold">กรรมการ</th>
                                            <th scope="col" class="px-6 py-3 font-bold text-center w-40">สถานะ</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                        @forelse ($invigilators as $inv)
                                            <tr
                                                class="bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-3">
                                                        <span
                                                            class="font-medium text-gray-900 dark:text-white">{{ $inv['name'] }}</span>
                                                        @if ($inv['role'] == 1)
                                                            <span
                                                                class="bg-blue-50 text-blue-600 border border-blue-200 text-[10px] font-bold px-2 py-0.5 rounded-full dark:bg-blue-500/10 dark:border-blue-500/20 dark:text-blue-400">อาจารย์ที่ปรึกษา</span>
                                                        @elseif($inv['role'] == 2)
                                                            <span
                                                                class="bg-gray-100 text-gray-600 border border-gray-200 text-[10px] font-bold px-2 py-0.5 rounded-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">กรรมการ</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    @switch($inv['status'])
                                                        @case(null)
                                                        @case('2')
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-600 border border-amber-200 dark:bg-amber-500/10 dark:border-amber-500/20 dark:text-amber-400 whitespace-nowrap">
                                                                <i class="fa-solid fa-hourglass-half mr-1.5 opacity-70"></i>
                                                                รออนุมัติ
                                                            </span>
                                                        @break

                                                        @case('1')
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200 dark:bg-emerald-500/10 dark:border-emerald-500/20 dark:text-emerald-400 whitespace-nowrap">
                                                                <i class="fa-solid fa-circle-check mr-1.5 opacity-70"></i>
                                                                อนุมัติแล้ว
                                                            </span>
                                                        @break

                                                        @case('3')
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-600 border border-rose-200 dark:bg-rose-500/10 dark:border-rose-500/20 dark:text-rose-400 whitespace-nowrap">
                                                                <i class="fa-solid fa-circle-xmark mr-1.5 opacity-70"></i>
                                                                ไม่อนุมัติ
                                                            </span>
                                                        @break

                                                        @default
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 whitespace-nowrap">
                                                                ไม่ทราบสถานะ
                                                            </span>
                                                    @endswitch
                                                </td>
                                            </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="2" class="px-6 py-8 text-center text-gray-500">
                                                        ไม่พบข้อมูลกรรมการ</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>

                        <div
                            class="border-t border-gray-100 dark:border-gray-700 pt-6 mt-8 flex justify-end gap-3 items-center">
                            <a href="{{ route('student.revision.index') }}"
                                class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">
                                ยกเลิก
                            </a>
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

                    // สคริปต์สำหรับให้ Textarea ยืดตามบรรทัดอัตโนมัติ
                    const textarea = document.getElementById('edit_detail');
                    if (textarea) {
                        const autoResize = function() {
                            this.style.height = 'auto';
                            this.style.height = this.scrollHeight + 'px';
                        };

                        textarea.addEventListener('input', autoResize);

                        // เรียกใช้ฟังก์ชันทันทีตอนโหลดหน้า 
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
                            text: 'กรุณาตรวจสอบความถูกต้องของข้อมูลที่กรอก',
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
