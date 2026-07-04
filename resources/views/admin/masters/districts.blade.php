@extends('layouts.admin')
@section('title', 'Districts')
@section('heading', 'Districts')

@section('content')
@if ($errors->any())<div class="mb-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 px-4 py-2 text-sm">{{ $errors->first() }}</div>@endif
<div class="grid gap-5 lg:grid-cols-3">
    <div class="card card-pad">
        <h3 class="font-semibold text-slate-800 mb-3">Add District</h3>
        <form method="POST" action="{{ route('admin.districts.store') }}" class="space-y-3">
            @csrf
            <div><label class="label">Name</label><input name="name" class="input" required></div>
            <div><label class="label">Code</label><input name="code" class="input"></div>
            <button class="btn btn-primary btn-sm w-full"><x-icon name="plus" class="w-4 h-4" /> Add</button>
        </form>
    </div>
    <div class="lg:col-span-2">
        @foreach ($districts as $d)
            <form id="up-{{ $d->id }}" method="POST" action="{{ route('admin.districts.update', $d) }}">@csrf @method('PUT')</form>
            <form id="del-{{ $d->id }}" method="POST" action="{{ route('admin.districts.destroy', $d) }}" onsubmit="return confirm('Delete this district?')">@csrf @method('DELETE')</form>
        @endforeach
        <div class="card overflow-x-auto">
            <table class="table-grm">
                <thead><tr><th>Name</th><th>Code</th><th>Beels</th><th>Active</th><th></th></tr></thead>
                <tbody>
                    @foreach ($districts as $d)
                        <tr>
                            <td><input form="up-{{ $d->id }}" name="name" value="{{ $d->name }}" class="input"></td>
                            <td><input form="up-{{ $d->id }}" name="code" value="{{ $d->code }}" class="input w-24"></td>
                            <td>{{ $d->beels_count }}</td>
                            <td><input form="up-{{ $d->id }}" type="checkbox" name="is_active" value="1" @checked($d->is_active) class="h-4 w-4 rounded text-brand-600"></td>
                            <td class="whitespace-nowrap">
                                <button form="up-{{ $d->id }}" class="btn btn-sm btn-outline"><x-icon name="check" class="w-4 h-4" /></button>
                                <button form="del-{{ $d->id }}" class="btn btn-sm btn-danger"><x-icon name="trash" class="w-4 h-4" /></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $districts->links() }}</div>
    </div>
</div>
@endsection
