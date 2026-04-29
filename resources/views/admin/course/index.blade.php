<x-app-layout>
    <div class="p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200">
                    Course Management
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                    จัดการรายวิชาโครงงาน
                </p>
            </div>

            <div class="flex gap-2">
                <button type="button" data-modal-target="createCourseModal" data-modal-toggle="createCourseModal"
                    class="bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg text-sm px-4 py-2 flex items-center shadow-sm transition-colors duration-200">
                    <i class="fa-solid fa-plus mr-2"></i> เพิ่มรายวิชา
                </button>
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors duration-200">

            <div class="overflow-x-auto rounded-t-xl">
                <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400 transition-colors duration-200">
                    <thead
                        class="text-xs text-gray-500 uppercase bg-gray-50/50 border-b border-gray-100 dark:bg-gray-700/50 dark:border-gray-700 dark:text-gray-400 transition-colors duration-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-medium w-24">No.</th>
                            {{-- <th scope="col" class="px-6 py-4 font-medium w-32">ID</th> --}}
                            <th scope="col" class="px-6 py-4 font-medium">ชื่อรายวิชา</th>
                            <th scope="col" class="px-6 py-4 font-medium text-right w-32">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @forelse ($courses as $course)
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700 transition-colors duration-200 group">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                    {{ $loop->iteration + ($courses->currentPage() - 1) * $courses->perPage() }}
                                </td>
                                {{-- <td class="px-6 py-4 font-medium text-gray-500 dark:text-gray-400">
                                    {{ $course->id }}
                                </td> --}}
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    {{ $course->name }}
                                </td>
                                <td class="px-6 py-4 text-right space-x-3 text-gray-400 dark:text-gray-500">
                                    <button type="button" title="แก้ไข"
                                        class="hover:text-blue-500 dark:hover:text-blue-400 transition-colors btn-edit-course"
                                        data-modal-target="editCourseModal" data-modal-toggle="editCourseModal"
                                        data-id="{{ $course->id }}" data-name="{{ $course->name }}">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <form action="{{ route('admin.course.delete', $course->id) }}" method="POST"
                                        class="delete-form inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" title="ลบ"
                                            class="delete-button hover:text-red-500 dark:hover:text-red-400 transition-colors"
                                            data-topic="{{ $course->name }}">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-8 text-center text-gray-500 dark:text-gray-400" colspan="4">
                                    <div class="flex flex-col items-center justify-center">
                                        <i
                                            class="fa-solid fa-folder-open text-4xl text-gray-200 dark:text-gray-600 mb-2"></i>
                                        <p>ยังไม่มีรายวิชาโครงงาน</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($courses->hasPages())
                <div
                    class="p-4 border-t border-gray-100 bg-gray-50 dark:border-gray-700 dark:bg-gray-800 transition-colors duration-200">
                    {{ $courses->links() }}
                </div>
            @endif
        </div>
    </div>

    <div id="createCourseModal" tabindex="-1" aria-hidden="true"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto inset-0 h-modal h-full bg-black bg-opacity-50 flex items-center justify-center transition-opacity duration-300">
        <div class="relative w-full max-w-md max-h-full">
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl transition-colors duration-200">
                <div
                    class="flex items-center justify-between px-6 py-4 border-b dark:border-gray-700 rounded-t-2xl bg-gray-50 dark:bg-gray-700/50">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-orange-100 dark:bg-orange-500/20 rounded-full flex items-center justify-center text-orange-500 dark:text-orange-400">
                            <i class="fa-solid fa-folder-plus"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">เพิ่มรายวิชาโครงงาน</h3>
                    </div>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:text-gray-900 dark:hover:text-white rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        data-modal-hide="createCourseModal">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                @include('admin.course.partials.create-form')
            </div>
        </div>
    </div>

    <div id="editCourseModal" tabindex="-1" aria-hidden="true"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto inset-0 h-modal h-full bg-black bg-opacity-50 flex items-center justify-center transition-opacity duration-300">
        <div class="relative w-full max-w-md max-h-full">
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl transition-colors duration-200">
                <div
                    class="flex items-center justify-between px-6 py-4 border-b dark:border-gray-700 rounded-t-2xl bg-gray-50 dark:bg-gray-700/50">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-orange-100 dark:bg-orange-500/20 rounded-full flex items-center justify-center text-orange-500 dark:text-orange-400">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">แก้ไขข้อมูลรายวิชา</h3>
                    </div>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:text-gray-900 dark:hover:text-white rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        data-modal-hide="editCourseModal">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                @include('admin.course.partials.edit-form')
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                // การดึงข้อมูลลง Edit Modal
                const editButtons = document.querySelectorAll('.btn-edit-course');
                const editForm = document.getElementById('editCourseForm');
                const updateBaseUrl = "{{ url('admin/course/course') }}"; // URL ตาม route ของคุณ

                editButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        document.getElementById('edit_name').value = this.getAttribute('data-name');

                        if (editForm) {
                            editForm.action = updateBaseUrl + '/' + id;
                        }
                    });
                });

                // SweetAlert2 สำหรับปุ่ม Delete
                document.querySelectorAll('.delete-button').forEach(function(button) {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const form = this.closest('form');
                        const courseName = this.getAttribute('data-topic');

                        Swal.fire({
                            title: 'คุณต้องการลบรายวิชา "' + courseName + '" ใช่หรือไม่?',
                            text: "เมื่อลบแล้วข้อมูลจะหายไป!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#f97316', // สีส้ม
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

                // ตรวจสอบ error / validation
                @if (session('error') || $errors->any())
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: "{{ session('error') ?? $errors->first() }}",
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#f97316'
                    });
                @endif
            });
        </script>
    @endpush
</x-app-layout>
