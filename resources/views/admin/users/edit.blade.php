@extends('layouts.admin')
@section('title', 'Edit User')
@section('heading', 'Edit User — '.$user->name)

@section('content')
@if ($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<div class="row g-4">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="card shadow-sm">
            @csrf @method('PUT')
            <div class="card-body">@include('admin.users._form')</div>
            <div class="card-footer bg-white text-end">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button class="btn btn-grm"><i class="bi bi-save"></i> Update</button>
            </div>
        </form>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white"><i class="bi bi-arrow-left-right text-grm"></i> New Assignment</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.assign', $user) }}">
                    @csrf
                    <div class="mb-2"><label class="form-label small">Role</label>
                        <select name="user_type_id" class="form-select form-select-sm" required>@foreach ($userTypes as $t)<option value="{{ $t->id }}" @selected($user->user_type_id==$t->id)>{{ $t->name }}</option>@endforeach</select></div>
                    <div class="mb-2"><label class="form-label small">District</label>
                        <select name="district_id" class="form-select form-select-sm"><option value="">--</option>@foreach ($districts as $d)<option value="{{ $d->id }}" @selected($user->district_id==$d->id)>{{ $d->name }}</option>@endforeach</select></div>
                    <div class="mb-2"><label class="form-label small">CPIU</label>
                        <select name="cpiu_id" class="form-select form-select-sm"><option value="">--</option>@foreach ($cpius as $c)<option value="{{ $c->id }}" @selected($user->cpiu_id==$c->id)>{{ $c->name }}</option>@endforeach</select></div>
                    <div class="mb-2"><label class="form-label small">Beel</label>
                        <select name="beel_id" class="form-select form-select-sm"><option value="">--</option>@foreach ($beels as $b)<option value="{{ $b->id }}" @selected($user->beel_id==$b->id)>{{ $b->name }}</option>@endforeach</select></div>
                    <div class="row g-2">
                        <div class="col-6"><label class="form-label small">Assign Date</label><input type="date" name="assign_date" value="{{ date('Y-m-d') }}" class="form-control form-control-sm" required></div>
                        <div class="col-6"><label class="form-label small">Relieving Date</label><input type="date" name="relieving_date" class="form-control form-control-sm"></div>
                    </div>
                    <button class="btn btn-sm btn-grm w-100 mt-2"><i class="bi bi-plus"></i> Record Assignment</button>
                </form>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header bg-white"><i class="bi bi-clock-history text-grm"></i> Assignment History</div>
            <ul class="list-group list-group-flush small">
                @forelse ($user->assignments->sortByDesc('assign_date') as $a)
                    <li class="list-group-item">
                        <strong>{{ $a->userType?->name ?? '—' }}</strong><br>
                        <span class="text-muted">{{ optional($a->assign_date)->format('d M Y') }} → {{ optional($a->relieving_date)->format('d M Y') ?? 'present' }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No assignment history.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
