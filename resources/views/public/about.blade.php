@extends('layouts.app', ['title' => 'About Us - Mental Health and Counseling SaaS'])

@section('content')
<div class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Hero Section -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-teal-100 text-teal-800 tracking-wide uppercase">Our Clinical Mission</span>
            <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight mt-4 sm:text-5xl">Empowering Practitioners, Healing Minds</h1>
            <p class="mt-4 text-lg text-slate-600 leading-relaxed">
                We bridge clinical excellence with enterprise technology to remove administrative friction from psychological care. Therapists, counselors, and psychiatric practices rely on our ecosystem for seamless booking, HIPAA-aligned clinical documentation, and client empowerment.
            </p>
        </div>

        <!-- Clinical Leadership & Story -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-20">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight mb-4">A SaaS Platform Engineered by Clinicians & Engineers</h2>
                <div class="space-y-4 text-slate-600 leading-relaxed">
                    <p>
                        Traditional Electronic Health Records (EHRs) were designed for hospital billing codes rather than modern psychological care. Mental health providers spent over 30% of their working hours wrestling with clumsy software, risking burnout and diminishing client facetime.
                    </p>
                    <p>
                        In 2024, our founding team set out to engineer a multi-tenant platform dedicated entirely to therapy sessions, client outcomes, and behavioral health workflows. From one-click SOAP note signing to collision-free scheduling with customizable buffer times, every pixel is calibrated for psychological practice needs.
                    </p>
                    <p>
                        Today, Serenity Mental Health & Counseling Booking powers solo practices, multidisciplinary behavioral clinics, university counseling centers, and telehealth networks across the globe.
                    </p>
                </div>
                <div class="mt-8 grid grid-cols-3 gap-6 pt-6 border-t border-slate-200">
                    <div>
                        <div class="text-3xl font-extrabold text-teal-600">100k+</div>
                        <div class="text-xs font-medium text-slate-500 uppercase mt-1">Sessions Booked</div>
                    </div>
                    <div>
                        <div class="text-3xl font-extrabold text-teal-600">99.98%</div>
                        <div class="text-xs font-medium text-slate-500 uppercase mt-1">Uptime SLA</div>
                    </div>
                    <div>
                        <div class="text-3xl font-extrabold text-teal-600">26</div>
                        <div class="text-xs font-medium text-slate-500 uppercase mt-1">Modular Tools</div>
                    </div>
                </div>
            </div>
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
                <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-teal-500"></span>
                    Our Core Ethical Pillars
                </h3>
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-sm shrink-0">1</div>
                        <div>
                            <h4 class="font-semibold text-slate-900 text-sm">Strict Clinical Confidentiality</h4>
                            <p class="text-xs text-slate-500 mt-1">Clinical notes are walled off from receptionists, administrative staff, and billing departments with cryptographically auditable access logs.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-sm shrink-0">2</div>
                        <div>
                            <h4 class="font-semibold text-slate-900 text-sm">Human-First AI Assistance</h4>
                            <p class="text-xs text-slate-500 mt-1">Clinician-in-the-loop architecture ensures AI never renders medical diagnoses or auto-commits clinical notes without certified practitioner review.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-sm shrink-0">3</div>
                        <div>
                            <h4 class="font-semibold text-slate-900 text-sm">Zero Client Barrier</h4>
                            <p class="text-xs text-slate-500 mt-1">Frictionless client portal with instant self-scheduling, multilingual invoices, and encrypted telehealth rooms directly in modern web browsers.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-sm shrink-0">4</div>
                        <div>
                            <h4 class="font-semibold text-slate-900 text-sm">Tenant Sovereignty & Privacy</h4>
                            <p class="text-xs text-slate-500 mt-1">Multi-tenant row-level data isolation guarantees that practice records, client histories, and financials are isolated and fully exportable.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leadership / Clinical Advisory -->
        <div class="mb-16">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <h2 class="text-3xl font-extrabold text-slate-900">Clinical Leadership & Architecture</h2>
                <p class="mt-2 text-sm text-slate-500">Designed under the guidance of licensed psychologists, clinical social workers, and cybersecurity engineers.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-2xl border border-slate-200 text-center">
                    <div class="w-20 h-20 mx-auto rounded-full bg-teal-100 text-teal-800 flex items-center justify-center text-xl font-bold mb-4">
                        IA
                    </div>
                    <h3 class="font-bold text-slate-900">Imran Ahmed</h3>
                    <p class="text-xs text-teal-600 font-medium">Principal Architect & Systems Engineer</p>
                    <p class="text-xs text-slate-500 mt-3 leading-relaxed">
                        Lead architect specializing in high-performance Laravel enterprise SaaS, healthcare data privacy, and multi-tenant systems.
                    </p>
                    <div class="mt-4">
                        <a href="https://imrandev.bd" target="_blank" class="text-xs text-teal-700 hover:text-teal-900 font-semibold underline">imrandev.bd</a>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-slate-200 text-center">
                    <div class="w-20 h-20 mx-auto rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-xl font-bold mb-4">
                        SM
                    </div>
                    <h3 class="font-bold text-slate-900">Dr. Sarah Mitchell, PsyD</h3>
                    <p class="text-xs text-emerald-600 font-medium">Director of Clinical Protocols</p>
                    <p class="text-xs text-slate-500 mt-3 leading-relaxed">
                        Board-certified clinical psychologist with 15+ years overseeing behavioral health documentation, SOAP/DAP standards, and clinician ergonomics.
                    </p>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-slate-200 text-center">
                    <div class="w-20 h-20 mx-auto rounded-full bg-indigo-100 text-indigo-800 flex items-center justify-center text-xl font-bold mb-4">
                        MC
                    </div>
                    <h3 class="font-bold text-slate-900">Marcus Chen, CISSP</h3>
                    <p class="text-xs text-indigo-600 font-medium">Healthcare Cybersecurity Advisor</p>
                    <p class="text-xs text-slate-500 mt-3 leading-relaxed">
                        Cybersecurity veteran leading audit trails, PHI encryption, role segregation, and zero-trust authentication protocols.
                    </p>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="bg-gradient-to-r from-teal-700 to-cyan-800 rounded-3xl p-10 text-white text-center shadow-lg">
            <h2 class="text-2xl sm:text-3xl font-extrabold">Ready to elevate your mental health practice?</h2>
            <p class="mt-3 text-teal-100 max-w-xl mx-auto text-sm">
                Join forward-thinking therapists and clinics. Start with our 14-day free trial or test drive our interactive live demo today.
            </p>
            <div class="mt-6 flex flex-wrap justify-center gap-4">
                <a href="{{ route('booking.index') }}" class="px-6 py-3 rounded-xl bg-white text-teal-800 font-semibold text-sm hover:bg-teal-50 transition shadow">Book a Consultation</a>
                <a href="{{ route('pricing') }}" class="px-6 py-3 rounded-xl bg-teal-600 text-white font-semibold text-sm hover:bg-teal-500 transition border border-teal-400">View SaaS Plans</a>
            </div>
        </div>
    </div>
</div>
@endsection
