@extends('layouts.admin')
@section('title', $grievance->tracking_id)
@section('heading', 'Grievance '.$grievance->tracking_id)

@section('content')
@php $canAct = !in_array($grievance->status, ['closed']); @endphp

<div class="grid gap-5 lg:grid-cols-3">
    <div class="lg:col-span-2 space-y-5">
        <div class="card">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 px-5 py-4">
                <span class="text-lg font-bold text-brand-700">{{ $grievance->tracking_id }}</span>
                <div class="flex items-center gap-1.5">
                    @if ($grievance->is_sensitive)<span class="badge bg-rose-100 text-rose-700"><x-icon name="shield-lock" class="w-3.5 h-3.5" /> Sensitive</span>@endif
                    @if ($grievance->is_confidential)<span class="badge bg-slate-200 text-slate-700">Confidential</span>@endif
                    <x-status-badge :status="$grievance->status" />
                </div>
            </div>
            <div class="card-pad">
                @if ($grievance->is_sensitive)
                    <div class="mb-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 px-4 py-2 text-sm flex items-start gap-2">
                        <x-icon name="shield-lock" class="w-4 h-4 mt-0.5 shrink-0" /> This is a sensitive case (GBV/SEA-SH or misconduct). Handle confidentially; refer to the Internal Complaints Committee (ICC) where applicable.
                    </div>
                @endif
                <div class="grid gap-4 sm:grid-cols-3 text-sm">
                    <div><div class="text-xs text-slate-400">Complainant</div>{{ $grievance->is_anonymous ? 'Anonymous' : ($grievance->name ?? '—') }}</div>
                    <div><div class="text-xs text-slate-400">Mobile</div>{{ $grievance->is_confidential ? '••••••' : ($grievance->mobile ?? '—') }}</div>
                    <div><div class="text-xs text-slate-400">Email</div>{{ $grievance->is_confidential ? '••••••' : ($grievance->email ?? '—') }}</div>
                    <div><div class="text-xs text-slate-400">Category</div>{{ $grievance->category?->name ?? '—' }}</div>
                    <div><div class="text-xs text-slate-400">Mode</div>{{ ucfirst($grievance->mode_of_receipt) }}</div>
                    <div><div class="text-xs text-slate-400">Current Level</div>{{ $grievance->levelLabel() }}</div>
                    <div><div class="text-xs text-slate-400">Beel</div>{{ $grievance->beel?->name ?? '—' }}</div>
                    <div><div class="text-xs text-slate-400">District</div>{{ $grievance->district?->name ?? '—' }}</div>
                    <div><div class="text-xs text-slate-400">Place / Village</div>{{ $grievance->place_village }}</div>
                    <div><div class="text-xs text-slate-400">Registered</div>{{ $grievance->created_at->format('d M Y') }}</div>
                    <div><div class="text-xs text-slate-400">Due</div><span class="{{ $grievance->isOverdue() ? 'text-rose-600 font-medium' : '' }}">{{ optional($grievance->due_at)->format('d M Y') ?? '—' }}{{ $grievance->isOverdue() ? ' (overdue)' : '' }}</span></div>
                </div>
                <div class="mt-4 text-sm"><div class="text-xs text-slate-400">Description</div><p class="text-slate-700">{{ $grievance->description }}</p></div>

                @if ($grievance->beel && $grievance->beel->latitude)
                    <a href="https://www.google.com/maps?q={{ $grievance->beel->latitude }},{{ $grievance->beel->longitude }}" target="_blank" class="mt-3 inline-flex items-center gap-1 text-sm text-brand-600 hover:underline"><x-icon name="map-pin" class="w-4 h-4" /> Beel location on map</a>
                @endif

                @if ($grievance->documents->count())
                    <div class="mt-4">
                        <div class="text-xs text-slate-400 mb-1">Attachments</div>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($grievance->documents as $doc)
                                <a href="{{ Storage::url($doc->path) }}" target="_blank" class="badge bg-slate-100 text-slate-700"><x-icon name="paperclip" class="w-3.5 h-3.5" /> {{ $doc->original_name }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($grievance->resolution)
                    <div class="mt-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 text-sm"><strong>Resolution:</strong> {{ $grievance->resolution }}</div>
                @endif

                <div class="mt-4 flex flex-wrap gap-2">
                    <a href="{{ route('grievance.ack', $grievance->tracking_id) }}" class="btn btn-sm btn-outline"><x-icon name="download" class="w-4 h-4" /> Acknowledgment PDF</a>
                    @if (in_array($grievance->status, ['resolved','closed']))
                        <a href="{{ route('grievance.resolution', $grievance->tracking_id) }}" class="btn btn-sm btn-success"><x-icon name="download" class="w-4 h-4" /> Resolution PDF</a>
                    @endif
                </div>
            </div>
        </div>

        @if ($grievance->feedback)
            <div class="card card-pad">
                <h3 class="font-semibold text-slate-800 flex items-center gap-2 mb-3"><x-icon name="star" class="w-5 h-5 text-amber-500" /> Complainant Feedback</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-sm">
                    <div>Satisfaction: <strong>{{ ucfirst($grievance->feedback->satisfaction) }}</strong></div>
                    <div>Transparency: <strong>{{ ucfirst($grievance->feedback->transparency) }}</strong></div>
                    <div>Behaviour: <strong>{{ ucfirst($grievance->feedback->official_behavior) }}</strong></div>
                    <div>Rating: <strong>{{ $grievance->feedback->rating ?? '—' }}/5</strong></div>
                    <div>Felt safe: <strong>{{ $grievance->feedback->feel_safe ? 'Yes' : 'No' }}</strong></div>
                </div>
                @if ($grievance->feedback->comments)<p class="mt-2 text-sm text-slate-600 italic">"{{ $grievance->feedback->comments }}"</p>@endif
            </div>
        @endif
    </div>

    <div class="space-y-5">
        @php
            $me = auth()->user();
            $canComment  = $canAct && $me->canGrievance('comment');
            $canEscalate = $canAct && $me->canGrievance('escalate') && $grievance->current_level < 3;
            $canResolve  = $canAct && $me->canGrievance('resolve');
            $hasActions  = $canComment || $canEscalate || $canResolve;
            $firstTab    = $canComment ? 'comment' : ($canEscalate ? 'escalate' : ($canResolve ? 'resolve' : ''));
        @endphp
        @if ($hasActions)
        <div class="card card-pad" x-data="{ tab: '{{ $firstTab }}' }">
            <h3 class="font-semibold text-slate-800 mb-3">Take Action <span class="text-xs font-normal text-slate-400">· {{ $me->userType?->name }}</span></h3>
            <div class="flex rounded-lg bg-slate-100 p-1 text-sm mb-3">
                @if ($canComment)<button @click="tab='comment'" :class="tab==='comment' ? 'bg-white shadow-sm text-brand-700' : 'text-slate-500'" class="flex-1 rounded-md py-1.5 font-medium">Comment</button>@endif
                @if ($canEscalate)<button @click="tab='escalate'" :class="tab==='escalate' ? 'bg-white shadow-sm text-brand-700' : 'text-slate-500'" class="flex-1 rounded-md py-1.5 font-medium">Escalate</button>@endif
                @if ($canResolve)<button @click="tab='resolve'" :class="tab==='resolve' ? 'bg-white shadow-sm text-brand-700' : 'text-slate-500'" class="flex-1 rounded-md py-1.5 font-medium">Resolve</button>@endif
            </div>

            @if ($canComment)
            <div x-show="tab==='comment'">
                @if ($grievance->status === 'registered' && $me->canGrievance('review'))
                    <form method="POST" action="{{ route('admin.grievances.review', $grievance) }}" class="mb-2">
                        @csrf<button class="btn btn-sm btn-outline w-full"><x-icon name="hourglass" class="w-4 h-4" /> Mark Under Review</button>
                    </form>
                @endif
                <form method="POST" action="{{ route('admin.grievances.comment', $grievance) }}">
                    @csrf
                    <textarea name="remarks" class="input mb-2" rows="3" placeholder="Add a review comment / note" required></textarea>
                    <button class="btn btn-sm btn-primary w-full"><x-icon name="chat" class="w-4 h-4" /> Add Comment</button>
                </form>
            </div>
            @endif

            @if ($canEscalate)
            <div x-show="tab==='escalate'" x-cloak>
                <form method="POST" action="{{ route('admin.grievances.escalate', $grievance) }}">
                    @csrf
                    <label class="label">Escalate to Level</label>
                    <select name="to_level" class="input mb-2">
                        @for ($lvl = $grievance->current_level + 1; $lvl <= 3; $lvl++)
                            <option value="{{ $lvl }}">Level {{ $lvl }} — {{ $lvl == 2 ? 'Cluster / CPIU' : 'PIU' }}</option>
                        @endfor
                    </select>
                    <label class="label">Team / Committee <span class="text-xs text-slate-400">· District: {{ $grievance->district?->name ?? '—' }}</span></label>
                    <select name="committee_id" class="input mb-2">
                        <option value="">-- Not specified --</option>
                        @foreach ($escalationTeams as $lvl => $teams)
                            @foreach ($teams as $team)
                                <option value="{{ $team->id }}">L{{ $lvl }} — {{ $team->name }}</option>
                            @endforeach
                        @endforeach
                    </select>
                    <textarea name="remarks" class="input mb-2" rows="2" placeholder="Reason for escalation"></textarea>
                    <button class="btn btn-sm w-full bg-amber-500 text-white hover:bg-amber-600"><x-icon name="arrow-up" class="w-4 h-4" /> Escalate</button>
                </form>
                @php $flatTeams = collect($escalationTeams)->flatten(1); @endphp
                @if ($flatTeams->isNotEmpty())
                    <div class="mt-3 rounded-lg bg-slate-50 p-3 text-xs text-slate-500">
                        <p class="font-semibold text-slate-600 mb-1">GRC teams for this district</p>
                        @foreach ($flatTeams as $team)
                            <p>L{{ $team->level }} · <span class="text-slate-700">{{ $team->name }}</span> — {{ $team->members->count() }} members ({{ $team->womenPercentage() }}% women)</p>
                        @endforeach
                    </div>
                @endif
            </div>
            @endif

            @if ($canResolve)
            <div x-show="tab==='resolve'" x-cloak>
                <form method="POST" action="{{ route('admin.grievances.resolve', $grievance) }}">
                    @csrf
                    <textarea name="resolution" class="input mb-2" rows="4" placeholder="Describe the decision / corrective action taken" required></textarea>
                    <button class="btn btn-sm btn-success w-full"><x-icon name="check-circle" class="w-4 h-4" /> Mark Resolved</button>
                </form>
            </div>
            @endif
        </div>
        @else
            <div class="rounded-lg bg-slate-100 text-slate-600 px-4 py-3 text-sm flex items-center gap-2">
                <x-icon name="lock" class="w-4 h-4" />
                @if (! $canAct) This grievance is closed. No further actions available.
                @elseif (empty($me->allowedGrievanceActions())) Monitoring access — you can view this grievance but cannot take actions.
                @else No actions available for your role at this level.
                @endif
            </div>
        @endif

        <div class="card card-pad">
            <h3 class="font-semibold text-slate-800 flex items-center gap-2 mb-3"><x-icon name="clock" class="w-5 h-5 text-brand-600" /> Timeline / Audit Trail</h3>
            <ol class="relative border-l-2 border-slate-100 ml-2 space-y-4 max-h-[26rem] overflow-y-auto">
                @foreach ($grievance->actions->sortByDesc('created_at') as $a)
                    <li class="ml-4">
                        <span class="absolute -left-[7px] mt-1.5 h-3 w-3 rounded-full bg-brand-600"></span>
                        <div class="text-sm font-medium text-slate-800 capitalize flex items-center gap-2">{{ str_replace('_',' ',$a->action) }}
                            @if ($a->to_level && $a->from_level && $a->to_level != $a->from_level)<span class="badge bg-amber-100 text-amber-700">L{{ $a->from_level }}→L{{ $a->to_level }}</span>@endif
                        </div>
                        @if ($a->remarks)<p class="text-sm text-slate-500">{{ $a->remarks }}</p>@endif
                        <p class="text-xs text-slate-400">{{ $a->user?->name ?? 'System' }} · {{ $a->created_at->format('d M Y, h:i A') }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
</div>
@endsection
