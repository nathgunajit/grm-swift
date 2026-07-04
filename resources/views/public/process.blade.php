@extends('layouts.public')
@section('title', 'GRM Process — SWIFT GRM Portal')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-10">
    <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2 mb-6"><x-icon name="diagram" class="w-6 h-6 text-brand-600" /> Grievance Redressal Process</h1>

    <div class="card card-pad mb-8">
        <h3 class="font-semibold text-slate-800 mb-4">How it works</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            @foreach ([
                ['pencil','Submit','Complaint received online or offline'],
                ['download','Acknowledge','Tracking ID & acknowledgment issued'],
                ['people','Level I Review','Field GRC — within 7 days'],
                ['arrow-up','Escalate','To CPIU / PIU if unresolved'],
                ['check-circle','Resolve','Decision communicated'],
                ['star','Feedback','Satisfaction captured after closure'],
            ] as $step)
                <div class="rounded-xl bg-slate-50 p-4 text-center">
                    <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-lg bg-brand-100 text-brand-600"><x-icon name="{{ $step[0] }}" class="w-5 h-5" /></div>
                    <div class="mt-2 font-semibold text-sm text-slate-700">{{ $step[1] }}</div>
                    <div class="text-xs text-slate-400 mt-0.5">{{ $step[2] }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-3">
        @foreach ([
            ['Within 7 days','bg-slate-100 text-slate-700','Level I — Field / Community GRC','Constituted by DFDO.',['DFDO / SWIFT Nodal Officer — Chairperson','BDC Facilitator (NGO) — Convenor','Social Safeguards & Gender Coordinator — Member','Fisher Cooperative / SHG / ST Representative — Member','Beel Animator — Rapporteur']],
            ['Within 15 days','bg-amber-100 text-amber-700','Level II — Cluster / CPIU GRC','Constituted by Zonal Project Coordinator.',['Zonal Officer, CPIU — Chairperson','DFDO — Convenor','Local NGO / person of repute — Member','ST community representative (preferably woman) — Member','Social Safeguards / Environment Coordinator — Rapporteur']],
            ['Within 15 days','bg-slate-800 text-white','Level III — PIU GRC','Final resolution tier.',['Deputy Project Director, SWIFT — Chairperson','Social Safeguards & Gender Specialist — Convenor','Senior Project Advisor (PMU) — Member','PIU Representative — Member','Communication Specialist — Rapporteur']],
        ] as [$sla, $slaClass, $title, $sub, $members])
            <div class="card card-pad">
                <span class="badge {{ $slaClass }} mb-2">{{ $sla }}</span>
                <h3 class="font-semibold text-slate-800">{{ $title }}</h3>
                <p class="text-xs text-slate-400 mb-2">{{ $sub }}</p>
                <ul class="text-sm text-slate-500 space-y-1 list-disc list-inside">
                    @foreach ($members as $m)<li>{{ $m }}</li>@endforeach
                </ul>
            </div>
        @endforeach
    </div>

    <div class="mt-6 rounded-xl bg-brand-50 border border-brand-100 px-4 py-3 text-sm text-brand-800 flex items-center gap-2">
        <x-icon name="people" class="w-5 h-5" /> <span><strong>Gender requirement:</strong> At least 30% of GRC members at each level shall be women.</span>
    </div>
</div>
@endsection
