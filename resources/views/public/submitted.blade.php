@extends('layouts.public')
@section('title', 'Grievance Submitted — SWIFT GRM Portal')

@section('content')
<div class="mx-auto max-w-2xl px-4 py-12">
    <div class="card card-pad text-center">
        <x-brand-bar class="mb-6" />
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
            <x-icon name="check-circle" class="w-10 h-10" />
        </div>
        <h1 class="mt-4 text-2xl font-bold text-slate-800">Grievance Registered Successfully</h1>
        <p class="mt-2 text-slate-500">Your grievance has been recorded and will be reviewed under the SWIFT Grievance Redressal Mechanism. Expected initial decision: within <strong>7 days</strong> (field level).</p>

        <div class="mt-6 grid gap-4 sm:grid-cols-2">
            <div class="rounded-xl border border-slate-200 p-4">
                <div class="text-xs uppercase tracking-wide text-slate-400">Tracking ID</div>
                <div class="text-xl font-bold text-brand-700">{{ $grievance->tracking_id }}</div>
            </div>
            <div class="rounded-xl border border-slate-200 p-4">
                <div class="text-xs uppercase tracking-wide text-slate-400">Acknowledgment No.</div>
                <div class="text-xl font-bold text-brand-700">{{ $grievance->acknowledgment_no }}</div>
            </div>
        </div>

        <p class="mt-4 text-sm text-slate-400">Please save your Tracking ID. You will need it (or your mobile number) to track the status.</p>

        <div class="mt-6 flex flex-wrap justify-center gap-3">
            <a href="{{ route('grievance.ack', $grievance->tracking_id) }}" class="btn btn-primary"><x-icon name="download" class="w-5 h-5" /> Download Acknowledgment (PDF)</a>
            <a href="{{ route('track') }}?q={{ $grievance->tracking_id }}" class="btn btn-outline"><x-icon name="search" class="w-5 h-5" /> Track this Grievance</a>
        </div>
    </div>
</div>
@endsection
