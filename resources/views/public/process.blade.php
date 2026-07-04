@extends('layouts.public')
@section('title', 'GRM Process — SWIFT GRM Portal')

@section('content')
<div class="container my-5">
    <h2 class="text-grm mb-4"><i class="bi bi-diagram-3"></i> Grievance Redressal Process</h2>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5>How it works</h5>
            <div class="row text-center g-2 my-3">
                @foreach ([
                    ['bi-pencil-square','Submit','Complaint received online or offline'],
                    ['bi-receipt','Acknowledge','Tracking ID &amp; acknowledgment issued'],
                    ['bi-people','Level I Review','Field GRC — within 7 days'],
                    ['bi-arrow-up-circle','Escalate','To CPIU / PIU if unresolved'],
                    ['bi-check-circle','Resolve','Decision communicated'],
                    ['bi-star','Feedback','Satisfaction captured after closure'],
                ] as $step)
                    <div class="col">
                        <div class="p-2">
                            <i class="bi {{ $step[0] }} text-grm h3"></i>
                            <div class="fw-semibold small">{{ $step[1] }}</div>
                            <div class="text-muted" style="font-size:.75rem">{!! $step[2] !!}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm"><div class="card-body">
                <span class="badge bg-secondary mb-2">Within 7 days</span>
                <h5>Level I — Field / Community GRC</h5>
                <p class="small text-muted mb-1">Constituted by DFDO. Composition:</p>
                <ul class="small text-muted">
                    <li>DFDO / SWIFT Nodal Officer — Chairperson</li>
                    <li>BDC Facilitator (NGO) — Convenor</li>
                    <li>Social Safeguards &amp; Gender Coordinator — Member</li>
                    <li>Fisher Cooperative / SHG / ST Representative — Member</li>
                    <li>Beel Animator — Rapporteur</li>
                </ul>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm"><div class="card-body">
                <span class="badge bg-warning text-dark mb-2">Within 15 days</span>
                <h5>Level II — Cluster / CPIU GRC</h5>
                <ul class="small text-muted">
                    <li>Zonal Officer, CPIU — Chairperson</li>
                    <li>DFDO — Convenor</li>
                    <li>Local NGO / person of repute — Member</li>
                    <li>ST community representative (preferably woman) — Member</li>
                    <li>Social Safeguards / Environment Coordinator — Rapporteur</li>
                </ul>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm"><div class="card-body">
                <span class="badge bg-dark mb-2">Within 15 days</span>
                <h5>Level III — PIU GRC</h5>
                <ul class="small text-muted">
                    <li>Deputy Project Director, SWIFT — Chairperson</li>
                    <li>Social Safeguards &amp; Gender Specialist — Convenor</li>
                    <li>Senior Project Advisor (PMU) — Member</li>
                    <li>PIU Representative — Member</li>
                    <li>Communication Specialist — Rapporteur</li>
                </ul>
            </div></div>
        </div>
    </div>

    <div class="alert alert-light border mt-4"><i class="bi bi-gender-ambiguous text-grm"></i> <strong>Gender requirement:</strong> At least 30% of GRC members at each level shall be women.</div>
</div>
@endsection
