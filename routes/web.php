<?php

use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdvisorController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\ProposeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\InvigilatorController;
use App\Http\Middleware\Admin;
use Illuminate\Support\Facades\Auth;
use App\Models\Upload;
use App\Models\UploadFile;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\AdvisorIsAdmin;

// Route::get('/', function () {
//     return view('project');
// })->name('welcome');

Route::get('/', [ProjectController::class, 'index'])->name('welcome');

// dashboard route will check if user is student or advisor and redirect to the correct dashboard
Route::get('/dashboard', function () {

    if (Auth::guard('students')->check()) {
        return redirect()->route('student.group.index');
    }

    if (Auth::guard('advisors')->check()) {

        $advisor = Auth::guard('advisors')->user();

        switch ($advisor->a_type) {
            case 'admin':
                return redirect()->route('admin.advisor.index');

            case 'teacher':
                return redirect()->route('teacher.calendar.index');

            case 'advisor':
                return redirect()->route('advisor.propose.index');
        }
    }

    return redirect()->route('project.index');
})->name('dashboard');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth:students,advisors'])->group(function () {
    Route::prefix('/profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit')->defaults('title', 'จัดการข้อมูลส่วนตัว');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::patch('/password', [ProfileController::class, 'updatePassword'])->name('update.password');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    Route::get('teacher/calendar/home', [TeacherController::class, 'calendarHome'])->name('teacher.calendar.home')->defaults('title', 'ปฏิทินการสอบ');
});

