<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto mt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-lightbulb text-orange-500"></i> เสนอหัวข้อโครงงาน
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-200">
                    จัดการข้อมูลการเสนอหัวข้อโครงงานวิจัยของคุณ
                </p>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto mt-2 md:mt-0">
                @if ($userGroupId === null)
                    <span
                        class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-medium bg-rose-50 text-rose-600 border border-rose-200 dark:bg-rose-500/10 dark:border-rose-500/20 dark:text-rose-400 w-full sm:w-auto shadow-sm">
                        <i class="fa-solid fa-circle-exclamation mr-2"></i> คุณยังไม่ได้สร้างกลุ่มโครงงาน
                    </span>
                @elseif (!$hasActiveProposal)
                    <a href="{{ route('student.propose.create') }}"
                        class="inline-flex items-center justify-center px-5 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium rounded-lg shadow-sm transition-colors w-full sm:w-auto">
                        <i class="fa-solid fa-plus mr-2"></i> สร้างหัวข้อ
                    </a>
                @else
                    <span
                        class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-medium bg-gray-100 text-gray-500 border border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 cursor-not-allowed w-full sm:w-auto shadow-sm">
                        <i class="fa-solid fa-check-circle mr-2"></i> คุณได้สร้างหัวข้อโครงงานแล้ว
                    </span>
                @endif
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-200 overflow-hidden">

            <div class="p-5 border-b border-gray-100 dark:border-gray-700">
                <div class="relative w-full md:w-96">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fa-solid fa-search text-gray-400"></i>
                    </div>
                    <input type="text"
                        class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-orange-500 dark:focus:border-orange-500 transition-colors"
                        placeholder="ค้นหาชื่อโครงงาน...">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400 transition-colors duration-200">
                    <thead
                        class="text-xs text-gray-400 uppercase bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 transition-colors duration-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold w-16 text-center">ลำดับ</th>
                            <th scope="col" class="px-6 py-4 font-semibold min-w-[250px]">ชื่อโครงงาน</th>
                            <th scope="col" class="px-6 py-4 font-semibold min-w-[150px]">อาจารย์ที่ปรึกษา</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center w-36">สถานะ</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center w-32">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($proposals as $proposal)
                            <tr
                                class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors duration-200 group">

                                <td class="px-6 py-4 text-center font-medium text-gray-500 dark:text-gray-400">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-6 py-4">
                                    <div
                                        class="font-medium text-gray-900 dark:text-white text-sm group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors line-clamp-2">
                                        {{ $proposal->title }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-gray-700 dark:text-gray-200">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-6 h-6 rounded-full bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400 flex items-center justify-center shrink-0">
                                            <i class="fa-solid fa-user-tie text-[10px]"></i>
                                        </div>
                                        <span class="truncate">{{ $proposal->advisor->name ?? '-' }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @switch($proposal->status)
                                        @case(0)
                                            <span
                                                class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 w-28">
                                                <i class="fa-solid fa-circle-check mr-1.5"></i> อนุมัติ
                                            </span>
                                        @break

                                        @case(1)
                                            <span
                                                class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 w-28">
                                                <i class="fa-solid fa-clock mr-1.5"></i> รอตรวจสอบ
                                            </span>
                                        @break

                                        @case(2)
                                            <span
                                                class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400 w-28">
                                                <i class="fa-solid fa-circle-xmark mr-1.5"></i> ไม่อนุมัติ
                                            </span>
                                        @break

                                        @default
                                            <span
                                                class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 w-28">
                                                <i class="fa-solid fa-circle-question mr-1.5"></i> ไม่ทราบสถานะ
                                            </span>
                                    @endswitch
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @if ($proposal->status == 2 || $proposal->status == 1)
                                            <button type="button" title="แก้ไข"
                                                onclick="window.location.href='{{ route('student.propose.edit', $proposal->id) }}'"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-orange-50 hover:text-orange-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors">
                                                <i class="fa-solid fa-pen text-xs"></i>
                                            </button>
                                        @endif

                                        <button type="button" title="ประวัติการเสนอ"
                                            onclick="window.location.href='{{ route('student.propose.history', $proposal->id) }}'"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-blue-50 hover:text-blue-600 border border-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors">
                                            <i class="fa-solid fa-clock-rotate-left text-xs"></i>
                                        </button>
                                    </div>
                                </td>

                            </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <i
                                                class="fa-solid fa-folder-open text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                                            <p class="text-sm font-medium">ยังไม่มีข้อมูลการเสนอหัวข้อ</p>
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
