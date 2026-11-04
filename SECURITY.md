# Security & PHI Safeguards Policy
### Mental Health and Counseling Booking SaaS Platform

---

## 1. Zero-Trust Architecture & PHI Isolation

The security architecture of **Mental Health and Counseling Booking** enforces the principle of least privilege and strict separation of concerns for behavioral healthcare data.

### Technical Separation of Demographics vs. Clinical PHI
In traditional CRM systems, all staff members can inspect customer interactions. In our platform:
1. **Front Desk / Receptionists:** Can view appointment slots, client names, contact numbers, and payment status. They have **zero read or write access** to `clinical_notes`, `treatment_plans`, or diagnosis codes.
2. **Billing Managers:** Have access strictly to service codes, fee totals, and payment reconciliation. They cannot inspect clinical psychotherapy notes or subjective client disclosures.
3. **Licensed Clinicians:** Can only view clinical records for clients explicitly assigned to their caseload or supervised under clinical director oversight.

---

## 2. Immutable Clinical Records & Amendments

Under medical documentation statutes:
- When a clinician electronically signs a note (SOAP, DAP, BIRP, GIRP, Progress, or Discharge note), the record is locked against in-place edits.
- The system generates a cryptographic SHA-256 digital signature digest capturing practitioner identity, client identity, timestamp, and content.
- Any subsequent correction generates an immutable amendment record (`clinical_note_versions`), retaining the original text, the altered text, the clinician identity, and the formal clinical justification.

---

## 3. Cryptographic Audit Trails

Every read, write, update, sign, or export of clinical data creates an immutable row in `clinical_note_access_logs` and `audit_logs` storing:
- Authenticated `user_id` and assigned role
- Exact action (`view`, `create`, `edit`, `sign`, `lock`, `export`, `print`)
- Client IPv4/IPv6 address
- Full browser User Agent string
- Timestamp with microsecond precision

---

## 4. Telehealth & WebRTC Privacy

- Telehealth video consultations use end-to-end encrypted WebRTC streams.
- Audio and video streams are completely ephemeral; no audio or video streams are recorded, buffered, or retained on platform servers unless explicitly consented to and recorded by licensed practitioners under statutory guidelines.
- Telehealth room IDs are cryptographically randomized strings with high entropy (`serenity-room-xxxxxxxxxxxx`).

---

## 5. Non-Emergency Safety Notices

Because mental health platforms may be accessed by individuals experiencing severe psychological distress, the platform prominently embeds non-emergency crisis banners across all public, booking, and portal views:
- Explicit warnings that the platform is not an emergency psychiatric response service.
- Direct prominent links to the **988 Suicide & Crisis Lifeline** (free, confidential, 24/7).

---

## 6. Vulnerability Disclosure Policy

If you discover a security vulnerability or potential PHI exposure within this platform:
1. **Do not** disclose the vulnerability publicly or in public issue trackers.
2. Email full technical details and proof-of-concept steps directly to **Imran Ahmed** at <security@imrandev.bd> and <me@imrandev.bd>.
3. We acknowledge reports within 24 hours and prioritize PHI-related disclosures with utmost urgency.

---

## 7. Security Officer

- **Lead Cybersecurity Architect:** Imran Ahmed
- **Email:** me@imrandev.bd
- **Official Website:** [https://imrandev.bd](https://imrandev.bd)
