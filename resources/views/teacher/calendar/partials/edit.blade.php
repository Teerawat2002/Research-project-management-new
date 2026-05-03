<!-- Modal Edit -->
<div id="editCalendarModal" tabindex="-1" aria-hidden="true"
    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto inset-0 h-modal h-full bg-black bg-opacity-50 flex items-center justify-center transition-opacity duration-300">
    <div class="relative w-full max-w-2xl max-h-[90vh]">
        <div
            class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl transition-colors duration-200 flex flex-col max-h-[90vh]">

            <!-- Modal Header -->
            <div
                class="flex items-center justify-between px-6 py-4 border-b dark:border-gray-700 rounded-t-2xl bg-gray-50 dark:bg-gray-700/50 shrink-0">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-yellow-100 dark:bg-yellow-500/20 rounded-full flex items-center justify-center text-yellow-600 dark:text-yellow-500">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">แก้ไขข้อมูล</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">อัปเดตกำหนดการปฏิทินการศึกษา</p>
                    </div>
                </div>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:text-gray-900 dark:hover:text-white rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                    data-modal-hide="editCalendarModal">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="overflow-y-auto p-6 flex-1 custom-scrollbar">
                <form id="editCalendarForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="edit_ac_id"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">ปีการศึกษา <span
                                class="text-red-500">*</span></label>
                        <select name="ac_id" id="edit_ac_id" required
                            class="block w-full px-4 py-2.5 text-sm text-gray-900 border border-gray-200 rounded-xl bg-gray-50 focus:ring-yellow-500 focus:border-yellow-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-yellow-500">
                            <option value="" disabled>-- เลือกปีการศึกษา --</option>
                            @foreach ($academicYears as $year)
                                <option value="{{ $year->id }}">{{ $year->year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="edit_start_date"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">วันที่เริ่ม
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="start_date" id="edit_start_date" required placeholder="DD-MM-YYYY"
                                class="block w-full px-4 py-2.5 text-sm text-gray-900 border border-gray-200 rounded-xl bg-gray-50 focus:ring-yellow-500 focus:border-yellow-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div>
                            <label for="edit_end_date"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">วันที่สิ้นสุด
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="end_date" id="edit_end_date" required placeholder="DD-MM-YYYY"
                                class="block w-full px-4 py-2.5 text-sm text-gray-900 border border-gray-200 rounded-xl bg-gray-50 focus:ring-yellow-500 focus:border-yellow-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="edit_title"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">รายการ / หัวข้อ
                            <span class="text-red-500">*</span></label>
                        <div class="flex shadow-sm rounded-xl w-full">
                            <span
                                class="inline-flex shrink-0 items-center px-4 text-sm text-gray-500 bg-gray-100 border border-e-0 border-gray-200 rounded-s-xl dark:bg-gray-700 dark:border-gray-600">
                                <i class="fa-solid fa-list-check"></i>
                            </span>
                            <input type="text" name="title" id="edit_title" required
                                class="rounded-none rounded-e-xl block flex-1 min-w-0 w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200">
                        </div>
                    </div>

                    <div>
                        <label for="edit_description"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">รายละเอียด</label>
                        <textarea name="description" id="edit_description" rows="3"
                            class="block w-full px-4 py-2.5 text-sm text-gray-900 border border-gray-200 rounded-xl bg-gray-50 focus:ring-yellow-500 focus:border-yellow-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white custom-scrollbar"></textarea>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t dark:border-gray-700 shrink-0">
                <button type="button" data-modal-hide="editCalendarModal"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">ยกเลิก</button>
                <button type="submit" form="editCalendarForm"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-yellow-500 rounded-xl hover:bg-yellow-600 shadow-sm flex items-center gap-2 transition-colors">
                    <i class="fa-solid fa-save"></i> บันทึกการแก้ไข
                </button>
            </div>
        </div>
    </div>
</div>
