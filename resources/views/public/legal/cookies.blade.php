@extends('layouts.app', ['title' => 'Cookie Policy - Mental Health SaaS'])

@section('content')
<div class="py-16 bg-slate-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl border border-slate-200 p-8 sm:p-12 shadow-sm">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-teal-100 text-teal-800 tracking-wide uppercase">Legal & Compliance</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-3 mb-6">Cookie & Tracking Policy</h1>
            <p class="text-xs text-slate-500 mb-8 pb-6 border-b border-slate-100">Last updated: January 1, 2026</p>

            <div class="prose prose-slate max-w-none text-slate-700 text-sm leading-relaxed space-y-6">
                <section>
                    <h2 class="text-lg font-bold text-slate-900">1. How We Use Cookies</h2>
                    <p>
                        Our platform employs strictly essential cookies necessary to maintain authenticated sessions, safeguard CSRF tokens, and record tenant context. In strict compliance with healthcare privacy principles, we do not utilize third-party ad retargeting pixels on clinical portal routes.
                    </p>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-slate-900">2. Categories of Cookies</h2>
                    <div class="space-y-4">
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                            <h3 class="font-bold text-slate-900 text-sm">Essential Authentication Cookies</h3>
                            <p class="text-xs text-slate-600 mt-1">Required to authenticate practitioners, administrators, and clients securely and maintain session continuity across requests.</p>
                        </div>
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                            <h3 class="font-bold text-slate-900 text-sm">Security & CSRF Protection</h3>
                            <p class="text-xs text-slate-600 mt-1">Cryptographic session tokens designed to protect forms and telehealth rooms from cross-site request forgery attacks.</p>
                        </div>
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                            <h3 class="font-bold text-slate-900 text-sm">Preference & Localization</h3>
                            <p class="text-xs text-slate-600 mt-1">Remembers user interface choices, including language selections (e.g. English, Bengali) and timezone preferences.</p>
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-slate-900">3. Managing Cookie Preferences</h2>
                    <p>
                        You can configure your browser to block or alert you about cookies; however, blocking essential authentication cookies will prevent you from logging into the clinical admin panel or patient portal.
                    </p>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
