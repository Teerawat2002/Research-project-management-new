<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto mt-4">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200">
                    Academic Calendar
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                    จัดการปฏิทินการศึกษาและกำหนดการที่สำคัญ
                </p>
            </div>

            {{-- <div class="flex gap-2">
                <a href="{{ route('teacher.calendar.create') }}"
                    class="bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-xl text-sm px-4 py-2.5 flex items-center shadow-sm transition-colors duration-200">
                    <i class="fa-solid fa-plus mr-2"></i> เพิ่มรายการ
                </a>
            </div> --}}

            <div class="flex gap-2">
                <button type="button" data-modal-target="createCalendarModal" data-modal-toggle="createCalendarModal"
                    class="bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-xl text-sm px-4 py-2.5 flex items-center shadow-sm transition-colors duration-200">
                    <i class="fa-solid fa-plus mr-2"></i> เพิ่มรายการ
                </button>
            </div>

        </div>

        <!-- Main Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors duration-200 overflow-hidden">

            <!-- Filter Area -->
            <div
                class="p-5 border-b border-gray-50 dark:border-gray-700 bg-white dark:bg-gray-800 transition-colors duration-200 flex justify-end">
                <form method="GET" action="{{ route('teacher.calendar.index') }}" id="filterForm"
                    class="w-full sm:w-auto">

                    <!-- Hidden Input สำหรับเก็บค่าปีที่เลือกส่งไป Form -->
                    <input type="hidden" name="ac_id" id="hidden_ac_id" value="{{ request('ac_id') }}">

                    <div class="flex items-center gap-3">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 shrink-0">
                            <i class="fa-solid fa-calendar-day mr-1 text-orange-500"></i> ปีการศึกษา:
                        </label>

                        <!-- Custom Dropdown Component -->
                        @php
                            $selectedYearName = 'ปีการศึกษาทั้งหมด';
                            if (request('ac_id')) {
                                foreach ($academicYears as $year) {
                                    if (request('ac_id') == $year->id) {
                                        $selectedYearName = $year->year;
                                        break;
                                    }
                                }
                            }
                        @endphp

                        <div class="relative w-full sm:w-56">
                            <x-dropdown align="right" width="56">
                                <x-slot name="trigger">
                                    <button type="button"
                                        class="flex items-center justify-between w-full py-2.5 px-4 text-sm text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-orange-400 hover:border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:border-gray-500">
                                        <span class="truncate">{{ $selectedYearName }}</span>
                                        <i
                                            class="fa-solid fa-chevron-down text-xs text-gray-400 dark:text-gray-500 ml-2"></i>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <!-- ปุ่มรีเซ็ต / แสดงทั้งหมด -->
                                    <x-dropdown-link href="#"
                                        onclick="event.preventDefault(); document.getElementById('hidden_ac_id').value=''; document.getElementById('filterForm').submit();"
                                        class="{{ request('ac_id') == '' ? 'bg-orange-50 text-orange-600 font-bold dark:bg-gray-700 dark:text-orange-400' : 'text-gray-700 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white' }}">
                                        ปีการศึกษาทั้งหมด
                                    </x-dropdown-link>

                                    <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

                                    <!-- รายชื่อปีการศึกษา -->
                                    @foreach ($academicYears as $year)
                                        <x-dropdown-link href="#"
                                            onclick="event.preventDefault(); document.getElementById('hidden_ac_id').value='{{ $year->id }}'; document.getElementById('filterForm').submit();"
                                            class="{{ request('ac_id') == $year->id ? 'bg-orange-50 text-orange-600 font-bold dark:bg-gray-700 dark:text-orange-400' : 'text-gray-700 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white' }}">
                                            {{ $year->year }}
                                        </x-dropdown-link>
                                    @endforeach
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table Area -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400 transition-colors duration-200">
                    <thead
                        class="text-xs text-gray-500 uppercase bg-gray-50/50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 transition-colors duration-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold whitespace-nowrap w-48">
                                <i class="fa-regular fa-clock mr-1"></i> วันที่กำหนด
                            </th>
                            <th scope="col" class="px-6 py-4 font-semibold">
                                <i class="fa-solid fa-list-check mr-1"></i> รายการ
                            </th>
                            <th scope="col" class="px-6 py-4 font-semibold w-1/3">
                                <i class="fa-solid fa-circle-info mr-1"></i> รายละเอียด
                            </th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center w-32">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @forelse ($calendarData as $data)
                            <tr
                                class="hover:bg-orange-50/50 dark:hover:bg-gray-700/50 transition-colors duration-200 group">

                                <!-- วันที่ -->
                                <td class="px-6 py-5 align-top whitespace-nowrap">
                                    <div
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-orange-50 text-orange-700 border border-orange-200 dark:bg-orange-500/10 dark:text-orange-400 dark:border-orange-500/20 shadow-sm">
                                        {{ \Carbon\Carbon::parse($data->start_date)->format('d/m/Y') }}

                                        <!-- เช็คว่าถ้าเริ่มและจบคนละวัน ให้โชว์ลูกศรเชื่อม -->
                                        @if (
                                            \Carbon\Carbon::parse($data->start_date)->format('Y-m-d') !==
                                                \Carbon\Carbon::parse($data->end_date)->format('Y-m-d'))
                                            <span class="mx-2 text-orange-400 dark:text-orange-600">
                                                <i class="fa-solid fa-arrow-right"></i>
                                            </span>
                                            {{ \Carbon\Carbon::parse($data->end_date)->format('d/m/Y') }}
                                        @endif
                                    </div>
                                </td>

                                <!-- ชื่อรายการ -->
                                <td class="px-6 py-5 align-top">
                                    <div
                                        class="font-bold text-gray-900 dark:text-white text-base group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">
                                        {{ $data->title }}
                                    </div>
                                </td>

                                <!-- รายละเอียด -->
                                <td class="px-6 py-5 align-top text-gray-500 dark:text-gray-400 leading-relaxed">
                                    {{ $data->description ?: '-' }}
                                </td>

                                <!-- ปุ่ม Action (Edit / Delete) -->
                                <td class="px-6 py-5 align-top text-center space-x-3 text-gray-400 dark:text-gray-500">
                                    <button type="button" title="แก้ไข" data-modal-target="editCalendarModal"
                                        data-modal-toggle="editCalendarModal"
                                        class="btn-edit-calendar hover:text-blue-500 dark:hover:text-blue-400 transition-colors"
                                        data-id="{{ $data->id }}" data-ac_id="{{ $data->ac_id }}"
                                        data-start="{{ \Carbon\Carbon::parse($data->start_date)->format('Y-m-d') }}"
                                        data-end="{{ \Carbon\Carbon::parse($data->end_date)->format('Y-m-d') }}"
                                        data-title="{{ $data->title }}" data-description="{{ $data->description }}">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>

                                    <form action="{{ route('teacher.calendar.delete', $data->id) }}" method="POST"
                                        class="delete-form inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" title="ลบ"
                                            class="delete-button hover:text-red-500 dark:hover:text-red-400 transition-colors"
                                            data-topic="{{ $data->title }}">
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
                                            class="fa-regular fa-calendar-xmark text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                                        <p class="text-base font-medium">ไม่พบข้อมูลปฏิทินการศึกษา</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($calendarData->hasPages())
                <div
                    class="p-4 border-t border-gray-100 bg-gray-50 dark:border-gray-700 dark:bg-gray-800 transition-colors duration-200">
                    {{ $calendarData->links() }}
                </div>
            @endif
        </div>
    </div>

    @include('teacher.calendar.partials.create')
    @include('teacher.calendar.partials.edit')

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                const editButtons = document.querySelectorAll('.btn-edit-calendar');
                const editForm = document.getElementById('editCalendarForm');

                editButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');

                        // 1. ดึงค่าวันที่มาเก็บไว้ในตัวแปร
                        const startDate = this.getAttribute('data-start');
                        const endDate = this.getAttribute('data-end');

                        // 2. เติมข้อมูลลงช่อง Input (ลบคำสั่ง edit_start_date.value ทิ้งไปแล้ว)
                        document.getElementById('edit_ac_id').value = this.getAttribute('data-ac_id');
                        document.getElementById('edit_title').value = this.getAttribute('data-title');
                        document.getElementById('edit_description').value = this.getAttribute(
                            'data-description');

                        const editStartEl = document.getElementById('edit_start_date');
                        const editEndEl = document.getElementById('edit_end_date');

                        // 3. จัดการโยนวันที่เข้า Flatpickr อย่างถูกต้อง
                        if (editStartEl._flatpickr) {
                            // เคลียร์ค่า Min/Max เก่าทิ้งก่อน กันการบล็อกตัวเอง
                            editStartEl._flatpickr.set("maxDate", null);
                            // ตั้งค่าวันที่ โดยบังคับอ่าน format "Y-m-d" และไม่ให้ trigger onChange (false)
                            editStartEl._flatpickr.setDate(startDate, false, "Y-m-d");
                        } else {
                            editStartEl.value = startDate;
                        }

                        if (editEndEl._flatpickr) {
                            editEndEl._flatpickr.set("minDate", null);
                            editEndEl._flatpickr.setDate(endDate, false, "Y-m-d");
                        } else {
                            editEndEl.value = endDate;
                        }

                        // 4. อัปเดต Action URL ของ Form
                        if (editForm) {
                            let updateUrl = "{{ route('teacher.calendar.update', ':id') }}";
                            editForm.action = updateUrl.replace(':id', id);
                        }
                    });
                });

                // SweetAlert2 สำหรับปุ่ม Delete
                document.querySelectorAll('.delete-button').forEach(function(button) {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const form = this.closest('form');
                        const topic = this.getAttribute('data-topic');

                        Swal.fire({
                            title: 'คุณต้องการลบรายการนี้ใช่หรือไม่?',
                            text: "ลบ: " + topic,
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
