<x-app-layout>
    <div class="p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200">
                    Major Management
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                    จัดการข้อมูลสาขาวิชา
                </p>
            </div>

            <div class="flex gap-2">
                <button type="button" data-modal-target="createMajorModal" data-modal-toggle="createMajorModal"
                    class="bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg text-sm px-4 py-2 flex items-center shadow-sm transition-colors duration-200">
                    <i class="fa-solid fa-plus mr-2"></i> เพิ่มสาขาวิชา
                </button>
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors duration-200">

            <div
                class="p-5 border-b border-gray-50 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm rounded-t-xl transition-colors duration-200">
                <form id="filterForm" method="GET" action="{{ route('admin.major.index') }}"
                    class="flex flex-col sm:flex-row justify-between items-center gap-4">

                    <div class="relative w-full sm:w-80">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <input type="text" name="search" id="table-search" value="{{ request('search') }}"
                            class="block w-full p-2.5 pl-10 text-sm text-gray-900 border border-gray-200 rounded-lg bg-gray-50 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-orange-500 dark:focus:border-orange-500 transition-colors duration-200"
                            placeholder="ค้นหาสาขาวิชา...">
                    </div>

                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400 transition-colors duration-200">
                    <thead
                        class="text-xs text-gray-500 uppercase bg-gray-50/50 border-b border-gray-100 dark:bg-gray-700/50 dark:border-gray-700 dark:text-gray-400 transition-colors duration-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-medium w-24">No.</th>
                            <th scope="col" class="px-6 py-4 font-medium">Major name</th>
                            <th scope="col" class="px-6 py-4 font-medium text-right w-32">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @forelse ($Major as $major)
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700 transition-colors duration-200 group">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                    {{ $loop->iteration + ($Major->currentPage() - 1) * $Major->perPage() }}
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    {{ $major->m_name }}
                                </td>
                                <td class="px-6 py-4 text-right space-x-3 text-gray-400 dark:text-gray-500">
                                    <button type="button" title="แก้ไข"
                                        class="hover:text-blue-500 dark:hover:text-blue-400 transition-colors btn-edit-major"
                                        data-modal-target="editMajorModal" data-modal-toggle="editMajorModal"
                                        data-id="{{ $major->id }}" data-m_name="{{ $major->m_name }}">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <form action="{{ route('admin.major.delete', $major->id) }}" method="POST"
                                        class="delete-form inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" title="ลบ"
                                            class="delete-button hover:text-red-500 dark:hover:text-red-400 transition-colors"
                                            data-topic="{{ $major->m_name }}">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-8 text-center text-gray-500 dark:text-gray-400" colspan="3">
                                    <div class="flex flex-col items-center justify-center">
                                        <i
                                            class="fa-solid fa-folder-open text-4xl text-gray-200 dark:text-gray-600 mb-2"></i>
                                        <p>ไม่พบข้อมูลสาขาวิชา</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($Major->hasPages())
                <div
                    class="p-4 border-t border-gray-100 bg-gray-50 dark:border-gray-700 dark:bg-gray-800 transition-colors duration-200">
                    {{ $Major->links() }}
                </div>
            @endif
        </div>
    </div>

    <div id="createMajorModal" tabindex="-1" aria-hidden="true"
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
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">เพิ่มสาขาวิชา</h3>
                    </div>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:text-gray-900 dark:hover:text-white rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        data-modal-hide="createMajorModal">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                @include('admin.major.partials.create-form')
            </div>
        </div>
    </div>

    <div id="editMajorModal" tabindex="-1" aria-hidden="true"
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
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">แก้ไขข้อมูลสาขาวิชา</h3>
                    </div>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:text-gray-900 dark:hover:text-white rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        data-modal-hide="editMajorModal">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                @include('admin.major.partials.edit-form')
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                // การดึงข้อมูลลง Edit Modal
                const editButtons = document.querySelectorAll('.btn-edit-major');
                const editForm = document.getElementById('editMajorForm');
                const updateBaseUrl = "{{ url('admin/major/update') }}"; // ปรับ route base ให้ตรงกับที่กำหนดใน web.php

                editButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        document.getElementById('edit_m_name').value = this.getAttribute('data-m_name');

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
                        const majorName = this.getAttribute('data-topic');

                        Swal.fire({
                            title: 'คุณต้องการลบสาขาวิชา "' + majorName + '" ใช่หรือไม่?',
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
