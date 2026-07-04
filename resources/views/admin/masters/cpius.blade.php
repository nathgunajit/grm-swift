@extends('layouts.admin')
@section('title', 'CPIUs')
@section('heading', 'CPIUs')

@section('content')
@if ($errors->any())<div class="alert alert-danger small">{{ $errors->first() }}</div>@endif
<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm"><div class="card-header bg-white">Add CPIU</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.cpius.store') }}">
                    @csrf
                    <div class="mb-2"><label class="form-label small">Name</label><input name="name" class="form-control form-control-sm" required></div>
                    <div class="mb-2"><label class="form-label small">Code</label><input name="code" class="form-control form-control-sm"></div>
                    <button class="btn btn-sm btn-grm w-100"><i class="bi bi-plus"></i> Add</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        @foreach ($cpius as $c)
            <form id="up-{{ $c->id }}" method="POST" action="{{ route('admin.cpius.update', $c) }}">@csrf @method('PUT')</form>
            <form id="del-{{ $c->id }}" method="POST" action="{{ route('admin.cpius.destroy', $c) }}" onsubmit="return confirm('Delete this CPIU?')">@csrf @method('DELETE')</form>
        @endforeach
        <div class="card shadow-sm"><div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light"><tr><th>Name</th><th>Code</th><th>Beels</th><th></th></tr></thead>
                <tbody>
                    @foreach ($cpius as $c)
                        <tr>
                            <td><input form="up-{{ $c->id }}" name="name" value="{{ $c->name }}" class="form-control form-control-sm"></td>
                            <td><input form="up-{{ $c->id }}" name="code" value="{{ $c->code }}" class="form-control form-control-sm" style="width:90px"></td>
                            <td>{{ $c->beels_count }}</td>
                            <td class="text-nowrap">
                                <button form="up-{{ $c->id }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-check"></i></button>
                                <button form="del-{{ $c->id }}" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div></div>
        <div class="mt-2">{{ $cpius->links() }}</div>
    </div>
</div>
@endsection
