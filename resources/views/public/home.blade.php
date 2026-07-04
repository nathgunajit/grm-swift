@extends('layouts.public')
@section('title', 'Home — SWIFT GRM Portal')

@section('content')
{{-- Hero --}}
<section class="relative overflow-hidden bg-gradient-to-br from-brand-800 via-brand-700 to-brand-600 text-white">
    <div class="absolute inset-0 opacity-10" style="background-image:radial-gradient(circle at 20% 30%, #fff 1px, transparent 1px);background-size:28px 28px;"></div>
    <div class="relative mx-auto max-w-7xl px-4 py-16 sm:py-20">
        <div class="grid items-center gap-10 lg:grid-cols-2">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-xs font-semibold backdrop-blur">
                    <x-icon name="shield-check" class="w-4 h-4 text-accent-300" /> ADB-assisted SWIFT Project
                </span>
                <h1 class="mt-4 font-display text-4xl sm:text-5xl font-extrabold leading-tight text-white">Grievance Redressal Mechanism</h1>
                <p class="mt-4 max-w-xl text-brand-50/90 text-lg">A structured, accessible and time-bound platform to receive, assess and resolve grievances. Your voice matters — complaints are free and can be anonymous.</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('grievance.create') }}" class="btn btn-accent px-6 py-3 text-base shadow-lg"><x-icon name="pencil" class="w-5 h-5" /> Register a Complaint</a>
                    <a href="{{ route('track') }}" class="btn px-6 py-3 text-base bg-white/10 text-white border border-white/30 hover:bg-white/20"><x-icon name="search" class="w-5 h-5" /> Track Status</a>
                </div>
            </div>
            <div class="hidden lg:flex justify-center">
                <div class="rounded-3xl bg-white/10 p-10 backdrop-blur ring-1 ring-white/20">
                    <x-icon name="shield-check" class="w-48 h-48 text-white/90" />
                </div>
            </div>
        </div>
    </div>
</section>

<div class="mx-auto max-w-7xl px-4">
    {{-- Stats --}}
    <section class="-mt-10 relative z-10">
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
            @php $cards = [
                ['Total', $stats['total'], 'border-brand-600', 'text-brand-600'],
                ['Registered', $stats['registered'], 'border-slate-400', 'text-slate-500'],
                ['Under Review', $stats['under_review'], 'border-sky-500', 'text-sky-500'],
                ['Escalated', $stats['escalated'], 'border-amber-500', 'text-amber-500'],
                ['Resolved', $stats['resolved'], 'border-emerald-500', 'text-emerald-500'],
            ]; @endphp
            @foreach ($cards as [$label, $value, $border, $text])
                <div class="stat-card {{ $border }} text-center">
                    <div class="text-3xl font-bold {{ $text }}">{{ $value }}</div>
                    <div class="mt-1 text-xs font-medium text-slate-500">{{ $label }}</div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- 3-tier GRC --}}
    <section class="py-14">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-slate-800">Three-Tier Redressal</h2>
            <p class="mt-2 text-slate-500">Every grievance is heard within a defined, time-bound process.</p>
        </div>
        <div class="grid gap-6 md:grid-cols-3">
            @php $tiers = [
                ['1', 'Level I — Field / Beel', 'Heard and resolved at the field level within 7 days by the Field GRC (DFDO, BDC Facilitator, SSGC, Beel Animator).', 'bg-brand-100 text-brand-600'],
                ['2', 'Level II — Cluster / CPIU', 'Unresolved or escalated cases addressed at the CPIU cluster level within 15 days.', 'bg-sky-100 text-sky-600'],
                ['3', 'Level III — PIU', 'Final resolution at the PIU level within 15 days, with the right to approach the ADB Accountability Mechanism.', 'bg-amber-100 text-amber-600'],
            ]; @endphp
            @foreach ($tiers as [$n, $title, $desc, $badge])
                <div class="card card-pad group hover:-translate-y-1 transition">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl {{ $badge }} font-display text-xl font-bold">{{ $n }}</div>
                    <h3 class="mt-4 text-lg font-semibold">{{ $title }}</h3>
                    <p class="mt-2 text-sm text-slate-500 leading-relaxed">{{ $desc }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Sensitive-case notice --}}
    <section class="pb-16">
        <div class="rounded-2xl bg-gradient-to-r from-rose-50 to-amber-50 ring-1 ring-rose-100 p-6 flex items-start gap-4">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-rose-100 text-rose-600">
                <x-icon name="shield-lock" class="w-6 h-6" />
            </div>
            <div>
                <h3 class="font-semibold text-slate-800">Sensitive complaints are protected</h3>
                <p class="text-sm text-slate-600 mt-1">Complaints regarding Gender-Based Violence (GBV) or Sexual Exploitation and Abuse/Harassment (SEA/SH) are handled confidentially through the Internal Complaints Committee, with a survivor-centred approach.</p>
            </div>
        </div>
    </section>
</div>
@endsection
