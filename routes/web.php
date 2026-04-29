<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SchoolClassController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\AcademicTermController;
use App\Http\Controllers\Admin\ScoreApprovalController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\NewsPostController;
use App\Http\Controllers\Public\NewsController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboard;
use App\Http\Controllers\Teacher\AttendanceController;
use App\Http\Controllers\Teacher\ScoreController;
use App\Http\Controllers\Teacher\ResultController;
use App\Http\Controllers\Teacher\ProfileController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\AboutController;
use App\Http\Controllers\Public\AdmissionsController;
use App\Http\Controllers\Public\AcademicsController;
use App\Http\Controllers\Public\GalleryController as PublicGalleryController;
use App\Http\Controllers\Public\StaffController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\PublicResultController;
use App\Http\Controllers\Admin\PublicationController;
use App\Http\Controllers\Admin\PopupNoticeController;
use App\Http\Controllers\Admin\BackupController;

// ─────────────────────────────────────────────
// PUBLIC WEBSITE
// ─────────────────────────────────────────────
Route::get('/',            [HomeController::class,         'index'])->name('home');
Route::get('/about',       [AboutController::class,        'index'])->name('about');
Route::get('/admissions',  [AdmissionsController::class,   'index'])->name('admissions');
Route::get('/academics',   [AcademicsController::class,    'index'])->name('academics');
Route::get('/gallery',     [PublicGalleryController::class,'index'])->name('gallery');
Route::get('/staff',       [StaffController::class,        'index'])->name('staff');
Route::get('/contact',     [ContactController::class,      'index'])->name('contact');
Route::post('/contact',    [ContactController::class,      'send'])->name('contact.send')->middleware('throttle:contact');
Route::get('/news',            [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{newsPost}', [NewsController::class, 'show'])->name('news.show');

// ─────────────────────────────────────────────
// PUBLIC RESULT VIEWER (parent-facing, no login)
// ─────────────────────────────────────────────
Route::get('/portal/result/{token}',      [PublicResultController::class, 'show'])->name('result.public.show')->middleware('throttle:20,1');
Route::get('/portal/result/{token}/pdf',  [PublicResultController::class, 'pdf'])->name('result.public.pdf')->middleware('throttle:20,1');

// ─────────────────────────────────────────────
// AUTH
// ─────────────────────────────────────────────
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware(['guest', 'throttle:login']);
Route::post('/logout',[AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Forgot / Reset Password
Route::get('/forgot-password',         [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'show'])->name('password.request')->middleware('guest');
Route::post('/forgot-password',        [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'send'])->name('password.email')->middleware(['guest','throttle:5,1']);
Route::get('/reset-password/{token}',  [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'resetForm'])->name('password.reset')->middleware('guest');
Route::post('/reset-password',         [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'reset'])->name('password.update')->middleware('guest');

// ─────────────────────────────────────────────
// ADMIN ROUTES
// ─────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:principal,admin'])->group(function () {

    Route::get('/dashboard',    [AdminDashboard::class,  'index'])->name('dashboard');
    Route::get('/profile',      [AdminDashboard::class,  'profile'])->name('profile');

    // Staff / Users
    Route::get('/users',                 [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create',          [UserController::class, 'create'])->name('users.create');
    Route::post('/users',                [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit',     [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}',          [UserController::class, 'update'])->name('users.update');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::post('/users/{user}/toggle-status',  [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::delete('/users/{user}',              [UserController::class, 'destroy'])->name('users.destroy');

    // Students
    Route::get('/students',                          [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create',                   [StudentController::class, 'create'])->name('students.create');
    Route::post('/students',                         [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/bulk-promote',             [StudentController::class, 'promotionPreview'])->name('students.bulk-promote');
    Route::post('/students/bulk-promote',            [StudentController::class, 'promotionExecute'])->name('students.bulk-promote.execute');
    Route::get('/students/{student}/result-pdf',     [StudentController::class, 'resultPdf'])->name('students.result-pdf')->middleware('throttle:30,1');
    Route::get('/students/{student}',                [StudentController::class, 'show'])->name('students.show');
    Route::get('/students/{student}/edit',           [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}',                [StudentController::class, 'update'])->name('students.update');
    Route::post('/students/{student}/promote',       [StudentController::class, 'promote'])->name('students.promote');
    Route::delete('/students/{student}',             [StudentController::class, 'destroy'])->name('students.destroy');

    // Classes
    Route::get('/classes',               [SchoolClassController::class, 'index'])->name('classes.index');
    Route::post('/classes',              [SchoolClassController::class, 'store'])->name('classes.store');
    Route::put('/classes/{class}',       [SchoolClassController::class, 'update'])->name('classes.update');
    Route::delete('/classes/{class}',    [SchoolClassController::class, 'destroy'])->name('classes.destroy');

    // Subjects
    Route::get('/subjects',                [SubjectController::class, 'index'])->name('subjects.index');
    Route::post('/subjects',               [SubjectController::class, 'store'])->name('subjects.store');
    Route::delete('/subjects/bulk',        [SubjectController::class, 'bulkDestroy'])->name('subjects.bulk-destroy');
    Route::put('/subjects/{subject}',      [SubjectController::class, 'update'])->name('subjects.update');
    Route::delete('/subjects/{subject}',   [SubjectController::class, 'destroy'])->name('subjects.destroy');

    // Academic Terms
    Route::get('/terms',                 [AcademicTermController::class, 'index'])->name('terms.index');
    Route::post('/terms',                [AcademicTermController::class, 'store'])->name('terms.store');
    Route::put('/terms/{term}',          [AcademicTermController::class, 'update'])->name('terms.update');
    Route::post('/terms/{term}/set-current', [AcademicTermController::class, 'setCurrent'])->name('terms.set-current');

    // Score Approval
    Route::get('/scores',                    [ScoreApprovalController::class, 'index'])->name('scores.index');
    Route::post('/scores/bulk-approve',      [ScoreApprovalController::class, 'bulkApprove'])->name('scores.bulk-approve');
    Route::post('/scores/bulk-delete',       [ScoreApprovalController::class, 'bulkDelete'])->name('scores.bulk-delete');
    Route::get('/scores/{score}',            [ScoreApprovalController::class, 'show'])->name('scores.show');
    Route::post('/scores/{score}/approve',   [ScoreApprovalController::class, 'approve'])->name('scores.approve');
    Route::post('/scores/{score}/return',    [ScoreApprovalController::class, 'return'])->name('scores.return');
    Route::post('/scores/{score}/lock',      [ScoreApprovalController::class, 'lock'])->name('scores.lock');
    Route::post('/scores/{score}/unlock',    [ScoreApprovalController::class, 'unlock'])->name('scores.unlock');

    // Reports
    Route::get('/reports',               [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/class/{class}', [ReportController::class, 'classReport'])->name('reports.class');
    Route::get('/reports/attendance',    [ReportController::class, 'attendance'])->name('reports.attendance');
    Route::get('/reports/student/{student}/transcript', [ReportController::class, 'studentTranscript'])->name('reports.student-transcript');

    // Announcements
    Route::get('/announcements',         [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/announcements',        [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::put('/announcements/{announcement}',    [AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

    // Gallery
    Route::get('/gallery',               [GalleryController::class, 'index'])->name('gallery.index');
    Route::post('/gallery',              [GalleryController::class, 'store'])->name('gallery.store');
    Route::delete('/gallery/{item}',     [GalleryController::class, 'destroy'])->name('gallery.destroy');

    // News Posts
    Route::get('/news-posts',                   [NewsPostController::class, 'index'])->name('news-posts.index');
    Route::get('/news-posts/create',            [NewsPostController::class, 'create'])->name('news-posts.create');
    Route::post('/news-posts',                  [NewsPostController::class, 'store'])->name('news-posts.store');
    Route::get('/news-posts/{newsPost}/edit',   [NewsPostController::class, 'edit'])->name('news-posts.edit');
    Route::put('/news-posts/{newsPost}',        [NewsPostController::class, 'update'])->name('news-posts.update');
    Route::delete('/news-posts/{newsPost}',     [NewsPostController::class, 'destroy'])->name('news-posts.destroy');

    // Hero Slides
    Route::get('/hero-slides',                          [HeroSlideController::class, 'index'])->name('hero-slides.index');
    Route::post('/hero-slides',                         [HeroSlideController::class, 'store'])->name('hero-slides.store');
    Route::put('/hero-slides/{heroSlide}',              [HeroSlideController::class, 'update'])->name('hero-slides.update');
    Route::delete('/hero-slides/{heroSlide}',           [HeroSlideController::class, 'destroy'])->name('hero-slides.destroy');
    Route::post('/hero-slides/{heroSlide}/toggle',      [HeroSlideController::class, 'toggleActive'])->name('hero-slides.toggle');

    // Settings
    Route::get('/settings',              [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings',             [SettingsController::class, 'update'])->name('settings.update');

    // Activity Logs
    Route::get('/activity-logs',         [ActivityLogController::class, 'index'])->name('activity-logs');

    // Admin Profile
    Route::put('/profile',               [AdminProfileController::class, 'update'])->name('profile.update');
    Route::post('/password',             [AdminProfileController::class, 'changePassword'])->name('password.change');

    // Result Publications (principal remarks)
    Route::get('/publications',                              [PublicationController::class, 'index'])->name('publications.index');
    Route::post('/publications/{publication}/remarks',       [PublicationController::class, 'addRemarks'])->name('publications.remarks');

    // Contact Messages
    Route::get('/messages',              [ContactMessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{message}',    [ContactMessageController::class, 'show'])->name('messages.show');
    Route::delete('/messages/{message}', [ContactMessageController::class, 'destroy'])->name('messages.destroy');

    // Grading Scale (managed via Settings)
    Route::post('/grading-scales',              [SettingsController::class, 'storeGradingScale'])->name('grading-scales.store');
    Route::put('/grading-scales/{scale}',       [SettingsController::class, 'updateGradingScale'])->name('grading-scales.update');
    Route::delete('/grading-scales/{scale}',    [SettingsController::class, 'destroyGradingScale'])->name('grading-scales.destroy');

    // CSV exports
    Route::get('/reports/export/class-csv/{class}', [ReportController::class, 'exportClassCsv'])->name('reports.export.class-csv');
    Route::get('/reports/export/attendance-csv',    [ReportController::class, 'exportAttendanceCsv'])->name('reports.export.attendance-csv');

    // Popup Notice
    Route::get('/popup-notice',             [PopupNoticeController::class, 'index'])->name('popup-notice.index');
    Route::put('/popup-notice',             [PopupNoticeController::class, 'update'])->name('popup-notice.update');
    Route::delete('/popup-notice/image',    [PopupNoticeController::class, 'destroyImage'])->name('popup-notice.destroy-image');
});

// ─────────────────────────────────────────────
// BACKUP ROUTES (admin only — not accessible to principal)
// ─────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/backup',                        [BackupController::class, 'index'])->name('backup.index');
    Route::post('/backup',                       [BackupController::class, 'store'])->name('backup.store')->middleware('throttle:10,1');
    Route::get('/backup/{filename}',             [BackupController::class, 'download'])->name('backup.download');
    Route::delete('/backup/{filename}',          [BackupController::class, 'destroy'])->name('backup.destroy');
    Route::post('/backup/clear-soft',            [BackupController::class, 'clearSoft'])->name('backup.clear-soft')->middleware('throttle:3,1');
    Route::post('/backup/clear-hard',            [BackupController::class, 'clearHard'])->name('backup.clear-hard')->middleware('throttle:3,1');
});

// ─────────────────────────────────────────────
// TEACHER ROUTES
// ─────────────────────────────────────────────
Route::prefix('teacher')->name('teacher.')->middleware(['auth', 'role:teacher'])->group(function () {

    Route::get('/dashboard',   [TeacherDashboard::class, 'index'])->name('dashboard');
    Route::get('/profile',     [TeacherDashboard::class, 'profile'])->name('profile');
    Route::put('/profile',     [ProfileController::class,'update'])->name('profile.update');
    Route::post('/password',   [ProfileController::class,'changePassword'])->name('password.change');

    // My students
    Route::get('/students',    [TeacherDashboard::class, 'students'])->name('students');

    // Attendance
    Route::get('/attendance',        [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance',       [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/report', [AttendanceController::class, 'report'])->name('attendance.report');

    // Scores
    Route::get('/scores',            [ScoreController::class, 'index'])->name('scores.index');
    Route::post('/scores/save',      [ScoreController::class, 'save'])->name('scores.save');
    Route::post('/scores/submit',    [ScoreController::class, 'submit'])->name('scores.submit');

    // Results (form teachers only — enforced inside controller)
    Route::get('/results',                   [ResultController::class, 'index'])->name('results.index');
    Route::post('/results/generate',         [ResultController::class, 'generate'])->name('results.generate');
    Route::get('/results/publish',           [ResultController::class, 'publishForm'])->name('results.publish.form');
    Route::post('/results/publish',          [ResultController::class, 'storePublication'])->name('results.publish.store');

    // Announcements
    Route::get('/announcements',     [\App\Http\Controllers\Teacher\AnnouncementController::class, 'index'])->name('announcements');
});
