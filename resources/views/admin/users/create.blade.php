@extends('layouts.admin')
@section('title', 'Register User')
@section('heading', 'Register Official User')

@section('content')
@if ($errors->any())<div class="mb-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 text-sm"><ul class="list-disc list-inside">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ route('admin.users.store') }}" class="card card-pad space-y-4">
    @csrf
    @include('admin.users._form')
    <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Cancel</a>
        <button class="btn btn-primary"><x-icon name="save" class="w-5 h-5" /> Register &amp; Assign</button>
    </div>
</form>
@endsection
