@extends('layouts.public')
@section('title', 'Official Login — SWIFT GRM Portal')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="text-grm text-center mb-1"><i class="bi bi-person-lock"></i> Official Login</h4>
                    <p class="text-muted text-center small">Access the GRM administration panel.</p>

                    @if ($errors->any())
                        <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST" action="{{ route('login.attempt') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email or Mobile Number</label>
                            <input type="text" name="login" value="{{ old('login') }}" class="form-control" autofocus required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label small" for="remember">Remember me</label>
                        </div>
                        <button class="btn btn-grm w-100"><i class="bi bi-box-arrow-in-right"></i> Login</button>
                    </form>

                    <div class="alert alert-light border mt-3 mb-0 small">
                        <strong>Demo credentials</strong><br>
                        Super Admin: admin@grmswift.local / Admin@123<br>
                        Officials (e.g. SSGC): ssgc@grmswift.local / Password@123
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
