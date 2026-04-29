<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add SSS-specific score breakdown columns.
     * JSS uses: ca_score (1st CA), ca_score_2 (2nd CA), exam_score, total_score
     * SSS uses: weekly_exercise_1, weekly_exercise_2, take_home, college_quiz,
     *           summary_ca (auto-sum), mid_term, exam_score, total_score
     * Subject master's remarks also added for SSS sheets.
     */
    public function up(): void
    {
        Schema::table('scores', function (Blueprint $table) {
            // JSS 2nd CA (JSS has two separate CAs)
            $table->decimal('ca_score_2', 5, 2)->nullable()->after('ca_score');

            // SSS CA breakdown
            $table->decimal('weekly_exercise_1', 5, 2)->nullable()->after('ca_score_2');
            $table->decimal('weekly_exercise_2', 5, 2)->nullable()->after('weekly_exercise_1');
            $table->decimal('take_home',         5, 2)->nullable()->after('weekly_exercise_2');
            $table->decimal('college_quiz',      5, 2)->nullable()->after('take_home');
            $table->decimal('summary_ca',        5, 2)->nullable()->after('college_quiz');
            $table->decimal('mid_term',          5, 2)->nullable()->after('summary_ca');

            // Per-subject teacher remarks (used in SSS sheet)
            $table->string('subject_remark', 200)->nullable()->after('remarks');
        });
    }

    public function down(): void
    {
        Schema::table('scores', function (Blueprint $table) {
            $table->dropColumn([
                'ca_score_2',
                'weekly_exercise_1',
                'weekly_exercise_2',
                'take_home',
                'college_quiz',
                'summary_ca',
                'mid_term',
                'subject_remark',
            ]);
        });
    }
};
