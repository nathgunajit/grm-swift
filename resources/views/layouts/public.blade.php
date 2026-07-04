<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SWIFT GRM Portal')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen flex flex-col bg-slate-50">

{{-- Accent top rule --}}
<div class="h-1 bg-gradient-to-r from-accent-500 via-accent-400 to-brand-500"></div>

{{-- Utility bar --}}
<div class="bg-brand-900 text-brand-100/90 text-xs">
    <div class="mx-auto max-w-7xl px-4 h-9 flex items-center justify-between">
        <span class="flex items-center gap-1.5"><x-icon name="globe" class="w-3.5 h-3.5 text-accent-400" /> Government of Assam · ARIAS Society</span>
        <div class="hidden sm:flex items-center gap-5">
            <a href="tel:03612332004" class="flex items-center gap-1.5 hover:text-white transition"><x-icon name="phone" class="w-3.5 h-3.5" /> Helpline: 0361-2332004</a>
            <a href="mailto:spd@arias.in" class="flex items-center gap-1.5 hover:text-white transition"><x-icon name="envelope" class="w-3.5 h-3.5" /> spd@arias.in</a>
        </div>
    </div>
</div>

{{-- Masthead --}}
<header class="bg-white border-b border-slate-100">
    <div class="mx-auto max-w-7xl px-4 py-3.5 flex items-center justify-between gap-4">
        <a href="{{ route('home') }}" class="flex items-center gap-3 sm:gap-5 group">
            <img src="{{ asset('images/arias-logo.png') }}" alt="ARIAS Society" class="h-11 sm:h-14 w-auto object-contain">
            <span class="hidden sm:block h-11 w-px bg-slate-200"></span>
            <span>
                <span class="block font-display text-lg sm:text-2xl font-extrabold text-brand-800 leading-tight group-hover:text-brand-700 transition">SWIFT GRM Portal</span>
                <span class="block text-[11px] sm:text-sm text-slate-500">Grievance Redressal Mechanism · Assam SWIFT Project</span>
            </span>
        </a>
        <div class="flex items-center gap-4 sm:gap-6">
            <img src="{{ asset('images/swift-logo.png') }}" alt="SWIFT Project" class="h-12 sm:h-16 w-auto object-contain">
            <img src="{{ asset('images/assam-govt-logo.png') }}" alt="Government of Assam" class="h-11 sm:h-14 w-auto object-contain">
        </div>
    </div>
</header>

{{-- Main navigation --}}
<nav x-data="{ open: false }" class="sticky top-0 z-40 bg-gradient-to-r from-brand-900 via-brand-800 to-brand-600 shadow-lg ring-1 ring-black/5">
    <div class="mx-auto max-w-7xl px-4">
        <div class="flex h-14 items-center justify-between">
            @php $nav = [['home','Home'],['grievance.create','Register Complaint'],['track','Track Complaint'],['process','GRM Process'],['resources','Resources'],['faq','Help & FAQ'],['contact','Contact']]; @endphp
            <a href="{{ route('home') }}" class="lg:hidden flex items-center gap-2 text-white font-display font-bold"><x-icon name="water" class="w-5 h-5 text-accent-400" /> SWIFT GRM</a>
            <div class="hidden lg:flex items-center gap-0.5 text-sm">
                @foreach ($nav as [$route, $label])
                    @php $active = request()->routeIs($route); @endphp
                    <a href="{{ route($route) }}" class="relative rounded-md px-3.5 py-2 font-medium transition {{ $active ? 'text-white' : 'text-brand-50/90 hover:bg-white/10 hover:text-white' }}">
                        {{ $label }}
                        @if ($active)<span class="absolute inset-x-3 -bottom-0.5 h-0.5 rounded-full bg-accent-400"></span>@endif
                    </a>
                @endforeach
            </div>
            <a href="{{ route('login') }}" class="hidden lg:inline-flex items-center gap-1.5 rounded-lg bg-white/10 border border-white/25 px-3.5 py-1.5 font-semibold text-white hover:bg-white/20 transition">
                <x-icon name="lock" class="w-4 h-4" /> Official Login
            </a>
            <button @click="open = !open" class="lg:hidden text-white p-2 -mr-2" aria-label="Menu">
                <x-icon name="menu" class="w-6 h-6" x-show="!open" />
                <x-icon name="x" class="w-6 h-6" x-show="open" x-cloak />
            </button>
        </div>
    </div>
    {{-- Mobile menu --}}
    <div x-show="open" x-cloak class="lg:hidden bg-brand-800 px-4 pb-4 pt-1 space-y-1 border-t border-white/10">
        @foreach ($nav as [$route, $label])
            <a href="{{ route($route) }}" class="block rounded-md px-3 py-2 font-medium {{ request()->routeIs($route) ? 'bg-white/15 text-white' : 'text-brand-50 hover:bg-white/10' }}">{{ $label }}</a>
        @endforeach
        <a href="{{ route('login') }}" class="mt-1 flex items-center gap-1.5 rounded-md bg-accent-500 px-3 py-2 font-semibold text-white"><x-icon name="lock" class="w-4 h-4" /> Official Login</a>
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
