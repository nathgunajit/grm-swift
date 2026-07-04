@extends('layouts.public')
@section('title', 'Grievance Submitted — SWIFT GRM Portal')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body p-5">
                    <i class="bi bi-check-circle-fill text-success" style="font-size:4rem;"></i>
                    <h3 class="mt-3">Grievance Registered Successfully</h3>
                    <p class="text-muted">Your grievance has been recorded and will be reviewed under the SWIFT Grievance Redressal Mechanism. Expected initial decision: within <strong>7 days</strong> (field level).</p>

                    <div class="row g-3 my-3">
                        <div class="col-md-6">
                            <div class="border rounded p-3">
                                <div class="small text-muted">Tracking ID</div>
                                <div class="h4 text-grm mb-0">{{ $grievance->tracking_id }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3">
                                <div class="small text-muted">Acknowledgment No.</div>
                                <div class="h4 text-grm mb-0">{{ $grievance->acknowledgment_no }}</div>
                            </div>
                        </div>
                    </div>

                    <p class="small text-muted">Please save your Tracking ID. You will need it (or your mobile number) to track the status.</p>

                    <a href="{{ route('grievance.ack', $grievance->tracking_id) }}" class="btn btn-grm"><i class="bi bi-download"></i> Download Acknowledgment (PDF)</a>
                    <a href="{{ route('track') }}?q={{ $grievance->tracking_id }}" class="btn btn-outline-secondary"><i class="bi bi-search"></i> Track this Grievance</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
