@extends('layouts.public')
@section('title', 'Contact Us — SWIFT GRM Portal')

@section('content')
<div class="container my-5">
    <h2 class="text-grm mb-4"><i class="bi bi-telephone"></i> Contact Us</h2>
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100"><div class="card-body">
                <h5>Project Management Unit (PMU)</h5>
                <p class="text-muted mb-1">ARIAS Society — SWIFT Project</p>
                <p class="text-muted small">Agriculture Complex, Khanapara, G.S. Road, Guwahati-781022, Assam</p>
                <ul class="list-unstyled small">
                    <li><i class="bi bi-telephone text-grm"></i> 0361-2332004</li>
                    <li><i class="bi bi-envelope text-grm"></i> spd@arias.in</li>
                    <li><i class="bi bi-globe text-grm"></i> www.arias.in</li>
                </ul>
            </div></div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100"><div class="card-body">
                <h5>Local Contacts</h5>
                <p class="text-muted small">For field-level assistance, you may also contact:</p>
                <ul class="list-unstyled small">
                    <li><i class="bi bi-person text-grm"></i> Your <strong>Beel Animator</strong></li>
                    <li><i class="bi bi-person text-grm"></i> The <strong>BDC Facilitator</strong></li>
                    <li><i class="bi bi-building text-grm"></i> The <strong>District Fisheries Development Office (DFDO)</strong></li>
                </ul>
                <a href="{{ route('grievance.create') }}" class="btn btn-grm btn-sm"><i class="bi bi-pencil-square"></i> Register a Grievance Online</a>
            </div></div>
        </div>
    </div>
</div>
@endsection
