<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto mt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200">
                    Committee Groups
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                    รายการกลุ่มคณะกรรมการประจำปีการศึกษา <span
                        class="font-bold text-orange-500">{{ $academicYear->year }}</span>
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('teacher.invigilator.home') }}"
                    class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200 flex items-center gap-2 text-sm font-medium">
                    <i class="fa-solid fa-arrow-left"></i> ย้อนกลับ
                </a>
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors duration-200 overflow-hidden">

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400 transition-colors duration-200">
                    <thead
                        class="text-xs text-gray-500 uppercase bg-gray-50/50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 transition-colors duration-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold w-24 text-center">
                                No.
                            </th>
                            <th scope="col" class="px-6 py-4 font-semibold">
                                <i class="fa-solid fa-layer-group mr-1"></i> ชื่อกลุ่ม
                            </th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center w-48">
                                <i class="fa-solid fa-users mr-1"></i> จำนวนกรรมการ
                            </th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center w-40">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @forelse ($groupsWithMemberCount as $groups)
                            <tr
                                class="hover:bg-orange-50/50 dark:hover:bg-gray-700/50 transition-colors duration-200 group">

                                <td class="px-6 py-5 text-center text-gray-500 dark:text-gray-400 font-medium">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-6 py-5">
                                    <div
                                        class="font-bold text-gray-900 dark:text-white text-base group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">
                                        {{ $groups->name }}
                                    </div>
                                    <div class="text-xs text-gray-400 mt-0.5">ID: {{ $groups->id }}</div>
                                </td>

                                <td class="px-6 py-5 text-center">
                                    <span
                                        class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 border border-indigo-200 dark:bg-indigo-500/10 dark:border-indigo-500/20 dark:text-indigo-400">
                                        {{ $groups->memberCount }} ท่าน
                                    </span>
                                </td>

                                <td class="px-6 py-5 text-center space-x-2 text-gray-400 dark:text-gray-500">

                                    <button type="button" title="ดูรายละเอียด"
                                        onclick="window.location.href='{{ route('teacher.invigilator.member', ['id' => $groups->id]) }}'"
                                        class="hover:text-emerald-500 dark:hover:text-emerald-400 transition-colors p-1">
                                        <i class="fa-solid fa-users-viewfinder"></i>
                                    </button>

                                    <button type="button" title="แก้ไข"
                                        onclick="window.location.href='{{ route('teacher.invigilator.edit', ['id' => $groups->id]) }}'"
                                        class="hover:text-blue-500 dark:hover:text-blue-400 transition-colors p-1">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>

                                    <form action="{{ route('teacher.invigilator.delete', $groups->id) }}" method="POST"
                                        class="delete-form inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" title="ลบ"
                                            class="delete-button hover:text-red-500 dark:hover:text-red-400 transition-colors p-1"
                                            data-topic="{{ $groups->name }}">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-16 text-center text-gray-500 dark:text-gray-400" colspan="4">
                                    <div class="flex flex-col items-center justify-center">
                                        <i
                                            class="fa-solid fa-users-slash text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                                        <p class="text-base font-medium">ไม่พบข้อมูลกลุ่มคณะกรรมการในปีการศึกษานี้</p>
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

                // ==========================================
                // Script ของ SweetAlert
                // ==========================================

                // ตรวจสอบ success message
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: "{{ session('success') }}",
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#f97316'
                    });
                @endif

                // ตรวจสอบ validation errors
                @if ($errors->any())
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: "{{ $errors->first() }}",
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#f97316'
                    });
                @endif

                // SweetAlert2 สำหรับปุ่ม Delete
                document.querySelectorAll('.delete-button').forEach(function(button) {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const form = this.closest('form');
                        const topic = this.getAttribute('data-topic');

                        Swal.fire({
                            title: 'คุณต้องการลบกลุ่มนี้ใช่หรือไม่?',
                            text: "ลบกลุ่ม: " + topic,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#f97316', // สีส้ม ตามธีม
                            cancelButtonColor: '#9ca3af', // สีเทา
                            confirmButtonText: 'ใช่, ลบเลย!',
                            cancelButtonText: 'ยกเลิก'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });

            });
        </script>
    @endpush
</x-app-layout>
