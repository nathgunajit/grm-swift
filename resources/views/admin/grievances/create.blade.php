@extends('layouts.admin')
@section('title', 'Manual Grievance Entry')
@section('heading', 'Manual Grievance Entry')

@section('content')
<p class="text-muted">Register a grievance received offline (verbal, written, drop-box, phone, or meeting). An acknowledgment will be generated.</p>

@if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<form method="POST" action="{{ route('admin.grievances.store') }}" enctype="multipart/form-data" class="card shadow-sm">
    @csrf
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Mode of Receipt <span class="text-danger">*</span></label>
                <select name="mode_of_receipt" class="form-select" required>
                    @foreach (['verbal','written','phone','drop-box','meeting','online'] as $m)
                        <option value="{{ $m }}" @selected(old('mode_of_receipt', 'verbal')===$m)>{{ ucfirst($m) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Category <span class="text-danger">*</span></label>
                <select name="category_id" class="form-select" required>
                    <option value="">-- Select --</option>
                    @foreach ($categories as $c)<option value="{{ $c->id }}" @selected(old('category_id')==$c->id)>{{ $c->code }}. {{ $c->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end gap-3">
                <div class="form-check"><input class="form-check-input" type="checkbox" name="is_anonymous" value="1" id="anon" @checked(old('is_anonymous'))><label class="form-check-label" for="anon">Anonymous</label></div>
                <div class="form-check"><input class="form-check-input" type="checkbox" name="is_confidential" value="1" id="conf" @checked(old('is_confidential'))><label class="form-check-label" for="conf">Confidential</label></div>
            </div>

            <div class="col-md-4"><label class="form-label">Name</label><input type="text" name="name" value="{{ old('name') }}" class="form-control"></div>
            <div class="col-md-2"><label class="form-label">Gender</label>
                <select name="gender" class="form-select"><option value="">--</option>@foreach(['Male','Female','Other'] as $g)<option @selected(old('gender')===$g)>{{ $g }}</option>@endforeach</select>
            </div>
            <div class="col-md-2"><label class="form-label">Age</label><input type="number" name="age" value="{{ old('age') }}" class="form-control"></div>
            <div class="col-md-4"><label class="form-label">Mobile</label><input type="text" name="mobile" value="{{ old('mobile') }}" class="form-control" placeholder="10-digit"></div>

            <div class="col-md-4"><label class="form-label">Email</label><input type="email" name="email" value="{{ old('email') }}" class="form-control"></div>
            <div class="col-md-4"><label class="form-label">Caste / Category</label><input type="text" name="caste" value="{{ old('caste') }}" class="form-control"></div>
            <div class="col-md-4"><label class="form-label">Place / Village <span class="text-danger">*</span></label><input type="text" name="place_village" value="{{ old('place_village') }}" class="form-control" required></div>

            <div class="col-md-8"><label class="form-label">Communication Address</label><textarea name="address" class="form-control" rows="1">{{ old('address') }}</textarea></div>
            <div class="col-md-4"><label class="form-label">Beel <span class="text-danger">*</span></label>
                <select name="beel_id" class="form-select" required><option value="">-- Select --</option>@foreach ($beels as $b)<option value="{{ $b->id }}" @selected(old('beel_id')==$b->id)>{{ $b->name }}</option>@endforeach</select>
            </div>

            <div class="col-12"><label class="form-label">Description <span class="text-danger">*</span></label><textarea name="description" class="form-control" rows="3" required>{{ old('description') }}</textarea></div>
            <div class="col-md-8"><label class="form-label">Documents</label><input type="file" name="documents[]" class="form-control" multiple></div>
        </div>
    </div>
    <div class="card-footer bg-white text-end">
        <button class="btn btn-grm"><i class="bi bi-save"></i> Register Grievance</button>
    </div>
</form>
@endsection
