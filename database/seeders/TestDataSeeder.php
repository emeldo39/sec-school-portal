<?php

namespace Database\Seeders;

use App\Models\AcademicTerm;
use App\Models\ActivityLog;
use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\ContactMessage;
use App\Models\SchoolClass;
use App\Models\Score;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeacherAssignment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // ─────────────────────────────────────────────────────────────
        // 1. ACADEMIC TERMS
        // ─────────────────────────────────────────────────────────────
        AcademicTerm::query()->update(['is_current' => false]);

        $currentTerm = AcademicTerm::firstOrCreate(
            ['name' => 'First Term', 'academic_year' => '2024/2025'],
            ['is_current' => true,  'start_date' => '2024-09-09', 'end_date' => '2024-12-13']
        );
        $currentTerm->update(['is_current' => true]);

        $term2 = AcademicTerm::firstOrCreate(
            ['name' => 'Third Term', 'academic_year' => '2023/2024'],
            ['is_current' => false, 'start_date' => '2024-04-22', 'end_date' => '2024-07-26']
        );
        $term3 = AcademicTerm::firstOrCreate(
            ['name' => 'Second Term', 'academic_year' => '2023/2024'],
            ['is_current' => false, 'start_date' => '2024-01-08', 'end_date' => '2024-04-05']
        );
        $term4 = AcademicTerm::firstOrCreate(
            ['name' => 'First Term', 'academic_year' => '2023/2024'],
            ['is_current' => false, 'start_date' => '2023-09-11', 'end_date' => '2023-12-15']
        );

        $this->command->info('✓ Academic terms seeded.');

        // ─────────────────────────────────────────────────────────────
        // 2. LOAD CLASSES & SUBJECTS
        // ─────────────────────────────────────────────────────────────
        $classes  = SchoolClass::all()->keyBy('name');
        $subjects = Subject::all()->keyBy('code');

        if ($classes->isEmpty() || $subjects->isEmpty()) {
            $this->command->error('Run SchoolClassSeeder and SubjectSeeder first.');
            return;
        }

        // ─────────────────────────────────────────────────────────────
        // 3. TEACHERS
        // ─────────────────────────────────────────────────────────────
        $teacherDefs = [
            [
                'name' => 'Mr. Emeka Okonkwo',   'email' => 'emeka.okonkwo@royalcollege.edu.ng',
                'phone' => '08031234567',          'form_class' => 'JSS1A',
                'class_names'   => ['JSS1A','JSS1B','JSS2A','JSS2B'],
                'subject_codes' => ['ENG','CIV'],
            ],
            [
                'name' => 'Mrs. Adaeze Nnadi',    'email' => 'adaeze.nnadi@royalcollege.edu.ng',
                'phone' => '08023456789',          'form_class' => 'JSS2A',
                'class_names'   => ['JSS1A','JSS1B','JSS2A','JSS2B','JSS3A','JSS3B'],
                'subject_codes' => ['MTH','BSC'],
            ],
            [
                'name' => 'Mr. Chidi Eze',        'email' => 'chidi.eze@royalcollege.edu.ng',
                'phone' => '08045678901',          'form_class' => 'JSS3A',
                'class_names'   => ['JSS2A','JSS2B','JSS3A','JSS3B'],
                'subject_codes' => ['SST','BUS','AGR'],
            ],
            [
                'name' => 'Mrs. Ngozi Obi',       'email' => 'ngozi.obi@royalcollege.edu.ng',
                'phone' => '08056789012',          'form_class' => 'SS1A',
                'class_names'   => ['SS1A','SS1B','SS2A','SS2B','SS3A','SS3B'],
                'subject_codes' => ['PHY','CHM','BIO'],
            ],
            [
                'name' => 'Mr. Ifeanyi Aneke',    'email' => 'ifeanyi.aneke@royalcollege.edu.ng',
                'phone' => '08067890123',          'form_class' => 'SS2A',
                'class_names'   => ['SS1A','SS2A','SS2B','SS3A','SS3B'],
                'subject_codes' => ['ECO','ACC','COM'],
            ],
            [
                'name' => 'Mrs. Chioma Okwu',     'email' => 'chioma.okwu@royalcollege.edu.ng',
                'phone' => '08078901234',          'form_class' => 'SS3A',
                'class_names'   => ['SS1B','SS2A','SS2B','SS3A','SS3B'],
                'subject_codes' => ['LIT','GOV','HIS'],
            ],
            [
                'name' => 'Mr. Obiora Nwachukwu', 'email' => 'obiora.nwachukwu@royalcollege.edu.ng',
                'phone' => '08089012345',          'form_class' => 'SS1B',
                'class_names'   => ['JSS1A','JSS1B','JSS2A','JSS2B','JSS3A','JSS3B'],
                'subject_codes' => ['IGB','FRN','PHE'],
            ],
        ];

        $adminUser    = User::whereIn('role', ['principal', 'admin'])->first();
        $teacherModels = [];

        foreach ($teacherDefs as $def) {
            $formClass = $classes->get($def['form_class']);
            $user = User::firstOrCreate(
                ['email' => $def['email']],
                [
                    'name'            => $def['name'],
                    'phone'           => $def['phone'],
                    'password'        => Hash::make('password'),
                    'role'            => 'teacher',
                    'status'          => 'active',
                    'is_form_teacher' => true,
                    'form_class_id'   => $formClass?->id,
                ]
            );

            // Sync assignments (wipe & re-insert)
            TeacherAssignment::where('user_id', $user->id)->delete();
            foreach ($def['class_names'] as $cn) {
                $cls = $classes->get($cn);
                if (!$cls) continue;
                foreach ($def['subject_codes'] as $code) {
                    $sub = $subjects->get($code);
                    if (!$sub) continue;
                    TeacherAssignment::create([
                        'user_id'    => $user->id,
                        'class_id'   => $cls->id,
                        'subject_id' => $sub->id,
                    ]);
                }
            }

            $teacherModels[] = ['user' => $user, 'def' => $def];
        }

        $this->command->info('✓ ' . count($teacherModels) . ' teachers seeded.');

        // ─────────────────────────────────────────────────────────────
        // 4. STUDENTS (8 per class)
        // ─────────────────────────────────────────────────────────────
        $maleFirst   = ['Chukwuemeka','Obinna','Chidera','Nnamdi','Ikenna','Chinedu','Uzochukwu','Kelechi',
                        'Ifeanyi','Uchenna','Somtochukwu','Chiamaka','Tobechukwu','Munachimso','Chizaram'];
        $femaleFirst = ['Adaeze','Chioma','Ngozi','Amara','Nkechi','Onyinye','Ifeoma','Chinyere',
                        'Obiageli','Uchechi','Oluchukwu','Nkiruka','Chinecherem','Mmesoma','Ezinne'];
        $lastNames   = ['Okonkwo','Nwosu','Eze','Obi','Aneke','Nnadi','Okwu','Igwe','Chukwu','Okafor',
                        'Nwachukwu','Uche','Onwudiwe','Okeke','Nweke','Mbah','Ogbu','Ugwu','Asogwa','Ezeani'];

        $studentIndex = 1;
        $allStudents  = [];

        foreach ($classes as $className => $classModel) {
            for ($i = 0; $i < 8; $i++) {
                $gender    = ($i % 2 === 0) ? 'male' : 'female';
                $firstName = $gender === 'male'
                    ? $maleFirst[array_rand($maleFirst)]
                    : $femaleFirst[array_rand($femaleFirst)];
                $lastName  = $lastNames[array_rand($lastNames)];
                $admNum    = 'DRIC/2024/' . str_pad($studentIndex, 4, '0', STR_PAD_LEFT);

                $student = Student::firstOrCreate(
                    ['admission_number' => $admNum],
                    [
                        'first_name'     => $firstName,
                        'last_name'      => $lastName,
                        'date_of_birth'  => Carbon::now()
                                            ->subYears(rand(10, 17))
                                            ->subDays(rand(0, 364))
                                            ->toDateString(),
                        'gender'         => $gender,
                        'class_id'       => $classModel->id,
                        'guardian_name'  => $lastNames[array_rand($lastNames)] . ' ' . $firstName,
                        'guardian_phone' => '0' . rand(7,9) . '0' . rand(10000000, 99999999),
                        'status'         => 'active',
                    ]
                );

                $allStudents[] = [
                    'model' => $student,
                    'class' => $classModel,
                    'isJss' => str_starts_with($classModel->level, 'JSS'),
                ];
                $studentIndex++;
            }
        }

        $this->command->info('✓ ' . count($allStudents) . ' students seeded.');

        // ─────────────────────────────────────────────────────────────
        // 5. SCORES (current term, all teacher–class–subject combos)
        // ─────────────────────────────────────────────────────────────
        $scoreCount = 0;

        foreach ($teacherModels as $tm) {
            $teacher = $tm['user'];
            $def     = $tm['def'];

            foreach ($def['class_names'] as $cn) {
                $classModel = $classes->get($cn);
                if (!$classModel) continue;

                $isJss = str_starts_with($classModel->level, 'JSS');
                $classStudents = array_filter(
                    $allStudents, fn($s) => $s['class']->id === $classModel->id
                );

                foreach ($def['subject_codes'] as $code) {
                    $subject = $subjects->get($code);
                    if (!$subject) continue;

                    foreach ($classStudents as $s) {
                        $student = $s['model'];

                        // Random status weighted towards approved
                        $r      = rand(1, 10);
                        $status = match (true) {
                            $r <= 5 => 'approved',
                            $r <= 8 => 'submitted',
                            default => 'draft',
                        };

                        if ($isJss) {
                            $ca1   = round(rand(15, 30) + rand(0,9)/10, 1);
                            $ca2   = round(rand(15, 30) + rand(0,9)/10, 1);
                            $exam  = round(rand(22, 40) + rand(0,9)/10, 1);
                            $total = $ca1 + $ca2 + $exam;

                            $scoreData = [
                                'ca_score'          => $ca1,
                                'ca_score_2'        => $ca2,
                                'exam_score'        => $exam,
                                'total_score'       => $total,
                                'weekly_exercise_1' => null,
                                'weekly_exercise_2' => null,
                                'take_home'         => null,
                                'college_quiz'      => null,
                                'summary_ca'        => null,
                                'mid_term'          => null,
                            ];
                        } else {
                            $we1   = round(rand(6, 10) + rand(0,9)/10, 1);
                            $we2   = round(rand(6, 10) + rand(0,9)/10, 1);
                            $th    = round(rand(6, 10) + rand(0,9)/10, 1);
                            $cq    = round(rand(6, 10) + rand(0,9)/10, 1);
                            $sumCA = round($we1 + $we2 + $th + $cq, 1);
                            $mid   = round(rand(13, 20) + rand(0,9)/10, 1);
                            $exam  = round(rand(25, 40) + rand(0,9)/10, 1);
                            $total = round($sumCA + $mid + $exam, 1);

                            $scoreData = [
                                'ca_score'          => null,
                                'ca_score_2'        => null,
                                'weekly_exercise_1' => $we1,
                                'weekly_exercise_2' => $we2,
                                'take_home'         => $th,
                                'college_quiz'      => $cq,
                                'summary_ca'        => $sumCA,
                                'mid_term'          => $mid,
                                'exam_score'        => $exam,
                                'total_score'       => $total,
                            ];
                        }

                        Score::updateOrCreate(
                            [
                                'student_id' => $student->id,
                                'subject_id' => $subject->id,
                                'term_id'    => $currentTerm->id,
                            ],
                            array_merge($scoreData, [
                                'class_id'     => $classModel->id,
                                'status'       => $status,
                                'submitted_by' => $teacher->id,
                                'submitted_at' => Carbon::now()->subDays(rand(5, 30)),
                                'reviewed_by'  => $status === 'approved'
                                                  ? ($adminUser?->id ?? $teacher->id)
                                                  : null,
                                'reviewed_at'  => $status === 'approved'
                                                  ? Carbon::now()->subDays(rand(1, 10))
                                                  : null,
                            ])
                        );
                        $scoreCount++;
                    }
                }
            }
        }

        $this->command->info("✓ {$scoreCount} scores seeded.");

        // ─────────────────────────────────────────────────────────────
        // 6. ATTENDANCE (last 20 school days per class)
        // ─────────────────────────────────────────────────────────────
        $schoolDays = [];
        $cursor = Carbon::now()->subDay();
        while (count($schoolDays) < 20) {
            if ($cursor->isWeekday()) {
                $schoolDays[] = $cursor->toDateString();
            }
            $cursor->subDay();
        }

        $attCount = 0;
        foreach ($teacherModels as $tm) {
            $teacher = $tm['user'];
            $def     = $tm['def'];

            foreach ($def['class_names'] as $cn) {
                $classModel = $classes->get($cn);
                if (!$classModel) continue;

                $classStudents = array_filter(
                    $allStudents, fn($s) => $s['class']->id === $classModel->id
                );

                foreach ($classStudents as $s) {
                    foreach ($schoolDays as $day) {
                        $r   = rand(1, 10);
                        $att = match (true) {
                            $r <= 7 => 'present',
                            $r <= 9 => 'absent',
                            default => 'late',
                        };
                        try {
                            Attendance::firstOrCreate(
                                ['student_id' => $s['model']->id, 'date' => $day],
                                [
                                    'class_id'  => $classModel->id,
                                    'term_id'   => $currentTerm->id,
                                    'status'    => $att,
                                    'marked_by' => $teacher->id,
                                ]
                            );
                            $attCount++;
                        } catch (\Exception) {
                            // duplicate — skip
                        }
                    }
                }
            }
        }

        $this->command->info("✓ {$attCount} attendance records seeded.");

        // ─────────────────────────────────────────────────────────────
        // 7. ANNOUNCEMENTS
        // ─────────────────────────────────────────────────────────────
        $announcementData = [
            [
                'title' => 'Welcome Back — First Term 2024/2025',
                'body'  => 'We warmly welcome all students and staff back to school. First term activities begin on Monday 9th September 2024. Let us all strive for excellence this new academic session. Parents are encouraged to check the portal regularly for updates.',
            ],
            [
                'title' => 'Mid-Term Break Notice',
                'body'  => 'Mid-term break will be observed from Monday 21st to Friday 25th October 2024. All students are expected to resume on Monday 28th October 2024. Parents and guardians are notified accordingly.',
            ],
            [
                'title' => 'Score Submission Deadline — 15th November',
                'body'  => 'All subject teachers are reminded to submit their first-term CA scores on the portal not later than Friday 15th November 2024. Scores submitted after this date will be treated as incomplete. Please contact the ICT desk for assistance.',
            ],
            [
                'title' => 'End-of-Term Examination Timetable Released',
                'body'  => 'The first-term examination timetable is now available on the school notice board and portal. All students must collect their examination cards from the school office before the commencement of examinations. Examinations begin Monday 2nd December 2024.',
            ],
            [
                'title' => 'Compulsory Staff Meeting — Monday 4th November',
                'body'  => 'There will be a compulsory staff meeting on Monday 4th November 2024 at 7:30am in the school hall. All teaching and non-teaching staff must attend. Items on the agenda include end-of-term preparations and the new portal workflow.',
            ],
            [
                'title' => 'Inter-House Sports — Save the Date',
                'body'  => 'The Annual Divine Royal Inter-House Sports Competition is scheduled for Saturday 23rd November 2024 at the school sports field. All students are expected to participate. House captains should submit team lists to the sports master by 8th November.',
            ],
        ];

        foreach ($announcementData as $a) {
            Announcement::firstOrCreate(
                ['title' => $a['title']],
                [
                    'body'      => $a['body'],
                    'posted_by' => $adminUser?->id ?? User::first()->id,
                    'target'    => 'all',
                ]
            );
        }

        $this->command->info('✓ ' . count($announcementData) . ' announcements seeded.');

        // ─────────────────────────────────────────────────────────────
        // 8. CONTACT MESSAGES
        // ─────────────────────────────────────────────────────────────
        $msgData = [
            [
                'name'    => 'Olusegun Adebayo',
                'email'   => 'sadebayo@gmail.com',
                'subject' => 'Admission Enquiry for 2025/2026 Session',
                'message' => "Good day, I would like to know if admissions are currently open for JSS1 for the 2025/2026 academic session. My son is 11 years old and just completed primary six. Please advise on the admission requirements, entrance examination dates, and school fees structure. Thank you.",
                'is_read' => false,
            ],
            [
                'name'    => 'Mrs. Blessing Okafor',
                'email'   => 'b.okafor@yahoo.com',
                'subject' => 'Result Sheet Not Received — JSS2 Daughter',
                'message' => "Good afternoon. My daughter Chidinma Okafor in JSS2B has not yet received her second term result sheet. I visited the school last week but was informed the form teacher was unavailable. Could you please look into this and let me know when I can collect it? Thank you.",
                'is_read' => false,
            ],
            [
                'name'    => 'Emmanuel Nzeka',
                'email'   => 'enmannzeka@gmail.com',
                'subject' => 'School Fee Payment Plan Request',
                'message' => "I am the parent of Tobechukwu Nzeka in SS2A. Due to current economic challenges I am respectfully requesting a payment plan for the first term school fees. I can make an initial payment of 50% immediately and clear the balance before mid-term break. I hope the school management will consider this in good faith. Thank you.",
                'is_read' => false,
            ],
            [
                'name'    => 'Mr. Chukwudi Mba',
                'email'   => 'cmba@outlook.com',
                'subject' => 'Complaint: Broken Windows in JSS3B',
                'message' => "I visited the school recently and observed that several windows in the JSS3B classroom are broken. This exposes the students to cold and rain. I kindly request that maintenance be carried out urgently for the comfort and wellbeing of our children.",
                'is_read' => true,
            ],
            [
                'name'    => 'Adaora Obiechina',
                'email'   => 'aobiechina@gmail.com',
                'subject' => 'Application for Teaching Position',
                'message' => "Dear Administrator, I am a graduate of Mathematics Education with five years of secondary school teaching experience. I am interested in any available teaching position at Divine Royal International College. Please advise on your recruitment process or how I may forward my CV. Thank you.",
                'is_read' => true,
            ],
            [
                'name'    => 'Nkechi Uzoh',
                'email'   => 'nkechiuzoh@gmail.com',
                'subject' => 'Appreciation — Academic Improvement of My Son',
                'message' => "I wish to sincerely commend the management and teaching staff of Divine Royal International College for the remarkable improvement in my son Somtochukwu's academic performance this term. His results have been outstanding and he is very motivated. God bless this great school.",
                'is_read' => true,
            ],
            [
                'name'    => 'Chief Innocent Okoro',
                'email'   => 'chiefokoro@royalbiz.com',
                'subject' => 'Partnership & Scholarship Proposal',
                'message' => "On behalf of Okoro Foundation, I wish to explore a partnership opportunity with your school to sponsor three outstanding indigent students annually through a full scholarship. We believe in education as a tool for community development. Please let us know the appropriate channel to formalise this proposal.",
                'is_read' => false,
            ],
            [
                'name'    => 'Mrs. Roseline Dike',
                'email'   => 'roselined@gmail.com',
                'subject' => 'Transfer Letter Request',
                'message' => "Good day, we are relocating to Lagos State and I would like to request a transfer letter and academic records for my daughter Precious Dike who is currently in SS1B. Please let me know what documents I need to provide and how long this process will take.",
                'is_read' => false,
            ],
        ];

        foreach ($msgData as $m) {
            ContactMessage::firstOrCreate(
                ['email' => $m['email'], 'subject' => $m['subject']],
                $m
            );
        }

        $this->command->info('✓ ' . count($msgData) . ' contact messages seeded (' .
            collect($msgData)->where('is_read', false)->count() . ' unread).');

        // ─────────────────────────────────────────────────────────────
        // 9. ACTIVITY LOGS
        // ─────────────────────────────────────────────────────────────
        $actorId = $adminUser?->id ?? User::first()->id;

        $logs = [
            ['action' => 'login',                'description' => 'User logged in',                                              'days_ago' => 1],
            ['action' => 'admin_create_teacher', 'description' => 'Created teacher account: Mr. Emeka Okonkwo (emeka.okonkwo@royalcollege.edu.ng)', 'days_ago' => 3],
            ['action' => 'admin_create_teacher', 'description' => 'Created teacher account: Mrs. Adaeze Nnadi (adaeze.nnadi@royalcollege.edu.ng)',   'days_ago' => 3],
            ['action' => 'admin_create_teacher', 'description' => 'Created teacher account: Mrs. Ngozi Obi (ngozi.obi@royalcollege.edu.ng)',         'days_ago' => 4],
            ['action' => 'admin_create_student', 'description' => 'Created student: Chukwuemeka Okonkwo (DRIC/2024/0001)',       'days_ago' => 5],
            ['action' => 'admin_update_settings','description' => 'Updated school settings',                                     'days_ago' => 6],
            ['action' => 'admin_create_grade',   'description' => 'Created grade scale: A1',                                     'days_ago' => 7],
            ['action' => 'teacher_submit_scores','description' => 'Submitted 8 score(s) for class JSS1A, subject English Language, term First Term 2024/2025', 'days_ago' => 10],
            ['action' => 'teacher_submit_scores','description' => 'Submitted 8 score(s) for class SS2A, subject Physics, term First Term 2024/2025',            'days_ago' => 11],
            ['action' => 'admin_approve_score',  'description' => 'Approved score: Adaeze Okonkwo — Mathematics (JSS2A)',         'days_ago' => 12],
            ['action' => 'admin_approve_score',  'description' => 'Approved score: Obinna Eze — English Language (SS1A)',          'days_ago' => 12],
            ['action' => 'admin_lock_score',     'description' => 'Locked score: Ngozi Igwe — Chemistry (SS3A)',                  'days_ago' => 14],
            ['action' => 'admin_create_announcement', 'description' => 'Posted announcement: Welcome Back — First Term 2024/2025', 'days_ago' => 30],
            ['action' => 'login',                'description' => 'User logged in',                                              'days_ago' => 2],
            ['action' => 'logout',               'description' => 'User logged out',                                             'days_ago' => 2],
        ];

        foreach ($logs as $log) {
            ActivityLog::create([
                'user_id'     => $actorId,
                'action'      => $log['action'],
                'description' => $log['description'],
                'ip_address'  => '127.0.0.1',
                'created_at'  => Carbon::now()->subDays($log['days_ago'])->subMinutes(rand(0, 59)),
            ]);
        }

        $this->command->info('✓ ' . count($logs) . ' activity log entries seeded.');
        $this->command->newLine();
        $this->command->info('═══════════════════════════════════════════════');
        $this->command->info('  Test data seeding complete!');
        $this->command->info('  Teachers  : ' . count($teacherModels) . ' (password: password)');
        $this->command->info('  Students  : ' . count($allStudents));
        $this->command->info('  Scores    : ' . $scoreCount);
        $this->command->info('  Attendance: ' . $attCount . ' records');
        $this->command->info('  Terms     : 4');
        $this->command->info('  Messages  : ' . count($msgData));
        $this->command->info('═══════════════════════════════════════════════');
    }
}
