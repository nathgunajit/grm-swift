@extends('layouts.admin')
@section('title', 'Grievances')
@section('heading', 'Grievances')

@section('content')
<form method="GET" class="card card-pad mb-4">
    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5 items-end">
        <div class="lg:col-span-2">
            <label class="label">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" class="input" placeholder="Tracking ID / name / mobile">
        </div>
        <div>
            <label class="label">Status</label>
            <select name="status" class="input">
                <option value="">All</option>
                @foreach (\App\Models\Grievance::STATUS_LABELS as $k => $v)<option value="{{ $k }}" @selected(request('status')===$k)>{{ $v }}</option>@endforeach
            </select>
        </div>
        <div>
            <label class="label">Level</label>
            <select name="level" class="input">
                <option value="">All</option>
                @for ($i = 1; $i <= 3; $i++)<option value="{{ $i }}" @selected(request('level')==$i)>Level {{ $i }}</option>@endfor
            </select>
        </div>
        <div class="flex items-center gap-3">
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="overdue" value="1" @checked(request('overdue')) class="h-4 w-4 rounded text-brand-600"> Overdue
            </label>
        </div>
    </div>
    <div class="flex justify-end gap-2 mt-3">
        <a href="{{ route('admin.grievances.index') }}" class="btn btn-sm btn-outline">Reset</a>
        <button class="btn btn-sm btn-primary"><x-icon name="funnel" class="w-4 h-4" /> Filter</button>
    </div>
</form>

<div class="card overflow-x-auto">
    <table class="table-grm">
        <thead><tr><th>Tracking ID</th><th>Complainant</th><th>Category</th><th>Beel</th><th>Level</th><th>Status</th><th>Due</th><th></th></tr></thead>
        <tbody>
            @forelse ($grievances as $g)
                <tr>
                    <td class="font-medium text-slate-800">{{ $g->tracking_id }} @if ($g->is_sensitive)<x-icon name="shield-lock" class="inline w-4 h-4 text-rose-500" />@endif</td>
                    <td class="text-slate-500">{{ $g->is_anonymous ? 'Anonymous' : ($g->name ?? '—') }}</td>
                    <td class="text-slate-500">{{ $g->category?->name ?? '—' }}</td>
                    <td class="text-slate-500">{{ $g->beel?->name ?? '—' }}</td>
                    <td><span class="badge bg-slate-100 text-slate-600">L{{ $g->current_level }}</span></td>
                    <td><x-status-badge :status="$g->status" /></td>
                    <td>
                        @php [$dueClass, $dueLabel] = $g->dueBadge(); @endphp
                        <div class="flex items-center gap-2">
                            <span class="badge {{ $dueClass }}">{{ $dueLabel }}</span>
                            <span class="text-xs text-slate-400">{{ optional($g->due_at)->format('d M Y') }}</span>
                        </div>
                    </td>
                    <td><a href="{{ route('admin.grievances.show', $g) }}" class="btn btn-sm btn-primary"><x-icon name="eye" class="w-4 h-4" /></a></td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-slate-400 py-8">No grievances found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3 flex flex-wrap items-center gap-4 text-xs text-slate-500">
    <span class="font-medium text-slate-600">Due:</span>
    <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span> On time (&gt;3 days)</span>
    <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span> Approaching (≤3 days)</span>
    <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span> Overdue</span>
    <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-slate-400"></span> Resolved / Done</span>
</div>
<div class="mt-4">{{ $grievances->links() }}</div>
@endsection
