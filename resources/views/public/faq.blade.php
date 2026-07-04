@extends('layouts.public')
@section('title', 'Help & FAQ — SWIFT GRM Portal')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-10">
    <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2 mb-6"><x-icon name="question" class="w-6 h-6 text-brand-600" /> Help &amp; Frequently Asked Questions</h1>

    <div class="space-y-3" x-data="{ open: 0 }">
        @foreach ([
            ['Who can submit a grievance?', 'Any Project Affected Person, beel-dependent community member, beneficiary or non-beneficiary, SHG/fisher cooperative, or any stakeholder affected by SWIFT activities. No fee is charged.'],
            ['What can I complain about?', 'Beneficiary selection, benefit/payment issues, construction/work quality, environmental and social impacts, staff misbehaviour, exclusion of eligible persons, corruption, or any project-related problem. Suggestions are also welcome.'],
            ['Can I complain anonymously?', 'Yes. Tick the Anonymous option on the form. You may also mark a complaint as Confidential to keep your identity protected.'],
            ['How will I know the status?', 'You receive a unique Tracking ID and Acknowledgment Number. Use the Track Complaint page with your Tracking ID, Acknowledgment No, or mobile number.'],
            ['How long does resolution take?', 'Field level within 7 days; Cluster/CPIU level within 15 days; PIU level within 15 days. If not resolved or you are dissatisfied, it is escalated to the next level.'],
            ['What about GBV or harassment complaints?', 'Complaints related to Gender-Based Violence or Sexual Exploitation and Abuse/Harassment are handled confidentially through the Internal Complaints Committee with a survivor-centred approach.'],
            ['What if I am not satisfied?', 'You can reopen your grievance from the Track page, and it will be escalated. You also retain the right to seek legal remedies or approach the ADB Accountability Mechanism.'],
        ] as $i => $faq)
            <div class="card overflow-hidden">
                <button type="button" @click="open = (open === {{ $i }} ? null : {{ $i }})" class="w-full flex items-center justify-between px-5 py-4 text-left font-medium text-slate-800">
                    <span>{{ $faq[0] }}</span>
                    <x-icon name="plus" class="w-5 h-5 text-brand-600 transition" x-bind:class="open === {{ $i }} ? 'rotate-45' : ''" />
                </button>
                <div x-show="open === {{ $i }}" x-cloak class="px-5 pb-4 text-sm text-slate-500">{{ $faq[1] }}</div>
            </div>
        @endforeach
    </div>
</div>
@endsection
