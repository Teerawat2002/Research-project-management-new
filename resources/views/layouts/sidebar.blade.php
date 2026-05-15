<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-[260px] h-screen bg-white dark:bg-gray-900 border-r border-gray-100 dark:border-gray-800 flex flex-col font-sans"
    :class="[sidebarOpen ? 'translate-x-0' : '-translate-x-full', isReady ? 'transition-transform duration-300' : '']"
    aria-label="Sidebar">

    <div class="flex items-center px-5 h-16 border-b border-gray-100 dark:border-gray-800 shrink-0">
        <a href="{{ route('welcome') }}" class="flex items-center gap-3">
            <div class="w-8 h-8 bg-orange-500 text-white rounded-[10px] flex items-center justify-center shadow-sm">
                <i class="fa-solid fa-graduation-cap text-sm"></i>
            </div>
            <span class="text-[17px] font-bold text-gray-800 dark:text-white tracking-tight">Research Exam</span>
        </a>
    </div>

    <div class="flex-1 overflow-y-auto px-3 py-4 space-y-6 custom-scrollbar">

        <div>
            <div class="px-3 text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">
                Academics
            </div>
            <ul class="space-y-0.5">
                <li>
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-colors group 
                        {{ request()->routeIs('profile.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                        <i
                            class="fa-solid fa-user w-5 text-center transition-colors text-[15px] 
                            {{ request()->routeIs('profile.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                        <span>Profile</span>
                    </a>
                </li>

                @if (
                    (Auth::guard('advisors')->check() && in_array(Auth::guard('advisors')->user()->a_type, ['advisor', 'teacher'])) ||
                        Auth::guard('students')->check())
                    <li>
                        <a href="{{ route('teacher.calendar.home') }}"
                            class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-colors group 
                            {{ request()->routeIs('teacher.calendar.home') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                            <i
                                class="fa-solid fa-calendar-days w-5 text-center transition-colors text-[15px] 
                                {{ request()->routeIs('teacher.calendar.home') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                            <span>ปฏิทินการดำเนินงาน</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>

        @if (Auth::guard('advisors')->check() && Auth::guard('advisors')->user()->a_type === 'admin')
            <div>
                <div
                    class="px-3 text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">
                    System
                </div>
                <ul class="space-y-0.5">
                    <li x-data="{ open: {{ request()->routeIs('admin.advisor.*', 'admin.student.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="flex items-center w-full gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-colors group 
                            {{ request()->routeIs('admin.advisor.*', 'admin.student.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                            <i
                                class="fa-solid fa-users w-5 text-center transition-colors text-[15px] 
                                {{ request()->routeIs('admin.advisor.*', 'admin.student.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                            <span class="flex-1 text-left">User Management</span>
                            <i class="fas fa-chevron-down text-[10px] text-gray-400 transition-transform"
                                :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <ul x-show="open" x-collapse
                            class="py-1 mt-1 space-y-0.5 relative before:absolute before:inset-y-0 before:left-5 before:w-px before:bg-gray-200 dark:before:bg-gray-700 pl-8">
                            <li>
                                <a href="{{ route('admin.advisor.index') }}"
                                    class="flex items-center py-1.5 px-3 rounded-md text-[13.5px] font-medium transition-colors 
                                    {{ request()->routeIs('admin.advisor.*') ? 'bg-orange-50/50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                                    อาจารย์ (Advisors)
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.student.index') }}"
                                    class="flex items-center py-1.5 px-3 rounded-md text-[13.5px] font-medium transition-colors 
                                    {{ request()->routeIs('admin.student.*') ? 'bg-orange-50/50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                                    นักศึกษา (Students)
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="{{ route('admin.major.index') }}"
                            class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-colors group 
                            {{ request()->routeIs('admin.major.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                            <i
                                class="fa-solid fa-folder-tree w-5 text-center transition-colors text-[15px] 
                                {{ request()->routeIs('admin.major.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                            <span>สาขาวิชา</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.course.index') }}"
                            class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-colors group 
                            {{ request()->routeIs('admin.course.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                            <i
                                class="fa-solid fa-bookmark w-5 text-center transition-colors text-[15px] 
                                {{ request()->routeIs('admin.course.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                            <span>รายวิชาโครงงาน</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.project-type.index') }}"
                            class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-colors group 
                            {{ request()->routeIs('admin.project-type.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                            <i
                                class="fa-solid fa-layer-group w-5 text-center transition-colors text-[15px] 
                                {{ request()->routeIs('admin.project-type.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                            <span>ประเภทโครงงานวิจัย</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.academic-year.index') }}"
                            class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-colors group 
                            {{ request()->routeIs('admin.academic-year.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                            <i
                                class="fa-solid fa-user-graduate w-5 text-center transition-colors text-[15px] 
                                {{ request()->routeIs('admin.academic-year.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                            <span>ปีการศึกษา</span>
                        </a>
                    </li>

                    <li x-data="{ open: {{ request()->routeIs('admin.topic.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="flex items-center w-full gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-colors group 
                            {{ request()->routeIs('admin.topic.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                            <i
                                class="fa-solid fa-list w-5 text-center transition-colors text-[15px] 
                                {{ request()->routeIs('admin.topic.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                            <span class="flex-1 text-left">Topic Management</span>
                            <i class="fas fa-chevron-down text-[10px] text-gray-400 transition-transform"
                                :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <ul x-show="open" x-collapse
                            class="py-1 mt-1 space-y-0.5 relative before:absolute before:inset-y-0 before:left-5 before:w-px before:bg-gray-200 dark:before:bg-gray-700 pl-8">
                            <li>
                                <a href="{{ route('admin.topic.maintopic.index') }}"
                                    class="flex items-center py-1.5 px-3 rounded-md text-[13.5px] font-medium transition-colors 
                                    {{ request()->routeIs('admin.topic.maintopic.*') ? 'bg-orange-50/50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                                    หัวข้อหลัก
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.topic.subtopic.index') }}"
                                    class="flex items-center py-1.5 px-3 rounded-md text-[13.5px] font-medium transition-colors 
                                    {{ request()->routeIs('admin.topic.subtopic.*') ? 'bg-orange-50/50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                                    หัวข้อรอง
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.topic.subsubtopic.index') }}"
                                    class="flex items-center py-1.5 px-3 rounded-md text-[13.5px] font-medium transition-colors 
                                    {{ request()->routeIs('admin.topic.subsubtopic.*') ? 'bg-orange-50/50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                                    หัวข้อย่อย
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="{{ route('admin.form.index') }}"
                            class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-colors group 
                            {{ request()->routeIs('admin.form.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                            <i
                                class="fa-solid fa-clipboard-list w-5 text-center transition-colors text-[15px] 
                                {{ request()->routeIs('admin.form.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                            <span>แบบฟอร์มคะแนน</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.alumni.project.index') }}"
                            class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-colors group 
                            {{ request()->routeIs('admin.alumni.project.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                            <i
                                class="fa-solid fa-book-bookmark w-5 text-center transition-colors text-[15px] 
                                {{ request()->routeIs('admin.alumni.project.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                            <span>จัดการโครงงานศิษย์เก่า</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endif

        @if (Auth::guard('advisors')->check() && Auth::guard('advisors')->user()->a_type === 'teacher')
            <div>
                <div
                    class="px-3 text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">
                    Teacher Menu
                </div>
                <ul class="space-y-0.5">
                    <li>
                        <a href="{{ route('teacher.calendar.index') }}"
                            class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-colors group 
                            {{ request()->routeIs('teacher.calendar.index') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                            <i
                                class="fa-solid fa-calendar-days w-5 text-center transition-colors text-[15px] 
                                {{ request()->routeIs('teacher.calendar.index') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                            <span>ปฏิทินการศึกษา</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('teacher.propose.index') }}"
                            class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-colors group 
                            {{ request()->routeIs('teacher.propose.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                            <i
                                class="fa-solid fa-book-bookmark w-5 text-center transition-colors text-[15px] 
                                {{ request()->routeIs('teacher.propose.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                            <span>หัวข้อโครงงาน</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('teacher.invigilator.home') }}"
                            class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-colors group 
                            {{ request()->routeIs('teacher.invigilator.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                            <i
                                class="fa-solid fa-users w-5 text-center transition-colors text-[15px] 
                                {{ request()->routeIs('teacher.invigilator.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                            <span>จัดกลุ่มกรรมการ</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endif

        @if (Auth::guard('advisors')->check() && in_array(Auth::guard('advisors')->user()->a_type, ['advisor', 'teacher']))
            <div>
                <div
                    class="px-3 text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">
                    Advisor Menu
                </div>
                <ul class="space-y-0.5">
                    <li>
                        <a href="{{ route('advisor.propose.index') }}"
                            class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-colors group 
                            {{ request()->routeIs('advisor.propose.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                            <i
                                class="fa-solid fa-book-bookmark w-5 text-center transition-colors text-[15px] 
                                {{ request()->routeIs('advisor.propose.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                            <span>หัวข้อโครงงาน</span>
                        </a>
                    </li>

                    <li x-data="{ open: {{ request()->routeIs('advisor.submission.*', 'advisor.revision.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="flex items-center w-full gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-colors group 
                            {{ request()->routeIs('advisor.submission.*', 'advisor.revision.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                            <i
                                class="fa-solid fa-table-list w-5 text-center transition-colors text-[15px] 
                                {{ request()->routeIs('advisor.submission.*', 'advisor.revision.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                            <span class="flex-1 text-left">การสอบ</span>
                            <i class="fas fa-chevron-down text-[10px] text-gray-400 transition-transform"
                                :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <ul x-show="open" x-collapse
                            class="py-1 mt-1 space-y-0.5 relative before:absolute before:inset-y-0 before:left-5 before:w-px before:bg-gray-200 dark:before:bg-gray-700 pl-8">
                            <li>
                                <a href="{{ route('advisor.submission.index') }}"
                                    class="flex items-center py-1.5 px-3 rounded-md text-[13.5px] font-medium transition-colors 
                                    {{ request()->routeIs('advisor.submission.*') ? 'bg-orange-50/50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                                    การยื่นสอบ
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('advisor.revision.index') }}"
                                    class="flex items-center py-1.5 px-3 rounded-md text-[13.5px] font-medium transition-colors 
                                    {{ request()->routeIs('advisor.revision.*') ? 'bg-orange-50/50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                                    การแก้ไข
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="{{ route('advisor.upload.index') }}"
                            class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-colors group 
                            {{ request()->routeIs('advisor.upload.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                            <i
                                class="fa-solid fa-folder-open w-5 text-center transition-colors text-[15px] 
                                {{ request()->routeIs('advisor.upload.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                            <span>อัพโหลดโครงงาน</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endif

        @if (Auth::guard('advisors')->check() && Auth::guard('advisors')->user()->a_type !== 'admin')
            <div>
                <div
                    class="px-3 text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">
                    Invigilator
                </div>
                <ul class="space-y-0.5">
                    <li>
                        <a href="{{ route('invigilator.examination.index') }}"
                            class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-colors group 
                            {{ request()->routeIs('invigilator.examination.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                            <i
                                class="fa-solid fa-table-columns w-5 text-center transition-colors text-[15px] 
                                {{ request()->routeIs('invigilator.examination.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                            <span>รายการสอบ</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('invigilator.revision.index') }}"
                            class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-colors group 
                            {{ request()->routeIs('invigilator.revision.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                            <i
                                class="fa-solid fa-file-pen w-5 text-center transition-colors text-[15px] 
                                {{ request()->routeIs('invigilator.revision.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                            <span>การแก้ไข</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endif

        @if (Auth::guard('students')->check())
            <div>
                <div
                    class="px-3 text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">
                    Student Menu
                </div>
                <ul class="space-y-0.5">
                    <li>
                        <a href="{{ route('student.group.index') }}"
                            class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-colors group 
                            {{ request()->routeIs('student.group.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                            <i
                                class="fa-solid fa-users w-5 text-center transition-colors text-[15px] 
                                {{ request()->routeIs('student.group.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                            <span>กลุ่มโครงงาน</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('student.propose.index') }}"
                            class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-colors group 
                            {{ request()->routeIs('student.propose.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                            <i
                                class="fa-solid fa-book-bookmark w-5 text-center transition-colors text-[15px] 
                                {{ request()->routeIs('student.propose.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                            <span>เสนอหัวข้อ</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('student.submission.index') }}"
                            class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-colors group 
                            {{ request()->routeIs('student.submission.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                            <i
                                class="fa-solid fa-clipboard-check w-5 text-center transition-colors text-[15px] 
                                {{ request()->routeIs('student.submission.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                            <span>ยื่นสอบ</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('student.revision.index') }}"
                            class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-colors group 
                            {{ request()->routeIs('student.revision.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                            <i
                                class="fa-solid fa-file-pen w-5 text-center transition-colors text-[15px] 
                                {{ request()->routeIs('student.revision.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                            <span>ยื่นแก้ไข</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('student.upload.index') }}"
                            class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-colors group 
                            {{ request()->routeIs('student.upload.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                            <i
                                class="fa-solid fa-file-upload w-5 text-center transition-colors text-[15px] 
                                {{ request()->routeIs('student.upload.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                            <span>ยื่นอัพโหลดโครงงาน</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endif

    </div>

    <div class="p-4 border-t border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 shrink-0">
        <div class="flex items-center gap-3 mb-3">
            <img src="https://ui-avatars.com/api/?name={{ Auth::guard('students')->check() ? urlencode(Auth::guard('students')->user()->name) : (Auth::guard('advisors')->check() ? urlencode(Auth::guard('advisors')->user()->name) : 'User') }}&background=f97316&color=fff"
                alt="Avatar" class="w-9 h-9 rounded-full object-cover border border-gray-100 dark:border-gray-700">
            <div class="flex-1 min-w-0">
                <p class="text-[13px] font-bold text-gray-900 dark:text-white truncate">
                    @if (Auth::guard('students')->check())
                        {{ Auth::guard('students')->user()->name }}
                    @elseif(Auth::guard('advisors')->check())
                        {{ Auth::guard('advisors')->user()->name }}
                    @else
                        User
                    @endif
                </p>
                <div class="mt-0.5">
                    <span
                        class="inline-flex items-center px-2 py-0.5 bg-orange-500 dark:bg-orange-600 text-white rounded text-[9px] font-bold tracking-wide shadow-sm">
                        @if (Auth::guard('advisors')->check())
                            {{ strtoupper(Auth::guard('advisors')->user()->a_type) }}
                        @else
                            STUDENT
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <div>
            @if (Auth::guard('students')->check())
                <form method="POST" action="{{ route('studentLogout.logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full text-left text-sm text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors flex items-center justify-center gap-2 px-2 py-1.5 rounded-lg border border-transparent hover:border-red-100 dark:hover:border-red-900/50 hover:bg-red-50 dark:hover:bg-red-900/20">
                        <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i> <span>Logout</span>
                    </button>
                </form>
            @elseif (Auth::guard('advisors')->check())
                <form method="POST" action="{{ route('advisorLogout.logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full text-left text-sm text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors flex items-center justify-center gap-2 px-2 py-1.5 rounded-lg border border-transparent hover:border-red-100 dark:hover:border-red-900/50 hover:bg-red-50 dark:hover:bg-red-900/20">
                        <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i> <span>Logout</span>
                    </button>
                </form>
            @endif
        </div>
    </div>
</aside>
