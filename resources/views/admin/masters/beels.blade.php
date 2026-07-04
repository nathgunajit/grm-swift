@extends('layouts.admin')
@section('title', 'Beels')
@section('heading', 'Beels')

@section('content')
@if ($errors->any())<div class="alert alert-danger small">{{ $errors->first() }}</div>@endif
<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm"><div class="card-header bg-white">Add Beel</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.beels.store') }}">
                    @csrf
                    <div class="mb-2"><label class="form-label small">Name</label><input name="name" class="form-control form-control-sm" required></div>
                    <div class="mb-2"><label class="form-label small">District</label>
                        <select name="district_id" class="form-select form-select-sm"><option value="">--</option>@foreach ($districts as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach</select></div>
                    <div class="mb-2"><label class="form-label small">Block</label>
                        <select name="block_id" class="form-select form-select-sm"><option value="">--</option>@foreach ($blocks as $b)<option value="{{ $b->id }}">{{ $b->name }}</option>@endforeach</select></div>
                    <div class="mb-2"><label class="form-label small">CPIU</label>
                        <select name="cpiu_id" class="form-select form-select-sm"><option value="">--</option>@foreach ($cpius as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach</select></div>
                    <button class="btn btn-sm btn-grm w-100"><i class="bi bi-plus"></i> Add</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        @foreach ($beels as $b)
            <form id="up-{{ $b->id }}" method="POST" action="{{ route('admin.beels.update', $b) }}">@csrf @method('PUT')</form>
            <form id="del-{{ $b->id }}" method="POST" action="{{ route('admin.beels.destroy', $b) }}" onsubmit="return confirm('Delete this beel?')">@csrf @method('DELETE')</form>
        @endforeach
        <div class="card shadow-sm"><div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light"><tr><th>Name</th><th>District</th><th>CPIU</th><th></th></tr></thead>
                <tbody>
                    @foreach ($beels as $b)
                        <tr>
                            <td><input form="up-{{ $b->id }}" name="name" value="{{ $b->name }}" class="form-control form-control-sm"></td>
                            <td><select form="up-{{ $b->id }}" name="district_id" class="form-select form-select-sm"><option value="">--</option>@foreach ($districts as $d)<option value="{{ $d->id }}" @selected($b->district_id==$d->id)>{{ $d->name }}</option>@endforeach</select></td>
                            <td><select form="up-{{ $b->id }}" name="cpiu_id" class="form-select form-select-sm"><option value="">--</option>@foreach ($cpius as $c)<option value="{{ $c->id }}" @selected($b->cpiu_id==$c->id)>{{ $c->name }}</option>@endforeach</select></td>
                            <td class="text-nowrap">
                                <input form="up-{{ $b->id }}" type="hidden" name="block_id" value="{{ $b->block_id }}">
                                <button form="up-{{ $b->id }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-check"></i></button>
                                <button form="del-{{ $b->id }}" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div></div>
        <div class="mt-2">{{ $beels->links() }}</div>
    </div>
</div>
@endsection
