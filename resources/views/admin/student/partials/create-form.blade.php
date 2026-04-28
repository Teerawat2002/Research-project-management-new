<form id="createStudentForm" method="POST" action="{{ route('admin.student.store') }}"
    class="p-6 transition-colors duration-200">
    @csrf

    <div class="mb-4">
        <label
            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5 transition-colors duration-200">
            รหัสนักศึกษา (Student ID) <span class="text-red-500 dark:text-red-400">*</span>
        </label>
        <input type="text" name="s_id" required
            class="block w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-500 transition-colors duration-200">
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
        <div>
            <label
                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5 transition-colors duration-200">
                ชื่อ <span class="text-red-500 dark:text-red-400">*</span>
            </label>
            <input type="text" name="s_fname" required
                class="block w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-500 transition-colors duration-200">
        </div>
        <div>
            <label
                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5 transition-colors duration-200">
                นามสกุล <span class="text-red-500 dark:text-red-400">*</span>
            </label>
            <input type="text" name="s_lname" required
                class="block w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-500 transition-colors duration-200">
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
        <div>
            <label
                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5 transition-colors duration-200">
                รหัสผ่าน <span class="text-red-500 dark:text-red-400">*</span>
            </label>
            <input type="password" id="create_password" name="password" required
                class="block w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-500 transition-colors duration-200">
        </div>
        <div>
            <label
                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5 transition-colors duration-200">
                ยืนยันรหัสผ่าน <span class="text-red-500 dark:text-red-400">*</span>
            </label>
            <input type="password" id="create_password_confirmation" name="password_confirmation" required
                class="block w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-500 transition-colors duration-200">
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <div>
            <label
                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5 transition-colors duration-200">
                สถานะ <span class="text-red-500 dark:text-red-400">*</span>
            </label>
            <select name="status" required
                class="block w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-500 transition-colors duration-200">
                <option value="" disabled selected class="dark:bg-gray-700">เลือกสถานะ</option>
                <option value="active" class="dark:bg-gray-700">ปกติ</option>
                <option value="graduated" class="dark:bg-gray-700">จบการศึกษา</option>
            </select>
        </div>
        <div>
            <label
                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5 transition-colors duration-200">
                สาขาวิชา <span class="text-red-500 dark:text-red-400">*</span>
            </label>
            <select name="m_id" required
                class="block w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-500 transition-colors duration-200">
                <option value="" disabled selected class="dark:bg-gray-700">เลือกสาขาวิชา</option>
                @foreach ($majors as $major)
                    <option value="{{ $major->id }}" class="dark:bg-gray-700">{{ $major->m_name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <hr class="border-gray-100 dark:border-gray-700 mb-4 transition-colors duration-200">

    <div class="flex items-center justify-end gap-3">
        <button type="button" data-modal-hide="createStudentModal"
            class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-200">
            ยกเลิก
        </button>
        <button type="submit"
            class="px-5 py-2.5 text-sm font-medium text-white bg-orange-500 rounded-xl hover:bg-orange-600 dark:hover:bg-orange-600 shadow-sm flex items-center gap-2 transition-colors duration-200">
            <i class="fa-solid fa-save"></i> บันทึกข้อมูล
        </button>
    </div>
</form>
