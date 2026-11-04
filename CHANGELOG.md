# Changelog
All notable changes to **Mental Health and Counseling Booking** will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-01-01

### Added
- **Core SaaS Architecture:** Multi-tenant architecture with global query scopes and tenant data isolation.
- **26 Business Modules:** Centralized module registry (`ModuleRegistry`) with dependency graph and tenant toggle capabilities.
- **Role-Based Access Control:** 10 core roles including Super Admin, Owner, Admin, Manager, Clinician, Receptionist, Billing Manager, Client, Guardian, and Auditor.
- **Clinical Documentation System:** HIPAA/GDPR-compliant SOAP, DAP, BIRP, GIRP, Progress, and Discharge notes with cryptographic digital signing, versioned amendments, and access logs.
- **Collision-Free Booking Engine:** Real-time slot calculation respecting practitioner hours, breaks, buffer times, and leave days with database row locking.
- **WebRTC Telehealth Consultations:** Browser-based encrypted video consultation rooms with session timers and crisis notices.
- **Self-Service Client Portal:** Responsive portal for booking sessions, viewing appointments, launching video rooms, settling invoices, downloading Unicode PDF receipts, reviewing clinical summaries, and secure messaging.
- **Integrated Imran Ahmed Packages:**
  - `imrandevbd/drutogoti` for performance acceleration and query caching.
  - `imrandevbd/laravel-admin-panel` for secondary operations management at `/ops`.
  - `imrandevbd/laravel-ai-hub` for AI Studio at `/ai-hub`.
  - `imrandevbd/laravel-ai-catalog` for clinical content and prompt catalog.
  - `imrandevbd/laravel-attendance-hub` for biometric ADMS device bridge.
  - `imrandevbd/laravel-unicode-pdf` for multilingual invoice receipts.
  - `imrandevbd/laravel-universal-slug` for multilingual Unicode blog slugs.
- **Multilingual Public Marketing Website:** Complete with 10 one-click demo personas, 26 module showcase, pricing tiers, FAQ accordion, contact forms, legal compliance pages, and crisis helpline notices (988).
- **REST API v1:** Comprehensive JSON endpoints under `/api/v1/` secured with Laravel Sanctum tokens.
- **Automated Test Suite:** 28 Unit and Feature tests covering tenant isolation, clinical note security, booking engine collisions, module management, Unicode PDF receipts, and REST API endpoints.
