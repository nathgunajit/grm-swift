@extends('layouts.public')
@section('title', 'Resources — SWIFT GRM Portal')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-10">
    <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2 mb-2"><x-icon name="folder" class="w-6 h-6 text-brand-600" /> Resources &amp; IEC Materials</h1>
    <p class="text-slate-500 mb-6">Reference documents and information materials on the SWIFT Grievance Redressal Mechanism.</p>

    <div class="grid gap-5 sm:grid-cols-3">
        @foreach ([
            ['FINAL GRM Manual.pdf', 'GRM Manual', 'Complete Grievance Redressal Mechanism manual with committee structure, protocol and annexures.'],
            ['GRM.docx', 'Application & Form Specification', 'User types, grievance form fields and module structure.'],
            ['GRM Pages.xlsx', 'Portal Page List', 'Web portal and admin panel page structure.'],
        ] as $doc)
            <div class="card card-pad">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-brand-100 text-brand-600"><x-icon name="folder" class="w-6 h-6" /></div>
                <h3 class="mt-3 font-semibold text-slate-800">{{ $doc[1] }}</h3>
                <p class="mt-1 text-sm text-slate-500">{{ $doc[2] }}</p>
                <a href="{{ route('resources.download', $doc[0]) }}" class="btn btn-sm btn-outline mt-4"><x-icon name="download" class="w-4 h-4" /> Download</a>
            </div>
        @endforeach
    </div>

    <div class="card card-pad mt-6">
        <h3 class="font-semibold text-brand-700">Your Voice Matters</h3>
        <ul class="mt-2 text-sm text-slate-500 space-y-1 list-disc list-inside">
            <li>If you have any complaint, problem or suggestion about SWIFT Project activities — please tell us.</li>
            <li>Your complaint will be recorded and addressed in a fair and time-bound manner.</li>
            <li>No fee is required. Complaints can be given by anyone, including anonymously.</li>
            <li>Submit via the Beel Animator / BDC member, a written application, the grievance register, complaint box, phone, or this online portal.</li>
        </ul>
    </div>
</div>
@endsection
