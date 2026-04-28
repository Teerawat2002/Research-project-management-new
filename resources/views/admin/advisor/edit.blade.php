<x-app-layout>
    <div class="mt-8 py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm border border-gray-100 sm:rounded-2xl">

                <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">แก้ไขข้อมูลอาจารย์</h3>
                        <p class="text-sm text-gray-500 mt-1">อัปเดตรายละเอียดบัญชีผู้ใช้ในระบบ</p>
                    </div>
                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center text-orange-500">
                        <i class="fa-solid fa-user-pen"></i>
                    </div>
                </div>

                <div class="p-8">
                    <form method="POST" action="{{ route('admin.advisor.update', $advisorDetail->id) }}"
                        id="advisorForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-5">
                            <label for="a_id" class="block text-sm font-semibold text-gray-700 mb-1.5">รหัสอาจารย์
                                (Advisor ID) <span class="text-red-500">*</span></label>
                            <input type="text" id="a_id" name="a_id" required
                                value="{{ old('a_id', $advisorDetail->a_id) }}"
                                class="block w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition-all duration-200">

                            @error('a_id')
                                <div class="mt-2 text-sm text-red-500 flex items-center gap-1">
                                    <i class="fa-solid fa-circle-exclamation"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                            <div>
                                <label for="a_fname" class="block text-sm font-semibold text-gray-700 mb-1.5">ชื่อ
                                    <span class="text-red-500">*</span></label>
                                <input type="text" id="a_fname" name="a_fname" required
                                    value="{{ old('a_fname', $advisorDetail->a_fname) }}"
                                    class="block w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition-all duration-200">
                            </div>

                            <div>
                                <label for="a_lname" class="block text-sm font-semibold text-gray-700 mb-1.5">นามสกุล
                                    <span class="text-red-500">*</span></label>
                                <input type="text" id="a_lname" name="a_lname" required
                                    value="{{ old('a_lname', $advisorDetail->a_lname) }}"
                                    class="block w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition-all duration-200">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                            <div>
                                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    รหัสผ่านใหม่ <span
                                        class="text-xs text-gray-400 font-normal ml-1">(เว้นว่างไว้หากใช้รหัสผ่านเดิม)</span>
                                </label>
                                <input type="password" id="password" name="password"
                                    class="block w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition-all duration-200"
                                    placeholder="••••••••">

                                @error('password')
                                    <div class="mt-2 text-sm text-red-500 flex items-center gap-1">
                                        <i class="fa-solid fa-circle-exclamation"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation"
                                    class="block text-sm font-semibold text-gray-700 mb-1.5">ยืนยันรหัสผ่านใหม่</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="block w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition-all duration-200"
                                    placeholder="••••••••">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
                            <div>
                                <label for="a_type"
                                    class="block text-sm font-semibold text-gray-700 mb-1.5">ประเภทผู้ใช้งาน (Role)
                                    <span class="text-red-500">*</span></label>
                                <select id="a_type" name="a_type" required
                                    class="block w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition-all duration-200 cursor-pointer appearance-none">
                                    <option value="advisor"
                                        {{ old('a_type', $advisorDetail->a_type) == 'advisor' ? 'selected' : '' }}>
                                        Advisor</option>
                                    <option value="teacher"
                                        {{ old('a_type', $advisorDetail->a_type) == 'teacher' ? 'selected' : '' }}>
                                        Teacher</option>
                                    <option value="admin"
                                        {{ old('a_type', $advisorDetail->a_type) == 'admin' ? 'selected' : '' }}>Admin
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label for="m_id" class="block text-sm font-semibold text-gray-700 mb-1.5">สาขาวิชา
                                    (Major) <span class="text-red-500">*</span></label>
                                <select id="m_id" name="m_id" required
                                    class="block w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition-all duration-200 cursor-pointer appearance-none">
                                    <option value="" disabled>เลือกสาขาวิชา</option>
                                    @foreach ($majors as $major)
                                        <option value="{{ $major->id }}"
                                            {{ old('m_id', $advisorDetail->m_id) == $major->id ? 'selected' : '' }}>
                                            {{ $major->m_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr class="border-gray-100 mb-6">

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.advisor.index') }}"
                                class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-all duration-200">
                                ยกเลิก
                            </a>
                            <button type="submit"
                                class="px-6 py-2.5 text-sm font-medium text-white bg-orange-500 border border-transparent rounded-xl hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200 flex items-center gap-2 shadow-sm">
                                <i class="fa-solid fa-save"></i> บันทึกการแก้ไข
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('advisorForm');
            form.addEventListener('submit', function(e) {
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('password_confirmation').value;

                // Remove any previous error message if exists
                const prevError = document.getElementById('passwordError');
                if (prevError) {
                    prevError.remove();
                }

                // ตรวจสอบเฉพาะกรณีที่มีการพิมพ์รหัสผ่านใหม่เข้ามา
                if (password && password !== confirmPassword) {
                    e.preventDefault();
                    // Create error message block (Styled to match the new theme)
                    const errorDiv = document.createElement('div');
                    errorDiv.id = 'passwordError';
                    errorDiv.className = 'mt-2 text-sm text-red-500 flex items-center gap-1';
                    errorDiv.innerHTML = `
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน!</span>
                    `;
                    // Insert the error message below the confirm password field
                    const confirmInput = document.getElementById('password_confirmation');
                    confirmInput.parentNode.appendChild(errorDiv);
                }
            });
        });
    </script>
</x-app-layout>
