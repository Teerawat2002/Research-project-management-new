<x-app-layout>
    <div class="p-6 max-w-4xl mx-auto mt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div class="flex-1 min-w-0 pr-4">
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-orange-500 shrink-0"></i> แก้ไขข้อมูลการเสนอหัวข้อ
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 leading-relaxed truncate">
                    ปรับปรุงรายละเอียดโครงงานวิจัยของคุณตามคำแนะนำของอาจารย์
                </p>
            </div>

            <a href="{{ route('student.propose.index') }}"
                class="shrink-0 whitespace-nowrap px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors flex items-center gap-2 text-sm font-medium">
                <i class="fa-solid fa-arrow-left"></i> ย้อนกลับ
            </a>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden transition-colors duration-200">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-orange-400 to-yellow-500"></div>

            <form method="POST" action="{{ route('student.propose.update', $proposal->id) }}" id="proposeForm">
                @csrf
                @method('PUT')

                <div class="p-6 md:p-8 space-y-6">

                    @if (!empty($proposal->comments))
                        <div
                            class="p-4 bg-red-50 dark:bg-red-500/10 border border-red-100 dark:border-red-500/20 rounded-xl flex items-start gap-4 mb-2">
                            <div
                                class="w-10 h-10 rounded-full bg-white dark:bg-gray-800 text-red-500 flex items-center justify-center shrink-0 shadow-sm">
                                <i class="fa-solid fa-comment-dots text-lg"></i>
                            </div>
                            <div class="w-full">
                                <h4 class="text-sm font-bold text-red-700 dark:text-red-400 mb-1">
                                    ความเห็นของอาจารย์ที่ปรึกษา (ให้แก้ไข)</h4>
                                <textarea disabled rows="2"
                                    class="auto-expand w-full bg-transparent border-none p-0 text-sm text-red-600 dark:text-red-300 resize-none cursor-default focus:ring-0 focus:border-none p-0 m-0">{{ $proposal->comments }}</textarea>
                            </div>
                        </div>
                    @endif

                    <div>
                        <label for="title"
                            class="block text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-book text-orange-500"></i> ชื่อหัวข้อโครงงานที่นำเสนอ
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title', $proposal->title) }}"
                            class="block w-full px-4 py-3 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white transition-colors shadow-sm"
                            placeholder="ระบุชื่อโครงงานของคุณ..." required>
                        @error('title')
                            <p class="text-rose-500 text-xs mt-2 flex items-center gap-1"><i
                                    class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="type_id"
                                class="block text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-2 flex items-center gap-2">
                                <i class="fa-solid fa-layer-group text-blue-500"></i> ประเภทหัวข้อโครงงาน
                            </label>
                            <select name="type_id" id="type_id"
                                class="block w-full px-4 py-3 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white transition-colors cursor-pointer shadow-sm"
                                required>
                                <option value="" disabled hidden>-- เลือกประเภทโครงงาน --</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}"
                                        {{ old('type_id', $proposal->type_id) == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type_id')
                                <p class="text-rose-500 text-xs mt-2 flex items-center gap-1"><i
                                        class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="a_id"
                                class="block text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-2 flex items-center gap-2">
                                <i class="fa-solid fa-user-tie text-indigo-500"></i> อาจารย์ที่ปรึกษา
                            </label>
                            <select name="a_id" id="a_id"
                                class="block w-full px-4 py-3 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white transition-colors cursor-pointer shadow-sm"
                                required>
                                <option value="" disabled hidden>-- เลือกอาจารย์ที่ปรึกษา --</option>
                                @foreach ($advisors as $advisor)
                                    <option value="{{ $advisor->id }}"
                                        {{ old('a_id', $proposal->a_id) == $advisor->id ? 'selected' : '' }}>
                                        {{ $advisor->a_fname }} {{ $advisor->a_lname }}
                                    </option>
                                @endforeach
                            </select>
                            @error('a_id')
                                <p class="text-rose-500 text-xs mt-2 flex items-center gap-1"><i
                                        class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="border-t border-gray-100 dark:border-gray-700 my-4"></div>

                    <div>
                        <label for="objective"
                            class="block text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-bullseye text-emerald-500"></i> วัตถุประสงค์ของโครงงาน
                        </label>
                        <textarea name="objective" id="objective" rows="3" required
                            class="auto-expand block w-full px-4 py-3 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white transition-colors shadow-sm resize-none custom-scrollbar"
                            placeholder="อธิบายวัตถุประสงค์ที่ต้องการบรรลุ..."></textarea>
                        @error('objective')
                            <p class="text-rose-500 text-xs mt-2 flex items-center gap-1"><i
                                    class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="scope"
                            class="block text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-compress text-purple-500"></i> ขอบเขตของโครงงาน
                        </label>
                        <textarea name="scope" id="scope" rows="3" required
                            class="auto-expand block w-full px-4 py-3 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white transition-colors shadow-sm resize-none custom-scrollbar"
                            placeholder="ระบุขอบเขตการทำงานของระบบ..."></textarea>
                        @error('scope')
                            <p class="text-rose-500 text-xs mt-2 flex items-center gap-1"><i
                                    class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tools"
                            class="block text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-screwdriver-wrench text-rose-500"></i>
                            ภาษาและเครื่องมือที่ใช้ในการพัฒนา
                        </label>
                        <textarea name="tools" id="tools" rows="2"
                            class="auto-expand block w-full px-4 py-3 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white transition-colors shadow-sm resize-none custom-scrollbar"
                            placeholder="เช่น PHP, Laravel, MySQL, VS Code..."></textarea>
                        @error('tools')
                            <p class="text-rose-500 text-xs mt-2 flex items-center gap-1"><i
                                    class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div
                    class="px-6 py-4 bg-gray-50/80 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3 items-center">
                    <button type="button" onclick="window.location.href='{{ route('student.propose.index') }}'"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">
                        ยกเลิก
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-bold text-white bg-orange-500 rounded-xl hover:bg-orange-600 shadow-sm flex items-center gap-2 transition-colors">
                        <i class="fa-solid fa-save"></i> บันทึกการแก้ไข
                    </button>
                </div>

            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Auto-expand textarea (ปรับขนาดกล่องตามเนื้อหาที่พิมพ์และเนื้อหาที่ดึงมาจากฐานข้อมูล)
                document.querySelectorAll('.auto-expand').forEach(textarea => {
                    const adjust = el => {
                        el.style.height = 'auto';
                        const lineHeight = parseInt(window.getComputedStyle(el).lineHeight) || 20;
                        el.style.height = (el.scrollHeight + (lineHeight / 2)) + 'px';
                    };

                    // เซ็ตค่าจาก Database และ Old Input
                    const oldObjective = @json(old('objective', $proposal->objective));
                    if (textarea.id === 'objective' && oldObjective) textarea.value = oldObjective;

                    const oldScope = @json(old('scope', $proposal->scope));
                    if (textarea.id === 'scope' && oldScope) textarea.value = oldScope;

                    const oldTools = @json(old('tools', $proposal->tools));
                    if (textarea.id === 'tools' && oldTools) textarea.value = oldTools;

                    // ปรับขนาดครั้งแรก
                    setTimeout(() => adjust(textarea), 0);

                    // ปรับขนาดเมื่อพิมพ์หรือเปลี่ยนขนาดจอ
                    textarea.addEventListener('input', () => adjust(textarea));
                    window.addEventListener('resize', () => adjust(textarea));
                });

                // ตรวจสอบ success message
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: "{{ session('success') }}",
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#f97316'
                    });
                @endif

                // ตรวจสอบ validation errors 
                @if ($errors->any())
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: "กรุณาตรวจสอบข้อมูลให้ครบถ้วน",
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#f97316'
                    });
                @endif
            });
        </script>
    @endpush
</x-app-layout>
