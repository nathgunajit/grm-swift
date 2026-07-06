<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SWIFT GRM Portal')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen flex flex-col bg-slate-50 overflow-x-hidden">

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

{{-- Portal header (title + navigation, no logos) --}}
<header x-data="{ open: false }" class="sticky top-0 z-40 bg-gradient-to-r from-brand-900 via-brand-800 to-brand-600 shadow-lg ring-1 ring-black/5">
    <div class="mx-auto max-w-7xl px-4">
        <div class="flex h-16 items-center justify-between gap-4">
            {{-- Portal title --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 text-white group shrink-0">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 ring-1 ring-white/20 shrink-0">
                    <x-icon name="shield-check" class="w-6 h-6 text-accent-400" />
                </span>
                <span class="leading-tight whitespace-nowrap">
                    <span class="block font-display text-base sm:text-xl font-extrabold">SWIFT GRM Portal</span>
                    <span class="hidden sm:block xl:whitespace-nowrap text-[11px] text-brand-50/70">Grievance Redressal Mechanism · Assam SWIFT Project</span>
                </span>
            </a>

            {{-- Desktop nav --}}
            @php
                $nav = [
                    ['home', 'Home', 'home'],
                    ['track', 'Track Complaint', 'search'],
                    ['process', 'GRM Process', 'diagram'],
                    ['contact', 'Contact', 'phone'],
                ];
            @endphp
            <nav class="hidden xl:flex flex-nowrap items-center gap-1 text-sm">
                @foreach ($nav as [$route, $label, $icon])
                    @php $active = request()->routeIs($route); @endphp
                    <a href="{{ route($route) }}" class="group/nav relative flex shrink-0 items-center gap-1.5 whitespace-nowrap rounded-full px-3 py-2 font-medium transition-all duration-200 {{ $active ? 'bg-white/15 text-white shadow-inner' : 'text-brand-50/80 hover:bg-white/10 hover:text-white' }}">
                        <x-icon name="{{ $icon }}" class="w-4 h-4 shrink-0 {{ $active ? 'text-accent-400' : 'text-brand-50/60 group-hover/nav:text-accent-300' }} transition-colors" />
                        {{ $label }}
                    </a>
                @endforeach

                {{-- Primary CTA --}}
                <a href="{{ route('grievance.create') }}" class="ml-1.5 inline-flex shrink-0 items-center gap-1.5 whitespace-nowrap rounded-full bg-accent-500 px-4 py-2 font-semibold text-white shadow-md shadow-accent-500/30 ring-1 ring-accent-400/50 hover:bg-accent-600 hover:shadow-lg hover:-translate-y-px transition-all">
                    <x-icon name="pencil" class="w-4 h-4 shrink-0" /> Register Complaint
                </a>
                <a href="{{ route('login') }}" class="inline-flex shrink-0 items-center gap-1.5 whitespace-nowrap rounded-full border border-white/25 px-3 py-2 font-semibold text-white hover:bg-white/10 transition">
                    <x-icon name="lock" class="w-4 h-4 shrink-0" /> Login
                </a>
            </nav>

            {{-- Mobile toggle --}}
            <button @click="open = !open" class="xl:hidden inline-flex items-center justify-center rounded-lg p-2 text-white hover:bg-white/10 transition" aria-label="Menu">
                <x-icon name="menu" class="w-6 h-6" x-show="!open" />
                <x-icon name="x" class="w-6 h-6" x-show="open" x-cloak />
            </button>
        </div>
    </div>
    {{-- Mobile menu --}}
    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
         class="xl:hidden bg-brand-800/95 backdrop-blur px-3 pb-4 pt-2 space-y-1 border-t border-white/10">
        <a href="{{ route('grievance.create') }}" class="flex items-center gap-2.5 rounded-lg bg-accent-500 px-3 py-2.5 font-semibold text-white shadow-md"><x-icon name="pencil" class="w-5 h-5" /> Register Complaint</a>
        @foreach ($nav as [$route, $label, $icon])
            @php $active = request()->routeIs($route); @endphp
            <a href="{{ route($route) }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2.5 font-medium {{ $active ? 'bg-white/15 text-white' : 'text-brand-50/90 hover:bg-white/10' }}">
                <x-icon name="{{ $icon }}" class="w-5 h-5 {{ $active ? 'text-accent-400' : 'text-brand-50/60' }}" /> {{ $label }}
            </a>
        @endforeach
        <a href="{{ route('login') }}" class="flex items-center gap-2.5 rounded-lg border border-white/20 px-3 py-2.5 font-semibold text-white hover:bg-white/10"><x-icon name="lock" class="w-5 h-5" /> Official Login</a>
    </div>
</header>

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
