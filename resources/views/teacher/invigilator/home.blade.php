<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto mt-4">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200">
                    Committee Groups
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                    รายการกลุ่มคณะกรรมการและจำนวนกลุ่มในแต่ละปีการศึกษา
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('teacher.invigilator.create') }}"
                    class="bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-xl text-sm px-4 py-2.5 flex items-center shadow-sm transition-colors duration-200">
                    <i class="fa-solid fa-plus mr-2"></i> เพิ่มกลุ่ม
                </a>
            </div>
        </div>

        <!-- Main Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors duration-200 overflow-hidden">

            <!-- Table Area -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400 transition-colors duration-200">
                    <thead
                        class="text-xs text-gray-500 uppercase bg-gray-50/50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 transition-colors duration-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold w-24 text-center">
                                ลำดับ
                            </th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center">
                                <i class="fa-solid fa-users mr-1"></i> จำนวนกลุ่มคณะกรรมการ
                            </th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center">
                                <i class="fa-solid fa-calendar-day mr-1"></i> ปีการศึกษา
                            </th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center w-32">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @forelse ($groupWithMemberCount as $groups)
                            <tr
                                class="hover:bg-orange-50/50 dark:hover:bg-gray-700/50 transition-colors duration-200 group">

                                <!-- ลำดับ -->
                                <td class="px-6 py-5 text-center text-gray-500 dark:text-gray-400">
                                    {{ $loop->iteration }}
                                </td>

                                <!-- จำนวนกลุ่ม -->
                                <td class="px-6 py-5 text-center">
                                    <span
                                        class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-600 border border-blue-200 dark:bg-blue-500/10 dark:border-blue-500/20 dark:text-blue-400">
                                        {{ $groups->group_count }} กลุ่ม
                                    </span>
                                </td>

                                <!-- ปีการศึกษา -->
                                <td class="px-6 py-5 text-center">
                                    <div
                                        class="font-bold text-gray-900 dark:text-white text-base group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">
                                        {{ $groups->academic_year->year }}
                                    </div>
                                </td>

                                <!-- Action -->
                                <td class="px-6 py-5 text-center">
                                    <a href="{{ route('teacher.invigilator.group', $groups->ac_id) }}"
                                        title="ดูรายละเอียด"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-orange-50 hover:text-orange-500 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-orange-400 transition-colors">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-16 text-center text-gray-500 dark:text-gray-400" colspan="4">
                                    <div class="flex flex-col items-center justify-center">
                                        <i
                                            class="fa-solid fa-users-slash text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                                        <p class="text-base font-medium">ไม่พบข้อมูลกลุ่มคณะกรรมการ</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    @push('scripts')
        <!-- แสดงการแจ้งเตือนด้วย SweetAlert2 -->
        @if (session('success'))
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ!',
                        text: '{{ session('success') }}',
                        showConfirmButton: true,
                        confirmButtonColor: '#f97316', // สีส้มให้ตรงธีม
                        timer: 3000
                    });
                });
            </script>
        @endif

        @if ($errors->any())
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: '{{ $errors->first() }}',
                        showConfirmButton: true,
                        confirmButtonColor: '#f97316'
                    });
                });
            </script>
        @endif
    @endpush
</x-app-layout>
