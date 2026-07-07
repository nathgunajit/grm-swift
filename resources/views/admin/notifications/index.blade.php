@extends('layouts.admin')
@section('title', 'Notifications')
@section('heading', 'Notifications')

@section('content')
<div class="space-y-6" x-data="{ tab: 'inbox' }">
    <div class="flex items-center justify-between">
        <div class="flex gap-1 rounded-lg bg-slate-100 p-1 text-sm">
            <button @click="tab='inbox'" :class="tab==='inbox' ? 'bg-white shadow-sm text-brand-700' : 'text-slate-500'" class="rounded-md px-4 py-1.5 font-medium">Inbox</button>
            @if ($smsLogs->isNotEmpty() || in_array(auth()->user()->role(), ['super_admin','pmu_admin']))
                <button @click="tab='sms'" :class="tab==='sms' ? 'bg-white shadow-sm text-brand-700' : 'text-slate-500'" class="rounded-md px-4 py-1.5 font-medium">Demo SMS Log</button>
            @endif
        </div>
        @if (auth()->user()->unreadNotifications->count())
            <form method="POST" action="{{ route('admin.notifications.readAll') }}">
                @csrf
                <button class="btn btn-outline text-sm"><x-icon name="check" class="w-4 h-4" /> Mark all read</button>
            </form>
        @endif
    </div>

    {{-- Inbox --}}
    <div x-show="tab==='inbox'" class="card divide-y divide-slate-100">
        @forelse ($notifications as $n)
            <a href="{{ route('admin.notifications.read', $n->id) }}" class="flex items-start gap-3 px-5 py-4 hover:bg-slate-50 {{ $n->read_at ? '' : 'bg-brand-50/40' }}">
                <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-100 text-brand-700"><x-icon name="{{ $n->data['icon'] ?? 'inbox' }}" class="w-5 h-5" /></span>
                <span class="min-w-0 flex-1">
                    <span class="flex items-center gap-2">
                        <span class="font-medium text-slate-800">{{ $n->data['title'] ?? 'Notification' }}</span>
                        @if (! $n->read_at)<span class="h-2 w-2 rounded-full bg-rose-500"></span>@endif
                    </span>
                    <span class="block text-sm text-slate-500">{{ $n->data['body'] ?? '' }}</span>
                    <span class="block text-xs text-slate-400 mt-1">{{ $n->created_at->diffForHumans() }} · {{ $n->data['tracking_id'] ?? '' }}</span>
                </span>
            </a>
        @empty
            <p class="px-5 py-12 text-center text-slate-400">No notifications yet.</p>
        @endforelse
    </div>
    <div x-show="tab==='inbox'">{{ $notifications->links() }}</div>

    {{-- Demo SMS log --}}
    <div x-show="tab==='sms'" x-cloak class="card overflow-hidden">
        <div class="border-b border-slate-100 px-5 py-3">
            <p class="text-sm text-slate-500">Every message the <strong>demo SMS gateway</strong> would send is logged here (no real SMS is dispatched). Emails in demo mode are written to <code>storage/logs/laravel.log</code>.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="table-grm min-w-full text-sm">
                <thead><tr><th>Time</th><th>Mobile</th><th>Purpose</th><th>Message</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse ($smsLogs as $log)
                        <tr>
                            <td class="whitespace-nowrap text-slate-500">{{ $log->created_at->format('d M, h:i A') }}</td>
                            <td class="font-mono">{{ $log->mobile }}</td>
                            <td><span class="badge bg-slate-100 text-slate-600">{{ $log->purpose ?? '—' }}</span></td>
                            <td class="max-w-md text-slate-600">{{ $log->message }}</td>
                            <td><span class="badge bg-emerald-100 text-emerald-700">{{ $log->status }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-10 text-center text-slate-400">No demo SMS sent yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
