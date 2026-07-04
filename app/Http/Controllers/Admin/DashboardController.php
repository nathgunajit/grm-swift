<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grievance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $base = fn () => Grievance::query()->visibleTo($user);

        $stats = [
            'total' => $base()->count(),
            'registered' => $base()->where('status', 'registered')->count(),
            'under_review' => $base()->where('status', 'under_review')->count(),
            'escalated' => $base()->where('status', 'escalated')->count(),
            'resolved' => $base()->whereIn('status', ['resolved', 'closed'])->count(),
            'overdue' => $base()->whereNotIn('status', ['resolved', 'closed'])
                ->whereNotNull('due_at')->where('due_at', '<', now())->count(),
            'sensitive' => $base()->where('is_sensitive', true)->whereNotIn('status', ['resolved', 'closed'])->count(),
        ];

        $recent = $base()->with(['category', 'beel'])->latest()->limit(10)->get();

        // Chart data
        $statusChart = [
            'labels' => ['Registered', 'Under Review', 'Escalated', 'Resolved', 'Closed'],
            'data' => [
                $stats['registered'],
                $stats['under_review'],
                $stats['escalated'],
                $base()->where('status', 'resolved')->count(),
                $base()->where('status', 'closed')->count(),
            ],
        ];

        $levelChart = [
            'labels' => ['Level I', 'Level II', 'Level III'],
            'data' => [
                $base()->where('current_level', 1)->count(),
                $base()->where('current_level', 2)->count(),
                $base()->where('current_level', 3)->count(),
            ],
        ];

        // Last 6 months: registered vs resolved
        $months = [];
        $registeredSeries = [];
        $resolvedSeries = [];
        for ($m = 5; $m >= 0; $m--) {
            $start = Carbon::now()->startOfMonth()->subMonths($m);
            $end = (clone $start)->endOfMonth();
            $months[] = $start->format('M');
            $registeredSeries[] = $base()->whereBetween('created_at', [$start, $end])->count();
            $resolvedSeries[] = $base()->whereIn('status', ['resolved', 'closed'])
                ->whereBetween('resolved_at', [$start, $end])->count();
        }
        $trendChart = [
            'labels' => $months,
            'registered' => $registeredSeries,
            'resolved' => $resolvedSeries,
        ];

        return view('admin.dashboard', compact('stats', 'recent', 'user', 'statusChart', 'levelChart', 'trendChart'));
    }
}