// Admin setting
// Route::middleware(['auth:advisors'])->group(function () {
// Route::middleware(['auth:advisors', 'advisor.admin'])->group(function () {
Route::middleware(['auth:advisors', AdvisorIsAdmin::class])->group(function () {
    Route::prefix('admin/advisor')->name('admin.advisor.')->group(function () {
        Route::get('index', [AdminController::class, 'advisorIndex'])->name('index')->defaults('title', 'จัดการข้อมูลอาจารย์');
        Route::get('create', [AdminController::class, 'advisorCreate'])->name('create');
        Route::post('store', [AdminController::class, 'advisorStore'])->name('store');
        Route::get('edit/{id}', [AdminController::class, 'advisorEdit'])->name('edit');
        Route::put('{id}', [AdminController::class, 'advisorUpdate'])->name('update');
        Route::delete('advisor/{id}', [AdminController::class, 'advisorDelete'])->name('delete');
        Route::post('import', [AdminController::class, 'importAdvisor'])->name('import');
    });

    Route::prefix('admin/student')->name('admin.student.')->group(function () {
        Route::get('index', [AdminController::class, 'studentIndex'])->name('index')->defaults('title', 'จัดการข้อมูลนักศึกษา');
        Route::get('create', [AdminController::class, 'studentCreate'])->name('create');
        Route::post('store', [AdminController::class, 'studentStore'])->name('store');
        Route::get('edit/{id}', [AdminController::class, 'studentEdit'])->name('edit');
        Route::put('{id}', [AdminController::class, 'studentUpdate'])->name('update');
        Route::delete('delete/{id}', [AdminController::class, 'studentDelete'])->name('delete');
        Route::post('import', [AdminController::class, 'importStudent'])->name('import');
    });

    Route::prefix('admin/major')->name('admin.major.')->group(function () {
        Route::get('index', [AdminController::class, 'majorIndex'])->name('index')->defaults('title', 'จัดการข้อมูลสาขา');
        Route::get('create', [AdminController::class, 'majorCreate'])->name('create');
        Route::post('store', [AdminController::class, 'majorStore'])->name('store');
        Route::get('edit/{id}', [AdminController::class, 'majorEdit'])->name('edit');
        Route::put('update/{id}', [AdminController::class, 'majorUpdate'])->name('update');
        Route::delete('delete/{id}', [AdminController::class, 'majorDelete'])->name('delete');
    });

    Route::prefix('admin/academic-year')->name('admin.academic-year.')->group(function () {
        Route::get('index', [AdminController::class, 'academicYearIndex'])->name('index')->defaults('title', 'จัดการข้อมูลปีการศึกษา');
        Route::get('create', [AdminController::class, 'yearcreate'])->name('create');
        Route::post('store', [AdminController::class, 'yearstore'])->name('store');
        Route::delete('delete/{id}', [AdminController::class, 'academicYearDelete'])->name('delete');
    });

    Route::prefix('admin/course')->name('admin.course.')->group(function () {
        Route::get('index', [AdminController::class, 'courseIndex'])->name('index')->defaults('title', 'จัดการข้อมูลรายวิชา');
        Route::get('create', [AdminController::class, 'courseCreate'])->name('create');
        Route::post('store', [AdminController::class, 'courseStore'])->name('store');
        Route::get('course/{id}/edit', [AdminController::class, 'courseEdit'])->name('edit');
        Route::put('course/{id}', [AdminController::class, 'courseUpdate'])->name('update');
        Route::delete('delete/{id}', [AdminController::class, 'courseDelete'])->name('delete');
    });

    Route::prefix('admin/project-type')->name('admin.project-type.')->group(function () {
        Route::get('index', [AdminController::class, 'projectTypeIndex'])->name('index')->defaults('title', 'จัดการข้อมูลประเภทโครงการ');
        Route::get('create', [AdminController::class, 'projectTypeCreate'])->name('create');
        Route::post('store', [AdminController::class, 'projectTypeStore'])->name('store');
        Route::get('edit/{id}', [AdminController::class, 'projectTypeEdit'])->name('edit');
        Route::put('update/{id}', [AdminController::class, 'projectTypeUpdate'])->name('update');
        Route::delete('delete/{id}', [AdminController::class, 'projectTypeDelete'])->name('delete');
    });

    Route::prefix('admin/topic')->name('admin.topic.')->group(function () {
        //maintopic
        Route::get('maintopic/index', [AdminController::class, 'mainindex'])->name('maintopic.index')->defaults('title', 'จัดการข้อมูลหัวข้อหลัก');
        Route::get('maintopic/create', [AdminController::class, 'maincreate'])->name('maintopic.create');
        Route::post('maintopic/store', [AdminController::class, 'mainstore'])->name('maintopic.store');
        Route::get('maintopic/edit/{id}', [AdminController::class, 'mainedit'])->name('maintopic.edit');
        Route::delete('maintopic/{id}', [AdminController::class, 'maindelete'])->name('maintopic.delete');
        Route::put('maintopic/{id}', [AdminController::class, 'mainupdate'])->name('maintopic.update');

        //subtipic
        Route::get('subtopic/index', [AdminController::class, 'subindex'])->name('subtopic.index')->defaults('title', 'จัดการข้อมูลหัวข้อรอง');
        Route::get('subtopic/create', [AdminController::class, 'subcreate'])->name('subtopic.create');
        Route::post('subtopic/store', [AdminController::class, 'substore'])->name('subtopic.store');
        Route::get('subtopic/edit/{id}', [AdminController::class, 'subedit'])->name('subtopic.edit');
        Route::put('subtopic/{id}', [AdminController::class, 'subupdate'])->name('subtopic.update');
        Route::delete('subtopic/{id}', [AdminController::class, 'subdelete'])->name('subtopic.delete');

        //subsubtopic
        Route::get('subsubtopic/index', [AdminController::class, 'subsubindex'])->name('subsubtopic.index')->defaults('title', 'จัดการข้อมูลหัวข้อรอง');
        Route::get('subsubtopic/create', [AdminController::class, 'subsubcreate'])->name('subsubtopic.create');
        Route::post('subsubtopic/store', [AdminController::class, 'subsubstore'])->name('subsubtopic.store');
        Route::get('subsubtopic/edit/{id}', [AdminController::class, 'subsubedit'])->name('subsubtopic.edit');
        Route::put('subsubtopic/{id}', [AdminController::class, 'subsubupdate'])->name('subsubtopic.update');
        Route::delete('subsubtopic/{id}', [AdminController::class, 'subsubdelete'])->name('subsubtopic.delete');
    });

    Route::prefix('admin/form')->name('admin.form.')->group(function () {
        Route::get('index', [AdminController::class, 'formIndex'])->name('index')->defaults('title', 'จัดการข้อมูลแบบฟอร์ม');
        Route::get('create', [AdminController::class, 'formcreate'])->name('create');
        Route::post('form', [AdminController::class, 'formstore'])->name('store');
        Route::get('addtopic/{id}', [AdminController::class, 'createTopics'])->name('add');
        Route::post('addtopic/{id}', [AdminController::class, 'storeTopics'])->name('storeTopic');
        Route::get('edittopic/{id}', [AdminController::class, 'editTopics'])->name('editTopic');
        Route::put('updatetopic/{id}', [AdminController::class, 'updateTopic'])->name('updateTopic');
        Route::delete('form{id}', [AdminController::class, 'formdelete'])->name('delete');
        Route::get('show/{id}', [AdminController::class, 'show'])->name('show');
    });

    Route::prefix('admin/alumni')->name('admin.alumni.')->group(function () {
        Route::prefix('project')->name('project.')->group(function () {
            Route::get('index', [AdminController::class, 'alumniProjectIndex'])->name('index')->defaults('title', 'จัดการข้อมูลโครงการศิษย์เก่า');
            Route::get('create', [AdminController::class, 'alumniProjectCreate'])->name('create');
            Route::post('store', [AdminController::class, 'alumniProjectStore'])->name('store');
            Route::get('edit/{alumniProject}', [AdminController::class, 'alumniProjectEdit'])->name('edit');
            Route::put('update/{alumniProject}', [AdminController::class, 'alumniProjectUpdate'])->name('update');
            Route::delete('delete/{id}', [AdminController::class, 'alumniProjectDelete'])->name('delete');
        });

        // Route::prefix('student')->name('student.')->group(function () {
        //     Route::get('index', [AdminController::class, 'alumniStudentIndex'])->name('index');
        //     Route::get('create', [AdminController::class, 'alumniStudentCreate'])->name('create');
        //     Route::post('store', [AdminController::class, 'alumniStudentStore'])->name('store');
        //     Route::get('edit/{id}', [AdminController::class, 'alumniStudentEdit'])->name('edit');
        //     Route::put('update/{id}', [AdminController::class, 'alumniStudentUpdate'])->name('update');
        //     Route::delete('delete/{id}', [AdminController::class, 'alumniStudentDelete'])->name('delete');
        // });
    });
});


