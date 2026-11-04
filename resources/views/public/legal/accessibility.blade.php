@extends('layouts.app', ['title' => 'Accessibility Statement - Mental Health SaaS'])

@section('content')
<div class="py-16 bg-slate-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl border border-slate-200 p-8 sm:p-12 shadow-sm">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-teal-100 text-teal-800 tracking-wide uppercase">Inclusivity & Access</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-3 mb-6">Accessibility Statement</h1>
            <p class="text-xs text-slate-500 mb-8 pb-6 border-b border-slate-100">WCAG 2.1 Level AA Conformance Target</p>

            <div class="prose prose-slate max-w-none text-slate-700 text-sm leading-relaxed space-y-6">
                <section>
                    <h2 class="text-lg font-bold text-slate-900">1. Our Commitment to Accessible Mental Healthcare</h2>
                    <p>
                        We believe mental health care must be accessible to everyone, including individuals with visual, auditory, cognitive, or motor impairments. We continually optimize our client booking portal, patient intake forms, and telehealth interfaces in accordance with the World Wide Web Consortium (W3C) Web Content Accessibility Guidelines (WCAG) 2.1 Level AA.
                    </p>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-slate-900">2. Key Accessibility Features</h2>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><strong>High Contrast & Legible Typography:</strong> Thoughtful color ratios ensuring readability across light and dark display modes.</li>
                        <li><strong>Screen Reader Semantic Markup:</strong> Explicit ARIA tags, labels, and roles on booking calendar controls, slot pickers, and intake questions.</li>
                        <li><strong>Keyboard Navigation:</strong> Full focus trap management and intuitive tab indexing for clients navigating without mouse or pointer devices.</li>
                        <li><strong>Responsive Scaling:</strong> Native zooming support up to 200% without loss of functionality or horizontal scrolling.</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-slate-900">3. Feedback & Accessibility Assistance</h2>
                    <p>
                        If you experience difficulty accessing any part of our platform or booking interface, please reach out to our team at <a href="mailto:support@imrandev.bd" class="text-teal-600 font-semibold underline">support@imrandev.bd</a>. We are dedicated to providing accommodations and resolving accessibility barriers promptly.
                    </p>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
