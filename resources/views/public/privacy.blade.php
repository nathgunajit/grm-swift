@extends('layouts.public')
@section('title', 'Privacy Policy — SWIFT GRM Portal')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-10">
    <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2 mb-6"><x-icon name="shield-lock" class="w-6 h-6 text-brand-600" /> Privacy Policy</h1>
    <div class="card card-pad space-y-4 text-sm text-slate-600">
        <div>
            <h3 class="font-semibold text-slate-800">Confidentiality &amp; Anonymity</h3>
            <p class="mt-1">The identity of complainants is kept confidential, particularly in sensitive cases. Anonymous grievances are also accepted and processed. You may mark any grievance as Confidential.</p>
        </div>
        <div>
            <h3 class="font-semibold text-slate-800">Information We Collect</h3>
            <p class="mt-1">We collect only the information you provide on the grievance form — name (optional if anonymous), contact details, location, and the description of your grievance, along with any documents you upload.</p>
        </div>
        <div>
            <h3 class="font-semibold text-slate-800">How Your Information Is Used</h3>
            <p class="mt-1">Your information is used solely to register, assess, resolve and communicate the outcome of your grievance, and for aggregate monitoring and reporting to the PIU/PMU and ADB. Personal details are not disclosed publicly.</p>
        </div>
        <div>
            <h3 class="font-semibold text-slate-800">Sensitive Complaints</h3>
            <p class="mt-1">Complaints involving Gender-Based Violence (GBV) or Sexual Exploitation and Abuse/Harassment are handled through a special confidential process by the Internal Complaints Committee, ensuring protection from retaliation and a survivor-centred approach.</p>
        </div>
        <div>
            <h3 class="font-semibold text-slate-800">Record Keeping</h3>
            <p class="mt-1">All grievance records are securely maintained at CPIU and PIU levels with strict confidentiality, in accordance with the SWIFT GRM Manual.</p>
        </div>
    </div>
</div>
@endsection
