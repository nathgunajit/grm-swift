@extends('layouts.admin')
@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
<p class="text-slate-500 mb-5">Welcome, <span class="font-medium text-slate-700">{{ $user->name }}</span>. Showing grievances within your jurisdiction ({{ $user->userType?->name }}).</p>

<div class="grid grid-cols-2 gap-4 sm:grid-cols-3 xl:grid-cols-6 mb-6">
    @php $cards = [
        ['Total', $stats['total'], 'border-brand-600', 'text-brand-600', 'inbox'],
        ['Registered', $stats['registered'], 'border-slate-400', 'text-slate-500', 'inbox'],
        ['Under Review', $stats['under_review'], 'border-sky-500', 'text-sky-500', 'hourglass'],
        ['Escalated', $stats['escalated'], 'border-amber-500', 'text-amber-500', 'arrow-up'],
        ['Resolved', $stats['resolved'], 'border-emerald-500', 'text-emerald-500', 'check-circle'],
        ['Overdue', $stats['overdue'], 'border-rose-500', 'text-rose-500', 'clock'],
    ]; @endphp
    @foreach ($cards as [$label, $value, $border, $text, $icon])
        <div class="stat-card {{ $border }}">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold {{ $text }}">{{ $value }}</div>
                    <div class="text-xs text-slate-500 mt-0.5">{{ $label }}</div>
                </div>
                <x-icon name="{{ $icon }}" class="w-6 h-6 {{ $text }} opacity-70" />
            </div>
        </div>
    @endforeach
</div>

@if ($stats['sensitive'] > 0)
    <div class="mb-6 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 text-sm flex items-center gap-2">
        <x-icon name="shield-lock" class="w-5 h-5" /> <strong>{{ $stats['sensitive'] }}</strong> open sensitive case(s) (GBV/SEA-SH or misconduct) require priority confidential handling.
    </div>
@endif

<div class="grid gap-5 lg:grid-cols-3 mb-6">
    <div class="card card-pad">
        <h3 class="font-semibold text-slate-800 mb-3">By Status</h3>
        <canvas id="statusChart" height="200"></canvas>
    </div>
    <div class="card card-pad">
        <h3 class="font-semibold text-slate-800 mb-3">By Level</h3>
        <canvas id="levelChart" height="200"></canvas>
    </div>
    <div class="card card-pad">
        <h3 class="font-semibold text-slate-800 mb-3">Trend (6 months)</h3>
        <canvas id="trendChart" height="200"></canvas>
    </div>
</div>

<div class="card">
    <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
        <h3 class="font-semibold text-slate-800">Recent Grievances</h3>
        <a href="{{ route('admin.grievances.index') }}" class="btn btn-sm btn-outline">View all</a>
    </div>
    <div class="overflow-x-auto">
        <table class="table-grm">
            <thead><tr><th>Tracking ID</th><th>Category</th><th>Beel</th><th>Level</th><th>Status</th><th>Due</th><th></th></tr></thead>
            <tbody>
                @forelse ($recent as $g)
                    <tr>
                        <td class="font-medium text-slate-800">{{ $g->tracking_id }} @if ($g->is_sensitive)<x-icon name="shield-lock" class="inline w-4 h-4 text-rose-500" />@endif</td>
                        <td class="text-slate-500">{{ $g->category?->name ?? '—' }}</td>
                        <td class="text-slate-500">{{ $g->beel?->name ?? '—' }}</td>
                        <td><span class="badge bg-slate-100 text-slate-600">L{{ $g->current_level }}</span></td>
                        <td><x-status-badge :status="$g->status" /></td>
                        <td class="{{ $g->isOverdue() ? 'text-rose-600 font-medium' : 'text-slate-500' }}">{{ optional($g->due_at)->format('d M Y') ?? '—' }}</td>
                        <td><a href="{{ route('admin.grievances.show', $g) }}" class="btn btn-sm btn-primary"><x-icon name="eye" class="w-4 h-4" /></a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-slate-400 py-8">No grievances in your jurisdiction yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const brand = '#0b5e4f', sky = '#0ea5e9', amber = '#f59e0b', emerald = '#10b981', slate = '#94a3b8', rose = '#f43f5e';
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: { labels: @json($statusChart['labels']), datasets: [{ data: @json($statusChart['data']), backgroundColor: [slate, sky, amber, emerald, '#334155'] }] },
        options: { plugins: { legend: { position: 'bottom' } }, cutout: '60%' }
    });
    new Chart(document.getElementById('levelChart'), {
        type: 'bar',
        data: { labels: @json($levelChart['labels']), datasets: [{ label: 'Grievances', data: @json($levelChart['data']), backgroundColor: [brand, sky, amber], borderRadius: 6 }] },
        options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
    });
    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: { labels: @json($trendChart['labels']), datasets: [
            { label: 'Registered', data: @json($trendChart['registered']), borderColor: brand, backgroundColor: 'rgba(11,94,79,.1)', fill: true, tension: .35 },
            { label: 'Resolved', data: @json($trendChart['resolved']), borderColor: emerald, backgroundColor: 'rgba(16,185,129,.1)', fill: true, tension: .35 }
        ] },
        options: { plugins: { legend: { position: 'bottom' } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
    });
});
</script>
@endpush
