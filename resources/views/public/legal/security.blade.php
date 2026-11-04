@extends('layouts.app', ['title' => 'Security & PHI Safeguards - Mental Health SaaS'])

@section('content')
<div class="py-16 bg-slate-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl border border-slate-200 p-8 sm:p-12 shadow-sm">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-teal-100 text-teal-800 tracking-wide uppercase">Infrastructure & PHI Defense</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-3 mb-6">Security & PHI Safeguards</h1>
            <p class="text-xs text-slate-500 mb-8 pb-6 border-b border-slate-100">Zero-Trust Clinical Architecture &bull; Technical & Physical Safeguards</p>

            <div class="prose prose-slate max-w-none text-slate-700 text-sm leading-relaxed space-y-6">
                <section>
                    <h2 class="text-lg font-bold text-slate-900">1. Data Encryption in Transit & at Rest</h2>
                    <p>
                        All platform communications enforce Transport Layer Security (TLS 1.3) with modern cipher suites. High-entropy encryption protects sensitive records, cryptographic signature digests, and database backups at rest.
                    </p>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-slate-900">2. Immutable Clinical Note Versioning & Signing</h2>
                    <p>
                        Once a practitioner signs a clinical note (SOAP, DAP, BIRP, GIRP, Progress, or Discharge note), the record is locked against direct edits. Any subsequent alteration generates an explicit, version-incremented amendment record preserving:
                    </p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Original locked content and timestamp.</li>
                        <li>Clinician identity and digital signature hash.</li>
                        <li>Clinical reason for amendment.</li>
                        <li>Complete historical revision ledger.</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-slate-900">3. Granular Role-Based Access Control (RBAC)</h2>
                    <p>
                        Access permissions are enforced server-side. Receptionists and billing clerks cannot bypass API or front-end barriers to view psychotherapy notes. Super Administrators cannot access individual clinical therapy sessions without explicit tenant authorization.
                    </p>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-slate-900">4. Telehealth Security & WebRTC Isolation</h2>
                    <p>
                        Video consultations utilize peer-to-peer encrypted WebRTC channels. Video/audio streams are ephemeral and are not recorded or persisted on platform servers unless explicitly configured by licensed practitioners with bilateral client consent.
                    </p>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-slate-900">5. Responsible Security Disclosure</h2>
                    <p>
                        We welcome vulnerability reports from security researchers. Please review our disclosure policy or contact our engineering team directly at <a href="mailto:security@imrandev.bd" class="text-teal-600 font-semibold underline">security@imrandev.bd</a>.
                    </p>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
