<form id="editSubSubTopicForm" method="POST" action="" class="p-6 transition-colors duration-200">
    @csrf
    @method('PUT')

    <div class="mb-5">
        <label
            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5 transition-colors duration-200">
            เลือกหัวข้อรอง <span class="text-red-500 dark:text-red-400">*</span>
        </label>

        <input type="hidden" name="stopic_id" id="edit_stopic_id" required>

        <div class="flex shadow-sm rounded-xl w-full">
            <span
                class="inline-flex shrink-0 items-center px-4 text-sm text-gray-500 bg-gray-100 border border-e-0 border-gray-200 rounded-s-xl dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600 transition-colors duration-200">
                <i class="fa-solid fa-layer-group"></i>
            </span>

            <div class="relative flex-1 min-w-0">
                <button type="button" id="editDropdownBtn" data-dropdown-toggle="editDropdownMenu"
                    class="flex items-center justify-between w-full px-4 py-2.5 text-sm text-gray-700 bg-white border border-gray-200 rounded-e-xl focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400 hover:bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600 transition-colors duration-150 ease-in-out">
                    <span id="editDropdownText" class="truncate flex-1 text-left pr-2">เลือกหัวข้อรอง</span>
                    <i class="fa-solid fa-chevron-down text-xs text-gray-400 dark:text-gray-500 shrink-0"></i>
                </button>

                <div id="editDropdownMenu"
                    class="z-50 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-lg w-full max-h-60 overflow-y-auto border border-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:divide-gray-600">
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="editDropdownBtn">
                        @foreach ($subtopics as $st)
                            <li>
                                <a href="#"
                                    onclick="event.preventDefault(); document.getElementById('edit_stopic_id').value='{{ $st->id }}'; document.getElementById('editDropdownText').innerText='{{ addslashes($st->name) }}'; document.getElementById('editDropdownBtn').click();"
                                    class="block px-4 py-2 hover:bg-orange-50 hover:text-orange-600 dark:hover:bg-gray-600 dark:hover:text-white transition-colors"
                                    title="{{ $st->name }}">
                                    {{ \Illuminate\Support\Str::limit($st->name, 60, '...') }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @error('stopic_id')
            <p class="text-red-500 dark:text-red-400 text-sm mt-1.5">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-5">
        <label for="edit_name"
            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5 transition-colors duration-200">
            ชื่อหัวข้อย่อย <span class="text-red-500 dark:text-red-400">*</span>
        </label>
        <div class="flex shadow-sm rounded-xl w-full">
            <span
                class="inline-flex shrink-0 items-center px-4 text-sm text-gray-500 bg-gray-100 border border-e-0 border-gray-200 rounded-s-xl dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600 transition-colors duration-200">
                <i class="fa-solid fa-tag"></i>
            </span>
            <input type="text" id="edit_name" name="name" required
                class="rounded-none rounded-e-xl block flex-1 min-w-0 w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-500 transition-colors duration-200"
                placeholder="เช่น การจัดรูปแบบหน้าจอ">
        </div>
        @error('name')
            <p class="text-red-500 dark:text-red-400 text-sm mt-1.5">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-6">
        <label for="edit_score"
            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5 transition-colors duration-200">
            คะแนน <span class="text-red-500 dark:text-red-400">*</span>
        </label>
        <div class="flex shadow-sm rounded-xl w-full">
            <span
                class="inline-flex shrink-0 items-center px-4 text-sm text-gray-500 bg-gray-100 border border-e-0 border-gray-200 rounded-s-xl dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600 transition-colors duration-200">
                <i class="fa-solid fa-star-half-stroke"></i>
            </span>
            <input type="number" id="edit_score" name="score" required min="0" step="0.5"
                class="rounded-none rounded-e-xl block flex-1 min-w-0 w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-500 transition-colors duration-200"
                placeholder="เช่น 5">
        </div>
        @error('score')
            <p class="text-red-500 dark:text-red-400 text-sm mt-1.5">{{ $message }}</p>
        @enderror
    </div>

    <hr class="border-gray-100 dark:border-gray-700 mb-4 transition-colors duration-200">

    <div class="flex items-center justify-end gap-3">
        <button type="button" data-modal-hide="editSubSubTopicModal"
            class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-200">
            ยกเลิก
        </button>
        <button type="submit"
            class="px-5 py-2.5 text-sm font-medium text-white bg-orange-500 rounded-xl hover:bg-orange-600 dark:hover:bg-orange-600 shadow-sm flex items-center gap-2 transition-colors duration-200">
            <i class="fa-solid fa-save"></i> บันทึกการแก้ไข
        </button>
    </div>
</form>