Route::middleware(['auth:students'])->group(function () {

    Route::prefix('student/group')->name('student.group.')->group(function () {
        Route::get('index', [StudentController::class, 'groupIndex'])->name('index')->defaults('title', 'จัดการข้อมูลกลุ่มโครงงาน');
        Route::get('create', [StudentController::class, 'groupCreate'])->name('create');
        Route::post('store', [StudentController::class, 'groupStore'])->name('store');
        Route::get('edit/{group}', [StudentController::class, 'edit'])->name('edit');
        Route::put('update/{group}', [StudentController::class, 'update'])->name('update');
    });

    Route::prefix('student/propose')->name('student.propose.')->group(function () {
        Route::get('index', [StudentController::class, 'proposeIndex'])->name('index')->defaults('title', 'จัดการข้อมูลเสนอโครงงาน');
        Route::get('create', [StudentController::class, 'proposeCreate'])->name('create');
        Route::post('store', [StudentController::class, 'proposeStore'])->name('store');
        Route::get('edit/{id}', [StudentController::class, 'proposeEdit'])->name('edit');
        Route::put('{id}', [StudentController::class, 'proposeUpdate'])->name('update');

        Route::get('history/{id}', [StudentController::class, 'historyProposeIndex'])->name('history');
    });

    Route::prefix('student/submission')->name('student.submission.')->group(function () {
        Route::get('index', [StudentController::class, 'submissionIndex'])->name('index')->defaults('title', 'จัดการข้อมูลยื่นสอบโครงงาน');
        Route::get('create/{proposeId}', [StudentController::class, 'submissionCreate'])->name('create');
        Route::post('store/{proposeId}', [StudentController::class, 'submissionStore'])->name('store');
        // Route::get('submission/{id}/{propose_id}', [StudentController::class, 'submission'])->name('submission');
        Route::get('view/{submissionId}/{proposeId}', [StudentController::class, 'submissionView'])->name('view');
        Route::post('save/{id}', [StudentController::class, 'save'])->name('save');
        Route::get('edit/{submissionId}/{proposeId}', [StudentController::class, 'submissionEdit'])->name('edit');
        Route::put('{submissionId}/{proposeId}', [StudentController::class, 'submissionUpdate'])->name('update');
        Route::get('download/{id}', [StudentController::class, 'submissionDownloadFile'])->name('download');
        Route::get('schedule/{submissionId}', [StudentController::class, 'scheduleShow'])->name('schedule');

        Route::get('history/{id}', [StudentController::class, 'historyExamSubIndex'])->name('history');
    });

    Route::prefix('student/revision')->name('student.revision.')->group(function () {
        Route::get('index', [StudentController::class, 'revisionIndex'])->name('index')->defaults('title', 'จัดการข้อมูลแก้ไขโครงงาน');
        Route::get('create/{id}', [StudentController::class, 'revisionCreate'])->name('create');
        Route::post('store/{submission}', [StudentController::class, 'revisionStore'])->name('store');
        Route::get('edit/{revision}',   [StudentController::class, 'editRevision'])->name('edit');
        Route::put('update/{revision}', [StudentController::class, 'revisionUpdate'])->name('update');
        Route::get('download/{id}', [StudentController::class, 'revisionDownloadFile'])->name('download');
        Route::get('show/{revisionId}', [StudentController::class, 'showRevision'])->name('show');
    });

    Route::prefix('student/upload')->name('student.upload.')->group(function () {
        Route::get('index', [StudentController::class, 'uploadIndex'])->name('index')->defaults('title', 'จัดการข้อมูลอัปโหลด');
        Route::get('create/{proposeId}', [StudentController::class, 'uploadCreate'])->name('create');
        Route::post('store/{proposeId}', [StudentController::class, 'uploadStore'])->name('store');
        Route::get('show/{upload}', [StudentController::class, 'uploadShow'])->name('show');
        Route::get('edit/{upload}', [StudentController::class, 'uploadEdit'])->name('edit');
        Route::put('update/{upload}', [StudentController::class, 'uploadUpdate'])->name('update');
        Route::get('preview/{upload}/{type}', [StudentController::class, 'preview'])->name('preview')
            ->where('type', 'abstract|cover|project');
    });
});

