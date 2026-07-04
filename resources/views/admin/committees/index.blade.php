@extends('layouts.admin')
@section('title', 'Committees')
@section('heading', 'Grievance Redressal Committees')

@section('content')
@if ($errors->any())<div class="alert alert-danger small">{{ $errors->first() }}</div>@endif
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card shadow-sm"><div class="card-header bg-white">Create Committee</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.committees.store') }}">
                    @csrf
                    <div class="mb-2"><label class="form-label small">Name</label><input name="name" class="form-control form-control-sm" required></div>
                    <div class="mb-2"><label class="form-label small">Level</label>
                        <select name="level" class="form-select form-select-sm" required>
                            <option value="1">Level I — Field / Beel</option>
                            <option value="2">Level II — Cluster / CPIU</option>
                            <option value="3">Level III — PIU</option>
                        </select></div>
                    <div class="mb-2"><label class="form-label small">District</label>
                        <select name="district_id" class="form-select form-select-sm"><option value="">--</option>@foreach ($districts as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach</select></div>
                    <div class="mb-2"><label class="form-label small">CPIU</label>
                        <select name="cpiu_id" class="form-select form-select-sm"><option value="">--</option>@foreach ($cpius as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach</select></div>
                    <button class="btn btn-sm btn-grm w-100"><i class="bi bi-plus"></i> Create</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        @forelse ($committees as $c)
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div><span class="badge bg-secondary">Level {{ $c->level }}</span> <strong>{{ $c->name }}</strong>
                        <span class="small text-muted">{{ $c->district?->name }} {{ $c->cpiu?->name }}</span>
                    </div>
                    <div>
                        <span class="badge {{ $c->womenPercentage() >= 30 ? 'bg-success' : 'bg-danger' }}">{{ $c->womenPercentage() }}% women</span>
                        <form method="POST" action="{{ route('admin.committees.destroy', $c) }}" class="d-inline" onsubmit="return confirm('Delete committee?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-3">
                        <thead><tr><th>Name</th><th>Role</th><th>Woman</th><th></th></tr></thead>
                        <tbody>
                            @forelse ($c->members as $m)
                                <tr>
                                    <td>{{ $m->name }}<div class="small text-muted">{{ $m->designation }}</div></td>
                                    <td class="text-capitalize">{{ $m->role }}</td>
                                    <td>{{ $m->is_woman ? 'Yes' : 'No' }}</td>
                                    <td><form method="POST" action="{{ route('admin.committees.members.remove', [$c, $m]) }}" onsubmit="return confirm('Remove member?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger py-0"><i class="bi bi-x"></i></button></form></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-muted small">No members yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <form method="POST" action="{{ route('admin.committees.members.add', $c) }}" class="row g-2 align-items-end">
                        @csrf
                        <div class="col-md-4"><input name="name" class="form-control form-control-sm" placeholder="Member name" required></div>
                        <div class="col-md-3"><input name="designation" class="form-control form-control-sm" placeholder="Designation"></div>
                        <div class="col-md-3"><select name="role" class="form-select form-select-sm">
                            <option value="chairperson">Chairperson</option><option value="convenor">Convenor</option>
                            <option value="member" selected>Member</option><option value="rapporteur">Rapporteur</option>
                        </select></div>
                        <div class="col-md-1"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_woman" value="1" title="Woman"></div></div>
                        <div class="col-md-1"><button class="btn btn-sm btn-grm"><i class="bi bi-plus"></i></button></div>
                    </form>
                </div>
            </div>
        @empty
            <div class="alert alert-light border">No committees created yet.</div>
        @endforelse
    </div>
</div>
@endsection
