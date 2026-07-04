@extends('layouts.public')
@section('title', 'Track Complaint — SWIFT GRM Portal')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <h2 class="text-grm mb-3"><i class="bi bi-search"></i> Track Your Grievance</h2>

            <form method="POST" action="{{ route('track.search') }}" class="card card-body shadow-sm border-0 mb-4">
                @csrf
                <div class="input-group input-group-lg">
                    <input type="text" name="query" class="form-control" placeholder="Enter Tracking ID, Acknowledgment No, or Mobile Number"
                           value="{{ old('query', request('q')) }}" required>
                    <button class="btn btn-grm"><i class="bi bi-search"></i> Track</button>
                </div>
            </form>

            @isset($grievance)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div>
                        <span class="h5 text-grm">{{ $grievance->tracking_id }}</span>
                        @if ($grievance->is_sensitive)<span class="badge bg-danger ms-2"><i class="bi bi-shield-lock"></i> Sensitive</span>@endif
                        @if ($grievance->is_confidential)<span class="badge bg-secondary ms-1">Confidential</span>@endif
                    </div>
                    <x-status-badge :status="$grievance->status" class="fs-6" />
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-3"><small class="text-muted d-block">Category</small>{{ $grievance->category?->name ?? '—' }}</div>
                        <div class="col-md-3"><small class="text-muted d-block">Current Level</small>{{ $grievance->levelLabel() }}</div>
                        <div class="col-md-3"><small class="text-muted d-block">Beel</small>{{ $grievance->beel?->name ?? '—' }}</div>
                        <div class="col-md-3"><small class="text-muted d-block">Registered On</small>{{ $grievance->created_at->format('d M Y') }}</div>
                    </div>
                    @unless ($grievance->is_confidential)
                        <p class="mb-3"><small class="text-muted d-block">Description</small>{{ $grievance->description }}</p>
                    @endunless

                    @if ($grievance->due_at && !in_array($grievance->status, ['resolved','closed']))
                        <p class="{{ $grievance->isOverdue() ? 'text-danger' : 'text-muted' }} small">
                            <i class="bi bi-clock"></i> Expected resolution by {{ $grievance->due_at->format('d M Y') }}
                            @if ($grievance->isOverdue()) (overdue) @endif
                        </p>
                    @endif

                    @if ($grievance->resolution)
                        <div class="alert alert-success"><strong>Resolution:</strong> {{ $grievance->resolution }}</div>
                    @endif

                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('grievance.ack', $grievance->tracking_id) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-download"></i> Acknowledgment PDF</a>
                        @if (in_array($grievance->status, ['resolved','closed']))
                            <a href="{{ route('grievance.resolution', $grievance->tracking_id) }}" class="btn btn-sm btn-outline-success"><i class="bi bi-download"></i> Resolution Letter PDF</a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Timeline --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white"><i class="bi bi-clock-history text-grm"></i> Status Timeline</div>
                <div class="card-body">
                    <ul class="timeline mb-0">
                        @foreach ($grievance->actions as $a)
                            <li>
                                <div class="fw-semibold text-capitalize">{{ str_replace('_',' ',$a->action) }}
                                    @if ($a->to_level && $a->from_level && $a->to_level != $a->from_level)
                                        <span class="badge bg-warning text-dark">L{{ $a->from_level }} &rarr; L{{ $a->to_level }}</span>
                                    @endif
                                </div>
                                @if ($a->remarks)<div class="small text-muted">{{ $a->remarks }}</div>@endif
                                <div class="small text-muted">{{ $a->created_at->format('d M Y, h:i A') }}</div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Feedback / Reopen (after resolution) --}}
            @if (in_array($grievance->status, ['resolved','closed']))
                @if ($grievance->feedback)
                    <div class="alert alert-light border"><i class="bi bi-star-fill text-warning"></i> Feedback received — satisfaction: <strong>{{ ucfirst($grievance->feedback->satisfaction) }}</strong>. Thank you.</div>
                @else
                    <div class="row g-4">
                        <div class="col-md-7">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-white"><i class="bi bi-chat-left-text text-grm"></i> Share Your Feedback</div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('grievance.feedback', $grievance->tracking_id) }}">
                                        @csrf
                                        <div class="mb-2"><label class="form-label small mb-1">Were you informed about the GRM process?</label>
                                            <select name="informed" class="form-select form-select-sm" required><option value="1">Yes</option><option value="0">No</option></select></div>
                                        <div class="mb-2"><label class="form-label small mb-1">Was your complaint heard respectfully?</label>
                                            <select name="heard_respectfully" class="form-select form-select-sm" required><option value="1">Yes</option><option value="0">No</option></select></div>
                                        <div class="mb-2"><label class="form-label small mb-1">Was the response time reasonable?</label>
                                            <select name="response_time_ok" class="form-select form-select-sm" required><option value="1">Yes</option><option value="0">No</option></select></div>
                                        <div class="row g-2">
                                            <div class="col-4"><label class="form-label small mb-1">Satisfaction</label>
                                                <select name="satisfaction" class="form-select form-select-sm" required><option value="fully">Fully</option><option value="partly">Partly</option><option value="not">Not</option></select></div>
                                            <div class="col-4"><label class="form-label small mb-1">Transparency</label>
                                                <select name="transparency" class="form-select form-select-sm" required><option value="good">Good</option><option value="average">Average</option><option value="poor">Poor</option></select></div>
                                            <div class="col-4"><label class="form-label small mb-1">Official behaviour</label>
                                                <select name="official_behavior" class="form-select form-select-sm" required><option value="good">Good</option><option value="average">Average</option><option value="poor">Poor</option></select></div>
                                        </div>
                                        <div class="row g-2 mt-1">
                                            <div class="col-6"><label class="form-label small mb-1">Feel safe using GRM again?</label>
                                                <select name="feel_safe" class="form-select form-select-sm" required><option value="1">Yes</option><option value="0">No</option></select></div>
                                            <div class="col-6"><label class="form-label small mb-1">Overall rating (1-5)</label>
                                                <input type="number" name="rating" min="1" max="5" class="form-control form-control-sm"></div>
                                        </div>
                                        <div class="mt-2"><label class="form-label small mb-1">Comments</label>
                                            <textarea name="comments" class="form-control form-control-sm" rows="2"></textarea></div>
                                        <button class="btn btn-grm btn-sm mt-3"><i class="bi bi-send"></i> Submit Feedback &amp; Close</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-white"><i class="bi bi-arrow-repeat text-grm"></i> Not Satisfied?</div>
                                <div class="card-body">
                                    <p class="small text-muted">If you are not satisfied with the resolution, you may reopen the grievance. It will be escalated to the next level for review.</p>
                                    <form method="POST" action="{{ route('grievance.reopen', $grievance->tracking_id) }}">
                                        @csrf
                                        <textarea name="reason" class="form-control form-control-sm mb-2" rows="3" placeholder="Reason for reopening" required></textarea>
                                        <button class="btn btn-outline-danger btn-sm"><i class="bi bi-arrow-repeat"></i> Reopen Grievance</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
            @endisset
        </div>
    </div>
</div>
@endsection
