@extends('layouts.admin')
@section('title', 'Edit User')
@section('heading', 'Edit User — '.$user->name)

@section('content')
@if ($errors->any())<div class="mb-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 text-sm"><ul class="list-disc list-inside">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<div class="grid gap-5 lg:grid-cols-3">
    <div class="lg:col-span-2">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="card card-pad space-y-4">
            @csrf @method('PUT')
            @include('admin.users._form')
            <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Cancel</a>
                <button class="btn btn-primary"><x-icon name="save" class="w-5 h-5" /> Update</button>
            </div>
        </form>
    </div>
    <div class="space-y-5">
        <div class="card card-pad">
            <h3 class="font-semibold text-slate-800 flex items-center gap-2 mb-3"><x-icon name="refresh" class="w-5 h-5 text-brand-600" /> New Assignment</h3>
            <form method="POST" action="{{ route('admin.users.assign', $user) }}" class="space-y-2">
                @csrf
                <div><label class="label">Role</label><select name="user_type_id" class="input" required>@foreach ($userTypes as $t)<option value="{{ $t->id }}" @selected($user->user_type_id==$t->id)>{{ $t->name }}</option>@endforeach</select></div>
                <div><label class="label">District</label><select name="district_id" class="input"><option value="">--</option>@foreach ($districts as $d)<option value="{{ $d->id }}" @selected($user->district_id==$d->id)>{{ $d->name }}</option>@endforeach</select></div>
                <div><label class="label">CPIU</label><select name="cpiu_id" class="input"><option value="">--</option>@foreach ($cpius as $c)<option value="{{ $c->id }}" @selected($user->cpiu_id==$c->id)>{{ $c->name }}</option>@endforeach</select></div>
                <div><label class="label">Beel</label><select name="beel_id" class="input"><option value="">--</option>@foreach ($beels as $b)<option value="{{ $b->id }}" @selected($user->beel_id==$b->id)>{{ $b->name }}</option>@endforeach</select></div>
                <div class="grid grid-cols-2 gap-2">
                    <div><label class="label">Assign Date</label><input type="date" name="assign_date" value="{{ date('Y-m-d') }}" class="input" required></div>
                    <div><label class="label">Relieving Date</label><input type="date" name="relieving_date" class="input"></div>
                </div>
                <button class="btn btn-primary btn-sm w-full mt-1"><x-icon name="plus" class="w-4 h-4" /> Record Assignment</button>
            </form>
        </div>
        <div class="card">
            <h3 class="font-semibold text-slate-800 flex items-center gap-2 px-5 py-4 border-b border-slate-100"><x-icon name="clock" class="w-5 h-5 text-brand-600" /> Assignment History</h3>
            <ul class="divide-y divide-slate-100 text-sm">
                @forelse ($user->assignments->sortByDesc('assign_date') as $a)
                    <li class="px-5 py-3">
                        <div class="font-medium text-slate-800">{{ $a->userType?->name ?? '—' }}</div>
                        <div class="text-slate-400 text-xs">{{ optional($a->assign_date)->format('d M Y') }} → {{ optional($a->relieving_date)->format('d M Y') ?? 'present' }}</div>
                    </li>
                @empty
                    <li class="px-5 py-3 text-slate-400">No assignment history.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
