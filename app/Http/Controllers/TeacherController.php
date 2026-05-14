<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\AcademicYear;
use App\Models\Advisor;
use App\Models\ProjectGroup;
use App\Models\InvigilatorGroup;
use App\Models\InviGroupMember;
use App\Models\Propose;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function index()
    {
        $advisor = Auth::guard('advisors')->user();

        return view('teacher.dashboard', compact('advisor'));
    }


    // Calendar Setting
    public function calendarIndex(Request $request)
    {
        // ดึงข้อมูลปีการศึกษาที่มีอยู่ทั้งหมด
        $academicYears = AcademicYear::all();

        // ถ้ามีการเลือกปีการศึกษา
        if ($request->has('ac_id') && $request->ac_id) {
            // กรองข้อมูลจากปีการศึกษาที่เลือก
            $calendarData = Calendar::with('academic_year')
                ->where('ac_id', $request->ac_id)
                ->paginate(10);
        } else {
            // ถ้าไม่มีการเลือกปีการศึกษาให้แสดงทั้งหมด
            $calendarData = Calendar::with('academic_year')->paginate(10);
        }

        return view('teacher.calendar.index', compact('calendarData', 'academicYears'));
    }

    public function calendarCreate()
    {
        // Fetch all academic years
        $academic_years = AcademicYear::all();
        return view('teacher.calendar.create', compact('academic_years'));
    }

    public function calendarStore(Request $request)
    {
        // dd($request->all());
        // Validate the incoming data
        $request->validate([
            'ac_id' => ['required', 'exists:academic_years,id'],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Create a new calendar entry
        Calendar::create([
            'ac_id' => $request->ac_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('teacher.calendar.index')->with('success', 'เพิ่มรายการเรียบร้อยแล้ว');
    }

    public function calendarHome(Request $request)
    {
        // เช็คว่าเป็นผู้ใช้ประเภทไหน โดยใช้ guard
        $user = Auth::guard('web')->user(); // ใช้ guard 'web' สำหรับผู้ใช้งานทั่วไป

        if (Auth::guard('students')->check()) {
            // สำหรับ student
            $userAcId = Auth::user()->ac_id;  // ดึง ac_id ของ student
            $calendarData = Calendar::with('academic_year')
                ->where('ac_id', $userAcId)  // กรองข้อมูลตาม ac_id ของ student
                ->get();
        } elseif (Auth::guard('advisors')->check()) {
            // สำหรับ advisor หรือ teacher
            // ถ้ามีการเลือกปีการศึกษา
            if ($request->has('ac_id') && $request->ac_id) {
                $calendarData = Calendar::with('academic_year')
                    ->where('ac_id', $request->ac_id) // กรองข้อมูลตามปีการศึกษาที่เลือก
                    ->get();
            } else {
                // ถ้าไม่ได้เลือกปีการศึกษาให้แสดงปีล่าสุด
                $lastAcademicYear = AcademicYear::orderBy('year', 'desc')->first();
                if ($lastAcademicYear) {
                    $lastAcId = $lastAcademicYear->id;
                    $calendarData = Calendar::with('academic_year')
                        ->where('ac_id', $lastAcId)  // ใช้ปีการศึกษาล่าสุด
                        ->get();
                } else {
                    $calendarData = [];
                }
            }
        } else {
            $calendarData = [];  // หากไม่มีประเภทผู้ใช้ (เช่น guest หรือ unregistered)
        }

        // ดึงข้อมูลปีการศึกษาที่มีอยู่ทั้งหมด
        $academicYears = AcademicYear::all();

        return view('teacher.calendar.home', compact('calendarData', 'academicYears'));
    }

    // ฟังก์ชันแสดงฟอร์มแก้ไขข้อมูล
    public function editCalendar($id)
    {
        // ดึงข้อมูล Calendar ที่ต้องการแก้ไข
        $calendar = Calendar::findOrFail($id);

        // ดึงข้อมูลปีการศึกษาที่มีอยู่ทั้งหมด
        $academic_years = AcademicYear::all();

        // ส่งข้อมูลไปยังหน้า edit
        return view('teacher.calendar.edit', compact('calendar', 'academic_years'));
    }

    // ฟังก์ชันสำหรับอัปเดตข้อมูลที่ถูกแก้ไข
    public function updateCalendar(Request $request, $id)
    {
        // Validate the input
        $validated = $request->validate([
            'ac_id' => 'required|exists:academic_years,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Find the calendar entry and update
        $calendar = Calendar::findOrFail($id);
        $calendar->update($validated);

        // Redirect back to the index with a success message
        return redirect()->route('teacher.calendar.index')->with('success', 'แก้ไขรายการเรียบร้อยแล้ว!');
    }

    public function deleteCalendar($id)
    {
        // ดึงข้อมูล Calendar ที่จะลบ
        $calendar = Calendar::findOrFail($id);

        // ลบข้อมูลจากฐานข้อมูล
        $calendar->delete();

        // ส่งผู้ใช้กลับไปยังหน้าหลักพร้อมกับข้อความสำเร็จ
        return redirect()->route('teacher.calendar.index')->with('success', 'ลบรายการเรียบร้อยแล้ว!');
    }


    // Proposal Setting
    public function proposeIndex(Request $request)
    {
        // สร้าง Query Builder ค้างไว้ พร้อมโหลด relations
        $query = Propose::with(['advisor', 'project_group']);

        // รับค่าจาก Form
        $status = $request->input('status');
        $search = $request->input('search');

        // 1. ฟิลเตอร์ตาม "สถานะ" (รองรับค่า '0')
        if ($status !== null && $status !== '') {
            $query->whereHas('project_group', function ($q) use ($status) {
                $q->where('status', $status);
            });
        }

        // 2. ฟิลเตอร์ตาม "คำค้นหา" (ค้นหาจากชื่อโครงงาน หรือ ชื่ออาจารย์)
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                // ค้นหาในชื่อโครงงาน (title)
                $q->where('title', 'LIKE', "%{$search}%")
                    // หรือค้นหาจากชื่ออาจารย์ที่ปรึกษา (relation: advisor)
                    ->orWhereHas('advisor', function ($advQuery) use ($search) {
                        $advQuery->where('a_fname', 'LIKE', "%{$search}%")
                            ->orWhere('a_lname', 'LIKE', "%{$search}%");
                    });
            });
        }

        // ดึงข้อมูลพร้อมการแบ่งหน้า และแนบ Query String กลับไป (เพื่อให้กดเปลี่ยนหน้าแล้วค่าค้นหาไม่หาย)
        $propose = $query->latest()->paginate(10)->withQueryString();

        return view('teacher.propose.index', compact('propose'));
    }


    //Inviligator Group Setting
    public function InvigilatorHome()
    {
        // ดึงข้อมูลกลุ่ม Invigilator และนับจำนวนกลุ่มตามปี
        $groupWithMemberCount = InvigilatorGroup::with('academic_year') // Load ความสัมพันธ์
            ->selectRaw('ac_id, COUNT(id) as group_count') // นับจำนวนกลุ่มในแต่ละปี
            ->groupBy('ac_id') // จัดกลุ่มตาม ac_id (ปีการศึกษา)
            ->with('academic_year') // ดึงข้อมูลปีการศึกษาจากความสัมพันธ์
            ->orderBy('ac_id', 'desc')
            ->get();

        // ส่งข้อมูลไปยัง View
        return view('teacher.invigilator.home', compact('groupWithMemberCount'));
    }

    // Show invigilator group
    public function InvigilatorGroup($ac_id)
    {
        // ดึงข้อมูลปีการศึกษาที่เลือก
        $academicYear = AcademicYear::findOrFail($ac_id);

        // ดึงข้อมูลกลุ่ม Invigilator โดยอิงจาก ac_id ที่ระบุ
        $groups = InvigilatorGroup::with('academic_year')
            ->where('ac_id', $ac_id) // เฉพาะกลุ่มที่อยู่ใน ac_id
            ->get();

        // สำหรับแต่ละกลุ่ม, คำนวณจำนวนสมาชิกในกลุ่ม
        $groupsWithMemberCount = $groups->map(function ($group) {
            // นับจำนวนสมาชิกของกลุ่มจาก InviGroupMember
            $memberCount = InviGroupMember::where('invi_group_id', $group->id)->count();
            $group->memberCount = $memberCount; // เพิ่มฟิลด์ memberCount เข้าไปในอ็อบเจ็กต์
            return $group;
        });

        // ส่งข้อมูลไปยัง View
        return view('teacher.invigilator.group', compact('groupsWithMemberCount', 'academicYear'));
    }


    // ฟังก์ชันแสดงหน้า Create
    public function create(Request $request)
    {
        // ดึงข้อมูลปีการศึกษาทั้งหมด
        $academic_years = AcademicYear::all();

        // ตรวจสอบว่ามีการเลือกปีการศึกษาหรือไม่
        if ($request->has('ac_id')) {
            // ดึงข้อมูลปีการศึกษาที่เลือก
            $ac_id = $request->ac_id;

            // ดึงข้อมูลอาจารย์ที่ยังไม่ได้ถูกเลือกในปีการศึกษานั้น
            $advisors = Advisor::where('a_type', '!=', 'admin')
                ->whereNotIn('id', function ($query) use ($ac_id) {
                    // ดึงรายการอาจารย์ที่อยู่ในกลุ่มในปีการศึกษาที่เลือก
                    $query->select('a_id')
                        ->from('invi_group_members')
                        ->join('invigilator_groups', 'invi_group_members.invi_group_id', '=', 'invigilator_groups.id')
                        ->where('invigilator_groups.ac_id', $ac_id);
                })
                ->get();
        } else {
            // ถ้าไม่มีการเลือกปีการศึกษา ให้แสดงอาจารย์ทั้งหมด
            $advisors = Advisor::where('a_type', '!=', 'admin')->get();
        }

        // ส่งข้อมูลไปยัง View
        return view('teacher.invigilator.create', compact('academic_years', 'advisors'));
    }


    // ฟังก์ชันบันทึกข้อมูลกลุ่มกรรมการ
    public function store(Request $request)
    {
        // การตรวจสอบข้อมูลที่ได้รับจากฟอร์ม
        $request->validate([
            'name' => 'required|string|max:255',
            'ac_id' => 'required|integer',
            'advisors' => 'required|array',  // รับหลายค่า
            'advisors.*' => 'exists:advisors,id',  // ตรวจสอบว่าอาจารย์ที่เลือกมีอยู่ในฐานข้อมูล
        ]);

        // ตรวจสอบว่ามีการบันทึกชื่อกลุ่มเดียวกันในปีการศึกษาเดียวกันหรือไม่
        $existingGroup = InvigilatorGroup::where('name', $request->name)
            ->where('ac_id', $request->ac_id)
            ->exists();

        if ($existingGroup) {
            return back()->withErrors(['name' => 'A group with this name already exists in the selected academic year.']);
        }

        // หากไม่พบชื่อกลุ่มซ้ำกัน ก็ให้สร้างกลุ่มใหม่
        $invigilatorGroup = InvigilatorGroup::create([
            'name' => $request->name,
            'ac_id' => $request->ac_id,
        ]);

        // บันทึกข้อมูลสมาชิกของกลุ่ม
        foreach ($request->advisors as $advisorId) {
            InviGroupMember::create([
                'invi_group_id' => $invigilatorGroup->id,  // บันทึก ID ของกลุ่ม Invigilator
                'a_id' => $advisorId,  // บันทึก ID ของอาจารย์
            ]);
        }

        // แสดงผลลัพธ์หลังการบันทึกข้อมูล
        return redirect()->route('teacher.invigilator.home')->with('success', 'เพิ่มกลุ่มกรรมการเรียบร้อยแล้ว!');
    }

    public function showGroupMembers($id)
    {
        // ดึงข้อมูลกลุ่ม Invigilator
        $group = InvigilatorGroup::findOrFail($id);

        // ดึงข้อมูลกรรมการในกลุ่มนั้นๆ (อาจารย์) โดยเชื่อมกับตาราง InviGroupMember และ Advisor
        $members = InviGroupMember::with('advisor') // เชื่อมโยงกับตาราง Advisor
            ->where('invi_group_id', $id)
            ->get();

        // ส่งข้อมูลไปยัง View
        return view('teacher.invigilator.member', compact('group', 'members'));
    }

    public function editGroupMembers($id)
    {
        // ดึงข้อมูลกลุ่ม Invigilator ที่เลือก
        $group = InvigilatorGroup::findOrFail($id);

        // ดึงข้อมูลสมาชิกในกลุ่มนั้น ๆ (อาจารย์)
        $members = InviGroupMember::with('advisor')
            ->where('invi_group_id', $id)
            ->get();

        // ดึงข้อมูลอาจารย์ที่สามารถเลือกได้ โดยกรองอาจารย์ที่ยังไม่ได้ถูกเลือกในกลุ่มนั้น ๆ
        $advisors = Advisor::where('a_type', '!=', 'admin')
            ->whereNotIn('id', $members->pluck('a_id')->toArray()) // กรองอาจารย์ที่อยู่ในกลุ่มแล้ว
            ->get();

        // ส่งข้อมูลไปยัง View
        return view('teacher.invigilator.edit', compact('group', 'members', 'advisors'));
    }

    public function updateGroupMembers(Request $request, $id)
    {
        // ดึงข้อมูลกลุ่ม Invigilator
        $group = InvigilatorGroup::findOrFail($id);

        // ตรวจสอบว่ามีการเลือกอาจารย์ที่จะลบออกจากกลุ่มหรือไม่
        if ($request->has('remove_advisors')) {
            // ลบสมาชิกที่ไม่ได้ถูกเลือก (uncheck)
            InviGroupMember::where('invi_group_id', $id)
                ->whereNotIn('a_id', $request->remove_advisors) // ลบสมาชิกที่ไม่ได้ถูกเลือก
                ->delete();
        }

        // ตรวจสอบว่ามีการเพิ่มอาจารย์ใหม่หรือไม่
        if ($request->has('advisors')) {
            // เพิ่มอาจารย์ใหม่เข้าไปในกลุ่ม
            foreach ($request->advisors as $advisorId) {
                InviGroupMember::create([
                    'invi_group_id' => $id,
                    'a_id' => $advisorId,
                ]);
            }
        }

        // ส่งผู้ใช้กลับไปที่หน้าหลักหรือแสดงผลลัพธ์หลังจากอัพเดต
        return redirect()->route('teacher.invigilator.group', $group->ac_id)->with('success', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
    }

    public function deleteGroup($id)
    {
        // ดึงข้อมูลกลุ่ม Invigilator
        $group = InvigilatorGroup::findOrFail($id);

        // เริ่มต้นการทำงานใน Transaction เพื่อความปลอดภัยในการลบข้อมูล
        DB::beginTransaction();

        try {
            // ลบข้อมูลสมาชิกในกลุ่มก่อน
            InviGroupMember::where('invi_group_id', $id)->delete();

            // ลบกลุ่ม Invigilator ที่เลือก
            $group->delete();

            // ทำการ commit การเปลี่ยนแปลงในฐานข้อมูล
            DB::commit();

            // ส่งผู้ใช้กลับไปยังหน้าหลักหรือแสดงผลลัพธ์หลังจากการลบ
            return redirect()->route('teacher.invigilator.group', $group->ac_id)
                ->with('success', 'ลบข้อมูลกลุ่มกรรมการเรียบร้อยแล้ว!');
        } catch (\Exception $e) {
            // ถ้ามีข้อผิดพลาดเกิดขึ้น ทำการ rollback การเปลี่ยนแปลง
            DB::rollBack();
            // ส่งข้อผิดพลาดไปที่ view
            return back()->withErrors(['error' => 'ไม่สามารถลบข้อมูลได้ อาจเกิดจากมีการใช้งานข้อมูลกลุ่มกรรมการในการจัดการสอบ!!']);
        }
    }

    public function proposeShow($id)
    {
        $advisor = Auth::guard('advisors')->user();

        $propose = Propose::findOrFail($id);
        // dd($propose);

        // ดึงสมาชิกจากความสัมพันธ์ (พร้อม student)
        $members = optional($propose->project_group)->group_members ?? collect();

        return view('teacher.propose.show', compact('advisor', 'propose', 'members'));
    }
}
