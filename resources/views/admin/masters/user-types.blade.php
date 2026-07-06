@extends('layouts.admin')
@section('title', 'User Types')
@section('heading', 'User Types')

@section('content')
@if ($errors->any())<div class="mb-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 px-4 py-2 text-sm">{{ $errors->first() }}</div>@endif
<div class="grid gap-5 lg:grid-cols-3">
    <div class="card card-pad">
        <h3 class="font-semibold text-slate-800 mb-3">Add User Type</h3>
        <form method="POST" action="{{ route('admin.user-types.store') }}" class="space-y-3">
            @csrf
            <div><label class="label">Name</label><input name="name" class="input" required></div>
            <div><label class="label">Description</label><textarea name="description" class="input" rows="2"></textarea></div>
            <button class="btn btn-primary btn-sm w-full"><x-icon name="plus" class="w-4 h-4" /> Add</button>
        </form>
    </div>
    <div class="lg:col-span-2">
        @foreach ($types as $t)
            <form id="up-{{ $t->id }}" method="POST" action="{{ route('admin.user-types.update', $t) }}">@csrf @method('PUT')</form>
            <form id="del-{{ $t->id }}" method="POST" action="{{ route('admin.user-types.destroy', $t) }}" onsubmit="return confirm('Delete this user type?')">@csrf @method('DELETE')</form>
        @endforeach
        <div class="card overflow-x-auto">
            <table class="table-grm">
                <thead><tr><th>Name</th><th>Description</th><th>Users (Nos)</th><th></th></tr></thead>
                <tbody>
                    @foreach ($types as $t)
                        <tr>
                            <td class="min-w-36">
                                <input form="up-{{ $t->id }}" name="name" value="{{ $t->name }}" class="input">
                                <span class="badge bg-slate-100 text-slate-500 mt-1">{{ $t->slug }}</span>
                            </td>
                            <td><textarea form="up-{{ $t->id }}" name="description" rows="2" class="input">{{ $t->description }}</textarea></td>
                            <td class="text-center"><span class="badge bg-brand-50 text-brand-700">{{ $t->users_count }}</span></td>
                            <td class="whitespace-nowrap">
                                <button form="up-{{ $t->id }}" class="btn btn-sm btn-outline"><x-icon name="check" class="w-4 h-4" /></button>
                                <button form="del-{{ $t->id }}" class="btn btn-sm btn-danger"><x-icon name="trash" class="w-4 h-4" /></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $types->links() }}</div>
    </div>
</div>
@endsection
