<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto mt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-users text-orange-500"></i> รายชื่อนักศึกษาทั้งหมด
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                    ข้อมูลรายชื่อนักศึกษาและจัดการกลุ่มโครงงานของคุณ
                </p>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto mt-2 md:mt-0">
                @if ($hasGroup)
                    <span
                        class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-bold bg-gray-100 text-gray-500 border border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 cursor-not-allowed w-full sm:w-auto shadow-sm">
                        <i class="fa-solid fa-check-circle mr-2"></i> คุณมีกลุ่มแล้ว
                    </span>
                    <a href="{{ route('student.group.edit', ['group' => $groupId]) }}"
                        class="inline-flex items-center justify-center px-5 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold rounded-xl shadow-sm transition-colors w-full sm:w-auto">
                        <i class="fa-solid fa-pen-to-square mr-2"></i> แก้ไขกลุ่ม
                    </a>
                @else
                    <a href="{{ route('student.group.create') }}"
                        class="inline-flex items-center justify-center px-5 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold rounded-xl shadow-sm transition-colors w-full sm:w-auto">
                        <i class="fa-solid fa-user-plus mr-2"></i> สร้างกลุ่ม
                    </a>
                @endif
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors duration-200 overflow-hidden">

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400 transition-colors duration-200">
                    <thead
                        class="text-xs text-gray-500 uppercase bg-gray-50/50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 transition-colors duration-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold w-32">รหัสนักศึกษา</th>
                            <th scope="col" class="px-6 py-4 font-semibold min-w-[150px]">ชื่อ</th>
                            <th scope="col" class="px-6 py-4 font-semibold min-w-[150px]">นามสกุล</th>
                            <th scope="col" class="px-6 py-4 font-semibold">สาขา</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center w-32">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @forelse ($Student as $user)
                            <tr
                                class="hover:bg-orange-50/50 dark:hover:bg-gray-700/50 transition-colors duration-200 group">

                                <td class="px-6 py-5 font-bold text-gray-900 dark:text-white">
                                    {{ $user->s_id }}
                                </td>

                                <td class="px-6 py-5 text-gray-800 dark:text-gray-200 font-medium">
                                    {{ $user->s_fname }}
                                </td>

                                <td class="px-6 py-5 text-gray-800 dark:text-gray-200 font-medium">
                                    {{ $user->s_lname }}
                                </td>

                                <td class="px-6 py-5 text-gray-600 dark:text-gray-400">
                                    {{ $user->major->m_name ?? '-' }}
                                </td>

                                <td class="px-6 py-5 text-center">
                                    @if ($user->status === 'active')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200 dark:bg-emerald-500/10 dark:border-emerald-500/20 dark:text-emerald-400 whitespace-nowrap">
                                            <i class="fa-solid fa-user-check mr-1.5 opacity-70"></i> ปกติ
                                        </span>
                                    @elseif ($user->status === 'graduated')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 whitespace-nowrap">
                                            <i class="fa-solid fa-graduation-cap mr-1.5 opacity-70"></i> จบการศึกษา
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-rose-50 text-rose-600 border border-rose-200 dark:bg-rose-500/10 dark:border-rose-500/20 dark:text-rose-400 whitespace-nowrap">
                                            <i class="fa-solid fa-circle-question mr-1.5 opacity-70"></i> ไม่ทราบสถานะ
                                        </span>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <i
                                            class="fa-solid fa-users-slash text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                                        <p class="text-base font-medium">ไม่พบข้อมูลนักศึกษา</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($Student->hasPages())
                <div
                    class="p-4 border-t border-gray-100 bg-gray-50 dark:border-gray-700 dark:bg-gray-800 transition-colors duration-200 rounded-b-xl">
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
