<div id="editProjectModal" tabindex="-1" aria-hidden="true"
    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto inset-0 h-modal h-full bg-black bg-opacity-50 flex items-center justify-center transition-opacity duration-300">
    <div class="relative w-full max-w-4xl max-h-[90vh]">
        <div
            class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl transition-colors duration-200 flex flex-col max-h-[90vh]">

            <div
                class="flex items-center justify-between px-6 py-4 border-b dark:border-gray-700 rounded-t-2xl bg-gray-50 dark:bg-gray-700/50 shrink-0">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-yellow-100 dark:bg-yellow-500/20 rounded-full flex items-center justify-center text-yellow-600 dark:text-yellow-500">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">แก้ไขโครงงานวิจัยศิษย์เก่า</h3>
                    </div>
                </div>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:text-gray-900 dark:hover:text-white rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                    data-modal-hide="editProjectModal">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="overflow-y-auto p-6 flex-1 custom-scrollbar">
                <form id="editProjectForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <h4
                            class="text-sm font-bold text-yellow-600 dark:text-yellow-500 uppercase tracking-wider mb-4 border-b border-gray-100 dark:border-gray-700 pb-2 flex items-center gap-2">
                            <i class="fa-solid fa-circle-info"></i> 1. ข้อมูลทั่วไป
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="col-span-2 md:col-span-1">
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">ชื่อโครงงาน
                                    <span class="text-red-500">*</span></label>
                                <input type="text" id="edit_title" name="title" required
                                    class="block w-full px-3 py-2 text-sm text-gray-900 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-yellow-500 focus:border-yellow-500">
                            </div>
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">คำสำคัญ
                                    <span class="text-red-500">*</span></label>
                                <input type="text" id="edit_keyword" name="keyword" required
                                    class="block w-full px-3 py-2 text-sm text-gray-900 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-yellow-500 focus:border-yellow-500">
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">ประเภทโครงงาน
                                    <span class="text-red-500">*</span></label>
                                <select id="edit_project_type_id" name="project_type_id" required
                                    class="block w-full px-3 py-2 text-sm text-gray-900 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">เลือกประเภท</option>
                                    @foreach ($project_types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">ปีการศึกษา
                                    <span class="text-red-500">*</span></label>
                                <select id="edit_academic_year" name="academic_year" required
                                    class="block w-full px-3 py-2 text-sm text-gray-900 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">เลือกปีการศึกษา</option>
                                    @foreach ($academicYears as $year)
                                        <option value="{{ $year->id }}">{{ $year->year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4
                            class="text-sm font-bold text-yellow-600 dark:text-yellow-500 uppercase tracking-wider mb-4 border-b border-gray-100 dark:border-gray-700 pb-2 flex items-center gap-2">
                            <i class="fa-solid fa-file-arrow-up"></i> 2. อัปโหลดไฟล์ (เว้นว่างไว้หากไม่ต้องการเปลี่ยน)
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">ปกโครงงาน</label>
                                <input type="file" name="cover_file" accept="image/*"
                                    class="block w-full text-xs text-gray-900 border border-gray-200 rounded-lg bg-gray-50 dark:text-gray-400 dark:bg-gray-700 dark:border-gray-600">
                                <a id="edit_link_cover" href="#" target="_blank"
                                    class="hidden text-[11px] text-blue-500 hover:underline mt-1"><i
                                        class="fa-solid fa-arrow-up-right-from-square"></i> ดูไฟล์เดิม</a>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Abstract
                                    (PDF)</label>
                                <input type="file" name="abstract_file" accept="application/pdf"
                                    class="block w-full text-xs text-gray-900 border border-gray-200 rounded-lg bg-gray-50 dark:text-gray-400 dark:bg-gray-700 dark:border-gray-600">
                                <a id="edit_link_abstract" href="#" target="_blank"
                                    class="hidden text-[11px] text-blue-500 hover:underline mt-1"><i
                                        class="fa-solid fa-arrow-up-right-from-square"></i> ดูไฟล์เดิม</a>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">ไฟล์โครงงาน
                                    (PDF)</label>
                                <input type="file" name="project_file" accept="application/pdf"
                                    class="block w-full text-xs text-gray-900 border border-gray-200 rounded-lg bg-gray-50 dark:text-gray-400 dark:bg-gray-700 dark:border-gray-600">
                                <a id="edit_link_project" href="#" target="_blank"
                                    class="hidden text-[11px] text-blue-500 hover:underline mt-1"><i
                                        class="fa-solid fa-arrow-up-right-from-square"></i> ดูไฟล์เดิม</a>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h4
                            class="text-sm font-bold text-yellow-600 dark:text-yellow-500 uppercase tracking-wider mb-4 border-b border-gray-100 dark:border-gray-700 pb-2 flex items-center gap-2">
                            <i class="fa-solid fa-users"></i> 3. ข้อมูลผู้จัดทำ
                        </h4>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">อาจารย์ที่ปรึกษา
                                    <span class="text-red-500">*</span></label>
                                <select id="edit_advisor_id" name="advisor_id" required
                                    class="select2-search block w-full px-3 py-2 text-sm border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600">
                                    <option value="">เลือกอาจารย์ที่ปรึกษา</option>
                                    @foreach ($advisors as $advisor)
                                        <option value="{{ $advisor->id }}">{{ $advisor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">นักศึกษาในโครงงาน
                                    <span class="text-red-500">*</span></label>
                                <select id="edit_student_ids" name="student_ids[]" multiple required
                                    class="select2-multi block w-full px-3 py-2 text-sm border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600">
                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}">{{ $student->s_id }} -
                                            {{ $student->s_fname }} {{ $student->s_lname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t dark:border-gray-700 shrink-0">
                <button type="button" data-modal-hide="editProjectModal"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">ยกเลิก</button>
                <button type="submit" form="editProjectForm"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-yellow-500 rounded-xl hover:bg-yellow-600 shadow-sm flex items-center gap-2 transition-colors">
                    <i class="fa-solid fa-save"></i> บันทึกการแก้ไข
                </button>
            </div>
        </div>
    </div>
</div>
