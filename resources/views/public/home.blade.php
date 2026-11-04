@extends('layouts.app')

@section('title', 'Serenity Health - Complete Mental Healthcare Practice Management SaaS')

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-b from-teal-50/60 via-white to-slate-50 pt-16 pb-24 lg:pt-24 lg:pb-32 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-teal-100 text-teal-800 text-xs font-semibold mb-6 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-teal-600 animate-pulse"></span>
                <span>Modular Multi-Tenant Mental Healthcare SaaS Architecture</span>
            </div>

            <!-- Headline -->
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-[1.15] mb-6">
                One Complete Platform to Manage Your <span class="text-teal-700 bg-gradient-to-r from-teal-700 to-teal-500 bg-clip-text text-transparent">Mental Health Practice</span>.
            </h1>

            <!-- Description -->
            <p class="text-lg sm:text-xl text-slate-600 mb-10 leading-relaxed font-normal">
                Manage appointments, practitioners, clients, therapy sessions, clinical documentation and business operations from one secure, flexible platform.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-12">
                <a href="{{ route('booking.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-xl bg-teal-700 hover:bg-teal-800 text-white font-semibold text-base shadow-lg shadow-teal-700/25 transition-all transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Book a Session</span>
                </a>

                <a href="#demo-accounts" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-xl bg-white border border-slate-300 hover:bg-slate-50 text-slate-800 font-semibold text-base shadow-sm transition-all">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span>One-Click Demo Logins</span>
                </a>

                <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-base shadow-md transition-all">
                    <span>Start Free Trial</span>
                </a>
            </div>

            <!-- Trust Highlights -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-left pt-8 border-t border-slate-200/80">
                <div class="p-3 bg-white rounded-xl border border-slate-100 shadow-sm">
                    <div class="text-xs text-slate-500 font-medium">Data Isolation</div>
                    <div class="text-sm font-bold text-slate-900 mt-0.5">Strict Tenant Scoping</div>
                </div>
                <div class="p-3 bg-white rounded-xl border border-slate-100 shadow-sm">
                    <div class="text-xs text-slate-500 font-medium">Clinical Records</div>
                    <div class="text-sm font-bold text-slate-900 mt-0.5">SOAP / DAP / BIRP Notes</div>
                </div>
                <div class="p-3 bg-white rounded-xl border border-slate-100 shadow-sm">
                    <div class="text-xs text-slate-500 font-medium">Virtual Care</div>
                    <div class="text-sm font-bold text-slate-900 mt-0.5">Integrated Telehealth</div>
                </div>
                <div class="p-3 bg-white rounded-xl border border-slate-100 shadow-sm">
                    <div class="text-xs text-slate-500 font-medium">Compliance</div>
                    <div class="text-sm font-bold text-slate-900 mt-0.5">Audit Trails & Consent</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ONE-CLICK DEMO ACCOUNTS DRAWER / SHOWCASE (Mandatory Section 13) -->
