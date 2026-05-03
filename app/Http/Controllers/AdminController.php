<?php

namespace App\Http\Controllers;

use App\Models\Advisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Major;
use App\Models\AcademicYear;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Formset;
use App\Models\GroupMember;
use App\Models\MainTopic;
use App\Models\SubTopic;
use App\Models\SubsubTopic;
use App\Models\ExamType;
use App\Models\ProjectType;
use App\Models\AlumniProject;
use App\Models\ProjectGroup;
use Illuminate\Support\Facades\File;
use App\Models\UploadFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $advisor = Auth::guard('advisors')->user();

        return view('admin.dashboard', compact('advisor'));
    }


    // Advisor Setting
    public function advisorIndex(Request $request)
    {
        $advisor = Auth::guard('advisors')->user();
        $search = $request->input('search');
        $majorFilter = $request->input('m_id');
        $typeFilter = $request->input('a_type');

        // Get the list of majors for the dropdown
        $majors = Major::all();

        // Define advisor types for filtering
        $advisorTypes = [
            'advisor' => 'Advisor',
            'teacher' => 'Teacher',
            'admin'   => 'Admin',
        ];

        $advisorUser = Advisor::with('major')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('a_fname', 'like', '%' . $search . '%')
                        ->orWhere('a_lname', 'like', '%' . $search . '%')
                        ->orWhere('a_id', 'like', '%' . $search . '%');
                });
            })
            ->when($majorFilter, function ($query, $majorFilter) {
                return $query->where('m_id', $majorFilter);
            })
            ->when($typeFilter, function ($query, $typeFilter) {
                return $query->where('a_type', $typeFilter);
            })
            ->orderBy('a_id', 'asc')
            ->paginate(10)
            ->appends([
                'search' => $search,
                'm_id'   => $majorFilter,
                'a_type' => $typeFilter,
            ]);

        return view('admin.advisor.index', compact('advisor', 'advisorUser', 'search', 'majors', 'majorFilter', 'advisorTypes', 'typeFilter'));
    }


    public function advisorCreate()
    {
        $majors = Major::all();
        return view('admin.advisor.create', compact('majors'));
    }

    public function advisorStore(Request $request)
    {
        $request->validate([
            'a_id' => ['required', 'string', 'max:255', 'unique:advisors,a_id'],
            'a_fname' => ['required', 'string', 'max:255'],
            'a_lname' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed'], // Ensure password confirmation
            'a_type' => ['required', 'in:advisor,teacher,admin'], // Validate type
            'm_id' => ['required', 'exists:majors,id'], // Validate major ID
        ], [
            'a_id.unique' => 'Advisor ID นี้มีอยู่แล้ว กรุณาใช้ Advisor ID อื่น'
        ]);

        Advisor::create([
            'a_id' => $request->a_id,
            'a_fname' => $request->a_fname,
            'a_lname' => $request->a_lname,
            'a_password' => Hash::make($request->password), // Hash the password
            'a_type' => $request->a_type,
            'm_id' => $request->m_id,
            'status' => 'active',
        ]);

        return redirect()->route('admin.advisor.index')->with('success', 'Advisor created successfully!');
    }

    // นำเข้าข้อมูลอาจารย์ excel (JSON จาก SheetJS)
    // public function importAdvisor(Request $request)
    // {
    //     try {
    //         $rows = $request->input('advisors');
    //         if (!$rows || !is_array($rows) || count($rows) === 0) {
    //             return response()->json(['message' => 'ไม่มีข้อมูลนำเข้า'], 400);
    //         }

    //         // ถ้าไฟล์ของคุณหัวตารางอยู่ "แถว 2" ให้ส่ง dataStartRow=3 มาด้วยจากฝั่ง JS
    //         // ถ้าไม่ส่งมา จะถือว่าข้อมูลเริ่มที่แถว 2 (หัวตารางแถว 1) เป็นค่า default
    //         $dataStartRow = (int) ($request->input('dataStartRow') ?? 2);

    //         // normalize helper (กัน space แปลก / zero-width / เคสภาษาไทย)
    //         $normalize = function ($s) {
    //             $s = (string)$s;
    //             $s = str_replace("\xC2\xA0", ' ', $s); // NBSP -> space
    //             $s = preg_replace('/[\x{200B}\x{200C}\x{200D}\x{FEFF}]/u', '', $s); // zero-width
    //             $s = trim($s);
    //             $s = preg_replace('/\s+/u', '', $s); // ลบช่องว่างทั้งหมด
    //             return mb_strtolower($s, 'UTF-8');
    //         };

    //         // map หัวตารางที่คาดว่าจะเจอ -> คีย์มาตรฐาน
    //         $headerSynonyms = [
    //             'a_id'     => ['รหัสอาจารย์', 'a_id', 'รหัส', 'id'],
    //             'a_fname'  => ['ชื่อ', 'fname', 'first_name', 'firstname'],
    //             'a_lname'  => ['นามสกุล', 'lname', 'last_name', 'lastname'],
    //             'major'    => ['สาขาวิชา', 'สาขา', 'major', 'ภาควิชา', 'หลักสูตร'],
    //             'password' => ['รหัสผ่าน', 'password', 'pass'],
    //             'a_type'   => ['ประเภท', 'type', 'บทบาท', 'role'],
    //             'status'   => ['สถานะ', 'status', 'state'],
    //         ];
    //         $keyMap = [];
    //         foreach ($headerSynonyms as $canon => $syns) {
    //             foreach ($syns as $syn) $keyMap[$normalize($syn)] = $canon;
    //         }

    //         // map ชื่อสาขา -> id (normalize)
    //         $majorMap = Major::pluck('id', 'm_name')
    //             ->mapWithKeys(fn($id, $name) => [$normalize($name) => $id])
    //             ->toArray();

    //         // ค่าที่อนุญาต
    //         $allowedTypes  = ['advisor', 'coadvisor', 'head', 'committee'];
    //         $statusAliases = [
    //             'active'   => ['active', 'ใช้งาน', 'เปิด', 'พร้อม', 'available'],
    //             'inactive' => ['inactive', 'ไม่ใช้งาน', 'ปิด', 'พัก', 'unavailable'],
    //         ];
    //         $statusReverse = [];
    //         foreach ($statusAliases as $std => $alts) {
    //             foreach ($alts as $a) $statusReverse[$normalize($a)] = $std;
    //         }

    //         // เตรียมตรวจซ้ำใน DB ล่วงหน้า
    //         $candidateIds = [];
    //         foreach ($rows as $r) {
    //             $nk = array_change_key_case(array_combine(
    //                 array_map($normalize, array_keys($r)),
    //                 array_values($r)
    //             ));
    //             $aIdGuess = $nk[$normalize('รหัสอาจารย์')] ?? $nk[$normalize('a_id')] ?? null;
    //             if (!empty($aIdGuess)) $candidateIds[] = (string)$aIdGuess;
    //         }
    //         $existingSet = array_flip(
    //             Advisor::whereIn('a_id', $candidateIds)->pluck('a_id')->toArray()
    //         );

    //         $inserted = 0;
    //         $skippedDuplicates = 0;
    //         $skippedInvalid = 0;
    //         $warnings = [];
    //         $excelAIds = []; // กันซ้ำในไฟล์เอง

    //         DB::beginTransaction();

    //         foreach ($rows as $index => $row) {
    //             // เลขแถวจริงใน Excel (ให้ตรงกับที่ผู้ใช้เห็น)
    //             $rowNumber = $index + $dataStartRow;

    //             // สร้างแถว canonical keys
    //             $normRow = [];
    //             foreach ($row as $k => $v) {
    //                 $nk = $normalize($k);
    //                 if (isset($keyMap[$nk])) $normRow[$keyMap[$nk]] = is_string($v) ? trim($v) : $v;
    //             }

    //             $aId   = $normRow['a_id']    ?? null;
    //             $fname = $normRow['a_fname'] ?? null;
    //             $lname = $normRow['a_lname'] ?? null;
    //             $major = $normRow['major']   ?? '';
    //             $pass  = $normRow['password'] ?? null;
    //             $atype = $normRow['a_type']  ?? 'advisor';
    //             $statusRaw = $normRow['status'] ?? 'active';

    //             // map major -> m_id
    //             $mId = null;
    //             if (!empty($major)) {
    //                 $mId = $majorMap[$normalize($major)] ?? null;
    //                 if (!$mId) $warnings[] = "แถว $rowNumber: สาขาวิชา \"{$major}\" ไม่พบในระบบ";
    //             }

    //             // ตรวจความครบ
    //             if (empty($aId) || empty($fname) || empty($lname) || !$mId) {
    //                 $warnings[] = "แถว $rowNumber: ข้อมูลไม่ครบหรือไม่ถูกต้อง (ต้องมี รหัสอาจารย์/ชื่อ/นามสกุล/สาขาวิชา)";
    //                 $skippedInvalid++;
    //                 continue;
    //             }

    //             // กันซ้ำในไฟล์
    //             if (isset($excelAIds[$aId])) {
    //                 $warnings[] = "แถว $rowNumber: รหัสอาจารย์ '$aId' ซ้ำกับข้อมูลในไฟล์ (แถว {$excelAIds[$aId]})";
    //                 $skippedInvalid++;
    //                 continue;
    //             }
    //             $excelAIds[$aId] = $rowNumber;

    //             // กันซ้ำใน DB
    //             if (isset($existingSet[$aId])) {
    //                 $warnings[] = "แถว $rowNumber: รหัสอาจารย์ '$aId' มีอยู่แล้วในระบบ";
    //                 $skippedDuplicates++;
    //                 continue;
    //             }

    //             // sanitize a_type / status
    //             $atypeNorm  = in_array($normalize($atype), $allowedTypes, true) ? $normalize($atype) : 'advisor';
    //             $statusNorm = $statusReverse[$normalize($statusRaw)] ?? 'active';

    //             // สร้างรหัสผ่านชั่วคราวถ้าไม่ส่งมา
    //             if (empty($pass)) {
    //                 $pass = Str::random(8);
    //                 $warnings[] = "แถว $rowNumber: ไม่มีรหัสผ่าน → สร้างรหัสชั่วคราวให้แล้ว";
    //             }

    //             Advisor::create([
    //                 'a_id'       => (string)$aId,
    //                 'a_fname'    => (string)$fname,
    //                 'a_lname'    => (string)$lname,
    //                 'a_password' => Hash::make((string)$pass),
    //                 'a_type'     => $atypeNorm,
    //                 'status'     => $statusNorm,
    //                 'm_id'       => $mId,
    //             ]);

    //             $existingSet[$aId] = true;
    //             $inserted++;
    //         }

    //         DB::commit();

    //         return response()->json([
    //             'message'  => "นำเข้าสำเร็จ: $inserted รายการ (ข้ามซ้ำ: $skippedDuplicates, ไม่ผ่านตรวจสอบ: $skippedInvalid)",
    //             'warnings' => $warnings,
    //         ]);
    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }
    public function importAdvisor(Request $request)
    {
        try {
            $rows = $request->input('advisors');
            if (!$rows || !is_array($rows) || count($rows) === 0) {
                return response()->json(['message' => 'ไม่มีข้อมูลนำเข้า'], 400);
            }

            $dataStartRow = (int) ($request->input('dataStartRow') ?? 2);

            $normalize = function ($s) {
                $s = (string)$s;
                $s = str_replace("\xC2\xA0", ' ', $s);
                $s = preg_replace('/[\x{200B}\x{200C}\x{200D}\x{FEFF}]/u', '', $s);
                $s = trim($s);
                $s = preg_replace('/\s+/u', '', $s);
                return mb_strtolower($s, 'UTF-8');
            };

            $headerSynonyms = [
                'a_id'     => ['รหัสอาจารย์', 'a_id', 'รหัส', 'id'],
                'a_fname'  => ['ชื่อ', 'fname', 'first_name', 'firstname'],
                'a_lname'  => ['นามสกุล', 'lname', 'last_name', 'lastname'],
                'major'    => ['สาขาวิชา', 'สาขา', 'major', 'ภาควิชา', 'หลักสูตร'],
                'password' => ['รหัสผ่าน', 'password', 'pass'],
                'a_type'   => ['ประเภท', 'type', 'บทบาท', 'role'],
                'status'   => ['สถานะ', 'status', 'state'],
            ];
            $keyMap = [];
            foreach ($headerSynonyms as $canon => $syns) {
                foreach ($syns as $syn) $keyMap[$normalize($syn)] = $canon;
            }

            $majorMap = Major::pluck('id', 'm_name')
                ->mapWithKeys(fn($id, $name) => [$normalize($name) => $id])
                ->toArray();

            $allowedTypes  = ['advisor', 'admin', 'teacher'];
            $statusAliases = [
                'active'   => ['active', 'ใช้งาน', 'เปิด', 'พร้อม', 'available'],
                'inactive' => ['inactive', 'ไม่ใช้งาน', 'ปิด', 'พัก', 'unavailable'],
            ];
            $statusReverse = [];
            foreach ($statusAliases as $std => $alts) {
                foreach ($alts as $a) $statusReverse[$normalize($a)] = $std;
            }

            $candidateIds = [];
            foreach ($rows as $r) {
                // ปรับให้สั้นลง ไม่ต้องใช้ array_change_key_case เพราะ normalize ทำ strtolower แล้ว
                $nk = array_combine(
                    array_map($normalize, array_keys($r)),
                    array_values($r)
                );
                $aIdGuess = $nk[$normalize('รหัสอาจารย์')] ?? $nk['a_id'] ?? null;
                if (!empty($aIdGuess)) $candidateIds[] = (string)$aIdGuess;
            }

            $existingSet = array_flip(
                Advisor::whereIn('a_id', $candidateIds)->pluck('a_id')->toArray()
            );

            $skippedDuplicates = 0;
            $skippedInvalid = 0;
            $warnings = [];
            $excelAIds = [];
            $insertData = []; // เตรียมไว้สำหรับ Bulk Insert
            $now = now(); // เตรียม Timestamp ให้พร้อม

            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                $rowNumber = $index + $dataStartRow;

                $normRow = [];
                foreach ($row as $k => $v) {
                    $nk = $normalize($k);
                    if (isset($keyMap[$nk])) $normRow[$keyMap[$nk]] = is_string($v) ? trim($v) : $v;
                }

                $aId   = $normRow['a_id']    ?? null;
                $fname = $normRow['a_fname'] ?? null;
                $lname = $normRow['a_lname'] ?? null;
                $major = $normRow['major']   ?? '';
                $pass  = $normRow['password'] ?? null;
                $atype = $normRow['a_type']  ?? 'advisor';
                $statusRaw = $normRow['status'] ?? 'active';

                $mId = null;
                if (!empty($major)) {
                    $mId = $majorMap[$normalize($major)] ?? null;
                    if (!$mId) $warnings[] = "แถว $rowNumber: สาขาวิชา \"{$major}\" ไม่พบในระบบ";
                }

                if (empty($aId) || empty($fname) || empty($lname) || !$mId) {
                    $warnings[] = "แถว $rowNumber: ข้อมูลไม่ครบหรือไม่ถูกต้อง (ต้องมี รหัสอาจารย์/ชื่อ/นามสกุล/สาขาวิชา)";
                    $skippedInvalid++;
                    continue;
                }

                if (isset($excelAIds[$aId])) {
                    $warnings[] = "แถว $rowNumber: รหัสอาจารย์ '$aId' ซ้ำกับข้อมูลในไฟล์ (แถว {$excelAIds[$aId]})";
                    $skippedInvalid++;
                    continue;
                }
                $excelAIds[$aId] = $rowNumber;

                if (isset($existingSet[$aId])) {
                    $warnings[] = "แถว $rowNumber: รหัสอาจารย์ '$aId' มีอยู่แล้วในระบบ";
                    $skippedDuplicates++;
                    continue;
                }

                $atypeNorm  = in_array($normalize($atype), $allowedTypes, true) ? $normalize($atype) : 'advisor';
                $statusNorm = $statusReverse[$normalize($statusRaw)] ?? 'active';

                // แจ้งรหัสผ่านใน Warning เพื่อให้แอดมินทราบ
                if (empty($pass)) {
                    $pass = \Illuminate\Support\Str::random(8);
                    $warnings[] = "แถว $rowNumber: ไม่มีรหัสผ่าน → สร้างรหัสชั่วคราว: {$pass}";
                }

                // เก็บลง Array แทนการ Create ทีละรอบ
                $insertData[] = [
                    'a_id'       => (string)$aId,
                    'a_fname'    => (string)$fname,
                    'a_lname'    => (string)$lname,
                    'a_password' => \Illuminate\Support\Facades\Hash::make((string)$pass),
                    'a_type'     => $atypeNorm,
                    'status'     => $statusNorm,
                    'm_id'       => $mId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                $existingSet[$aId] = true;
            }

            // ทำ Bulk Insert (แบ่ง Chunk เผื่อกรณีไฟล์ใหญ่มากๆ เพื่อป้องกัน Binding Limits)
            $inserted = count($insertData);
            if ($inserted > 0) {
                foreach (array_chunk($insertData, 500) as $chunk) {
                    Advisor::insert($chunk);
                }
            }

            DB::commit();

            return response()->json([
                'message'  => "นำเข้าสำเร็จ: $inserted รายการ (ข้ามซ้ำ: $skippedDuplicates, ไม่ผ่านตรวจสอบ: $skippedInvalid)",
                'warnings' => $warnings,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function advisorEdit($id)
    {
        // Get the currently authenticated advisor (if needed)
        $advisor = Auth::guard('advisors')->user();

        // Retrieve the advisor details by its ID
        $advisorDetail = Advisor::findOrFail($id);

        // Retrieve all available majors for the dropdown
        $majors = Major::all();

        // Return the edit view with advisor details and majors
        return view('admin.advisor.edit', compact('advisor', 'advisorDetail', 'majors'));
    }

    public function advisorUpdate(Request $request, $id)
    {
        // Validate the input
        $request->validate([
            'a_id' => ['required', 'string', 'max:255', Rule::unique('advisors')->ignore($id, 'id')],
            'a_fname' => ['required', 'string', 'max:255'],
            'a_lname' => ['required', 'string', 'max:255'],
            // Password is optional on update; if provided, it must be confirmed and meet minimum length
            'password' => ['nullable', 'confirmed'],
            'a_type' => ['required', 'in:advisor,teacher,admin'],
            'm_id' => ['required', 'exists:majors,id'],
        ]);

        // Retrieve the advisor to update
        $advisorDetail = Advisor::findOrFail($id);

        // Update fields
        $advisorDetail->a_id = $request->a_id;
        $advisorDetail->a_fname = $request->a_fname;
        $advisorDetail->a_lname = $request->a_lname;
        $advisorDetail->a_type = $request->a_type;
        $advisorDetail->m_id = $request->m_id;

        // Update password only if provided
        if ($request->filled('password')) {
            $advisorDetail->a_password = Hash::make($request->password);
        }

        $advisorDetail->save();

        return redirect()->route('admin.advisor.index')
            ->with('success', 'Advisor updated successfully!');
    }

    public function advisorDelete($id)
    {
        // Find the advisor record by ID or fail with a 404
        $advisorDetail = Advisor::findOrFail($id);

        // Delete the advisor record
        $advisorDetail->delete();

        // Redirect back to the advisor index with a success message
        return redirect()->route('admin.advisor.index')
            ->with('success', 'Advisor deleted successfully!');
    }


    //Academic year
    public function academicYearIndex()
    {
        $advisor = Auth::guard('advisors')->user();
        $academicYear = AcademicYear::orderBy('year', 'desc')->paginate(10);

        return view('admin.academic-year.index', compact('advisor', 'academicYear'));
    }

    public function yearcreate()
    {
        return view('admin.academic-year.create');
    }

    public function yearstore(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:2500|unique:academic_years,year',
        ]);

        AcademicYear::create($validated);

        return redirect()->route('admin.academic-year.index')->with('success', 'Academic year created successfully!');
    }

    public function academicYearDelete($id)
    {
        $year = AcademicYear::findOrFail($id);
        $year->delete();

        return redirect()->route('admin.academic-year.index')
            ->with('success', 'Academic year deleted successfully!');
    }


    // Course
    public function courseIndex()
    {
        $advisor = Auth::guard('advisors')->user();
        $courses = ExamType::orderBy('id', 'asc')->paginate(10);

        return view('admin.course.index', compact('advisor', 'courses'));
    }

    public function courseCreate()
    {
        return view('admin.course.create');
    }

    public function courseStore(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255|unique:exam_type,name',
        ]);

        ExamType::create([
            'name'   => $validated['name'],
            'status' => 1,
        ]);

        return redirect()
            ->route('admin.course.index')
            ->with('success', 'เพิ่มรายวิชาเรียบร้อยแล้ว');
    }

    public function courseEdit($id)
    {
        $course = ExamType::findOrFail($id);
        return view('admin.course.edit', compact('course'));
    }

    public function courseUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:exam_type,name,' . $id,
        ]);

        $course = ExamType::findOrFail($id);
        $course->update(['name' => $validated['name']]);

        return redirect()
            ->route('admin.course.index')
            ->with('success', 'แก้ไขรายวิชาเรียบร้อยแล้ว');
    }

    // ลบ
    public function courseDelete($id)
    {
        $course = ExamType::findOrFail($id);
        $course->delete();

        return redirect()
            ->route('admin.course.index')
            ->with('success', 'ลบรายวิชาเรียบร้อยแล้ว');
    }


    // Project Type
    public function projectTypeIndex(Request $request)
    {
        $advisor = Auth::guard('advisors')->user();
        $search = $request->input('search'); // รับค่าจากช่องค้นหา

        $projectTypes = ProjectType::query()
            ->when($search, function ($query, $search) {
                // ค้นหาจากคอลัมน์ชื่อประเภทโครงงานวิจัย
                return $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('id', 'asc')
            ->paginate(10)
            ->withQueryString(); // สำคัญมาก: ทำให้พารามิเตอร์ ?search=... ไม่หายเวลาคลิกหน้าถัดไป

        return view('admin.project-type.index', compact('advisor', 'projectTypes'));
    }

    public function projectTypeCreate()
    {
        return view('admin.project-type.create');
    }

    public function projectTypeStore(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
        ]);

        ProjectType::create([
            'name'   => $validated['name'],
        ]);

        return redirect()
            ->route('admin.project-type.index')
            ->with('success', 'เพิ่มประเภทโครงการเรียบร้อยแล้ว');
    }

    public function projectTypeEdit($id)
    {
        $projectType = ProjectType::findOrFail($id);
        return view('admin.project-type.edit', compact('projectType'));
    }

    public function projectTypeUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255' . $id,
        ]);

        $projectType = ProjectType::findOrFail($id);
        $projectType->update(['name' => $validated['name']]);

        return redirect()
            ->route('admin.project-type.index')
            ->with('success', 'แก้ไขประเภทโครงการเรียบร้อยแล้ว');
    }

    // ลบ
    public function projectTypeDelete($id)
    {
        $projectType = ProjectType::findOrFail($id);
        $projectType->delete();

        return redirect()
            ->route('admin.project-type.index')
            ->with('success', 'ลบประเภทโครงการเรียบร้อยแล้ว');
    }


    // Major setting
    public function majorIndex(Request $request)
    {
        $advisor = Auth::guard('advisors')->user();
        $search = $request->input('search');

        $Major = Major::when($search, function ($query, $search) {
            return $query->where('m_name', 'like', '%' . $search . '%');
        })
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('admin.major.index', compact('advisor', 'Major', 'search'));
    }


    public function majorCreate()
    {
        return view('admin.major.create');
    }

    public function majorStore(Request $request)
    {
        $request->validate([
            'm_name' => ['required', 'string', 'max:255'],
        ]);

        Major::create([
            'm_name' => $request->m_name,
        ]);

        return redirect()->route('admin.major.index')->with('success', 'Major created successfully!');
    }

    public function majorEdit($id)
    {
        // Retrieve the major record or fail with a 404 error if not found
        $major = Major::findOrFail($id);

        // Return the edit view with the major data
        return view('admin.major.edit', compact('major'));
    }

    public function majorUpdate(Request $request, $id)
    {
        $request->validate([
            'm_name' => ['required', 'string', 'max:255'],
        ]);

        $major = Major::findOrFail($id);
        $major->update([
            'm_name' => $request->m_name,
        ]);

        return redirect()->route('admin.major.index')->with('success', 'Major updated successfully!');
    }

    public function majorDelete($id)
    {
        // Retrieve the major record by its ID or fail
        $major = Major::findOrFail($id);

        // Delete the record
        $major->delete();

        // Redirect back to the index page with a success message
        return redirect()->route('admin.major.index')
            ->with('success', 'Major deleted successfully!');
    }


    // Student Setting
    // public function studentIndex()
    // {
    //     $advisor = Auth::guard('advisors')->user();
    //     $Student = Student::with(['academic_year', 'project_group'])->orderBy('id', 'asc')->paginate(10);

    //     return view('admin.student.index', compact('advisor', 'Student'));
    // }

    public function studentIndex(Request $request)
    {
        $advisor = Auth::guard('advisors')->user();

        $search = $request->input('search');
        $majorFilter = $request->input('m_id');

        // Fetch data for dropdowns
        $majors = Major::all();

        $Student = Student::with(['project_group'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('s_fname', 'like', "%{$search}%")
                        ->orWhere('s_lname', 'like', "%{$search}%")
                        ->orWhere('s_id', 'like', "%{$search}%");
                });
            })
            ->when($majorFilter, function ($query, $majorFilter) {
                // Filter by major (m_id)
                return $query->where('m_id', $majorFilter);
            })
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('admin.student.index', compact('advisor', 'Student', 'majors'));
    }

    public function studentCreate()
    {
        // $students = Student::with(['academic_year', 'project_group'])->orderBy('id', 'asc')->get();
        // $academicYears = AcademicYear::all(); // Fetch all academic years
        $majors = Major::all(); // Fetch all majors
        return view('admin.student.create', compact('majors'));
    }

    public function studentStore(Request $request)
    {
        // dd($request->all());

        $request->validate([
            's_id' => ['required', 'string', 'max:255', 'unique:students'],
            's_fname' => ['required', 'string', 'max:255'],
            's_lname' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', 'min:8'], // Ensure password confirmation
            'status' => ['required', 'in:active,graduated'],
            'm_id' => ['required', 'exists:majors,id'], // Validate major ID from the 'majors' table
            // 'ac_id' => ['required', 'exists:academic_years,id'], // Validate academic year from the 'academic_years' table
        ]);

        // dd('Validation passed!');

        Student::create([
            's_id' => $request->s_id,
            's_fname' => $request->s_fname,
            's_lname' => $request->s_lname,
            's_password' => Hash::make($request->password), // Hash the password
            'status' => $request->status,
            'm_id' => $request->m_id,
            // 'ac_id' => $request->ac_id,
        ]);

        return redirect()->route('admin.student.index')->with('success', 'Student created successfully!');
    }

    public function studentEdit($id)
    {
        // Retrieve the student record by its ID
        $student = Student::findOrFail($id);

        // Fetch all academic years and majors for the form dropdowns
        // $academicYears = AcademicYear::all();
        $majors = Major::all();

        // Return the edit view and pass the student, academic years, and majors data
        return view('admin.student.edit', compact('student', 'majors'));
    }

    public function studentUpdate(Request $request, $id)
    {
        $request->validate([
            's_id' => ['required', 'string', 'max:255', Rule::unique('students')->ignore($id, 'id')],
            's_fname' => ['required', 'string', 'max:255'],
            's_lname' => ['required', 'string', 'max:255'],
            // Password is optional on update; if provided, it must be confirmed and meet minimum length.
            'password' => ['nullable', 'confirmed', 'min:8'],
            'status' => ['required', 'in:active,graduated'],
            'm_id' => ['required', 'exists:majors,id'],
            // 'ac_id' => ['required', 'exists:academic_years,id'],
        ]);

        // Find the student record by its ID
        $student = Student::findOrFail($id);

        // Update student details
        $student->s_id = $request->s_id;
        $student->s_fname = $request->s_fname;
        $student->s_lname = $request->s_lname;
        $student->status = $request->status;
        $student->m_id = $request->m_id;
        // $student->ac_id = $request->ac_id;

        // Update the password only if provided
        if ($request->filled('password')) {
            $student->s_password = Hash::make($request->password);
        }

        $student->save();

        return redirect()->route('admin.student.index')
            ->with('success', 'Student updated successfully!');
    }

    public function importStudent(Request $request)
    {
        try {
            $raw = $request->input('students');
            if (!$raw || !is_array($raw)) {
                return response()->json(['message' => 'ไม่มีข้อมูลนำเข้า'], 400);
            }

            // helper
            $norm = fn($t) => $t === null ? null
                : mb_strtolower(preg_replace('/\x{00A0}|\s+/u', '', trim((string)$t)));

            $pick = function (array $row, array $keys) {
                $lower = array_change_key_case($row, CASE_LOWER);
                foreach ($keys as $k) {
                    $k = mb_strtolower($k);
                    if (isset($lower[$k]) && trim((string)$lower[$k]) !== '') {
                        return trim((string)$lower[$k]);
                    }
                }
                return '';
            };

            $toStatus = function ($v) {
                $v = mb_strtolower(trim((string)($v ?? 'active')));

                // graduated
                if (in_array($v, [
                    'graduated',
                    'จบ',
                    'จบการศึกษา',
                    'สำเร็จการศึกษา'
                ])) {
                    return 'graduated';
                }

                // default = active
                return 'active';
            };

            // ดัชนีสาขา: รองรับทั้งชื่อเต็มและรหัสย่อ (m_code) ถ้ามี
            $majors = Major::select('id', 'm_name')->get();
            $majorIdx = [];
            foreach ($majors as $m) {
                if (!empty($m->m_name)) $majorIdx[$norm($m->m_name)] = $m->id;
            }

            $pending  = [];  // กันซ้ำ s_id ในไฟล์
            $pwdMap   = [];
            $warnings = [];
            $line = 1;

            foreach ($raw as $r) {
                $line++;

                $sid   = $pick($r, ['รหัสนักศึกษา', 'student_id', 'sid', 's_id']);
                $fname = $pick($r, ['ชื่อ', 'first_name', 'fname', 's_fname']);
                $lname = $pick($r, ['นามสกุล', 'last_name', 'lname', 's_lname']);
                $pass  = $pick($r, ['รหัสผ่าน', 'password']);
                $major = $pick($r, ['สาขาวิชา', 'major', 'm_name']);
                $stat = $pick($r, ['สถานะ', 'status']);

                if ($sid === '' || $fname === '' || $lname === '' || $major === '') {
                    $warnings[] = "แถว {$line}: ข้อมูลไม่ครบ (ต้องมี รหัสนักศึกษา/ชื่อ/นามสกุล/สาขาวิชา)";
                    continue;
                }

                if (isset($pending[$sid])) {
                    $warnings[] = "แถว {$line}: รหัสนักศึกษา {$sid} ซ้ำในไฟล์ (ข้าม)";
                    continue;
                }

                $mid = $majorIdx[$norm($major)] ?? null;
                if (!$mid) {
                    $warnings[] = "แถว {$line}: ไม่พบสาขาวิชา “{$major}”";
                    continue;
                }

                $pending[$sid] = [
                    's_id'       => $sid,
                    's_fname'    => $fname,
                    's_lname'    => $lname,
                    'm_id'       => $mid,
                    'status'     => $toStatus($stat),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                if ($pass !== '') $pwdMap[$sid] = Hash::make($pass);
            }

            if (empty($pending)) {
                return response()->json(['message' => 'ไม่มีแถวที่นำเข้าได้', 'warnings' => $warnings], 422);
            }

            // ข้าม s_id ที่มีอยู่แล้ว
            $incoming = array_keys($pending);
            $exists = Student::whereIn('s_id', $incoming)->pluck('s_id')->all();
            foreach ($exists as $dup) {
                unset($pending[$dup], $pwdMap[$dup]);
                $warnings[] = "รหัสนักศึกษา {$dup} มีอยู่แล้วในระบบ (ข้าม)";
            }

            if (empty($pending)) {
                return response()->json(['message' => 'ไม่มีแถวใหม่ให้บันทึก (ซ้ำทั้งหมด)', 'warnings' => $warnings], 422);
            }

            $toInsert = array_map(function ($row) use ($pwdMap) {
                $sid = $row['s_id'];
                $row['s_password'] = $pwdMap[$sid] ?? Hash::make('12345678'); // default
                return $row;
            }, array_values($pending));

            DB::transaction(fn() => Student::insert($toInsert));

            return response()->json([
                'message'  => 'นำเข้าเรียบร้อย (เพิ่มใหม่ ' . count($toInsert) . ' รายการ)',
                'warnings' => $warnings,
            ]);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()], 500);
        }
    }

    public function studentDelete($id)
    {
        // Retrieve the student record by its ID or fail if not found
        $student = Student::findOrFail($id);

        // Delete the student record
        $student->delete();

        // Redirect back to the student index with a success message
        return redirect()->route('admin.student.index')
            ->with('success', 'Student deleted successfully!');
    }

    //maintopic
    public function mainindex(Request $request)
    {
        $advisor = Auth::guard('advisors')->user();

        // Retrieve query parameters
        $search = $request->input('search');
        $formFilter = $request->input('form_id');  // can be '', 'unattached', or a numeric ID

        // Fetch all formsets for the dropdown
        $forms = Formset::all();

        $main_topics = MainTopic::when($search, function ($query, $search) {
            // Filter by topic name
            $query->where('name', 'like', "%{$search}%");
        })
            ->when($formFilter, function ($query, $formFilter) {
                // If user wants "Unattached" topics
                if ($formFilter === 'unattached') {
                    $query->whereNull('form_id');
                }
                // If user selected a specific form ID
                elseif (is_numeric($formFilter)) {
                    $query->where('form_id', $formFilter);
                }
                // If $formFilter is empty, we don't add any additional condition
            })
            ->orderBy('created_at', 'asc')
            ->paginate(10)
            ->appends([
                'search'   => $search,
                'form_id'  => $formFilter,
            ]);

        return view('admin.topic.maintopic.index', compact(
            'advisor',
            'main_topics',
            'search',
            'formFilter',
            'forms'
        ));
    }

    public function maincreate()
    {
        $advisor = Auth::guard('advisors')->user();
        $main_topics = MainTopic::all();
        return view('admin.topic.maintopic.create', compact('advisor', 'main_topics'));
    }

    public function mainstore(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'score' => ['required', 'numeric'],
        ]);

        MainTopic::create([
            'name' => $request->name,
            'score' => $request->score,
        ]);

        return redirect()->route('admin.topic.maintopic.index')->with('success', 'Form created successfully!');
    }

    public function mainedit($id)
    {
        $advisor = Auth::guard('advisors')->user();
        $topic = MainTopic::findOrFail($id);

        return view('admin.topic.maintopic.edit', compact('advisor', 'topic'));
    }


    public function mainupdate(Request $request, $id)
    {
        // Validate the incoming data
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'score' => ['required', 'numeric'],
        ]);

        // Find the topic or throw a 404 error if not found
        $topic = MainTopic::findOrFail($id);

        // Update the topic with the new data
        $topic->update([
            'name' => $request->name,
            'score' => $request->score,
        ]);

        // Redirect to the index page with a success message
        return redirect()->route('admin.topic.maintopic.index')
            ->with('success', 'Topic updated successfully!');
    }


    public function maindelete($id)
    {
        $topic = MainTopic::findOrFail($id);
        $topic->delete();

        return redirect()->route('admin.topic.maintopic.index')
            ->with('success', 'Main topic deleted successfully!');
    }


    //subtopic
    public function subindex(Request $request)
    {
        $advisor = Auth::guard('advisors')->user();
        $search = $request->input('search');
        $maintopicFilter = $request->input('maintopic_id');

        // ดึงข้อมูล MainTopic ทั้งหมดเพื่อใช้ใน Dropdown ทั้งส่วน Filter และ Modal (ปรับชื่อตัวแปรเป็น $main_topics)
        $main_topics = MainTopic::all();

        $sub_topics = SubTopic::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%");
        })
            ->when($maintopicFilter, function ($query, $maintopicFilter) {
                if ($maintopicFilter === 'unattached') {
                    return $query->whereNull('mtopic_id');
                } elseif (is_numeric($maintopicFilter)) {
                    return $query->where('mtopic_id', $maintopicFilter);
                }
            })
            ->orderBy('created_at', 'asc')
            ->paginate(10)
            ->appends([
                'search' => $search,
                'maintopic_id' => $maintopicFilter
            ]);

        return view('admin.topic.subtopic.index', compact('advisor', 'sub_topics', 'search', 'maintopicFilter', 'main_topics'));
    }

    public function substore(Request $request)
    {
        $request->validate([
            'mtopic_id' => ['required', 'exists:main_topics,id'],
            'name'      => ['required', 'string', 'max:255'],
            'score'     => ['required', 'numeric', 'min:0'],
        ]);

        SubTopic::create([
            'mtopic_id' => $request->mtopic_id,
            'name'      => $request->name,
            'score'     => $request->score,
        ]);

        return redirect()->route('admin.topic.subtopic.index')
            ->with('success', 'เพิ่มหัวข้อรองสำเร็จเรียบร้อย!');
    }

    public function subupdate(Request $request, $id)
    {
        $request->validate([
            'mtopic_id' => ['required', 'exists:main_topics,id'],
            'name'      => ['required', 'string', 'max:255'],
            'score'     => ['required', 'numeric', 'min:0'],
        ]);

        $subTopic = SubTopic::findOrFail($id);

        $subTopic->update([
            'mtopic_id' => $request->mtopic_id,
            'name'      => $request->name,
            'score'     => $request->score,
        ]);

        return redirect()->route('admin.topic.subtopic.index')
            ->with('success', 'แก้ไขข้อมูลหัวข้อรองสำเร็จเรียบร้อย!');
    }

    public function subdelete($id)
    {
        $subTopic = SubTopic::findOrFail($id);
        $subTopic->delete();

        return redirect()->route('admin.topic.subtopic.index')
            ->with('success', 'ลบหัวข้อรองสำเร็จเรียบร้อย!');
    }


    //subsuptopic
    public function subsubindex(Request $request)
    {
        $advisor = Auth::guard('advisors')->user();
        $search = $request->input('search');
        $subtopicFilter = $request->input('subtopic_id'); // can be "", "unattached", or a numeric ID

        // Fetch all SubTopics for the dropdown
        $subtopics = SubTopic::all();

        $subsub_topics = SubsubTopic::when($search, function ($query, $search) {
            // Filter by sub-subtopic name
            return $query->where('name', 'like', "%{$search}%");
        })
            ->when($subtopicFilter, function ($query, $subtopicFilter) {
                // If user wants "unattached" sub-subtopics (no parent subtopic)
                if ($subtopicFilter === 'unattached') {
                    $query->whereNull('stopic_id');
                }
                // If user selected a numeric subtopic ID
                elseif (is_numeric($subtopicFilter)) {
                    $query->where('stopic_id', $subtopicFilter);
                }
            })
            ->orderBy('created_at', 'asc')
            ->paginate(10)
            ->appends([
                'search' => $search,
                'subtopic_id' => $subtopicFilter,
            ]);

        return view('admin.topic.subsubtopic.index', compact(
            'advisor',
            'subsub_topics',
            'search',
            'subtopicFilter',
            'subtopics'
        ));
    }

    public function subsubcreate()
    {

        $advisor = Auth::guard('advisors')->user();
        $subsub_topics = SubsubTopic::all();
        $sub_topics = SubTopic::all();
        return view('admin.topic.subsubtopic.create', compact('advisor', 'subsub_topics', 'sub_topics'));
    }

    public function subsubstore(Request $request)
    {
        $request->validate([
            'stopic_id' => ['required', 'exists:sub_topics,id'],
            'name' => ['required', 'string', 'max:255'],
            'score' => ['required', 'numeric'],
        ], [
            'stopic_id.required' => 'The sub-topic ID field is required.',
        ]);

        SubsubTopic::create([
            'stopic_id' => $request->stopic_id,
            'name' => $request->name,
            'score' => $request->score,
        ]);

        return redirect()->route('admin.topic.subsubtopic.index')->with('success', 'Form created successfully!');
    }

    public function subsubedit($id)
    {
        $advisor = Auth::guard('advisors')->user();
        $subsubTopic = SubsubTopic::findOrFail($id);
        $sub_topics = SubTopic::all();

        return view('admin.topic.subsubtopic.edit', compact('advisor', 'subsubTopic', 'sub_topics'));
    }

    public function subsubupdate(Request $request, $id)
    {
        $request->validate([
            'stopic_id' => ['required', 'exists:sub_topics,id'],
            'name'      => ['required', 'string', 'max:255'],
            'score'     => ['required', 'numeric'],
        ]);

        $subsubTopic = SubsubTopic::findOrFail($id);

        $subsubTopic->update([
            'stopic_id' => $request->stopic_id,
            'name'      => $request->name,
            'score'     => $request->score,
        ]);

        return redirect()->route('admin.topic.subsubtopic.index')
            ->with('success', 'Sub-subtopic updated successfully!');
    }

    public function subsubdelete($id)
    {
        $subsubTopic = SubsubTopic::findOrFail($id);
        $subsubTopic->delete();

        return redirect()->route('admin.topic.subsubtopic.index')
            ->with('success', 'Sub-subtopic deleted successfully!');
    }


    //Form setting
    public function formIndex()
    {
        $advisor = Auth::guard('advisors')->user();
        $formdata = Formset::orderBy('id', 'asc')
            ->paginate(10);
        return view('admin.form.index', compact('advisor', 'formdata'));
    }

    public function formcreate()
    {
        // If you have an ExamType model, fetch all exam types for the dropdown
        $project_types = ProjectType::all();

        // Return the create formset view, passing in the project types
        return view('admin.form.create', compact('project_types'));
    }

    public function formstore(Request $request)
    {
        $request->validate([
            'project_type_id' => ['required', 'exists:project_type,id'],
            'name'         => ['required', 'string', 'max:255'],
        ]);

        // Formset::create([
        //     'exam_type_id' => $request->exam_type_id,
        //     'name'         => $request->name,
        // ]);

        $formset = Formset::create([
            'project_type_id' => $request->project_type_id,
            'name'         => $request->name,
        ]);

        return redirect()->route('admin.form.add', $formset->id)
            ->with('success', 'Formset created successfully! Now add topics.');
    }

    public function createTopics($formsetId)
    {
        $formset = Formset::findOrFail($formsetId);
        // Fetch available topics (assumes you have a Topic model)
        $topics = MainTopic::whereNull('form_id')->get();

        return view('admin.form.addtopic', compact('formset', 'topics'));
    }

    public function storeTopics(Request $request, $forms)
    {
        $formset = Formset::findOrFail($forms);

        // Validate that 'main_topics' is an array of IDs that exist in main_topics
        $request->validate([
            'main_topics'   => 'required|array',
            'main_topics.*' => 'exists:main_topics,id',
        ]);

        // 1) Assign the form_id to all selected topics
        MainTopic::whereIn('id', $request->main_topics)
            ->update(['form_id' => $formset->id]);

        // 2) Optionally remove form_id from topics that were linked but are not selected now
        MainTopic::where('form_id', $formset->id)
            ->whereNotIn('id', $request->main_topics)
            ->update(['form_id' => null]);

        return redirect()->route('admin.form.index')
            ->with('success', 'Main topics attached to the formset successfully!');
    }

    public function editTopics($id)
    {
        // Find the formset by its ID
        $formset = Formset::findOrFail($id);

        // Retrieve topics that are either attached to this formset or not attached at all
        $topics = MainTopic::where(function ($q) use ($formset) {
            $q->where('form_id', $formset->id)
                ->orWhereNull('form_id');
        })->get();

        // Return the view for editing topics with the formset and topics data
        return view('admin.form.edittopic', compact('formset', 'topics'));
    }


    public function updateTopic(Request $request, $forms)
    {
        $formset = Formset::findOrFail($forms);

        // Validate the checkbox input
        $request->validate([
            'main_topics'   => 'nullable|array',
            'main_topics.*' => 'exists:main_topics,id',
        ]);

        // If no topics were checked, default to an empty array
        $selectedTopics = $request->input('main_topics', []);

        // 1) Assign form_id to all selected topics
        if (!empty($selectedTopics)) {
            MainTopic::whereIn('id', $selectedTopics)
                ->update(['form_id' => $formset->id]);
        }

        // 2) Remove form_id from topics that were previously linked but are now unselected
        MainTopic::where('form_id', $formset->id)
            ->whereNotIn('id', $selectedTopics)
            ->update(['form_id' => null]);

        return redirect()->route('admin.form.index')
            ->with('success', 'Main topics updated successfully!');
    }

    public function formdelete($id)
    {
        $formset = Formset::findOrFail($id);

        // Optionally, remove the association with main topics before deleting the formset.
        MainTopic::where('form_id', $formset->id)->update(['form_id' => null]);

        // Delete the formset record.
        $formset->delete();

        return redirect()->route('admin.form.index')
            ->with('success', 'Formset deleted successfully!');
    }

    public function show($id)
    {
        // Retrieve the formset by its ID
        $formset = Formset::findOrFail($id);

        // Eager load subtopics and subsubtopics for each main topic
        $attachedTopics = MainTopic::with(['sub_topics.subsub_topics'])
            ->where('form_id', $formset->id)
            ->get();

        return view('admin.form.show', compact('formset', 'attachedTopics'));
    }

    // Alumni Setting
    public function alumniProjectIndex(Request $request)
    {
        $project_types = ProjectType::all();

        $alumniProjects = AlumniProject::with(['projectType', 'advisor'])
            ->when($request->project_type_id, function ($query) use ($request) {
                $query->where('project_type_id', $request->project_type_id);
            })
            ->when($request->search, function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('keyword', 'like', "%{$search}%");
                });
            })
            ->orderBy('id', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.alumni.project.index',
            compact('project_types', 'alumniProjects')
        );
    }

    public function alumniProjectCreate()
    {
        $project_types = ProjectType::all();
        $advisors = Advisor::all();
        $academicYears = AcademicYear::all();
        $students = Student::where('status', 'graduated')->get();
        return view('admin.alumni.project.create', compact('project_types', 'advisors', 'academicYears', 'students'));
    }

    public function alumniProjectStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'keyword' => 'required|string|max:255',
            'project_type_id' => 'required|exists:project_type,id',
            'academic_year' => 'required|exists:academic_years,id',
            'advisor_id' => 'required|exists:advisors,id',
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',

            'cover_file' => 'required|image|mimes:jpg,jpeg,png,webp|max:51200',
            'abstract_file' => 'required|file|mimes:pdf|max:51200',
            'project_file' => 'required|file|mimes:pdf|max:51200',
        ]);

        DB::beginTransaction();

        try {
            /* =========================
         * 1. สร้าง Project Group
         * ========================= */
            $projectGroup = ProjectGroup::create([
                'ac_id' => $request->academic_year,
                'status' => '0',
            ]);

            /* =========================
         * 2. เพิ่มสมาชิกกลุ่ม
         * ========================= */
            foreach ($request->student_ids as $studentId) {
                GroupMember::create([
                    'group_id' => $projectGroup->id,
                    's_id' => $studentId,
                ]);
            }

            /* =========================
         * 3. สร้าง Alumni Project
         * ========================= */
            $alumniProject = AlumniProject::create([
                'title' => $request->title,
                'keyword' => $request->keyword,
                'project_type_id' => $request->project_type_id,
                'advisor_id' => $request->advisor_id,
                'project_group_id' => $projectGroup->id,
            ]);

            /* =========================
         * 4. Upload Files
         * ========================= */
            $basePath = 'uploads/alumni_projects/' . $alumniProject->id;

            $coverFile = $request->file('cover_file');
            $coverName = 'cover_' . Str::uuid() . '.' . $coverFile->getClientOriginalExtension();

            $coverPath = $coverFile->storeAs(
                $basePath . '/cover',
                $coverName,
                'public'
            );

            $abstractFile = $request->file('abstract_file');
            $abstractName = 'abstract_' . Str::uuid() . '.' . $abstractFile->getClientOriginalExtension();

            $abstractPath = $abstractFile->storeAs(
                $basePath . '/abstract',
                $abstractName,
                'public'
            );

            $projectFile = $request->file('project_file');
            $projectName = 'project_' . Str::uuid() . '.' . $projectFile->getClientOriginalExtension();

            $projectPath = $projectFile->storeAs(
                $basePath . '/project',
                $projectName,
                'public'
            );

            /* =========================
         * 5. บันทึก upload_files
         * ========================= */
            UploadFile::create([
                'fileable_id' => $alumniProject->id,
                'fileable_type' => AlumniProject::class,
                'cover_file' => $coverPath,
                'abstract_file' => $abstractPath,
                'project_file' => $projectPath,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.alumni.project.index')
                ->with('success', 'เพิ่มโครงงานศิษย์เก่าสำเร็จ');
        } catch (\Exception $e) {
            DB::rollBack();

            // ลบไฟล์ถ้ามี error
            if (isset($basePath)) {
                Storage::disk('public')->deleteDirectory($basePath);
            }

            return back()
                ->withErrors(['error' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function alumniProjectEdit(AlumniProject $alumniProject)
    {
        $project_types = ProjectType::all();
        $advisors = Advisor::all();
        $academicYears = AcademicYear::all();
        $students = Student::where('status', 'graduated')->get();

        // นักศึกษาในกลุ่มเดิม
        $selectedStudents = GroupMember::where(
            'group_id',
            $alumniProject->project_group_id
        )->pluck('s_id')->toArray();

        $uploadFile = UploadFile::where([
            'fileable_id' => $alumniProject->id,
            'fileable_type' => AlumniProject::class
        ])->first();

        return view(
            'admin.alumni.project.edit',
            compact(
                'alumniProject',
                'project_types',
                'advisors',
                'academicYears',
                'students',
                'selectedStudents',
                'uploadFile'
            )
        );
    }

    public function alumniProjectUpdate(Request $request, AlumniProject $alumniProject)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'keyword' => 'required|string|max:255',
            'project_type_id' => 'required|exists:project_type,id',
            'academic_year' => 'required|exists:academic_years,id',
            'advisor_id' => 'required|exists:advisors,id',
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',

            'cover_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:51200',
            'abstract_file' => 'nullable|file|mimes:pdf|max:51200',
            'project_file' => 'nullable|file|mimes:pdf|max:51200',
        ]);

        DB::beginTransaction();

        try {
            /* =========================
         * 1. Update Project Group
         * ========================= */
            $projectGroup = ProjectGroup::findOrFail($alumniProject->project_group_id);
            $projectGroup->update([
                'ac_id' => $request->academic_year,
            ]);

            /* =========================
         * 2. Update Group Members
         * ========================= */
            GroupMember::where('group_id', $projectGroup->id)->delete();

            foreach ($request->student_ids as $studentId) {
                GroupMember::create([
                    'group_id' => $projectGroup->id,
                    's_id' => $studentId,
                ]);
            }

            /* =========================
         * 3. Update Alumni Project
         * ========================= */
            $alumniProject->update([
                'title' => $request->title,
                'keyword' => $request->keyword,
                'project_type_id' => $request->project_type_id,
                'advisor_id' => $request->advisor_id,
            ]);

            /* =========================
        * 4. Update Files (ถ้ามี)
        * ========================= */
            $uploadFile = UploadFile::where([
                'fileable_id' => $alumniProject->id,
                'fileable_type' => AlumniProject::class
            ])->first();

            $basePath = 'uploads/alumni_projects/' . $alumniProject->id;

            /* ===== Cover ===== */
            if ($request->hasFile('cover_file')) {
                if ($uploadFile->cover_file) {
                    Storage::disk('public')->delete($uploadFile->cover_file);
                }

                $coverFile = $request->file('cover_file');
                $coverName = 'cover_' . Str::uuid() . '.' . $coverFile->getClientOriginalExtension();

                $coverPath = $coverFile->storeAs(
                    $basePath . '/cover',
                    $coverName,
                    'public'
                );

                $uploadFile->cover_file = $coverPath;
            }

            /* ===== Abstract ===== */
            if ($request->hasFile('abstract_file')) {
                if ($uploadFile->abstract_file) {
                    Storage::disk('public')->delete($uploadFile->abstract_file);
                }

                $abstractFile = $request->file('abstract_file');
                $abstractName = 'abstract_' . Str::uuid() . '.' . $abstractFile->getClientOriginalExtension();

                $abstractPath = $abstractFile->storeAs(
                    $basePath . '/abstract',
                    $abstractName,
                    'public'
                );

                $uploadFile->abstract_file = $abstractPath;
            }

            /* ===== Project ===== */
            if ($request->hasFile('project_file')) {
                if ($uploadFile->project_file) {
                    Storage::disk('public')->delete($uploadFile->project_file);
                }

                $projectFile = $request->file('project_file');
                $projectName = 'project_' . Str::uuid() . '.' . $projectFile->getClientOriginalExtension();

                $projectPath = $projectFile->storeAs(
                    $basePath . '/project',
                    $projectName,
                    'public'
                );

                $uploadFile->project_file = $projectPath;
            }

            $uploadFile->save();

            DB::commit();

            return redirect()
                ->route('admin.alumni.project.index')
                ->with('success', 'แก้ไขโครงงานศิษย์เก่าสำเร็จ');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    public function alumniProjectDelete($id)
    {
        DB::beginTransaction();

        try {
            $alumniProject = AlumniProject::findOrFail($id);

            $projectGroupId = $alumniProject->project_group_id;
            $basePath = 'uploads/alumni_projects/' . $alumniProject->id;

            /* =========================
         * 1. ลบ upload_files
         * ========================= */
            UploadFile::where([
                'fileable_id' => $alumniProject->id,
                'fileable_type' => AlumniProject::class
            ])->delete();

            /* =========================
         * 2. ลบ group_members
         * ========================= */
            if ($projectGroupId) {
                GroupMember::where('group_id', $projectGroupId)->delete();
            }

            /* =========================
         * 3. ลบ project_groups
         * ========================= */
            if ($projectGroupId) {
                ProjectGroup::where('id', $projectGroupId)->delete();
            }

            /* =========================
         * 4. ลบ alumni_projects
         * ========================= */
            $alumniProject->delete();

            DB::commit();

            /* =========================
         * 5. ลบไฟล์ (นอก transaction)
         * ========================= */
            Storage::disk('public')->deleteDirectory($basePath);

            return redirect()
                ->route('admin.alumni.project.index')
                ->with('success', 'ลบโครงงานศิษย์เก่าสำเร็จ');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors([
                'error' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ]);
        }
    }
}
