@extends('layouts.admin')
@section('title', $grievance->tracking_id)
@section('heading', 'Grievance '.$grievance->tracking_id)

@section('content')
@php $role = auth()->user()->role(); $canAct = !in_array($grievance->status, ['closed']); @endphp

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="h5 mb-0 text-grm">{{ $grievance->tracking_id }}</span>
                <div>
                    @if ($grievance->is_sensitive)<span class="badge bg-danger"><i class="bi bi-shield-lock"></i> Sensitive</span>@endif
                    @if ($grievance->is_confidential)<span class="badge bg-secondary">Confidential</span>@endif
                    <x-status-badge :status="$grievance->status" />
                </div>
            </div>
            <div class="card-body">
                @if ($grievance->is_sensitive)
                    <div class="alert alert-danger py-2 small"><i class="bi bi-exclamation-triangle"></i> This is a sensitive case (GBV/SEA-SH or misconduct). Handle confidentially; refer to the Internal Complaints Committee (ICC) where applicable. Do not discuss publicly.</div>
                @endif
                <div class="row g-3">
                    <div class="col-md-4"><small class="text-muted d-block">Complainant</small>{{ $grievance->is_anonymous ? 'Anonymous' : ($grievance->name ?? '—') }}</div>
                    <div class="col-md-4"><small class="text-muted d-block">Mobile</small>{{ $grievance->is_confidential ? '••••••' : ($grievance->mobile ?? '—') }}</div>
                    <div class="col-md-4"><small class="text-muted d-block">Email</small>{{ $grievance->is_confidential ? '••••••' : ($grievance->email ?? '—') }}</div>
                    <div class="col-md-4"><small class="text-muted d-block">Category</small>{{ $grievance->category?->name ?? '—' }}</div>
                    <div class="col-md-4"><small class="text-muted d-block">Mode</small>{{ ucfirst($grievance->mode_of_receipt) }}</div>
                    <div class="col-md-4"><small class="text-muted d-block">Current Level</small>{{ $grievance->levelLabel() }}</div>
                    <div class="col-md-4"><small class="text-muted d-block">Beel</small>{{ $grievance->beel?->name ?? '—' }}</div>
                    <div class="col-md-4"><small class="text-muted d-block">District</small>{{ $grievance->district?->name ?? '—' }}</div>
                    <div class="col-md-4"><small class="text-muted d-block">Place / Village</small>{{ $grievance->place_village }}</div>
                    <div class="col-md-4"><small class="text-muted d-block">Registered</small>{{ $grievance->created_at->format('d M Y') }}</div>
                    <div class="col-md-4"><small class="text-muted d-block">Due</small>
                        <span class="{{ $grievance->isOverdue() ? 'text-danger fw-semibold' : '' }}">{{ optional($grievance->due_at)->format('d M Y') ?? '—' }}{{ $grievance->isOverdue() ? ' (overdue)' : '' }}</span>
                    </div>
                    <div class="col-12"><small class="text-muted d-block">Description</small>{{ $grievance->description }}</div>
                </div>

                @if ($grievance->documents->count())
                    <hr>
                    <small class="text-muted d-block mb-1">Attachments</small>
                    @foreach ($grievance->documents as $doc)
                        <a href="{{ Storage::url($doc->path) }}" target="_blank" class="badge bg-light text-dark border text-decoration-none"><i class="bi bi-paperclip"></i> {{ $doc->original_name }}</a>
                    @endforeach
                @endif

                @if ($grievance->resolution)
                    <div class="alert alert-success mt-3 mb-0"><strong>Resolution:</strong> {{ $grievance->resolution }}</div>
                @endif
            </div>
            <div class="card-footer bg-white">
                <a href="{{ route('grievance.ack', $grievance->tracking_id) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-download"></i> Acknowledgment PDF</a>
                @if (in_array($grievance->status, ['resolved','closed']))
                    <a href="{{ route('grievance.resolution', $grievance->tracking_id) }}" class="btn btn-sm btn-outline-success"><i class="bi bi-download"></i> Resolution PDF</a>
                @endif
            </div>
        </div>

        @if ($grievance->feedback)
            <div class="card shadow-sm">
                <div class="card-header bg-white"><i class="bi bi-star text-warning"></i> Complainant Feedback</div>
                <div class="card-body small">
                    <div class="row g-2">
                        <div class="col-4">Satisfaction: <strong>{{ ucfirst($grievance->feedback->satisfaction) }}</strong></div>
                        <div class="col-4">Transparency: <strong>{{ ucfirst($grievance->feedback->transparency) }}</strong></div>
                        <div class="col-4">Behaviour: <strong>{{ ucfirst($grievance->feedback->official_behavior) }}</strong></div>
                        <div class="col-4">Rating: <strong>{{ $grievance->feedback->rating ?? '—' }}/5</strong></div>
                        <div class="col-4">Felt safe: <strong>{{ $grievance->feedback->feel_safe ? 'Yes' : 'No' }}</strong></div>
                    </div>
                    @if ($grievance->feedback->comments)<div class="mt-2">"{{ $grievance->feedback->comments }}"</div>@endif
                </div>
            </div>
        @endif
    </div>

    <div class="col-lg-5">
        {{-- Actions --}}
        @if ($canAct)
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white"><i class="bi bi-tools text-grm"></i> Take Action</div>
            <div class="card-body">
                <ul class="nav nav-tabs nav-fill small mb-3" role="tablist">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#t-comment">Comment</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#t-escalate">Escalate</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#t-resolve">Resolve</button></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="t-comment">
                        @if ($grievance->status === 'registered')
                            <form method="POST" action="{{ route('admin.grievances.review', $grievance) }}" class="mb-2">
                                @csrf
                                <button class="btn btn-sm btn-outline-primary w-100"><i class="bi bi-hourglass"></i> Mark Under Review</button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('admin.grievances.comment', $grievance) }}">
                            @csrf
                            <textarea name="remarks" class="form-control form-control-sm mb-2" rows="3" placeholder="Add a review comment / note" required></textarea>
                            <button class="btn btn-sm btn-grm w-100"><i class="bi bi-chat-left-text"></i> Add Comment</button>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="t-escalate">
                        @if ($grievance->current_level < 3)
                            <form method="POST" action="{{ route('admin.grievances.escalate', $grievance) }}">
                                @csrf
                                <p class="small text-muted">Escalate to Level {{ $grievance->current_level + 1 }} (next GRC tier).</p>
                                <textarea name="remarks" class="form-control form-control-sm mb-2" rows="3" placeholder="Reason for escalation"></textarea>
                                <button class="btn btn-sm btn-warning w-100"><i class="bi bi-arrow-up-circle"></i> Escalate to Level {{ $grievance->current_level + 1 }}</button>
                            </form>
                        @else
                            <p class="small text-muted mb-0">This grievance is already at the highest level (PIU). It cannot be escalated further.</p>
                        @endif
                    </div>
                    <div class="tab-pane fade" id="t-resolve">
                        <form method="POST" action="{{ route('admin.grievances.resolve', $grievance) }}">
                            @csrf
                            <textarea name="resolution" class="form-control form-control-sm mb-2" rows="4" placeholder="Describe the decision / corrective action taken" required></textarea>
                            <button class="btn btn-sm btn-success w-100"><i class="bi bi-check-circle"></i> Mark Resolved</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @else
            <div class="alert alert-secondary small"><i class="bi bi-lock"></i> This grievance is closed. No further actions available.</div>
        @endif

        {{-- Timeline --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white"><i class="bi bi-clock-history text-grm"></i> Timeline / Audit Trail</div>
            <div class="card-body" style="max-height:420px;overflow-y:auto;">
                <ul class="list-unstyled mb-0">
                    @foreach ($grievance->actions->sortByDesc('created_at') as $a)
                        <li class="border-start ps-3 pb-3" style="border-color:#cfe0dc !important;">
                            <div class="fw-semibold text-capitalize small">{{ str_replace('_',' ',$a->action) }}
                                @if ($a->to_level && $a->from_level && $a->to_level != $a->from_level)
                                    <span class="badge bg-warning text-dark">L{{ $a->from_level }}&rarr;L{{ $a->to_level }}</span>
                                @endif
                            </div>
                            @if ($a->remarks)<div class="small text-muted">{{ $a->remarks }}</div>@endif
                            <div class="text-muted" style="font-size:.72rem">
                                {{ $a->user?->name ?? 'System' }} · {{ $a->created_at->format('d M Y, h:i A') }}
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
