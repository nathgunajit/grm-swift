@extends('layouts.admin')
@section('title', 'Users')
@section('heading', 'Official Users')

@section('content')
<div class="flex justify-end mb-4">
    <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary"><x-icon name="user-plus" class="w-4 h-4" /> Register User</a>
</div>
<div class="card overflow-x-auto">
    <table class="table-grm">
        <thead><tr><th>EMPID</th><th>Name</th><th>Role</th><th>Contact</th><th>Jurisdiction</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @foreach ($users as $u)
                <tr>
                    <td class="text-slate-500">{{ $u->empid ?? '—' }}</td>
                    <td><div class="font-medium text-slate-800">{{ $u->name }}</div><div class="text-xs text-slate-400">{{ $u->designation }}</div></td>
                    <td><span class="badge bg-slate-100 text-slate-600">{{ $u->userType?->name ?? '—' }}</span></td>
                    <td class="text-slate-500 text-xs">{{ $u->email }}<br>{{ $u->mobile }}</td>
                    <td class="text-slate-500 text-xs">{{ $u->district?->name }} @if($u->beel)/ {{ $u->beel->name }}@endif</td>
                    <td>@if($u->is_active)<span class="badge bg-emerald-100 text-emerald-700">Active</span>@else<span class="badge bg-slate-200 text-slate-600">Inactive</span>@endif</td>
                    <td class="whitespace-nowrap">
                        <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline"><x-icon name="pencil" class="w-4 h-4" /></a>
                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}" class="inline" onsubmit="return confirm('Delete this user?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><x-icon name="trash" class="w-4 h-4" /></button></form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $users->links() }}</div>
@endsection
