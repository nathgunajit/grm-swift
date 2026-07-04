@extends('layouts.admin')
@section('title', 'Grievances')
@section('heading', 'Grievances')

@section('content')
<form method="GET" class="card card-body shadow-sm mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label small mb-1">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Tracking ID / name / mobile">
        </div>
        <div class="col-md-2">
            <label class="form-label small mb-1">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">All</option>
                @foreach (\App\Models\Grievance::STATUS_LABELS as $k => $v)
                    <option value="{{ $k }}" @selected(request('status')===$k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small mb-1">Level</label>
            <select name="level" class="form-select form-select-sm">
                <option value="">All</option>
                @for ($i = 1; $i <= 3; $i++)<option value="{{ $i }}" @selected(request('level')==$i)>Level {{ $i }}</option>@endfor
            </select>
        </div>
        <div class="col-md-2">
            <div class="form-check mt-4">
                <input class="form-check-input" type="checkbox" name="overdue" value="1" id="ov" @checked(request('overdue'))>
                <label class="form-check-label small" for="ov">Overdue only</label>
            </div>
        </div>
        <div class="col-md-3 text-end">
            <button class="btn btn-sm btn-grm"><i class="bi bi-funnel"></i> Filter</button>
            <a href="{{ route('admin.grievances.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
        </div>
    </div>
</form>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>Tracking ID</th><th>Complainant</th><th>Category</th><th>Beel</th><th>Level</th><th>Status</th><th>Due</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($grievances as $g)
                    <tr>
                        <td class="fw-semibold">{{ $g->tracking_id }}
                            @if ($g->is_sensitive)<i class="bi bi-shield-lock text-danger" title="Sensitive"></i>@endif
                        </td>
                        <td class="small">{{ $g->is_anonymous ? 'Anonymous' : ($g->name ?? '—') }}</td>
                        <td class="small">{{ $g->category?->name ?? '—' }}</td>
                        <td class="small">{{ $g->beel?->name ?? '—' }}</td>
                        <td><span class="badge bg-light text-dark border">L{{ $g->current_level }}</span></td>
                        <td><x-status-badge :status="$g->status" /></td>
                        <td class="small {{ $g->isOverdue() ? 'text-danger fw-semibold' : 'text-muted' }}">{{ optional($g->due_at)->format('d M Y') ?? '—' }}</td>
                        <td><a href="{{ route('admin.grievances.show', $g) }}" class="btn btn-sm btn-grm"><i class="bi bi-eye"></i></a></td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No grievances found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $grievances->links() }}</div>
@endsection
