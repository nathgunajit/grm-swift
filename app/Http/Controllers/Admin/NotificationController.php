<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsLog;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()->paginate(20);

        // Demo SMS gateway log — Super Admin / PMU Admin only.
        $smsLogs = in_array($request->user()->role(), ['super_admin', 'pmu_admin'], true)
            ? SmsLog::latest()->limit(50)->get()
            : collect();

        return view('admin.notifications.index', compact('notifications', 'smsLogs'));
    }

    /** Mark one notification read and jump to the grievance it references. */
    public function read(Request $request, string $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $url = $notification->data['url'] ?? route('admin.notifications.index');

        return redirect($url);
    }

    public function readAll(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }
}
