<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') — SWIFT GRM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="bg-slate-100" x-data="{ sidebar: false }">
<div class="min-h-screen lg:flex">
    {{-- Sidebar --}}
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-brand-900 to-brand-800 text-white transform transition-transform lg:translate-x-0 lg:static lg:inset-auto"
           :class="sidebar ? 'translate-x-0' : '-translate-x-full'">
        <div class="flex items-center gap-2 px-5 h-16 font-display font-bold text-lg border-b border-white/10">
            <x-icon name="water" class="w-6 h-6 text-accent-400" /> SWIFT GRM
        </div>
        <nav class="p-3 space-y-1 overflow-y-auto" style="max-height:calc(100vh-4rem)">
            @php $role = auth()->user()->role(); @endphp
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><x-icon name="dashboard" class="w-5 h-5" /> Dashboard</a>

            <p class="px-3 pt-4 pb-1 text-[11px] uppercase tracking-wider text-brand-100/50">Grievances</p>
            <a href="{{ route('admin.grievances.index') }}" class="sidebar-link {{ request()->routeIs('admin.grievances.*') ? 'active' : '' }}"><x-icon name="inbox" class="w-5 h-5" /> Grievances</a>
            @if (auth()->user()->canGrievance('manual_entry'))
                <a href="{{ route('admin.grievances.create') }}" class="sidebar-link"><x-icon name="plus" class="w-5 h-5" /> Manual Entry</a>
            @endif
            <a href="{{ route('admin.reports.index') }}" class="sidebar-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"><x-icon name="chart" class="w-5 h-5" /> Reports</a>
            <a href="{{ route('admin.notifications.index') }}" class="sidebar-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}"><x-icon name="bell" class="w-5 h-5" /> Notifications
                @if (auth()->user()->unreadNotifications->count())<span class="ml-auto rounded-full bg-rose-500 px-1.5 text-[10px] font-bold text-white">{{ auth()->user()->unreadNotifications->count() }}</span>@endif
            </a>

            @if (in_array($role, ['super_admin','pmu_admin']))
                <p class="px-3 pt-4 pb-1 text-[11px] uppercase tracking-wider text-brand-100/50">Administration</p>
                <a href="{{ route('admin.districts.index') }}" class="sidebar-link {{ request()->routeIs('admin.districts.*') ? 'active' : '' }}"><x-icon name="geo" class="w-5 h-5" /> Districts</a>
                <a href="{{ route('admin.blocks.index') }}" class="sidebar-link {{ request()->routeIs('admin.blocks.*') ? 'active' : '' }}"><x-icon name="grid" class="w-5 h-5" /> Blocks</a>
                <a href="{{ route('admin.revenue-circles.index') }}" class="sidebar-link {{ request()->routeIs('admin.revenue-circles.*') ? 'active' : '' }}"><x-icon name="grid" class="w-5 h-5" /> Revenue Circles</a>
                <a href="{{ route('admin.cpius.index') }}" class="sidebar-link {{ request()->routeIs('admin.cpius.*') ? 'active' : '' }}"><x-icon name="diagram" class="w-5 h-5" /> CPIUs</a>
                <a href="{{ route('admin.beels.index') }}" class="sidebar-link {{ request()->routeIs('admin.beels.*') ? 'active' : '' }}"><x-icon name="water" class="w-5 h-5" /> Beels</a>
                <a href="{{ route('admin.committees.index') }}" class="sidebar-link {{ request()->routeIs('admin.committees.*') ? 'active' : '' }}"><x-icon name="people" class="w-5 h-5" /> Committees</a>
                <a href="{{ route('admin.user-types.index') }}" class="sidebar-link {{ request()->routeIs('admin.user-types.*') ? 'active' : '' }}"><x-icon name="tag" class="w-5 h-5" /> User Types</a>
                <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><x-icon name="user" class="w-5 h-5" /> Users</a>
            @endif
        </nav>
    </aside>

    {{-- Backdrop (mobile) --}}
    <div x-show="sidebar" x-cloak @click="sidebar = false" class="fixed inset-0 z-40 bg-black/40 lg:hidden"></div>

    {{-- Main --}}
    <div class="flex-1 min-w-0">
        <header class="sticky top-0 z-30 flex h-16 items-center justify-between bg-white px-4 shadow-sm">
            <div class="flex items-center gap-3">
                <button @click="sidebar = !sidebar" class="lg:hidden text-slate-500"><x-icon name="menu" class="w-6 h-6" /></button>
                <h1 class="font-display font-semibold text-brand-700">@yield('heading', 'Dashboard')</h1>
            </div>
            <div class="flex items-center gap-2">
            {{-- Notification bell --}}
            @php
                $unread = auth()->user()->unreadNotifications;
                $recent = auth()->user()->notifications()->limit(8)->get();
            @endphp
            <div x-data="{ bell: false }" class="relative">
                <button @click="bell = !bell" class="relative flex h-9 w-9 items-center justify-center rounded-full text-slate-500 hover:bg-slate-100">
                    <x-icon name="bell" class="w-6 h-6" />
                    @if ($unread->count())
                        <span class="absolute -top-0.5 -right-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-rose-600 px-1 text-[10px] font-bold text-white">{{ $unread->count() > 9 ? '9+' : $unread->count() }}</span>
                    @endif
                </button>
                <div x-show="bell" x-cloak @click.outside="bell = false" class="absolute right-0 mt-2 w-80 max-w-[90vw] rounded-lg bg-white shadow-xl ring-1 ring-slate-100 z-40">
                    <div class="flex items-center justify-between border-b border-slate-100 px-4 py-2.5">
                        <span class="text-sm font-semibold text-slate-700">Notifications</span>
                        @if ($unread->count())
                            <form method="POST" action="{{ route('admin.notifications.readAll') }}">
                                @csrf
                                <button class="text-xs text-brand-600 hover:underline">Mark all read</button>
                            </form>
                        @endif
                    </div>
                    <div class="max-h-96 overflow-y-auto divide-y divide-slate-50">
                        @forelse ($recent as $n)
                            <a href="{{ route('admin.notifications.read', $n->id) }}"
                               class="flex gap-3 px-4 py-3 hover:bg-slate-50 {{ $n->read_at ? '' : 'bg-brand-50/40' }}">
                                <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-brand-100 text-brand-700"><x-icon name="{{ $n->data['icon'] ?? 'inbox' }}" class="w-4 h-4" /></span>
                                <span class="min-w-0">
                                    <span class="block text-sm font-medium text-slate-800">{{ $n->data['title'] ?? 'Notification' }}</span>
                                    <span class="block text-xs text-slate-500 truncate">{{ $n->data['body'] ?? '' }}</span>
                                    <span class="block text-[11px] text-slate-400 mt-0.5">{{ $n->created_at->diffForHumans() }}</span>
                                </span>
                            </a>
                        @empty
                            <p class="px-4 py-8 text-center text-sm text-slate-400">No notifications yet.</p>
                        @endforelse
                    </div>
                    <a href="{{ route('admin.notifications.index') }}" class="block border-t border-slate-100 px-4 py-2.5 text-center text-sm text-brand-600 hover:bg-slate-50">View all</a>
                </div>
            </div>
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-2 text-sm text-slate-700">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-100 text-brand-700"><x-icon name="user" class="w-5 h-5" /></span>
                    <span class="hidden sm:block">{{ auth()->user()->name }}</span>
                    <span class="badge bg-slate-100 text-slate-600 hidden sm:inline-flex">{{ auth()->user()->userType?->name }}</span>
                </button>
                <div x-show="open" x-cloak @click.outside="open = false" class="absolute right-0 mt-2 w-44 rounded-lg bg-white shadow-lg ring-1 ring-slate-100 py-1 text-sm">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-slate-50"><x-icon name="home" class="w-4 h-4" /> Public Site</a>
                    <div class="border-t border-slate-100 my-1"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="flex w-full items-center gap-2 px-4 py-2 text-rose-600 hover:bg-slate-50"><x-icon name="logout" class="w-4 h-4" /> Logout</button>
                    </form>
                </div>
            </div>
            </div>
        </header>

        @if (session('success'))
            <div class="px-4 pt-4"><div class="rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 text-sm">{{ session('success') }}</div></div>
        @endif
        @if (session('error'))
            <div class="px-4 pt-4"><div class="rounded-lg bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 text-sm">{{ session('error') }}</div></div>
        @endif

        <main class="p-4 sm:p-6">
            @yield('content')
        </main>
    </div>
</div>
<style>[x-cloak]{display:none!important}</style>
@stack('scripts')
</body>
</html>
