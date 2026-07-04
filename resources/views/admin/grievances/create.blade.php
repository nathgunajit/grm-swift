@extends('layouts.admin')
@section('title', 'Manual Grievance Entry')
@section('heading', 'Manual Grievance Entry')

@section('content')
<p class="text-slate-500 mb-4">Register a grievance received offline (verbal, written, drop-box, phone, or meeting). An acknowledgment will be generated.</p>

@if ($errors->any())
    <div class="mb-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 text-sm">
        <ul class="list-disc list-inside">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.grievances.store') }}" enctype="multipart/form-data" class="card card-pad space-y-4">
    @csrf
    <div class="grid gap-4 sm:grid-cols-3">
        <div>
            <label class="label">Mode of Receipt <span class="text-rose-500">*</span></label>
            <select name="mode_of_receipt" class="input" required>
                @foreach (['verbal','written','phone','drop-box','meeting','online'] as $m)<option value="{{ $m }}" @selected(old('mode_of_receipt','verbal')===$m)>{{ ucfirst($m) }}</option>@endforeach
            </select>
        </div>
        <div>
            <label class="label">Category <span class="text-rose-500">*</span></label>
            <select name="category_id" class="input" required>
                <option value="">-- Select --</option>
                @foreach ($categories as $c)<option value="{{ $c->id }}" @selected(old('category_id')==$c->id)>{{ $c->code }}. {{ $c->name }}</option>@endforeach
            </select>
        </div>
        <div class="flex items-end gap-4">
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_anonymous" value="1" @checked(old('is_anonymous')) class="h-4 w-4 rounded text-brand-600"> Anonymous</label>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_confidential" value="1" @checked(old('is_confidential')) class="h-4 w-4 rounded text-brand-600"> Confidential</label>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <div><label class="label">Name</label><input type="text" name="name" value="{{ old('name') }}" class="input"></div>
        <div><label class="label">Gender</label><select name="gender" class="input"><option value="">--</option>@foreach(['Male','Female','Other'] as $g)<option @selected(old('gender')===$g)>{{ $g }}</option>@endforeach</select></div>
        <div><label class="label">Age</label><input type="number" name="age" value="{{ old('age') }}" class="input"></div>
        <div><label class="label">Mobile</label><input type="text" name="mobile" value="{{ old('mobile') }}" class="input" placeholder="10-digit"></div>
        <div><label class="label">Email</label><input type="email" name="email" value="{{ old('email') }}" class="input"></div>
        <div><label class="label">Caste / Category</label><input type="text" name="caste" value="{{ old('caste') }}" class="input"></div>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="sm:col-span-1"><label class="label">Place / Village <span class="text-rose-500">*</span></label><input type="text" name="place_village" value="{{ old('place_village') }}" class="input" required></div>
        <div><label class="label">Beel <span class="text-slate-400 text-xs">(optional)</span></label><select name="beel_id" class="input"><option value="">-- Select --</option>@foreach ($beels as $b)<option value="{{ $b->id }}" @selected(old('beel_id')==$b->id)>{{ $b->name }}</option>@endforeach</select></div>
        <div><label class="label">Communication Address</label><input type="text" name="address" value="{{ old('address') }}" class="input"></div>
    </div>

    <div><label class="label">Description <span class="text-rose-500">*</span></label><textarea name="description" class="input" rows="3" required>{{ old('description') }}</textarea></div>
    <div><label class="label">Documents</label><input type="file" name="documents[]" class="input" multiple></div>

    <div class="flex justify-end border-t border-slate-100 pt-4">
        <button class="btn btn-primary"><x-icon name="save" class="w-5 h-5" /> Register Grievance</button>
    </div>
</form>
@endsection
