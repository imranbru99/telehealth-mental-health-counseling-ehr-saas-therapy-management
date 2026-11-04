@extends('layouts.app', ['title' => 'Privacy Policy & Clinical Confidentiality'])

@section('content')
<div class="py-16 bg-slate-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl border border-slate-200 p-8 sm:p-12 shadow-sm">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-teal-100 text-teal-800 tracking-wide uppercase">Legal & Compliance</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-3 mb-6">Privacy Policy & HIPAA Notice</h1>
            <p class="text-xs text-slate-500 mb-8 pb-6 border-b border-slate-100">Last updated: January 1, 2026 &bull; Effective for all tenants and clients</p>

            <div class="prose prose-slate max-w-none text-slate-700 text-sm leading-relaxed space-y-6">
                <section>
                    <h2 class="text-lg font-bold text-slate-900">1. Commitment to Health Data Privacy</h2>
                    <p>
                        Mental Health and Counseling Booking ("the Platform", "we", "us") operates as a secure, multi-tenant Practice Management and Electronic Health Record (EHR) SaaS ecosystem. We recognize the profound sensitivity of behavioral health records and adhere strictly to the Health Insurance Portability and Accountability Act (HIPAA), the General Data Protection Regulation (GDPR), and applicable mental healthcare licensing confidentiality statutes.
                    </p>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-slate-900">2. Protected Health Information (PHI) Isolation</h2>
                    <p>
                        Clinical notes (including SOAP, DAP, BIRP, GIRP, Progress, and Discharge notes) are classified as Protected Health Information (PHI). We enforce strict technical separation between demographic scheduling data and clinical documentation:
                    </p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Receptionists and front-desk personnel can view appointment schedules, client names, and contact details, but have zero programmatic or visual access to clinical session records.</li>
                        <li>Billing personnel have access solely to invoice numbers, service codes, and transaction amounts.</li>
                        <li>Every read, write, or export of clinical records generates an immutable audit record containing timestamp, IP address, user identity, and session identifier.</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-slate-900">3. Multi-Tenant Data Sovereignty</h2>
                    <p>
                        All tenant data is strictly segregated at the database query layer via global tenant scopes. Practitioners and administrators in Tenant Organization A cannot access, query, or enumerate clients, appointments, or staff records of Tenant Organization B.
                    </p>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-slate-900">4. Clinician-in-the-Loop Artificial Intelligence</h2>
                    <p>
                        Where AI features (such as draft summarization or session notes assistance) are enabled:
                    </p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li>AI outputs are treated as strictly advisory drafts and require active practitioner review and digital signature before inclusion in the medical record.</li>
                        <li>PHI is de-identified and never used to train public machine learning models.</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-slate-900">5. Client Rights & Data Portability</h2>
                    <p>
                        Clients retain the right to inspect their records, request rectifications, and obtain cryptographically sealed PDF copies of their invoices and clinical summaries, subject to practitioner clinical discretion and jurisdictional safety exceptions.
                    </p>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-slate-900">6. Contact Our Privacy Officer</h2>
                    <p>
                        For inquiries regarding health data confidentiality or Business Associate Agreements (BAA), please contact our compliance desk at <a href="mailto:privacy@imrandev.bd" class="text-teal-600 font-semibold underline">privacy@imrandev.bd</a>.
                    </p>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
