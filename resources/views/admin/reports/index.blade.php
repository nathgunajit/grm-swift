@extends('layouts.admin')
@section('title', 'Reports')
@section('heading', 'Monitoring &amp; Reports')

@section('content')
<div class="d-flex justify-content-end gap-2 mb-3">
    <a href="{{ route('admin.reports.csv') }}" class="btn btn-sm btn-outline-success"><i class="bi bi-filetype-csv"></i> Export CSV</a>
    <a href="{{ route('admin.reports.pdf') }}" class="btn btn-sm btn-outline-danger"><i class="bi bi-filetype-pdf"></i> Export PDF</a>
</div>

<div class="row g-3 mb-4">
    @foreach ([
        ['Total Grievances', $total, 'primary'],
        ['Resolved / Closed', $resolved, 'success'],
        ['Resolution within SLA', $slaRate.'%', 'info'],
        ['Avg. Resolution Time', $avgDays.' days', 'secondary'],
        ['Escalated Cases', $escalatedCount, 'warning'],
    ] as $c)
        <div class="col-6 col-md">
            <div class="card stat-card shadow-sm h-100"><div class="card-body text-center">
                <div class="h4 mb-0 text-{{ $c[2] }}">{{ $c[1] }}</div><small class="text-muted">{{ $c[0] }}</small>
            </div></div>
        </div>
    @endforeach
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card shadow-sm h-100"><div class="card-header bg-white">By Status</div>
            <div class="card-body">@include('admin.reports._bars', ['data' => collect($byStatus)->mapWithKeys(fn($v,$k)=>[(\App\Models\Grievance::STATUS_LABELS[$k] ?? $k) => $v])->toArray(), 'color' => 'primary'])</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm h-100"><div class="card-header bg-white">By Level</div>
            <div class="card-body">@include('admin.reports._bars', ['data' => collect($byLevel)->mapWithKeys(fn($v,$k)=>['Level '.$k => $v])->toArray(), 'color' => 'warning'])</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm h-100"><div class="card-header bg-white">By Category</div>
            <div class="card-body">@include('admin.reports._bars', ['data' => $byCategory, 'color' => 'success'])</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm h-100"><div class="card-header bg-white">By District</div>
            <div class="card-body">@include('admin.reports._bars', ['data' => $byDistrict, 'color' => 'info'])</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm h-100"><div class="card-header bg-white">Feedback — Satisfaction</div>
            <div class="card-body">
                @if (count($satisfaction))
                    @include('admin.reports._bars', ['data' => collect($satisfaction)->mapWithKeys(fn($v,$k)=>[ucfirst($k) => $v])->toArray(), 'color' => 'secondary'])
                @else
                    <p class="text-muted small mb-0">No feedback recorded yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
