<form id="editMainTopicForm" method="POST" action="" class="p-6 transition-colors duration-200">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label for="edit_name"
            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5 transition-colors duration-200">
            ชื่อหัวข้อ <span class="text-red-500 dark:text-red-400">*</span>
        </label>
        <input type="text" id="edit_name" name="name" required
            class="block w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-500 transition-colors duration-200"
            placeholder="เช่น โครงสร้างระบบ">
        @error('name')
            <p class="text-red-500 dark:text-red-400 text-sm mt-1.5">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-6">
        <label for="edit_score"
            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5 transition-colors duration-200">
            คะแนน <span class="text-red-500 dark:text-red-400">*</span>
        </label>
        <input type="number" id="edit_score" name="score" required min="0" step="0.5"
            class="block w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-500 transition-colors duration-200"
            placeholder="เช่น 10">
        @error('score')
            <p class="text-red-500 dark:text-red-400 text-sm mt-1.5">{{ $message }}</p>
        @enderror
    </div>

    <hr class="border-gray-100 dark:border-gray-700 mb-4 transition-colors duration-200">

    <div class="flex items-center justify-end gap-3">
        <button type="button" data-modal-hide="editMainTopicModal"
            class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-200">
            ยกเลิก
        </button>
        <button type="submit"
            class="px-5 py-2.5 text-sm font-medium text-white bg-orange-500 rounded-xl hover:bg-orange-600 dark:hover:bg-orange-600 shadow-sm flex items-center gap-2 transition-colors duration-200">
            <i class="fa-solid fa-save"></i> บันทึกการแก้ไข
        </button>
    </div>
</form>
