<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Advisor;
use App\Models\Revision;
use App\Models\RevisionApproval;
use App\Models\ExamInviMember;
use App\Models\Propose;
use App\Models\GroupMember;
use App\Models\ExamSubmission;
use App\Models\ExamType;
use App\Models\InvigilatorGroup;
use App\Models\InviGroupMember;
use App\Models\ProjectGroup;
use App\Models\Student;
use App\Models\Formset;
use App\Models\MainTopic;
use App\Models\SubTopic;
use App\Models\SubsubTopic;
use App\Models\ProjectType;
use App\Models\ExamGrade;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\FuncCall;

class InvigilatorController extends Controller
{
    // Examination

    public function examinationIndex(Request $request)
    {
        $advisor = Auth::guard('advisors')->user();

        // 1) all pivot-table IDs for this advisor
        $invigilatorMemberIds = InviGroupMember::where('a_id', $advisor->id)->pluck('id');

        // 2) all submission IDs that have an ExamInviMember for any of those pivots
        $submissionIds = ExamInviMember::whereIn('invi_member_id', $invigilatorMemberIds)->pluck('submission_id');

        // รับค่าการค้นหาและสถานะจาก Request
        $search = $request->input('search');
        $status = $request->input('status');

        $query = ExamSubmission::whereIn('id', $submissionIds);

        // 3) ค้นหาตามชื่อโครงงาน
        if ($search) {
            $query->whereHas('propose', function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%");
            });
        }

        // 4) กรองตามสถานะ
        if ($status !== null && $status !== '') {
            $query->where('status', $status);
        } else {
            // หากไม่ได้เลือกสถานะ ให้แสดงเฉพาะสถานะ รอสอบ (4), หรือสอบเสร็จ (0) ตามที่เคยระบุไว้
            $query->whereIn('status', [0, 4]);
        }

        $examinations = $query->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('invigilator.examination.index', compact('examinations'));
    }

    public function submissionView($id)
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
        return view('invigilator.examination.view', compact('examsubmission', 'proposes', 'members'));
    }

    public function submissionDownload($id)
    {
        $filePath = ExamSubmission::findOrFail($id)->file_path;

        if (Storage::disk('public')->exists($filePath)) {
            return response()->download(storage_path('app/public/' . $filePath));
        }

        return abort(404, 'File not found');
    }

    public function showSchedule($id)
    {
        // ดึงข้อมูล ExamSubmission พร้อมความสัมพันธ์ที่จำเป็น
        $submission = ExamSubmission::findOrFail($id);

        return view('invigilator.examination.schedule', compact('submission'));
    }

    // Example controller function
    public function scoreForm($id)
    {
        // ดึงข้อมูลการสอบตาม id
        $examsubmission = ExamSubmission::findOrFail($id);

        // ดึงข้อมูล exam type ตาม typeId
        $projectType = ProjectType::where('id', $examsubmission->propose->project_type->id)->first();

        // ดึงข้อมูล formsets ตาม exam_type_id
        $formsets = Formset::where('project_type_id', $projectType->id)->get();

        // ดึงข้อมูลหัวข้อหลัก (main topics) ตาม form_id
        $mainTopics = MainTopic::where('form_id', $formsets->pluck('id'))->get();

        // ดึงข้อมูลหัวข้อย่อย (sub topics) ที่เกี่ยวข้องกับ main_topics
        $subTopics = SubTopic::whereIn('mtopic_id', $mainTopics->pluck('id'))->get();

        // ดึงข้อมูลหัวข้อย่อยยิ่ง (subsub topics) ที่เกี่ยวข้องกับ sub_topics
        $subSubTopics = SubSubTopic::whereIn('stopic_id', $subTopics->pluck('id'))->get();

        // ดึงข้อมูลกลุ่มสมาชิก (group_members) ที่เกี่ยวข้องกับ group_id จาก exam_submission
        $groupMembers = GroupMember::where('group_id', $examsubmission->propose->group_id)->get();

        // ส่งข้อมูลไปยัง view
        return view('invigilator.examination.score', compact('examsubmission', 'projectType', 'formsets', 'mainTopics', 'subTopics', 'subSubTopics', 'groupMembers'));
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

        // // ลบเกรดเก่าของ submission นี้ ที่อาจารย์คนนี้เคยบันทึก
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
            // ถ้าครบ ให้เปลี่ยนสถานะของ submission เป็น 0 (สอบเสร็จสิ้น)
            $submission->status = 0;
            $submission->save();
        }

        return redirect()
            ->route('invigilator.examination.index')
            ->with('success', 'บันทึกคะแนนเรียบร้อยแล้ว');
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

    // revision setting
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
            ->where('role', 2)
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
                            ->where('role', 2)
                    )
                    ->latest('id') // กันกรณีมีหลายแถว เผื่อแก้ไขซ้ำ
                    ->limit(1),
                // เผื่อใช้ชื่อโครงงานใน view
                'exam_submission.propose:id,title',
            ])
            ->orderByDesc('updated_at')
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('invigilator.revision.index', compact('revisions', 'search'));
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

        return view('invigilator.revision.approve', compact('revisions', 'invigilators'));
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

        // 4) ตรวจสอบสถานะของกรรมการ role = 1 ทั้งหมด
        //    - หา list ของ exam_invi_id ที่ role = 1 สำหรับ submission นี้
        $committeeIds = ExamInviMember::where('submission_id', $revision->submission_id)
            ->where('role', 1)
            ->pluck('id'); // ใช้ id ของ exam_invi_members

        //    - นับจำนวนกรรมการทั้งหมด (role = 1)
        $totalCommittee = $committeeIds->count();

        //    - นับจำนวนที่อยู่ใน revision_approvals 
        //      สำหรับ revision นี้ และ exam_invi_id หรือตัวกรรมการข้างบน
        $approvedCount = RevisionApproval::where('revision_id', $revision->id)
            ->whereIn('exam_invi_id', $committeeIds)
            ->where('status', 1)
            ->count();

        $rejectCount = RevisionApproval::where('revision_id', $revision->id)
            ->whereIn('exam_invi_id', $committeeIds)
            ->where('status', 3)
            ->count();

        // 5) อัปเดตสถานะหลักในตาราง revisions ตามเงื่อนไข
        if ($rejectCount === $totalCommittee) {
            // กรรมการทุกคนยังรออนุมัติ (status = 1) → set revision.status = 3
            $revision->status = 3;
        } elseif ($approvedCount === $totalCommittee) {
            // กรรมการทุกคนอนุมัติ (status = 3) → set revision.status = 4
            $revision->status = 4;
        }
        // (ถ้าไม่เข้าเงื่อนไขใด ๆ คงสถานะเดิมไว้)

        $revision->save();

        return redirect()
            ->route('invigilator.revision.index')
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

        return view('invigilator.revision.show', compact('revision', 'invigilators'));
    }
}
