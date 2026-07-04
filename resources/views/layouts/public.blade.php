<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SWIFT GRM Portal')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen flex flex-col">

{{-- Top brand strip --}}
<div class="bg-white border-b border-slate-100">
    <div class="mx-auto max-w-7xl px-4 py-2.5 flex items-center justify-between gap-4">
        <x-brand-bar compact class="justify-start" />
        <div class="hidden sm:block text-right">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-brand-700">ARIAS Society · Govt. of Assam</p>
            <p class="text-[11px] text-slate-500">SWIFT Project — Grievance Redressal Mechanism</p>
        </div>
    </div>
</div>

{{-- Main navigation --}}
<nav x-data="{ open: false }" class="sticky top-0 z-40 bg-gradient-to-r from-brand-800 to-brand-600 shadow-lg">
    <div class="mx-auto max-w-7xl px-4">
        <div class="flex h-14 items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-white font-display font-bold text-lg">
               <img src="images/GRMLOGO.png"> SWIFT GRM
            </a>
            <div class="hidden lg:flex items-center gap-1 text-sm">
                @php $nav = [['home','Home'],['grievance.create','Register Complaint'],['track','Track Complaint'],['process','GRM Process'],['resources','Resources'],['faq','Help & FAQ'],['contact','Contact']]; @endphp
                @foreach ($nav as [$route, $label])
                    <a href="{{ route($route) }}" class="rounded-md px-3 py-2 font-medium text-brand-50 hover:bg-white/10 hover:text-white transition {{ request()->routeIs($route) ? 'bg-white/15 text-white' : '' }}">{{ $label }}</a>
                @endforeach
                <a href="{{ route('login') }}" class="ml-2 inline-flex items-center gap-1.5 rounded-md border border-white/40 px-3 py-1.5 font-semibold text-white hover:bg-white/10">
                    <x-icon name="lock" class="w-4 h-4" /> Official Login
                </a>
            </div>
            <button @click="open = !open" class="lg:hidden text-white p-2" aria-label="Menu">
                <x-icon name="menu" class="w-6 h-6" x-show="!open" />
                <x-icon name="x" class="w-6 h-6" x-show="open" x-cloak />
            </button>
        </div>
    </div>
    {{-- Mobile menu --}}
    <div x-show="open" x-cloak class="lg:hidden bg-brand-800 px-4 pb-4 space-y-1">
        @foreach ($nav as [$route, $label])
            <a href="{{ route($route) }}" class="block rounded-md px-3 py-2 text-brand-50 hover:bg-white/10">{{ $label }}</a>
        @endforeach
        <a href="{{ route('login') }}" class="block rounded-md px-3 py-2 font-semibold text-accent-300 hover:bg-white/10">Official Login</a>
    </div>
</nav>

@if (session('success'))
    <div class="mx-auto max-w-7xl px-4 pt-4">
        <div class="rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 text-sm">{{ session('success') }}</div>
    </div>
@endif
@if (session('error'))
    <div class="mx-auto max-w-7xl px-4 pt-4">
        <div class="rounded-lg bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 text-sm">{{ session('error') }}</div>
    </div>
@endif

<main class="flex-1">
    @yield('content')
</main>

<footer class="mt-16 bg-brand-900 text-brand-100">
    <div class="mx-auto max-w-7xl px-4 py-10">
        <div class="grid gap-8 md:grid-cols-4">
            <div class="md:col-span-2">
                <div class="flex items-center gap-2 text-white font-display font-bold text-lg mb-3">
                    <x-icon name="water" class="w-6 h-6 text-accent-400" /> SWIFT GRM Portal
                </div>
                <p class="text-sm text-brand-100/80 leading-relaxed">Assam Sustainable Wetland and Integrated Fisheries Transformation (SWIFT) Project, financed by the Asian Development Bank (ADB) and implemented by ARIAS Society, Government of Assam.</p>
                <p class="text-sm text-brand-100/70 mt-3">Agriculture Complex, Khanapara, G.S. Road, Guwahati-781022</p>
            </div>
            <div>
                <h6 class="text-white font-semibold mb-3">Quick Links</h6>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('grievance.create') }}" class="hover:text-white">Register a Grievance</a></li>
                    <li><a href="{{ route('track') }}" class="hover:text-white">Track Status</a></li>
                    <li><a href="{{ route('process') }}" class="hover:text-white">GRM Process</a></li>
                    <li><a href="{{ route('privacy') }}" class="hover:text-white">Privacy Policy</a></li>
                </ul>
            </div>
            <div>
                <h6 class="text-white font-semibold mb-3">Contact</h6>
                <ul class="space-y-2 text-sm">
                    <li class="flex items-center gap-2"><x-icon name="phone" class="w-4 h-4" /> 0361-2332004</li>
                    <li class="flex items-center gap-2"><x-icon name="envelope" class="w-4 h-4" /> spd@arias.in</li>
                    <li class="flex items-center gap-2"><x-icon name="globe" class="w-4 h-4" /> www.arias.in</li>
                </ul>
            </div>
        </div>
        <div class="mt-8 border-t border-white/10 pt-5 text-center text-xs text-brand-100/70">
            &copy; {{ date('Y') }} ARIAS Society, Government of Assam. All grievances are handled free of cost.
        </div>
    </div>
</footer>

<style>[x-cloak]{display:none!important}</style>
</body>
</html>
