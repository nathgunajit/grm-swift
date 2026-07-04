@extends('layouts.admin')
@section('title', 'User Types')
@section('heading', 'User Types')

@section('content')
@if ($errors->any())<div class="alert alert-danger small">{{ $errors->first() }}</div>@endif
<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm"><div class="card-header bg-white">Add User Type</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.user-types.store') }}">
                    @csrf
                    <div class="mb-2"><label class="form-label small">Name</label><input name="name" class="form-control form-control-sm" required></div>
                    <div class="mb-2"><label class="form-label small">Description</label><textarea name="description" class="form-control form-control-sm" rows="2"></textarea></div>
                    <button class="btn btn-sm btn-grm w-100"><i class="bi bi-plus"></i> Add</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        @foreach ($types as $t)
            <form id="up-{{ $t->id }}" method="POST" action="{{ route('admin.user-types.update', $t) }}">@csrf @method('PUT')</form>
            <form id="del-{{ $t->id }}" method="POST" action="{{ route('admin.user-types.destroy', $t) }}" onsubmit="return confirm('Delete this user type?')">@csrf @method('DELETE')</form>
        @endforeach
        <div class="card shadow-sm"><div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light"><tr><th>Name</th><th>Description</th><th>Users</th><th></th></tr></thead>
                <tbody>
                    @foreach ($types as $t)
                        <tr>
                            <td style="min-width:130px"><input form="up-{{ $t->id }}" name="name" value="{{ $t->name }}" class="form-control form-control-sm"><span class="badge bg-light text-muted border mt-1">{{ $t->slug }}</span></td>
                            <td><textarea form="up-{{ $t->id }}" name="description" class="form-control form-control-sm" rows="1">{{ $t->description }}</textarea></td>
                            <td>{{ $t->users_count }}</td>
                            <td class="text-nowrap">
                                <button form="up-{{ $t->id }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-check"></i></button>
                                <button form="del-{{ $t->id }}" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div></div>
        <div class="mt-2">{{ $types->links() }}</div>
    </div>
</div>
@endsection
