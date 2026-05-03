<x-app-layout>
    <div class="p-6 max-w-4xl mx-auto mt-8">

        <!-- Header -->
        <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200">
                    รายละเอียดแบบฟอร์ม
                </h1>
                <p class="text-sm font-medium text-orange-500 dark:text-orange-400 mt-1 transition-colors duration-200">
                    {{ $formset->name }}
                </p>
            </div>
            <a href="{{ route('admin.form.index') }}"
                class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200 flex items-center gap-2 text-sm font-medium">
                <i class="fa-solid fa-arrow-left"></i> ย้อนกลับ
            </a>
        </div>

        <div
            class="bg-white dark:bg-gray-800 p-6 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl transition-colors duration-200">

            <!-- ประเภทการสอบ -->
            <div class="mb-6 pb-6 border-b border-gray-100 dark:border-gray-700">
                <h2 class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">
                    ประเภทการสอบ
                </h2>
                <div
                    class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20">
                    <i class="fa-solid fa-bookmark mr-2"></i> {{ $formset->project_type->name }}
                </div>
            </div>

            <!-- โครงสร้างหัวข้อ -->
            <div>
                <h2
                    class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3 flex items-center justify-between">
                    <span>โครงสร้างหัวข้อ (Topics)</span>
                    <a href="{{ route('admin.form.editTopic', $formset->id) }}"
                        class="text-orange-500 hover:text-orange-600 dark:hover:text-orange-400 lowercase normal-case flex items-center gap-1">
                        <i class="fa-solid fa-pen-to-square"></i> แก้ไขหัวข้อ
                    </a>
                </h2>

                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-700 p-5">
                    @if ($attachedTopics->isEmpty())
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <i class="fa-solid fa-folder-open text-4xl mb-3 text-gray-300 dark:text-gray-600"></i>
                            <p>ยังไม่มีหัวข้อโปรดเพิ่มหัวข้อ</p>
                        </div>
                    @else
                        <ul class="space-y-5">
                            @foreach ($attachedTopics as $topic)
                                <li class="text-gray-900 dark:text-gray-100 font-semibold text-base">

                                    <!-- ระดับที่ 1: หัวข้อหลัก -->
                                    <div class="flex items-start">
                                        <i class="fa-solid fa-folder text-orange-500 mt-1 mr-3 shrink-0"></i>
                                        <div>
                                            <span>{{ $topic->name }}</span>
                                            <!-- ป้ายคะแนน หัวข้อหลัก -->
                                            <span
                                                class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-orange-100 text-orange-700 dark:bg-orange-500/20 dark:text-orange-400 align-middle">
                                                {{ $topic->score }} คะแนน
                                            </span>
                                        </div>
                                    </div>

                                    @if ($topic->sub_topics->isNotEmpty())
                                        <ul
                                            class="pl-5 mt-3 border-l-2 border-gray-200 dark:border-gray-700 ml-2 space-y-3">
                                            @foreach ($topic->sub_topics as $subtopic)
                                                <li class="text-gray-700 dark:text-gray-300 text-sm font-medium">

                                                    <!-- ระดับที่ 2: หัวข้อรอง -->
                                                    <div class="flex items-start">
                                                        <i
                                                            class="fa-solid fa-tag text-gray-400 dark:text-gray-500 mt-1 mr-2 text-xs shrink-0"></i>
                                                        <div>
                                                            <span>{{ $subtopic->name }}</span>
                                                            <!-- ป้ายคะแนน หัวข้อรอง -->
                                                            <span
                                                                class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 align-middle">
                                                                {{ $subtopic->score }} คะแนน
                                                            </span>
                                                        </div>
                                                    </div>

                                                    @if ($subtopic->subsub_topics->isNotEmpty())
                                                        <ul class="pl-6 mt-2 space-y-2">
                                                            @foreach ($subtopic->subsub_topics as $subsub)
                                                                <!-- ระดับที่ 3: หัวข้อย่อย -->
                                                                <li
                                                                    class="text-gray-500 dark:text-gray-400 text-sm flex items-start">
                                                                    <span
                                                                        class="mr-2 mt-1.5 text-[8px] shrink-0 text-gray-300 dark:text-gray-600"><i
                                                                            class="fa-solid fa-circle"></i></span>
                                                                    <div>
                                                                        <span
                                                                            class="font-normal">{{ $subsub->name }}</span>
                                                                        <!-- ป้ายคะแนน หัวข้อย่อย -->
                                                                        <span
                                                                            class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-500 border border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700 align-middle">
                                                                            {{ $subsub->score }} คะแนน
                                                                        </span>
                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
