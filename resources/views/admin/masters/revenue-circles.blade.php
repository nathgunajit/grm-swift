@extends('layouts.admin')
@section('title', 'Revenue Circles')
@section('heading', 'Revenue Circles')

@section('content')
@if ($errors->any())<div class="alert alert-danger small">{{ $errors->first() }}</div>@endif
<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm"><div class="card-header bg-white">Add Revenue Circle</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.revenue-circles.store') }}">
                    @csrf
                    <div class="mb-2"><label class="form-label small">District</label>
                        <select name="district_id" class="form-select form-select-sm" required>
                            <option value="">-- Select --</option>
                            @foreach ($districts as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach
                        </select></div>
                    <div class="mb-2"><label class="form-label small">Name</label><input name="name" class="form-control form-control-sm" required></div>
                    <button class="btn btn-sm btn-grm w-100"><i class="bi bi-plus"></i> Add</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        @foreach ($circles as $c)
            <form id="up-{{ $c->id }}" method="POST" action="{{ route('admin.revenue-circles.update', $c) }}">@csrf @method('PUT')</form>
            <form id="del-{{ $c->id }}" method="POST" action="{{ route('admin.revenue-circles.destroy', $c) }}" onsubmit="return confirm('Delete this revenue circle?')">@csrf @method('DELETE')</form>
        @endforeach
        <div class="card shadow-sm"><div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light"><tr><th>Name</th><th>District</th><th></th></tr></thead>
                <tbody>
                    @foreach ($circles as $c)
                        <tr>
                            <td><input form="up-{{ $c->id }}" name="name" value="{{ $c->name }}" class="form-control form-control-sm"></td>
                            <td><select form="up-{{ $c->id }}" name="district_id" class="form-select form-select-sm">
                                @foreach ($districts as $d)<option value="{{ $d->id }}" @selected($c->district_id==$d->id)>{{ $d->name }}</option>@endforeach
                            </select></td>
                            <td class="text-nowrap">
                                <button form="up-{{ $c->id }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-check"></i></button>
                                <button form="del-{{ $c->id }}" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div></div>
        <div class="mt-2">{{ $circles->links() }}</div>
    </div>
</div>
@endsection
