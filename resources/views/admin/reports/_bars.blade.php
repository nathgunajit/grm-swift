@php $max = collect($data)->max() ?: 1; @endphp
@forelse ($data as $label => $value)
    <div class="mb-3">
        <div class="flex justify-between text-sm mb-1"><span class="text-slate-600">{{ $label }}</span><span class="font-semibold text-slate-800">{{ $value }}</span></div>
        <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
            <div class="h-full rounded-full {{ $color }}" style="width: {{ round($value / $max * 100) }}%"></div>
        </div>
    </div>
@empty
    <p class="text-sm text-slate-400">No data.</p>
@endforelse
