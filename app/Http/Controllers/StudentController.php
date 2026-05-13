<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\ProjectGroup;
use App\Models\GroupMember;
use App\Models\Propose;
use App\Models\Advisor;
use App\Models\Base\Examination;
use App\Models\ExamSubmission;
use App\Models\ExamInviMember;
use App\Models\ExamSubmissionHistory;
use App\Models\InviGroupMember;
use App\Models\ProjectType;
use App\Models\ProposeHistory;
use App\Models\InvigilatorGroup;
use App\Models\ExamType;
use App\Models\Revision;
use App\Models\RevisionApproval;
use App\Models\Upload;
use App\Models\UploadFile;
use App\Models\Major;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\CodeCoverage\Report\Xml\Totals;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StudentController extends Controller
{

    // Start Group Setting
    public function groupIndex(Request $request)
    {
        // ดึงข้อมูลผู้ใช้ที่ล็อกอินอยู่
        $stdLogin = Auth::guard('students')->user();

        // รับค่าคำค้นหาจากช่อง Search
        $search = $request->input('search');

        // กรองข้อมูล Student ตาม m_id ของผู้ที่ล็อกอิน (สาขาเดียวกัน)
        $Student = Student::where('m_id', $stdLogin->m_id)
            ->where('status', 'active') // นักศึกษาที่มีสถานะ active
            ->when($search, function ($query, $search) {
                // ถ้าระบุคำค้นหา ให้หาจาก รหัสนักศึกษา, ชื่อ หรือ นามสกุล
                return $query->where(function ($q) use ($search) {
                    $q->where('s_id', 'LIKE', "%{$search}%")
                        ->orWhere('s_fname', 'LIKE', "%{$search}%")
                        ->orWhere('s_lname', 'LIKE', "%{$search}%");
                });
            })
            ->orderBy('s_id', 'asc') // เรียงตามรหัสนักศึกษาจะดูเป็นระเบียบกว่าเรียงตาม id (PK)
            ->paginate(10)
            ->withQueryString(); // คำสั่งนี้ช่วยให้ตอนกดเปลี่ยนหน้า Pagiantion (หน้า 2, 3) ค่า Search จะไม่หายไป

        // ตรวจสอบว่า นักศึกษาที่ล็อกอินอยู่ เป็นสมาชิกกลุ่มหรือยัง
        $groupMember = GroupMember::where('s_id', $stdLogin->id)->first();
        $hasGroup  = $groupMember !== null;
        $groupId   = $groupMember->group_id ?? null;

        return view('student.group.index', compact('Student', 'stdLogin', 'hasGroup', 'groupId', 'search'));
    }

    public function groupCreate(Request $request)
    {
        // ดึงข้อมูลผู้ที่ล็อกอินอยู่
        $stdLogin = Auth::guard('students')->user();

        // รับคีย์เวิร์ดค้นหา (หรือ null)
        $search = $request->input('search');

        // ดึงรายการปีการศึกษา ทั้งหมด
        $years = AcademicYear::orderBy('year', 'desc')->get();

        // รวบรวม s_id ของนักศึกษาที่อยู่ในทุกกลุ่ม
        $memberIds = GroupMember::pluck('s_id')->toArray();

        // สร้าง Query Builder เพื่อกรองนักศึกษา
        $studentsQuery = Student::whereNotIn('id', $memberIds)
            ->where('id', '<>', $stdLogin->id);

        // ถ้ามีการค้นหา ให้วางเงื่อนไขชื่อ/สกุล หรือ รหัส
        if ($search) {
            $studentsQuery->where(function ($q) use ($search) {
                $q->where('s_fname', 'like', "%{$search}%")
                    ->orWhere('s_lname', 'like', "%{$search}%")
                    ->orWhere('s_id',    'like', "%{$search}%");
            });
        }

        // เรียงและดึงผลลัพธ์
        // $students = $studentsQuery->orderBy('id', 'asc')->get();
        $students = $studentsQuery
            ->where('status', 'active')
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('student.group.create', compact('students', 'stdLogin', 'search', 'years'));
    }

    public function groupStore(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'ac_id'      => 'required|exists:academic_years,id',
            'students'   => 'nullable|array',
            'students.*' => 'exists:students,id',
        ], [
            'ac_id.required'    => 'กรุณาเลือกปีการศึกษา',
            'ac_id.exists'      => 'ปีการศึกษาที่เลือกไม่ถูกต้อง',
            'students.*.exists' => 'ไม่พบข้อมูลนักศึกษาที่เลือก',
        ]);

        // Create a new project group
        $group = ProjectGroup::create([
            'status' => 1,
            'ac_id' => $request['ac_id'],
        ]);

        // ดึงผู้ใช้งานปัจจุบัน
        $stdLogin = Auth::guard('students')->user();

        // บันทึกตัวเองก่อน
        GroupMember::create([
            'group_id' => $group->id,
            's_id'     => $stdLogin->id,
        ]);

        // ถ้ามีนักศึกษาที่เลือก ให้บันทึกต่อ
        if (!empty($validated['students'])) {
            foreach ($validated['students'] as $studentId) {
                // ข้ามตัวเอง ถ้าเผลอเลือกซ้ำ
                if ($studentId == $stdLogin->id) {
                    continue;
                }
                $student = Student::findOrFail($studentId);
                GroupMember::create([
                    'group_id' => $group->id,
                    's_id'     => $student->id,
                ]);
            }
        }

        return redirect()
            ->route('student.group.index')
            ->with('success', 'สร้างกลุ่มเรียบร้อยแล้ว');
    }

    public function edit(Request $request, $groupId)
    {
        // โหลดข้อมูลกลุ่ม
        $group     = ProjectGroup::findOrFail($groupId);
        $search    = $request->input('search');
        $stdLogin  = Auth::guard('students')->user();       // ดึงผู้ล็อกอิน

        // รวบรวม s_id ของสมาชิกกลุ่มนี้
        $memberIds = GroupMember::where('group_id', $groupId)->pluck('s_id');

        // 1) นักศึกษาที่อยู่ในกลุ่มนี้
        $assignedQuery = Student::whereIn('id', $memberIds);

        // 2) นักศึกษาที่ยังไม่อยู่ในกลุ่มใดเลย
        $availableQuery = Student::whereNotIn('id', function ($q) {
            $q->select('s_id')->from('group_members');
        });

        // กรองค้นหาบน available เท่านั้น (assigned ไม่ต้องกรอง)
        if ($search) {
            $availableQuery->where(function ($q) use ($search) {
                $q->where('s_fname', 'like', "%{$search}%")
                    ->orWhere('s_lname', 'like', "%{$search}%");
            });
        }

        $assignedStudents  = $assignedQuery->get();
        $availableStudents = $availableQuery->where('status', 'active')
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('student.group.edit', compact(
            'group',
            'assignedStudents',
            'availableStudents',
            'search',
            'stdLogin'               // ส่งตัวแปรเข้าไปด้วย
        ));
    }

    public function update(Request $request, $groupId)
    {
        // Validate input
        $validated = $request->validate([
            'students'   => 'nullable|array',
            'students.*' => 'exists:students,id',
        ], [
            'students.*.exists' => 'ไม่พบข้อมูลนักศึกษาที่เลือก',
        ]);

        // โหลดกลุ่มและผู้ใช้ปัจจุบัน
        $group    = ProjectGroup::findOrFail($groupId);
        $stdLogin = Auth::guard('students')->user();

        // เตรียมลิสต์ id ของนักศึกษาที่จะเป็นสมาชิกใหม่ (PK id)
        $newIds = $validated['students'] ?? [];
        // บังคับให้ตัวเองอยู่ในกลุ่มเสมอ
        if (!in_array($stdLogin->id, $newIds)) {
            $newIds[] = $stdLogin->id;
        }

        // ดึงรายการ id ปัจจุบันจาก group_members.s_id
        $currentIds = GroupMember::where('group_id', $groupId)
            ->pluck('s_id')
            ->toArray();

        // คำนวนหาว่าจะลบใคร (มีใน current แต่ไม่ได้เลือกใหม่)  
        $toRemove = array_diff($currentIds, $newIds);
        // คำนวนหาว่าจะเพิ่มใคร (เลือกใหม่แต่ยังไม่มีใน current)
        $toAdd    = array_diff($newIds, $currentIds);

        // ลบสมาชิกที่ถูกถอดออก
        if (!empty($toRemove)) {
            GroupMember::where('group_id', $groupId)
                ->whereIn('s_id', $toRemove)
                ->delete();
        }

        // เพิ่มสมาชิกใหม่
        foreach ($toAdd as $studentId) {
            GroupMember::create([
                'group_id' => $groupId,
                's_id'      => $studentId,
            ]);
        }

        return redirect()
            ->route('student.group.index')
            ->with('success', 'อัปเดตสมาชิกกลุ่มเรียบร้อยแล้ว');
    }

    //End Group Setting


    // Start Propose Setting
    public function proposeIndex()
    {
        $user = Auth::guard('students')->user(); // ดึงข้อมูลผู้ที่ล็อกอิน
        $userGroup = GroupMember::where('s_id', $user->id)->first(); // ตรวจสอบว่าผู้ใช้มี group หรือไม่

        // ถ้าไม่มีให้รีไดเรกต์ไปสร้างกลุ่ม
        if (! $userGroup) {
            return redirect()
                ->route('student.group.index')
                ->with('error', 'กรุณาสร้างกลุ่มโครงงานก่อน');
        }

        // ถ้าผู้ใช้ไม่มี group_id ใน group_members ให้กำหนดให้เป็น null
        $userGroupId = $userGroup ? $userGroup->group_id : null;

        // ดึง proposals ที่เกี่ยวข้องกับ group_id ของผู้ใช้งาน
        $proposals = Propose::with('advisor') // โหลดความสัมพันธ์ advisor
            ->where('group_id', $userGroupId) // กรองเฉพาะ group_id ของผู้ที่ล็อกอิน
            ->orderBy('id', 'asc') // เรียงตาม id จากน้อยไปมาก
            ->get();

        // ตรวจสอบว่า id ที่ล็อกอินมี group_id ตรงกับ group_id ใน proposals หรือไม่
        $hasActiveProposal = Propose::where('group_id', $userGroupId)->exists(); // ตรวจสอบว่ามี proposal หรือไม่

        // ส่งข้อมูลไปยัง View
        return view('student.propose.index', compact('proposals', 'hasActiveProposal', 'userGroupId'));
    }

    public function proposeCreate()
    {
        $advisors = Advisor::all()
            ->where('a_type', '!=', 'admin');
        // ->orderBy('id', 'asc')
        // ->get();

        // Propose types
        $types = ProjectType::all();

        return view('student.propose.create', compact('advisors', 'types'));
    }

    public function proposeStore(Request $request)
    {
        // ตรวจสอบข้อมูลที่ส่งมา
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'objective' => 'required|string',
            'scope' => 'required|string',
            'tools' => 'nullable|string',
            'type_id'   => 'required|exists:project_type,id',
            'a_id' => 'required|exists:advisors,id',
        ], [
            'title.required' => 'กรุณากรอกชื่อโครงงาน',
            'objective.required' => 'กรุณากรอกวัตถุประสงค์ของโครงงาน',
            'scope.required' => 'กรุณากรอกขอบเขตของโครงงาน',
            'tools.required' => 'กรุณากรอกภาษาและเครื่องมือที่ใช้ในการพัฒนาโครงงาน',
            'type_id.required'   => 'กรุณาเลือกประเภทโครงงาน',
            'a_id.required' => 'กรุณาเลือกอาจารย์ที่ปรึกษา',
        ]);

        // ดึงค่า group_id ของผู้ใช้ที่ล็อกอิน
        $student = Auth::guard('students')->user(); // ดึงข้อมูลผู้ที่ล็อกอิน
        $userGroup = GroupMember::where('s_id', $student->id)->first(); // ตรวจสอบว่าผู้ใช้มี group หรือไม่

        // สร้าง Proposal ใหม่
        Propose::create([
            'title' => $validated['title'],
            'objective' => $validated['objective'],
            'scope' => $validated['scope'],
            'tools' => $validated['tools'],
            'type_id'   => $validated['type_id'],
            'a_id' => $validated['a_id'],
            'group_id' => $userGroup->group_id, // เก็บ group_id ของผู้ใช้ที่ล็อกอิน
            'status' => 1, // ค่าเริ่มต้น
        ]);

        // // อัปเดตค่า status ของ project_groups เป็น 2
        // ProjectGroup::where('id', $groupId)->update(['status' => 2]);

        // Redirect หลังจากบันทึกสำเร็จ
        return redirect()->route('student.propose.index')->with('success', 'Proposal created successfully!');
    }

    public function proposeEdit($id)
    {
        $proposal = Propose::findOrFail($id); // ดึงข้อมูล Proposal

        $advisors = Advisor::all()
            ->where('a_type', '!=', 'admin'); // ดึงข้อมูล Advisor

        // Propose types
        $types = ProjectType::all();

        return view('student.propose.edit', compact('proposal', 'advisors', 'types'));
    }

    public function proposeUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'objective' => 'required|string',
            'scope' => 'required|string',
            'tools' => 'nullable|string',
            'type_id'   => 'required|exists:project_type,id',
            'a_id' => 'required|exists:advisors,id',
        ]);
        // dd($validated);

        $proposal = Propose::findOrFail($id);
        $proposal->update(array_merge($validated, ['status' => 1])); // อัปเดตข้อมูลและตั้งค่า status เป็น 1
        return redirect()->route('student.propose.index')->with('success', 'Proposal updated successfully!');
    }

    public function historyProposeIndex($id)
    {
        // ตรวจสอบว่าข้อเสนอที่ต้องการดูมีอยู่หรือไม่
        $proposal = Propose::findOrFail($id);

        // ตรวจสอบว่า user ที่ล็อกอินเป็นสมาชิกของกลุ่มที่ยื่น proposal นี้หรือไม่
        $student = Auth::guard('students')->user(); // ดึงข้อมูลผู้ที่ล็อกอิน
        $userGroup = GroupMember::where('s_id', $student->id)->first(); // ตรวจสอบว่าผู้ใช้มี group หรือไม่
        if ($userGroup->group_id !== $proposal->group_id) {
            return redirect()->route('student.propose.index')->with('error', 'You do not have access to this proposal history.');
        }

        // ดึงข้อมูลประวัติการแก้ไขของ proposal นี้
        $history = ProposeHistory::where('propose_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('student.propose.history', compact('history', 'proposal'));
    }

    // End Propose Setting


    // Start Exam Submission Setting
    public function submissionIndex()
    {
        // 1) ดึงนักศึกษา (Model เดียว)
        $student = Auth::guard('students')->user();

        // 2) ตรวจสอบว่ามีสร้างกลุ่มโครงงานไว้หรือยัง
        $groupMember = GroupMember::where('s_id', $student->id)->first();
        if (! $groupMember) {
            return redirect()
                ->route('student.group.index')
                ->with('error', 'กรุณาสร้างกลุ่มโครงงานก่อน');
        }

        // 3) ดึงข้อเสนอโครงงานของกลุ่ม
        $proposes = Propose::where('group_id', $groupMember->group_id)
            ->first();
        if (! $proposes) {
            return redirect()
                ->route('student.propose.index')
                ->with('error', 'กรุณายื่นหัวข้อโครงงานก่อน');
        }

        // 4) ดึงการส่งสอบทั้งหมดของข้อเสนอนี้
        $examsubmissions = ExamSubmission::where('propose_id', $proposes->id)
            ->get();

        // 5) ส่งไป View (ปรับชื่อตัวแปรให้สื่อความหมาย)
        return view('student.submission.index', [
            'proposes'     => $proposes,
            'examsubmissions' => $examsubmissions,
        ]);
    }

    public function submissionCreate($proposeId)
    {
        // โหลดข้อเสนอ พร้อมสมาชิกในกลุ่ม
        $propose = Propose::findOrFail($proposeId);

        // ดึงสมาชิกจากตาราง group_members ที่ group_id ตรงกับข้อเสนอนี้
        $members = GroupMember::where('group_id', $propose->group_id)
            ->get();

        // ดึงประเภทการสอบทั้งหมด
        $examTypes = ExamType::all();

        // ส่งไป View
        return view('student.submission.create', compact('propose', 'members', 'examTypes'));
    }

    public function submissionStore(Request $request, $proposeId)
    {
        // ค้นหาประเภทการสอบที่ผู้ใช้เลือกจาก exam_type_id
        $examType = ExamType::findOrFail($request->exam_type_id);

        // กำหนดกฎการตรวจสอบข้อมูลเบื้องต้น
        $rules = [
            'exam_type_id' => 'required|exists:exam_type,id',
        ];

        // ตรวจสอบว่า exam type ที่เลือก "ไม่ใช่ AUCC"
        // ถ้าไม่ใช่ AUCC ต้องบังคับกรอกครั้งที่สอบและแนบไฟล์
        if (strtolower($examType->name) !== 'aucc') {
            $rules['attempt'] = 'required|integer|min:1';
            $rules['file']    = 'required|file|mimes:pdf|max:51200';
        }

        // ทำการ validate ข้อมูลตาม rules ที่กำหนด
        $request->validate($rules);

        // ค้นหาข้อมูล propose เพื่อยืนยันว่ามีอยู่จริง
        $propose = Propose::findOrFail($proposeId);

        // ตรวจสอบว่าผู้ใช้เคยยื่นสอบประเภทนี้ไปแล้วหรือยัง
        // เพื่อป้องกันการยื่นซ้ำในประเภทเดียวกัน
        $exists = ExamSubmission::where('propose_id', $proposeId)
            ->where('exam_type_id', $request->exam_type_id)
            ->exists();

        // ถ้าพบว่ามีการยื่นซ้ำ ให้ส่งกลับไปหน้ารายการพร้อมแจ้ง error
        if ($exists) {
            return redirect()
                ->route('student.submission.index')
                ->withErrors([
                    'exam_type_id' => 'คุณได้ยื่นสอบประเภทนี้ไปแล้ว ไม่สามารถยื่นซ้ำได้'
                ]);
        }

        // เตรียมข้อมูลเบื้องต้นสำหรับบันทึกลงตาราง exam_submissions
        $data = [
            'propose_id'   => $proposeId,
            'exam_type_id' => $request->exam_type_id,
            'status'       => 1,   // กำหนดสถานะเริ่มต้นเป็น 1 (เช่น ส่งแล้ว)
        ];

        // ถ้าประเภทการสอบไม่ใช่ AUCC ให้บันทึกครั้งที่สอบ
        // แต่ถ้าเป็น AUCC จะตั้งค่า attempt เป็น null
        if (strtolower($examType->name) !== 'aucc') {
            $data['attempt'] = $request->attempt;
        } else {
            $data['attempt'] = null;
        }

        // สร้าง record ใหม่ในตาราง exam_submissions
        $submission = ExamSubmission::create($data);

        // ตรวจสอบว่ามีการอัปโหลดไฟล์เข้ามาหรือไม่
        // (AUCC จะไม่มีไฟล์แนบ)
        if ($request->hasFile('file')) {

            // เก็บไฟล์ลงใน storage
            // แยกโฟลเดอร์ตาม id ของ submission เพื่อความเป็นระเบียบ
            $filePath = $request->file('file')
                ->store("uploads/exam/submission/{$submission->id}", 'public');

            // อัปเดต path ของไฟล์ลงในฐานข้อมูล
            $submission->update(['file_path' => $filePath]);
        }

        // เมื่อบันทึกสำเร็จ ให้ redirect กลับไปหน้ารายการ submission
        // พร้อมข้อความแจ้งเตือน success
        return redirect()
            ->route('student.submission.index')
            ->with('success', 'บันทึกการส่งสอบเรียบร้อยแล้ว');
    }

    public function submission($id, $propose_id)
    {
        // ดึงข้อมูลผู้ใช้ที่ล็อกอินอยู่
        $stdLogin = Auth::guard('students')->user();

        // ดึงข้อมูลจากตาราง proposes โดยใช้ id ของผู้ใช้เป็น group_id
        $proposes = Propose::findOrFail($propose_id);

        // ดึงข้อมูลจากตาราง ProjectGroup โดยใช้ group_id ของ Propose
        $group = ProjectGroup::where('id', $proposes->group_id)->first();
        // dd($group);

        $members = GroupMember::with('student')->where('group_id', $group->id)->get();
        // dd($members);

        $examSubmission = ExamSubmission::findOrFail($id);

        // ส่งข้อมูลไปยัง View
        return view('student.submission.submission', compact('proposes', 'members', 'examSubmission'));
    }

    public function save(Request $request, $id)
    {
        // ตรวจสอบข้อมูล
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:2048',
            'attempt' => 'required|integer',
        ]);

        // ดึงข้อมูล propose ที่เกี่ยวข้อง
        $examSubmission = ExamSubmission::findOrFail($id);

        // อัปโหลดไฟล์ไปยังตำแหน่งจริง
        $file = $request->file('file');
        $finalPath = $file->store("uploads/exam/submission/{$id}", 'public');

        $examSubmission->update([
            'attempt'   => $request->attempt,
            'file_path' => $finalPath,
            'status'    => '1', // Pending
            'comments'  => $request->comments ?? null, // หากมีฟิลด์ comments
        ]);

        // ส่งกลับไปยังหน้าแรก
        return redirect()->route('student.submission.index')->with('success', 'File saved successfully!');
    }

    public function submissionEdit($submissionId, $proposeId)
    {
        // โหลด submission พร้อมความสัมพันธ์
        $submission = ExamSubmission::findOrFail($submissionId);

        $propose   = $submission->propose;
        $members   = GroupMember::with('student')
            ->where('group_id', $propose->group_id)
            ->get();
        $examTypes = ExamType::all();

        return view('student.submission.edit', compact('submission', 'propose', 'members', 'examTypes'));
    }

    public function submissionUpdate(Request $request, $submissionId)
    {
        $request->validate([
            'exam_type_id' => 'required|exists:exam_type,id',
            'attempt'      => 'required|integer|min:1',
            'file'         => 'nullable|file|mimes:pdf|max:51200',
        ]);

        $submission = ExamSubmission::findOrFail($submissionId);

        // อัปเดตข้อมูลหลัก
        $submission->update([
            'exam_type_id' => $request->exam_type_id,
            'attempt'      => $request->attempt,
            'status' => 1,
        ]);

        // ถ้ามีไฟล์ใหม่ ให้เก็บและอัปเดต path
        if ($request->hasFile('file')) {
            // ลบไฟล์เก่า (ถ้าต้องการ)
            if ($submission->file_path && Storage::disk('public')->exists($submission->file_path)) {
                Storage::disk('public')->delete($submission->file_path);
            }

            // เก็บไฟล์ใหม่ลงโฟลเดอร์เดิม
            $newPath = $request->file('file')
                ->store("uploads/exam/submission/{$submission->id}", 'public');

            $submission->update(['file_path' => $newPath]);
        }

        return redirect()
            ->route('student.submission.index')
            ->with('success', 'แก้ไขการยื่นสอบเรียบร้อยแล้ว');
    }

    public function submissionView($id, $propose_id)
    {
        // ดึงข้อมูลผู้ใช้ที่ล็อกอินอยู่
        $stdLogin = Auth::guard('students')->user();

        // ดึงข้อมูลจากตาราง proposes โดยใช้ id ของผู้ใช้เป็น group_id
        $proposes = Propose::findOrFail($propose_id);
        // dd($proposes->id);

        // ดึงข้อมูลจากตาราง ProjectGroup โดยใช้ group_id ของ Propose
        $group = ProjectGroup::where('id', $proposes->group_id)->first();
        // dd($group);

        $members = GroupMember::with('student')->where('group_id', $group->id)->get();
        // dd($members);

        $examsubmission = ExamSubmission::findOrFail($id);

        // ส่งข้อมูลไปยัง View
        return view('student.submission.view', compact('examsubmission', 'members'));
    }

    public function submissionDownloadFile($id)
    {
        $filePath = ExamSubmission::findOrFail($id)->file_path;

        if (Storage::disk('public')->exists($filePath)) {
            return response()->download(storage_path('app/public/' . $filePath));
        }

        return abort(404, 'File not found');
    }

    public function scheduleShow($id)
    {
        $submission = ExamSubmission::findOrFail($id);

        // ส่งข้อมูลไปยัง view สำหรับแสดงผล
        return view('student.submission.schedule', compact('submission'));
    }

    public function historyExamSubIndex($examSubmissionId)
    {
        // ดึงข้อมูลการยื่นสอบ
        $examSubmission = ExamSubmission::findOrFail($examSubmissionId);

        // ดึงประวัติการเปลี่ยนแปลงจากตาราง exam_submission_histories
        $history = ExamSubmissionHistory::where('exam_submission_id', $examSubmissionId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('student.submission.history', compact('examSubmission', 'history'));
    }


    // Revisoin setting

    public function revisionIndex()
    {
        $student = Auth::guard('students')->user();

        // ดึง group member
        $groupMember = GroupMember::where('s_id', $student->id)->first();
        if (! $groupMember) {
            return redirect()->route('student.group.index')
                ->with('error', 'ยังไม่มีกลุ่มโครงงาน กรุณาสร้างกลุ่มโครงงาน');
        }

        // ดึงหัวข้อเสนอ
        $propose = Propose::where('group_id', $groupMember->group_id)->first();
        if (! $propose) {
            return redirect()->route('student.propose.index')
                ->with('error', 'กรุณายื่นหัวข้อก่อน');
        }

        // ดึงการส่งสอบ
        $submission = ExamSubmission::where('propose_id', $propose->id)->first();
        if (! $submission) {
            return redirect()->route('student.submission.index')
                ->with('error', 'กรุณายื่นสอบก่อน');
        }
        // dd( $submission->id);

        $total_invigilator = ExamInviMember::where('submission_id', $submission->id)->count();

        // ดึงรายการแก้ไข
        $revisions = Revision::where('submission_id', $submission->id)
            ->withCount([
                // สถานะรออนุมัติ (waiting) สมมติใน DB เก็บเป็น '1'
                'revision_approvals as approve_count' => function ($q) {
                    $q->where('status', 1);
                },
                // สถานะอนุมัติ สมมติเป็น '3'
                'revision_approvals as rejected_count' => function ($q) {
                    $q->where('status', 3);
                },
                // // สถานะไม่อนุมัติ สมมติเป็น '2'
                // 'revision_approvals as waiting_count' => function ($q) {
                //     $q->where('status', 2);
                // },
            ])
            ->paginate(10);

        return view('student.revision.index', compact(
            'revisions',
            'submission',
            'total_invigilator'
        ));
    }

    public function revisionCreate($id)
    {
        $submission = ExamSubmission::findOrFail($id);

        return view('student.revision.create', compact('submission'));
    }

    public function revisionStore(Request $request, ExamSubmission $submission)
    {
        $data = $request->validate([
            'file_path'    => 'required|file|mimes:pdf,doc,docx',
            'edit_detail'  => 'required|string|max:2000',
        ]);

        // dd($submission->id);
        $revision = Revision::create([
            'submission_id' => $submission->id,
            'file_path'     => '',
            'edit_detail'   => $data['edit_detail'],
            'status'        => '1',
        ]);

        // อัปโหลดไฟล์ไปยังตำแหน่งจริง
        $file = $request->file('file_path');
        $storedPath = $file->store("uploads/revisions/{$revision->id}", 'public');

        $revision->update([
            'file_path' => $storedPath
        ]);

        return redirect()
            ->route('student.revision.index', $submission->id)
            ->with('success', 'สร้างรายการแก้ไขเรียบร้อยแล้ว');
    }

    // แสดงฟอร์มแก้ไขขณะยังไม่ได้อนุมัติ
    public function editRevision($revisionId)
    {
        // ตรวจสอบว่ารายการ revision นี้เป็นของ student คนนั้นจริง ๆ
        // สมมติว่าโครงสร้างความสัมพันธ์คือ:
        // Revision → submission → propose → group_id → GroupMember (s_id)
        // $ownerId = $revision
        //     ->exam_submission
        //     ->propose
        //     ->project_group
        //     ->group_members;
        //     // ->s_id;

        // if ($ownerId->s_id !== $studentId) {
        //     abort(403, 'คุณไม่มีสิทธิ์เข้าใช้งานหน้านี้');
        // }

        $revision = Revision::findOrFail($revisionId);

        // รวบรวม list ของกรรมการพร้อมสถานะ
        $invigilators = $revision->exam_submission->exam_invi_members->map(function ($inv) use ($revision) {
            $advisor = $inv->invi_group_member->advisor;
            $approval = $revision->revision_approvals
                ->firstWhere('exam_invi_id', $inv->id);
            return [
                'name'   => $advisor->name,
                'status' => $approval->status ?? null,
                'role'   => $inv->role,
            ];
        });

        return view('student.revision.edit', compact('revision', 'invigilators'));
    }

    // บันทึกข้อมูลจากฟอร์มแก้ไข
    public function revisionUpdate(Request $request, Revision $revision)
    {
        $data = $request->validate([
            'file_path'   => 'nullable|file|mimes:pdf,doc,docx',
            'edit_detail' => 'required|string|max:2000',
        ]);

        // ถ้ามีไฟล์ใหม่ ให้ลบของเดิมและอัปโหลดเข้าโฟลเดอร์ตาม revision id
        if ($request->hasFile('file_path')) {
            Storage::disk('public')->delete($revision->file_path);
            $revision->file_path = $request->file('file_path')
                ->store("uploads/revisions/{$revision->id}", 'public');
        }

        $revision->edit_detail = $data['edit_detail'];
        // ถ้าต้องการ reset status กลับเป็นรออนุมัติ:
        // $revision->status = '1';
        $revision->save();

        // อัปเดต revision_approvals ทุกเรคคอร์ดให้เป็น 2 (รออนุมัติ)
        RevisionApproval::where('revision_id', $revision->id)
            ->where('status', '3')
            ->update(['status' => '2']);

        return redirect()
            ->route('student.revision.index')
            ->with('success', 'อัปเดตรายการแก้ไขเรียบร้อยแล้ว');
    }

    public function revisionDownloadFile($id)
    {
        $filePath = Revision::findOrFail($id)->file_path;

        if (Storage::disk('public')->exists($filePath)) {
            return response()->download(storage_path('app/public/' . $filePath));
        }

        return abort(404, 'File not found');
    }

    public function showRevision($revisionId)
    {
        $studentId = Auth::guard('students')->id();

        // Load revision with relationships
        $revision = Revision::findOrFail($revisionId);

        // // Authorization: ensure ownership
        // $ownerId = optional($revision->submission->propose->group->groupMember)->s_id;
        // if ($ownerId !== $studentId) {
        //     abort(403, 'คุณไม่มีสิทธิ์เข้าถึงข้อมูลนี้');
        // }

        // Build invigilator list
        $invigilators = $revision->exam_submission->exam_invi_members->map(function ($inv) use ($revision) {
            $advisor = $inv->invi_group_member->advisor;
            $approval = $revision->revision_approvals->firstWhere('exam_invi_id', $inv->id);
            return [
                'name'   => $advisor->name,
                'status' => $approval->status ?? null,
                'role'   => $inv->role,
            ];
        });

        return view('student.revision.show', compact('revision', 'invigilators'));
    }

    public function uploadIndex()
    {
        $student = auth('students')->user(); // ใช้ guard 'students'

        if (!$student) {
            abort(403, "ไม่พบข้อมูลนักศึกษาในระบบ");
        }

        $groupMember = GroupMember::where('s_id', $student->id)->first();
        if (! $groupMember) {
            return redirect()->route('student.group.index')
                ->with('error', 'ยังไม่มีกลุ่มโครงงาน กรุณาสร้างกลุ่มโครงงาน');
        }

        // ดึงหัวข้อเสนอ
        $proposes = Propose::where('group_id', $groupMember->group_id)->first();
        if (! $proposes) {
            return redirect()->route('student.propose.index')
                ->with('error', 'กรุณายื่นหัวข้อก่อน');
        }

        // ดึงการส่งสอบ
        $submission = ExamSubmission::where('propose_id', $proposes->id)->first();
        if (! $submission) {
            return redirect()->route('student.submission.index')
                ->with('error', 'กรุณายื่นสอบก่อน');
        }

        // revision ของการยื่นสอบ
        $revision = Revision::where('submission_id', $submission->id)->first();
        if (! $revision) {
            return redirect()->route('student.submission.index')
                ->with('error', 'ยังไม่พบข้อมูล Revision สำหรับการยื่นสอบนี้');
        }

        // ดึง uploads พร้อมความสัมพันธ์ที่ต้องใช้ในตาราง (กัน N+1)
        $uploads = Upload::with([
            'revision.exam_submission.propose.advisor',
            'file',
        ])
            ->where('revision_id', $revision->id)
            ->get();

        // flag สำหรับซ่อนปุ่ม
        $hasUpload = $uploads->isNotEmpty();

        return view('student.upload.index', compact('uploads', 'proposes', 'hasUpload'));
    }

    public function uploadCreate($proposeId)
    {
        $proposes = Propose::findOrFail($proposeId);

        return view('student.upload.create', compact('proposes'));
    }

    public function uploadStore(Request $request, $proposeId)
    {
        $propose = Propose::findOrFail($proposeId);
        $submission = ExamSubmission::where('propose_id', $propose->id)->firstOrFail();
        $revision = Revision::where('submission_id', $submission->id)->firstOrFail();

        $validated = $request->validate([
            'abstract'     => 'required|mimes:pdf|max:51200',
            'cover_file'   => 'required|image|mimes:jpg,jpeg,png|max:10240',
            'project_file' => 'required|mimes:pdf|max:102400',
            'keyword'      => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            /* =========================
         * 1. สร้าง upload
         * ========================= */
            $upload = Upload::create([
                'revision_id' => $revision->id,
                'keyword' => $validated['keyword'],
                'status' => '1',
            ]);

            /* =========================
         * 2. Upload files
         * ========================= */
            $dir = "uploads/upload/{$upload->id}";
            $ts = now()->format('Ymd');

            $abstractName = "Abstract_{$upload->id}_{$ts}.pdf";
            $projectName  = "Project_{$upload->id}_{$ts}.pdf";

            $coverExt = strtolower($request->file('cover_file')->extension());
            $coverName = "Cover_{$upload->id}_{$ts}.{$coverExt}";

            $abstractRel = $request->file('abstract')
                ->storeAs($dir, $abstractName, 'public');

            $projectRel = $request->file('project_file')
                ->storeAs($dir, $projectName, 'public');

            $coverRel = $request->file('cover_file')
                ->storeAs($dir, $coverName, 'public');

            /* =========================
         * 3. บันทึก upload_files (polymorphic)
         * ========================= */
            UploadFile::create([
                'fileable_id'   => $upload->id,
                'fileable_type' => Upload::class,
                'cover_file'    => $coverRel,
                'project_file'  => $projectRel,
                'abstract_file' => $abstractRel,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'บันทึกข้อมูลไฟล์โครงงานเรียบร้อยแล้ว!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            // ลบโฟลเดอร์ถ้าอัปโหลดไปแล้ว
            if (isset($upload)) {
                Storage::disk('public')->deleteDirectory("uploads/upload/{$upload->id}");
            }

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function preview(Upload $upload, string $type): BinaryFileResponse
    {
        $student = auth('students')->user();
        abort_unless($student, 403);

        $groupMember = GroupMember::where('s_id', $student->id)->first();
        abort_unless($groupMember, 403);

        $propose = $upload->revision?->exam_submission?->propose;
        abort_unless(
            $propose && (int) $propose->group_id === (int) $groupMember->group_id,
            403
        );

        if (! in_array($type, ['abstract', 'cover', 'project'], true)) {
            abort(404);
        }

        /* =========================
     * ใช้ polymorphic relation
     * ========================= */
        $file = $upload->file;
        abort_unless($file, 404);

        $attr = match ($type) {
            'abstract' => 'abstract_file',
            'cover'    => 'cover_file',
            'project'  => 'project_file',
        };

        $path = $file->{$attr};
        abort_if(! $path || ! Storage::disk('public')->exists($path), 404);

        $fullPath = Storage::disk('public')->path($path);
        $mime = Storage::disk('public')->mimeType($path)
            ?? ($type === 'cover' ? 'image/jpeg' : 'application/pdf');

        $response = response()->file($fullPath, [
            'Content-Type'    => $mime,
            'X-Frame-Options' => 'SAMEORIGIN',
            'Cache-Control'   => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma'          => 'no-cache',
            'Expires'         => '0',
        ]);

        $response->setLastModified(
            \DateTime::createFromFormat('U', (string) filemtime($fullPath))
        );
        $response->setEtag(md5_file($fullPath));

        return $response;
    }

    public function uploadShow(Upload $upload)
    {
        // นักศึกษาที่ล็อกอินอยู่ (guard: students)
        $student = auth('students')->user();
        abort_unless($student, 403);

        // กลุ่มของนักศึกษาคนนี้
        $groupMember = GroupMember::where('s_id', $student->id)->first();
        abort_unless($groupMember, 403);

        // โหลดความสัมพันธ์ที่ต้องใช้ + ไฟล์ล่าสุด
        $upload->load([
            'revision.exam_submission.propose',
            'file',
        ]);

        // ตรวจว่า upload นี้เป็นของกลุ่มนักศึกษาคนนี้จริง
        $propose = $upload->revision?->exam_submission?->propose;
        abort_unless($propose && (int)$propose->group_id === (int)$groupMember->group_id, 403);

        // ไฟล์ล่าสุดของ upload นี้
        $file = $upload->file;

        // URL รูปปก (ผ่าน preview route + กันแคช)
        $coverPreviewUrl = ($file && $file->cover_file)
            ? route('student.upload.preview', ['upload' => $upload->id, 'type' => 'cover']) . '?v=' . now()->timestamp
            : null;

        return view('student.upload.show', compact('upload', 'propose', 'file', 'coverPreviewUrl'));
    }

    public function uploadEdit(Upload $upload)
    {
        $student = auth('students')->user();
        abort_unless($student, 403);

        $groupMember = GroupMember::where('s_id', $student->id)->first();
        abort_unless($groupMember, 403);

        $propose = $upload->revision?->exam_submission?->propose;
        abort_unless(
            $propose && (int) $propose->group_id === (int) $groupMember->group_id,
            403
        );

        /* =========================
     * ใช้ polymorphic relation
     * ========================= */
        $file = $upload->file;

        $hasCover = (bool) optional($file)->cover_file;

        $coverPreviewUrl = $hasCover
            ? route('student.upload.preview', [
                'upload' => $upload->id,
                'type'   => 'cover'
            ]) . '?v=' . now()->timestamp
            : null;

        return view('student.upload.edit', [
            'upload'          => $upload,
            'file'            => $file,
            'propose'         => $propose,
            'coverPreviewUrl' => $coverPreviewUrl,
        ]);
    }

    public function uploadUpdate(Request $request, Upload $upload)
    {
        $student = auth('students')->user();
        abort_unless($student, 403);

        $groupMember = GroupMember::where('s_id', $student->id)->first();
        abort_unless($groupMember, 403);

        $propose = $upload->revision?->exam_submission?->propose;
        abort_unless(
            $propose && (int)$propose->group_id === (int)$groupMember->group_id,
            403
        );

        /* =========================
     * Validate
     * ========================= */
        $validated = $request->validate([
            'keyword'      => 'required|string|max:255',
            'abstract'     => 'nullable|mimes:pdf|max:51200',
            'cover_file'   => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            'project_file' => 'nullable|mimes:pdf|max:102400',
        ]);

        /* =========================
     * Update upload
     * ========================= */
        $upload->update([
            'keyword' => $validated['keyword'],
            'status'  => 1, // กลับไปรออนุมัติ
        ]);

        $hasAbstract = $request->hasFile('abstract');
        $hasCover    = $request->hasFile('cover_file');
        $hasProject  = $request->hasFile('project_file');

        if (!($hasAbstract || $hasCover || $hasProject)) {
            return response()->json([
                'success' => true,
                'message' => 'อัปเดตคำสำคัญเรียบร้อยแล้ว',
            ]);
        }

        /* =========================
     * File record (polymorphic)
     * ========================= */
        $file = $upload->file ?? $upload->file()->create([]);

        $dir      = "uploads/upload/{$upload->id}";
        $uploadId = $upload->id;

        /* =========================
     * Abstract (PDF)
     * ========================= */
        if ($hasAbstract) {
            if ($file->abstract_file) {
                Storage::disk('public')->delete($file->abstract_file);
            }

            $uuid = Str::uuid()->toString();
            $name = "abstract_{$uploadId}_{$uuid}.pdf";

            $file->abstract_file = $request->file('abstract')
                ->storeAs($dir, $name, 'public');
        }

        /* =========================
     * Cover (Image)
     * ========================= */
        if ($hasCover) {
            if ($file->cover_file) {
                Storage::disk('public')->delete($file->cover_file);
            }

            $uuid = Str::uuid()->toString();
            $ext  = $request->file('cover_file')->extension();
            $name = "cover_{$uploadId}_{$uuid}.{$ext}";

            $file->cover_file = $request->file('cover_file')
                ->storeAs($dir, $name, 'public');
        }

        /* =========================
     * Project (PDF)
     * ========================= */
        if ($hasProject) {
            if ($file->project_file) {
                Storage::disk('public')->delete($file->project_file);
            }

            $uuid = Str::uuid()->toString();
            $name = "project_{$uploadId}_{$uuid}.pdf";

            $file->project_file = $request->file('project_file')
                ->storeAs($dir, $name, 'public');
        }

        $file->save();

        return response()->json([
            'success' => true,
            'message' => 'อัปเดตข้อมูลโครงงานเรียบร้อยแล้ว!',
        ]);
    }
}
