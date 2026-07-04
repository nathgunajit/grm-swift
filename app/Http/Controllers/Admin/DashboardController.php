<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grievance;
use Illuminate\Http\Request;

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

        return view('admin.dashboard', compact('stats', 'recent', 'user'));
    }
}
