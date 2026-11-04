@extends('layouts.app')

@section('title', 'Platform Features - Serenity Health')
@section('meta_description', 'Explore clinical documentation, multi-branch management, telehealth, booking engines, and multi-tenant security features.')

@section('content')
<div class="py-16 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-wider text-teal-700 bg-teal-50 px-3 py-1 rounded-full border border-teal-200">
                Enterprise Capabilities
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 mt-4 tracking-tight">
                Designed for High-Performing Mental Health Clinics
            </h1>
            <p class="text-slate-600 text-base mt-4 leading-relaxed">
                Connect your clinicians, clients, front-desk staff, and billing team into one synchronized healthcare operating system.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- 1. Clinical Notes -->
            <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-2xl bg-teal-700 text-white flex items-center justify-center mb-6 shadow-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Structured Clinical Documentation</h3>
                <p class="text-sm text-slate-600 leading-relaxed mb-4">
                    Document client sessions using industry standard SOAP, DAP, BIRP, and GIRP formats. Clinicians digitally sign notes, triggering immutable locks and cryptographic timestamps.
                </p>
                <div class="text-xs font-semibold text-teal-800">Module: Clinical Documentation &bull; PHI Protected</div>
            </div>

            <!-- 2. Booking Engine -->
            <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-2xl bg-sky-700 text-white flex items-center justify-center mb-6 shadow-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Collision-Free Scheduling Engine</h3>
                <p class="text-sm text-slate-600 leading-relaxed mb-4">
                    Strict database locking and transactional verification completely prevents double booking. Configurable buffer intervals, practitioner working schedules, and waiting lists.
                </p>
                <div class="text-xs font-semibold text-sky-800">Module: Appointment Booking &bull; Live Slot Validation</div>
            </div>

            <!-- 3. Telehealth -->
            <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-2xl bg-indigo-700 text-white flex items-center justify-center mb-6 shadow-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Zero-Install Telehealth Video</h3>
                <p class="text-sm text-slate-600 leading-relaxed mb-4">
                    Host secure virtual consultations directly in browser. Automatic unique video meeting rooms, waiting lobby, meeting password protection, and zero recording by default for confidentiality.
                </p>
                <div class="text-xs font-semibold text-indigo-800">Module: Telehealth Integration &bull; WebRTC / Jitsi</div>
            </div>

            <!-- 4. Client CRM & Guardians -->
            <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-2xl bg-teal-800 text-white flex items-center justify-center mb-6 shadow-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Client Portal & Guardian Care</h3>
                <p class="text-sm text-slate-600 leading-relaxed mb-4">
                    Clients access personal upcoming appointments, pay invoices online, sign consent forms, and message clinicians. Parents/guardians manage authorized dependents with strict confidentiality bounds.
                </p>
                <div class="text-xs font-semibold text-teal-800">Module: Client Portal &bull; Role Scoped</div>
            </div>

            <!-- 5. Unicode PDF & Invoicing -->
            <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-2xl bg-emerald-700 text-white flex items-center justify-center mb-6 shadow-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Billing & Unicode PDF Generation</h3>
                <p class="text-sm text-slate-600 leading-relaxed mb-4">
                    Multi-currency session charges, automated line item generation, payment receipts, and high-performance multilingual PDF exports powered by <code class="font-mono text-emerald-900 font-bold">imrandevbd/laravel-unicode-pdf</code>.
                </p>
                <div class="text-xs font-semibold text-emerald-800">Module: Finance &bull; English & Bengali Support</div>
            </div>

            <!-- 6. Attendance & HR -->
            <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-2xl bg-amber-700 text-white flex items-center justify-center mb-6 shadow-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Biometric Staff Attendance</h3>
                <p class="text-sm text-slate-600 leading-relaxed mb-4">
                    Integrated with <code class="font-mono text-amber-900 font-bold">imrandevbd/laravel-attendance-hub</code> to communicate with physical biometric fingerprint and ADMS time clocks for clinic staff and nurse shifts.
                </p>
                <div class="text-xs font-semibold text-amber-800">Module: Attendance Hub &bull; Hardware Bridge</div>
            </div>
        </div>
    </div>
</div>
@endsection
