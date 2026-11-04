@extends('layouts.app', ['title' => 'Request a Demo & Live Interactive Test Drive'])

@section('content')
<div class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-teal-100 text-teal-800 tracking-wide uppercase">Interactive Sandbox & Walkthrough</span>
            <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight mt-4 sm:text-5xl">Experience the Complete Ecosystem</h1>
            <p class="mt-4 text-lg text-slate-600">
                Test-drive our 26 business modules with live synthetic clinic data or request a 1-on-1 personalized architecture walkthrough with our clinical systems engineers.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-16">
            <!-- Left: Instant One-Click Role Simulator -->
            <div class="lg:col-span-7 bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">Instant Live Sandbox (No Signup Needed)</h2>
                        <p class="text-xs text-slate-500 mt-1">Click any role below to prefill credentials and enter the live environment</p>
                    </div>
                    <span class="px-2.5 py-1 text-xs font-semibold bg-emerald-100 text-emerald-800 rounded-full">All Passwords: Demo@12345</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    @php
                        $demoRoles = [
                            ['role' => 'Super Administrator', 'email' => 'superadmin@demo.mhcb.test', 'badge' => 'Platform', 'desc' => 'Multi-tenant, module toggle, billing & system metrics'],
                            ['role' => 'Organization Owner', 'email' => 'owner@demo.mhcb.test', 'badge' => 'Clinic Admin', 'desc' => 'Branch settings, staff, subscription, module management'],
                            ['role' => 'Clinical Director', 'email' => 'admin@demo.mhcb.test', 'badge' => 'Clinical', 'desc' => 'Protocol oversight, clinical documentation, supervision'],
                            ['role' => 'Therapist / Counselor', 'email' => 'practitioner@demo.mhcb.test', 'badge' => 'Practitioner', 'desc' => 'Appointments, SOAP/DAP notes, telehealth, treatment plans'],
                            ['role' => 'Clinic Manager', 'email' => 'manager@demo.mhcb.test', 'badge' => 'Operations', 'desc' => 'Shift scheduling, attendance hub, service configuration'],
                            ['role' => 'Receptionist', 'email' => 'reception@demo.mhcb.test', 'badge' => 'Front Desk', 'desc' => 'Collision-free booking, waitlist, zero clinical PHI access'],
                            ['role' => 'Billing Manager', 'email' => 'billing@demo.mhcb.test', 'badge' => 'Finance', 'desc' => 'Invoicing, Unicode PDF receipts, payment reconciliations'],
                            ['role' => 'Compliance Auditor', 'email' => 'auditor@demo.mhcb.test', 'badge' => 'Security', 'desc' => 'Audit logs, PHI access history, HIPAA consent records'],
                            ['role' => 'Client / Patient', 'email' => 'client@demo.mhcb.test', 'badge' => 'Client Portal', 'desc' => 'Self-booking, telehealth video room, invoices, documents'],
                            ['role' => 'Parent / Guardian', 'email' => 'guardian@demo.mhcb.test', 'badge' => 'Dependent', 'desc' => 'Dependent care authorization, family billing, intake']
                        ];
                    @endphp

                    @foreach($demoRoles as $item)
                        <div class="p-3.5 rounded-xl border border-slate-200 hover:border-teal-400 hover:bg-teal-50/40 transition group flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-bold text-slate-800">{{ $item['role'] }}</span>
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-slate-100 text-slate-600 group-hover:bg-teal-100 group-hover:text-teal-800">{{ $item['badge'] }}</span>
                                </div>
                                <p class="text-[11px] text-slate-500 line-clamp-2 leading-relaxed">{{ $item['desc'] }}</p>
                            </div>
                            <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-[11px] font-mono text-slate-400 group-hover:text-slate-600 truncate max-w-[170px]">{{ $item['email'] }}</span>
                                <a href="{{ route('login', ['email' => $item['email']]) }}" class="text-xs font-semibold text-teal-600 group-hover:text-teal-800 flex items-center gap-1">
                                    Enter &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right: Guided Enterprise Demo Form -->
            <div class="lg:col-span-5 bg-white p-8 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 mb-2">Book a 30-Min Architecture Demo</h2>
                    <p class="text-xs text-slate-500 mb-6">Discuss your clinic’s specific workflows, data migration, and compliance requirements with our solutions engineers.</p>

                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="subject" value="Custom Enterprise Demo Request">

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Your Name</label>
                            <input type="text" name="name" required placeholder="Dr. Jennifer Ross" class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-teal-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Work Email</label>
                            <input type="email" name="email" required placeholder="jennifer@mindworksclinic.com" class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-teal-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Organization / Clinic Size</label>
                            <select name="phone" class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-teal-500 outline-none">
                                <option value="Solo Practice (1 clinician)">Solo Practice (1 Clinician)</option>
                                <option value="Group Practice (2 - 10 clinicians)">Group Practice (2 - 10 Clinicians)</option>
                                <option value="Multi-Location Clinic (11 - 50 clinicians)">Multi-Location Clinic (11 - 50 Clinicians)</option>
                                <option value="Hospital / Healthcare Network (50+ clinicians)">Hospital / Healthcare Network (50+ Clinicians)</option>
                                <option value="University Counseling Center">University Counseling Center</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Key Focus Areas</label>
                            <textarea name="message" rows="3" required placeholder="e.g. Telehealth video rooms, SOAP note customization, biometric attendance, or client portal..." class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-teal-500 outline-none"></textarea>
                        </div>

                        <button type="submit" class="w-full py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold text-sm rounded-xl transition shadow">
                            Schedule Technical Walkthrough
                        </button>
                    </form>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 text-[11px] text-slate-500 flex items-center justify-between">
                    <span>Includes 14-day full platform trial</span>
                    <span class="font-medium text-slate-700">No credit card required</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
