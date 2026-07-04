@props(['status'])
@php
$map = [
    'registered' => ['secondary', 'Registered'],
    'under_review' => ['primary', 'Under Review'],
    'escalated' => ['warning text-dark', 'Escalated'],
    'resolved' => ['success', 'Resolved'],
    'closed' => ['dark', 'Closed'],
    'reopened' => ['danger', 'Reopened'],
];
[$class, $label] = $map[$status] ?? ['secondary', ucfirst($status)];
@endphp
<span {{ $attributes->merge(['class' => "badge bg-$class"]) }}>{{ $label }}</span>
