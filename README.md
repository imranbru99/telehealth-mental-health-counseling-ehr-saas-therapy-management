# Mental Health and Counseling Booking SaaS Platform
### Complete Enterprise Behavioral Healthcare Practice Management, Appointment Booking, Therapy Sessions, & Clinical Documentation

[![PHP Version](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=flat&logo=php)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![Filament Version](https://img.shields.io/badge/Filament-3.x-F59E0B?style=flat&logo=livewire)](https://filamentphp.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-teal.svg)](LICENSE)
[![Security: HIPAA Aligned](https://img.shields.io/badge/Compliance-HIPAA%20%2F%20GDPR-0D9488.svg)](SECURITY.md)
[![Lead Architect](https://img.shields.io/badge/Architect-Imran%20Ahmed-0f766e?style=flat)](https://imrandev.bd)

---

## 1. System Overview

**Mental Health and Counseling Booking** is a mission-critical, multi-tenant Practice Management and Electronic Health Record (EHR) SaaS ecosystem calibrated specifically for mental health clinics, psychology practices, counseling centers, individual psychotherapists, university counseling centers, and telehealth networks.

Unlike generic scheduling widgets or cumbersome legacy hospital software, this platform is engineered from the ground up for the nuances of psychological care:
- **Zero-Barrier Client Intake:** Collision-free public booking engine, automated intake screeners (biopsychosocial, GAD-7, PHQ-9), and informed consent flows.
- **Strict Clinical PHI Isolation:** Clinical records (SOAP, DAP, BIRP, GIRP, Progress, and Discharge notes) are isolated from front-desk receptionists and billing managers through database-level scopes and authorization policies.
- **Immutable Digital Signatures & Audit Trails:** Cryptographically sealed clinical notes that lock upon practitioner signing; amendments are stored as versioned revisions with mandatory clinical justifications.
- **Embedded WebRTC Telehealth:** Encrypted, zero-persistence browser-based video rooms with session timers, camera/mic controls, and non-emergency psychiatric crisis safeguards (988 integration).
- **Comprehensive SaaS Modularization:** 26 independently toggleable SaaS business modules controlled via a centralized module registry with dependency resolution.

---

## 2. Integrated Package Architecture

The platform integrates seven specialized enterprise packages developed by **Imran Ahmed** (`https://imrandev.bd`):

| Package | Version | Architectural Responsibility |
|---|---|---|
| `imrandevbd/drutogoti` | `dev-main` | High-performance HTTP request acceleration, intelligent query caching, and asset minification. |
| `imrandevbd/laravel-admin-panel` | `dev-main` | Secondary operations and DevOps interface (mounted at `/ops` to deconflict with Filament's `/admin`). |
| `imrandevbd/laravel-ai-hub` | `dev-main` | Secure AI integration engine & clinician-in-the-loop studio (mounted at `/ai-hub`). |
| `imrandevbd/laravel-ai-catalog` | `dev-main` | Clinical content library, evidence-based therapy templates, and resource generation engine. |
| `imrandevbd/laravel-attendance-hub` | `dev-main` | Biometric ADMS hardware bridge connecting clinic fingerprint/facial terminals to staff HR attendance. |
| `imrandevbd/laravel-unicode-pdf` | `dev-main` | Multilingual Unicode PDF engine rendering UTF-8 receipts in Bengali, English, Arabic, and more. |
| `imrandevbd/laravel-universal-slug` | `dev-main` | Unicode-compliant URL slug generator supporting native Bengali (`/blog/মানসিক-স্বাস্থ্য`) and international scripts. |

---

## 3. The 26 SaaS Business Modules

Every module can be activated or deactivated globally by the Super Administrator, or per-tenant according to commercial subscription plans:

1. **Dashboard & Analytics** (`dashboard_analytics`): Role-tailored metrics for Super Admins, Owners, Clinicians, Front Desk, and Clients.
2. **Organization & Branch Management** (`organization_branches`): Multi-clinic locations, working shifts, and manager delegation.
3. **Practitioner Management** (`practitioner_management`): Clinician licensure verification, specialties, credentials, and bios.
4. **Client CRM & Demographics** (`client_crm`): Demographics, emergency contacts, guardian links (strictly segregated from PHI).
5. **Appointment Booking Engine** (`appointments_booking`): Collision-free calendar, buffer times, waitlists, and database locking.
6. **Calendar & Availability Scheduling** (`calendar_availability`): Working hours, recurring availability, and approved leaves.
7. **Therapy Session Workflow** (`therapy_sessions`): Full session lifecycle, timer, modality tracking, and documentation check.
8. **Clinical Documentation & Notes** (`clinical_notes`): SOAP, DAP, BIRP, GIRP, Progress, and Discharge notes with digital signatures.
9. **Treatment Planning & Outcomes** (`treatment_plans`): ICD-10/DSM-5 diagnostic coding, measurable goals, and progress tracking.
10. **Custom Forms & Clinical Intake** (`form_templates`): Biopsychosocial screeners, GAD-7, PHQ-9, and dynamic form builder.
11. **Informed Consent & Legal Agreements** (`consent_records`): Electronic signatures, versioned HIPAA notices, and telehealth consents.
12. **Telehealth Video Consultations** (`telehealth`): End-to-end encrypted browser-based WebRTC/Jitsi video rooms.
13. **Invoicing & Billing Engine** (`invoicing_billing`): Automated billing, Superbills, Unicode PDF receipts, and transaction ledgers.
14. **Online Payments & Subscriptions** (`payments_subscriptions`): Test-mode Stripe payment gateway and multi-tier SaaS billing.
15. **Biometric HR & Attendance Hub** (`attendance_management`): ZKTeco/ADMS biometric device synchronization and staff timesheets.
16. **Staff Leave & Shift Management** (`leave_management`): Time-off requests, vacation approvals, and calendar blackout sync.
17. **Client Self-Service Portal** (`client_portal`): Patient appointment management, video room launch, receipts, and profile.
18. **Secure Clinical Messaging** (`secure_messaging`): Encrypted internal communication between clients, therapists, and staff.
19. **Notifications & SMS Reminders** (`notifications_reminders`): Automated booking reminders, email logs, and crisis notices.
20. **Multilingual CMS & Educational Journal** (`cms_blog`): Evidence-based therapy articles in English and Bengali (`universal-slug`).
21. **Support Tickets & Helpdesk** (`support_tickets`): Technical support queues, issue tracking, and ticket escalation.
22. **Client Reviews & Ratings** (`reviews_feedback`): Anonymous client satisfaction feedback and therapy quality metrics.
23. **Clinical Document Vault** (`document_vault`): Secure storage of intake reports, evaluation summaries, and patient handouts.
24. **Audit Logs & PHI Access Records** (`audit_logging`): Immutable HIPAA/GDPR audit ledger recording every view, sign, and export.
25. **Clinician-in-the-Loop AI Assistant** (`ai_clinical_assistant`): Non-diagnostic drafting aid with mandatory clinician signature oversight.
26. **Multi-Tenant System Engine** (`multi_tenancy`): Global database scoping, custom subdomains, and isolated tenant environments.

---

## 4. One-Click Synthetic Demo Personas

The application includes synthetic seed data for **"Serenity Mental Wellness Center"** with 10 pre-configured accounts:

| Persona | Email | Default Password | Panel / Destination |
|---|---|---|---|
| **Super Administrator** | `superadmin@demo.mhcb.test` | `Demo@12345` | Global SaaS Operations (`/admin`) |
| **Organization Owner** | `owner@demo.mhcb.test` | `Demo@12345` | Serenity Clinic Administration (`/admin`) |
| **Organization Admin** | `admin@demo.mhcb.test` | `Demo@12345` | Operations & Staff (`/admin`) |
| **Clinic Manager** | `manager@demo.mhcb.test` | `Demo@12345` | Scheduling & Biometric Attendance (`/admin`) |
| **Therapist / Counselor** | `practitioner@demo.mhcb.test` | `Demo@12345` | Clinical Notes & Telehealth (`/admin`) |
| **Receptionist** | `reception@demo.mhcb.test` | `Demo@12345` | Front Desk & Appointments (`/admin`) |
| **Billing Manager** | `billing@demo.mhcb.test` | `Demo@12345` | Invoices, Fees & PDF Superbills (`/admin`) |
| **Compliance Auditor** | `auditor@demo.mhcb.test` | `Demo@12345` | Audit Logs & PHI Access History (`/admin`) |
| **Client / Patient** | `client@demo.mhcb.test` | `Demo@12345` | Client Portal (`/portal`) |
| **Parent / Guardian** | `guardian@demo.mhcb.test` | `Demo@12345` | Family Portal & Minor Consents (`/portal`) |

> [!TIP]
> The login screen at `/login` features **10 one-click persona cards** that auto-fill the form with credentials instantly.

---

## 5. Security & Clinical Confidentiality Safeguards

- **HIPAA Technical Separation:** Front desk receptionists and billing staff can view demographic scheduling details, but have zero access to clinical psychotherapy notes (`clinical_notes`).
- **Cryptographic Signature Verification:** Signed clinical notes compute an immutable SHA-256 hash. Modifications are prohibited; amendments create separate versioned snapshots (`clinical_note_versions`).
- **Comprehensive Audit Trails:** Every access, view, or export of sensitive client charts is immutably logged in `clinical_note_access_logs` and `audit_logs` with client IP and user agent.
- **Psychiatric Emergency Notices:** Every page prominently displays non-emergency warnings and direct routing to the **988 Suicide & Crisis Lifeline**.
- **Clinician-in-the-Loop AI:** AI suggestions are explicitly marked as preliminary drafts and require manual clinician review and digital signature before being committed to records.

---

## 6. Installation & Quickstart

```bash
# 1. Clone or navigate to the workspace
cd /home/imran/project/new/mental-health-and-counseling-booking-sessions-and-notes

# 2. Install PHP and Node dependencies
composer install
npm install

# 3. Configure environment
cp .env.example .env
php artisan key:generate

# 4. Migrate database & seed synthetic demo environment
php artisan migrate:fresh --seed

# 5. Build frontend production assets
npm run build

# 6. Execute automated test suite
php artisan test

# 7. Start local development server
php artisan serve
```

Access the application in your browser:
- Marketing Website: `http://localhost:8000`
- Booking Wizard: `http://localhost:8000/book`
- Client Health Portal: `http://localhost:8000/portal`
- Clinical Admin Panel (Filament): `http://localhost:8000/admin`
- Operations Panel: `http://localhost:8000/ops`
- AI Studio Hub: `http://localhost:8000/ai-hub`

---

## 7. REST API v1 Quick Reference

All endpoints are versioned under `/api/v1/` and support Sanctum Bearer tokens:

- `POST /api/v1/auth/login`: Authenticate and receive Sanctum API token.
- `GET /api/v1/practitioners`: List active clinicians with specialties and fees.
- `GET /api/v1/services`: List available counseling services and modalities.
- `GET /api/v1/branches`: List clinic locations and branch contacts.
- `GET /api/v1/appointments/slots`: Query collision-free slots (`?practitioner_id=1&date=YYYY-MM-DD`).
- `POST /api/v1/appointments`: Book an appointment with collision prevention.
- `GET /api/v1/modules`: List all 26 registered SaaS modules.
- `GET /api/v1/appointments`: List authenticated user's appointments.
- `GET /api/v1/therapy-sessions`: List therapy session history.
- `GET /api/v1/clinical-notes`: Authorized clinicians access clinical notes.
- `GET /api/v1/invoices`: View client invoices and payment statuses.

---

## 8. Developer Identity & Git Author

- **Developer Name:** Imran Ahmed
- **Git Author & Committer:** Imran Ahmed (`me@imrandev.bd`)
- **Official Website:** [https://imrandev.bd](https://imrandev.bd)
- **Copyright:** &copy; 2026 Imran Ahmed. All rights reserved.
- **License:** MIT License. See [LICENSE](LICENSE) for details.
