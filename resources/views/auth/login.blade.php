@extends('layouts.public')
@section('title', 'Official Login — SWIFT GRM Portal')

@section('content')
<div class="mx-auto max-w-md px-4 py-12">
    <div class="card card-pad">
        <div class="text-center mb-5">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-brand-100 text-brand-600"><x-icon name="lock" class="w-6 h-6" /></div>
            <h1 class="mt-3 text-xl font-bold text-slate-800">Official Login</h1>
            <p class="text-sm text-slate-500">Access the GRM administration panel.</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 px-4 py-2 text-sm">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4">
            @csrf
            <div>
                <label class="label">Email or Mobile Number</label>
                <input type="text" name="login" value="{{ old('login') }}" class="input" autofocus required>
            </div>
            <div>
                <label class="label">Password</label>
                <input type="password" name="password" class="input" required>
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" class="h-4 w-4 rounded text-brand-600 focus:ring-brand-500"> Remember me
            </label>
            <button class="btn btn-primary w-full"><x-icon name="login" class="w-5 h-5" /> Login</button>
        </form>

        <div class="mt-4 rounded-lg bg-slate-50 border border-slate-200 px-4 py-3 text-xs text-slate-500">
            <strong class="text-slate-700">Demo credentials</strong><br>
            Super Admin: admin@grmswift.local / Admin@123<br>
            Officials (e.g. SSGC): ssgc@grmswift.local / Password@123
        </div>
    </div>
</div>
@endsection
