@extends('layouts.app', ['title' => 'Data Protection & GDPR Compliance'])

@section('content')
<div class="py-16 bg-slate-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl border border-slate-200 p-8 sm:p-12 shadow-sm">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-teal-100 text-teal-800 tracking-wide uppercase">Legal & Compliance</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-3 mb-6">Data Protection & GDPR Statement</h1>
            <p class="text-xs text-slate-500 mb-8 pb-6 border-b border-slate-100">Last updated: January 1, 2026</p>

            <div class="prose prose-slate max-w-none text-slate-700 text-sm leading-relaxed space-y-6">
                <section>
                    <h2 class="text-lg font-bold text-slate-900">1. Regulatory Framework</h2>
                    <p>
                        We process personal data in compliance with the General Data Protection Regulation (GDPR) Regulation (EU) 2016/679, the UK Data Protection Act 2018, and health information privacy standards worldwide.
                    </p>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-slate-900">2. Data Controller vs. Data Processor</h2>
                    <p>
                        When an organization (counseling clinic, psychology practice, university health center) uses our platform, that organization is the <strong>Data Controller</strong> of its client medical and psychological records. Our company serves as the <strong>Data Processor</strong> providing cloud software infrastructure, secure database isolation, and automated backup services under a Data Processing Addendum (DPA).
                    </p>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-slate-900">3. Rights of Data Subjects</h2>
                    <p>Under GDPR and international privacy regulations, data subjects possess the following rights:</p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>Right of Access:</strong> Request a comprehensive export of personal profile data, appointment attendance records, and billing receipts.</li>
                        <li><strong>Right to Rectification:</strong> Rectify inaccurate contact or profile information via the Client Portal.</li>
                        <li><strong>Right to Erasure ("Right to be Forgotten"):</strong> Request data deletion, subject to statutory medical record retention mandates applicable to healthcare providers.</li>
                        <li><strong>Right to Data Portability:</strong> Obtain structured data exports (JSON/CSV) of personal data.</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-slate-900">4. Retention of Clinical Records</h2>
                    <p>
                        Medical and psychological licensing boards require clinical records to be retained for specified statutory periods (typically 7 years for adult clients, or until age of majority plus statutory duration for minors). We honor legal record-retention requirements while enabling tenants to archive closed client charts securely.
                    </p>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
