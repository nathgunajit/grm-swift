@extends('layouts.admin')
@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
<p class="text-muted">Welcome, {{ $user->name }}. Showing grievances within your jurisdiction ({{ $user->userType?->name }}).</p>

<div class="row g-3 mb-4">
    @foreach ([
        ['Total', $stats['total'], 'primary', 'bi-inbox'],
        ['Registered', $stats['registered'], 'secondary', 'bi-file-earmark'],
        ['Under Review', $stats['under_review'], 'info', 'bi-hourglass-split'],
        ['Escalated', $stats['escalated'], 'warning', 'bi-arrow-up-circle'],
        ['Resolved', $stats['resolved'], 'success', 'bi-check-circle'],
        ['Overdue', $stats['overdue'], 'danger', 'bi-clock-history'],
    ] as $card)
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="h3 mb-0 text-{{ $card[2] }}">{{ $card[1] }}</div>
                            <small class="text-muted">{{ $card[0] }}</small>
                        </div>
                        <i class="bi {{ $card[3] }} text-{{ $card[2] }} h4"></i>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

@if ($stats['sensitive'] > 0)
    <div class="alert alert-danger"><i class="bi bi-shield-lock"></i> <strong>{{ $stats['sensitive'] }}</strong> open sensitive case(s) (GBV/SEA-SH or misconduct) require priority confidential handling.</div>
@endif

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clock-history text-grm"></i> Recent Grievances</span>
        <a href="{{ route('admin.grievances.index') }}" class="btn btn-sm btn-outline-secondary">View all</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>Tracking ID</th><th>Category</th><th>Beel</th><th>Level</th><th>Status</th><th>Due</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($recent as $g)
                    <tr>
                        <td class="fw-semibold">{{ $g->tracking_id }}
                            @if ($g->is_sensitive)<i class="bi bi-shield-lock text-danger" title="Sensitive"></i>@endif
                        </td>
                        <td class="small">{{ $g->category?->name ?? '—' }}</td>
                        <td class="small">{{ $g->beel?->name ?? '—' }}</td>
                        <td><span class="badge bg-light text-dark border">L{{ $g->current_level }}</span></td>
                        <td><x-status-badge :status="$g->status" /></td>
                        <td class="small {{ $g->isOverdue() ? 'text-danger fw-semibold' : 'text-muted' }}">
                            {{ optional($g->due_at)->format('d M Y') ?? '—' }}
                        </td>
                        <td><a href="{{ route('admin.grievances.show', $g) }}" class="btn btn-sm btn-grm"><i class="bi bi-eye"></i></a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No grievances in your jurisdiction yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
