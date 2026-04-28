<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen bg-white border-r border-gray-100 sm:translate-x-0 transition-transform shadow-sm"
    x-bind:class="open ? 'translate-x-0' : '-translate-x-full'" aria-label="Sidebar">

    <div class="flex items-center px-6 h-16 border-b border-gray-100">
        <a href="{{ route('welcome') }}" class="flex items-center gap-3">
            <div class="bg-orange-500 text-white p-1.5 rounded-lg">
                <i class="fa-solid fa-graduation-cap fa-lg"></i>
            </div>
            <span class="text-xl font-bold text-gray-800">Research Exam</span>
        </a>
    </div>

    <div class="flex flex-col h-[calc(100vh-4rem)] overflow-y-auto">
        <div class="px-4 py-4 space-y-6 flex-1">

            <div>
                <p class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Academics</p>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition group 
                {{ request()->routeIs('profile.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-orange-50 hover:text-orange-600' }}">
                            <i
                                class="fa-solid fa-user w-5 {{ request()->routeIs('profile.*') ? 'text-orange-500' : 'text-gray-400 group-hover:text-orange-500' }}"></i>
                            <span class="ml-2">Profile</span>
                        </a>
                    </li>

                    @if (
                        (Auth::guard('advisors')->check() && in_array(Auth::guard('advisors')->user()->a_type, ['advisor', 'teacher'])) ||
                            Auth::guard('students')->check())
                        <li>
                            <a href="{{ route('teacher.calendar.home') }}"
                                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition group 
                    {{ request()->routeIs('teacher.calendar.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-orange-50 hover:text-orange-600' }}">
                                <i
                                    class="fa-solid fa-calendar-days w-5 {{ request()->routeIs('teacher.calendar.*') ? 'text-orange-500' : 'text-gray-400 group-hover:text-orange-500' }}"></i>
                                <span class="ml-2">ปฏิทินการดำเนินงาน</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            @if (Auth::guard('advisors')->check() && Auth::guard('advisors')->user()->a_type === 'admin')
                <div class="mt-6">
                    <p class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">System (Admin)</p>
                    <ul class="space-y-1">

                        <li x-data="{ open: {{ request()->routeIs('admin.advisor.*', 'admin.student.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                class="flex items-center w-full px-3 py-2.5 text-sm font-medium rounded-lg transition group 
                    {{ request()->routeIs('admin.advisor.*', 'admin.student.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-orange-50 hover:text-orange-600' }}">
                                <i
                                    class="fa-solid fa-users w-5 {{ request()->routeIs('admin.advisor.*', 'admin.student.*') ? 'text-orange-500' : 'text-gray-400 group-hover:text-orange-500' }}"></i>
                                <span class="flex-1 ml-2 text-left">User Management</span>
                                <i class="fas fa-chevron-down text-xs transition-transform"
                                    :class="open ? 'rotate-180' : ''"></i>
                            </button>
                            <ul x-show="open" x-collapse class="py-1 mt-1 space-y-1 pl-9">
                                <li>
                                    <a href="{{ route('admin.advisor.index') }}"
                                        class="flex items-center py-2 text-sm font-medium transition 
                            {{ request()->routeIs('admin.advisor.*') ? 'text-orange-600' : 'text-gray-500 hover:text-orange-600' }}">
                                        อาจารย์ (Advisors)
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.student.index') }}"
                                        class="flex items-center py-2 text-sm font-medium transition 
                            {{ request()->routeIs('admin.student.*') ? 'text-orange-600' : 'text-gray-500 hover:text-orange-600' }}">
                                        นักศึกษา (Students)
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="{{ route('admin.major.index') }}"
                                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition group 
                    {{ request()->routeIs('admin.major.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-orange-50 hover:text-orange-600' }}">
                                <i
                                    class="fa-solid fa-folder-tree w-5 {{ request()->routeIs('admin.major.*') ? 'text-orange-500' : 'text-gray-400 group-hover:text-orange-500' }}"></i>
                                <span class="ml-2">สาขาวิชา</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.course.index') }}"
                                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition group 
                    {{ request()->routeIs('admin.course.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-orange-50 hover:text-orange-600' }}">
                                <i
                                    class="fa-solid fa-bookmark w-5 {{ request()->routeIs('admin.course.*') ? 'text-orange-500' : 'text-gray-400 group-hover:text-orange-500' }}"></i>
                                <span class="ml-2">รายวิชาโครงงาน</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.project-type.index') }}"
                                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition group 
                    {{ request()->routeIs('admin.project-type.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-orange-50 hover:text-orange-600' }}">
                                <i
                                    class="fa-solid fa-layer-group w-5 {{ request()->routeIs('admin.project-type.*') ? 'text-orange-500' : 'text-gray-400 group-hover:text-orange-500' }}"></i>
                                <span class="ml-2">ประเภทโครงงานวิจัย</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.academic-year.index') }}"
                                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition group 
                    {{ request()->routeIs('admin.academic-year.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-orange-50 hover:text-orange-600' }}">
                                <i
                                    class="fa-solid fa-user-graduate w-5 {{ request()->routeIs('admin.academic-year.*') ? 'text-orange-500' : 'text-gray-400 group-hover:text-orange-500' }}"></i>
                                <span class="ml-2">ปีการศึกษา</span>
                            </a>
                        </li>

                        <li x-data="{ open: {{ request()->routeIs('admin.topic.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                class="flex items-center w-full px-3 py-2.5 text-sm font-medium rounded-lg transition group 
                    {{ request()->routeIs('admin.topic.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-orange-50 hover:text-orange-600' }}">
                                <i
                                    class="fa-solid fa-users w-5 {{ request()->routeIs('admin.topic.*') ? 'text-orange-500' : 'text-gray-400 group-hover:text-orange-500' }}"></i>
                                <span class="flex-1 ml-2 text-left">Topic Management</span>
                                <i class="fas fa-chevron-down text-xs transition-transform"
                                    :class="open ? 'rotate-180' : ''"></i>
                            </button>
                            <ul x-show="open" x-collapse class="py-1 mt-1 space-y-1 pl-9">
                                <li>
                                    <a href="{{ route('admin.topic.maintopic.index') }}"
                                        class="flex items-center py-2 text-sm font-medium transition 
                            {{ request()->routeIs('admin.topic.maintopic.*') ? 'text-orange-600' : 'text-gray-500 hover:text-orange-600' }}">
                                        หัวข้อหลัก
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.topic.subtopic.index') }}"
                                        class="flex items-center py-2 text-sm font-medium transition 
                            {{ request()->routeIs('admin.topic.subtopic.*') ? 'text-orange-600' : 'text-gray-500 hover:text-orange-600' }}">
                                        หัวข้อรอง
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.topic.subsubtopic.index') }}"
                                        class="flex items-center py-2 text-sm font-medium transition 
                            {{ request()->routeIs('admin.topic.subsubtopic.*') ? 'text-orange-600' : 'text-gray-500 hover:text-orange-600' }}">
                                        หัวข้อย่อย
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="{{ route('admin.form.index') }}"
                                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition group 
                    {{ request()->routeIs('admin.form.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-orange-50 hover:text-orange-600' }}">
                                <i
                                    class="fa-solid fa-clipboard-list w-5 {{ request()->routeIs('admin.form.*') ? 'text-orange-500' : 'text-gray-400 group-hover:text-orange-500' }}"></i>
                                <span class="ml-2">แบบฟอร์มคะแนน</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.alumni.project.index') }}"
                                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition group 
                    {{ request()->routeIs('admin.alumni.project.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-orange-50 hover:text-orange-600' }}">
                                <i
                                    class="fa-solid fa-book-bookmark w-5 {{ request()->routeIs('admin.alumni.project.*') ? 'text-orange-500' : 'text-gray-400 group-hover:text-orange-500' }}"></i>
                                <span class="ml-2">จัดการโครงงานศิษย์เก่า</span>
                            </a>
                        </li>
                    </ul>
                </div>
            @endif

        </div>

        <!-- Teacher menu -->
        @if (Auth::guard('advisors')->check() && Auth::guard('advisors')->user()->a_type === 'teacher')
            <div class="pt-4 mt-4 space-y-2 border-t border-gray-200 dark:border-gray-700">
                <h3 class="px-2 text-lg font-semibold text-gray-900 dark:text-white">
                    เมนูอาจารย์รายวิชา
                </h3>
            </div>
            <ul class="space-y-2 font-medium">
                <!-- calender -->
                <li>
                    <a href="{{ route('teacher.calendar.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i
                            class="fas fa-solid fa-calendar-days text-gray-500 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"></i>
                        <span class="ms-3">ปฏิทินการศึกษา</span>
                    </a>
                </li>

                <!-- Users -->
                <li>
                    <a href="{{ route('teacher.propose.proposeIndex') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i
                            class="fas fa-solid fa-book-bookmark text-gray-500 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">หัวข้อโครงงาน</span>
                    </a>
                </li>

                <!-- Arcademy year -->
                <li>
                    <a href="{{ route('teacher.invigilator.home') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i
                            class="fas fa-users text-gray-500 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">จัดกลุ่มกรรมการ</span>
                    </a>
                </li>
            </ul>
            <ul class="pt-4 mt-4 space-y-2 font-medium border-t border-gray-200 dark:border-gray-700"></ul>
        @endif

        <!-- Advisor menu -->
        @if (Auth::guard('advisors')->check() && in_array(Auth::guard('advisors')->user()->a_type, ['advisor', 'teacher']))
            <div class="pt-4 mt-4 space-y-2 border-t border-gray-200 dark:border-gray-700">
                <h3 class="px-2 text-lg font-semibold text-gray-900 dark:text-white">
                    เมนูอาจารย์ที่ปรึกษา
                </h3>
            </div>
            <ul class="space-y-2 font-medium">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('advisor.propose.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i
                            class="fas fa-solid fa-book-bookmark text-gray-500 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"></i>
                        <span class="ms-3">หัวข้อโครงงาน</span>
                    </a>
                </li>

                <!-- จัดการหัวข้อ -->
                <li x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                        <i
                            class="fas fa-solid fa-table-list text-gray-500 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"></i>
                        <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">การสอบ</span>
                        <i class="fas fa-chevron-down w-3 h-3"></i>
                    </button>
                    <ul x-show="open" class="py-2 space-y-2">
                        <!-- หัวข้อหลัก -->
                        <li>
                            <a href="{{ route('advisor.submission.index') }}"
                                class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                                <i
                                    class="fas fa-solid fa-table-columns text-xs text-gray-500 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"></i>
                                <span class="ms-3">การยื่นสอบ</span>
                            </a>
                        </li>
                        <!-- หัวข้อหลัก -->
                        {{-- <li>
                            <a href="{{ route('advisor.examination.index') }}"
                                class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                                <i
                                    class="fas fa-solid fa-table-columns text-xs text-gray-500 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"></i>
                                <span class="ms-3">จัดการสอบ</span>
                            </a>
                        </li> --}}
                        <!-- หัวข้อรอง -->
                        {{-- <li>
                            <a href="{{ route('advisor.score.index') }}"
                                class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                                <i
                                    class="fas fa-solid fa-clipboard-list text-xs text-gray-500 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"></i>
                                <span class="ms-3">การให้คะแนน</span>
                            </a>
                        </li> --}}
                        <!-- หัวข้อย่อย -->
                        <li>
                            <a href="{{ route('advisor.revision.index') }}"
                                class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                                <i
                                    class="fas fa-solid fa-file-pen text-xs text-gray-500 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"></i>
                                <span class="ms-3">การแก้ไข</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Arcademy year -->
                <li>
                    <a href="{{ route('advisor.upload.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i
                            class="fas fa-solid fa-folder-open text-gray-500 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">อัพโหลดโครงงาน</span>
                    </a>
                </li>
            </ul>
            <ul class="pt-4 mt-4 space-y-2 font-medium border-t border-gray-200 dark:border-gray-700"></ul>
        @endif

        <!-- invigilator menu -->
        @if (Auth::guard('advisors')->check() && Auth::guard('advisors')->user()->a_type !== 'admin')
            <div class="pt-4 mt-4 space-y-2 border-t border-gray-200 dark:border-gray-700">
                <h3 class="px-2 text-lg font-semibold text-gray-900 dark:text-white">
                    เมนูกรรมการ
                </h3>
            </div>
            <ul class="space-y-2 font-medium">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('invigilator.examination.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i
                            class="fas fa-solid fa-table-columns text-gray-500 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"></i>
                        <span class="ms-3">รายการสอบ</span>
                    </a>
                </li>

                {{-- <li>
                    <a href="#"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i
                            class="fas fa-solid fa-clipboard-list text-gray-500 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"></i>
                        <span class="ms-3">ให้คะแนน</span>
                    </a>
                </li> --}}

                <li>
                    <a href="{{ route('invigilator.revision.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i
                            class="fas fa-solid fa-file-pen text-gray-500 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"></i>
                        <span class="ms-3">การแก้ไข</span>
                    </a>
                </li>

            </ul>
            <ul class="pt-4 mt-4 space-y-2 font-medium border-t border-gray-200 dark:border-gray-700"></ul>
        @endif

        <!-- Student menu -->
        @if (Auth::guard('students')->check())
            <div class="pt-4 mt-4 space-y-2 border-t border-gray-200 dark:border-gray-700">
                <h3 class="px-2 text-lg font-semibold text-gray-900 dark:text-white">
                    เมนูนักศึกษา
                </h3>
            </div>
            <ul class="space-y-2 font-medium">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('student.group.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i
                            class="fas fa-users text-gray-500 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"></i>
                        <span class="ms-3">กลุ่มโครงงาน</span>
                    </a>
                </li>

                <!-- Users -->
                <li>
                    <a href="{{ route('student.propose.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i
                            class="fas fa-solid fa-book-bookmark text-gray-500 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">เสนอหัวข้อ</span>
                    </a>
                </li>

                <!-- Arcademy year -->
                <li>
                    <a href="{{ route('student.submission.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i
                            class="fas fa-clipboard-check text-gray-500 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">ยื่นสอบ</span>
                    </a>
                </li>

                <!-- Arcademy year -->
                <li>
                    <a href="{{ route('student.revision.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i
                            class="fas fa-file-pen text-gray-500 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">ยื่นแก้ไข</span>
                    </a>
                </li>

                <!-- Arcademy year -->
                <li>
                    <a href="{{ route('student.upload.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i
                            class="fas fa-file-upload text-gray-500 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">ยื่นอัพโหลดโครงงาน</span>
                    </a>
                </li>
            </ul>
        @endif

        <div class="p-4 border-t border-gray-100 bg-gray-50">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-gray-300 flex-shrink-0">
                    <img src="https://ui-avatars.com/api/?name=User&background=3b82f6&color=fff" alt="Avatar"
                        class="rounded-full">
                </div>
                <div class="ml-3 overflow-hidden">
                    <p class="text-sm font-medium text-gray-900 truncate">
                        @if (Auth::guard('students')->check())
                            {{ Auth::guard('students')->user()->name }}
                        @elseif(Auth::guard('advisors')->check())
                            {{ Auth::guard('advisors')->user()->name }}
                        @else
                            User
                        @endif
                    </p>
                    <p class="text-xs text-gray-500 truncate">
                        <span
                            class="inline-block px-2 py-0.5 mt-0.5 bg-orange-500 text-white rounded text-[10px] font-bold">
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
                            class="w-full text-left text-sm text-gray-500 hover:text-red-600 flex items-center px-2 py-1">
                            <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> Logout
                        </button>
                    </form>
                @elseif (Auth::guard('advisors')->check())
                    <form method="POST" action="{{ route('advisorLogout.logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left text-sm text-gray-500 hover:text-red-600 flex items-center px-2 py-1">
                            <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> Logout
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</aside>
