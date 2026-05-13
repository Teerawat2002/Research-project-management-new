<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto mt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200">
                    รายชื่อนักศึกษาทั้งหมด
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                    ข้อมูลรายชื่อนักศึกษาและจัดการกลุ่มโครงงานของคุณ.
                </p>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto mt-2 md:mt-0">
                @if ($hasGroup)
                    <span
                        class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-medium bg-gray-100 text-gray-500 border border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 cursor-not-allowed w-full sm:w-auto">
                        <i class="fa-solid fa-check-circle mr-2"></i> คุณมีกลุ่มแล้ว
                    </span>
                    <a href="{{ route('student.group.edit', ['group' => $groupId]) }}"
                        class="inline-flex items-center justify-center px-5 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium rounded-lg shadow-sm transition-colors w-full sm:w-auto">
                        <i class="fa-solid fa-pen-to-square mr-2"></i> แก้ไขกลุ่ม
                    </a>
                @else
                    <a href="{{ route('student.group.create') }}"
                        class="inline-flex items-center justify-center px-5 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium rounded-lg shadow-sm transition-colors w-full sm:w-auto">
                        <i class="fa-solid fa-plus mr-2"></i> สร้างกลุ่ม
                    </a>
                @endif
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-200 overflow-hidden">

            <div class="p-5 border-b border-gray-100 dark:border-gray-700">
                <form method="GET" action="{{ route('student.group.index') }}" class="relative w-full md:w-96">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fa-solid fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-orange-500 dark:focus:border-orange-500 transition-colors"
                        placeholder="ค้นหารหัสนักศึกษา, ชื่อ, นามสกุล...">

                    <button type="submit" class="hidden"></button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400 transition-colors duration-200">
                    <thead
                        class="text-xs text-gray-400 uppercase bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 transition-colors duration-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold w-40">รหัสนักศึกษา</th>
                            <th scope="col" class="px-6 py-4 font-semibold">ชื่อ - นามสกุล</th>
                            <th scope="col" class="px-6 py-4 font-semibold">สาขา</th>
                            <th scope="col" class="px-6 py-4 font-semibold w-32">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($Student as $user)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors duration-200">

                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    {{ $user->s_id }}
                                </td>

                                <td class="px-6 py-4 text-gray-700 dark:text-gray-200">
                                    {{ $user->s_fname }} {{ $user->s_lname }}
                                </td>

                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                        {{ $user->major->m_name ?? '-' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    @if ($user->status === 'active')
                                        <span
                                            class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-500 text-white w-20">
                                            ปกติ
                                        </span>
                                    @elseif ($user->status === 'graduated')
                                        <span
                                            class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 w-24">
                                            จบการศึกษา
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400 w-24">
                                            ไม่ทราบ
                                        </span>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <i
                                            class="fa-solid fa-folder-open text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                                        <p class="text-sm font-medium">ไม่พบข้อมูลนักศึกษา</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($Student->hasPages())
                <div
                    class="p-4 border-t border-gray-100 bg-white dark:border-gray-700 dark:bg-gray-800 transition-colors duration-200">
                    {{ $Student->links() }}
                </div>
            @endif

        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // แจ้งเตือนความสำเร็จ
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: "{{ session('success') }}",
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#f97316'
                    });
                @endif

                // แจ้งเตือนข้อผิดพลาด (Validation)
                @if ($errors->any())
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: "{{ $errors->first() }}",
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#f97316'
                    });
                @endif

                // แจ้งเตือนข้อผิดพลาด (Session Error)
                @if (session('error'))
                    Swal.fire({
                        icon: 'warning',
                        title: 'แจ้งเตือน',
                        text: "{{ session('error') }}",
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#f97316'
                    });
                @endif
            });
        </script>
    @endpush
</x-app-layout>
