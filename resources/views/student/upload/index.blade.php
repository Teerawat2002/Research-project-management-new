<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto mt-4" x-data="{ showPdf: false, pdfUrl: '', pdfTitle: '' }">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-file-arrow-up text-orange-500"></i> รายการอัปโหลดไฟล์โครงงาน
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                    จัดการเอกสารโครงงานฉบับสมบูรณ์และไฟล์อื่นๆ ที่เกี่ยวข้อง
                </p>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto mt-2 md:mt-0">
                @if (!$hasUpload)
                    <a href="{{ route('student.upload.create', ['proposeId' => $proposes->id]) }}"
                        class="inline-flex items-center justify-center px-5 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium rounded-lg shadow-sm transition-colors w-full sm:w-auto">
                        <i class="fa-solid fa-plus mr-2"></i> อัปโหลดไฟล์
                    </a>
                @else
                    <span
                        class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-medium bg-gray-100 text-gray-500 border border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 cursor-not-allowed w-full sm:w-auto shadow-sm">
                        <i class="fa-solid fa-circle-check mr-2"></i> คุณได้อัปโหลดไฟล์แล้ว (แก้ไขได้ในรายการ)
                    </span>
                @endif
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-200 overflow-hidden">

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400 transition-colors duration-200">
                    <thead
                        class="text-xs text-gray-400 uppercase bg-gray-50/50 dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 transition-colors duration-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold min-w-[250px]">ชื่อโครงงาน</th>
                            <th scope="col" class="px-6 py-4 font-semibold min-w-[180px]">อาจารย์ที่ปรึกษา</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center w-36">สถานะ</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center w-36">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($uploads as $upload)
                            @php
                                $propose = $upload->revision->exam_submission->propose ?? null;
                                $advisor = $propose?->advisor ?? null;
                            @endphp
                            <tr
                                class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors duration-200 group bg-white dark:bg-gray-800">

                                <td class="px-6 py-4">
                                    <div
                                        class="font-medium text-gray-900 dark:text-white text-sm group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors line-clamp-2">
                                        {{ $propose->title ?? '-' }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-gray-700 dark:text-gray-200">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-6 h-6 rounded-full bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400 flex items-center justify-center shrink-0">
                                            <i class="fa-solid fa-user-tie text-[10px]"></i>
                                        </div>
                                        <span class="truncate">
                                            {{ trim(($advisor->a_fname ?? '') . ' ' . ($advisor->a_lname ?? '')) ?: '-' }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @switch($upload->status)
                                        @case(0)
                                            <span
                                                class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 w-28">
                                                <i class="fa-solid fa-check-double mr-1.5"></i> อนุมัติแล้ว
                                            </span>
                                        @break

                                        @case(1)
                                            <span
                                                class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 w-28">
                                                <i class="fa-solid fa-hourglass-half mr-1.5"></i> รอการอนุมัติ
                                            </span>
                                        @break

                                        @case(2)
                                            <span
                                                class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400 w-28">
                                                <i class="fa-solid fa-xmark mr-1.5"></i> ถูกปฏิเสธ
                                            </span>
                                        @break

                                        @default
                                            <span
                                                class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 w-28">
                                                <i class="fa-solid fa-circle-question mr-1.5"></i> ไม่ทราบสถานะ
                                            </span>
                                    @endswitch
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @if ($upload->status == 1)
                                            <button type="button" title="แก้ไข"
                                                onclick="window.location.href='{{ route('student.upload.edit', ['upload' => $upload->id]) }}'"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-400 hover:bg-orange-50 hover:text-orange-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-500 dark:hover:bg-gray-700 transition-colors">
                                                <i class="fa-solid fa-pen text-xs"></i>
                                            </button>
                                            <button type="button" title="ดูรายละเอียด"
                                                onclick="window.location.href='{{ route('student.upload.show', ['upload' => $upload->id]) }}'"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-400 hover:bg-blue-50 hover:text-blue-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-500 dark:hover:bg-gray-700 transition-colors">
                                                <i class="fa-solid fa-eye text-xs"></i>
                                            </button>
                                        @elseif ($upload->status == 2)
                                            <button type="button" title="แก้ไข"
                                                onclick="window.location.href='{{ route('student.upload.edit', ['upload' => $upload->id]) }}'"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-400 hover:bg-orange-50 hover:text-orange-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-500 dark:hover:bg-gray-700 transition-colors">
                                                <i class="fa-solid fa-pen text-xs"></i>
                                            </button>
                                        @else
                                            <button type="button" title="ดูรายละเอียด"
                                                onclick="window.location.href='{{ route('student.upload.show', ['upload' => $upload->id]) }}'"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-400 hover:bg-blue-50 hover:text-blue-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-500 dark:hover:bg-gray-700 transition-colors">
                                                <i class="fa-solid fa-eye text-xs"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <i
                                                class="fa-solid fa-folder-open text-5xl text-gray-300 dark:text-gray-600 mb-3"></i>
                                            <p class="text-sm font-medium">ยังไม่มีการอัปโหลดไฟล์</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        @push('scripts')
            <script>
                document.addEventListener("DOMContentLoaded", function() {

                    // สคริปต์สำหรับปุ่มลบ (หากเปิดใช้งาน .delete-btn)
                    document.querySelectorAll('.delete-btn').forEach(button => {
                        button.addEventListener('click', function() {
                            const form = this.closest('.delete-form');
                            const title = this.getAttribute('data-title');

                            Swal.fire({
                                title: 'คุณแน่ใจหรือไม่?',
                                html: `<div class="text-base mt-2">ลบรายการ<br><span class="text-rose-500 font-bold">${title}</span><br><span class="text-sm text-gray-500 mt-2 block">ข้อมูลที่ลบจะไม่สามารถกู้คืนได้!!!</span></div>`,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#ef4444',
                                cancelButtonColor: '#9ca3af',
                                confirmButtonText: 'ใช่, ลบเลย!',
                                cancelButtonText: 'ยกเลิก'
                            }).then(result => {
                                if (result.isConfirmed) form.submit();
                            });
                        });
                    });

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

                });
            </script>
        @endpush
    </x-app-layout>
