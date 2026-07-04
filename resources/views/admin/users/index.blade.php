@extends('layouts.admin')
@section('title', 'Users')
@section('heading', 'Official Users')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-grm"><i class="bi bi-person-plus"></i> Register User</a>
</div>
<div class="card shadow-sm"><div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light"><tr><th>EMPID</th><th>Name</th><th>Role</th><th>Contact</th><th>Jurisdiction</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @foreach ($users as $u)
                <tr>
                    <td class="small">{{ $u->empid ?? '—' }}</td>
                    <td>{{ $u->name }}<div class="small text-muted">{{ $u->designation }}</div></td>
                    <td><span class="badge bg-secondary">{{ $u->userType?->name ?? '—' }}</span></td>
                    <td class="small">{{ $u->email }}<div>{{ $u->mobile }}</div></td>
                    <td class="small">{{ $u->district?->name }} @if($u->beel)/ {{ $u->beel->name }}@endif</td>
                    <td>@if($u->is_active)<span class="badge bg-success">Active</span>@else<span class="badge bg-secondary">Inactive</span>@endif</td>
                    <td class="text-nowrap">
                        <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}" class="d-inline" onsubmit="return confirm('Delete this user?')">
                            @csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div></div>
<div class="mt-3">{{ $users->links() }}</div>
@endsection
