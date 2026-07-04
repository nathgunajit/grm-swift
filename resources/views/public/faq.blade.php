@extends('layouts.public')
@section('title', 'Help & FAQ — SWIFT GRM Portal')

@section('content')
<div class="container my-5">
    <h2 class="text-grm mb-4"><i class="bi bi-question-circle"></i> Help &amp; Frequently Asked Questions</h2>
    <div class="accordion" id="faq">
        @foreach ([
            ['Who can submit a grievance?', 'Any Project Affected Person, beel-dependent community member, beneficiary or non-beneficiary, SHG/fisher cooperative, or any stakeholder affected by SWIFT activities. No fee is charged.'],
            ['What can I complain about?', 'Beneficiary selection, benefit/payment issues, construction/work quality, environmental and social impacts, staff misbehaviour, exclusion of eligible persons, corruption, or any project-related problem. Suggestions are also welcome.'],
            ['Can I complain anonymously?', 'Yes. Tick the Anonymous option on the form. You may also mark a complaint as Confidential to keep your identity protected.'],
            ['How will I know the status?', 'You receive a unique Tracking ID and Acknowledgment Number. Use the Track Complaint page with your Tracking ID, Acknowledgment No, or mobile number.'],
            ['How long does resolution take?', 'Field level within 7 days; Cluster/CPIU level within 15 days; PIU level within 15 days. If not resolved or you are dissatisfied, it is escalated to the next level.'],
            ['What about GBV or harassment complaints?', 'Complaints related to Gender-Based Violence or Sexual Exploitation and Abuse/Harassment are handled confidentially through the Internal Complaints Committee with a survivor-centred approach.'],
            ['What if I am not satisfied?', 'You can reopen your grievance from the Track page, and it will be escalated. You also retain the right to seek legal remedies or approach the ADB Accountability Mechanism.'],
        ] as $i => $faq)
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button {{ $i ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#q{{ $i }}">
                        {{ $faq[0] }}
                    </button>
                </h2>
                <div id="q{{ $i }}" class="accordion-collapse collapse {{ $i ? '' : 'show' }}" data-bs-parent="#faq">
                    <div class="accordion-body text-muted">{{ $faq[1] }}</div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
