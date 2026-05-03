<x-app-layout>
    <div class="p-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200">
                    Form Management
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                    จัดการแบบฟอร์มคะแนน
                </p>
            </div>

            <div class="flex gap-2">
                <button type="button" data-modal-target="createFormModal" data-modal-toggle="createFormModal"
                    class="bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg text-sm px-4 py-2 flex items-center shadow-sm transition-colors duration-200">
                    <i class="fa-solid fa-plus mr-2"></i> เพิ่มแบบฟอร์ม
                </button>
            </div>
        </div>

        <!-- Main Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors duration-200">

            <!-- Table -->
            <div class="overflow-x-auto rounded-t-xl">
                <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400 transition-colors duration-200">
                    <thead
                        class="text-xs text-gray-500 uppercase bg-gray-50/50 border-b border-gray-100 dark:bg-gray-700/50 dark:border-gray-700 dark:text-gray-400 transition-colors duration-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-medium w-24">No.</th>
                            <th scope="col" class="px-6 py-4 font-medium w-24">ID</th>
                            <th scope="col" class="px-6 py-4 font-medium">ชื่อแบบฟอร์ม</th>
                            <th scope="col" class="px-6 py-4 font-medium">ประเภทการสอบ</th>
                            <th scope="col" class="px-6 py-4 font-medium text-right w-40">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @forelse ($formdata as $forms)
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700 transition-colors duration-200 group">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                    {{ $loop->iteration + ($formdata->currentPage() - 1) * $formdata->perPage() }}
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-500 dark:text-gray-400">
                                    {{ $forms->id }}
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    {{ $forms->name }}
                                </td>
                                <td class="px-6 py-4 text-emerald-600 dark:text-emerald-400 font-medium">
                                    {{ $forms->project_type->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-right space-x-2 text-gray-400 dark:text-gray-500">
                                    <!-- ปุ่มดูรายละเอียด (เปลี่ยนเป็นสีเขียวมินิมอล) -->
                                    <button type="button" title="ดูรายละเอียด"
                                        onclick="window.location.href='{{ route('admin.form.show', $forms->id) }}'"
                                        class="hover:text-emerald-500 dark:hover:text-emerald-400 transition-colors">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>

                                    <!-- ปุ่มจัดการหัวข้อ (เปลี่ยนเป็นรูป List) -->
                                    <button type="button" title="จัดการหัวข้อ"
                                        onclick="window.location.href='{{ route('admin.form.editTopic', $forms->id) }}'"
                                        class="hover:text-blue-500 dark:hover:text-blue-400 transition-colors">
                                        <i class="fa-solid fa-list-check"></i>
                                    </button>

                                    <form action="{{ route('admin.form.delete', $forms->id) }}" method="POST"
                                        class="delete-form inline pl-1 border-l border-gray-200 dark:border-gray-600">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" title="ลบแบบฟอร์ม"
                                            class="delete-button hover:text-red-500 dark:hover:text-red-400 transition-colors"
                                            data-topic="{{ $forms->name }}">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-8 text-center text-gray-500 dark:text-gray-400" colspan="5">
                                    <div class="flex flex-col items-center justify-center">
                                        <i
                                            class="fa-solid fa-folder-open text-4xl text-gray-200 dark:text-gray-600 mb-2"></i>
                                        <p>ไม่พบข้อมูลแบบฟอร์ม</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($formdata->hasPages())
                <div
                    class="p-4 border-t border-gray-100 bg-gray-50 dark:border-gray-700 dark:bg-gray-800 transition-colors duration-200">
                    {{ $formdata->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Create (แก้ไขจากโค้ดเดิมของคุณ ให้เป็น Modal เข้าธีม) -->
    <div id="createFormModal" tabindex="-1" aria-hidden="true"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto inset-0 h-modal h-full bg-black bg-opacity-50 flex items-center justify-center transition-opacity duration-300">
        <div class="relative w-full max-w-md max-h-full">
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl transition-colors duration-200">
                <div
                    class="flex items-center justify-between px-6 py-4 border-b dark:border-gray-700 rounded-t-2xl bg-gray-50 dark:bg-gray-700/50">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-orange-100 dark:bg-orange-500/20 rounded-full flex items-center justify-center text-orange-500 dark:text-orange-400">
                            <i class="fa-solid fa-file-signature"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">เพิ่มแบบฟอร์ม</h3>
                    </div>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:text-gray-900 dark:hover:text-white rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        data-modal-hide="createFormModal">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="p-6">
                    @include('admin.form.partials.create')
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                // SweetAlert2 สำหรับปุ่ม Delete
                document.querySelectorAll('.delete-button').forEach(function(button) {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const form = this.closest('form');
                        const formName = this.getAttribute('data-topic');

                        Swal.fire({
                            title: 'คุณต้องการลบแบบฟอร์ม "' + formName + '" ใช่หรือไม่?',
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
            });
        </script>
    @endpush
</x-app-layout>
