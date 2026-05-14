<x-guest-layout :types="$types" :type-id="$typeId">
    <div x-data="{ showPdf: false, pdfUrl: '', pdfTitle: '' }" class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-5 gap-8 py-8">

        {{-- LEFT: Cover --}}
        <div class="md:col-span-2">
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-colors duration-200">
                <div class="aspect-[3/4] bg-gray-50 dark:bg-gray-900 relative group">
                    <img src="{{ $coverUrl ?: 'https://picsum.photos/seed/placeholder/600/800' }}" alt="ปกโครงงาน"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">

                    {{-- Badge เล็กๆ มุมขวาบนของรูป --}}
                    <div
                        class="absolute top-4 right-4 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-medium text-orange-600 dark:text-orange-400 shadow-sm border border-orange-100 dark:border-orange-500/20">
                        {{ $source === 'upload' ? 'Propose' : 'Project' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT: Content --}}
        <div class="md:col-span-3">
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 md:p-8 transition-colors duration-200 h-full">

                {{-- Title --}}
                <h1 class="text-2xl md:text-3xl font-bold leading-snug text-gray-900 dark:text-white">
                    {{ $source === 'upload' ? $propose->title ?? 'ไม่พบชื่อโครงงาน' : $project->title ?? 'ไม่พบชื่อโครงงาน' }}
                </h1>

                {{-- Meta Info --}}
                <div class="mt-4 flex flex-col gap-2 text-sm text-gray-600 dark:text-gray-300">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-folder-open w-5 text-center text-gray-400 dark:text-gray-500"></i>
                        <span>หมวดหมู่:</span>
                        <span
                            class="font-medium text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-md">
                            {{ $source === 'upload' ? $propose->project_type?->name ?? '-' : $project->projectType?->name ?? '-' }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-regular fa-calendar w-5 text-center text-gray-400 dark:text-gray-500"></i>
                        <span>วันที่อัปโหลด:</span>
                        <span class="font-medium text-gray-800 dark:text-gray-200">
                            {{ optional($project->updated_at)->format('d M Y') ?? '-' }}
                        </span>
                    </div>
                </div>

                {{-- Action buttons --}}
                <div class="mt-8 flex items-center gap-3">
                    @if ($abstractUrl)
                        <button
                            @click="pdfUrl='{{ route('project.preview', [
                                'source' => $source,
                                'id' => $project->id,
                                'type' => 'abstract',
                            ]) }}' + '?v=' + Date.now();
                            pdfTitle='บทคัดย่อ'; showPdf=true"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-orange-500 text-white font-medium shadow-sm hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500/50 transform hover:scale-105 transition-all duration-200">
                            <i class="fa-solid fa-file-pdf"></i>
                            ดูรายละเอียด
                        </button>
                    @else
                        <button disabled
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 font-medium cursor-not-allowed transition-colors duration-200 border border-gray-200 dark:border-gray-600">
                            <i class="fa-solid fa-file-pdf"></i>
                            ไม่มีไฟล์บทคัดย่อ
                        </button>
                    @endif
                </div>

                <div class="h-px w-full bg-gray-100 dark:bg-gray-700 my-8"></div>

                {{-- Meta list (Keywords & Files) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div
                        class="flex items-start gap-3 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-100 dark:border-gray-600/50 transition-colors duration-200">
                        <div
                            class="w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-500/20 text-orange-500 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-tags"></i>
                        </div>
                        <div>
                            <div
                                class="text-gray-500 dark:text-gray-400 mb-1 text-xs uppercase tracking-wider font-semibold">
                                คำสำคัญ</div>
                            <div class="font-medium text-gray-800 dark:text-gray-200 leading-relaxed">
                                {{ $project->keyword ?? '-' }}</div>
                        </div>
                    </div>

                    <div
                        class="flex items-start gap-3 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-100 dark:border-gray-600/50 transition-colors duration-200">
                        <div
                            class="w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-500/20 text-orange-500 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-paperclip"></i>
                        </div>
                        <div>
                            <div
                                class="text-gray-500 dark:text-gray-400 mb-1 text-xs uppercase tracking-wider font-semibold">
                                ไฟล์ที่มี</div>
                            <div class="font-medium text-gray-800 dark:text-gray-200">
                                @if ($abstractUrl)
                                    <span
                                        class="inline-flex items-center gap-1.5 bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400 px-2.5 py-1 rounded-md text-xs">
                                        <i class="fa-solid fa-check"></i> Abstract
                                    </span>
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Members & Advisor --}}
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

                    {{-- ผู้จัดทำ --}}
                    <div
                        class="p-5 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-600 transition-colors duration-200">
                        <div class="text-orange-500 font-semibold mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-users"></i> ผู้จัดทำ
                        </div>

                        @if ($source === 'upload')
                            <ul class="space-y-2">
                                @forelse($propose->project_group?->group_members ?? [] as $m)
                                    @if ($m->student)
                                        <li
                                            class="flex items-center gap-2 text-gray-800 dark:text-gray-200 font-medium">
                                            <div class="w-1.5 h-1.5 rounded-full bg-gray-300 dark:bg-gray-500"></div>
                                            {{ $m->student->s_fname }} {{ $m->student->s_lname }}
                                        </li>
                                    @endif
                                @empty
                                    <li class="text-gray-500 dark:text-gray-400">-</li>
                                @endforelse
                            </ul>
                        @else
                            <ul class="space-y-2">
                                @forelse($project->projectGroup?->group_members ?? [] as $m)
                                    @if ($m->student)
                                        <li
                                            class="flex items-center gap-2 text-gray-800 dark:text-gray-200 font-medium">
                                            <div class="w-1.5 h-1.5 rounded-full bg-gray-300 dark:bg-gray-500"></div>
                                            {{ $m->student->s_fname }} {{ $m->student->s_lname }}
                                        </li>
                                    @endif
                                @empty
                                    <li class="text-gray-500 dark:text-gray-400">-</li>
                                @endforelse
                            </ul>
                        @endif
                    </div>

                    {{-- อาจารย์ที่ปรึกษา --}}
                    <div
                        class="p-5 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-600 transition-colors duration-200">
                        <div class="text-orange-500 font-semibold mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-user-tie"></i> อาจารย์ที่ปรึกษา
                        </div>

                        @if ($source === 'upload')
                            @php $adv = $propose->advisor ?? null; @endphp
                        @else
                            @php $adv = $project->advisor ?? null; @endphp
                        @endif

                        @if ($adv)
                            <div class="flex items-center gap-2 text-gray-800 dark:text-gray-200 font-medium">
                                <div
                                    class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 shrink-0">
                                    <i class="fa-regular fa-user"></i>
                                </div>
                                {{ $adv->a_fname }} {{ $adv->a_lname }}
                            </div>
                        @else
                            <div class="text-gray-500 dark:text-gray-400">-</div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        {{-- MODAL PDF Preview --}}
        <div x-cloak x-show="showPdf" x-transition.opacity
            class="fixed inset-0 z-50 bg-gray-900/70 backdrop-blur-sm flex items-center justify-center p-4">

            <div @click.away="showPdf=false"
                class="bg-white dark:bg-gray-800 rounded-2xl overflow-hidden w-full max-w-5xl h-[90vh] flex flex-col shadow-2xl border border-gray-200 dark:border-gray-700 transition-colors duration-200 transform scale-100">

                {{-- Modal Header --}}
                <div
                    class="flex justify-between items-center bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                    <h2 class="font-bold text-lg text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-file-pdf text-orange-500"></i>
                        <span x-text="pdfTitle"></span>
                    </h2>
                    <button @click="showPdf=false"
                        class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-200 dark:bg-gray-600 text-gray-500 dark:text-gray-400 hover:bg-rose-100 hover:text-rose-500 dark:hover:bg-rose-500/20 dark:hover:text-rose-400 transition-colors focus:outline-none"
                        aria-label="ปิด">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                {{-- Modal Body (PDF) --}}
                <div class="flex-1 w-full bg-gray-100 dark:bg-gray-900 relative">
                    {{-- Loader ตอนที่กำลังโหลด iframe --}}
                    <div
                        class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 dark:text-gray-500 pointer-events-none">
                        <i class="fa-solid fa-circle-notch fa-spin text-3xl mb-3 text-orange-500"></i>
                        <p class="text-sm">กำลังโหลดเอกสาร...</p>
                    </div>

                    <iframe :src="pdfUrl" class="absolute inset-0 w-full h-full z-10" frameborder="0"></iframe>
                </div>
            </div>
        </div>

    </div>
</x-guest-layout>
