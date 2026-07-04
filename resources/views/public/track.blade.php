@extends('layouts.public')
@section('title', 'Track Complaint — SWIFT GRM Portal')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-10">
    <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2 mb-4"><x-icon name="search" class="w-6 h-6 text-brand-600" /> Track Your Grievance</h1>

    <form method="POST" action="{{ route('track.search') }}" class="card card-pad mb-6">
        @csrf
        <div class="flex flex-col sm:flex-row gap-2">
            <input type="text" name="query" class="input" placeholder="Enter Tracking ID, Acknowledgment No, or Mobile Number" value="{{ old('query', request('q')) }}" required>
            <button class="btn btn-primary whitespace-nowrap"><x-icon name="search" class="w-5 h-5" /> Track</button>
        </div>
    </form>

    @isset($grievance)
    <div class="card mb-6">
        <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 px-5 py-4">
            <div class="flex items-center gap-2">
                <span class="text-lg font-bold text-brand-700">{{ $grievance->tracking_id }}</span>
                @if ($grievance->is_sensitive)<span class="badge bg-rose-100 text-rose-700"><x-icon name="shield-lock" class="w-3.5 h-3.5" /> Sensitive</span>@endif
                @if ($grievance->is_confidential)<span class="badge bg-slate-200 text-slate-700">Confidential</span>@endif
            </div>
            <x-status-badge :status="$grievance->status" />
        </div>
        <div class="card-pad">
            <div class="grid gap-4 sm:grid-cols-4 mb-4">
                <div><div class="text-xs text-slate-400">Category</div>{{ $grievance->category?->name ?? '—' }}</div>
                <div><div class="text-xs text-slate-400">Current Level</div>{{ $grievance->levelLabel() }}</div>
                <div><div class="text-xs text-slate-400">Beel</div>{{ $grievance->beel?->name ?? '—' }}</div>
                <div><div class="text-xs text-slate-400">Registered On</div>{{ $grievance->created_at->format('d M Y') }}</div>
            </div>
            @unless ($grievance->is_confidential)
                <div class="mb-4"><div class="text-xs text-slate-400">Description</div><p class="text-sm text-slate-700">{{ $grievance->description }}</p></div>
            @endunless

            @if ($grievance->beel && $grievance->beel->latitude && $grievance->beel->longitude)
                <a href="https://www.google.com/maps?q={{ $grievance->beel->latitude }},{{ $grievance->beel->longitude }}" target="_blank" class="inline-flex items-center gap-1 text-sm text-brand-600 hover:underline mb-4">
                    <x-icon name="map-pin" class="w-4 h-4" /> View beel location on map
                </a>
            @endif

            @if ($grievance->due_at && !in_array($grievance->status, ['resolved','closed']))
                <p class="text-sm {{ $grievance->isOverdue() ? 'text-rose-600' : 'text-slate-500' }} flex items-center gap-1 mb-4">
                    <x-icon name="clock" class="w-4 h-4" /> Expected resolution by {{ $grievance->due_at->format('d M Y') }}@if ($grievance->isOverdue()) (overdue)@endif
                </p>
            @endif

            @if ($grievance->resolution)
                <div class="rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 text-sm mb-4"><strong>Resolution:</strong> {{ $grievance->resolution }}</div>
            @endif

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('grievance.ack', $grievance->tracking_id) }}" class="btn btn-sm btn-outline"><x-icon name="download" class="w-4 h-4" /> Acknowledgment PDF</a>
                @if (in_array($grievance->status, ['resolved','closed']))
                    <a href="{{ route('grievance.resolution', $grievance->tracking_id) }}" class="btn btn-sm btn-success"><x-icon name="download" class="w-4 h-4" /> Resolution Letter PDF</a>
                @endif
            </div>
        </div>
    </div>

    {{-- Timeline --}}
    <div class="card card-pad mb-6">
        <h3 class="font-semibold text-slate-800 flex items-center gap-2 mb-4"><x-icon name="clock" class="w-5 h-5 text-brand-600" /> Status Timeline</h3>
        <ol class="relative border-l-2 border-slate-100 ml-2 space-y-5">
            @foreach ($grievance->actions as $a)
                <li class="ml-5">
                    <span class="absolute -left-[7px] mt-1.5 h-3 w-3 rounded-full bg-brand-600"></span>
                    <div class="font-medium text-slate-800 capitalize flex items-center gap-2">{{ str_replace('_',' ',$a->action) }}
                        @if ($a->to_level && $a->from_level && $a->to_level != $a->from_level)
                            <span class="badge bg-amber-100 text-amber-700">L{{ $a->from_level }} → L{{ $a->to_level }}</span>
                        @endif
                    </div>
                    @if ($a->remarks)<p class="text-sm text-slate-500">{{ $a->remarks }}</p>@endif
                    <p class="text-xs text-slate-400">{{ $a->created_at->format('d M Y, h:i A') }}</p>
                </li>
            @endforeach
        </ol>
    </div>

    {{-- Feedback / Reopen --}}
    @if (in_array($grievance->status, ['resolved','closed']))
        @if ($grievance->feedback)
            <div class="rounded-lg bg-slate-50 border border-slate-200 px-4 py-3 text-sm text-slate-600 flex items-center gap-2">
                <x-icon name="star" class="w-4 h-4 text-amber-500" /> Feedback received — satisfaction: <strong>{{ ucfirst($grievance->feedback->satisfaction) }}</strong>. Thank you.
            </div>
        @else
            <div class="grid gap-6 md:grid-cols-2">
                <div class="card card-pad">
                    <h3 class="font-semibold text-slate-800 flex items-center gap-2 mb-3"><x-icon name="chat" class="w-5 h-5 text-brand-600" /> Share Your Feedback</h3>
                    <form method="POST" action="{{ route('grievance.feedback', $grievance->tracking_id) }}" class="space-y-2 text-sm">
                        @csrf
                        <div><label class="label">Were you informed about the GRM process?</label><select name="informed" class="input" required><option value="1">Yes</option><option value="0">No</option></select></div>
                        <div><label class="label">Was your complaint heard respectfully?</label><select name="heard_respectfully" class="input" required><option value="1">Yes</option><option value="0">No</option></select></div>
                        <div><label class="label">Was the response time reasonable?</label><select name="response_time_ok" class="input" required><option value="1">Yes</option><option value="0">No</option></select></div>
                        <div class="grid grid-cols-3 gap-2">
                            <div><label class="label">Satisfaction</label><select name="satisfaction" class="input" required><option value="fully">Fully</option><option value="partly">Partly</option><option value="not">Not</option></select></div>
                            <div><label class="label">Transparency</label><select name="transparency" class="input" required><option value="good">Good</option><option value="average">Average</option><option value="poor">Poor</option></select></div>
                            <div><label class="label">Behaviour</label><select name="official_behavior" class="input" required><option value="good">Good</option><option value="average">Average</option><option value="poor">Poor</option></select></div>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div><label class="label">Feel safe using GRM again?</label><select name="feel_safe" class="input" required><option value="1">Yes</option><option value="0">No</option></select></div>
                            <div><label class="label">Overall rating (1-5)</label><input type="number" name="rating" min="1" max="5" class="input"></div>
                        </div>
                        <div><label class="label">Comments</label><textarea name="comments" class="input" rows="2"></textarea></div>
                        <button class="btn btn-primary btn-sm mt-2"><x-icon name="send" class="w-4 h-4" /> Submit Feedback &amp; Close</button>
                    </form>
                </div>
                <div class="card card-pad">
                    <h3 class="font-semibold text-slate-800 flex items-center gap-2 mb-3"><x-icon name="refresh" class="w-5 h-5 text-brand-600" /> Not Satisfied?</h3>
                    <p class="text-sm text-slate-500 mb-3">If you are not satisfied with the resolution, you may reopen the grievance. It will be escalated to the next level for review.</p>
                    <form method="POST" action="{{ route('grievance.reopen', $grievance->tracking_id) }}">
                        @csrf
                        <textarea name="reason" class="input mb-2" rows="3" placeholder="Reason for reopening" required></textarea>
                        <button class="btn btn-danger btn-sm"><x-icon name="refresh" class="w-4 h-4" /> Reopen Grievance</button>
                    </form>
                </div>
            </div>
        @endif
    @endif
    @endisset
</div>
@endsection
