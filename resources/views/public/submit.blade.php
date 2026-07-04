@extends('layouts.public')
@section('title', 'Register Complaint — SWIFT GRM Portal')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <h2 class="text-grm mb-1"><i class="bi bi-pencil-square"></i> Register a Grievance / Suggestion</h2>
            <p class="text-muted">The SWIFT Project welcomes complaints, suggestions, queries and comments. There is no fee. You may submit anonymously.</p>

            @if ($errors->any())
                <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif

            <form method="POST" action="{{ route('grievance.store') }}" enctype="multipart/form-data" class="card shadow-sm border-0">
                @csrf
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Mode of Receipt <span class="text-danger">*</span></label>
                            <select name="mode_of_receipt" class="form-select" required>
                                <option value="online" @selected(old('mode_of_receipt')==='online')>Online</option>
                                <option value="verbal" @selected(old('mode_of_receipt')==='verbal')>Verbal</option>
                                <option value="written" @selected(old('mode_of_receipt')==='written')>Written</option>
                                <option value="phone" @selected(old('mode_of_receipt')==='phone')>Phone</option>
                                <option value="drop-box" @selected(old('mode_of_receipt')==='drop-box')>Drop Box</option>
                                <option value="meeting" @selected(old('mode_of_receipt')==='meeting')>Meeting</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- Select category --</option>
                                @foreach ($categories as $c)
                                    <option value="{{ $c->id }}" @selected(old('category_id')==$c->id)>{{ $c->code }}. {{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_anonymous" id="anon" value="1" @checked(old('is_anonymous')) onchange="toggleAnon()">
                                <label class="form-check-label" for="anon">Submit anonymously (personal details become optional)</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Name <span class="text-danger req">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">--</option>
                                @foreach (['Male','Female','Other'] as $g)
                                    <option value="{{ $g }}" @selected(old('gender')===$g)>{{ $g }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Age</label>
                            <input type="number" name="age" value="{{ old('age') }}" class="form-control" min="1" max="120">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Mobile <span class="text-danger req">*</span></label>
                            <input type="text" name="mobile" value="{{ old('mobile') }}" class="form-control" placeholder="10-digit mobile">
                            <div class="form-text">No OTP required. Used to contact you for feedback.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Caste / Category</label>
                            <input type="text" name="caste" value="{{ old('caste') }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Communication Address <span class="text-danger req">*</span></label>
                            <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Place / Village <span class="text-danger">*</span></label>
                            <input type="text" name="place_village" value="{{ old('place_village') }}" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Beel <span class="text-danger">*</span></label>
                            <select name="beel_id" class="form-select" required>
                                <option value="">-- Select Beel --</option>
                                @foreach ($beels as $b)
                                    <option value="{{ $b->id }}" @selected(old('beel_id')==$b->id)>{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description of Complaint / Suggestion <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="4" required placeholder="Please provide who, what, where and how.">{{ old('description') }}</textarea>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">Supporting Documents</label>
                            <input type="file" name="documents[]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                            <div class="form-text">Optional. PDF / image / doc, up to 5 MB each.</div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_confidential" id="conf" value="1" @checked(old('is_confidential'))>
                                <label class="form-check-label" for="conf">Mark as Confidential</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white p-3 text-end">
                    <button type="submit" class="btn btn-grm btn-lg"><i class="bi bi-send"></i> Submit Grievance</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleAnon() {
    const anon = document.getElementById('anon').checked;
    document.querySelectorAll('.req').forEach(el => el.style.display = anon ? 'none' : 'inline');
}
toggleAnon();
</script>
@endpush
