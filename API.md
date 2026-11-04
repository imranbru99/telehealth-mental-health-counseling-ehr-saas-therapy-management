# REST API Documentation (v1)
### Mental Health and Counseling Booking SaaS Platform

The platform exposes a versioned JSON REST API under `/api/v1/` for mobile clients, third-party practice integrations, and EHR inter-operability.

---

## 1. Authentication

Authentication uses **Laravel Sanctum** Bearer tokens.

### `POST /api/v1/auth/login`
Authenticates a user and generates an API Bearer token.

**Request:**
```json
{
  "email": "client@demo.mhcb.test",
  "password": "Demo@12345",
  "device_name": "iOS-Client-App"
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "token": "1|n1n7O8zJ2M1...",
  "user": {
    "id": 8,
    "name": "Alexander Cole (Patient)",
    "email": "client@demo.mhcb.test",
    "role": "client",
    "tenant_id": 1
  }
}
```

### `POST /api/v1/auth/logout`
Revokes the current access token. Requires Bearer Token.

---

## 2. Public Practice Endpoints

### `GET /api/v1/practitioners`
Returns the list of active clinicians, licensure, specialties, and session fees.

**Query Parameters:**
- `specialty` (optional): Filter by clinical specialty (e.g. `CBT`, `Trauma`).

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Dr.",
      "specialties": ["CBT", "Anxiety & Panic", "Trauma / PTSD"],
      "languages": ["English"],
      "qualifications": "Doctorate / Master of Clinical Psychology.",
      "license_number": "PSY-CA-28491",
      "license_verified": true,
      "base_fee": 180.00,
      "session_duration_minutes": 50,
      "rating": 4.95,
      "user": {
        "id": 5,
        "name": "Dr. Sarah Mitchell, PsyD",
        "email": "practitioner@demo.mhcb.test"
      }
    }
  ]
}
```

---

### `GET /api/v1/services`
Retrieves all active clinical services offered by the clinic.

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Initial Clinical Intake & Assessment",
      "slug": "initial-clinical-intake",
      "duration_minutes": 90,
      "price": 220.00,
      "type": "individual",
      "delivery_mode": "hybrid"
    }
  ]
}
```

---

### `GET /api/v1/appointments/slots`
Calculates real-time, collision-free time slots for a clinician on a given date.

**Query Parameters:**
- `practitioner_id` (required, int): Practitioner ID.
- `date` (required, string): Date formatted as `YYYY-MM-DD`.

**Response (200 OK):**
```json
{
  "success": true,
  "date": "2026-11-12",
  "slots": ["09:00", "10:00", "11:00", "13:00", "14:00", "15:00", "16:00"]
}
```

---

### `POST /api/v1/appointments`
Books an appointment with collision prevention.

**Request:**
```json
{
  "practitioner_id": 1,
  "client_id": 1,
  "service_id": 2,
  "scheduled_at": "2026-11-12 10:00:00",
  "delivery_mode": "online_telehealth"
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Appointment booked successfully.",
  "data": {
    "id": 51,
    "appointment_number": "APT-00051",
    "scheduled_at": "2026-11-12T10:00:00.000000Z",
    "status": "confirmed",
    "delivery_mode": "online_telehealth",
    "telehealth_meeting_url": "https://meet.jit.si/serenity-room-xyz"
  }
}
```

---

## 3. SaaS Modules Endpoint

### `GET /api/v1/modules`
Returns the status and configuration of all 26 registered SaaS business modules.

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": "appointments_booking",
      "name": "Appointment Booking Engine",
      "category": "operations",
      "is_core": true,
      "default_enabled": true
    }
  ]
}
```

---

## 4. Protected Endpoints (Bearer Token Required)

- `GET /api/v1/appointments`: List appointments for authenticated user.
- `GET /api/v1/therapy-sessions`: List completed therapy sessions with clinician summaries.
- `GET /api/v1/clinical-notes`: Accessible by authorized clinicians and clinical supervisors.
- `GET /api/v1/invoices`: List billing records, balances, and payment statuses.

---

## 5. Author Information

- **API Architect:** Imran Ahmed
- **Email:** me@imrandev.bd
- **Website:** [https://imrandev.bd](https://imrandev.bd)
