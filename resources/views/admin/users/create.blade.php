@extends('layouts.admin')
@section('title', 'Register User')
@section('heading', 'Register Official User')

@section('content')
@if ($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ route('admin.users.store') }}" class="card shadow-sm">
    @csrf
    <div class="card-body">@include('admin.users._form')</div>
    <div class="card-footer bg-white text-end">
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button class="btn btn-grm"><i class="bi bi-save"></i> Register &amp; Assign</button>
    </div>
</form>
@endsection
