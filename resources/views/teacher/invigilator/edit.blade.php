<x-app-layout>
    <div class="p-6 max-w-5xl mx-auto mt-8">

        <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200">
                    แก้ไขสมาชิกกลุ่ม
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                    ปรับปรุงรายชื่ออาจารย์ประจำ <span class="font-bold text-orange-500">{{ $group->name }}</span>
                </p>
            </div>
            <a href="{{ url()->previous() }}"
                class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200 flex items-center gap-2 text-sm font-medium">
                <i class="fa-solid fa-arrow-left"></i> ย้อนกลับ
            </a>
        </div>

        <form action="{{ route('teacher.invigilator.update', $group->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div
                class="bg-white dark:bg-gray-800 p-6 shadow-sm border border-gray-100 dark:border-gray-700 rounded-2xl transition-colors duration-200 mb-6">

                <div class="mb-8">
                    <h3
                        class="text-sm font-bold text-red-500 uppercase tracking-wider mb-4 border-b border-gray-100 dark:border-gray-700 pb-2 flex items-center gap-2">
                        <i class="fa-solid fa-user-check"></i> 1. สมาชิกปัจจุบัน
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                        <i class="fa-solid fa-circle-info mr-1"></i> นำเครื่องหมายถูกออก หากต้องการลบรายชื่อออกจากกลุ่ม
                    </p>

                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @forelse ($members as $member)
                            <li class="w-full">
                                <div
                                    class="flex items-center p-3 border border-red-200 dark:border-red-500/30 rounded-xl bg-red-50/50 dark:bg-red-500/10 hover:bg-red-50 dark:hover:bg-red-500/20 transition-colors">
                                    <input id="advisor-{{ $member->advisor->id }}" type="checkbox"
                                        name="remove_advisors[]" value="{{ $member->advisor->id }}"
                                        class="w-4 h-4 text-red-600 bg-white border-red-300 rounded focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-red-500 cursor-pointer"
                                        checked>
                                    <label for="advisor-{{ $member->advisor->id }}"
                                        class="w-full ms-3 text-sm font-medium text-gray-900 dark:text-gray-200 cursor-pointer select-none flex items-center gap-2">
                                        <div
                                            class="w-6 h-6 rounded-full bg-red-200 dark:bg-red-500/30 text-red-600 dark:text-red-400 flex items-center justify-center text-xs shrink-0">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                        {{ $member->advisor->a_fname }} {{ $member->advisor->a_lname }}
                                    </label>
                                </div>
                            </li>
                        @empty
                            <li
                                class="col-span-2 py-4 text-center text-gray-500 dark:text-gray-400 text-sm border border-dashed border-gray-300 dark:border-gray-600 rounded-xl">
                                กลุ่มนี้ยังไม่มีสมาชิก
                            </li>
                        @endforelse
                    </ul>
                </div>

                <div>
                    <h3
                        class="text-sm font-bold text-orange-500 uppercase tracking-wider mb-4 border-b border-gray-100 dark:border-gray-700 pb-2 flex items-center gap-2">
                        <i class="fa-solid fa-user-plus"></i> 2. เพิ่มสมาชิกใหม่
                    </h3>

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-3">
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            <i class="fa-solid fa-circle-info mr-1"></i> ติ๊กเลือกอาจารย์ที่ต้องการเพิ่มเข้ากลุ่ม
                        </p>

                        <div class="relative w-full sm:w-64">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                            </div>
                            <input type="text" id="search-advisor" placeholder="ค้นหาชื่ออาจารย์..."
                                class="block w-full py-2 pl-10 pr-3 text-sm text-gray-900 border border-gray-200 rounded-lg bg-gray-50 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-colors">
                        </div>
                    </div>

                    <div
                        class="border border-gray-200 dark:border-gray-600 rounded-xl overflow-hidden bg-white dark:bg-gray-800 shadow-sm">
                        <ul id="advisor-list" class="divide-y divide-gray-100 dark:divide-gray-700 min-h-[300px]">
                            @forelse ($advisors as $advisor)
                                <li class="advisor-item w-full hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
                                    data-name="{{ strtolower($advisor->a_fname . ' ' . $advisor->a_lname) }}">
                                    <div class="flex items-center ps-4">
                                        <input id="new-advisor-{{ $advisor->id }}" type="checkbox" name="advisors[]"
                                            value="{{ $advisor->id }}"
                                            class="w-4 h-4 text-orange-500 bg-gray-100 border-gray-300 rounded focus:ring-orange-500 dark:focus:ring-orange-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-500 cursor-pointer">
                                        <label for="new-advisor-{{ $advisor->id }}"
                                            class="w-full py-3.5 ms-3 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer select-none flex items-center gap-2">
                                            {{ $advisor->a_fname }} {{ $advisor->a_lname }}
                                        </label>
                                    </div>
                                </li>
                            @empty
                                <li class="py-10 text-center text-gray-500 dark:text-gray-400 text-sm">
                                    ไม่มีอาจารย์ท่านอื่นให้เลือก</li>
                            @endforelse
                        </ul>

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

            <div class="flex items-center justify-end gap-3">
                <a href="{{ url()->previous() }}"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-200">
                    ยกเลิก
                </a>
                <button type="submit"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-orange-500 rounded-xl hover:bg-orange-600 shadow-sm flex items-center gap-2 transition-colors duration-200">
                    <i class="fa-solid fa-save"></i> บันทึกการแก้ไข
                </button>
            </div>

        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                // ==========================================
                // Script สำหรับแบ่งหน้า Checkbox (เพิ่มสมาชิกใหม่)
                // ==========================================
                const items = Array.from(document.querySelectorAll('.advisor-item'));
                const searchInput = document.getElementById('search-advisor');
                const prevBtn = document.getElementById('prev-page');
                const nextBtn = document.getElementById('next-page');
                const pageInfo = document.getElementById('page-info');

                const itemsPerPage = 10;
                let currentPage = 1;
                let filteredItems = [...items];

                function renderPagination() {
                    const totalPages = Math.ceil(filteredItems.length / itemsPerPage) || 1;
                    if (currentPage > totalPages) currentPage = totalPages;

                    const start = (currentPage - 1) * itemsPerPage;
                    const end = start + itemsPerPage;

                    items.forEach(item => item.style.display = 'none');

                    filteredItems.slice(start, end).forEach(item => {
                        item.style.display = 'block';
                    });

                    const totalItems = filteredItems.length;
                    pageInfo.textContent = `หน้า ${currentPage} จาก ${totalPages} (ค้นพบ ${totalItems} ท่าน)`;

                    prevBtn.disabled = currentPage === 1;
                    nextBtn.disabled = currentPage === totalPages || totalItems === 0;

                    prevBtn.classList.toggle('opacity-50', prevBtn.disabled);
                    prevBtn.classList.toggle('cursor-not-allowed', prevBtn.disabled);
                    nextBtn.classList.toggle('opacity-50', nextBtn.disabled);
                    nextBtn.classList.toggle('cursor-not-allowed', nextBtn.disabled);
                }

                if (prevBtn && nextBtn) {
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

                        currentPage = 1;
                        renderPagination();
                    });

                    renderPagination();
                }

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
