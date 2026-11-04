@extends('layouts.app', ['title' => 'Sign In - Mental Health SaaS'])

@section('content')
<div class="py-12 sm:py-16 bg-slate-50 min-h-[85vh] flex items-center">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left: Sign In Form -->
            <div class="lg:col-span-5 bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
                <div class="mb-6">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-teal-100 text-teal-800 uppercase tracking-wide">Secure Access</span>
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-2">Sign in to your account</h1>
                    <p class="text-xs text-slate-500 mt-1">Access the clinical management panel or client portal.</p>
                </div>

                @if(session('status'))
                    <div class="mb-4 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs font-medium text-emerald-800">
                        {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-xs text-red-700">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Email Address</label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            required 
                            value="{{ old('email', request('email', 'owner@demo.mhcb.test')) }}" 
                            class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none"
                        >
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">Password</label>
                            <span class="text-[11px] text-teal-600">Default: <span class="font-mono font-bold">Demo@12345</span></span>
                        </div>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            required 
                            value="Demo@12345" 
                            class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none"
                        >
                    </div>

                    <div class="flex items-center justify-between text-xs pt-1">
                        <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                            <input type="checkbox" name="remember" value="1" checked class="rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                            <span>Keep me signed in</span>
                        </label>
                        <span class="text-slate-400">HIPAA Compliant Session</span>
                    </div>

                    <button type="submit" class="w-full py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold text-sm rounded-xl transition shadow">
                        Sign In &rarr;
                    </button>
                </form>

                <div class="mt-6 pt-6 border-t border-slate-100 text-center text-xs text-slate-500">
                    Don’t have an account yet? 
                    <a href="{{ route('register') }}" class="font-semibold text-teal-600 hover:text-teal-800">Create one</a>
                </div>
            </div>

            <!-- Right: 10 One-Click Demo Role Accounts -->
            <div class="lg:col-span-7 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">One-Click Synthetic Demo Personas</h2>
                        <p class="text-xs text-slate-500">Click any persona to auto-fill the login form instantly.</p>
                    </div>
                    <span class="px-2.5 py-1 text-[11px] font-semibold bg-emerald-100 text-emerald-800 rounded-full font-mono">
                        PW: Demo@12345
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5" x-data>
                    @php
                        $personas = [
                            ['name' => 'Super Administrator', 'email' => 'superadmin@demo.mhcb.test', 'role' => 'Global SaaS Admin', 'dest' => 'Filament Platform Hub'],
                            ['name' => 'Organization Owner', 'email' => 'owner@demo.mhcb.test', 'role' => 'Serenity Center Owner', 'dest' => 'Full Clinic Administration'],
                            ['name' => 'Clinical Director', 'email' => 'admin@demo.mhcb.test', 'role' => 'Protocol & Supervisor', 'dest' => 'Clinical Supervision'],
                            ['name' => 'Therapist / Counselor', 'email' => 'practitioner@demo.mhcb.test', 'role' => 'Licensed Therapist', 'dest' => 'Notes, Sessions & Telehealth'],
                            ['name' => 'Clinic Manager', 'email' => 'manager@demo.mhcb.test', 'role' => 'Practice Operations', 'dest' => 'Schedules & Attendance Hub'],
                            ['name' => 'Receptionist', 'email' => 'reception@demo.mhcb.test', 'role' => 'Front Desk (No PHI)', 'dest' => 'Bookings & Waitlist'],
                            ['name' => 'Billing Manager', 'email' => 'billing@demo.mhcb.test', 'role' => 'Finance & Invoices', 'dest' => 'Billing & PDF Generation'],
                            ['name' => 'Compliance Auditor', 'email' => 'auditor@demo.mhcb.test', 'role' => 'HIPAA / Security Auditor', 'dest' => 'Access & Audit Logs'],
                            ['name' => 'Client / Patient', 'email' => 'client@demo.mhcb.test', 'role' => 'Client (Serenity)', 'dest' => 'Client Portal Dashboard'],
                            ['name' => 'Parent / Guardian', 'email' => 'guardian@demo.mhcb.test', 'role' => 'Dependent Guardian', 'dest' => 'Family Portal & Invoices']
                        ];
                    @endphp

                    @foreach($personas as $p)
                        <button 
                            type="button" 
                            @click="document.getElementById('email').value = '{{ $p['email'] }}'; document.getElementById('password').value = 'Demo@12345';"
                            class="p-2.5 rounded-xl border border-slate-200 hover:border-teal-500 hover:bg-teal-50/50 text-left transition group"
                        >
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-800 group-hover:text-teal-700">{{ $p['name'] }}</span>
                                <span class="text-[10px] font-mono text-slate-400 group-hover:text-teal-600">Select</span>
                            </div>
                            <div class="text-[11px] font-mono text-slate-500 truncate mt-0.5">{{ $p['email'] }}</div>
                            <div class="text-[10px] text-teal-600 font-medium mt-1">{{ $p['dest'] }}</div>
                        </button>
                    @endforeach
                </div>

                <div class="mt-4 p-3 rounded-xl bg-slate-50 border border-slate-200 text-[11px] text-slate-600 flex items-center gap-2">
                    <svg class="w-4 h-4 text-teal-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <span>Click any box above to fill credentials instantly, then click <strong>Sign In</strong>.</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
