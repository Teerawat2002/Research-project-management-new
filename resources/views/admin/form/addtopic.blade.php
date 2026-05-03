<x-app-layout>
    <div class="p-6 max-w-4xl mx-auto mt-8">

        <!-- Header -->
        <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200">
                    เลือกหัวข้อเข้าแบบฟอร์ม
                </h1>
                <p class="text-sm font-medium text-orange-500 dark:text-orange-400 mt-1 transition-colors duration-200">
                    ฟอร์ม: {{ $formset->name }}
                </p>
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 p-6 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl transition-colors duration-200">
            <form action="{{ route('admin.form.storeTopic', $formset->id) }}" method="POST">
                @csrf

                <h2 class="text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-4">
                    เลือกหัวข้อหลักที่ต้องการเพิ่ม <span
                        class="text-red-500 normal-case font-normal text-xs">*สามารถเลือกได้หลายข้อ</span>
                </h2>

                <!-- รายการแบบ Checkbox (สวยและกดง่ายกว่า Table) -->
                <div class="mb-6 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden shadow-sm">
                    @if ($topics->isEmpty())
                        <div class="text-center py-10 text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/50">
                            <i class="fa-solid fa-folder-open text-4xl mb-3 text-gray-300 dark:text-gray-600"></i>
                            <p>ไม่พบหัวข้อหลักในระบบ กรุณาไปเพิ่มหัวข้อก่อน</p>
                        </div>
                    @else
                        <ul
                            class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800 max-h-96 overflow-y-auto">
                            @foreach ($topics as $topic)
                                <li>
                                    <label for="topic-{{ $topic->id }}"
                                        class="flex items-center p-4 cursor-pointer hover:bg-orange-50 dark:hover:bg-gray-700/50 transition-colors group">
                                        <div class="flex items-center h-5">
                                            <input id="topic-{{ $topic->id }}" type="checkbox"
                                                value="{{ $topic->id }}" name="main_topics[]"
                                                class="w-5 h-5 text-orange-500 bg-gray-100 border-gray-300 rounded focus:ring-orange-500 dark:focus:ring-orange-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 cursor-pointer transition-colors"
                                                @if ($topic->form_id == $formset->id) checked @endif>
                                        </div>
                                        <div class="ml-4 text-sm flex-1">
                                            <span
                                                class="font-medium text-gray-900 dark:text-white group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">
                                                {{ $topic->name }}
                                            </span>
                                        </div>
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                @error('main_topics')
                    <p class="text-red-500 dark:text-red-400 text-sm mt-1.5 mb-4">{{ $message }}</p>
                @enderror

                <hr class="border-gray-100 dark:border-gray-700 mb-5 transition-colors duration-200">

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.form.index') }}"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-200">
                        ข้าม (ไว้ทำทีหลัง)
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-orange-500 rounded-xl hover:bg-orange-600 shadow-sm flex items-center gap-2 transition-colors duration-200">
                        <i class="fa-solid fa-save"></i> บันทึกรายการ
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
