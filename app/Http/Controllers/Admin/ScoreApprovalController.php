<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerm;
use App\Models\ActivityLog;
use App\Models\SchoolClass;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ScoreApprovalController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'status'   => 'nullable|in:draft,submitted,approved,locked,returned',
            'class_id' => 'nullable|integer|exists:classes,id',
            'term_id'  => 'nullable|integer|exists:academic_terms,id',
            'search'   => 'nullable|string|max:100',
        ]);

        // ── Statistics (scoped to term filter if set) ──────────────────
        $statBase = Score::query();
        if ($request->filled('term_id')) {
            $statBase->where('term_id', $request->term_id);
        } elseif ($currentTerm = AcademicTerm::where('is_current', true)->first()) {
            $statBase->where('term_id', $currentTerm->id);
        }
        $stats = (clone $statBase)->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        $statsAll = array_sum($stats);

        // ── Filtered list ──────────────────────────────────────────────
        $query = Score::with(['student', 'subject', 'schoolClass', 'term', 'submittedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'submitted');
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        if ($request->filled('term_id')) {
            $query->where('term_id', $request->term_id);
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sub) use ($q) {
                $sub->whereHas('student', fn($s) => $s->where('first_name', 'like', "%{$q}%")
                                                       ->orWhere('last_name',  'like', "%{$q}%")
                                                       ->orWhere('admission_number', 'like', "%{$q}%"))
                    ->orWhereHas('subject', fn($s) => $s->where('name', 'like', "%{$q}%"))
                    ->orWhereHas('submittedBy', fn($s) => $s->where('name', 'like', "%{$q}%"));
            });
        }

        $scores  = $query->latest('submitted_at')->paginate(50)->withQueryString();
        $classes = SchoolClass::orderBy('level')->orderBy('name')->get();
        $terms   = AcademicTerm::orderByDesc('academic_year')->get();

        return view('admin.scores.index', compact('scores', 'classes', 'terms', 'stats', 'statsAll'));
    }

    public function show(Score $score)
    {
        $score->load(['student', 'subject', 'schoolClass', 'term', 'submittedBy', 'reviewedBy']);
        return view('admin.scores.show', compact('score'));
    }

    public function approve(Request $request, Score $score)
    {
        abort_if(!auth()->user()->isPrincipal(), 403, 'Only the principal can approve scores.');
        abort_if(!in_array($score->status, ['submitted', 'returned']), 422, 'Score cannot be approved in its current state.');

        $score->update([
            'status'      => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'remarks'     => $request->input('remarks', $score->remarks),
        ]);

        ActivityLog::record('admin_approve_score',
            "Approved score: {$score->student->full_name} — {$score->subject->name} ({$score->schoolClass->name})");

        return back()->with('success', 'Score approved.');
    }

    public function return(Request $request, Score $score)
    {
        abort_if(!auth()->user()->isPrincipal(), 403, 'Only the principal can return scores.');
        abort_if($score->status !== 'submitted', 422, 'Only submitted scores can be returned.');

        $request->validate(['remarks' => 'required|string|max:500']);

        $score->update([
            'status'      => 'returned',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'remarks'     => $request->remarks,
        ]);

        ActivityLog::record('admin_return_score',
            "Returned score for revision: {$score->student->full_name} — {$score->subject->name}");

        return back()->with('success', 'Score returned to teacher for revision.');
    }

    public function lock(Score $score)
    {
        abort_if(!auth()->user()->isPrincipal(), 403, 'Only the principal can lock scores.');
        abort_if($score->status !== 'approved', 422, 'Only approved scores can be locked.');

        $score->update(['status' => 'locked']);

        ActivityLog::record('admin_lock_score',
            "Locked score: {$score->student->full_name} — {$score->subject->name}");

        return back()->with('success', 'Score locked.');
    }

    public function unlock(Score $score)
    {
        abort_if(!auth()->user()->isPrincipal(), 403, 'Only the principal can unlock scores.');
        abort_if($score->status !== 'locked', 422, 'Only locked scores can be unlocked.');

        $score->update(['status' => 'approved']);

        ActivityLog::record('admin_unlock_score',
            "Unlocked score: {$score->student->full_name} — {$score->subject->name}");

        return back()->with('success', 'Score unlocked.');
    }

    public function bulkApprove(Request $request)
    {
        abort_if(!auth()->user()->isPrincipal(), 403, 'Only the principal can bulk-approve scores.');

        $request->validate([
            'score_ids'   => 'required|array|min:1',
            'score_ids.*' => 'integer|exists:scores,id',
        ]);

        $scores = Score::whereIn('id', $request->score_ids)
            ->whereIn('status', ['submitted', 'returned'])
            ->get();

        if ($scores->isEmpty()) {
            return back()->with('error', 'No eligible scores selected. Only submitted or returned scores can be approved.');
        }

        Score::whereIn('id', $scores->pluck('id'))->update([
            'status'      => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        ActivityLog::record('admin_bulk_approve_scores',
            "Bulk approved {$scores->count()} score(s).");

        return back()->with('success', "{$scores->count()} score(s) approved successfully.");
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'score_ids'   => 'required|array|min:1',
            'score_ids.*' => 'integer|exists:scores,id',
            'password'    => 'required|string',
        ]);

        if (!Hash::check($request->password, auth()->user()->password)) {
            return back()->withErrors(['password' => 'Incorrect password. Deletion cancelled.'])->withInput();
        }

        $count = Score::whereIn('id', $request->score_ids)->count();
        Score::whereIn('id', $request->score_ids)->delete();

        ActivityLog::record('admin_bulk_delete_scores',
            "Bulk deleted {$count} score record(s).");

        return back()->with('success', "{$count} score record(s) deleted.");
    }
}