Route::get('/excel/{filename}', function ($filename) {
    $filename = urldecode($filename);
    $path = public_path('template/' . $filename);

    if (!File::exists($path)) {
        abort(404, 'ไม่พบไฟล์ excel');
    }

    return response()->download($path, $filename, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ]);
})->name('excel');


// Route::middleware(['auth:advisors'])->group(function () {
Route::middleware([AdvisorIsAdmin::class . ':advisor,teacher', 'auth:advisors'])->group(function () {
    Route::prefix('advisor/propose')->name('advisor.propose.')->group(function () {
        Route::get('index', [AdvisorController::class, 'proposeIndex'])->name('index')->defaults('title', 'จัดการข้อมูลหัวข้อโครงงาน');
        Route::get('approve/{id}', [AdvisorController::class, 'approveFormView'])->name('approveFormView');
        Route::get('view/{id}', [AdvisorController::class, 'approveView'])->name('approveView');
        Route::put('{id}', [AdvisorController::class, 'approve'])->name('approve');

        Route::get('history/{id}', [AdvisorController::class, 'historyProposeIndex'])->name('history');
    });

    // Route::get('invigilator/Exam', function () {
    //     return view('invigilator.Exam');
    // })->name('invigilator.Exam');

    Route::prefix('advisor/submission')->name('advisor.submission.')->group(function () {
        Route::get('index', [AdvisorController::class, 'submissionIndex'])->name('index')->defaults('title', 'จัดการข้อมูลการยื่นสอบโครงงาน');
        Route::get('submission/{id}', [AdvisorController::class, 'submission'])->name('submission');
        Route::get('download/{id}', [AdvisorController::class, 'downloadFile'])->name('download');
        Route::put('save/{id}', [AdvisorController::class, 'submissionSave'])->name('save');
        Route::get('view/{id}', [AdvisorController::class, 'examsubmissionView'])->name('view');
        Route::get('history/{id}', [AdvisorController::class, 'historyExamSubIndex'])->name('history');

        Route::get('aucc/{id}', [AdvisorController::class, 'auccForm'])->name('auccForm');
        Route::post('aucc/{id}', [AdvisorController::class, 'saveAuccScore'])->name('saveAucc');
    });

    Route::prefix('advisor/schedule')->name('advisor.schedule.')->group(function () {
        Route::get('create/{id}', [AdvisorController::class, 'schedule'])->name('create');
        Route::post('save/{id}', [AdvisorController::class, 'scheduleSave'])->name('save');
        Route::get('group/{id}', [AdvisorController::class, 'getInvigilators'])->name('byGroup');
        Route::get('show/{id}', [AdvisorController::class, 'showSchedule'])->name('show')->defaults('title', 'ดูตารางสอบ');
        Route::get('edit/{id}', [AdvisorController::class, 'scheduleEdit'])->name('edit');
        Route::put('update/{id}', [AdvisorController::class, 'scheduleUpdate'])->name('update');
    });

    Route::prefix('advisor/score')->name('advisor.score.')->group(function () {
        // Route::get('index', [AdvisorController::class, 'scoreIndex'])->name('index');
        Route::get('score/{id}', [AdvisorController::class, 'scoreForm'])->name('score')->defaults('title', 'การให้คะแนน');
        Route::post('save/{id}', [AdvisorController::class, 'saveScore'])->name('save');
        Route::get('view/{submission}', [AdvisorController::class, 'gradeView'])->name('view')->defaults('title', 'ดูคะแนน');

        // Route::get('manage/{id}/{propose_id}', [AdvisorController::class, 'manage'])->name('manage');
        // Route::get('download/{id}', [AdvisorController::class, 'downloadFile'])->name('download');
        // Route::post('examinationSave/{id}', [AdvisorController::class, 'examinationSave'])->name('examinationSave');
        // Route::get('exam-info/{id}/{propose_id}', [AdvisorController::class, 'examinationInfo'])->name('info');
    });

    Route::prefix('advisor/revision')->name('advisor.revision.')->group(function () {
        Route::get('index', [AdvisorController::class, 'revisionIndex'])->name('index')->defaults('title', 'จัดการข้อมูลแก้ไขโครงงาน');
        Route::get('approve/{revision}',   [AdvisorController::class, 'revisionApprove'])->name('approve');
        Route::put('update/{revision}', [AdvisorController::class, 'revisionUpdate'])->name('update');
        Route::get('download/{id}', [AdvisorController::class, 'revisionDownloadFile'])->name('download');
        Route::get('show/{revisionId}', [AdvisorController::class, 'showRevision'])->name('show');
    });

    Route::prefix('advisor/upload')->name('advisor.upload.')->group(function () {
        Route::get('index', [AdvisorController::class, 'uploadIndex'])->name('index')->defaults('title', 'จัดการข้อมูลอัปโหลด');
        Route::get('approve/{uploadId}', [AdvisorController::class, 'uploadApprove'])->name('approve');
        Route::put('update/{uploadId}', [AdvisorController::class, 'uploadUpdate'])->name('update');
        // Route::get('edit/{id}', [AdvisorController::class, 'uploadEdit'])->name('edit');
        // Route::put('update/{id}', [AdvisorController::class, 'uploadUpdate'])->name('update');
        Route::get('show/{upload}', [AdvisorController::class, 'uploadShow'])->name('show');
        Route::get('preview/{upload}/{type}', [AdvisorController::class, 'uploadPreview'])
            ->name('preview')
            ->where('type', 'abstract|cover|project');
    });
});

