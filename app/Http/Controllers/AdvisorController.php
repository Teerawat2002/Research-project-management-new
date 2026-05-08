<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Advisor;
use App\Models\Propose;
use App\Models\GroupMember;
use App\Models\ExamSubmission;
use App\Models\ExamSubmissionHistory;
use App\Models\ProjectType;
use App\Models\InvigilatorGroup;
use App\Models\InviGroupMember;
use App\Models\ProjectGroup;
use App\Models\ProposeHistory;
use App\Models\Student;
use App\Models\ExamInviMember;
use App\Models\Formset;
use App\Models\ExamGrade;
use App\Models\MainTopic;
use App\Models\SubTopic;
use App\Models\ExaminationGrade;
use App\Models\SubsubTopic;
use App\Models\Revision;
use App\Models\RevisionApproval;
use App\Models\Upload;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

use Illuminate\Support\Facades\Storage;


class AdvisorController extends Controller
{
    public function dashboard()
    {
        $advisor = Auth::guard('advisors')->user();

        return view('advisor.dashboard', compact('advisor'));
    }

    // Proposal setting
    public function proposeIndex(Request $request)
    {
        $user = Auth::user();
        $advisorId = $user->id;

        // เริ่มต้น Query
        $query = Propose::with('advisor')
            ->where('a_id', $advisorId)
            ->orderBy('created_at', 'desc');

        // 1. ถ้ามีการค้นหาจากชื่อ
        if ($request->has('search') && $request->search !== '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // 2. ถ้ามีการเลือก "สถานะ" จาก Dropdown
        $status = $request->input('status');

        // เช็คว่าค่าต้องไม่เป็น null และ ไม่เป็นค่าว่าง (วิธีนี้รองรับค่า '0' ด้วย)
        if ($status !== null && $status !== '') {
            $query->where('status', $status);
        }

        // 3. ดึงข้อมูลที่กรองแล้ว
        $proposals = $query->paginate(10)->withQueryString();

        // 4. เช็ค Active Proposal สำหรับการใช้งานอื่นๆ
        $hasActiveProposal = Propose::where('a_id', $advisorId)
            ->whereIn('status', [1, 2])
            ->exists();

        return view('advisor.propose.index', compact('proposals', 'hasActiveProposal'));
    }

    public function approveFormView($id)
    {
        // ดึงข้อมูล Proposal
        $proposal = Propose::with('advisor')->findOrFail($id);

        // ดึง group_id จาก proposal
        $groupId = $proposal->group_id;

        // ดึงสมาชิกในกลุ่มจาก group_members
        $groupMembers = GroupMember::with('student') // โหลดความสัมพันธ์ student
            ->where('group_id', $groupId)
            ->get();

        // ส่งข้อมูลไปยัง View
        return view('advisor.propose.approve', compact('proposal', 'groupMembers'));
    }

    public function approveView($id)
    {
        // ดึงข้อมูล Proposal
        $proposal = Propose::with('advisor')->findOrFail($id);

        // ดึง group_id จาก proposal
        $groupId = $proposal->group_id;

        // ดึงสมาชิกในกลุ่มจาก group_members
        $groupMembers = GroupMember::with('student') // โหลดความสัมพันธ์ student
            ->where('group_id', $groupId)
            ->get();

        // ส่งข้อมูลไปยัง View
        return view('advisor.propose.view', compact('proposal', 'groupMembers'));
    }

    public function approve(Request $request, $id)
    {
        $validated = $request->validate([
            'approval' => 'required|string|in:approved,rejected',
            'reason' => 'nullable|string|required_if:approval,rejected',
        ], [
            'approval.required' => 'กรุณาเลือกการอนุมัติ',
            'reason.required' => 'กรุณากรอกเหตุผล',
        ]);

        $proposal = Propose::findOrFail($id);

        // อัปเดตสถานะของ proposal
        $proposal->status = $validated['approval'] === 'approved' ? 0 : 2; // 0 = Approved, 2 = Rejected
        $proposal->comments = $validated['approval'] === 'approved' ? null : $validated['reason']; // ล้าง comment ถ้า approved
        $proposal->save();

        // ถ้าอนุมัติ
        if ($validated['approval'] === 'approved') {

            // อัปเดตสถานะใน project_groups
            ProjectGroup::where('id', $proposal->group_id)->update(['status' => 2]); // เปลี่ยนเป็น proposed

            // บันทึกลง propose_histories ว่า proposal ผ่านการอนุมัติ
            ProposeHistory::create([
                'propose_id' => $proposal->id,
                'status' => 'approved',
                'comments' => $validated['reason'],
                'created_at' => now(),
            ]);
        }

        // ถ้าถูกปฏิเสธ
        if ($validated['approval'] === 'rejected') {
            ProposeHistory::create([
                'propose_id' => $proposal->id,
                'status' => 'rejected',
                'comments' => $validated['reason'],
                'created_at' => now(),
            ]);
        }

        return redirect()->route('advisor.propose.index')->with('success', 'Proposal updated successfully!');
    }

    public function historyProposeIndex($id)
    {
        $proposal = Propose::findOrFail($id);
        $advisor = Auth::user(); // ดึงข้อมูลอาจารย์ที่ล็อกอิน

        // ตรวจสอบว่า user ที่ล็อกอินเป็นที่ปรึกษาของ proposal นี้หรือไม่
        if ($advisor->id !== $proposal->a_id) {
            return redirect()->route('advisor.propose.index')->with('error', 'You do not have access to this proposal history.');
        }

        // ดึงข้อมูลประวัติของ proposal นี้
        $history = ProposeHistory::where('propose_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('advisor.propose.history', compact('history', 'proposal'));
    }


    // Submission setting
    public function submissionIndex(Request $request)
    {
        // ดึง advisor ที่ล็อกอิน
        $advisor = Auth::guard('advisors')->user();

        // สร้าง query บน Propose
        $proposeQuery = Propose::where('a_id', $advisor->id);

        // กรองถ้ามี search
        if ($search = $request->input('search')) {
            $proposeQuery->where('title', 'like', "%{$search}%");
        }

        // เอาแค่ id ของ Propose ที่ผ่านเงื่อนไข
        $proposeIds = $proposeQuery->pluck('id');

        // เตรียม fetch ExamSubmission พร้อม relationship
        $examSubmissions = ExamSubmission::whereIn('propose_id', $proposeIds)
            ->orderBy('updated_at', 'desc')
            ->paginate(10)
            ->withQueryString();  // เก็บ search/page ใน URL ด้วย

        // ส่งไป View (ชื่อตัวแปรต้องตรงกันกับ Blade)
        return view('advisor.submission.index', compact('examSubmissions'));
    }

    public function submission($id)
    {
        // โหลดข้อเสนอ (Model เดียว)
        $propose = Propose::findOrFail($id);

        // โหลดกลุ่มและสมาชิก
        $group   = ProjectGroup::findOrFail($propose->group_id);
        $members = GroupMember::with('student')
            ->where('group_id', $group->id)
            ->get();

        // โหลดการส่งสอบครั้งแรกของข้อเสนอนี้ (Model เดียว)
        $submission = ExamSubmission::where('propose_id', $propose->id)
            ->firstOrFail();

        // ส่งไป View
        return view(
            'advisor.submission.submission',
            compact('propose', 'members', 'submission')
        );
    }

    // Submission file
    public function downloadFile($id)
    {
        $filePath = ExamSubmission::findOrFail($id)->file_path;

        if (Storage::disk('public')->exists($filePath)) {
            return response()->download(storage_path('app/public/' . $filePath));
        }

        return abort(404, 'File not found');
    }

    public function submissionSave(Request $request, $id)
    {
        // ตรวจสอบข้อมูล
        $validated = $request->validate([
            'approval' => 'required|string|in:approved,rejected',
            'reason' => 'nullable|string|required_if:approval,rejected',
        ], [
            'approval.required' => 'กรุณาเลือกการอนุมัติ',
            'reason.required' => 'กรุณากรอกเหตุผล',
        ]);

        // ดึงข้อมูล Exam Submission ที่เกี่ยวข้อง
        $examsubmission = ExamSubmission::findOrFail($id);

        // ดึง Proposal ที่เกี่ยวข้อง
        $propose = Propose::findOrFail($examsubmission->propose_id);

        // อัปเดตสถานะของ Exam Submission
        $examsubmission->status = $validated['approval'] === 'approved' ? 3 : 2; // 0 = Approved, 3 = Rejected
        $examsubmission->comments = $validated['approval'] === 'approved' ? null : $validated['reason']; // ถ้าอนุมัติ comments จะเป็น null
        $examsubmission->save();

        // บันทึกประวัติการเปลี่ยนแปลง
        ExamSubmissionHistory::create([
            'exam_submission_id' => $examsubmission->id,
            'status' => $validated['approval'],
            'comments' => $validated['approval'] === 'approved' ? null : $validated['reason'],
        ]);

        // กรณีที่ **อนุมัติ** (approved)
        if ($validated['approval'] === 'approved') {
            // อัปเดตสถานะใน project_groups
            ProjectGroup::where('id', $propose->group_id)->update(['status' => 3]); // เปลี่ยนเป็น proposed

        }

        // ส่งกลับไปยังหน้าแรก
        return redirect()->route('advisor.submission.index')->with('success', 'File saved successfully!');
    }


    public function historyExamSubIndex($examSubmissionId)
    {
        // ดึงข้อมูลการยื่นสอบ
        $examSubmission = ExamSubmission::findOrFail($examSubmissionId);

        // ดึงประวัติการเปลี่ยนแปลงจากตาราง exam_submission_histories
        $history = ExamSubmissionHistory::where('exam_submission_id', $examSubmissionId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('advisor.submission.history', compact('examSubmission', 'history'));
    }

    public function examsubmissionView($id)
    {
        // ดึงข้อมูลการส่งสอบตาม ID ที่ได้รับ
        $examsubmission = ExamSubmission::findOrFail($id);

        // ดึงข้อมูลที่เกี่ยวข้องกับ Proposal และ Members
        $proposes = Propose::where('id', $examsubmission->propose_id)->first();

        // ดึงข้อมูลจากตาราง ProjectGroup โดยใช้ group_id ของ Propose
        $group = ProjectGroup::where('id', $proposes->group_id)->first();

        // ดึงสมาชิกในกลุ่มจาก GroupMember
        $members = GroupMember::with('student')->where('group_id', $group->id)->get();

        // ส่งข้อมูลไปยังหน้า Blade
        return view('advisor.submission.view', compact('examsubmission', 'proposes', 'members'));
    }

    public function schedule($id)
    {
        $advisorId = Auth::guard('advisors')->id();
        $submission = ExamSubmission::with('propose.project_group')->findOrFail($id);

        // 1) หาปี (ac_id) ของกลุ่มโครงงานจาก submission
        $projectGroup = $submission->propose->project_group;
        $yearId = $projectGroup->ac_id;

        // 2) ดึงเฉพาะ InvigilatorGroup ที่ปีตรงกับปีโครงงาน และ advisor นี้เป็นสมาชิก
        $invigilatorGroups = InvigilatorGroup::where('ac_id', $yearId)
            ->whereHas('invi_group_members', function ($q) use ($advisorId) {
                $q->where('a_id', $advisorId);
            })
            ->with(['invi_group_members.advisor'])
            ->get();

        return view('advisor.schedule.create', compact(
            'submission',
            'invigilatorGroups',
            'advisorId'
        ));
    }

    public function scheduleSave(Request $request, $id)
    {
        $advisorId = Auth::guard('advisors')->id();

        // validate ทั้งห้อง/วัน/เวลา และ รายการกรรมการ
        $data = $request->validate([
            'e_room'       => 'required|string|max:50',
            'e_date'       => 'required|date',
            'e_time'       => 'required',
            'invigilator_group_id' => 'required|exists:invigilator_group,id',
            'invi_member_id'  => 'required|array|min:1',
            'invi_member_id.*' => 'exists:invi_group_member,id',
        ], [
            'e_room.required'       => 'กรุณาใส่ชื่อสถานที่สอบ',
            'e_date.required'       => 'กรุณาเลือกวันที่สอบ',
            'e_time.required'       => 'กรุณาเลือกเวลาสอบ',
            'invigilator_group_id.required' => 'กรุณาเลือกกลุ่มกรรมการคุมสอบ',
            'invi_member_id.required'  => 'กรุณาเลือกกรรมการ',
        ]);

        // อัพเดตตารางสอบ
        $submission = ExamSubmission::findOrFail($id);
        $submission->e_room = $data['e_room'];
        $submission->e_date = $data['e_date'];
        $submission->e_time = $data['e_time'];
        $submission->e_invi_group_id = $data['invigilator_group_id'];
        $submission->status = 4;
        $submission->save();

        // ลบข้อมูลเดิม แล้วบันทึกใหม่ลง exam_invi_members
        // ExamInviMember::where('submission_id', $submission->id)->delete();

        // บันทึกตัวเอง (role = 1)
        $own = InviGroupMember::where('invi_group_id', $data['invigilator_group_id'])
            ->where('a_id', $advisorId)
            ->first();
        if ($own) {
            ExamInviMember::create([
                'submission_id'   => $submission->id,
                'invi_member_id'  => $own->id,
                'role'            => 1,
            ]);
        }

        foreach ($data['invi_member_id'] as $inviId) {
            ExamInviMember::create([
                'submission_id' => $submission->id,
                'invi_member_id' => $inviId,
                'role' => 2,
            ]);
        }

        return redirect()
            ->route('advisor.submission.index')
            ->with('success', 'จัดตารางสอบและกำหนดกรรมการเรียบร้อยแล้ว');
    }

    public function showSchedule($id)
    {
        // ดึงข้อมูล ExamSubmission พร้อมความสัมพันธ์ที่จำเป็น
        $submission = ExamSubmission::findOrFail($id);

        return view('advisor.schedule.show', compact('submission'));
    }

    public function scheduleEdit($id)
    {
        $advisorId        = Auth::guard('advisors')->id();
        $submission       = ExamSubmission::findOrFail($id);
        // 1) หาปี (ac_id) ของกลุ่มโครงงานจาก submission
        $projectGroup = $submission->propose->project_group;
        $yearId = $projectGroup->ac_id;

        // 2) ดึงเฉพาะ InvigilatorGroup ที่ปีตรงกับปีโครงงาน และ advisor นี้เป็นสมาชิก
        $invigilatorGroups = InvigilatorGroup::where('ac_id', $yearId)
            ->whereHas('invi_group_members', function ($q) use ($advisorId) {
                $q->where('a_id', $advisorId);
            })
            ->with(['invi_group_members.advisor'])
            ->get();

        return view('advisor.schedule.edit', compact('submission', 'invigilatorGroups', 'advisorId'));
    }

    public function scheduleUpdate(Request $request, $id)
    {
        $advisorId = Auth::guard('advisors')->id();

        $data = $request->validate(
            [
                'e_room'                => 'required|string|max:50',
                'e_date'                => 'required|date',
                'e_time'                => 'required',
                'invigilator_group_id'  => 'required|exists:invigilator_group,id',
                'invi_member_id'        => 'required|array|min:1',
                'invi_member_id.*'      => 'exists:invi_group_member,id',
            ],
            [
                'e_room.required'       => 'กรุณาใส่ชื่อสถานที่สอบ',
                'e_date.required'       => 'กรุณาเลือกวันที่สอบ',
                'e_time.required'       => 'กรุณาเลือกเวลาสอบ',
                'invigilator_group_id.required' => 'กรุณาเลือกกลุ่มกรรมการคุมสอบ',
                'invi_member_id.required'  => 'กรุณาเลือกกรรมการ',
            ]
        );

        $submission = ExamSubmission::findOrFail($id);
        $submission->e_room             = $data['e_room'];
        $submission->e_date             = $data['e_date'];
        $submission->e_time             = $data['e_time'];
        $submission->e_invi_group_id    = $data['invigilator_group_id'];
        $submission->save();

        // หา record invi_group_member ของ advisor ปัจจุบันในกลุ่มที่เลือก
        $ownMember = InviGroupMember::where('invi_group_id', $data['invigilator_group_id'])
            ->where('a_id', $advisorId)
            ->first();

        // ลบเฉพาะคนอื่น (ไม่ลบ record ของตัวเอง)
        $query = ExamInviMember::where('submission_id', $id);
        if ($ownMember) {
            $query->where('invi_member_id', '!=', $ownMember->id);
        }
        $query->delete();

        foreach ($data['invi_member_id'] as $inviId) {
            ExamInviMember::create([
                'submission_id'    => $id,
                'invi_member_id'   => $inviId,
                'role' => 2,
            ]);
        }

        return redirect()
            ->route('advisor.submission.index')
            ->with('success', 'อัปเดตตารางสอบเรียบร้อยแล้ว');
    }

    // Example controller function
    public function scoreForm($id)
    {
        // ดึงข้อมูลการสอบตาม id
        $examsubmission = ExamSubmission::findOrFail($id);

        // ดึงข้อมูล exam type ตาม typeId
        $projectType = ProjectType::where('id', $examsubmission->propose->project_type->id)->first();
        // dd($projectType);

        // ดึงข้อมูล formsets ตาม exam_type_id
        $formsets = Formset::where('project_type_id', $projectType->id)->get();
        // dd($formsets);

        // ดึงข้อมูลหัวข้อหลัก (main topics) ตาม form_id
        $mainTopics = MainTopic::where('form_id', $formsets->pluck('id'))->get();

        // ดึงข้อมูลหัวข้อย่อย (sub topics) ที่เกี่ยวข้องกับ main_topics
        $subTopics = SubTopic::whereIn('mtopic_id', $mainTopics->pluck('id'))->get();

        // ดึงข้อมูลหัวข้อย่อยยิ่ง (subsub topics) ที่เกี่ยวข้องกับ sub_topics
        $subSubTopics = SubSubTopic::whereIn('stopic_id', $subTopics->pluck('id'))->get();

        // ดึงข้อมูลกลุ่มสมาชิก (group_members) ที่เกี่ยวข้องกับ group_id จาก exam_submission
        $groupMembers = GroupMember::where('group_id', $examsubmission->propose->group_id)->get();

        // ส่งข้อมูลไปยัง view
        return view('advisor.score.score', compact('examsubmission', 'projectType', 'formsets', 'mainTopics', 'subTopics', 'subSubTopics', 'groupMembers'));
    }

    public function saveScore(Request $request, $submissionId)
    {
        $advisorId = Auth::guard('advisors')->id();

        // ดึง submission และ invigilator record ของอาจารย์ปัจจุบัน
        $submission = ExamSubmission::findOrFail($submissionId);
        $inv = ExamInviMember::where('submission_id', $submissionId)
            ->whereHas('invi_group_member', function ($q) use ($advisorId) {
                $q->where('a_id', $advisorId);
            })
            ->firstOrFail();
        $examInviId = $inv->id;

        // //  คำนวณคะแนนเต็มรวม (maxTotal)
        // $ptypeId    = $submission->propose->project_type->id;
        // $formsetIds = Formset::where('project_type_id', $ptypeId)->pluck('id');

        // $mainTotal   = MainTopic::whereIn('form_id', $formsetIds)->sum('score');
        // $subTotal    = SubTopic::whereIn(
        //     'mtopic_id',
        //     MainTopic::whereIn('form_id', $formsetIds)->pluck('id')
        // )->sum('score');
        // $subsubTotal = SubSubTopic::whereIn(
        //     'stopic_id',
        //     SubTopic::whereIn(
        //         'mtopic_id',
        //         MainTopic::whereIn('form_id', $formsetIds)->pluck('id')
        //     )->pluck('id')
        // )->sum('score');

        // $maxTotal = $mainTotal + $subTotal + $subsubTotal;

        //  รวบรวมคะแนนที่ส่งมาจากฟอร์ม
        //    รูปแบบ $request->input('scores') คือ nested array ตามชื่อ input ใน Blade
        $raw = $request->input('scores', []);
        $studentTotals = [];
        foreach ($raw as $type => $topics) {
            foreach ($topics as $topicId => $members) {
                foreach ($members as $memberId => $val) {
                    $studentTotals[$memberId] = ($studentTotals[$memberId] ?? 0) + (int)$val;
                }
            }
        }

        // // 4. ลบเกรดเก่าของ submission นี้ ที่อาจารย์คนนี้เคยบันทึก
        // ExamGrade::where('submission_id', $submissionId)
        //     ->where('exam_invi_id', $examInviId)
        //     ->delete();

        // คำนวณ % และแปลงเป็น letter grade ตามเกณฑ์ แล้วสร้าง record ใหม่
        foreach ($studentTotals as $memberId => $total) {
            // $pct   = $maxTotal > 0 ? ($total / $maxTotal * 100) : 0;
            $grade = $this->calculateLetterGrade($total);

            ExamGrade::create([
                'submission_id' => $submissionId,
                'member_id'     => $memberId,
                'exam_invi_id'  => $examInviId,
                'grade'         => $grade,
            ]);
        }

        // เช็คว่ากรรมการทั้งหมดได้บันทึกคะแนนแล้วหรือยัง
        $totalInvigilators = ExamInviMember::where('submission_id', $submissionId)->count();
        $submittedInvigilators = ExamGrade::where('submission_id', $submissionId)
            ->distinct('exam_invi_id')
            ->count('exam_invi_id');

        if ($submittedInvigilators >= $totalInvigilators) {
            // ถ้าครบ ให้เปลี่ยนสถานะของ submission เป็น 0
            $submission->status = 0;
            $submission->save();

            // อัปเดตสถานะของ project_groups จาก group_id ใน propose
            $groupId = optional($submission->propose)->group_id;
            if ($groupId) {
                ProjectGroup::where('id', $groupId)->update(['status' => 4]);
            }
        }

        return redirect()
            ->route('advisor.submission.index')
            ->with('success', 'บันทึกเกรดเรียบร้อยแล้ว');
    }

    /**
     * แปลงเปอร์เซ็นต์เป็นเกรดตัวอักษร
     * A   = 80–100
     * B+  = 75–79
     * B   = 70–74
     * C+  = 65–69
     * C   = 60–64
     * D+  = 55–59
     * D   = 50–54
     * E   =   0–49
     */
    private function calculateLetterGrade(float $percent): string
    {
        if ($percent >= 80) return 'A';
        if ($percent >= 75) return 'B+';
        if ($percent >= 70) return 'B';
        if ($percent >= 65) return 'C+';
        if ($percent >= 60) return 'C';
        if ($percent >= 55) return 'D+';
        if ($percent >= 50) return 'D';
        return 'E';
    }

    public function gradeView($submissionId)
    {
        // $advisorId = Auth::guard('advisors')->id();

        // ดึงเกรดที่กรรมการนี้บันทึกไว้
        $grades = ExamGrade::where('submission_id', $submissionId)
            // ->where('exam_invi_id', $invi->id)
            ->get();

        // พร้อมส่งข้อมูล submission และ grades ไป View
        $submission = ExamSubmission::findOrFail($submissionId);

        return view('advisor.score.view', compact('submission', 'grades'));
    }

    public function auccForm($id)
    {
        $submission = ExamSubmission::findOrFail($id);

        // ดึง group_id จาก proposal
        $groupId = $submission->propose->group_id;

        // ดึงสมาชิกในกลุ่มจาก group_members
        $groupMembers = GroupMember::with('student') // โหลดความสัมพันธ์ student
            ->where('group_id', $groupId)
            ->get();

        return view('advisor.submission.auccForm', compact('submission', 'groupMembers'));
    }

    public function saveAuccScore(Request $request, $id)
    {
        $data = $request->validate([
            'advisor_score' => 'required|array',
            'advisor_score.*' => 'required|string',
        ]);

        // 1. หา exam_submission
        $submission = ExamSubmission::find($id);
        // dd($submission->propose);

        if (!$submission) {
            return redirect()
                ->route('advisor.submission.auccForm', $id)
                ->with('error', 'ไม่พบข้อมูลการสอบ');
        }

        // 2. เช็ค propose
        if (!$submission->propose) {
            return redirect()
                ->route('advisor.submission.auccForm', $id)
                ->with('error', 'ไม่พบข้อมูลโครงงานที่เกี่ยวข้อง');
        }

        // 3. หาอาจารย์จาก invi_group_member
        $inviGroupMember = DB::table('invi_group_member')
            ->where('a_id', $submission->propose->a_id)
            ->first();

        if (!$inviGroupMember) {
            return redirect()
                ->route('advisor.submission.auccForm', $id)
                ->with('error', 'ไม่พบข้อมูลอาจารย์ใน invi_group_member');
        }

        // เริ่ม transaction หลังจากเช็คข้อมูลครบแล้ว
        DB::beginTransaction();

        // 4. สร้าง record ใน exam_invi_member
        $examInviMemberId = DB::table('exam_invi_member')->insertGetId([
            'submission_id' => $submission->id,
            'invi_member_id'     => $inviGroupMember->id,
            'role'               => 1,
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        if (!$examInviMemberId) {
            DB::rollBack();

            return redirect()
                ->route('advisor.submission.auccForm', $id)
                ->with('error', 'ไม่สามารถสร้างข้อมูล exam_invi_member ได้');
        }

        // 5. บันทึกเกรดรายคน
        foreach ($data['advisor_score'] as $memberId => $score) {

            $insert = DB::table('exam_grade')->insert([
                'submission_id' => $submission->id,
                'exam_invi_id'  => $examInviMemberId,
                'member_id'     => $memberId,
                'grade'         => $score,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            if (!$insert) {
                DB::rollBack();

                return redirect()
                    ->route('advisor.submission.auccForm', $id)
                    ->with('error', 'ไม่สามารถบันทึกคะแนนของนักศึกษาได้');
            }
        }

        // 6. เปลี่ยนสถานะ submission
        $update = $submission->update([
            'status' => 0
        ]);

        if (!$update) {
            DB::rollBack();

            return redirect()
                ->route('advisor.submission.auccForm', $id)
                ->with('error', 'ไม่สามารถอัปเดตสถานะการสอบได้');
        }

        DB::commit();

        return redirect()
            ->route('advisor.submission.index')
            ->with('success', 'บันทึกเกรดรายบุคคลเรียบร้อยแล้ว');
    }

    /* Revision Management */
    public function revisionIndex(Request $request)
    {
        $advisor = Auth::guard('advisors')->user();

        // 1) หาทุก invi_group_member_id ของอาจารย์
        $inviGroupIds = InviGroupMember::where('a_id', $advisor->id)->pluck('id');

        $examInvi = ExamInviMember::whereIn('invi_member_id', $inviGroupIds)
            ->firstOrFail();
        // dd($examInvi);

        // 2) หา submission_id ที่อาจารย์นี้เป็น invigilator
        $submissionIds = ExamInviMember::whereIn('invi_member_id', $inviGroupIds)
            ->where('role', 1)
            ->pluck('submission_id');
        // dd($submissionIds);

        $search = trim((string) $request->input('search', ''));

        $query = Revision::whereIn('submission_id', $submissionIds)
            ->whereHas('exam_submission', fn($q) => $q->where('status', 0));

        if ($search !== '') {
            $query->whereHas(
                'exam_submission.propose',
                fn($q) =>
                $q->where('title', 'like', "%{$search}%")
            );
        }

        $revisions = $query
            ->with([
                // ดึง myApproval ของ “อาจารย์คนนี้” เท่านั้น
                'myApproval' => fn($q) => $q
                    ->whereHas(
                        'exam_invi_member',
                        fn($qq) =>
                        $qq->whereIn('invi_member_id', $inviGroupIds)
                            ->where('role', 1)
                    )
                    ->latest('id') // กันกรณีมีหลายแถว เผื่อแก้ไขซ้ำ
                    ->limit(1),
                // เผื่อใช้ชื่อโครงงานใน view
                'exam_submission.propose:id,title',
            ])
            ->orderByDesc('updated_at')
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('advisor.revision.index', compact('revisions', 'search'));
    }

    public function revisionApprove($id)
    {
        $revisions = Revision::findOrFail($id);

        // รวบรวม list ของกรรมการพร้อมสถานะ
        $invigilators = $revisions->exam_submission->exam_invi_members->map(function ($inv) use ($revisions) {
            $advisor = $inv->invi_group_member->advisor;
            $approval = $revisions->revision_approvals
                ->firstWhere('exam_invi_id', $inv->id);
            return [
                'name'   => $advisor->name,
                'status' => $approval->status ?? null,
                'role'   => $inv->role,
            ];
        });

        return view('advisor.revision.approve', compact('revisions', 'invigilators'));
    }

    public function revisionUpdate(Request $request, Revision $revision)
    {
        // 1) Validate — รับแค่ status
        $data = $request->validate([
            'status' => 'required|in:3,1',
        ]);

        // 2) หาตัว invigilator member ของอาจารย์คนนี้
        $advisorId   = Auth::guard('advisors')->id();
        $inviGroupIds = InviGroupMember::where('a_id', $advisorId)
            ->pluck('id');
        $examInvi    = ExamInviMember::whereIn('invi_member_id', $inviGroupIds)
            ->where('submission_id', $revision->submission_id)
            ->firstOrFail();
        // dd($examInvi);

        // 3) บันทึกลง revision_approvals
        RevisionApproval::updateOrCreate(
            [
                'revision_id'  => $revision->id,
                'exam_invi_id' => $examInvi->id,
            ],
            [
                'status' => $data['status'],
            ]
        );

        // 4) ถ้าเป็นอาจารย์ที่ปรึกษา (role = 1) ให้ตั้งค่า revision.status
        if ($examInvi->role === "1") {
            if ($data['status'] == 1) {
                // อาจารย์ที่ปรึกษาอนุมัติ → ตั้ง revision.status = 0
                $revision->status = 0;

                // อัปเดต project_groups.status = 5
                $submission = ExamSubmission::with('propose')
                    ->find($revision->submission_id);

                $groupId = optional($submission->propose)->group_id;
                if ($groupId) {
                    ProjectGroup::where('id', $groupId)->update(['status' => 5]);
                }
            } else {
                // อาจารย์ที่ปรึกษาไม่อนุมัติ → ตั้ง revision.status = 3
                $revision->status = 4;
            }
            $revision->save();
        }

        return redirect()
            ->route('advisor.revision.index')
            ->with('success', 'บันทึกผลการตรวจสอบเรียบร้อยแล้ว');
    }

    public function showRevision($revisionId)
    {
        // $studentId = Auth::guard('students')->id();

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

        return view('advisor.revision.show', compact('revision', 'invigilators'));
    }


    // Upload Management
    public function uploadIndex(Request $request)
    {
        $advisor = auth('advisors')->user();
        abort_unless($advisor, 403);

        $search = $request->input('search');

        $uploads = Upload::with([
            'revision.exam_submission.propose.advisor',
            'file',
        ])
            ->whereHas('revision.exam_submission.propose', function ($q) use ($advisor, $search) {
                $q->where('a_id', $advisor->id);

                if ($search) {
                    $q->where('title', 'LIKE', "%{$search}%");
                }
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();   // pagination จำค่า search

        return view('advisor.upload.index', compact('uploads', 'search'));
    }

    public function uploadApprove(Upload $uploadId)
    {
        $advisor = auth('advisors')->user();
        abort_unless($advisor, 403);

        $propose = $uploadId->revision?->exam_submission?->propose;
        abort_unless($propose && (int)$propose->a_id === (int)$advisor->id, 403);

        $file = $uploadId->file;

        // สร้าง URL พรีวิวรูปปก (ผ่าน route preview เพื่อคุมสิทธิ์ + กันแคช)
        $coverPreviewUrl = ($file && $file->cover_file)
            ? route('advisor.upload.preview', ['upload' => $uploadId->id, 'type' => 'cover']) . '?v=' . now()->timestamp
            : null;

        return view('advisor.upload.approve', [
            'upload'           => $uploadId,
            'file'             => $file,
            'propose'          => $propose,
            'coverPreviewUrl'  => $coverPreviewUrl,
        ]);
    }

    public function uploadUpdate(Request $request, Upload $uploadId)
    {
        $advisor = auth('advisors')->user();
        abort_unless($advisor, 403);

        $propose = $uploadId->revision?->exam_submission?->propose;
        abort_unless($propose && (int)$propose->a_id === (int)$advisor->id, 403);

        $data = $request->validate([
            'approval' => 'required|in:approved,rejected',
            'comment'  => 'nullable|string|max:2000',
        ]);

        // ถ้าปฏิเสธ บังคับให้มีเหตุผล (ถ้าต้องการ)
        if ($data['approval'] === 'rejected' && blank($data['comment'])) {
            return response()->json([
                'success' => false,
                'message' => 'กรุณาระบุเหตุผลเมื่อปฏิเสธ',
                'errors'  => ['comment' => ['กรุณาระบุเหตุผลเมื่อปฏิเสธ']],
            ], 422);
        }

        // mapping สถานะ: 0=อนุมัติ, 1=รอ, 2=ปฏิเสธ
        $status = $data['approval'] === 'approved' ? 0 : 2;

        // ถ้าอนุมัติ -> ลบ comment ออก (เป็น null)
        $comment = $status === 0
            ? null // ถ้าคอลัมน์ไม่ nullable เปลี่ยนเป็น '' ได้
            : ($data['comment'] ?? $uploadId->comment);

        $uploadId->update([
            'status'  => $status,
            'comment' => $comment,
        ]);

        if ($status === 0 && $propose?->group_id) {

            // 1. อัปเดตสถานะ group
            ProjectGroup::where('id', $propose->group_id)
                ->update(['status' => 0]);

            // 2. ดึง student_id ทั้งหมดในกลุ่ม
            $studentIds = DB::table('group_members')
                ->where('group_id', $propose->group_id)
                ->pluck('student_id');

            // 3. อัปเดตสถานะนักศึกษา
            Student::whereIn('id', $studentIds)
                ->update(['status' => 'graduated']);
        }

        return response()->json([
            'success' => true,
            'message' => $status === 0 ? 'อนุมัติเรียบร้อย' : 'ปฏิเสธเรียบร้อย',
        ]);
    }

    public function uploadPreview(Upload $upload, string $type): BinaryFileResponse
    {
        $advisor = auth('advisors')->user();
        abort_unless($advisor, 403);

        $propose = $upload->revision?->exam_submission?->propose;
        abort_unless($propose && (int)$propose->a_id === (int)$advisor->id, 403);

        if (! in_array($type, ['abstract', 'cover', 'project'], true)) abort(404);

        $file = $upload->file;
        abort_unless($file, 404);

        $attr = ['abstract' => 'abstract_file', 'cover' => 'cover_file', 'project' => 'project_file'][$type];
        $path = $file->{$attr};
        abort_if(!$path || !Storage::disk('public')->exists($path), 404);

        $fullPath = Storage::disk('public')->path($path);
        $mime = File::mimeType($fullPath) ?: 'application/pdf';

        $response = response()->file($fullPath, [
            'Content-Type'    => $mime,
            'X-Frame-Options' => 'SAMEORIGIN',
            'Cache-Control'   => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma'          => 'no-cache',
            'Expires'         => '0',
        ]);

        $response->setLastModified(\DateTime::createFromFormat('U', (string) filemtime($fullPath)));
        $response->setEtag(md5_file($fullPath));

        return $response;
    }

    public function uploadShow(Upload $upload)
    {
        $advisor = auth('advisors')->user();
        abort_unless($advisor, 403);

        $upload->load([
            'revision.exam_submission.propose.advisor',
            'file',
        ]);

        $propose = $upload->revision?->exam_submission?->propose;
        abort_unless($propose && (int) $propose->a_id === (int) $advisor->id, 403);

        $file = $upload->file;

        // URL รูปปก (ผ่าน route preview เพื่อคุมสิทธิ์)
        $coverPreviewUrl = ($file && $file->cover_file)
            ? route('advisor.upload.preview', ['upload' => $upload->id, 'type' => 'cover']) . '?v=' . now()->timestamp
            : null;

        return view('advisor.upload.show', [
            'upload'          => $upload,
            'propose'         => $propose,
            'file'            => $file,
            'coverPreviewUrl' => $coverPreviewUrl,
        ]);
    }
}
