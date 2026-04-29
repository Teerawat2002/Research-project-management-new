<form id="createCourseForm" method="POST" action="{{ route('admin.course.store') }}"
    class="p-6 transition-colors duration-200">
    @csrf

    <div class="mb-6">
        <label for="name"
            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5 transition-colors duration-200">
            ชื่อรายวิชา <span class="text-red-500 dark:text-red-400">*</span>
        </label>
        <div class="flex">
            <span
                class="inline-flex items-center px-3 text-sm text-gray-500 bg-gray-100 border border-e-0 border-gray-200 rounded-s-xl dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600 transition-colors duration-200">
                <i class="fa-solid fa-bookmark"></i>
            </span>
            <input type="text" id="name" name="name" required
                class="rounded-none rounded-e-xl block w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-500 transition-colors duration-200"
                placeholder="เช่น โครงงานเทคโนโลยีสารสนเทศ 1" value="{{ old('name') }}">
        </div>
    </div>

    <hr class="border-gray-100 dark:border-gray-700 mb-4 transition-colors duration-200">

    <div class="flex items-center justify-end gap-3">
        <button type="button" data-modal-hide="createCourseModal"
            class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-200">
            ยกเลิก
        </button>
        <button type="submit"
            class="px-5 py-2.5 text-sm font-medium text-white bg-orange-500 rounded-xl hover:bg-orange-600 dark:hover:bg-orange-600 shadow-sm flex items-center gap-2 transition-colors duration-200">
            <i class="fa-solid fa-save"></i> บันทึกข้อมูล
        </button>
    </div>
</form>
