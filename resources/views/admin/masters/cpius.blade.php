@extends('layouts.admin')
@section('title', 'CPIUs')
@section('heading', 'CPIUs')

@section('content')
@if ($errors->any())<div class="mb-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 px-4 py-2 text-sm">{{ $errors->first() }}</div>@endif
<div class="grid gap-5 lg:grid-cols-3">
    <div class="card card-pad">
        <h3 class="font-semibold text-slate-800 mb-3">Add CPIU</h3>
        <form method="POST" action="{{ route('admin.cpius.store') }}" class="space-y-3">
            @csrf
            <div><label class="label">Name</label><input name="name" class="input" required></div>
            <div><label class="label">Code</label><input name="code" class="input"></div>
            <div><label class="label">Zone</label><select name="zone_id" class="input"><option value="">--</option>@foreach ($zones as $z)<option value="{{ $z->id }}">{{ $z->name }}</option>@endforeach</select></div>
            <button class="btn btn-primary btn-sm w-full"><x-icon name="plus" class="w-4 h-4" /> Add</button>
        </form>
    </div>
    <div class="lg:col-span-2">
        @foreach ($cpius as $c)
            <form id="up-{{ $c->id }}" method="POST" action="{{ route('admin.cpius.update', $c) }}">@csrf @method('PUT')</form>
            <form id="del-{{ $c->id }}" method="POST" action="{{ route('admin.cpius.destroy', $c) }}" onsubmit="return confirm('Delete this CPIU?')">@csrf @method('DELETE')</form>
        @endforeach
        <div class="card overflow-x-auto">
            <table class="table-grm">
                <thead><tr><th>Name</th><th>Code</th><th>Zone</th><th>Beels</th><th></th></tr></thead>
                <tbody>
                    @foreach ($cpius as $c)
                        <tr>
                            <td><input form="up-{{ $c->id }}" name="name" value="{{ $c->name }}" class="input"></td>
                            <td><input form="up-{{ $c->id }}" name="code" value="{{ $c->code }}" class="input w-24"></td>
                            <td><select form="up-{{ $c->id }}" name="zone_id" class="input"><option value="">--</option>@foreach ($zones as $z)<option value="{{ $z->id }}" @selected($c->zone_id==$z->id)>{{ $z->name }}</option>@endforeach</select></td>
                            <td>{{ $c->beels_count }}</td>
                            <td class="whitespace-nowrap">
                                <button form="up-{{ $c->id }}" class="btn btn-sm btn-outline"><x-icon name="check" class="w-4 h-4" /></button>
                                <button form="del-{{ $c->id }}" class="btn btn-sm btn-danger"><x-icon name="trash" class="w-4 h-4" /></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $cpius->links() }}</div>
    </div>
</div>
@endsection
