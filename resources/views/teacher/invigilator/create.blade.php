<x-app-layout>
    <div class="p-6 max-w-5xl mx-auto mt-8">

        <!-- Header Section -->
        <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200">
                    เพิ่มกลุ่มคณะกรรมการ
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                    สร้างกลุ่มคณะกรรมการและกำหนดอาจารย์ประจำกลุ่ม
                </p>
            </div>
            <a href="{{ route('teacher.invigilator.home') }}"
                class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200 flex items-center gap-2 text-sm font-medium">
                <i class="fa-solid fa-arrow-left"></i> ย้อนกลับ
            </a>
        </div>

        <form action="{{ route('teacher.invigilator.store') }}" method="POST">
            @csrf

            <!-- Main Card -->
            <div
                class="bg-white dark:bg-gray-800 p-6 shadow-sm border border-gray-100 dark:border-gray-700 rounded-2xl transition-colors duration-200 mb-6">

                <!-- Section 1: ข้อมูลกลุ่ม -->
                <div class="mb-8">
                    <h3
                        class="text-sm font-bold text-orange-500 uppercase tracking-wider mb-4 border-b border-gray-100 dark:border-gray-700 pb-2 flex items-center gap-2">
                        <i class="fa-solid fa-users-gear"></i> 1. ข้อมูลกลุ่มคณะกรรมการ
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- ชื่อกลุ่ม -->
                        <div class="col-span-2 md:col-span-1">
                            <label for="name"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                ชื่อกลุ่ม <span class="text-red-500">*</span>
                            </label>
                            <div class="flex shadow-sm rounded-xl w-full">
                                <span
                                    class="inline-flex shrink-0 items-center px-4 text-sm text-gray-500 bg-gray-100 border border-e-0 border-gray-200 rounded-s-xl dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">
                                    <i class="fa-solid fa-layer-group"></i>
                                </span>
                                <input type="text" id="name" name="name" required
                                    placeholder="เช่น กลุ่มที่ 1 หรือ กรรมการชุด A"
                                    class="rounded-none rounded-e-xl block flex-1 min-w-0 w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-500 transition-colors">
                            </div>
                        </div>

                        <!-- ปีการศึกษา -->
                        <div class="col-span-2 md:col-span-1">
                            <label for="ac_id"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                ปีการศึกษา <span class="text-red-500">*</span>
                            </label>
                            <select name="ac_id" id="ac_id" required data-placeholder="-- เลือกปีการศึกษา --"
                                class="select2 w-full">
                                <option value=""></option>
                                @foreach ($academic_years as $year)
                                    <option value="{{ $year->id }}">{{ $year->year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 2: เลือกอาจารย์ (Checkbox + Pagination) -->
                <div class="mb-4">
                    <h3
                        class="text-sm font-bold text-orange-500 uppercase tracking-wider mb-4 border-b border-gray-100 dark:border-gray-700 pb-2 flex items-center gap-2">
                        <i class="fa-solid fa-user-check"></i> 2. กำหนดอาจารย์ประจำกลุ่ม
                    </h3>

                    <div>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-3">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                เลือกอาจารย์คณะกรรมการ <span class="text-red-500">*</span>
                            </label>

                            <!-- Search Input สำหรับกรองชื่อ -->
                            <div class="relative w-full sm:w-64">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                                </div>
                                <input type="text" id="search-advisor" placeholder="ค้นหาชื่ออาจารย์..."
                                    class="block w-full py-2 pl-10 pr-3 text-sm text-gray-900 border border-gray-200 rounded-lg bg-gray-50 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                        </div>

                        <!-- List Container -->
                        <div
                            class="border border-gray-200 dark:border-gray-600 rounded-xl overflow-hidden bg-white dark:bg-gray-800 shadow-sm">
                            <ul id="advisor-list" class="divide-y divide-gray-100 dark:divide-gray-700 min-h-[300px]">
                                @forelse ($advisors as $advisor)
                                    <li class="advisor-item w-full hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
                                        data-name="{{ strtolower($advisor->a_fname . ' ' . $advisor->a_lname) }}">
                                        <div class="flex items-center ps-4">
                                            <!-- เปลี่ยน Checkbox เป็นสีส้มให้เข้าธีม -->
                                            <input id="advisor-{{ $advisor->id }}" type="checkbox" name="advisors[]"
                                                value="{{ $advisor->id }}"
                                                class="w-4 h-4 text-orange-500 bg-gray-100 border-gray-300 rounded focus:ring-orange-500 dark:focus:ring-orange-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-500 cursor-pointer">
                                            <label for="advisor-{{ $advisor->id }}"
                                                class="w-full py-3.5 ms-3 text-sm font-medium text-gray-900 dark:text-gray-300 cursor-pointer select-none">
                                                {{ $advisor->a_fname }} {{ $advisor->a_lname }}
                                            </label>
                                        </div>
                                    </li>
                                @empty
                                    <li class="py-10 text-center text-gray-500 dark:text-gray-400 text-sm">
                                        ไม่พบข้อมูลอาจารย์</li>
                                @endforelse
                            </ul>

                            <!-- Pagination Controls -->
                            <div
                                class="p-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 flex flex-col sm:flex-row justify-between items-center gap-3">
                                <span class="text-sm text-gray-500 dark:text-gray-400" id="page-info">
                                    แสดงข้อมูล...
                                </span>
                                <div class="inline-flex rounded-md shadow-sm" role="group">
                                    <button type="button" id="prev-page"
                                        class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-orange-500 focus:z-10 focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 transition-colors">
                                        <i class="fa-solid fa-chevron-left mr-1"></i> ก่อนหน้า
                                    </button>
                                    <button type="button" id="next-page"
                                        class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-orange-500 focus:z-10 focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 transition-colors">
                                        ถัดไป <i class="fa-solid fa-chevron-right ml-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('teacher.invigilator.home') }}"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-200">
                    ยกเลิก
                </a>
                <button type="submit"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-orange-500 rounded-xl hover:bg-orange-600 shadow-sm flex items-center gap-2 transition-colors duration-200">
                    <i class="fa-solid fa-save"></i> บันทึกข้อมูล
                </button>
            </div>

        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                // ==========================================
                // Script สำหรับแบ่งหน้า Checkbox 10 รายการ
                // ==========================================
                const items = Array.from(document.querySelectorAll('.advisor-item'));
                const searchInput = document.getElementById('search-advisor');
                const prevBtn = document.getElementById('prev-page');
                const nextBtn = document.getElementById('next-page');
                const pageInfo = document.getElementById('page-info');

                const itemsPerPage = 10;
                let currentPage = 1;
                let filteredItems = [...items]; // เก็บเฉพาะ item ที่ผ่านการค้นหา

                function renderPagination() {
                    const totalPages = Math.ceil(filteredItems.length / itemsPerPage) || 1;
                    if (currentPage > totalPages) currentPage = totalPages;

                    const start = (currentPage - 1) * itemsPerPage;
                    const end = start + itemsPerPage;

                    // ซ่อนทั้งหมดก่อน
                    items.forEach(item => item.style.display = 'none');

                    // แสดงเฉพาะตัวที่อยู่ในหน้าปัจจุบัน
                    filteredItems.slice(start, end).forEach(item => {
                        item.style.display = 'block';
                    });

                    // อัปเดตข้อความและปุ่ม
                    const totalItems = filteredItems.length;
                    pageInfo.textContent = `หน้า ${currentPage} จาก ${totalPages} (ค้นพบ ${totalItems} ท่าน)`;

                    prevBtn.disabled = currentPage === 1;
                    nextBtn.disabled = currentPage === totalPages || totalItems === 0;

                    // ปรับ Style ปุ่มเมื่อถูก Disable
                    prevBtn.classList.toggle('opacity-50', prevBtn.disabled);
                    prevBtn.classList.toggle('cursor-not-allowed', prevBtn.disabled);
                    nextBtn.classList.toggle('opacity-50', nextBtn.disabled);
                    nextBtn.classList.toggle('cursor-not-allowed', nextBtn.disabled);
                }

                // ปุ่มเปลี่ยนหน้า
                prevBtn.addEventListener('click', () => {
                    if (currentPage > 1) {
                        currentPage--;
                        renderPagination();
                    }
                });

                nextBtn.addEventListener('click', () => {
                    const totalPages = Math.ceil(filteredItems.length / itemsPerPage);
                    if (currentPage < totalPages) {
                        currentPage++;
                        renderPagination();
                    }
                });

                // ระบบค้นหา (พิมพ์ปุ๊บ กรองทันที)
                searchInput.addEventListener('input', function() {
                    const keyword = this.value.toLowerCase().trim();

                    if (keyword === '') {
                        filteredItems = [...items];
                    } else {
                        filteredItems = items.filter(item => {
                            const name = item.getAttribute('data-name');
                            return name.includes(keyword);
                        });
                    }

                    currentPage = 1; // กลับไปหน้าแรกเสมอเมื่อค้นหา
                    renderPagination();
                });

                // รันครั้งแรกเมื่อเปิดหน้ามา
                renderPagination();


                // ==========================================
                // Script ของ SweetAlert
                // ==========================================
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
