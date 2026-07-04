@extends('layouts.admin')
@section('title', 'Committees')
@section('heading', 'Grievance Redressal Committees')

@section('content')
@if ($errors->any())<div class="mb-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 px-4 py-2 text-sm">{{ $errors->first() }}</div>@endif
<div class="grid gap-5 lg:grid-cols-3">
    <div class="card card-pad h-fit">
        <h3 class="font-semibold text-slate-800 mb-3">Create Committee</h3>
        <form method="POST" action="{{ route('admin.committees.store') }}" class="space-y-3">
            @csrf
            <div><label class="label">Name</label><input name="name" class="input" required></div>
            <div><label class="label">Level</label><select name="level" class="input" required>
                <option value="1">Level I — Field / Beel</option>
                <option value="2">Level II — Cluster / CPIU</option>
                <option value="3">Level III — PIU</option>
            </select></div>
            <div><label class="label">District</label><select name="district_id" class="input"><option value="">--</option>@foreach ($districts as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach</select></div>
            <div><label class="label">CPIU</label><select name="cpiu_id" class="input"><option value="">--</option>@foreach ($cpius as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach</select></div>
            <button class="btn btn-primary btn-sm w-full"><x-icon name="plus" class="w-4 h-4" /> Create</button>
        </form>
    </div>
    <div class="lg:col-span-2 space-y-5">
        @forelse ($committees as $c)
            <div class="card">
                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 px-5 py-4">
                    <div class="flex items-center gap-2">
                        <span class="badge bg-slate-100 text-slate-600">Level {{ $c->level }}</span>
                        <span class="font-semibold text-slate-800">{{ $c->name }}</span>
                        <span class="text-xs text-slate-400">{{ $c->district?->name }} {{ $c->cpiu?->name }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="badge {{ $c->womenPercentage() >= 30 ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">{{ $c->womenPercentage() }}% women</span>
                        <form method="POST" action="{{ route('admin.committees.destroy', $c) }}" class="inline" onsubmit="return confirm('Delete committee?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><x-icon name="trash" class="w-4 h-4" /></button></form>
                    </div>
                </div>
                <div class="card-pad">
                    <div class="overflow-x-auto">
                        <table class="table-grm mb-4">
                            <thead><tr><th>Name</th><th>Role</th><th>Woman</th><th></th></tr></thead>
                            <tbody>
                                @forelse ($c->members as $m)
                                    <tr>
                                        <td><div class="font-medium text-slate-700">{{ $m->name }}</div><div class="text-xs text-slate-400">{{ $m->designation }}</div></td>
                                        <td class="capitalize text-slate-500">{{ $m->role }}</td>
                                        <td>{{ $m->is_woman ? 'Yes' : 'No' }}</td>
                                        <td><form method="POST" action="{{ route('admin.committees.members.remove', [$c, $m]) }}" onsubmit="return confirm('Remove member?')">@csrf @method('DELETE')<button class="text-rose-500 hover:text-rose-700"><x-icon name="x" class="w-4 h-4" /></button></form></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-slate-400 text-sm">No members yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <form method="POST" action="{{ route('admin.committees.members.add', $c) }}" class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-end">
                        @csrf
                        <div class="sm:col-span-4"><input name="name" class="input" placeholder="Member name" required></div>
                        <div class="sm:col-span-3"><input name="designation" class="input" placeholder="Designation"></div>
                        <div class="sm:col-span-3"><select name="role" class="input">
                            <option value="chairperson">Chairperson</option><option value="convenor">Convenor</option>
                            <option value="member" selected>Member</option><option value="rapporteur">Rapporteur</option>
                        </select></div>
                        <label class="sm:col-span-1 flex items-center gap-1 text-xs"><input type="checkbox" name="is_woman" value="1" class="h-4 w-4 rounded text-brand-600"> Woman</label>
                        <div class="sm:col-span-1"><button class="btn btn-primary btn-sm w-full"><x-icon name="plus" class="w-4 h-4" /></button></div>
                    </form>
                </div>
            </div>
        @empty
            <div class="rounded-lg bg-white ring-1 ring-slate-100 px-4 py-6 text-center text-slate-400">No committees created yet.</div>
        @endforelse
    </div>
</div>
@endsection
