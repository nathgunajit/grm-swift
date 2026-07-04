<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SWIFT GRM Portal')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --grm-primary:#0b5e4f; --grm-accent:#1c7c66; }
        body { font-family:'Segoe UI',system-ui,sans-serif; color:#1f2d2b; }
        .navbar-grm { background:var(--grm-primary); }
        .navbar-grm .navbar-brand, .navbar-grm .nav-link { color:#fff !important; }
        .navbar-grm .nav-link:hover { color:#cfe9e1 !important; }
        .hero { background:linear-gradient(135deg,var(--grm-primary),var(--grm-accent)); color:#fff; padding:3.5rem 0; }
        .btn-grm { background:var(--grm-primary); color:#fff; }
        .btn-grm:hover { background:#08483c; color:#fff; }
        .text-grm { color:var(--grm-primary); }
        .stat-card { border-left:4px solid var(--grm-primary); }
        .timeline { list-style:none; padding-left:0; }
        .timeline li { position:relative; padding-left:1.75rem; padding-bottom:1.1rem; border-left:2px solid #cfe0dc; margin-left:.5rem; }
        .timeline li:last-child { border-left-color:transparent; }
        .timeline li::before { content:''; position:absolute; left:-7px; top:2px; width:12px; height:12px; border-radius:50%; background:var(--grm-primary); }
        footer.grm-footer { background:#08302a; color:#cfe0dc; }
        footer.grm-footer a { color:#9fd3c6; text-decoration:none; }
    </style>
    @stack('head')
</head>
<body class="d-flex flex-column min-vh-100 bg-light">
<nav class="navbar navbar-expand-lg navbar-grm shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <i class="bi bi-water"></i> SWIFT GRM Portal
        </a>
        <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('grievance.create') }}">Register Complaint</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('track') }}">Track Complaint</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('process') }}">GRM Process</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('resources') }}">Resources</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('faq') }}">Help &amp; FAQ</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
                <li class="nav-item"><a class="nav-link border rounded px-3 ms-lg-2" href="{{ route('login') }}"><i class="bi bi-person-lock"></i> Official Login</a></li>
            </ul>
        </div>
    </div>
</nav>

@if (session('success'))
    <div class="container mt-3"><div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button class="btn-close" data-bs-dismiss="alert"></button></div></div>
@endif
@if (session('error'))
    <div class="container mt-3"><div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button class="btn-close" data-bs-dismiss="alert"></button></div></div>
@endif

<main class="flex-fill">
    @yield('content')
</main>

<footer class="grm-footer mt-5 py-4">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-5">
                <h6 class="text-white">ARIAS Society — SWIFT Project</h6>
                <p class="small mb-1">Assam Sustainable Wetland and Integrated Fisheries Transformation (SWIFT) Project, financed by the Asian Development Bank (ADB).</p>
                <p class="small mb-0">Agriculture Complex, Khanapara, G.S. Road, Guwahati-781022</p>
            </div>
            <div class="col-md-4">
                <h6 class="text-white">Quick Links</h6>
                <ul class="list-unstyled small">
                    <li><a href="{{ route('grievance.create') }}">Register a Grievance</a></li>
                    <li><a href="{{ route('track') }}">Track Status</a></li>
                    <li><a href="{{ route('process') }}">GRM Process</a></li>
                    <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h6 class="text-white">Contact</h6>
                <p class="small mb-1"><i class="bi bi-telephone"></i> 0361-2332004</p>
                <p class="small mb-0"><i class="bi bi-envelope"></i> spd@arias.in</p>
            </div>
        </div>
        <hr class="border-secondary">
        <p class="small text-center mb-0">&copy; {{ date('Y') }} ARIAS Society, Government of Assam. All grievances are handled free of cost.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
