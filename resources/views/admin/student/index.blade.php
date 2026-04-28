<x-app-layout>
    <div class="p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200">
                    User Management (Students)
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                    จัดการข้อมูลบัญชีผู้ใช้ (นักศึกษา)
                </p>
            </div>

            <div class="flex gap-2">
                <button data-modal-target="uploadExcelModal" data-modal-toggle="uploadExcelModal"
                    class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 font-medium rounded-lg text-sm px-4 py-2 flex items-center shadow-sm transition-colors duration-200">
                    <i class="fa-solid fa-file-excel text-green-600 dark:text-green-500 mr-2"></i> Import Excel
                </button>
                <button type="button" data-modal-target="createStudentModal" data-modal-toggle="createStudentModal"
                    class="bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg text-sm px-4 py-2 flex items-center shadow-sm transition-colors duration-200">
                    <i class="fa-solid fa-plus mr-2"></i> Add User
                </button>
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors duration-200">

            <div
                class="p-5 border-b border-gray-50 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm rounded-t-xl transition-colors duration-200">
                <form id="filterForm" method="GET" action="{{ route('admin.student.index') }}"
                    class="flex flex-col sm:flex-row justify-between items-center gap-4">

                    <input type="hidden" name="m_id" id="hidden_m_id" value="{{ request('m_id') }}">

                    <div class="relative w-full sm:w-80">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <input type="text" name="search" id="table-search" value="{{ request('search') }}"
                            class="block w-full p-2.5 pl-10 text-sm text-gray-900 border border-gray-200 rounded-lg bg-gray-50 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-orange-500 dark:focus:border-orange-500 transition-colors duration-200"
                            placeholder="Search users...">
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        @php
                            $selectedMajorName = 'สาขาวิชาทั้งหมด';
                            foreach ($majors as $major) {
                                if (request('m_id') == $major->id) {
                                    $selectedMajorName = $major->m_name;
                                    break;
                                }
                            }
                        @endphp

                        <div class="relative w-full sm:w-48">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button type="button"
                                        class="flex items-center justify-between w-full py-2.5 px-4 text-sm text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-orange-400 hover:border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:border-gray-500">
                                        <span class="truncate">{{ $selectedMajorName }}</span>
                                        <i
                                            class="fa-solid fa-chevron-down text-xs text-gray-400 dark:text-gray-500 ml-2"></i>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link href="#"
                                        onclick="event.preventDefault(); document.getElementById('hidden_m_id').value=''; document.getElementById('filterForm').submit();"
                                        class="{{ request('m_id') == '' ? 'bg-orange-50 text-orange-600 font-bold dark:bg-gray-700 dark:text-orange-400' : 'text-gray-700 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white' }}">
                                        สาขาวิชาทั้งหมด
                                    </x-dropdown-link>

                                    <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

                                    @foreach ($majors as $major)
                                        <x-dropdown-link href="#"
                                            onclick="event.preventDefault(); document.getElementById('hidden_m_id').value='{{ $major->id }}'; document.getElementById('filterForm').submit();"
                                            class="{{ request('m_id') == $major->id ? 'bg-orange-50 text-orange-600 font-bold dark:bg-gray-700 dark:text-orange-400' : 'text-gray-700 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white' }}">
                                            {{ $major->m_name }}
                                        </x-dropdown-link>
                                    @endforeach
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400 transition-colors duration-200">
                    <thead
                        class="text-xs text-gray-500 uppercase bg-gray-50/50 border-b border-gray-100 dark:bg-gray-700/50 dark:border-gray-700 dark:text-gray-400 transition-colors duration-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-medium">Name</th>
                            <th scope="col" class="px-6 py-4 font-medium">Student ID</th>
                            <th scope="col" class="px-6 py-4 font-medium">Major</th>
                            <th scope="col" class="px-6 py-4 font-medium">Status</th>
                            <th scope="col" class="px-6 py-4 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @forelse ($Student as $user)
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700 transition-colors duration-200 group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $user->s_fname }}
                                        {{ $user->s_lname }}</div>
                                </td>
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $user->s_id }}</td>
                                <td class="px-6 py-4 dark:text-gray-300">{{ optional($user->major)->m_name ?? '-' }}
                                </td>

                                <td class="px-6 py-4">
                                    @if ($user->status === 'active')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20 transition-colors duration-200">
                                            <span
                                                class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400"></span>
                                            ปกติ
                                        </span>
                                    @elseif ($user->status === 'graduated')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20 transition-colors duration-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 dark:bg-blue-400"></span>
                                            จบการศึกษา
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-200 dark:bg-red-500/10 dark:text-red-400 dark:border-red-500/20 transition-colors duration-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 dark:bg-red-400"></span>
                                            ไม่ทราบสถานะ
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right space-x-3 text-gray-400 dark:text-gray-500">
                                    <button type="button" title="แก้ไข"
                                        class="hover:text-blue-500 dark:hover:text-blue-400 transition-colors btn-edit-student"
                                        data-modal-target="editStudentModal" data-modal-toggle="editStudentModal"
                                        data-id="{{ $user->id }}" data-s_id="{{ $user->s_id }}"
                                        data-fname="{{ $user->s_fname }}" data-lname="{{ $user->s_lname }}"
                                        data-status="{{ $user->status }}" data-m_id="{{ $user->m_id }}">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <form action="{{ route('admin.student.delete', $user->id) }}" method="POST"
                                        class="delete-form inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" title="ลบ"
                                            class="delete-button hover:text-red-500 dark:hover:text-red-400 transition-colors"
                                            data-topic="{{ $user->s_fname }} {{ $user->s_lname }}">
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
                                        <p>ไม่พบข้อมูลนักศึกษา</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($Student->hasPages())
                <div
                    class="p-4 border-t border-gray-100 bg-gray-50 dark:border-gray-700 dark:bg-gray-800 transition-colors duration-200">
                    {{ $Student->links() }}
                </div>
            @endif
        </div>
    </div>

    <div id="uploadExcelModal" tabindex="-1" aria-hidden="true"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto inset-0 h-modal h-full bg-black bg-opacity-50 flex items-center justify-center transition-opacity duration-300">
        <div class="relative w-full max-w-md max-h-full">
            <div
                class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden transition-colors duration-200">
                <div
                    class="flex items-center justify-between px-6 py-4 border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 transition-colors duration-200">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                            <i class="fa-solid fa-file-import"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                            นำเข้าข้อมูล (Excel) .xlsx
                        </h3>
                    </div>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:text-gray-900 dark:hover:text-white rounded-lg text-sm p-1.5 ml-auto inline-flex items-center transition"
                        data-modal-hide="uploadExcelModal">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form id="excel-upload-form" enctype="multipart/form-data" class="p-6">
                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">เลือกไฟล์เอกสาร
                            (.xlsx)</label>
                        <input type="file" id="excel-file" name="excel_file" accept=".xlsx"
                            class="block w-full text-sm text-gray-900 border border-gray-200 rounded-xl cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white transition-all file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-sm file:font-semibold file:bg-gray-200 file:text-gray-700 hover:file:bg-gray-300 dark:file:bg-gray-600 dark:file:text-gray-200 dark:hover:file:bg-gray-500"
                            required>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">รองรับเฉพาะไฟล์ .xlsx เท่านั้น</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="button" id="upload-button"
                            class="flex-1 bg-orange-500 hover:bg-orange-600 text-white font-bold py-2.5 rounded-xl shadow-sm transition-all duration-200 flex items-center justify-center">
                            <i class="fa-solid fa-cloud-arrow-up mr-2"></i> เริ่มนำเข้าข้อมูล
                        </button>

                        <a href="{{ route('excel', 'student_import_template.xlsx') }}"
                            class="flex-1 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600 font-bold py-2.5 rounded-xl shadow-sm transition-all duration-200 flex items-center justify-center text-sm text-center">
                            <i class="fa-solid fa-download text-emerald-600 dark:text-emerald-400 mr-2"></i>
                            ดาวน์โหลดตัวอย่าง
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="createStudentModal" tabindex="-1" aria-hidden="true"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto inset-0 h-modal h-full bg-black bg-opacity-50 flex items-center justify-center transition-opacity duration-300">
        <div class="relative w-full max-w-2xl max-h-full">
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl transition-colors duration-200">
                <div
                    class="flex items-center justify-between px-6 py-4 border-b dark:border-gray-700 rounded-t-2xl bg-gray-50 dark:bg-gray-700/50">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-orange-100 dark:bg-orange-500/20 rounded-full flex items-center justify-center text-orange-500 dark:text-orange-400">
                            <i class="fa-solid fa-user-plus"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">เพิ่มข้อมูลนักศึกษา</h3>
                    </div>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:text-gray-900 dark:hover:text-white rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        data-modal-hide="createStudentModal">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                @include('admin.student.partials.create-form')
            </div>
        </div>
    </div>

    <div id="editStudentModal" tabindex="-1" aria-hidden="true"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto inset-0 h-modal h-full bg-black bg-opacity-50 flex items-center justify-center transition-opacity duration-300">
        <div class="relative w-full max-w-2xl max-h-full">
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl transition-colors duration-200">
                <div
                    class="flex items-center justify-between px-6 py-4 border-b dark:border-gray-700 rounded-t-2xl bg-gray-50 dark:bg-gray-700/50">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-orange-100 dark:bg-orange-500/20 rounded-full flex items-center justify-center text-orange-500 dark:text-orange-400">
                            <i class="fa-solid fa-user-pen"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">แก้ไขข้อมูลนักศึกษา</h3>
                    </div>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:text-gray-900 dark:hover:text-white rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        data-modal-hide="editStudentModal">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                @include('admin.student.partials.edit-form')
            </div>
        </div>
    </div>

    {{-- @push('scripts')
        <script>
            // 1. นำเข้า Excel สำหรับนักศึกษา (สคริปต์เดิมของคุณ นำมาจัดให้เป็นระเบียบ)
            const editButtons = document.querySelectorAll('.btn-edit-student');
            const editForm = document.getElementById('editStudentForm');
            const updateBaseUrl = "{{ url('admin/student') }}";
            document.getElementById('upload-button').addEventListener('click', function() {
                const fileInput = document.getElementById('excel-file');
                const file = fileInput.files[0];

                if (!file) {
                    Swal.fire('ผิดพลาด', 'กรุณาเลือกไฟล์ Excel', 'error');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, {
                        type: 'array'
                    });
                    const sheetName = workbook.SheetNames[0];
                    const sheet = workbook.Sheets[sheetName];

                    const jsonData = XLSX.utils.sheet_to_json(sheet, {
                        range: 1, // <<< ข้ามแถวแรก (index 0)
                        defval: '' // กันค่า undefined
                    });

                    fetch('{{ route('admin.student.import') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                students: jsonData
                            })
                        })
                        .then(async (res) => {
                            const raw = await res.text();
                            let data = {};
                            try {
                                data = raw ? JSON.parse(raw) : {};
                            } catch (e) {}

                            if (!res.ok) {
                                const msg = data.message || raw || 'ไม่สามารถนำเข้าได้';
                                const warns = Array.isArray(data.warnings) ? data.warnings : [];
                                let html = msg;
                                if (warns.length) {
                                    html +=
                                        '<br><div style="text-align:left;max-height:220px;overflow:auto;"><ul>';
                                    html += warns.map(w => `<li>• ${w}</li>`).join('');
                                    html += '</ul></div>';
                                }
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'นำเข้าไม่ครบ',
                                    html
                                });
                                throw new Error(msg);
                            }
                            return data;
                        })
                        .then((data) => {
                            let html = data.message || 'สำเร็จ';
                            if (Array.isArray(data.warnings) && data.warnings.length) {
                                html += '<br><div style="text-align:left;max-height:220px;overflow:auto;"><ul>';
                                html += data.warnings.map(w => `<li>• ${w}</li>`).join('');
                                html += '</ul></div>';
                            }
                            Swal.fire({
                                    icon: 'success',
                                    title: 'สำเร็จ',
                                    html
                                })
                                .then(() => location.reload());
                        })
                        .catch(err => {
                            console.error(err);
                        });
                };

                reader.readAsArrayBuffer(file);
            });

            // 2. จัดการ Filter และ Delete Alerts
            document.addEventListener("DOMContentLoaded", function() {
                // SweetAlert2 สำหรับปุ่ม Delete
                document.querySelectorAll('.delete-button').forEach(function(button) {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const form = this.closest('form');
                        const studentName = this.getAttribute('data-topic');

                        Swal.fire({
                            title: 'คุณต้องการลบ "' + studentName + '" ใช่หรือไม่?',
                            text: "เมื่อลบแล้วข้อมูลจะหายไป!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#f97316', // สีส้มให้เข้าธีม
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

                // แจ้งเตือน SweetAlert จาก Session/Errors
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
                        text: "{{ $errors->first() }}",
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#f97316'
                    });
                @endif
            });

            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    document.getElementById('edit_s_id').value = this.getAttribute('data-s_id');
                    document.getElementById('edit_fname').value = this.getAttribute('data-fname');
                    document.getElementById('edit_lname').value = this.getAttribute('data-lname');
                    document.getElementById('edit_status').value = this.getAttribute('data-status');
                    document.getElementById('edit_m_id').value = this.getAttribute('data-m_id');

                    document.getElementById('edit_password').value = '';
                    document.getElementById('edit_password_confirmation').value = '';

                    editForm.action = updateBaseUrl + '/' + id;
                });
            });

            document.getElementById('createStudentForm').addEventListener('submit', function(e) {
                const pass = document.getElementById('create_password').value;
                const confirmPass = document.getElementById('create_password_confirmation').value;
                if (pass !== confirmPass) {
                    e.preventDefault();
                    Swal.fire('ผิดพลาด', 'รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน!', 'error');
                }
            });

            editForm.addEventListener('submit', function(e) {
                const pass = document.getElementById('edit_password').value;
                const confirmPass = document.getElementById('edit_password_confirmation').value;
                if (pass && pass !== confirmPass) {
                    e.preventDefault();
                    Swal.fire('ผิดพลาด', 'รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน!', 'error');
                }
            });
        </script>
    @endpush --}}

    @push('scripts')
        <script>
            // 1. นำเข้า Excel สำหรับนักศึกษา
            document.getElementById('upload-button').addEventListener('click', function() {
                const fileInput = document.getElementById('excel-file');
                const file = fileInput.files[0];

                if (!file) {
                    Swal.fire('ผิดพลาด', 'กรุณาเลือกไฟล์ Excel', 'error');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, {
                        type: 'array'
                    });
                    const sheetName = workbook.SheetNames[0];
                    const sheet = workbook.Sheets[sheetName];

                    const jsonData = XLSX.utils.sheet_to_json(sheet, {
                        range: 1, // ข้ามแถวแรก (index 0)
                        defval: '' // กันค่า undefined
                    });

                    fetch('{{ route('admin.student.import') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                students: jsonData
                            })
                        })
                        .then(async (res) => {
                            const raw = await res.text();
                            let data = {};
                            try {
                                data = raw ? JSON.parse(raw) : {};
                            } catch (e) {}

                            if (!res.ok) {
                                const msg = data.message || raw || 'ไม่สามารถนำเข้าได้';
                                const warns = Array.isArray(data.warnings) ? data.warnings : [];
                                let html = msg;
                                if (warns.length) {
                                    html +=
                                        '<br><div style="text-align:left;max-height:220px;overflow:auto;"><ul>';
                                    html += warns.map(w => `<li>• ${w}</li>`).join('');
                                    html += '</ul></div>';
                                }
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'นำเข้าไม่ครบ',
                                    html
                                });
                                throw new Error(msg);
                            }
                            return data;
                        })
                        .then((data) => {
                            let html = data.message || 'สำเร็จ';
                            if (Array.isArray(data.warnings) && data.warnings.length) {
                                html += '<br><div style="text-align:left;max-height:220px;overflow:auto;"><ul>';
                                html += data.warnings.map(w => `<li>• ${w}</li>`).join('');
                                html += '</ul></div>';
                            }
                            Swal.fire({
                                    icon: 'success',
                                    title: 'สำเร็จ',
                                    html
                                })
                                .then(() => location.reload());
                        })
                        .catch(err => {
                            console.error(err);
                        });
                };

                reader.readAsArrayBuffer(file);
            });

            // 2. จัดการ Filter, โหลดข้อมูลลง Modal, และ Delete Alerts
            document.addEventListener("DOMContentLoaded", function() {

                const filterForm = document.getElementById('filterForm');
                const majorSelect = document.getElementById('m_id');
                const searchInput = document.getElementById('table-search');

                // เมื่อเลือกสาขาวิชา
                if (majorSelect) {
                    majorSelect.addEventListener('change', () => {
                        filterForm.submit();
                    });
                }

                // การดึงข้อมูลลง Edit Modal
                const editButtons = document.querySelectorAll('.btn-edit-student');
                const editForm = document.getElementById('editStudentForm');
                const updateBaseUrl = "{{ url('admin/student') }}";

                editButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        document.getElementById('edit_s_id').value = this.getAttribute('data-s_id');
                        document.getElementById('edit_fname').value = this.getAttribute('data-fname');
                        document.getElementById('edit_lname').value = this.getAttribute('data-lname');
                        document.getElementById('edit_status').value = this.getAttribute('data-status');
                        document.getElementById('edit_m_id').value = this.getAttribute('data-m_id');

                        document.getElementById('edit_password').value = '';
                        document.getElementById('edit_password_confirmation').value = '';

                        if (editForm) {
                            editForm.action = updateBaseUrl + '/' + id;
                        }
                    });
                });

                // เช็ครหัสผ่าน Modal Create
                const createForm = document.getElementById('createStudentForm');
                if (createForm) {
                    createForm.addEventListener('submit', function(e) {
                        const pass = document.getElementById('create_password').value;
                        const confirmPass = document.getElementById('create_password_confirmation').value;
                        if (pass !== confirmPass) {
                            e.preventDefault();
                            Swal.fire('ผิดพลาด', 'รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน!', 'error');
                        }
                    });
                }

                // เช็ครหัสผ่าน Modal Edit
                if (editForm) {
                    editForm.addEventListener('submit', function(e) {
                        const pass = document.getElementById('edit_password').value;
                        const confirmPass = document.getElementById('edit_password_confirmation').value;
                        if (pass && pass !== confirmPass) {
                            e.preventDefault();
                            Swal.fire('ผิดพลาด', 'รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน!', 'error');
                        }
                    });
                }

                // SweetAlert2 สำหรับปุ่ม Delete
                document.querySelectorAll('.delete-button').forEach(function(button) {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const form = this.closest('form');
                        const studentName = this.getAttribute('data-topic');

                        Swal.fire({
                            title: 'คุณต้องการลบ "' + studentName + '" ใช่หรือไม่?',
                            text: "เมื่อลบแล้วข้อมูลจะหายไป!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#f97316', // สีส้มให้เข้าธีม
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

                // แจ้งเตือน SweetAlert จาก Session/Errors
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
                        text: "{{ $errors->first() }}",
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#f97316'
                    });
                @endif
            });
        </script>
    @endpush
</x-app-layout>
