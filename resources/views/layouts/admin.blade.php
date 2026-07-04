<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') — SWIFT GRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --grm-primary:#0b5e4f; }
        body { font-family:'Segoe UI',system-ui,sans-serif; background:#f4f7f6; }
        .sidebar { background:#0b5e4f; min-height:100vh; color:#cfe0dc; }
        .sidebar a { color:#cfe0dc; text-decoration:none; display:block; padding:.6rem 1rem; border-radius:.4rem; margin-bottom:.15rem; font-size:.92rem; }
        .sidebar a:hover, .sidebar a.active { background:rgba(255,255,255,.12); color:#fff; }
        .sidebar .brand { color:#fff; font-weight:bold; padding:1rem; font-size:1.1rem; }
        .sidebar .section { font-size:.72rem; text-transform:uppercase; letter-spacing:1px; opacity:.6; padding:.75rem 1rem .25rem; }
        .topbar { background:#fff; border-bottom:1px solid #e2e8e6; }
        .stat-card { border:none; border-radius:.6rem; }
        .text-grm { color:var(--grm-primary); }
        .btn-grm { background:var(--grm-primary); color:#fff; }
        .btn-grm:hover { background:#08483c; color:#fff; }
        .content { padding:1.5rem; }
        @media(max-width:768px){ .sidebar{ min-height:auto; } }
    </style>
    @stack('head')
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block sidebar p-0 collapse show" id="sidebar">
            <div class="brand"><i class="bi bi-water"></i> SWIFT GRM</div>
            <div class="px-2 pb-4">
                @php $role = auth()->user()->role(); @endphp
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a>

                <div class="section">Grievances</div>
                <a href="{{ route('admin.grievances.index') }}" class="{{ request()->routeIs('admin.grievances.*') ? 'active' : '' }}"><i class="bi bi-inbox"></i> Grievances</a>
                @if (in_array($role, ['beel_animator','bdc_facilitator','ssgc','dfdo','super_admin','pmu_admin']))
                    <a href="{{ route('admin.grievances.create') }}"><i class="bi bi-plus-square"></i> Manual Entry</a>
                @endif
                <a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"><i class="bi bi-bar-chart"></i> Reports</a>

                @if (in_array($role, ['super_admin','pmu_admin']))
                    <div class="section">Administration</div>
                    <a href="{{ route('admin.districts.index') }}" class="{{ request()->routeIs('admin.districts.*') ? 'active' : '' }}"><i class="bi bi-geo-alt"></i> Districts</a>
                    <a href="{{ route('admin.blocks.index') }}" class="{{ request()->routeIs('admin.blocks.*') ? 'active' : '' }}"><i class="bi bi-grid"></i> Blocks</a>
                    <a href="{{ route('admin.revenue-circles.index') }}" class="{{ request()->routeIs('admin.revenue-circles.*') ? 'active' : '' }}"><i class="bi bi-grid-3x3"></i> Revenue Circles</a>
                    <a href="{{ route('admin.cpius.index') }}" class="{{ request()->routeIs('admin.cpius.*') ? 'active' : '' }}"><i class="bi bi-diagram-2"></i> CPIUs</a>
                    <a href="{{ route('admin.beels.index') }}" class="{{ request()->routeIs('admin.beels.*') ? 'active' : '' }}"><i class="bi bi-water"></i> Beels</a>
                    <a href="{{ route('admin.committees.index') }}" class="{{ request()->routeIs('admin.committees.*') ? 'active' : '' }}"><i class="bi bi-people"></i> Committees</a>
                    <a href="{{ route('admin.user-types.index') }}" class="{{ request()->routeIs('admin.user-types.*') ? 'active' : '' }}"><i class="bi bi-tags"></i> User Types</a>
                    <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><i class="bi bi-person-badge"></i> Users</a>
                @endif
            </div>
        </nav>

        <main class="col-md-9 col-lg-10 ms-auto px-0">
            <div class="topbar d-flex justify-content-between align-items-center px-3 py-2">
                <button class="btn btn-sm btn-outline-secondary d-md-none" data-bs-toggle="collapse" data-bs-target="#sidebar"><i class="bi bi-list"></i></button>
                <div class="fw-semibold text-grm">@yield('heading', 'Dashboard')</div>
                <div class="dropdown">
                    <a class="dropdown-toggle text-decoration-none text-dark small" data-bs-toggle="dropdown" href="#">
                        <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                        <span class="badge bg-secondary">{{ auth()->user()->userType?->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('home') }}"><i class="bi bi-house"></i> Public Site</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right"></i> Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            @if (session('success'))
                <div class="content pb-0"><div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button class="btn-close" data-bs-dismiss="alert"></button></div></div>
            @endif
            @if (session('error'))
                <div class="content pb-0"><div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button class="btn-close" data-bs-dismiss="alert"></button></div></div>
            @endif

            <div class="content">
                @yield('content')
            </div>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
