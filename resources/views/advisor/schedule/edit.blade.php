@php
    // เตรียมข้อมูลตัวเลือกและคนที่ถูกเลือกไว้แล้ว
    $selected = old('invi_member_id', $submission->exam_invi_members->pluck('invi_member_id')->toArray());
    $advisorId = $advisorId; // รับจาก Controller

    $groups = collect($invigilatorGroups)
        ->map(
            fn($g) => [
                'id' => $g->id,
                'members' => $g->invi_group_members
                    ->filter(fn($m) => $m->a_id !== $advisorId) // ไม่เอาตัวเอง
                    ->map(
                        fn($m) => [
                            'a_id' => $m->id, // ใช้ invi_group_member.id
                            'name' => $m->advisor->a_fname . ' ' . $m->advisor->a_lname,
                            'checked' => in_array($m->id, $selected),
                        ],
                    )
                    ->values()
                    ->toArray(),
            ],
        )
        ->toArray();
@endphp

<x-app-layout>
    <div class="p-6 max-w-5xl mx-auto mt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div class="flex-1 min-w-0 pr-4">
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-orange-500"></i> แก้ไขตารางสอบโครงงาน
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 leading-relaxed truncate">
                    โครงงาน: <span class="font-bold text-orange-500">{{ $submission->propose->title }}</span>
                </p>
            </div>

            <a href="{{ route('advisor.submission.index') }}"
                class="shrink-0 whitespace-nowrap px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors flex items-center gap-2 text-sm font-medium">
                <i class="fa-solid fa-arrow-left"></i> ย้อนกลับ
            </a>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors duration-200 overflow-hidden">
            <form action="{{ route('advisor.schedule.update', $submission->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-6 md:p-8 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
                    <h3
                        class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-5 flex items-center gap-2">
                        <i class="fa-solid fa-map-location-dot text-orange-500"></i> 1. วัน เวลา และสถานที่สอบ
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                        <div>
                            <label for="e_date"
                                class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wider">
                                <i class="fa-regular fa-calendar mr-1"></i> วันที่สอบ
                            </label>
                            <input type="text" name="e_date" id="e_date"
                                value="{{ old('e_date', optional($submission->e_date)->format('d-m-Y')) }}"
                                placeholder="วว-ดด-ปป"
                                class="block w-full px-4 py-2.5 text-sm text-gray-900 bg-white border border-gray-200 rounded-xl focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-500 dark:text-white transition-colors cursor-pointer"
                                autocomplete="off">
                            @error('e_date')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="e_time"
                                class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wider">
                                <i class="fa-regular fa-clock mr-1"></i> เวลาสอบ
                            </label>
                            <input type="text" name="e_time" id="e_time"
                                value="{{ old('e_time', optional($submission->e_time)->format('H:i')) }}"
                                placeholder="HH:MM"
                                class="block w-full px-4 py-2.5 text-sm text-gray-900 bg-white border border-gray-200 rounded-xl focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-500 dark:text-white transition-colors cursor-pointer"
                                autocomplete="off">
                            @error('e_time')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="e_room"
                                class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wider">
                                <i class="fa-solid fa-door-open mr-1"></i> ห้องสอบ
                            </label>
                            <input type="text" name="e_room" id="e_room"
                                value="{{ old('e_room', $submission->e_room) }}" placeholder="เช่น ห้อง 101, Online"
                                class="block w-full px-4 py-2.5 text-sm text-gray-900 bg-white border border-gray-200 rounded-xl focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-500 dark:text-white transition-colors">
                            @error('e_room')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                <div class="p-6 md:p-8">
                    <h3
                        class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-5 flex items-center gap-2">
                        <i class="fa-solid fa-users-viewfinder text-blue-500"></i> 2. กำหนดคณะกรรมการคุมสอบ
                    </h3>

                    <div class="mb-6">
                        <label for="invigilator_group_id"
                            class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wider">
                            เลือกกลุ่มกรรมการ
                        </label>
                        <select id="invigilator_group_id" name="invigilator_group_id" required
                            class="block w-full md:w-1/2 px-4 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-xl focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white transition-colors cursor-pointer appearance-none">
                            <option value="">-- กรุณาเลือกกลุ่มกรรมการ --</option>
                            @foreach ($invigilatorGroups as $group)
                                <option value="{{ $group->id }}"
                                    {{ old('invigilator_group_id', $submission->e_invi_group_id) == $group->id ? 'selected' : '' }}>
                                    {{ $group->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('invigilator_group_id')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="invigilators_container"
                        class="{{ old('invi_member_id', $submission->exam_invi_members->pluck('invi_member_id')->isEmpty() ? 'hidden' : '') }} animate-fade-in-up">
                        <label
                            class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-3 uppercase tracking-wider">
                            <i class="fa-solid fa-user-check mr-1"></i> เลือกกรรมการคุมสอบที่เข้าร่วม
                        </label>
                        <div id="invigilators_list" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            {{-- JS จะเติม div + checkbox แสดงเฉพาะสมาชิกกลุ่ม และติ๊กอันที่เคยเลือกไว้ --}}
                        </div>
                        @error('invi_member_id')
                            <p class="text-rose-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div
                    class="px-6 py-4 bg-gray-50/50 dark:bg-gray-900/30 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3 shrink-0">
                    <button type="button" onclick="location.href='{{ url()->previous() }}'"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">
                        ยกเลิก
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-xl hover:bg-green-700 shadow-sm flex items-center gap-2 transition-colors">
                        <i class="fa-solid fa-save"></i> บันทึกการแก้ไข
                    </button>
                </div>

            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            const groups = @json($groups);
            const list = document.getElementById('invigilators_list');
            const container = document.getElementById('invigilators_container');
            const groupSelect = document.getElementById('invigilator_group_id');

            function renderInvigilators(cid) {
                list.innerHTML = '';
                const grp = groups.find(g => g.id === cid);

                if (grp && grp.members.length) {
                    grp.members.forEach(m => {
                        // 1. สร้าง Wrapper Div
                        const wrap = document.createElement('div');
                        // คลาสพื้นฐานของการ์ด
                        const baseClasses =
                            'flex items-center ps-4 border rounded-xl p-2 transition-colors cursor-pointer group';

                        // 2. สร้าง Checkbox
                        const chk = document.createElement('input');
                        chk.type = 'checkbox';
                        chk.id = `invi_member_${m.a_id}`;
                        chk.name = 'invi_member_id[]';
                        chk.value = m.a_id;

                        // เปลี่ยนคลาส Checkbox ให้เป็นสีส้ม (text-orange-500)
                        chk.className =
                            'w-4 h-4 text-orange-500 bg-gray-100 border-gray-300 rounded focus:ring-orange-500 dark:focus:ring-orange-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 cursor-pointer transition-colors';

                        // 3. สร้าง Label
                        const lbl = document.createElement('label');
                        lbl.htmlFor = chk.id;
                        lbl.className =
                            'w-full py-2 ms-3 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer group-hover:text-orange-700 dark:group-hover:text-orange-400 select-none transition-colors';
                        lbl.innerHTML =
                            `<i class="fa-solid fa-user text-gray-400 mr-2 group-hover:text-orange-400 transition-colors"></i> ${m.name}`;

                        wrap.append(chk, lbl);

                        // 4. ฟังก์ชันสำหรับอัปเดตสีพื้นหลังของกล่องเวลาถูกติ๊ก
                        const updateWrapStyle = () => {
                            if (chk.checked) {
                                // ถ้าติ๊กอยู่ ให้กรอบเป็นสีส้ม
                                wrap.className =
                                    `${baseClasses} border-orange-500 bg-orange-50 dark:bg-orange-500/10 dark:border-orange-500`;
                            } else {
                                // ถ้าไม่ได้ติ๊ก ให้เป็นสีเทาปกติ
                                wrap.className =
                                    `${baseClasses} border-gray-200 bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-orange-50 dark:hover:bg-gray-700/80`;
                            }
                        };

                        // ตรวจสอบค่าที่ถูกติ๊กมาแต่แรก (Pre-checked)
                        if (m.checked) {
                            chk.checked = true;
                        }
                        // เรียกใช้ฟังก์ชันปรับสีครั้งแรก
                        updateWrapStyle();

                        // 5. เมื่อคลิกที่ Checkbox โดยตรง
                        chk.addEventListener('change', updateWrapStyle);

                        // 6. เมื่อคลิกที่พื้นฐานของการ์ด (ช่วยให้กดง่ายขึ้น ไม่ต้องเล็งแค่ Checkbox)
                        wrap.addEventListener('click', (e) => {
                            if (e.target !== chk && e.target !== lbl && e.target.tagName !== 'I') {
                                chk.checked = !chk.checked;
                                updateWrapStyle();
                            }
                        });

                        list.appendChild(wrap);
                    });
                    container.classList.remove('hidden');
                } else {
                    container.classList.add('hidden');
                }
            }

            // ทำงานเมื่อมีการเปลี่ยนตัวเลือกกลุ่มกรรมการ
            groupSelect.addEventListener('change', e => {
                renderInvigilators(+e.target.value);
            });

            // prefill เมื่อโหลดหน้า (เพื่อดึงค่าเก่ามาแสดงทันที)
            document.addEventListener('DOMContentLoaded', () => {
                if (groupSelect.value) {
                    renderInvigilators(+groupSelect.value);
                }

                // เริ่มต้นระบบปฏิทินและเวลา
                flatpickr("#e_time", {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true,
                    minuteIncrement: 1,
                    disableMobile: true
                });
                flatpickr("#e_date", {
                    dateFormat: "d-m-Y",
                    minDate: "today",
                    disableMobile: true
                });

                // แจ้งเตือนข้อผิดพลาด
                @if ($errors->any())
                    Swal.fire({
                        icon: 'error',
                        title: 'ข้อมูลไม่ครบถ้วน',
                        text: 'กรุณาตรวจสอบข้อมูลและเลือกกรรมการให้ถูกต้อง',
                        confirmButtonColor: '#f97316'
                    });
                @endif
            });
        </script>
    @endpush
</x-app-layout>
