<x-app-layout>
    <div class="p-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200">
                    Sub Topic Management
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                    จัดการหัวข้อรอง
                </p>
            </div>

            <div class="flex gap-2">
                <button type="button" data-modal-target="createSubTopicModal" data-modal-toggle="createSubTopicModal"
                    class="bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg text-sm px-4 py-2 flex items-center shadow-sm transition-colors duration-200">
                    <i class="fa-solid fa-plus mr-2"></i> เพิ่มหัวข้อรอง
                </button>
            </div>
        </div>

        <!-- Main Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors duration-200">

            <!-- Filters & Search -->
            <div
                class="p-5 border-b border-gray-50 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm rounded-t-xl transition-colors duration-200">
                <form id="filterForm" method="GET" action="{{ route('admin.topic.subtopic.index') }}"
                    class="flex flex-col sm:flex-row justify-between items-center gap-4">

                    <input type="hidden" name="maintopic_id" id="hidden_maintopic_id"
                        value="{{ request('maintopic_id') }}">

                    <!-- ช่องค้นหา -->
                    <div class="relative w-full sm:w-80">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <input type="text" name="search" id="table-search" value="{{ request('search') }}"
                            class="block w-full p-2.5 pl-10 text-sm text-gray-900 border border-gray-200 rounded-lg bg-gray-50 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-orange-500 dark:focus:border-orange-500 transition-colors duration-200"
                            placeholder="ค้นหาหัวข้อรอง...">
                    </div>

                    <!-- Dropdown ตัวกรองหัวข้อหลัก -->
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        @php
                            $selectedMainTopicName = 'หัวข้อหลักทั้งหมด';
                            if (request('maintopic_id') === 'unattached') {
                                $selectedMainTopicName = 'ไม่อยู่ในหัวข้อหลัก';
                            } else {
                                foreach ($main_topics as $mt) {
                                    if (request('maintopic_id') == $mt->id) {
                                        $selectedMainTopicName = $mt->name;
                                        break;
                                    }
                                }
                            }
                        @endphp

                        <div class="relative w-full sm:w-64">
                            <x-dropdown align="right" width="64">
                                <x-slot name="trigger">
                                    <button type="button"
                                        class="flex items-center justify-between w-full py-2.5 px-4 text-sm text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-orange-400 hover:border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:border-gray-500">
                                        <span class="truncate">{{ $selectedMainTopicName }}</span>
                                        <i
                                            class="fa-solid fa-chevron-down text-xs text-gray-400 dark:text-gray-500 ml-2"></i>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link href="#"
                                        onclick="event.preventDefault(); document.getElementById('hidden_maintopic_id').value=''; document.getElementById('filterForm').submit();"
                                        class="{{ request('maintopic_id') == '' ? 'bg-orange-50 text-orange-600 font-bold dark:bg-gray-700 dark:text-orange-400' : 'text-gray-700 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white' }}">
                                        หัวข้อหลักทั้งหมด
                                    </x-dropdown-link>

                                    <x-dropdown-link href="#"
                                        onclick="event.preventDefault(); document.getElementById('hidden_maintopic_id').value='unattached'; document.getElementById('filterForm').submit();"
                                        class="{{ request('maintopic_id') == 'unattached' ? 'bg-orange-50 text-orange-600 font-bold dark:bg-gray-700 dark:text-orange-400' : 'text-gray-700 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white' }}">
                                        ไม่อยู่ในหัวข้อหลัก
                                    </x-dropdown-link>

                                    <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

                                    @foreach ($main_topics as $mt)
                                        <x-dropdown-link href="#"
                                            onclick="event.preventDefault(); document.getElementById('hidden_maintopic_id').value='{{ $mt->id }}'; document.getElementById('filterForm').submit();"
                                            class="{{ request('maintopic_id') == $mt->id ? 'bg-orange-50 text-orange-600 font-bold dark:bg-gray-700 dark:text-orange-400' : 'text-gray-700 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white' }}">
                                            {{ $mt->name }}
                                        </x-dropdown-link>
                                    @endforeach
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400 transition-colors duration-200">
                    <thead
                        class="text-xs text-gray-500 uppercase bg-gray-50/50 border-b border-gray-100 dark:bg-gray-700/50 dark:border-gray-700 dark:text-gray-400 transition-colors duration-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-medium w-24">No.</th>
                            <th scope="col" class="px-6 py-4 font-medium w-24">ID</th>
                            <th scope="col" class="px-6 py-4 font-medium">ชื่อหัวข้อรอง</th>
                            <th scope="col" class="px-6 py-4 font-medium truncate">หัวข้อหลัก</th>
                            <th scope="col" class="px-6 py-4 font-medium w-32">คะแนน</th>
                            <th scope="col" class="px-6 py-4 font-medium text-right w-32">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @forelse ($sub_topics as $topic)
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700 transition-colors duration-200 group">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                    {{ $loop->iteration + ($sub_topics->currentPage() - 1) * $sub_topics->perPage() }}
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-500 dark:text-gray-400">
                                    {{ $topic->id }}
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    {{ $topic->name }}
                                </td>
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400 truncate max-w-xs">
                                    {{ $topic->main_topic->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-emerald-600 dark:text-emerald-400 font-semibold">
                                    {{ $topic->score }}
                                </td>
                                <td class="px-6 py-4 text-right space-x-3 text-gray-400 dark:text-gray-500">
                                    <button type="button" title="แก้ไข"
                                        class="hover:text-blue-500 dark:hover:text-blue-400 transition-colors btn-edit-subtopic"
                                        data-modal-target="editSubTopicModal" data-modal-toggle="editSubTopicModal"
                                        data-id="{{ $topic->id }}" data-name="{{ $topic->name }}"
                                        data-mtopic_id="{{ $topic->mtopic_id }}" data-score="{{ $topic->score }}">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <form action="{{ route('admin.topic.subtopic.delete', $topic->id) }}"
                                        method="POST" class="delete-form inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" title="ลบ"
                                            class="delete-button hover:text-red-500 dark:hover:text-red-400 transition-colors"
                                            data-topic="{{ $topic->name }}">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-8 text-center text-gray-500 dark:text-gray-400" colspan="6">
                                    <div class="flex flex-col items-center justify-center">
                                        <i
                                            class="fa-solid fa-folder-open text-4xl text-gray-200 dark:text-gray-600 mb-2"></i>
                                        <p>ไม่พบข้อมูลหัวข้อรอง</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($sub_topics->hasPages())
                <div
                    class="p-4 border-t border-gray-100 bg-gray-50 dark:border-gray-700 dark:bg-gray-800 transition-colors duration-200">
                    {{ $sub_topics->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Create -->
    <div id="createSubTopicModal" tabindex="-1" aria-hidden="true"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto inset-0 h-modal h-full bg-black bg-opacity-50 flex items-center justify-center transition-opacity duration-300">
        <div class="relative w-full max-w-md max-h-full">
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl transition-colors duration-200">
                <div
                    class="flex items-center justify-between px-6 py-4 border-b dark:border-gray-700 rounded-t-2xl bg-gray-50 dark:bg-gray-700/50">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-orange-100 dark:bg-orange-500/20 rounded-full flex items-center justify-center text-orange-500 dark:text-orange-400">
                            <i class="fa-solid fa-list"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">เพิ่มหัวข้อรอง</h3>
                    </div>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:text-gray-900 dark:hover:text-white rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        data-modal-hide="createSubTopicModal">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                @include('admin.topic.subtopic.partials.create-form')
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="editSubTopicModal" tabindex="-1" aria-hidden="true"
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
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">แก้ไขหัวข้อรอง</h3>
                    </div>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:text-gray-900 dark:hover:text-white rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        data-modal-hide="editSubTopicModal">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                @include('admin.topic.subtopic.partials.edit-form')
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                // การดึงข้อมูลลง Edit Modal
                const editButtons = document.querySelectorAll('.btn-edit-subtopic');
                const editForm = document.getElementById('editSubTopicForm');
                const updateBaseUrl = "{{ url('admin/topic/subtopic') }}";

                // ดึงรายชื่อ Main Topic มาทำ Mapping เพื่อใช้แปลง ID เป็นชื่อบนปุ่ม
                const mainTopicMap = {
                    @foreach ($main_topics as $mt)
                        "{{ $mt->id }}": "{{ addslashes($mt->name) }}",
                    @endforeach
                };

                editButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        const mtopicId = this.getAttribute('data-mtopic_id');

                        document.getElementById('edit_name').value = this.getAttribute('data-name');
                        document.getElementById('edit_score').value = this.getAttribute('data-score');
                        document.getElementById('edit_mtopic_id').value = mtopicId;

                        // เปลี่ยนข้อความบนปุ่ม Dropdown ให้ตรงกับหัวข้อที่ถูกเลือก
                        if (mtopicId && mainTopicMap[mtopicId]) {
                            // ตัดข้อความให้สั้นลงกันล้น (จำลอง Str::limit)
                            let text = mainTopicMap[mtopicId];
                            if (text.length > 40) text = text.substring(0, 40) + '...';
                            document.getElementById('editDropdownText').innerText = text;
                        } else {
                            document.getElementById('editDropdownText').innerText = 'เลือกหัวข้อหลัก';
                        }

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
                        const topicName = this.getAttribute('data-topic');

                        Swal.fire({
                            title: 'คุณต้องการลบหัวข้อรอง "' + topicName + '" ใช่หรือไม่?',
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
