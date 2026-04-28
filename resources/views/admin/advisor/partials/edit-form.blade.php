<form id="editAdvisorForm" method="POST" action="" class="p-6 transition-colors duration-200">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label
            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5 transition-colors duration-200">
            รหัสอาจารย์ (Advisor ID) <span class="text-red-500 dark:text-red-400">*</span>
        </label>
        <input type="text" id="edit_a_id" name="a_id" required
            class="block w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-500 transition-colors duration-200">
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
        <div>
            <label
                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5 transition-colors duration-200">
                ชื่อ <span class="text-red-500 dark:text-red-400">*</span>
            </label>
            <input type="text" id="edit_fname" name="a_fname" required
                class="block w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-500 transition-colors duration-200">
        </div>
        <div>
            <label
                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5 transition-colors duration-200">
                นามสกุล <span class="text-red-500 dark:text-red-400">*</span>
            </label>
            <input type="text" id="edit_lname" name="a_lname" required
                class="block w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-500 transition-colors duration-200">
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
        <div>
            <label
                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5 transition-colors duration-200">
                รหัสผ่านใหม่ <span
                    class="text-xs text-gray-400 dark:text-gray-500 font-normal ml-1">(เว้นว่างหากใช้รหัสเดิม)</span>
            </label>
            <input type="password" id="edit_password" name="password"
                class="block w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-500 dark:placeholder-gray-400 transition-colors duration-200"
                placeholder="••••••••">
        </div>
        <div>
            <label
                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5 transition-colors duration-200">
                ยืนยันรหัสผ่านใหม่
            </label>
            <input type="password" id="edit_password_confirmation" name="password_confirmation"
                class="block w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-500 dark:placeholder-gray-400 transition-colors duration-200"
                placeholder="••••••••">
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <div>
            <label
                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5 transition-colors duration-200">
                ประเภทผู้ใช้งาน <span class="text-red-500 dark:text-red-400">*</span>
            </label>
            <select id="edit_type" name="a_type" required
                class="block w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-500 transition-colors duration-200">
                <option value="advisor" class="dark:bg-gray-700">Advisor</option>
                <option value="teacher" class="dark:bg-gray-700">Teacher</option>
                <option value="admin" class="dark:bg-gray-700">Admin</option>
            </select>
        </div>
        <div>
            <label
                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5 transition-colors duration-200">
                สาขาวิชา <span class="text-red-500 dark:text-red-400">*</span>
            </label>
            <select id="edit_m_id" name="m_id" required
                class="block w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-500 transition-colors duration-200">
                @foreach ($majors as $major)
                    <option value="{{ $major->id }}" class="dark:bg-gray-700">{{ $major->m_name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <hr class="border-gray-100 dark:border-gray-700 mb-4 transition-colors duration-200">

    <div class="flex items-center justify-end gap-3">
        <button type="button" data-modal-hide="editAdvisorModal"
            class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-200">
            ยกเลิก
        </button>
        <button type="submit"
            class="px-5 py-2.5 text-sm font-medium text-white bg-orange-500 rounded-xl hover:bg-orange-600 dark:hover:bg-orange-600 shadow-sm flex items-center gap-2 transition-colors duration-200">
            <i class="fa-solid fa-save"></i> บันทึกการแก้ไข
        </button>
    </div>
</form>
