@extends('layouts.admin')
@section('title', 'Zones')
@section('heading', 'Zone Management')

@section('content')
@if ($errors->any())<div class="mb-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 px-4 py-2 text-sm">{{ $errors->first() }}</div>@endif
<div class="grid gap-5 lg:grid-cols-3">
    <div class="card card-pad">
        <h3 class="font-semibold text-slate-800 mb-3">Add Zone</h3>
        <form method="POST" action="{{ route('admin.zones.store') }}" class="space-y-3">
            @csrf
            <div><label class="label">Name</label><input name="name" class="input" required></div>
            <div><label class="label">Code</label><input name="code" class="input"></div>
            <div><label class="label">Description</label><textarea name="description" class="input" rows="2"></textarea></div>
            <button class="btn btn-primary btn-sm w-full"><x-icon name="plus" class="w-4 h-4" /> Add</button>
        </form>
    </div>
    <div class="lg:col-span-2">
        @foreach ($zones as $z)
            <form id="up-{{ $z->id }}" method="POST" action="{{ route('admin.zones.update', $z) }}">@csrf @method('PUT')</form>
            <form id="del-{{ $z->id }}" method="POST" action="{{ route('admin.zones.destroy', $z) }}" onsubmit="return confirm('Delete this zone?')">@csrf @method('DELETE')</form>
        @endforeach
        <div class="card overflow-x-auto">
            <table class="table-grm">
                <thead><tr><th>Name</th><th>Code</th><th>Description</th><th>CPIUs</th><th></th></tr></thead>
                <tbody>
                    @foreach ($zones as $z)
                        <tr>
                            <td><input form="up-{{ $z->id }}" name="name" value="{{ $z->name }}" class="input"></td>
                            <td><input form="up-{{ $z->id }}" name="code" value="{{ $z->code }}" class="input w-24"></td>
                            <td><input form="up-{{ $z->id }}" name="description" value="{{ $z->description }}" class="input"></td>
                            <td>{{ $z->cpius_count }}</td>
                            <td class="whitespace-nowrap">
                                <button form="up-{{ $z->id }}" class="btn btn-sm btn-outline"><x-icon name="check" class="w-4 h-4" /></button>
                                <button form="del-{{ $z->id }}" class="btn btn-sm btn-danger"><x-icon name="trash" class="w-4 h-4" /></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $zones->links() }}</div>
    </div>
</div>
@endsection
