<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Client Portal' }} - {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans text-slate-800 antialiased flex flex-col">

    <!-- Crisis Lifeline Banner -->
    <aside aria-label="Crisis Helpline Notice" class="bg-amber-600 text-white text-xs py-2 px-4 text-center font-medium shadow-inner flex items-center justify-center gap-2">
        <svg class="w-4 h-4 shrink-0 text-amber-200" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
        <span><strong>Urgent Crisis Warning:</strong> If you are experiencing suicidal thoughts or emotional distress, dial or text <strong class="underline decoration-white font-bold tracking-wider">988</strong> for free, confidential, 24/7 support.</span>
    </aside>

    <!-- Top Navigation Bar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand / Portal Title -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('portal.dashboard') }}" class="flex items-center gap-2">
                        <div class="w-9 h-9 rounded-xl bg-teal-600 text-white flex items-center justify-center font-extrabold text-lg shadow-sm">
                            M
                        </div>
                        <div>
                            <span class="text-base font-bold text-slate-900 leading-tight block">Client Health Portal</span>
                            <span class="text-[10px] font-semibold tracking-wider text-teal-600 uppercase">Serenity Care Network</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Tabs -->
                <nav class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('portal.dashboard') }}" class="px-3 py-2 rounded-lg text-xs font-semibold {{ request()->routeIs('portal.dashboard') ? 'bg-teal-50 text-teal-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">Dashboard</a>
                    <a href="{{ route('portal.appointments') }}" class="px-3 py-2 rounded-lg text-xs font-semibold {{ request()->routeIs('portal.appointments*') ? 'bg-teal-50 text-teal-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">My Appointments</a>
                    <a href="{{ route('portal.invoices') }}" class="px-3 py-2 rounded-lg text-xs font-semibold {{ request()->routeIs('portal.invoices') ? 'bg-teal-50 text-teal-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">Billing & Invoices</a>
                    <a href="{{ route('portal.documents') }}" class="px-3 py-2 rounded-lg text-xs font-semibold {{ request()->routeIs('portal.documents') ? 'bg-teal-50 text-teal-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">Clinical Documents</a>
                    <a href="{{ route('portal.consents') }}" class="px-3 py-2 rounded-lg text-xs font-semibold {{ request()->routeIs('portal.consents') ? 'bg-teal-50 text-teal-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">Consents</a>
                    <a href="{{ route('portal.messages') }}" class="px-3 py-2 rounded-lg text-xs font-semibold {{ request()->routeIs('portal.messages') ? 'bg-teal-50 text-teal-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">Messages</a>
                    <a href="{{ route('portal.profile') }}" class="px-3 py-2 rounded-lg text-xs font-semibold {{ request()->routeIs('portal.profile') ? 'bg-teal-50 text-teal-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">Profile</a>
                </nav>

                <!-- Actions / User Menu -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('booking.index') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-teal-600 hover:bg-teal-700 text-white text-xs font-semibold transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Book Session
                    </a>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-slate-100 focus:outline-none">
                            <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-800 flex items-center justify-center font-bold text-xs">
                                {{ substr(auth()->user()->name ?? 'C', 0, 1) }}
                            </div>
                            <span class="hidden sm:inline text-xs font-semibold text-slate-700">{{ auth()->user()->name }}</span>
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div 
                            x-show="open" 
                            @click.away="open = false" 
                            x-cloak 
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-200 py-1 z-50 text-xs"
                        >
                            <div class="px-4 py-2 border-b border-slate-100">
                                <div class="font-semibold text-slate-800">{{ auth()->user()->name }}</div>
                                <div class="text-[11px] text-slate-400 truncate">{{ auth()->user()->email }}</div>
                            </div>
                            <a href="{{ route('portal.profile') }}" class="block px-4 py-2 text-slate-700 hover:bg-slate-50">Account Settings</a>
                            @if(auth()->user()->isPlatformAdmin() || auth()->user()->hasRole('Organization Owner') || auth()->user()->hasRole('Therapist / Counselor') || auth()->user()->hasRole('Clinic Manager'))
                                <a href="/admin" class="block px-4 py-2 text-teal-700 font-semibold hover:bg-teal-50">Switch to Admin Panel</a>
                            @endif
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">Sign Out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile subnavigation bar -->
        <div class="md:hidden border-t border-slate-200 px-4 py-2 flex items-center justify-around bg-slate-50 overflow-x-auto text-[11px] font-semibold text-slate-600">
            <a href="{{ route('portal.dashboard') }}" class="px-2 py-1 {{ request()->routeIs('portal.dashboard') ? 'text-teal-700 font-bold' : '' }}">Dashboard</a>
            <a href="{{ route('portal.appointments') }}" class="px-2 py-1 {{ request()->routeIs('portal.appointments*') ? 'text-teal-700 font-bold' : '' }}">Appointments</a>
            <a href="{{ route('portal.invoices') }}" class="px-2 py-1 {{ request()->routeIs('portal.invoices') ? 'text-teal-700 font-bold' : '' }}">Invoices</a>
            <a href="{{ route('portal.documents') }}" class="px-2 py-1 {{ request()->routeIs('portal.documents') ? 'text-teal-700 font-bold' : '' }}">Docs</a>
            <a href="{{ route('portal.messages') }}" class="px-2 py-1 {{ request()->routeIs('portal.messages') ? 'text-teal-700 font-bold' : '' }}">Messages</a>
            <a href="{{ route('portal.profile') }}" class="px-2 py-1 {{ request()->routeIs('portal.profile') ? 'text-teal-700 font-bold' : '' }}">Profile</a>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Flash Notification -->
            @if(session('success'))
                <div class="mb-6 rounded-xl bg-teal-50 border border-teal-200 p-4 flex items-center justify-between text-teal-900 text-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4 flex items-center justify-between text-red-900 text-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @yield('portal_content')
        </div>
    </main>

    <!-- Minimal Secure Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                &copy; {{ date('Y') }} Mental Health & Counseling SaaS. Protected under HIPAA & GDPR Protocols.
            </div>
            <div class="flex items-center gap-4 text-slate-400">
                <a href="{{ route('legal.privacy') }}" class="hover:text-slate-600">Privacy Policy</a>
                <a href="{{ route('legal.security') }}" class="hover:text-slate-600">Security Safeguards</a>
                <a href="{{ route('contact') }}" class="hover:text-slate-600">Clinical Support</a>
            </div>
        </div>
    </footer>

</body>
</html>
