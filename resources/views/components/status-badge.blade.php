@props(['status'])
@php
$map = [
    'registered'   => ['bg-slate-100 text-slate-700', 'Registered'],
    'under_review' => ['bg-sky-100 text-sky-700', 'Under Review'],
    'escalated'    => ['bg-amber-100 text-amber-700', 'Escalated'],
    'resolved'     => ['bg-emerald-100 text-emerald-700', 'Resolved'],
    'closed'       => ['bg-slate-200 text-slate-800', 'Closed'],
    'reopened'     => ['bg-rose-100 text-rose-700', 'Reopened'],
];
[$class, $label] = $map[$status] ?? ['bg-slate-100 text-slate-700', ucfirst($status)];
@endphp
<span {{ $attributes->merge(['class' => "badge $class"]) }}>{{ $label }}</span>
