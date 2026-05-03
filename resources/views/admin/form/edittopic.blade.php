<!-- ไฟล์: admin/form/editTopic.blade.php -->
<x-app-layout>
    <div class="p-6 max-w-4xl mx-auto">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200">
                จัดการหัวข้อในแบบฟอร์ม
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                ฟอร์ม: <span class="font-semibold text-orange-500">{{ $formset->name }}</span>
            </p>
        </div>

        <div
            class="bg-white dark:bg-gray-800 p-6 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl transition-colors duration-200">
            <form action="{{ route('admin.form.updateTopic', $formset->id) }}" method="POST">
                @csrf
                @method('PUT')

                <h2 class="text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">
                    เลือกหัวข้อหลักที่ต้องการผูกเข้ากับฟอร์ม</h2>

                <div class="mb-6 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                    @if ($topics->isEmpty())
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/50">
                            <p>ไม่มีหัวข้อหลักในระบบ</p>
                        </div>
                    @else
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            @foreach ($topics as $topic)
                                <li>
                                    <label for="topic-{{ $topic->id }}"
                                        class="flex items-center p-4 cursor-pointer hover:bg-orange-50 dark:hover:bg-gray-700/50 transition-colors group">
                                        <div class="flex items-center h-5">
                                            <input id="topic-{{ $topic->id }}" type="checkbox"
                                                value="{{ $topic->id }}" name="main_topics[]"
                                                class="w-5 h-5 text-orange-500 bg-gray-100 border-gray-300 rounded focus:ring-orange-500 dark:focus:ring-orange-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 transition-colors"
                                                @if ($topic->form_id == $formset->id) checked @endif>
                                        </div>
                                        <div class="ml-3 text-sm flex-1">
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

                <hr class="border-gray-100 dark:border-gray-700 mb-5 transition-colors duration-200">

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.form.index') }}"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-200">
                        ยกเลิก
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-orange-500 rounded-xl hover:bg-orange-600 shadow-sm flex items-center gap-2 transition-colors duration-200">
                        <i class="fa-solid fa-save"></i> บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
