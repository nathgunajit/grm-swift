@extends('layouts.admin')
@section('title', 'Reports')
@section('heading', 'Monitoring & Reports')

@section('content')
<div class="flex justify-end gap-2 mb-4">
    <a href="{{ route('admin.reports.csv') }}" class="btn btn-sm btn-outline"><x-icon name="download" class="w-4 h-4" /> Export CSV</a>
    <a href="{{ route('admin.reports.pdf') }}" class="btn btn-sm btn-danger"><x-icon name="download" class="w-4 h-4" /> Export PDF</a>
</div>

<div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5 mb-6">
    @php $kpis = [
        ['Total Grievances', $total, 'text-brand-600'],
        ['Resolved / Closed', $resolved, 'text-emerald-600'],
        ['Resolution within SLA', $slaRate.'%', 'text-sky-600'],
        ['Avg. Resolution Time', $avgDays.' days', 'text-slate-600'],
        ['Escalated Cases', $escalatedCount, 'text-amber-600'],
    ]; @endphp
    @foreach ($kpis as [$label, $value, $color])
        <div class="card card-pad text-center">
            <div class="text-2xl font-bold {{ $color }}">{{ $value }}</div>
            <div class="text-xs text-slate-500 mt-1">{{ $label }}</div>
        </div>
    @endforeach
</div>

<div class="grid gap-5 md:grid-cols-2">
    <div class="card card-pad"><h3 class="font-semibold text-slate-800 mb-3">By Status</h3>@include('admin.reports._bars', ['data' => collect($byStatus)->mapWithKeys(fn($v,$k)=>[(\App\Models\Grievance::STATUS_LABELS[$k] ?? $k) => $v])->toArray(), 'color' => 'bg-brand-500'])</div>
    <div class="card card-pad"><h3 class="font-semibold text-slate-800 mb-3">By Level</h3>@include('admin.reports._bars', ['data' => collect($byLevel)->mapWithKeys(fn($v,$k)=>['Level '.$k => $v])->toArray(), 'color' => 'bg-amber-500'])</div>
    <div class="card card-pad"><h3 class="font-semibold text-slate-800 mb-3">By Category</h3>@include('admin.reports._bars', ['data' => $byCategory, 'color' => 'bg-emerald-500'])</div>
    <div class="card card-pad"><h3 class="font-semibold text-slate-800 mb-3">By District</h3>@include('admin.reports._bars', ['data' => $byDistrict, 'color' => 'bg-sky-500'])</div>
    <div class="card card-pad md:col-span-2"><h3 class="font-semibold text-slate-800 mb-3">Feedback — Satisfaction</h3>
        @if (count($satisfaction))
            @include('admin.reports._bars', ['data' => collect($satisfaction)->mapWithKeys(fn($v,$k)=>[ucfirst($k) => $v])->toArray(), 'color' => 'bg-slate-500'])
        @else
            <p class="text-sm text-slate-400">No feedback recorded yet.</p>
        @endif
    </div>
</div>
@endsection
