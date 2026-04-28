<x-app-layout>
    <div class="mt-16 py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-50 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">ข้อมูลนักศึกษา</h3>

                    <div class="mt-4">
                        <div
                            class="flex flex-col sm:flex-row items-center justify-between
               space-y-4 sm:space-y-0 sm:space-x-4 pb-4">

                            {{-- Filter + Search --}}
                            <form id="filterForm" method="GET" action="{{ route('admin.student.index') }}"
                                class="flex flex-col sm:flex-row items-center
                     w-full sm:w-auto space-y-2 sm:space-y-0 sm:space-x-2">

                                {{-- สาขาวิชา --}}
                                <select name="m_id" id="m_id"
                                    class="block w-full sm:w-auto px-4 py-2 text-sm border rounded-md
                           bg-white dark:bg-gray-700 dark:text-white">
                                    <option value="">สาขาวิชาทั้งหมด</option>
                                    @foreach ($majors as $major)
                                        <option value="{{ $major->id }}"
                                            {{ request('m_id') == $major->id ? 'selected' : '' }}>
                                            {{ $major->m_name }}
                                        </option>
                                    @endforeach
                                </select>

                                {{-- ค้นหา --}}
                                <div class="relative w-full sm:w-auto">
                                    <div class="absolute inset-y-0 left-0 flex items-center ps-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0
                                 1110.89 3.476l4.817 4.817a1 1 0
                                 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <input type="text" name="search" id="table-search"
                                        value="{{ request('search') }}"
                                        class="block w-full sm:w-80 p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg
                              bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600
                              dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="ค้นหาข้อมูลนักศึกษา">
                                </div>
                            </form>

                            <div
                                class="flex flex-col sm:flex-row items-center w-full sm:w-auto
                     space-y-2 sm:space-y-0 sm:space-x-2">
                                {{-- ปุ่มเพิ่ม --}}
                                <div class="w-full sm:w-auto">
                                    <a href="{{ route('admin.student.create') }}"
                                        class="block w-full sm:inline-block text-center text-white bg-blue-500 hover:bg-blue-700
                      focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5
                      dark:bg-blue-500 dark:hover:bg-blue-700 dark:focus:ring-blue-600">
                                        <i class="fa-solid fa-user-plus fa-lg"></i> เพิ่มนักศึกษา
                                    </a>
                                </div>

                                <div class="w-full sm:w-auto">
                                    <button data-modal-target="uploadExcelModal" data-modal-toggle="uploadExcelModal"
                                        class="block w-full sm:inline-block text-center text-white bg-green-600 hover:bg-green-700 
                      focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 
                      dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-800">
                                        <i class="fa-solid fa-file-circle-plus fa-lg"></i> เพิ่มด้วย Excel
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <!-- Table -->
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-sm text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">ลำดับ</th>
                                        <th scope="col" class="px-6 py-3">รหัสนักศึกษา</th>
                                        <th scope="col" class="px-6 py-3">ชื่อ</th>
                                        <th scope="col" class="px-6 py-3">นามสกุล</th>
                                        <th scope="col" class="px-6 py-3">สาขาวิชา</th>
                                        {{-- <th scope="col" class="px-6 py-3">กลุ่มโครงงาน</th> --}}
                                        <th scope="col" class="px-6 py-3">Status</th>
                                        <th scope="col" class="px-6 py-3 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($Student as $user)
                                        <tr
                                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <th scope="row"
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{ $loop->iteration + ($Student->currentPage() - 1) * $Student->perPage() }}
                                            </th>
                                            <td class="px-6 py-4">
                                                {{ $user->s_id }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ $user->s_fname }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ $user->s_lname }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ optional($user->major)->m_name ?? '-' }}
                                            </td>
                                            {{-- <td class="px-6 py-4">
                                            {{ $user->project_group->id ?? '' }}
                                        </td> --}}

                                            {{-- <td class="px-6 py-4">
                                                {{ $user->status }}
                                            </td> --}}

                                            <td class="px-6 py-4">
                                                {{-- @php $status = $user->status; @endphp
                                                <span
                                                    class="px-2 py-1 rounded text-xs
        {{ $status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }}">
                                                    {{ ucfirst($status) }}
                                                    <!-- ใช้ ucfirst เพื่อให้ "active" เป็น "Active" -->
                                                </span> --}}

                                                @if ($user->status === 'active')
                                                    <span
                                                        class="px-2 py-1 text-xs text-green-700 bg-green-100 rounded">
                                                        ปกติ
                                                    </span>
                                                @elseif ($user->status === 'graduated')
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 rounded bg-gray-100 text-gray-700">
                                                        จบการศึกษา
                                                    </span>
                                                @else
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 rounded bg-red-100 text-red-700">
                                                        ไม่ทราบสถานะ
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center truncate">
                                                {{-- <a href="{{ route('admin.student.edit', $user->id) }}"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">แก้ไข</a>
                                            <form action="{{ route('admin.student.delete', $user->id) }}"
                                                method="POST" class="delete-form" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="delete-button font-medium text-red-600 dark:text-red-500 hover:underline"
                                                    data-topic="{{ $user->s_fname }} {{ $user->s_lname }}">
                                                    ลบ
                                                </button>
                                            </form> --}}

                                                <button type="button" title="แก้ไข"
                                                    onclick="window.location.href='{{ route('admin.student.edit', $user->id) }}'"
                                                    class="text-white bg-yellow-400 hover:bg-yellow-600 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-2.5 py-1.5 dark:bg-yellow-400 dark:hover:bg-yellow-600 dark:focus:ring-yellow-700">
                                                    <i class="fa-solid fa-pen fa-lg"></i>
                                                </button>
                                                <form action="{{ route('admin.student.delete', $user->id) }}"
                                                    method="POST" class="delete-form" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" title="ลบ"
                                                        class="delete-button text-white bg-red-500 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-2.5 py-1.5 dark:bg-red-500 dark:hover:bg-red-700 dark:focus:ring-red-800"
                                                        data-topic="{{ $user->s_fname }} {{ $user->s_lname }}">
                                                        <i class="fa-solid fa-trash-can fa-lg"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="px-6 py-4" colspan="9">
                                                Not found Student
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-4">
                        {{ $Student->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="uploadExcelModal" tabindex="-1" aria-hidden="true"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto inset-0 h-modal h-full bg-black bg-opacity-50 flex items-center justify-center">
        <div class="relative w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Header -->
                <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        นำเข้ารายชื่อนักศึกษา (Excel) .xlsx
                    </h3>

                    <!-- ปุ่มดาวน์โหลดตัวอย่าง -->
                    <a href="{{ route('excel', 'student_import_template.xlsx') }}"
                        class="inline-flex items-center bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-3 py-2 rounded-md shadow">
                        <i class="fa-solid fa-file-excel fa-lg mr-2"></i> ไฟล์ตัวอย่าง
                    </a>

                    <button type="button"
                        class="text-gray-400 bg-transparent hover:text-gray-900 dark:hover:text-white"
                        data-modal-hide="uploadExcelModal">&times;</button>
                </div>

                <!-- Body -->
                <form id="excel-upload-form" enctype="multipart/form-data" class="p-6 space-y-4">
                    <!-- Upload -->
                    <input type="file" id="excel-file" name="excel_file" accept=".xlsx"
                        class="block w-full mb-5 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                        required>

                    <!-- Upload button -->
                    <button type="button" id="upload-button"
                        class="w-auto bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        เพิ่มรายชื่อ
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Automatic Filtering Script -->
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
                // const jsonData = XLSX.utils.sheet_to_json(sheet);
                const jsonData = XLSX.utils.sheet_to_json(sheet, {
                    range: 1, // <<< ข้ามแถวแรก (index 0)
                    defval: '' // กันค่า undefined
                });

                fetch('{{ route('admin.student.import') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json', // <<< ช่วยให้ server ตอบ JSON แน่ ๆ
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            students: jsonData
                        })
                    })
                    .then(async (res) => {
                        const raw = await res.text(); // รับเป็น text ก่อน
                        let data = {};
                        try {
                            data = raw ? JSON.parse(raw) : {};
                        } // พยายามแปลงเป็น JSON
                        catch (e) {
                            /* ถ้า parse ไม่ได้ ก็ปล่อยเป็น {} */
                        }

                        if (!res.ok) {
                            // แสดงผลกรณี 4xx/5xx พร้อม warnings ถ้ามี
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
                            throw new Error(msg); // หยุด chain
                        }
                        return data; // ไป success
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

        document.addEventListener("DOMContentLoaded", function() {
            const filterForm = document.getElementById('filterForm');
            const majorSelect = document.getElementById('m_id');
            const searchInput = document.getElementById('table-search'); // แก้ id ให้ตรงกัน

            // เมื่อเลือกสาขาวิชา
            majorSelect.addEventListener('change', () => {
                filterForm.submit();
            });

            // เมื่อพิมพ์ในช่องค้นหา
            // searchInput.addEventListener('input', () => {
            //     filterForm.submit();
            // });

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
    </script>
</x-app-layout>

<script>
    // ตรวจสอบ success message
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'สำเร็จ',
            // timmer: 2000,
            text: "{{ session('success') }}",
            confirmButtonText: 'ตกลง'
        });
    @endif

    // ตรวจสอบ validation errors (แสดงข้อความแรก)
    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด',
            // timmer: 2000,
            text: "{{ $errors->first() }}",
            confirmButtonText: 'ตกลง'
        });
    @endif
</script>
