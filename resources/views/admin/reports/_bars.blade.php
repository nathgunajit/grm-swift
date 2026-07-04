@php $max = collect($data)->max() ?: 1; @endphp
@forelse ($data as $label => $value)
    <div class="mb-2">
        <div class="d-flex justify-content-between small"><span>{{ $label }}</span><span class="fw-semibold">{{ $value }}</span></div>
        <div class="progress" style="height:8px;">
            <div class="progress-bar bg-{{ $color }}" style="width: {{ round($value / $max * 100) }}%"></div>
        </div>
    </div>
@empty
    <p class="text-muted small mb-0">No data.</p>
@endforelse
