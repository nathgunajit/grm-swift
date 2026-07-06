<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grievance;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.reports.index', $this->buildReport($request->user()));
    }

    public function exportCsv(Request $request)
    {
        $data = $this->buildReport($request->user());

        $rows = [];
        $rows[] = ['SWIFT GRM — Summary Report', 'Generated '.now()->format('d M Y H:i')];
        $rows[] = [];
        $rows[] = ['Metric', 'Value'];
        $rows[] = ['Total grievances', $data['total']];
        $rows[] = ['Resolved / closed', $data['resolved']];
        $rows[] = ['Resolution rate within SLA (%)', $data['slaRate']];
        $rows[] = ['Average resolution time (days)', $data['avgDays']];
        $rows[] = ['Escalated cases', $data['escalatedCount']];
        $rows[] = [];
        $rows[] = ['Resolution Timeliness', 'Count'];
        $rows[] = ['Resolved on time', $data['onTime']];
        $rows[] = ['Resolved late (delayed)', $data['delayedResolved']];
        $rows[] = ['Open & overdue', $data['openOverdue']];
        $rows[] = [];
        $rows[] = ['By Status', 'Count'];
        foreach ($data['byStatus'] as $k => $v) {
            $rows[] = [Grievance::STATUS_LABELS[$k] ?? $k, $v];
        }
        $rows[] = [];
        $rows[] = ['By Category', 'Count'];
        foreach ($data['byCategory'] as $name => $v) {
            $rows[] = [$name, $v];
        }
        $rows[] = [];
        $rows[] = ['By Level', 'Count'];
        foreach ($data['byLevel'] as $k => $v) {
            $rows[] = ['Level '.$k, $v];
        }
        $rows[] = [];
        $rows[] = ['By District', 'Count'];
        foreach ($data['byDistrict'] as $name => $v) {
            $rows[] = [$name, $v];
        }
        $rows[] = [];
        $rows[] = ['Feedback satisfaction', 'Count'];
        foreach ($data['satisfaction'] as $k => $v) {
            $rows[] = [ucfirst($k), $v];
        }

        $callback = function () use ($rows) {
            $out = fopen('php://output', 'w');
            foreach ($rows as $r) {
                fputcsv($out, $r);
            }
            fclose($out);
        };

        return response()->streamDownload($callback, 'grm-report-'.now()->format('Ymd').'.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->buildReport($request->user());
        $data['statusLabels'] = Grievance::STATUS_LABELS;
        $pdf = Pdf::loadView('pdf.report', $data);

        return $pdf->download('grm-report-'.now()->format('Ymd').'.pdf');
    }

    private function buildReport($user): array
    {
        $base = fn () => Grievance::query()->visibleTo($user);

        $total = $base()->count();
        $resolved = $base()->whereIn('status', ['resolved', 'closed'])->count();
        $escalatedCount = $base()->where('current_level', '>', 1)->count();

        $byStatus = $base()->select('status', DB::raw('count(*) as c'))->groupBy('status')->pluck('c', 'status')->toArray();
        $byLevel = $base()->select('current_level', DB::raw('count(*) as c'))->groupBy('current_level')->pluck('c', 'current_level')->toArray();

        $byCategory = $base()->join('grievance_categories', 'grievances.category_id', '=', 'grievance_categories.id')
            ->select('grievance_categories.name', DB::raw('count(*) as c'))
            ->groupBy('grievance_categories.name')->pluck('c', 'name')->toArray();

        $byDistrict = $base()->join('districts', 'grievances.district_id', '=', 'districts.id')
            ->select('districts.name', DB::raw('count(*) as c'))
            ->groupBy('districts.name')->pluck('c', 'name')->toArray();

        // Resolution within SLA: resolved on or before due date.
        $resolvedRows = $base()->whereIn('status', ['resolved', 'closed'])
            ->whereNotNull('resolved_at')->get(['resolved_at', 'due_at', 'created_at']);
        $onTime = $resolvedRows->filter(fn ($g) => $g->due_at && $g->resolved_at && $g->resolved_at->lte($g->due_at))->count();
        $delayedResolved = $resolvedRows->count() - $onTime;
        $slaRate = $resolvedRows->count() ? round($onTime / $resolvedRows->count() * 100, 1) : 0;

        $avgDays = $resolvedRows->count()
            ? round($resolvedRows->avg(fn ($g) => $g->created_at->diffInDays($g->resolved_at)), 1)
            : 0;

        // Open grievances that are already past their due date.
        $openOverdue = $base()->whereNotIn('status', ['resolved', 'closed'])
            ->whereNotNull('due_at')->where('due_at', '<', now())->count();

        // Delayed grievances list (resolved late or open & overdue) for the report table.
        $delayedList = $base()->with(['category', 'district'])
            ->where(function ($q) {
                $q->where(fn ($w) => $w->whereIn('status', ['resolved', 'closed'])
                    ->whereColumn('resolved_at', '>', 'due_at'))
                  ->orWhere(fn ($w) => $w->whereNotIn('status', ['resolved', 'closed'])
                    ->whereNotNull('due_at')->where('due_at', '<', now()));
            })
            ->latest('due_at')->limit(20)->get();

        $onTimeVsDelayed = [
            'On time' => $onTime,
            'Delayed (resolved late)' => $delayedResolved,
            'Open & overdue' => $openOverdue,
        ];

        $satisfaction = $base()->join('grievance_feedback', 'grievances.id', '=', 'grievance_feedback.grievance_id')
            ->select('grievance_feedback.satisfaction', DB::raw('count(*) as c'))
            ->groupBy('grievance_feedback.satisfaction')->pluck('c', 'satisfaction')->toArray();

        return compact(
            'total', 'resolved', 'escalatedCount', 'byStatus', 'byLevel',
            'byCategory', 'byDistrict', 'slaRate', 'avgDays', 'satisfaction',
            'onTime', 'delayedResolved', 'openOverdue', 'onTimeVsDelayed', 'delayedList'
        );
    }
}