<section id="demo-accounts" class="py-16 bg-slate-100 border-b border-slate-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="text-xs font-bold uppercase tracking-wider text-teal-700 bg-teal-50 px-2.5 py-1 rounded-md border border-teal-200">
                Instant Evaluator Access
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
                10 Configured Demo Accounts
            </h2>
            <p class="text-sm text-slate-600 mt-1">
                Demo Organization: <strong>Serenity Mental Wellness Center</strong> &bull; Shared Password: <code class="bg-white px-2 py-0.5 rounded border border-slate-200 text-teal-800 font-bold">Demo@12345</code>
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- 1. Super Admin -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider bg-purple-100 text-purple-800 px-2 py-0.5 rounded">Platform Control</span>
                    <h3 class="font-bold text-slate-900 mt-2 text-base">Super Admin</h3>
                    <p class="text-xs text-slate-500 mt-1">Full SaaS platform management, tenant controls, subscriptions & 26 modules registry.</p>
                    <div class="mt-3 text-xs font-mono text-slate-600 bg-slate-50 p-1.5 rounded truncate">
                        superadmin@demo.mhcb.test
                    </div>
                </div>
                <form method="POST" action="{{ route('login.submit') }}" class="mt-4">
                    @csrf
                    <input type="hidden" name="email" value="superadmin@demo.mhcb.test">
                    <input type="hidden" name="password" value="Demo@12345">
                    <button type="submit" class="w-full py-2 px-3 rounded-lg bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold shadow-sm transition-colors text-center">
                        Login as Super Admin &rarr;
                    </button>
                </form>
            </div>

            <!-- 2. Organization Owner -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider bg-teal-100 text-teal-800 px-2 py-0.5 rounded">Clinic Leadership</span>
                    <h3 class="font-bold text-slate-900 mt-2 text-base">Org Owner</h3>
                    <p class="text-xs text-slate-500 mt-1">Manage practice branches, practitioners, clinic modules, billing & analytics.</p>
                    <div class="mt-3 text-xs font-mono text-slate-600 bg-slate-50 p-1.5 rounded truncate">
                        owner@demo.mhcb.test
                    </div>
                </div>
                <form method="POST" action="{{ route('login.submit') }}" class="mt-4">
                    @csrf
                    <input type="hidden" name="email" value="owner@demo.mhcb.test">
                    <input type="hidden" name="password" value="Demo@12345">
                    <button type="submit" class="w-full py-2 px-3 rounded-lg bg-teal-700 hover:bg-teal-800 text-white text-xs font-semibold shadow-sm transition-colors text-center">
                        Login as Owner &rarr;
                    </button>
                </form>
            </div>

            <!-- 3. Organization Admin -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider bg-sky-100 text-sky-800 px-2 py-0.5 rounded">Operations</span>
                    <h3 class="font-bold text-slate-900 mt-2 text-base">Org Admin</h3>
                    <p class="text-xs text-slate-500 mt-1">Manage daily clinic operations, user profiles, services catalog & appointments.</p>
                    <div class="mt-3 text-xs font-mono text-slate-600 bg-slate-50 p-1.5 rounded truncate">
                        admin@demo.mhcb.test
                    </div>
                </div>
                <form method="POST" action="{{ route('login.submit') }}" class="mt-4">
                    @csrf
                    <input type="hidden" name="email" value="admin@demo.mhcb.test">
                    <input type="hidden" name="password" value="Demo@12345">
                    <button type="submit" class="w-full py-2 px-3 rounded-lg bg-sky-700 hover:bg-sky-800 text-white text-xs font-semibold shadow-sm transition-colors text-center">
                        Login as Admin &rarr;
                    </button>
                </form>
            </div>

            <!-- 4. Clinic Manager -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded">Branch Oversight</span>
                    <h3 class="font-bold text-slate-900 mt-2 text-base">Clinic Manager</h3>
                    <p class="text-xs text-slate-500 mt-1">Oversee branches, doctor schedules, staff attendance & appointment flows.</p>
                    <div class="mt-3 text-xs font-mono text-slate-600 bg-slate-50 p-1.5 rounded truncate">
                        manager@demo.mhcb.test
                    </div>
                </div>
                <form method="POST" action="{{ route('login.submit') }}" class="mt-4">
                    @csrf
                    <input type="hidden" name="email" value="manager@demo.mhcb.test">
                    <input type="hidden" name="password" value="Demo@12345">
                    <button type="submit" class="w-full py-2 px-3 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-sm transition-colors text-center">
                        Login as Manager &rarr;
                    </button>
                </form>
            </div>

            <!-- 5. Practitioner (Therapist) -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider bg-indigo-100 text-indigo-800 px-2 py-0.5 rounded">Clinical Care</span>
                    <h3 class="font-bold text-slate-900 mt-2 text-base">Therapist / Clinician</h3>
                    <p class="text-xs text-slate-500 mt-1">Sessions, SOAP/DAP notes drafting & digital signatures, treatment plans.</p>
                    <div class="mt-3 text-xs font-mono text-slate-600 bg-slate-50 p-1.5 rounded truncate">
                        practitioner@demo.mhcb.test
                    </div>
                </div>
                <form method="POST" action="{{ route('login.submit') }}" class="mt-4">
                    @csrf
                    <input type="hidden" name="email" value="practitioner@demo.mhcb.test">
                    <input type="hidden" name="password" value="Demo@12345">
                    <button type="submit" class="w-full py-2 px-3 rounded-lg bg-indigo-700 hover:bg-indigo-800 text-white text-xs font-semibold shadow-sm transition-colors text-center">
                        Login as Clinician &rarr;
                    </button>
                </form>
            </div>

            <!-- 6. Receptionist -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-800 px-2 py-0.5 rounded">Front Desk</span>
                    <h3 class="font-bold text-slate-900 mt-2 text-base">Receptionist</h3>
                    <p class="text-xs text-slate-500 mt-1">Manage check-ins, bookings, waitlists. Confidential notes strictly hidden.</p>
                    <div class="mt-3 text-xs font-mono text-slate-600 bg-slate-50 p-1.5 rounded truncate">
                        reception@demo.mhcb.test
                    </div>
                </div>
                <form method="POST" action="{{ route('login.submit') }}" class="mt-4">
                    @csrf
                    <input type="hidden" name="email" value="reception@demo.mhcb.test">
                    <input type="hidden" name="password" value="Demo@12345">
                    <button type="submit" class="w-full py-2 px-3 rounded-lg bg-amber-700 hover:bg-amber-800 text-white text-xs font-semibold shadow-sm transition-colors text-center">
                        Login as Receptionist &rarr;
                    </button>
                </form>
            </div>

            <!-- 7. Billing Manager -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider bg-green-100 text-green-800 px-2 py-0.5 rounded">Finance</span>
                    <h3 class="font-bold text-slate-900 mt-2 text-base">Billing Manager</h3>
                    <p class="text-xs text-slate-500 mt-1">Invoices, payments, Unicode PDF receipts, tax calculations and refunds.</p>
                    <div class="mt-3 text-xs font-mono text-slate-600 bg-slate-50 p-1.5 rounded truncate">
                        billing@demo.mhcb.test
                    </div>
                </div>
                <form method="POST" action="{{ route('login.submit') }}" class="mt-4">
                    @csrf
                    <input type="hidden" name="email" value="billing@demo.mhcb.test">
                    <input type="hidden" name="password" value="Demo@12345">
                    <button type="submit" class="w-full py-2 px-3 rounded-lg bg-green-700 hover:bg-green-800 text-white text-xs font-semibold shadow-sm transition-colors text-center">
                        Login as Billing &rarr;
                    </button>
                </form>
            </div>

            <!-- 8. Client / Patient -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider bg-rose-100 text-rose-800 px-2 py-0.5 rounded">Client Portal</span>
                    <h3 class="font-bold text-slate-900 mt-2 text-base">Client / Patient</h3>
                    <p class="text-xs text-slate-500 mt-1">Personal dashboard, upcoming appointments, telehealth video, invoices, messaging.</p>
                    <div class="mt-3 text-xs font-mono text-slate-600 bg-slate-50 p-1.5 rounded truncate">
                        client@demo.mhcb.test
                    </div>
                </div>
                <form method="POST" action="{{ route('login.submit') }}" class="mt-4">
                    @csrf
                    <input type="hidden" name="email" value="client@demo.mhcb.test">
                    <input type="hidden" name="password" value="Demo@12345">
                    <button type="submit" class="w-full py-2 px-3 rounded-lg bg-rose-700 hover:bg-rose-800 text-white text-xs font-semibold shadow-sm transition-colors text-center">
                        Login as Client &rarr;
                    </button>
                </form>
            </div>

            <!-- 9. Parent / Guardian -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider bg-orange-100 text-orange-800 px-2 py-0.5 rounded">Dependent Care</span>
                    <h3 class="font-bold text-slate-900 mt-2 text-base">Parent / Guardian</h3>
                    <p class="text-xs text-slate-500 mt-1">Authorized minor care bookings and consent. Notes remain confidential.</p>
                    <div class="mt-3 text-xs font-mono text-slate-600 bg-slate-50 p-1.5 rounded truncate">
                        guardian@demo.mhcb.test
                    </div>
                </div>
                <form method="POST" action="{{ route('login.submit') }}" class="mt-4">
                    @csrf
                    <input type="hidden" name="email" value="guardian@demo.mhcb.test">
                    <input type="hidden" name="password" value="Demo@12345">
                    <button type="submit" class="w-full py-2 px-3 rounded-lg bg-orange-700 hover:bg-orange-800 text-white text-xs font-semibold shadow-sm transition-colors text-center">
                        Login as Guardian &rarr;
                    </button>
                </form>
            </div>

            <!-- 10. Compliance Auditor -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider bg-slate-200 text-slate-800 px-2 py-0.5 rounded">Audit & Security</span>
                    <h3 class="font-bold text-slate-900 mt-2 text-base">Compliance Auditor</h3>
                    <p class="text-xs text-slate-500 mt-1">Inspect access logs, consent histories and HIPAA compliance trails without clinical tampering.</p>
                    <div class="mt-3 text-xs font-mono text-slate-600 bg-slate-50 p-1.5 rounded truncate">
                        auditor@demo.mhcb.test
                    </div>
                </div>
                <form method="POST" action="{{ route('login.submit') }}" class="mt-4">
                    @csrf
                    <input type="hidden" name="email" value="auditor@demo.mhcb.test">
                    <input type="hidden" name="password" value="Demo@12345">
                    <button type="submit" class="w-full py-2 px-3 rounded-lg bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold shadow-sm transition-colors text-center">
                        Login as Auditor &rarr;
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- 26 Modular Architecture Showcase -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-teal-700">Centralized Module Registry</span>
                <h2 class="text-3xl font-extrabold text-slate-900 mt-1">26 Independently Configurable Modules</h2>
                <p class="text-slate-600 text-sm mt-1 max-w-xl">
                    Every organization enables only what it needs. A solo therapist activates notes and booking, while large multi-branch hospital centers leverage HR, biometric attendance and advanced analytics.
                </p>
            </div>
            <a href="{{ route('modules') }}" class="mt-4 md:mt-0 text-sm font-semibold text-teal-700 hover:text-teal-800 flex items-center gap-1">
                <span>View all 26 modules</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($modules as $module)
                <div class="p-6 rounded-2xl border border-slate-200 bg-slate-50/50 hover:bg-white hover:border-teal-300 hover:shadow-lg transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-800 flex items-center justify-center font-bold text-lg mb-4 group-hover:scale-110 transition-transform">
                        #{{ $module['sort_order'] }}
                    </div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-teal-700 bg-teal-50 px-2 py-0.5 rounded border border-teal-200">
                            {{ ucfirst($module['category']) }}
                        </span>
                        @if($module['is_core'])
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-600 bg-slate-200 px-1.5 py-0.5 rounded">Core</span>
                        @endif
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg mb-2">{{ $module['name'] }}</h3>
                    <p class="text-slate-600 text-xs leading-relaxed line-clamp-3 mb-4">
                        {{ $module['description'] }}
                    </p>
                    <div class="pt-3 border-t border-slate-200/60 flex items-center justify-between text-xs text-slate-500">
                        <span>Min Tier: <strong class="capitalize text-slate-700">{{ $module['min_plan_tier'] }}</strong></span>
                        <span class="text-emerald-700 font-semibold">Active &bull; v{{ $module['version'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Clinical Workflows & Security -->
<section class="py-20 bg-slate-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-teal-400">Clinical Integrity & Compliance</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-2 mb-6 leading-tight">
                    Engineered for Rigorous Mental Health Documentation Standards
                </h2>
                <p class="text-slate-300 text-base leading-relaxed mb-8">
                    Mental healthcare demands higher confidentiality and precision than ordinary CRM apps. Serenity Health provides dedicated clinical architectures:
                </p>

                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-lg bg-teal-500/20 text-teal-400 flex items-center justify-center shrink-0 mt-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-base">SOAP, DAP, BIRP & GIRP Clinical Formats</h4>
                            <p class="text-slate-400 text-xs mt-1">Rich-text structured templates with digital clinician signatures, cryptographic hashes, and locked records.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-lg bg-teal-500/20 text-teal-400 flex items-center justify-center shrink-0 mt-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-base">Auditable Versioned Amendments</h4>
                            <p class="text-slate-400 text-xs mt-1">Signed records are never silently overwritten. Any correction generates an immutable revision with recorded timestamp and mandatory rationale.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-lg bg-teal-500/20 text-teal-400 flex items-center justify-center shrink-0 mt-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-base">Role-Restricted Separation of Records</h4>
                            <p class="text-slate-400 text-xs mt-1">Administrative, reception, and billing staff only view CRM demographics. Protected Health Information (PHI) is isolated strictly to authorized clinicians.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Code / Architecture Sample Card -->
            <div class="bg-slate-800 p-6 sm:p-8 rounded-3xl border border-slate-700 shadow-2xl">
                <div class="flex items-center justify-between border-b border-slate-700 pb-4 mb-4">
                    <span class="text-xs font-mono text-teal-400 font-semibold">ClinicalNote::sign()</span>
                    <span class="text-xs text-emerald-400 bg-emerald-950 px-2 py-0.5 rounded border border-emerald-800">Verified Cryptographic Timestamp</span>
                </div>
                <pre class="text-xs font-mono text-slate-300 overflow-x-auto leading-relaxed">
<span class="text-purple-400">$clinicalNote</span> = ClinicalNote::create([
    <span class="text-teal-300">'format'</span>    =&gt; <span class="text-amber-300">'SOAP'</span>,
    <span class="text-teal-300">'client_id'</span> =&gt; <span class="text-purple-400">$session</span>-&gt;client_id,
    <span class="text-teal-300">'content'</span>   =&gt; [
        <span class="text-teal-300">'subjective'</span> =&gt; <span class="text-slate-400">'Client presents with moderate anxiety...'</span>,
        <span class="text-teal-300">'objective'</span>  =&gt; <span class="text-slate-400">'Affect congruent with mood; alert, oriented.'</span>,
        <span class="text-teal-300">'assessment'</span> =&gt; <span class="text-slate-400">'Positive response to CBT thought records.'</span>,
        <span class="text-teal-300">'plan'</span>       =&gt; <span class="text-slate-400">'Continue weekly 50-minute sessions.'</span>,
    ],
]);

<span class="text-purple-400">$clinicalNote</span>-&gt;sign(<span class="text-purple-400">$clinician</span>);
<span class="text-emerald-400">// Automatically locked, access logged, revision #1 committed</span>
</pre>
            </div>
        </div>
    </div>
</section>

<!-- Featured Counseling Services -->
<section class="py-20 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="text-xs font-bold uppercase tracking-wider text-teal-700">Specialized Mental Health Services</span>
            <h2 class="text-3xl font-extrabold text-slate-900 mt-1">Compassionate Care for Every Need</h2>
            <p class="text-slate-600 text-sm mt-2">Available for in-person consultation at our Serenity Centers or online via secure telehealth video.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($services as $service)
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold text-teal-800 bg-teal-50 border border-teal-200 px-2.5 py-0.5 rounded-full capitalize">
                                {{ str_replace('_', ' ', $service->type) }}
                            </span>
                            <span class="text-xs text-slate-500 font-medium">
                                {{ $service->duration_minutes }} min
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">{{ $service->name }}</h3>
                        <p class="text-slate-600 text-xs leading-relaxed mb-4">
                            {{ $service->description ?? 'Confidential therapy and clinical counseling customized to your personal therapeutic goals.' }}
                        </p>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-400 block">Session Fee</span>
                            <span class="text-xl font-extrabold text-slate-900">${{ number_format($service->price, 2) }}</span>
                        </div>
                        <a href="{{ route('booking.index', ['service_id' => $service->id]) }}" class="px-4 py-2 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-semibold shadow-sm transition-colors">
                            Book This &rarr;
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Therapists / Practitioners -->
<section class="py-20 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="text-xs font-bold uppercase tracking-wider text-teal-700">Licensed Professional Staff</span>
            <h2 class="text-3xl font-extrabold text-slate-900 mt-1">Meet Our Clinical Practitioners</h2>
            <p class="text-slate-600 text-sm mt-2">All clinicians hold verified credentials, specialized certifications, and extensive therapy experience.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($practitioners as $practitioner)
                <div class="bg-slate-50 rounded-2xl border border-slate-200 p-6 flex flex-col justify-between hover:border-teal-300 transition-colors">
                    <div>
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-14 h-14 rounded-2xl bg-teal-700 text-white font-bold flex items-center justify-center text-xl shadow-md">
                                {{ substr($practitioner->user->name ?? 'Dr', 0, 1) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-lg leading-tight">{{ $practitioner->name }}</h3>
                                <span class="text-xs font-medium text-teal-700">{{ $practitioner->title }} &bull; {{ $practitioner->qualifications ?? 'Clinical Specialist' }}</span>
                                <div class="flex items-center gap-1 text-xs text-amber-500 mt-1">
                                    <span>★ {{ number_format($practitioner->rating, 1) }}</span>
                                    <span class="text-slate-400">&bull; License: {{ $practitioner->license_number ?? 'Verified' }}</span>
                                </div>
                            </div>
                        </div>

                        <p class="text-xs text-slate-600 leading-relaxed mb-4">
                            {{ $practitioner->bio ?? 'Dedicated licensed practitioner specializing in evidence-based therapy, cognitive behavioral tools, and patient-centered counseling.' }}
                        </p>

                        <div class="flex flex-wrap gap-1.5 mb-4">
                            @if(is_array($practitioner->specialties))
                                @foreach($practitioner->specialties as $specialty)
                                    <span class="text-[10px] font-semibold bg-white border border-slate-200 text-slate-700 px-2 py-0.5 rounded-md">
                                        {{ $specialty }}
                                    </span>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-200 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-slate-400 uppercase">Consultation</span>
                            <div class="font-bold text-slate-900 text-base">${{ number_format($practitioner->base_fee, 2) }}</div>
                        </div>
                        <a href="{{ route('booking.index', ['practitioner_id' => $practitioner->id]) }}" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold shadow-sm transition-colors">
                            Book With Clinician
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Pricing Plans Overview -->
<section class="py-20 bg-slate-100 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="text-xs font-bold uppercase tracking-wider text-teal-700">Commercial SaaS Subscriptions</span>
            <h2 class="text-3xl font-extrabold text-slate-900 mt-1">Transparent Practice Plans</h2>
            <p class="text-slate-600 text-sm mt-2">Start free or scale up as your counseling center expands into multiple branches and clinicians.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($plans as $plan)
                <div class="bg-white rounded-2xl border {{ $plan->slug === 'professional' ? 'border-teal-500 ring-2 ring-teal-500 shadow-xl' : 'border-slate-200 shadow-sm' }} p-6 flex flex-col justify-between">
                    <div>
                        @if($plan->slug === 'professional')
                            <span class="text-[10px] font-bold uppercase tracking-wider bg-teal-100 text-teal-800 px-2 py-0.5 rounded block text-center mb-3">Most Popular</span>
                        @endif
                        <h3 class="font-bold text-slate-900 text-xl">{{ $plan->name }}</h3>
                        <p class="text-xs text-slate-500 mt-1 mb-4">{{ $plan->description }}</p>

                        <div class="mb-6">
                            <span class="text-3xl font-black text-slate-900">${{ number_format($plan->price_monthly, 0) }}</span>
                            <span class="text-xs text-slate-500">/month</span>
                        </div>

                        <ul class="space-y-2 text-xs text-slate-600 mb-6">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-teal-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span>Up to <strong>{{ $plan->max_practitioners }}</strong> Clinicians</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-teal-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span>Up to <strong>{{ $plan->max_clients }}</strong> Active Clients</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-teal-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span><strong>{{ $plan->max_branches }}</strong> Practice Branches</span>
                            </li>
                        </ul>
                    </div>

                    <a href="{{ route('register') }}" class="w-full py-2.5 px-4 rounded-xl text-center text-xs font-semibold transition-all {{ $plan->slug === 'professional' ? 'bg-teal-700 hover:bg-teal-800 text-white shadow-md' : 'bg-slate-100 hover:bg-slate-200 text-slate-800' }}">
                        Select {{ $plan->name }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Synthetic Demo Testimonials -->
<section class="py-20 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="text-xs font-bold uppercase tracking-wider text-teal-700">Practice Feedback</span>
            <h2 class="text-3xl font-extrabold text-slate-900 mt-1">Trusted by Mental Health Centers</h2>
            <p class="text-slate-600 text-xs mt-2">Clearly identified synthetic demo testimonials adhering to strict client privacy protections.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($reviews as $review)
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="text-amber-500 text-sm mb-3">
                            {{ str_repeat('★', $review->rating) }}
                        </div>
                        <p class="text-slate-700 text-sm italic mb-4 leading-relaxed">
                            "{{ $review->comment }}"
                        </p>
                    </div>
                    <div class="pt-4 border-t border-slate-200 text-xs text-slate-500 flex items-center justify-between">
                        <span>{{ $review->is_anonymous ? 'Verified Patient (Anonymous)' : 'Client Member' }}</span>
                        <span class="text-teal-700 font-semibold">{{ $review->practitioner->name ?? 'Clinic Clinician' }}</span>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center text-slate-400 py-8">
                    Sample synthetic reviews will appear here upon database seeding.
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Multilingual Clinical Blog Preview -->
<section class="py-20 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-teal-700">Practice Knowledge & Articles</span>
                <h2 class="text-3xl font-extrabold text-slate-900 mt-1">Clinical Insights (বাংলা & English)</h2>
                <p class="text-slate-600 text-sm mt-1">Evidence-based psychological wellness guides powered by universal Unicode URL slugs.</p>
            </div>
            <a href="{{ route('blog.index') }}" class="mt-4 md:mt-0 text-sm font-semibold text-teal-700 hover:text-teal-800 flex items-center gap-1">
                <span>Browse all articles</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($recentPosts as $post)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-teal-800 bg-teal-50 border border-teal-200 px-2 py-0.5 rounded">
                                {{ $post->category }}
                            </span>
                            <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded uppercase">
                                {{ $post->locale === 'bn' ? 'বাংলা' : 'EN' }}
                            </span>
                        </div>
                        <h3 class="font-bold text-slate-900 text-lg mb-2 hover:text-teal-700 transition-colors">
                            <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                        </h3>
                        <p class="text-slate-600 text-xs leading-relaxed line-clamp-3">
                            {{ $post->excerpt }}
                        </p>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <span>{{ $post->published_at ? $post->published_at->format('M j, Y') : 'Published' }}</span>
                        <a href="{{ route('blog.show', $post->slug) }}" class="font-bold text-teal-700 hover:underline">Read Guide &rarr;</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- FAQ Accordion -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="text-xs font-bold uppercase tracking-wider text-teal-700">Frequently Asked Questions</span>
            <h2 class="text-3xl font-extrabold text-slate-900 mt-1">Everything You Need to Know</h2>
        </div>

        <div class="space-y-4">
            @foreach($faqs as $faq)
                <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/50">
                    <h3 class="font-bold text-slate-900 text-base mb-2 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-teal-600"></span>
                        {{ $faq->question }}
                    </h3>
                    <p class="text-slate-600 text-sm leading-relaxed pl-4">
                        {{ $faq->answer }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
