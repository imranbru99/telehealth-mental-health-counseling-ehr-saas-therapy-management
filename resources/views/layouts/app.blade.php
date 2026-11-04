<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Mental Health & Counseling Booking SaaS') - Serenity Health</title>
    <meta name="description" content="@yield('meta_description', 'Complete multi-tenant mental healthcare practice management, therapy appointment booking, and clinical documentation SaaS platform.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="flex flex-col min-h-screen text-slate-800 antialiased selection:bg-teal-500 selection:text-white">

    <!-- Non-Emergency Psychiatric Notice / Crisis Lifeline Banner -->
    <div class="bg-rose-900 text-rose-100 text-xs py-2 px-4 text-center font-medium shadow-inner flex items-center justify-center gap-2">
        <svg class="w-4 h-4 text-rose-300 shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
        </svg>
        <span>
            <strong>Crisis Notice:</strong> This software is not an emergency medical service. If you are experiencing an immediate psychological crisis or suicidal thoughts, please call/text <strong>988</strong> (USA) or visit your nearest emergency room.
        </span>
    </div>

    <!-- Navigation Header -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-teal-700 to-teal-500 flex items-center justify-center text-white shadow-md shadow-teal-700/20 group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-xl font-bold tracking-tight text-slate-900 block leading-tight">Serenity Health</span>
                            <span class="text-xs font-semibold text-teal-700 tracking-wider uppercase block">Mental Health & Booking SaaS</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600">
                    <a href="{{ route('home') }}" class="hover:text-teal-700 transition-colors {{ request()->routeIs('home') ? 'text-teal-700 font-semibold' : '' }}">Home</a>
                    <a href="{{ route('features') }}" class="hover:text-teal-700 transition-colors {{ request()->routeIs('features') ? 'text-teal-700 font-semibold' : '' }}">Features</a>
                    <a href="{{ route('modules') }}" class="hover:text-teal-700 transition-colors {{ request()->routeIs('modules') ? 'text-teal-700 font-semibold' : '' }}">
                        Modules <span class="bg-teal-100 text-teal-800 text-[10px] font-bold px-1.5 py-0.5 rounded-full ml-1">26</span>
                    </a>
                    <a href="{{ route('pricing') }}" class="hover:text-teal-700 transition-colors {{ request()->routeIs('pricing') ? 'text-teal-700 font-semibold' : '' }}">Pricing</a>
                    <a href="{{ route('about') }}" class="hover:text-teal-700 transition-colors {{ request()->routeIs('about') ? 'text-teal-700 font-semibold' : '' }}">About</a>
                    <a href="{{ route('blog.index') }}" class="hover:text-teal-700 transition-colors {{ request()->routeIs('blog.*') ? 'text-teal-700 font-semibold' : '' }}">Blog</a>
                    <a href="{{ route('faq') }}" class="hover:text-teal-700 transition-colors {{ request()->routeIs('faq') ? 'text-teal-700 font-semibold' : '' }}">FAQ</a>
                    <a href="{{ route('contact') }}" class="hover:text-teal-700 transition-colors {{ request()->routeIs('contact') ? 'text-teal-700 font-semibold' : '' }}">Contact</a>
                </nav>

                <!-- Action CTAs -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('booking.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-sm font-semibold shadow-md shadow-teal-700/20 transition-all hover:shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Book Session</span>
                    </a>

                    @auth
                        @if(auth()->user()->isClient() || auth()->user()->isGuardian())
                            <a href="{{ route('portal.dashboard') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-sm font-semibold transition-colors">
                                Client Portal
                            </a>
                        @else
                            <a href="/admin" class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold transition-colors">
                                Admin Panel
                            </a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100" title="Logout">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-3.5 py-2.5 text-slate-600 hover:text-teal-700 text-sm font-medium transition-colors">
                            Sign In / Demo
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Global Flash Notification Alerts -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
            </div>
        </div>
    @endif

    @if(session('info'))
        <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
            <div class="p-4 rounded-xl bg-sky-50 border border-sky-200 text-sky-800 text-sm flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-sky-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('info') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-sky-500 hover:text-sky-700">&times;</button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm shadow-sm">
                <div class="font-bold mb-1">Please correct the following:</div>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Main Page Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Global SaaS Footer -->
    <footer class="bg-slate-900 text-slate-300 pt-16 pb-12 border-t border-slate-800 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-slate-800">
                <!-- Col 1: Platform Overview -->
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-teal-500 flex items-center justify-center text-slate-950 font-black shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-white">Serenity Health</span>
                    </div>
                    <p class="text-sm text-slate-400 mb-6 max-w-sm leading-relaxed">
                        The comprehensive, multi-tenant mental healthcare and counseling practice management SaaS. Empowering individual therapists, group clinics, and enterprise behavioral health centers worldwide.
                    </p>
                    <div class="text-xs text-slate-500">
                        Official Developer: <a href="https://imrandev.bd" target="_blank" class="text-teal-400 hover:underline font-semibold">Imran Ahmed</a> &bull; <a href="mailto:me@imrandev.bd" class="text-teal-400 hover:underline">me@imrandev.bd</a>
                    </div>
                </div>

                <!-- Col 2: Solutions -->
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Practice Solutions</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('booking.index') }}" class="hover:text-teal-400 transition-colors">Online Booking Engine</a></li>
                        <li><a href="{{ route('modules') }}" class="hover:text-teal-400 transition-colors">Clinical SOAP/DAP Notes</a></li>
                        <li><a href="{{ route('features') }}" class="hover:text-teal-400 transition-colors">Telehealth Consultations</a></li>
                        <li><a href="{{ route('modules') }}" class="hover:text-teal-400 transition-colors">Digital Treatment Plans</a></li>
                        <li><a href="{{ route('modules') }}" class="hover:text-teal-400 transition-colors">Informed Consent & Intake</a></li>
                        <li><a href="{{ route('modules') }}" class="hover:text-teal-400 transition-colors">Billing & Invoicing</a></li>
                    </ul>
                </div>

                <!-- Col 3: SaaS Platform -->
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Platform</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('modules') }}" class="hover:text-teal-400 transition-colors">All 26 Modules</a></li>
                        <li><a href="{{ route('pricing') }}" class="hover:text-teal-400 transition-colors">Subscription Pricing</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-teal-400 transition-colors">About Us</a></li>
                        <li><a href="{{ route('blog.index') }}" class="hover:text-teal-400 transition-colors">Clinical Blog (বাংলা / En)</a></li>
                        <li><a href="{{ route('demo.request') }}" class="hover:text-teal-400 transition-colors">Book a Live Demo</a></li>
                        <li><a href="{{ route('login') }}" class="text-teal-400 font-semibold hover:underline">Explore Demo Accounts</a></li>
                    </ul>
                </div>

                <!-- Col 4: Trust & Compliance -->
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Trust & Compliance</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('privacy') }}" class="hover:text-teal-400 transition-colors">Privacy Policy</a></li>
                        <li><a href="{{ route('terms') }}" class="hover:text-teal-400 transition-colors">Terms of Service</a></li>
                        <li><a href="{{ route('cookies') }}" class="hover:text-teal-400 transition-colors">Cookie Policy</a></li>
                        <li><a href="{{ route('data-protection') }}" class="hover:text-teal-400 transition-colors">Data Protection & GDPR</a></li>
                        <li><a href="{{ route('security') }}" class="hover:text-teal-400 transition-colors">HIPAA Security Safeguards</a></li>
                        <li><a href="{{ route('accessibility') }}" class="hover:text-teal-400 transition-colors">Accessibility Statement</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Copyright & Crisis Note -->
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                <div>
                    &copy; {{ date('Y') }} Mental Health & Counseling Booking. Developed by <a href="https://imrandev.bd" target="_blank" class="text-slate-400 hover:text-white">Imran Ahmed</a>. All rights reserved.
                </div>
                <div class="text-center sm:text-right text-[11px] text-slate-500">
                    Technical Privacy & Security Enabled. Not certified legal advice. Professional jurisdictional review required.
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
