<x-app-layout>
    <div class="p-6 max-w-5xl mx-auto mt-8">

        <!-- Header -->
        <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200">
                    แก้ไขโครงงานวิจัยศิษย์เก่า
                </h1>
                <p class="text-sm font-medium text-orange-500 dark:text-orange-400 mt-1 transition-colors duration-200">
                    ID: {{ $alumniProject->id }}
                </p>
            </div>
            <a href="{{ route('admin.alumni.project.index') }}"
                class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200 flex items-center gap-2 text-sm font-medium">
                <i class="fa-solid fa-arrow-left"></i> ย้อนกลับ
            </a>
        </div>

        <form action="{{ route('admin.alumni.project.update', $alumniProject->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div
                class="bg-white dark:bg-gray-800 p-6 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl transition-colors duration-200 mb-6">

                <!-- 1. ข้อมูลทั่วไป -->
                <div class="mb-8">
                    <h3
                        class="text-sm font-bold text-orange-500 uppercase tracking-wider mb-4 border-b border-gray-100 dark:border-gray-700 pb-2 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info"></i> 1. ข้อมูลทั่วไปของโครงงาน
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- ชื่อโครงงาน -->
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                ชื่อโครงงาน (ภาษาไทย) <span class="text-red-500">*</span>
                            </label>
                            <div class="flex shadow-sm rounded-xl w-full">
                                <span
                                    class="inline-flex shrink-0 items-center px-4 text-sm text-gray-500 bg-gray-100 border border-e-0 border-gray-200 rounded-s-xl dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">
                                    <i class="fa-solid fa-book"></i>
                                </span>
                                <input type="text" name="title" value="{{ old('title', $alumniProject->title) }}"
                                    required
                                    class="rounded-none rounded-e-xl block flex-1 min-w-0 w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-500 transition-colors">
                            </div>
                            @error('title')
                                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- คำสำคัญ -->
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                คำสำคัญ (Keywords) <span class="text-red-500">*</span>
                            </label>
                            <div class="flex shadow-sm rounded-xl w-full">
                                <span
                                    class="inline-flex shrink-0 items-center px-4 text-sm text-gray-500 bg-gray-100 border border-e-0 border-gray-200 rounded-s-xl dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">
                                    <i class="fa-solid fa-tags"></i>
                                </span>
                                <input type="text" name="keyword"
                                    value="{{ old('keyword', $alumniProject->keyword) }}" required
                                    class="rounded-none rounded-e-xl block flex-1 min-w-0 w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-500 transition-colors">
                            </div>
                        </div>

                        <!-- ประเภทโครงงาน -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                ประเภทโครงงาน <span class="text-red-500">*</span>
                            </label>
                            <select name="project_type_id" class="select2 w-full" required>
                                @foreach ($project_types as $type)
                                    <option value="{{ $type->id }}"
                                        {{ old('project_type_id', $alumniProject->project_type_id) == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- ปีการศึกษา -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                ปีการศึกษา <span class="text-red-500">*</span>
                            </label>
                            <select name="academic_year" class="select2 w-full" required>
                                @foreach ($academicYears as $year)
                                    <option value="{{ $year->id }}"
                                        {{ old('academic_year', $alumniProject->projectGroup->ac_id ?? '') == $year->id ? 'selected' : '' }}>
                                        {{ $year->year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- 2. ไฟล์โครงงาน -->
                <div class="mb-8">
                    <h3
                        class="text-sm font-bold text-orange-500 uppercase tracking-wider mb-4 border-b border-gray-100 dark:border-gray-700 pb-2 flex items-center gap-2">
                        <i class="fa-solid fa-file-arrow-up"></i> 2. อัปโหลดไฟล์ (Project Files)
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- ปกโครงงาน -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                ปกโครงงาน (รูปภาพ)
                            </label>
                            <input type="file" name="cover_file" accept="image/*"
                                class="block w-full text-sm text-gray-900 border border-gray-200 rounded-xl cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-s-xl file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 transition-all">

                            @if ($uploadFile?->cover_file)
                                <div class="mt-2 text-xs">
                                    <a href="{{ asset('storage/' . $uploadFile->cover_file) }}" target="_blank"
                                        class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:underline">
                                        <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i> ดูไฟล์ปกปัจจุบัน
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Abstract -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                ไฟล์ Abstract (PDF)
                            </label>
                            <input type="file" name="abstract_file" accept="application/pdf"
                                class="block w-full text-sm text-gray-900 border border-gray-200 rounded-xl cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-s-xl file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 transition-all">

                            @if ($uploadFile?->abstract_file)
                                <div class="mt-2 text-xs">
                                    <a href="{{ asset('storage/' . $uploadFile->abstract_file) }}" target="_blank"
                                        class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:underline">
                                        <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i> ดูไฟล์ Abstract
                                        ปัจจุบัน
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- ไฟล์โครงงาน -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                ไฟล์โครงงานฉบับเต็ม (PDF)
                            </label>
                            <input type="file" name="project_file" accept="application/pdf"
                                class="block w-full text-sm text-gray-900 border border-gray-200 rounded-xl cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-s-xl file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 transition-all">

                            @if ($uploadFile?->project_file)
                                <div class="mt-2 text-xs">
                                    <a href="{{ asset('storage/' . $uploadFile->project_file) }}" target="_blank"
                                        class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:underline">
                                        <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i>
                                        ดูไฟล์โครงงานปัจจุบัน
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 3. ผู้จัดทำและที่ปรึกษา -->
                <div class="mb-4">
                    <h3
                        class="text-sm font-bold text-orange-500 uppercase tracking-wider mb-4 border-b border-gray-100 dark:border-gray-700 pb-2 flex items-center gap-2">
                        <i class="fa-solid fa-users"></i> 3. ข้อมูลผู้จัดทำและที่ปรึกษา
                    </h3>

                    <div class="grid grid-cols-1 gap-6">
                        <!-- อาจารย์ที่ปรึกษา -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                อาจารย์ที่ปรึกษา (Advisor) <span class="text-red-500">*</span>
                            </label>
                            <select name="advisor_id" class="select2-search w-full" required>
                                @foreach ($advisors as $advisor)
                                    <option value="{{ $advisor->id }}"
                                        {{ old('advisor_id', $alumniProject->advisor_id) == $advisor->id ? 'selected' : '' }}>
                                        {{ $advisor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- นักศึกษา -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                นักศึกษาในโครงงาน (Members) <span class="text-red-500">*</span>
                            </label>
                            <select name="student_ids[]" multiple class="select2-multi w-full"
                                data-placeholder="เลือกนักศึกษา" required>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}"
                                        {{ in_array($student->id, $selectedStudents) ? 'selected' : '' }}>
                                        {{ $student->s_id }} - {{ $student->s_fname }} {{ $student->s_lname }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.alumni.project.index') }}"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-200">
                    ยกเลิก
                </a>
                <button type="submit"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-orange-500 rounded-xl hover:bg-orange-600 shadow-sm flex items-center gap-2 transition-colors duration-200">
                    <i class="fa-solid fa-save"></i> บันทึกการแก้ไข
                </button>
            </div>

        </form>
    </div>
</x-app-layout>