//teacher can see
// Route::middleware(['auth:advisors'])->group(function () {
Route::middleware([AdvisorIsAdmin::class . ':teacher', 'auth:advisors'])->group(function () {
    Route::prefix('teacher/calendar')->name('teacher.calendar.')->group(function () {
        Route::get('index', [TeacherController::class, 'calendarIndex'])->name('index')->defaults('title', 'จัดการปฏิทินการสอบ');
        Route::get('create', [TeacherController::class, 'calendarCreate'])->name('create');
        Route::post('store', [TeacherController::class, 'calendarStore'])->name('store');
        Route::get('edit/{id}', [TeacherController::class, 'editCalendar'])->name('edit');
        Route::put('update/{id}', [TeacherController::class, 'updateCalendar'])->name('update');
        Route::delete('delete/{id}', [TeacherController::class, 'deleteCalendar'])->name('delete');
    });

    Route::prefix('teacher/invigilator')->name('teacher.invigilator.')->group(function () {
        Route::get('home', [TeacherController::class, 'InvigilatorHome'])->name('home')->defaults('title', 'จัดการข้อมูลกรรมการผู้คุมสอบ');
        Route::get('group/{id}', [TeacherController::class, 'InvigilatorGroup'])->name('group');
        Route::get('create', [TeacherController::class, 'create'])->name('create');
        Route::post('store', [TeacherController::class, 'store'])->name('store');
        Route::get('member/{id}', [TeacherController::class, 'showGroupMembers'])->name('member');
        Route::get('edit/{id}', [TeacherController::class, 'editGroupMembers'])->name('edit');
        Route::put('edit/{id}', [TeacherController::class, 'updateGroupMembers'])->name('update');
        Route::delete('group/delete/{id}', [TeacherController::class, 'deleteGroup'])->name('delete');
    });

    Route::prefix('teacher/propose')->name('teacher.propose.')->group(function () {
        Route::get('index', [TeacherController::class, 'proposeIndex'])->name('index')->defaults('title', 'การติดตามข้อมูลโครงงาน');
        Route::get('show/{id}', [TeacherController::class, 'proposeShow'])->name('show');
    });
});

