<nav class="space-y-4">
    @php
        use App\Models\ProjectType;

        // ถ้าไม่ได้ส่ง $types มาจาก include ให้ query เอง
        $types =
            isset($types) && $types instanceof \Illuminate\Support\Collection
                ? $types
                : ProjectType::orderBy('name')->get();

        // ถ้าไม่ได้ส่ง $typeId มา ให้ดึงจาก query string ?type=
        $typeId = $typeId ?? request()->integer('type');
    @endphp

    <div>
        <h3 class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3 px-1">หมวดหมู่โครงงาน
        </h3>

        <ul class="space-y-1.5 text-sm">
            {{-- ลิงก์ "ทั้งหมด" --}}
            <li>
                <a href="{{ route('welcome') }}"
                    class="flex items-center justify-between px-4 py-2.5 rounded-xl transition-all duration-200 group
                    {{ empty($typeId)
                        ? 'bg-orange-50 text-orange-600 dark:bg-orange-500/10 dark:text-orange-400 font-bold shadow-sm border border-orange-100 dark:border-orange-500/20'
                        : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white border border-transparent' }}">

                    <div class="flex items-center gap-3">
                        <i
                            class="fa-solid fa-border-all {{ empty($typeId) ? 'text-orange-500' : 'text-gray-400 group-hover:text-orange-400' }} transition-colors"></i>
                        <span>ทั้งหมด</span>
                    </div>

                    @if (empty($typeId))
                        <div class="w-1.5 h-1.5 rounded-full bg-orange-500"></div>
                    @else
                        <i
                            class="fa-solid fa-chevron-right text-[10px] text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity transform translate-x-[-5px] group-hover:translate-x-0"></i>
                    @endif
                </a>
            </li>

            {{-- ลูปหมวดหมู่จาก project_types --}}
            @foreach ($types as $t)
                <li>
                    <a href="{{ route('project.index', ['type' => $t->id]) }}"
                        class="flex items-center justify-between px-4 py-2.5 rounded-xl transition-all duration-200 group
                        {{ (string) $typeId === (string) $t->id
                            ? 'bg-orange-50 text-orange-600 dark:bg-orange-500/10 dark:text-orange-400 font-bold shadow-sm border border-orange-100 dark:border-orange-500/20'
                            : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white border border-transparent' }}">

                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-folder {{ (string) $typeId === (string) $t->id ? 'text-orange-500' : 'text-gray-400 group-hover:text-orange-400' }} transition-colors"></i>
                            <span class="truncate">{{ $t->name }}</span>
                        </div>

                        @if ((string) $typeId === (string) $t->id)
                            <div class="w-1.5 h-1.5 rounded-full bg-orange-500"></div>
                        @else
                            <i
                                class="fa-solid fa-chevron-right text-[10px] text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity transform translate-x-[-5px] group-hover:translate-x-0"></i>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</nav>
