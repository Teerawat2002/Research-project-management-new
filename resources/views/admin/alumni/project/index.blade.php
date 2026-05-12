<x-app-layout>
    <div class="p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200">
                    Alumni Projects
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                    ข้อมูลโครงงานวิจัยศิษย์เก่า (มุมมองไอคอนโฟลเดอร์)
                </p>
            </div>

            <div class="flex gap-2">
                <button type="button" data-modal-target="createProjectModal" data-modal-toggle="createProjectModal"
                    class="bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-xl text-sm px-4 py-2 flex items-center shadow-sm transition-colors duration-200">
                    <i class="fa-solid fa-folder-plus mr-2"></i> เพิ่มโครงงานวิจัย
                </button>
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors duration-200">

            <div
                class="p-5 border-b border-gray-50 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm rounded-t-xl transition-colors duration-200">
                <form id="filterForm" method="GET" action="{{ route('admin.alumni.project.index') }}"
                    class="flex flex-col sm:flex-row justify-between items-center gap-4">

                    <input type="hidden" name="project_type_id" id="hidden_project_type_id"
                        value="{{ request('project_type_id') }}">

                    <div class="relative w-full sm:w-80">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <input type="text" name="search" id="table-search" value="{{ request('search') }}"
                            class="block w-full p-2.5 pl-10 text-sm text-gray-900 border border-gray-200 rounded-lg bg-gray-50 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-orange-500 dark:focus:border-orange-500 transition-colors duration-200"
                            placeholder="ค้นหาโครงงานศิษย์เก่า...">
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        @php
                            $selectedTypeName = 'ทุกประเภทโครงงาน';
                            if (request('project_type_id')) {
                                foreach ($project_types as $type) {
                                    if (request('project_type_id') == $type->id) {
                                        $selectedTypeName = $type->name;
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
                                        <span class="truncate">{{ $selectedTypeName }}</span>
                                        <i
                                            class="fa-solid fa-chevron-down text-xs text-gray-400 dark:text-gray-500 ml-2"></i>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link href="#"
                                        onclick="event.preventDefault(); document.getElementById('hidden_project_type_id').value=''; document.getElementById('filterForm').submit();"
                                        class="{{ request('project_type_id') == '' ? 'bg-orange-50 text-orange-600 font-bold dark:bg-gray-700 dark:text-orange-400' : 'text-gray-700 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white' }}">
                                        ทุกประเภทโครงงาน
                                    </x-dropdown-link>

                                    <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

                                    @foreach ($project_types as $type)
                                        <x-dropdown-link href="#"
                                            onclick="event.preventDefault(); document.getElementById('hidden_project_type_id').value='{{ $type->id }}'; document.getElementById('filterForm').submit();"
                                            class="{{ request('project_type_id') == $type->id ? 'bg-orange-50 text-orange-600 font-bold dark:bg-gray-700 dark:text-orange-400' : 'text-gray-700 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white' }}"
                                            title="{{ $type->name }}">
                                            {{ \Illuminate\Support\Str::limit($type->name, 40, '...') }}
                                        </x-dropdown-link>
                                    @endforeach
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </form>
            </div>

            <div class="p-6 bg-white dark:bg-gray-800 rounded-b-xl min-h-[50vh]">

                <style>
                    details>summary {
                        list-style: none;
                    }

                    details>summary::-webkit-details-marker {
                        display: none;
                    }
                </style>

                @if (empty($groupedProjects) || count($groupedProjects) === 0)
                    <div class="flex flex-col items-center justify-center py-20 text-gray-500 dark:text-gray-400">
                        <i class="fa-regular fa-folder-open text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                        <p>โฟลเดอร์ว่างเปล่า - ไม่พบข้อมูลโครงงาน</p>
                    </div>
                @else
                    @foreach ($groupedProjects as $typeName => $projects)
                        <details
                            class="group mb-8 last:mb-0 border border-gray-100 dark:border-gray-700 rounded-xl bg-gray-50/50 dark:bg-gray-900/30 overflow-hidden"
                            open>

                            <summary
                                class="flex items-center cursor-pointer bg-gray-100 dark:bg-gray-800 hover:bg-orange-50 dark:hover:bg-gray-700 p-4 transition-colors select-none">
                                <i
                                    class="fa-solid fa-chevron-right text-xs text-gray-400 mr-3 transition-transform group-open:rotate-90"></i>
                                <i class="fa-solid fa-layer-group text-orange-500 mr-2"></i>
                                <span
                                    class="font-bold text-gray-800 dark:text-gray-200 text-sm uppercase tracking-wider">{{ $typeName }}</span>
                                <span
                                    class="ml-3 text-xs font-medium text-gray-500 bg-white dark:bg-gray-600 border border-gray-200 dark:border-gray-500 px-2 py-0.5 rounded-full normal-case shadow-sm">
                                    {{ $projects->total() }} โครงงาน
                                </span>
                            </summary>

                            <div class="p-5">
                                <div
                                    class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 xl:grid-cols-8 gap-4">
                                    @foreach ($projects as $project)
                                        <div
                                            class="group/icon relative flex flex-col items-center justify-start p-3 rounded-xl hover:bg-white dark:hover:bg-gray-700/50 hover:shadow-sm border border-transparent hover:border-gray-200 dark:hover:border-gray-600 transition-all cursor-pointer text-center">

                                            <div
                                                class="absolute top-1 right-1 opacity-0 group-hover/icon:opacity-100 transition-opacity flex flex-col gap-1 z-10">
                                                <button type="button" title="แก้ไข"
                                                    data-modal-target="editProjectModal"
                                                    data-modal-toggle="editProjectModal"
                                                    class="btn-edit-project w-7 h-7 bg-white dark:bg-gray-800 text-blue-500 rounded-md shadow flex items-center justify-center hover:bg-blue-50 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 transition-colors"
                                                    data-id="{{ $project->id }}" data-title="{{ $project->title }}"
                                                    data-keyword="{{ $project->keyword }}"
                                                    data-project_type_id="{{ $project->project_type_id }}"
                                                    data-academic_year="{{ $project->projectGroup?->ac_id ?? '' }}"
                                                    data-advisor_id="{{ $project->advisor_id }}"
                                                    data-student_ids="{{ json_encode($project->projectGroup?->group_members?->pluck('s_id')->toArray() ?? []) }}"
                                                    data-cover_file="{{ $project->files?->first()?->cover_file ?? '' }}"
                                                    data-abstract_file="{{ $project->files?->first()?->abstract_file ?? '' }}"
                                                    data-project_file="{{ $project->files?->first()?->project_file ?? '' }}">
                                                    <i class="fa-solid fa-pen text-xs"></i>
                                                </button>

                                                <form action="{{ route('admin.alumni.project.delete', $project->id) }}"
                                                    method="POST" class="delete-form m-0"
                                                    onclick="event.stopPropagation();">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" title="ลบ"
                                                        class="delete-button w-7 h-7 bg-white dark:bg-gray-800 text-red-500 rounded-md shadow flex items-center justify-center hover:bg-red-50 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 transition-colors"
                                                        data-topic="{{ $project->title }}">
                                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                                    </button>
                                                </form>
                                            </div>

                                            <div class="relative mb-2">
                                                <i
                                                    class="fa-solid fa-folder text-[70px] text-orange-400 drop-shadow-sm group-hover/icon:scale-105 transition-transform duration-200"></i>
                                            </div>

                                            <span
                                                class="text-xs font-medium text-gray-800 dark:text-gray-200 line-clamp-2 w-full leading-tight"
                                                title="{{ $project->title }}">
                                                {{ $project->title ?? 'ไม่มีชื่อโครงงาน' }}
                                            </span>

                                            @if ($project->advisor)
                                                <span
                                                    class="text-[10px] text-gray-400 dark:text-gray-500 mt-1 line-clamp-1 w-full"
                                                    title="{{ $project->advisor->name ?? '' }}">
                                                    {{ $project->advisor->name ?? '' }}
                                                </span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                @if ($projects->hasPages())
                                    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                                        {{ $projects->links() }}
                                    </div>
                                @endif
                            </div>
                        </details>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    @include('admin.alumni.project.partials.create')
    @include('admin.alumni.project.partials.edit')

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                // Script สำหรับโยนข้อมูลใส่ Modal Edit
                const editButtons = document.querySelectorAll('.btn-edit-project');
                const editForm = document.getElementById('editProjectForm');
                const baseUrl = "{{ url('admin/alumni/project') }}";

                editButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');

                        document.getElementById('edit_title').value = this.getAttribute('data-title');
                        document.getElementById('edit_keyword').value = this.getAttribute(
                            'data-keyword');
                        document.getElementById('edit_project_type_id').value = this.getAttribute(
                            'data-project_type_id');
                        document.getElementById('edit_academic_year').value = this.getAttribute(
                            'data-academic_year');

                        if (window.jQuery) {
                            $('#edit_advisor_id').val(this.getAttribute('data-advisor_id')).trigger(
                                'change');
                            let students = JSON.parse(this.getAttribute('data-student_ids') || '[]');
                            $('#edit_student_ids').val(students).trigger('change');
                        }

                        const setFileLink = (type, path) => {
                            const linkEl = document.getElementById(`edit_link_${type}`);
                            if (path) {
                                linkEl.href = `{{ asset('storage') }}/${path}`;
                                linkEl.classList.remove('hidden');
                                linkEl.classList.add('inline-flex');
                            } else {
                                linkEl.classList.add('hidden');
                                linkEl.classList.remove('inline-flex');
                            }
                        };

                        setFileLink('cover', this.getAttribute('data-cover_file'));
                        setFileLink('abstract', this.getAttribute('data-abstract_file'));
                        setFileLink('project', this.getAttribute('data-project_file'));

                        if (editForm) {
                            editForm.action = baseUrl + '/' + id;
                        }
                    });
                });

                // SweetAlert2 สำหรับลบ
                document.querySelectorAll('.delete-button').forEach(function(button) {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const form = this.closest('form');
                        const topic = this.getAttribute('data-topic');

                        Swal.fire({
                            title: 'คุณต้องการลบแฟ้ม "' + topic + '" ใช่หรือไม่?',
                            text: "เมื่อลบแล้วข้อมูลจะหายไปจากระบบ!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#f97316',
                            cancelButtonColor: '#9ca3af',
                            confirmButtonText: 'ใช่, ลบเลย!',
                            cancelButtonText: 'ยกเลิก'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });

                // แจ้งเตือนสถานะ
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
