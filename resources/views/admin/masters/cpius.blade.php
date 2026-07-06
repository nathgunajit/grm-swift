@extends('layouts.admin')
@section('title', 'CPIUs')
@section('heading', 'CPIUs')

@section('content')
@if ($errors->any())<div class="mb-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 px-4 py-2 text-sm">{{ $errors->first() }}</div>@endif

<div class="grid gap-5 lg:grid-cols-3">
    {{-- Add CPIU --}}
    <div class="card card-pad h-fit">
        <h3 class="font-semibold text-slate-800 mb-3">Add CPIU</h3>
        <form method="POST" action="{{ route('admin.cpius.store') }}" class="space-y-3">
            @csrf
            <div><label class="label">Name</label><input name="name" class="input" required></div>
            <div><label class="label">Code</label><input name="code" class="input"></div>
            <div>
                <label class="label">Districts <span class="text-xs text-slate-400">(unassigned only)</span></label>
                <div class="max-h-44 overflow-y-auto rounded-lg border border-slate-200 p-2 space-y-1">
                    @foreach ($districts as $d)
                        @php $taken = $d->cpiu_id !== null; @endphp
                        <label class="flex items-center gap-2 text-sm {{ $taken ? 'text-slate-300' : 'text-slate-700' }}">
                            <input type="checkbox" name="district_ids[]" value="{{ $d->id }}" @disabled($taken) class="h-4 w-4 rounded text-brand-600">
                            {{ $d->name }} @if($taken)<span class="text-[11px] text-slate-400">· {{ $d->cpiu?->name }}</span>@endif
                        </label>
                    @endforeach
                </div>
            </div>
            <button class="btn btn-primary btn-sm w-full"><x-icon name="plus" class="w-4 h-4" /> Add</button>
        </form>
    </div>

    {{-- CPIU list with district assignment --}}
    <div class="lg:col-span-2 space-y-4">
        @forelse ($cpius as $c)
            <div class="card card-pad">
                <form id="del-cpiu-{{ $c->id }}" method="POST" action="{{ route('admin.cpius.destroy', $c) }}" onsubmit="return confirm('Delete this CPIU? Its districts will be unassigned.')">@csrf @method('DELETE')</form>
                <form method="POST" action="{{ route('admin.cpius.update', $c) }}">
                    @csrf @method('PUT')
                    <div class="flex flex-wrap items-end gap-3">
                        <div class="grow"><label class="label">Name</label><input name="name" value="{{ $c->name }}" class="input"></div>
                        <div class="w-24"><label class="label">Code</label><input name="code" value="{{ $c->code }}" class="input"></div>
                        <div class="text-sm text-slate-500 pb-2">Beels: <span class="font-semibold text-slate-700">{{ $c->beels_count }}</span></div>
                    </div>
                    <div class="mt-3">
                        <label class="label">Assigned Districts</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-1.5 rounded-lg border border-slate-200 p-2">
                            @foreach ($districts as $d)
                                @php $ownedByOther = $d->cpiu_id !== null && $d->cpiu_id !== $c->id; @endphp
                                <label class="flex items-center gap-2 text-sm {{ $ownedByOther ? 'text-slate-300' : 'text-slate-700' }}" @if($ownedByOther) title="Assigned to {{ $d->cpiu?->name }}" @endif>
                                    <input type="checkbox" name="district_ids[]" value="{{ $d->id }}"
                                           @checked($d->cpiu_id === $c->id) @disabled($ownedByOther)
                                           class="h-4 w-4 rounded text-brand-600">
                                    {{ $d->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="mt-3 flex justify-end gap-2">
                        <button form="del-cpiu-{{ $c->id }}" class="btn btn-sm btn-danger"><x-icon name="trash" class="w-4 h-4" /></button>
                        <button class="btn btn-sm btn-outline"><x-icon name="check" class="w-4 h-4" /> Save</button>
                    </div>
                </form>
            </div>
        @empty
            <div class="rounded-lg bg-white ring-1 ring-slate-100 px-4 py-6 text-center text-slate-400">No CPIUs yet.</div>
        @endforelse
    </div>
</div>
@endsection
