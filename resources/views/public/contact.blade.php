@extends('layouts.public')
@section('title', 'Contact Us — SWIFT GRM Portal')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-10">
    <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2 mb-6"><x-icon name="phone" class="w-6 h-6 text-brand-600" /> Contact Us</h1>
    <div class="grid gap-6 md:grid-cols-2">
        <div class="card card-pad">
            <h3 class="font-semibold text-slate-800">Project Management Unit (PMU)</h3>
            <p class="text-slate-500 mt-1">ARIAS Society — SWIFT Project</p>
            <p class="text-sm text-slate-500 mt-1">Agriculture Complex, Khanapara, G.S. Road, Guwahati-781022, Assam</p>
            <ul class="mt-3 space-y-2 text-sm text-slate-600">
                <li class="flex items-center gap-2"><x-icon name="phone" class="w-4 h-4 text-brand-600" /> 0361-2332004</li>
                <li class="flex items-center gap-2"><x-icon name="envelope" class="w-4 h-4 text-brand-600" /> spd@arias.in</li>
                <li class="flex items-center gap-2"><x-icon name="globe" class="w-4 h-4 text-brand-600" /> www.arias.in</li>
            </ul>
        </div>
        <div class="card card-pad">
            <h3 class="font-semibold text-slate-800">Local Contacts</h3>
            <p class="text-sm text-slate-500 mt-1">For field-level assistance, you may also contact:</p>
            <ul class="mt-3 space-y-2 text-sm text-slate-600">
                <li class="flex items-center gap-2"><x-icon name="user" class="w-4 h-4 text-brand-600" /> Your <strong>Beel Animator</strong></li>
                <li class="flex items-center gap-2"><x-icon name="user" class="w-4 h-4 text-brand-600" /> The <strong>BDC Facilitator</strong></li>
                <li class="flex items-center gap-2"><x-icon name="grid" class="w-4 h-4 text-brand-600" /> The <strong>District Fisheries Development Office (DFDO)</strong></li>
            </ul>
            <a href="{{ route('grievance.create') }}" class="btn btn-primary btn-sm mt-4"><x-icon name="pencil" class="w-4 h-4" /> Register a Grievance Online</a>
        </div>
    </div>
</div>
@endsection
