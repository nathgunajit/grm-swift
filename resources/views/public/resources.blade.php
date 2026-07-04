@extends('layouts.public')
@section('title', 'Resources — SWIFT GRM Portal')

@section('content')
<div class="container my-5">
    <h2 class="text-grm mb-4"><i class="bi bi-folder2-open"></i> Resources &amp; IEC Materials</h2>
    <p class="text-muted">Reference documents and information materials on the SWIFT Grievance Redressal Mechanism.</p>

    <div class="row g-3">
        @foreach ([
            ['FINAL GRM Manual.pdf', 'GRM Manual', 'Complete Grievance Redressal Mechanism manual with committee structure, protocol and annexures.', 'bi-file-earmark-pdf'],
            ['GRM.docx', 'Application &amp; Form Specification', 'User types, grievance form fields and module structure.', 'bi-file-earmark-word'],
            ['GRM Pages.xlsx', 'Portal Page List', 'Web portal and admin panel page structure.', 'bi-file-earmark-excel'],
        ] as $doc)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm"><div class="card-body">
                    <i class="bi {{ $doc[3] }} text-grm h2"></i>
                    <h6>{!! $doc[1] !!}</h6>
                    <p class="small text-muted">{{ $doc[2] }}</p>
                    <a href="{{ route('resources.download', $doc[0]) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-download"></i> Download</a>
                </div></div>
            </div>
        @endforeach
    </div>

    <div class="card border-0 shadow-sm mt-4"><div class="card-body">
        <h5 class="text-grm">Your Voice Matters</h5>
        <ul class="text-muted small mb-0">
            <li>If you have any complaint, problem or suggestion about SWIFT Project activities — please tell us.</li>
            <li>Your complaint will be recorded and addressed in a fair and time-bound manner.</li>
            <li>No fee is required. Complaints can be given by anyone, including anonymously.</li>
            <li>Submit via the Beel Animator / BDC member, a written application, the grievance register, complaint box, phone, or this online portal.</li>
        </ul>
    </div></div>
</div>
@endsection
