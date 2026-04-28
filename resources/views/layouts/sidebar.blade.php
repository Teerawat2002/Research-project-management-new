<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen bg-white dark:bg-gray-900 border-r border-gray-100 dark:border-gray-800 sm:translate-x-0 transition-transform shadow-sm"
    x-bind:class="open ? 'translate-x-0' : '-translate-x-full'" aria-label="Sidebar">

    <div class="flex items-center px-6 h-16 border-b border-gray-100 dark:border-gray-800 transition-colors">
        <a href="{{ route('welcome') }}" class="flex items-center gap-3">
            <div class="bg-orange-500 text-white p-1.5 rounded-lg shadow-sm">
                <i class="fa-solid fa-graduation-cap fa-lg"></i>
            </div>
            <span class="text-xl font-bold text-gray-800 dark:text-white transition-colors">Research Exam</span>
        </a>
    </div>

    <div class="flex flex-col h-[calc(100vh-4rem)] overflow-y-auto">
        <div class="px-4 py-4 space-y-6 flex-1">

            <div>
                <p class="px-2 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">
                    Academics</p>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group 
                            {{ request()->routeIs('profile.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-800 hover:text-orange-600 dark:hover:text-orange-400' }}">
                            <i
                                class="fa-solid fa-user w-5 transition-colors {{ request()->routeIs('profile.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-orange-500 dark:group-hover:text-orange-400' }}"></i>
                            <span class="ml-2">Profile</span>
                        </a>
                    </li>

                    @if (
                        (Auth::guard('advisors')->check() && in_array(Auth::guard('advisors')->user()->a_type, ['advisor', 'teacher'])) ||
                            Auth::guard('students')->check())
                        <li>
                            <a href="{{ route('teacher.calendar.home') }}"
                                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group 
                                {{ request()->routeIs('teacher.calendar.home') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-800 hover:text-orange-600 dark:hover:text-orange-400' }}">
                                <i
                                    class="fa-solid fa-calendar-days w-5 transition-colors {{ request()->routeIs('teacher.calendar.home') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-orange-500 dark:group-hover:text-orange-400' }}"></i>
                                <span class="ml-2">ปฏิทินการดำเนินงาน</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            @if (Auth::guard('advisors')->check() && Auth::guard('advisors')->user()->a_type === 'admin')
                <div>
                    <p
                        class="px-2 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">
                        System (Admin)</p>
                    <ul class="space-y-1">
                        <li x-data="{ open: {{ request()->routeIs('admin.advisor.*', 'admin.student.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                class="flex items-center w-full px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group 
                                {{ request()->routeIs('admin.advisor.*', 'admin.student.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-800 hover:text-orange-600 dark:hover:text-orange-400' }}">
                                <i
                                    class="fa-solid fa-users w-5 transition-colors {{ request()->routeIs('admin.advisor.*', 'admin.student.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-orange-500 dark:group-hover:text-orange-400' }}"></i>
                                <span class="flex-1 ml-2 text-left">User Management</span>
                                <i class="fas fa-chevron-down text-xs transition-transform"
                                    :class="open ? 'rotate-180' : ''"></i>
                            </button>
                            <ul x-show="open" x-collapse class="py-1 mt-1 space-y-1 pl-9">
                                <li>
                                    <a href="{{ route('admin.advisor.index') }}"
                                        class="flex items-center py-2 text-sm font-medium transition-colors 
                                        {{ request()->routeIs('admin.advisor.*') ? 'text-orange-600 dark:text-orange-400' : 'text-gray-500 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400' }}">
                                        อาจารย์ (Advisors)
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.student.index') }}"
                                        class="flex items-center py-2 text-sm font-medium transition-colors 
                                        {{ request()->routeIs('admin.student.*') ? 'text-orange-600 dark:text-orange-400' : 'text-gray-500 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400' }}">
                                        นักศึกษา (Students)
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="{{ route('admin.major.index') }}"
                                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group 
                                {{ request()->routeIs('admin.major.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-800 hover:text-orange-600 dark:hover:text-orange-400' }}">
                                <i
                                    class="fa-solid fa-folder-tree w-5 transition-colors {{ request()->routeIs('admin.major.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-orange-500 dark:group-hover:text-orange-400' }}"></i>
                                <span class="ml-2">สาขาวิชา</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.course.index') }}"
                                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group 
                                {{ request()->routeIs('admin.course.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-800 hover:text-orange-600 dark:hover:text-orange-400' }}">
                                <i
                                    class="fa-solid fa-bookmark w-5 transition-colors {{ request()->routeIs('admin.course.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-orange-500 dark:group-hover:text-orange-400' }}"></i>
                                <span class="ml-2">รายวิชาโครงงาน</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.project-type.index') }}"
                                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group 
                                {{ request()->routeIs('admin.project-type.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-800 hover:text-orange-600 dark:hover:text-orange-400' }}">
                                <i
                                    class="fa-solid fa-layer-group w-5 transition-colors {{ request()->routeIs('admin.project-type.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-orange-500 dark:group-hover:text-orange-400' }}"></i>
                                <span class="ml-2">ประเภทโครงงานวิจัย</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.academic-year.index') }}"
                                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group 
                                {{ request()->routeIs('admin.academic-year.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-800 hover:text-orange-600 dark:hover:text-orange-400' }}">
                                <i
                                    class="fa-solid fa-user-graduate w-5 transition-colors {{ request()->routeIs('admin.academic-year.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-orange-500 dark:group-hover:text-orange-400' }}"></i>
                                <span class="ml-2">ปีการศึกษา</span>
                            </a>
                        </li>

                        <li x-data="{ open: {{ request()->routeIs('admin.topic.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                class="flex items-center w-full px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group 
                                {{ request()->routeIs('admin.topic.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-800 hover:text-orange-600 dark:hover:text-orange-400' }}">
                                <i
                                    class="fa-solid fa-list w-5 transition-colors {{ request()->routeIs('admin.topic.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-orange-500 dark:group-hover:text-orange-400' }}"></i>
                                <span class="flex-1 ml-2 text-left">Topic Management</span>
                                <i class="fas fa-chevron-down text-xs transition-transform"
                                    :class="open ? 'rotate-180' : ''"></i>
                            </button>
                            <ul x-show="open" x-collapse class="py-1 mt-1 space-y-1 pl-9">
                                <li>
                                    <a href="{{ route('admin.topic.maintopic.index') }}"
                                        class="flex items-center py-2 text-sm font-medium transition-colors 
                                        {{ request()->routeIs('admin.topic.maintopic.*') ? 'text-orange-600 dark:text-orange-400' : 'text-gray-500 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400' }}">
                                        หัวข้อหลัก
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.topic.subtopic.index') }}"
                                        class="flex items-center py-2 text-sm font-medium transition-colors 
                                        {{ request()->routeIs('admin.topic.subtopic.*') ? 'text-orange-600 dark:text-orange-400' : 'text-gray-500 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400' }}">
                                        หัวข้อรอง
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.topic.subsubtopic.index') }}"
                                        class="flex items-center py-2 text-sm font-medium transition-colors 
                                        {{ request()->routeIs('admin.topic.subsubtopic.*') ? 'text-orange-600 dark:text-orange-400' : 'text-gray-500 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400' }}">
                                        หัวข้อย่อย
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="{{ route('admin.form.index') }}"
                                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group 
                                {{ request()->routeIs('admin.form.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-800 hover:text-orange-600 dark:hover:text-orange-400' }}">
                                <i
                                    class="fa-solid fa-clipboard-list w-5 transition-colors {{ request()->routeIs('admin.form.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-orange-500 dark:group-hover:text-orange-400' }}"></i>
                                <span class="ml-2">แบบฟอร์มคะแนน</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.alumni.project.index') }}"
                                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group 
                                {{ request()->routeIs('admin.alumni.project.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-800 hover:text-orange-600 dark:hover:text-orange-400' }}">
                                <i
                                    class="fa-solid fa-book-bookmark w-5 transition-colors {{ request()->routeIs('admin.alumni.project.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-orange-500 dark:group-hover:text-orange-400' }}"></i>
                                <span class="ml-2">จัดการโครงงานศิษย์เก่า</span>
                            </a>
                        </li>
                    </ul>
                </div>
            @endif

            @if (Auth::guard('advisors')->check() && Auth::guard('advisors')->user()->a_type === 'teacher')
                <div>
                    <p
                        class="px-2 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">
                        Teacher Menu</p>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('teacher.calendar.index') }}"
                                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group 
                                {{ request()->routeIs('teacher.calendar.index') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-800 hover:text-orange-600 dark:hover:text-orange-400' }}">
                                <i
                                    class="fa-solid fa-calendar-days w-5 transition-colors {{ request()->routeIs('teacher.calendar.index') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-orange-500 dark:group-hover:text-orange-400' }}"></i>
                                <span class="ml-2">ปฏิทินการศึกษา</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('teacher.propose.proposeIndex') }}"
                                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group 
                                {{ request()->routeIs('teacher.propose.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-800 hover:text-orange-600 dark:hover:text-orange-400' }}">
                                <i
                                    class="fa-solid fa-book-bookmark w-5 transition-colors {{ request()->routeIs('teacher.propose.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-orange-500 dark:group-hover:text-orange-400' }}"></i>
                                <span class="ml-2">หัวข้อโครงงาน</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('teacher.invigilator.home') }}"
                                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group 
                                {{ request()->routeIs('teacher.invigilator.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-800 hover:text-orange-600 dark:hover:text-orange-400' }}">
                                <i
                                    class="fa-solid fa-users w-5 transition-colors {{ request()->routeIs('teacher.invigilator.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-orange-500 dark:group-hover:text-orange-400' }}"></i>
                                <span class="ml-2">จัดกลุ่มกรรมการ</span>
                            </a>
                        </li>
                    </ul>
                </div>
            @endif

            @if (Auth::guard('advisors')->check() && in_array(Auth::guard('advisors')->user()->a_type, ['advisor', 'teacher']))
                <div>
                    <p
                        class="px-2 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">
                        Advisor Menu</p>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('advisor.propose.index') }}"
                                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group 
                                {{ request()->routeIs('advisor.propose.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-800 hover:text-orange-600 dark:hover:text-orange-400' }}">
                                <i
                                    class="fa-solid fa-book-bookmark w-5 transition-colors {{ request()->routeIs('advisor.propose.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-orange-500 dark:group-hover:text-orange-400' }}"></i>
                                <span class="ml-2">หัวข้อโครงงาน</span>
                            </a>
                        </li>

                        <li x-data="{ open: {{ request()->routeIs('advisor.submission.*', 'advisor.revision.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                class="flex items-center w-full px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group 
                                {{ request()->routeIs('advisor.submission.*', 'advisor.revision.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-800 hover:text-orange-600 dark:hover:text-orange-400' }}">
                                <i
                                    class="fa-solid fa-table-list w-5 transition-colors {{ request()->routeIs('advisor.submission.*', 'advisor.revision.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-orange-500 dark:group-hover:text-orange-400' }}"></i>
                                <span class="flex-1 ml-2 text-left">การสอบ</span>
                                <i class="fas fa-chevron-down text-xs transition-transform"
                                    :class="open ? 'rotate-180' : ''"></i>
                            </button>
                            <ul x-show="open" x-collapse class="py-1 mt-1 space-y-1 pl-9">
                                <li>
                                    <a href="{{ route('advisor.submission.index') }}"
                                        class="flex items-center py-2 text-sm font-medium transition-colors 
                                        {{ request()->routeIs('advisor.submission.*') ? 'text-orange-600 dark:text-orange-400' : 'text-gray-500 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400' }}">
                                        การยื่นสอบ
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('advisor.revision.index') }}"
                                        class="flex items-center py-2 text-sm font-medium transition-colors 
                                        {{ request()->routeIs('advisor.revision.*') ? 'text-orange-600 dark:text-orange-400' : 'text-gray-500 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400' }}">
                                        การแก้ไข
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="{{ route('advisor.upload.index') }}"
                                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group 
                                {{ request()->routeIs('advisor.upload.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-800 hover:text-orange-600 dark:hover:text-orange-400' }}">
                                <i
                                    class="fa-solid fa-folder-open w-5 transition-colors {{ request()->routeIs('advisor.upload.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-orange-500 dark:group-hover:text-orange-400' }}"></i>
                                <span class="ml-2">อัพโหลดโครงงาน</span>
                            </a>
                        </li>
                    </ul>
                </div>
            @endif

            @if (Auth::guard('advisors')->check() && Auth::guard('advisors')->user()->a_type !== 'admin')
                <div>
                    <p
                        class="px-2 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">
                        Invigilator</p>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('invigilator.examination.index') }}"
                                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group 
                                {{ request()->routeIs('invigilator.examination.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-800 hover:text-orange-600 dark:hover:text-orange-400' }}">
                                <i
                                    class="fa-solid fa-table-columns w-5 transition-colors {{ request()->routeIs('invigilator.examination.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-orange-500 dark:group-hover:text-orange-400' }}"></i>
                                <span class="ml-2">รายการสอบ</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('invigilator.revision.index') }}"
                                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group 
                                {{ request()->routeIs('invigilator.revision.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-800 hover:text-orange-600 dark:hover:text-orange-400' }}">
                                <i
                                    class="fa-solid fa-file-pen w-5 transition-colors {{ request()->routeIs('invigilator.revision.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-orange-500 dark:group-hover:text-orange-400' }}"></i>
                                <span class="ml-2">การแก้ไข</span>
                            </a>
                        </li>
                    </ul>
                </div>
            @endif

            @if (Auth::guard('students')->check())
                <div>
                    <p
                        class="px-2 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">
                        Student Menu</p>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('student.group.index') }}"
                                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group 
                                {{ request()->routeIs('student.group.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-800 hover:text-orange-600 dark:hover:text-orange-400' }}">
                                <i
                                    class="fa-solid fa-users w-5 transition-colors {{ request()->routeIs('student.group.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-orange-500 dark:group-hover:text-orange-400' }}"></i>
                                <span class="ml-2">กลุ่มโครงงาน</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('student.propose.index') }}"
                                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group 
                                {{ request()->routeIs('student.propose.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-800 hover:text-orange-600 dark:hover:text-orange-400' }}">
                                <i
                                    class="fa-solid fa-book-bookmark w-5 transition-colors {{ request()->routeIs('student.propose.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-orange-500 dark:group-hover:text-orange-400' }}"></i>
                                <span class="ml-2">เสนอหัวข้อ</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('student.submission.index') }}"
                                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group 
                                {{ request()->routeIs('student.submission.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-800 hover:text-orange-600 dark:hover:text-orange-400' }}">
                                <i
                                    class="fa-solid fa-clipboard-check w-5 transition-colors {{ request()->routeIs('student.submission.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-orange-500 dark:group-hover:text-orange-400' }}"></i>
                                <span class="ml-2">ยื่นสอบ</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('student.revision.index') }}"
                                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group 
                                {{ request()->routeIs('student.revision.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-800 hover:text-orange-600 dark:hover:text-orange-400' }}">
                                <i
                                    class="fa-solid fa-file-pen w-5 transition-colors {{ request()->routeIs('student.revision.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-orange-500 dark:group-hover:text-orange-400' }}"></i>
                                <span class="ml-2">ยื่นแก้ไข</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('student.upload.index') }}"
                                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group 
                                {{ request()->routeIs('student.upload.*') ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-gray-800 hover:text-orange-600 dark:hover:text-orange-400' }}">
                                <i
                                    class="fa-solid fa-file-upload w-5 transition-colors {{ request()->routeIs('student.upload.*') ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-orange-500 dark:group-hover:text-orange-400' }}"></i>
                                <span class="ml-2">ยื่นอัพโหลดโครงงาน</span>
                            </a>
                        </li>
                    </ul>
                </div>
            @endif

        </div>

        <div
            class="p-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50 transition-colors">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-700 flex-shrink-0">
                    <img src="https://ui-avatars.com/api/?name=User&background=3b82f6&color=fff" alt="Avatar"
                        class="rounded-full">
                </div>
                <div class="ml-3 overflow-hidden">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                        @if (Auth::guard('students')->check())
                            {{ Auth::guard('students')->user()->name }}
                        @elseif(Auth::guard('advisors')->check())
                            {{ Auth::guard('advisors')->user()->name }}
                        @else
                            User
                        @endif
                    </p>
                    <p class="text-xs text-gray-500 truncate mt-0.5">
                        <span
                            class="inline-block px-2 py-0.5 bg-orange-500 dark:bg-orange-600 text-white rounded text-[10px] font-bold shadow-sm">
                            @if (Auth::guard('advisors')->check())
                                {{ strtoupper(Auth::guard('advisors')->user()->a_type) }}
                            @else
                                STUDENT
                            @endif
                        </span>
                    </p>
                </div>
            </div>

            <div class="mt-3">
                @if (Auth::guard('students')->check())
                    <form method="POST" action="{{ route('studentLogout.logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left text-sm text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors flex items-center px-2 py-1 rounded-md hover:bg-red-50 dark:hover:bg-red-900/20">
                            <i class="fa-solid fa-arrow-right-from-bracket w-5"></i> Logout
                        </button>
                    </form>
                @elseif (Auth::guard('advisors')->check())
                    <form method="POST" action="{{ route('advisorLogout.logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left text-sm text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors flex items-center px-2 py-1 rounded-md hover:bg-red-50 dark:hover:bg-red-900/20">
                            <i class="fa-solid fa-arrow-right-from-bracket w-5"></i> Logout
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</aside>
