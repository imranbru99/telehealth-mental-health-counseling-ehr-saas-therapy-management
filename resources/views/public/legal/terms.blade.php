@extends('layouts.app', ['title' => 'Terms of Service - Mental Health SaaS'])

@section('content')
<div class="py-16 bg-slate-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl border border-slate-200 p-8 sm:p-12 shadow-sm">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-teal-100 text-teal-800 tracking-wide uppercase">Legal & Compliance</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-3 mb-6">Terms of Service</h1>
            <p class="text-xs text-slate-500 mb-8 pb-6 border-b border-slate-100">Last updated: January 1, 2026</p>

            <div class="prose prose-slate max-w-none text-slate-700 text-sm leading-relaxed space-y-6">
                <section>
                    <h2 class="text-lg font-bold text-slate-900">1. Acceptance of Terms</h2>
                    <p>
                        By accessing or using the Mental Health and Counseling Booking platform (the "Service"), you agree to be bound by these Terms of Service. If you are an organization subscribing on behalf of a mental health practice or clinic, you represent that you possess clinical and legal authority to bind that entity.
                    </p>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-slate-900">2. Emergency Disclaimer & Crisis Lifeline</h2>
                    <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs leading-relaxed">
                        <strong>CRITICAL EMERGENCY NOTICE:</strong> THIS PLATFORM IS NOT AN EMERGENCY DISPATCH SYSTEM. IF YOU OR SOMEONE YOU KNOW IS IN IMMEDIATE DANGER, EXPERIENCING SUICIDAL CRISIS, OR REQUIRING URGENT PSYCHIATRIC INTERVENTION, CALL 911 (OR YOUR LOCAL EMERGENCY NUMBER) OR DIAL/TEXT <strong>988</strong> IMMEDIATELY.
                    </div>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-slate-900">3. Practitioner Responsibilities & Licensing</h2>
                    <p>
                        Practitioners using the Service represent and warrant that they maintain valid, active behavioral health licenses and malpractice coverage within their practicing jurisdictions. The Platform provides tools for scheduling, documentation, and communication, but does not provide medical or psychological advice.
                    </p>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-slate-900">4. Booking, Cancellations & Rescheduling</h2>
                    <p>
                        Client appointments booked through the platform are governed by each organization's individual cancellation policy. Organizations are authorized to set minimum cancellation lead times (e.g., 24 or 48 hours) and associated late-cancellation fees.
                    </p>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-slate-900">5. Subscriptions, Fees & Billing</h2>
                    <p>
                        SaaS subscriptions for clinic tenants are billed monthly or annually according to the selected tier. Feature limits (such as practitioner seats, active client quotas, and module access) are enforced dynamically by the platform.
                    </p>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-slate-900">6. Limitation of Liability</h2>
                    <p>
                        To the maximum extent permitted by applicable law, the Platform and its operators shall not be liable for any indirect, incidental, or consequential damages arising from clinical treatment outcomes, internet connectivity interruptions during telehealth sessions, or unauthorized access resulting from user credential compromise.
                    </p>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
