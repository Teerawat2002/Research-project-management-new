<x-app-layout>
    <div class="p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">User Management (Advisors)</h1>
                <p class="text-sm text-gray-500 mt-1">จัดการข้อมูลบัญชีผู้ใช้ (อาจารย์)</p>
            </div>

            <div class="flex gap-2">
                <button data-modal-target="uploadExcelModal" data-modal-toggle="uploadExcelModal"
                    class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 font-medium rounded-lg text-sm px-4 py-2 flex items-center shadow-sm transition">
                    <i class="fa-solid fa-file-excel text-green-600 mr-2"></i> Import Excel
                </button>
                <button type="button" data-modal-target="createAdvisorModal" data-modal-toggle="createAdvisorModal"
                    class="bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg text-sm px-4 py-2 flex items-center shadow-sm transition">
                    <i class="fa-solid fa-plus mr-2"></i> Add User
                </button>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-50 bg-white shadow-sm rounded-t-xl">
                <form id="filterForm" method="GET" action="{{ route('admin.advisor.index') }}"
                    class="flex flex-col sm:flex-row justify-between items-center gap-4">

                    <input type="hidden" name="m_id" id="hidden_m_id" value="{{ request('m_id') }}">
                    <input type="hidden" name="a_type" id="hidden_a_type" value="{{ request('a_type') }}">

                    <div class="relative w-full sm:w-80">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                        </div>
                        <input type="text" name="search" id="table-search" value="{{ request('search') }}"
                            class="block w-full p-2.5 pl-10 text-sm text-gray-900 border border-gray-200 rounded-lg bg-gray-50 focus:ring-orange-500 focus:border-orange-500"
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

                            $selectedRoleName = 'Role ทั้งหมด';
                            if (request('a_type') !== null && isset($advisorTypes[request('a_type')])) {
                                $selectedRoleName = ucfirst($advisorTypes[request('a_type')]);
                            }
                        @endphp

                        <div class="relative w-full sm:w-48">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button type="button"
                                        class="flex items-center justify-between w-full py-2.5 px-4 text-sm text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-orange-400 hover:border-gray-300">
                                        <span class="truncate">{{ $selectedMajorName }}</span>
                                        <i class="fa-solid fa-chevron-down text-xs text-gray-400 ml-2"></i>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link href="#"
                                        onclick="event.preventDefault(); document.getElementById('hidden_m_id').value=''; document.getElementById('filterForm').submit();"
                                        class="{{ request('m_id') == '' ? 'bg-orange-50 text-orange-600 font-bold' : '' }}">
                                        สาขาวิชาทั้งหมด
                                    </x-dropdown-link>

                                    <div class="border-t border-gray-100 my-1"></div>

                                    @foreach ($majors as $major)
                                        <x-dropdown-link href="#"
                                            onclick="event.preventDefault(); document.getElementById('hidden_m_id').value='{{ $major->id }}'; document.getElementById('filterForm').submit();"
                                            class="{{ request('m_id') == $major->id ? 'bg-orange-50 text-orange-600 font-bold' : '' }}">
                                            {{ $major->m_name }}
                                        </x-dropdown-link>
                                    @endforeach
                                </x-slot>
                            </x-dropdown>
                        </div>

                        <div class="relative w-full sm:w-48">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button type="button"
                                        class="flex items-center justify-between w-full py-2.5 px-4 text-sm text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-orange-400 hover:border-gray-300">
                                        <span class="truncate">{{ $selectedRoleName }}</span>
                                        <i class="fa-solid fa-chevron-down text-xs text-gray-400 ml-2"></i>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link href="#"
                                        onclick="event.preventDefault(); document.getElementById('hidden_a_type').value=''; document.getElementById('filterForm').submit();"
                                        class="{{ request('a_type') == '' ? 'bg-orange-50 text-orange-600 font-bold' : '' }}">
                                        Role ทั้งหมด
                                    </x-dropdown-link>

                                    <div class="border-t border-gray-100 my-1"></div>

                                    @foreach ($advisorTypes as $key => $value)
                                        <x-dropdown-link href="#"
                                            onclick="event.preventDefault(); document.getElementById('hidden_a_type').value='{{ $key }}'; document.getElementById('filterForm').submit();"
                                            class="{{ request('a_type') == $key ? 'bg-orange-50 text-orange-600 font-bold' : '' }}">
                                            {{ ucfirst($value) }}
                                        </x-dropdown-link>
                                    @endforeach
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 border-b border-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-medium">Name</th>
                            <th scope="col" class="px-6 py-4 font-medium">Advisor ID</th>
                            <th scope="col" class="px-6 py-4 font-medium">Major</th>
                            <th scope="col" class="px-6 py-4 font-medium">Role</th>
                            <th scope="col" class="px-6 py-4 font-medium">Status</th>
                            <th scope="col" class="px-6 py-4 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($advisorUser as $user)
                            <tr class="hover:bg-gray-50/80 transition group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $user->a_fname }} {{ $user->a_lname }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $user->a_id }}</td>
                                <td class="px-6 py-4">{{ $user->major->m_name }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border border-gray-200 bg-white text-gray-600 shadow-sm">
                                        {{ ucfirst($user->a_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($user->status == 'active')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-500 text-white">
                                            Active
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right space-x-3 text-gray-400">
                                    <button type="button" title="แก้ไข"
                                        class="hover:text-blue-500 transition btn-edit-advisor"
                                        data-modal-target="editAdvisorModal" data-modal-toggle="editAdvisorModal"
                                        data-id="{{ $user->id }}" data-a_id="{{ $user->a_id }}"
                                        data-fname="{{ $user->a_fname }}" data-lname="{{ $user->a_lname }}"
                                        data-type="{{ $user->a_type }}" data-m_id="{{ $user->m_id }}">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    {{-- <button class="hover:text-gray-900 transition"><i
                                            class="fa-regular fa-envelope"></i></button> --}}
                                    <form action="{{ route('admin.advisor.delete', $user->id) }}" method="POST"
                                        class="delete-form inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" title="ลบ"
                                            class="delete-button hover:text-red-500 transition"
                                            data-topic="{{ $user->a_fname }} {{ $user->a_lname }}">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-8 text-center text-gray-500" colspan="6">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fa-solid fa-folder-open text-4xl text-gray-200 mb-2"></i>
                                        <p>ไม่มีข้อมูลอาจารย์ในระบบ</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($advisorUser->hasPages())
                <div class="p-4 border-t border-gray-100 bg-gray-50">
                    {{ $advisorUser->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal for Upload Excel -->
    <!-- Modal -->
    <div id="uploadExcelModal" tabindex="-1" aria-hidden="true"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto inset-0 h-modal h-full bg-black bg-opacity-50 flex items-center justify-center transition-opacity duration-300">
        <div class="relative w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b bg-gray-50">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600">
                            <i class="fa-solid fa-file-import"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">
                            นำเข้าข้อมูล (Excel)
                        </h3>
                    </div>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center transition"
                        data-modal-hide="uploadExcelModal">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form id="excel-upload-form" enctype="multipart/form-data" class="p-6">
                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium text-gray-700">เลือกไฟล์เอกสาร (.xlsx)</label>
                        <input type="file" id="excel-file" name="excel_file" accept=".xlsx"
                            class="block w-full text-sm text-gray-900 border border-gray-200 rounded-xl cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition-all file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-sm file:font-semibold file:bg-gray-200 file:text-gray-700 hover:file:bg-gray-300"
                            required>
                        <p class="mt-2 text-xs text-gray-500">รองรับเฉพาะไฟล์ .xlsx เท่านั้น</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="button" id="upload-button"
                            class="flex-1 bg-orange-500 hover:bg-orange-600 text-white font-bold py-2.5 rounded-xl shadow-sm transition-all duration-200 flex items-center justify-center">
                            <i class="fa-solid fa-cloud-arrow-up mr-2"></i> เริ่มนำเข้าข้อมูล
                        </button>

                        <a href="{{ route('excel', 'teacher_import_template.xlsx') }}"
                            class="flex-1 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 font-bold py-2.5 rounded-xl shadow-sm transition-all duration-200 flex items-center justify-center text-sm text-center">
                            <i class="fa-solid fa-download text-emerald-600 mr-2"></i> ดาวน์โหลดตัวอย่าง
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="createAdvisorModal" tabindex="-1" aria-hidden="true"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto inset-0 h-modal h-full bg-black bg-opacity-50 flex items-center justify-center">
        <div class="relative w-full max-w-2xl max-h-full">
            <div class="relative bg-white rounded-2xl shadow-xl">
                <div class="flex items-center justify-between px-6 py-4 border-b rounded-t-2xl bg-gray-50">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center text-orange-500">
                            <i class="fa-solid fa-user-plus"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">เพิ่มข้อมูลอาจารย์</h3>
                    </div>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        data-modal-hide="createAdvisorModal">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                @include('admin.advisor.partials.create-form')
            </div>
        </div>
    </div>

    <div id="editAdvisorModal" tabindex="-1" aria-hidden="true"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto inset-0 h-modal h-full bg-black bg-opacity-50 flex items-center justify-center">
        <div class="relative w-full max-w-2xl max-h-full">
            <div class="relative bg-white rounded-2xl shadow-xl">
                <div class="flex items-center justify-between px-6 py-4 border-b rounded-t-2xl bg-gray-50">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center text-orange-500">
                            <i class="fa-solid fa-user-pen"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">แก้ไขข้อมูลอาจารย์</h3>
                    </div>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        data-modal-hide="editAdvisorModal">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                @include('admin.advisor.partials.edit-form')
            </div>
        </div>
    </div>

    {{-- @push('scripts')
        <script>
            // 1. โค้ดจัดการ Upload Excel
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

                    // แปลงข้อมูล + trim คีย์หัวตาราง
                    const rawJson = XLSX.utils.sheet_to_json(sheet);
                    // const rows = XLSX.utils.sheet_to_json(ws, {
                    //     defval: '',
                    //     raw: false,
                    //     range: 1 // << ข้ามแถว 1 → ใช้แถว 2 เป็น header
                    // });
                    const jsonData = rawJson.map(row => {
                        const cleaned = {};
                        Object.keys(row).forEach(key => {
                            cleaned[key.trim()] = row[key]; // ตัดช่องว่างหัวตาราง
                        });
                        return cleaned;
                    });

                    // ส่งข้อมูลไปยัง route: admin.advisor.import
                    fetch('{{ route('admin.advisor.import') }}', {
                            method: 'POST',
                            // body: JSON.stringify({ advisors: rows, dataStartRow: 3 }),
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                advisors: jsonData
                            }) // ใช้ key advisors
                        })
                        .then(response => response.json()) // เพิ่มบรรทัดนี้ที่หายไป
                        .then(data => {
                            let msg = `<b>${data.message}</b>`;

                            if (data.warnings && data.warnings.length > 0) {
                                msg += '<br><br><b>คำเตือน:</b><ul style="text-align:left">';
                                data.warnings.forEach(w => {
                                    msg += `<li>${w}</li>`;
                                });
                                msg += '</ul>';
                            }

                            Swal.fire({
                                icon: 'success',
                                html: msg,
                                confirmButtonText: 'ตกลง'
                            }).then(() => location.reload());
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('ผิดพลาด', 'เกิดข้อผิดพลาดในการนำเข้าข้อมูล', 'error');
                        });
                };

                reader.readAsArrayBuffer(file);
            });

            // 2. โค้ด Filter และ Delete
            document.addEventListener("DOMContentLoaded", function() {
                const filterForm = document.getElementById('filterForm');
                const majorSelect = document.getElementById('m_id');
                const typeSelect = document.getElementById('a_type');
                const searchInput = document.getElementById('table-search'); // แก้ id ให้ตรงกัน

                // เมื่อเลือก Major หรือ Type
                majorSelect.addEventListener('change', () => {
                    filterForm.submit();
                });

                typeSelect.addEventListener('change', () => {
                    filterForm.submit();
                });

                // // เมื่อพิมพ์ในช่องค้นหา
                // let debounceTimeout;
                // searchInput.addEventListener('input', () => {
                //     clearTimeout(debounceTimeout);
                //     debounceTimeout = setTimeout(() => {
                //         filterForm.submit();
                //     }, 1000); // ปรับ delay ถ้าต้องการ
                // });

                // SweetAlert2 สำหรับปุ่ม Delete
                document.querySelectorAll('.delete-button').forEach(function(button) {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const form = this.closest('form');
                        const advisorName = this.getAttribute('data-topic');

                        Swal.fire({
                            title: 'คุณต้องการลบ "' + advisorName + '" ใช่หรือไม่?',
                            text: "เมื่อลบแล้วข้อมูลจะหายไป!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
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

            // 3. โค้ดแจ้งเตือน SweetAlert จาก Session/Errors
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ',
                    text: "{{ session('success') }}",
                    confirmButtonText: 'ตกลง'
                });
            @endif

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: "{{ $errors->first() }}",
                    confirmButtonText: 'ตกลง'
                });
            @endif
        </script>
    @endpush --}}

    @push('scripts')
        <script>
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

                    // แปลงข้อมูล + trim คีย์หัวตาราง
                    const rawJson = XLSX.utils.sheet_to_json(sheet);
                    // const rows = XLSX.utils.sheet_to_json(ws, {
                    //     defval: '',
                    //     raw: false,
                    //     range: 1 // << ข้ามแถว 1 → ใช้แถว 2 เป็น header
                    // });
                    const jsonData = rawJson.map(row => {
                        const cleaned = {};
                        Object.keys(row).forEach(key => {
                            cleaned[key.trim()] = row[key]; // ตัดช่องว่างหัวตาราง
                        });
                        return cleaned;
                    });

                    // ส่งข้อมูลไปยัง route: admin.advisor.import
                    fetch('{{ route('admin.advisor.import') }}', {
                            method: 'POST',
                            // body: JSON.stringify({ advisors: rows, dataStartRow: 3 }),
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                advisors: jsonData
                            }) // ใช้ key advisors
                        })
                        .then(response => response.json()) // เพิ่มบรรทัดนี้ที่หายไป
                        .then(data => {
                            let msg = `<b>${data.message}</b>`;

                            if (data.warnings && data.warnings.length > 0) {
                                msg += '<br><br><b>คำเตือน:</b><ul style="text-align:left">';
                                data.warnings.forEach(w => {
                                    msg += `<li>${w}</li>`;
                                });
                                msg += '</ul>';
                            }

                            Swal.fire({
                                icon: 'success',
                                html: msg,
                                confirmButtonText: 'ตกลง'
                            }).then(() => location.reload());
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('ผิดพลาด', 'เกิดข้อผิดพลาดในการนำเข้าข้อมูล', 'error');
                        });
                };

                reader.readAsArrayBuffer(file);
            });

            document.addEventListener("DOMContentLoaded", function() {

                // 1. นำข้อมูลมาใส่ใน Modal Edit เมื่อกดปุ่มแก้ไข
                const editButtons = document.querySelectorAll('.btn-edit-advisor');
                const editForm = document.getElementById('editAdvisorForm');

                // Base URL ของการอัปเดต เช่น http://yourdomain.com/admin/advisor
                // ปรับให้ตรงกับ Route Resource หรือ URL ของระบบคุณ
                const updateBaseUrl = "{{ url('admin/advisor') }}";

                editButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');

                        // เอาข้อมูลจากปุ่มมาใส่ใน Input
                        document.getElementById('edit_a_id').value = this.getAttribute('data-a_id');
                        document.getElementById('edit_fname').value = this.getAttribute('data-fname');
                        document.getElementById('edit_lname').value = this.getAttribute('data-lname');
                        document.getElementById('edit_type').value = this.getAttribute('data-type');
                        document.getElementById('edit_m_id').value = this.getAttribute('data-m_id');

                        // ล้างช่องรหัสผ่านเสมอเมื่อเปิดฟอร์มแก้ไขใหม่
                        document.getElementById('edit_password').value = '';
                        document.getElementById('edit_password_confirmation').value = '';

                        // เปลี่ยน URL ปลายทางของฟอร์ม (Form Action) ให้เป็น Route สำหรับผู้ใช้คนนั้นๆ
                        editForm.action = updateBaseUrl + '/' + id;
                    });
                });

                // 2. เช็ครหัสผ่านตรงกันใน Modal Create
                document.getElementById('createAdvisorForm').addEventListener('submit', function(e) {
                    const pass = document.getElementById('create_password').value;
                    const confirmPass = document.getElementById('create_password_confirmation').value;
                    if (pass !== confirmPass) {
                        e.preventDefault();
                        Swal.fire('ผิดพลาด', 'รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน!', 'error');
                    }
                });

                // 3. เช็ครหัสผ่านตรงกันใน Modal Edit (เช็คเฉพาะตอนที่มีการพิมพ์รหัสผ่านใหม่)
                editForm.addEventListener('submit', function(e) {
                    const pass = document.getElementById('edit_password').value;
                    const confirmPass = document.getElementById('edit_password_confirmation').value;
                    if (pass && pass !== confirmPass) {
                        e.preventDefault();
                        Swal.fire('ผิดพลาด', 'รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน!', 'error');
                    }
                });

                /* --- โค้ดเดิมที่คุณมีอยู่ด้านล่าง --- */

                // SweetAlert2 สำหรับปุ่ม Delete
                document.querySelectorAll('.delete-button').forEach(function(button) {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const form = this.closest('form');
                        const advisorName = this.getAttribute('data-topic');

                        Swal.fire({
                            title: 'คุณต้องการลบ "' + advisorName + '" ใช่หรือไม่?',
                            text: "เมื่อลบแล้วข้อมูลจะหายไป!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
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
                        confirmButtonText: 'ตกลง'
                    });
                @endif

                @if ($errors->any())
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: "{{ $errors->first() }}",
                        confirmButtonText: 'ตกลง'
                    });
                @endif
            });
        </script>
    @endpush

</x-app-layout>
