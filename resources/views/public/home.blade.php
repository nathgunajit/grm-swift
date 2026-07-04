@extends('layouts.public')
@section('title', 'Home — SWIFT GRM Portal')

@section('content')
<section class="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="fw-bold">Grievance Redressal Mechanism</h1>
                <p class="lead mb-4">A structured, accessible and time-bound platform to receive, assess and resolve grievances under the ADB-assisted Assam SWIFT Project. Your voice matters — complaints are free and can be anonymous.</p>
                <a href="{{ route('grievance.create') }}" class="btn btn-light btn-lg fw-semibold me-2"><i class="bi bi-pencil-square"></i> Register a Complaint</a>
                <a href="{{ route('track') }}" class="btn btn-outline-light btn-lg"><i class="bi bi-search"></i> Track Status</a>
            </div>
            <div class="col-lg-5 text-center d-none d-lg-block">
                <i class="bi bi-shield-check" style="font-size:10rem;opacity:.85;"></i>
            </div>
        </div>
    </div>
</section>

<div class="container my-5">
    <div class="row g-3 mb-5">
        <div class="col-6 col-md">
            <div class="card stat-card shadow-sm h-100"><div class="card-body text-center">
                <div class="h3 mb-0 text-grm">{{ $stats['total'] }}</div><small class="text-muted">Total Grievances</small>
            </div></div>
        </div>
        <div class="col-6 col-md">
            <div class="card stat-card shadow-sm h-100"><div class="card-body text-center">
                <div class="h3 mb-0 text-secondary">{{ $stats['registered'] }}</div><small class="text-muted">Registered</small>
            </div></div>
        </div>
        <div class="col-6 col-md">
            <div class="card stat-card shadow-sm h-100"><div class="card-body text-center">
                <div class="h3 mb-0 text-primary">{{ $stats['under_review'] }}</div><small class="text-muted">Under Review</small>
            </div></div>
        </div>
        <div class="col-6 col-md">
            <div class="card stat-card shadow-sm h-100"><div class="card-body text-center">
                <div class="h3 mb-0 text-warning">{{ $stats['escalated'] }}</div><small class="text-muted">Escalated</small>
            </div></div>
        </div>
        <div class="col-6 col-md">
            <div class="card stat-card shadow-sm h-100"><div class="card-body text-center">
                <div class="h3 mb-0 text-success">{{ $stats['resolved'] }}</div><small class="text-muted">Resolved</small>
            </div></div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0"><div class="card-body">
                <i class="bi bi-1-circle-fill text-grm h3"></i>
                <h5>Level I — Field / Beel</h5>
                <p class="text-muted small mb-0">Grievances heard and resolved at the field level within <strong>7 days</strong> by the Field GRC (DFDO, BDC Facilitator, SSGC, Beel Animator).</p>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0"><div class="card-body">
                <i class="bi bi-2-circle-fill text-grm h3"></i>
                <h5>Level II — Cluster / CPIU</h5>
                <p class="text-muted small mb-0">Unresolved or escalated cases are addressed at the CPIU cluster level within <strong>15 days</strong>.</p>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0"><div class="card-body">
                <i class="bi bi-3-circle-fill text-grm h3"></i>
                <h5>Level III — PIU</h5>
                <p class="text-muted small mb-0">Final resolution at the PIU level within <strong>15 days</strong>, with the right to approach the ADB Accountability Mechanism.</p>
            </div></div>
        </div>
    </div>

    <div class="alert alert-light border mt-5 d-flex align-items-center">
        <i class="bi bi-info-circle-fill text-grm h4 me-3 mb-0"></i>
        <div>Complaints regarding <strong>Gender-Based Violence (GBV)</strong> or Sexual Exploitation and Abuse/Harassment (SEA/SH) are handled confidentially through the Internal Complaints Committee with a survivor-centred approach.</div>
    </div>
</div>
@endsection