//Every Invigilator can see
Route::middleware(['auth:advisors'])->group(function () {

    Route::prefix('invigilator/examination')->name('invigilator.examination.')->group(function () {
        Route::get('index', [InvigilatorController::class, 'examinationIndex'])->name('index')->defaults('title', 'จัดการข้อมูลการสอบ');
        Route::get('score/{id}', [InvigilatorController::class, 'scoreForm'])->name('score');
        Route::post('save/{id}', [InvigilatorController::class, 'saveScore'])->name('save');
        Route::get('view/{id}', [InvigilatorController::class, 'submissionView'])->name('view');
        Route::get('download/{id}', [InvigilatorController::class, 'submissionDownload'])->name('subDownload');
        Route::get('schedule/{submissionId}', [InvigilatorController::class, 'showSchedule'])->name('schedule');
    });

    Route::prefix('invigilator/revision')->name('invigilator.revision.')->group(function () {
        Route::get('index', [InvigilatorController::class, 'revisionIndex'])->name('index')->defaults('title', 'การอนุมัติแก้ไขโครงงาน');
        Route::get('approve/{revision}',   [InvigilatorController::class, 'revisionApprove'])->name('approve');
        Route::put('update/{revision}', [InvigilatorController::class, 'revisionUpdate'])->name('update');
        Route::get('download/{id}', [InvigilatorController::class, 'revisionDownloadFile'])->name('download');
        Route::get('show/{revisionId}', [InvigilatorController::class, 'showRevision'])->name('show');
    });
});

Route::post('student.logout', [AuthenticatedSessionController::class, 'studentLogout'])->name('studentLogout.logout');
Route::post('advisor.logout', [AuthenticatedSessionController::class, 'advisorLogout'])->name('advisorLogout.logout');

Route::prefix('project')->name('project.')->group(function () {
    // หน้าแสดงผล (Blade)
    Route::get('/', [ProjectController::class, 'index'])->name('index');
    Route::get('/{source}/{id}', [ProjectController::class, 'show'])->name('show');
    // Route::get('{upload}/preview/{type}', [ProjectController::class, 'preview'])
    //     ->name('preview');
    Route::get('{source}/{id}/preview/{type}', [ProjectController::class, 'preview'])
        ->name('preview');
});

require __DIR__ . '/auth.php';
