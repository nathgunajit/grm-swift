@extends('layouts.admin')
@section('title', 'Blocks')
@section('heading', 'Blocks')

@section('content')
@if ($errors->any())<div class="mb-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 px-4 py-2 text-sm">{{ $errors->first() }}</div>@endif
<div class="grid gap-5 lg:grid-cols-3">
    <div class="card card-pad">
        <h3 class="font-semibold text-slate-800 mb-3">Add Block</h3>
        <form method="POST" action="{{ route('admin.blocks.store') }}" class="space-y-3">
            @csrf
            <div><label class="label">District</label><select name="district_id" class="input" required><option value="">-- Select --</option>@foreach ($districts as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach</select></div>
            <div><label class="label">Name</label><input name="name" class="input" required></div>
            <button class="btn btn-primary btn-sm w-full"><x-icon name="plus" class="w-4 h-4" /> Add</button>
        </form>
    </div>
    <div class="lg:col-span-2">
        @foreach ($blocks as $b)
            <form id="up-{{ $b->id }}" method="POST" action="{{ route('admin.blocks.update', $b) }}">@csrf @method('PUT')</form>
            <form id="del-{{ $b->id }}" method="POST" action="{{ route('admin.blocks.destroy', $b) }}" onsubmit="return confirm('Delete this block?')">@csrf @method('DELETE')</form>
        @endforeach
        <div class="card overflow-x-auto">
            <table class="table-grm">
                <thead><tr><th>Name</th><th>District</th><th></th></tr></thead>
                <tbody>
                    @foreach ($blocks as $b)
                        <tr>
                            <td><input form="up-{{ $b->id }}" name="name" value="{{ $b->name }}" class="input"></td>
                            <td><select form="up-{{ $b->id }}" name="district_id" class="input">@foreach ($districts as $d)<option value="{{ $d->id }}" @selected($b->district_id==$d->id)>{{ $d->name }}</option>@endforeach</select></td>
                            <td class="whitespace-nowrap">
                                <button form="up-{{ $b->id }}" class="btn btn-sm btn-outline"><x-icon name="check" class="w-4 h-4" /></button>
                                <button form="del-{{ $b->id }}" class="btn btn-sm btn-danger"><x-icon name="trash" class="w-4 h-4" /></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $blocks->links() }}</div>
    </div>
</div>
@endsection
