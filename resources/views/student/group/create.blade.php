<x-app-layout>
    <div class="p-6 max-w-4xl mx-auto mt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div class="flex-1 min-w-0 pr-4">
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-users-viewfinder text-orange-500 shrink-0"></i> สร้างกลุ่มโครงงาน
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 leading-relaxed truncate">
                    เลือกปีการศึกษาและเพิ่มรายชื่อนักศึกษาเข้าสู่กลุ่มโครงงานของคุณ
                </p>
            </div>

            <a href="{{ route('student.group.index') }}"
                class="shrink-0 whitespace-nowrap px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors flex items-center gap-2 text-sm font-medium">
                <i class="fa-solid fa-arrow-left"></i> ย้อนกลับ
            </a>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden transition-colors duration-200">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-orange-400 to-yellow-500"></div>

            <div class="p-6 md:p-8 border-b border-gray-50 dark:border-gray-700 bg-gray-50/30 dark:bg-gray-800/30">
                <form method="GET" action="{{ route('student.group.create') }}" class="w-full">
                    <div class="relative max-w-lg">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="ค้นหาชื่อ, นามสกุล หรือรหัสนักศึกษา..."
                            class="block w-full pl-10 pr-24 py-3 text-sm text-gray-900 border border-gray-200 rounded-xl bg-white focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:placeholder-gray-400 dark:text-white transition-colors shadow-sm" />
                        <div class="absolute inset-y-0 right-0 flex items-center pr-1.5">
                            <button type="submit"
                                class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-xs font-bold rounded-lg shadow-sm transition-colors dark:bg-gray-600 dark:hover:bg-gray-500">
                                ค้นหา
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <form method="POST" action="{{ route('student.group.store') }}" id="createGroupForm">
                @csrf

                <div class="p-6 md:p-8 space-y-8">

                    {{-- เลือกปีการศึกษา --}}
                    <div>
                        <label for="ac_id"
                            class="block text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-calendar-days text-orange-500"></i> ปีการศึกษาของกลุ่ม
                        </label>
                        <select name="ac_id" id="ac_id"
                            class="w-full md:w-1/2 px-4 py-3 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white transition-colors cursor-pointer shadow-sm">
                            <option value="" disabled selected hidden>-- กรุณาเลือกปีการศึกษา --</option>
                            @foreach ($years as $year)
                                <option value="{{ $year->id }}" {{ old('ac_id') == $year->id ? 'selected' : '' }}>
                                    ปีการศึกษา {{ $year->year }}
                                </option>
                            @endforeach
                        </select>
                        @error('ac_id')
                            <p class="text-rose-500 text-xs mt-2 flex items-center gap-1"><i
                                    class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- เลือกนักศึกษา --}}
                    <div>
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-2">
                            <label
                                class="block text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider flex items-center gap-2">
                                <i class="fa-solid fa-user-plus text-blue-500"></i> เลือกนักศึกษาเข้ากลุ่ม
                            </label>
                            <div class="flex gap-2">
                                <span
                                    class="text-xs font-bold text-blue-600 bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 px-2.5 py-1 rounded-lg flex items-center gap-1">
                                    <i class="fa-solid fa-check-double"></i> เลือกแล้ว <span
                                        id="selected-count">0</span> คน
                                </span>
                                <span
                                    class="text-xs font-medium text-gray-500 bg-gray-100 dark:bg-gray-700 px-2.5 py-1 rounded-lg">
                                    พบ {{ $students->total() }} คน
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @forelse ($students as $student)
                                <label
                                    class="relative flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer bg-white dark:bg-gray-800 hover:bg-orange-50 dark:hover:bg-gray-700/50 transition-all group has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50/50 dark:has-[:checked]:border-orange-500 dark:has-[:checked]:bg-orange-500/10 shadow-sm">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" id="student-{{ $student->id }}" name="students[]"
                                            value="{{ $student->id }}"
                                            class="student-checkbox w-4 h-4 text-orange-500 bg-gray-100 border-gray-300 rounded focus:ring-orange-500 dark:focus:ring-orange-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 cursor-pointer">
                                    </div>
                                    <div class="ms-3 flex items-center gap-3 w-full">
                                        <div
                                            class="w-8 h-8 rounded-full bg-blue-50 text-blue-500 dark:bg-blue-500/10 dark:text-blue-400 flex items-center justify-center shrink-0">
                                            <i class="fa-solid fa-user text-xs"></i>
                                        </div>
                                        <div class="flex flex-col min-w-0">
                                            <span
                                                class="text-sm font-bold text-gray-900 dark:text-white truncate group-has-[:checked]:text-orange-700 dark:group-has-[:checked]:text-orange-400">
                                                {{ $student->s_fname }} {{ $student->s_lname }}
                                            </span>
                                            <span
                                                class="text-[11px] text-gray-500 dark:text-gray-400 font-medium tracking-wider">
                                                รหัส: {{ $student->s_id }}
                                            </span>
                                        </div>
                                    </div>
                                </label>
                            @empty
                                <div
                                    class="col-span-1 sm:col-span-2 py-12 flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-dashed border-gray-200 dark:border-gray-700">
                                    <i
                                        class="fa-solid fa-users-slash text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                                        ไม่พบรายชื่อนักศึกษาที่ยังไม่มีกลุ่ม</p>
                                </div>
                            @endforelse
                        </div>

                        @if ($students->hasPages())
                            <div class="mt-6">
                                {{ $students->links() }}
                            </div>
                        @endif

                        @error('students')
                            <p class="text-rose-500 text-xs mt-3 flex items-center gap-1"><i
                                    class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div
                    class="px-6 py-4 bg-gray-50/80 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3 items-center">
                    <button type="button" id="cancelBtn"
                        onclick="window.location.href='{{ route('student.group.index') }}'"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">
                        ยกเลิก
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-bold text-white bg-orange-500 rounded-xl hover:bg-orange-600 shadow-sm flex items-center gap-2 transition-colors">
                        <i class="fa-solid fa-folder-plus"></i> สร้างกลุ่มโครงงาน
                    </button>
                </div>

            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {

                // 1. ระบบจำค่า ปีการศึกษา (Academic Year)
                const acStorageKey = 'selected_ac_id_create';
                const acSelect = document.getElementById('ac_id');

                // ถ้าใน Local Storage มีค่าเก่าที่จำไว้ และไม่มีการถูกบังคับเลือกมาจาก Server ให้โหลดค่าเก่ามาใส่
                const savedAcId = sessionStorage.getItem(acStorageKey);
                if (savedAcId && acSelect.value === "") {
                    acSelect.value = savedAcId;
                }

                // เมื่อผู้ใช้เปลี่ยนค่าปีการศึกษา ให้บันทึกลงความจำทันที
                acSelect.addEventListener('change', (e) => {
                    sessionStorage.setItem(acStorageKey, e.target.value);
                });


                // 2. สคริปต์แก้ปัญหาติ๊ก Checkbox หายตอนเปลี่ยนหน้า
                const storageKey = 'selected_students_create';
                let selectedStudents = JSON.parse(sessionStorage.getItem(storageKey) || '[]');
                const countDisplay = document.getElementById('selected-count');
                const checkboxes = document.querySelectorAll('.student-checkbox');

                const updateCount = () => {
                    if (countDisplay) countDisplay.innerText = selectedStudents.length;
                }

                checkboxes.forEach(cb => {
                    // โหลดหน้ามาปุ๊ป ถ้าเคยเซฟไว้ ให้ติ๊กถูกกลับคืนมา
                    if (selectedStudents.includes(cb.value)) {
                        cb.checked = true;
                    }

                    // ดักจับเวลาผู้ใช้กดติ๊กเข้า/ออก
                    cb.addEventListener('change', (e) => {
                        if (e.target.checked) {
                            if (!selectedStudents.includes(e.target.value)) {
                                selectedStudents.push(e.target.value);
                            }
                        } else {
                            selectedStudents = selectedStudents.filter(id => id !== e.target.value);
                        }
                        sessionStorage.setItem(storageKey, JSON.stringify(selectedStudents));
                        updateCount();
                    });
                });

                updateCount(); // อัปเดตตัวเลขครั้งแรกตอนโหลดหน้า


                // 3. ระบบส่งฟอร์ม (Form Submission)
                const form = document.getElementById('createGroupForm');
                if (form) {
                    form.addEventListener('submit', (e) => {
                        // ปิดการทำงานของ Checkbox เดิมในหน้า เพื่อไม่ให้ส่งค่าซ้ำซ้อน
                        checkboxes.forEach(cb => cb.name = '');

                        // นำรายชื่อจากในความจำ สร้างเป็น Input ซ่อน แล้วส่งไปกับ Form
                        selectedStudents.forEach(id => {
                            const hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = 'students[]';
                            hiddenInput.value = id;
                            form.appendChild(hiddenInput);
                        });

                        // ส่ง Form สำเร็จแล้ว ให้ล้างความจำทิ้งทั้งคู่
                        sessionStorage.removeItem(storageKey);
                        sessionStorage.removeItem(acStorageKey);
                    });
                }

                // กดยกเลิก ก็ให้ล้างความจำทิ้งเช่นกัน
                const cancelBtn = document.getElementById('cancelBtn');
                if (cancelBtn) {
                    cancelBtn.addEventListener('click', () => {
                        sessionStorage.removeItem(storageKey);
                        sessionStorage.removeItem(acStorageKey);
                    });
                }

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
                        text: "กรุณาตรวจสอบข้อมูลให้ครบถ้วน",
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#f97316'
                    });
                @endif
            });
        </script>
    @endpush
</x-app-layout>
