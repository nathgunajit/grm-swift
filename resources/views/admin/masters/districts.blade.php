@extends('layouts.admin')
@section('title', 'Districts')
@section('heading', 'Districts')

@section('content')
@if ($errors->any())<div class="alert alert-danger small">{{ $errors->first() }}</div>@endif
<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm"><div class="card-header bg-white">Add District</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.districts.store') }}">
                    @csrf
                    <div class="mb-2"><label class="form-label small">Name</label><input name="name" class="form-control form-control-sm" required></div>
                    <div class="mb-2"><label class="form-label small">Code</label><input name="code" class="form-control form-control-sm"></div>
                    <button class="btn btn-sm btn-grm w-100"><i class="bi bi-plus"></i> Add</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        {{-- Forms defined outside the table; inputs bind via the HTML5 form attribute --}}
        @foreach ($districts as $d)
            <form id="up-{{ $d->id }}" method="POST" action="{{ route('admin.districts.update', $d) }}">@csrf @method('PUT')</form>
            <form id="del-{{ $d->id }}" method="POST" action="{{ route('admin.districts.destroy', $d) }}" onsubmit="return confirm('Delete this district?')">@csrf @method('DELETE')</form>
        @endforeach
        <div class="card shadow-sm"><div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light"><tr><th>Name</th><th>Code</th><th>Beels</th><th>Active</th><th></th></tr></thead>
                <tbody>
                    @foreach ($districts as $d)
                        <tr>
                            <td><input form="up-{{ $d->id }}" name="name" value="{{ $d->name }}" class="form-control form-control-sm"></td>
                            <td><input form="up-{{ $d->id }}" name="code" value="{{ $d->code }}" class="form-control form-control-sm" style="width:90px"></td>
                            <td>{{ $d->beels_count }}</td>
                            <td><input form="up-{{ $d->id }}" type="checkbox" name="is_active" value="1" @checked($d->is_active)></td>
                            <td class="text-nowrap">
                                <button form="up-{{ $d->id }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-check"></i></button>
                                <button form="del-{{ $d->id }}" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div></div>
        <div class="mt-2">{{ $districts->links() }}</div>
    </div>
</div>
@endsection
