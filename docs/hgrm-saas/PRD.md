# PRD — Hotel Government Reporting & Monitoring (HGRM) SaaS

Status: Draft for review · Source: client R&D summary, 2026-08-01 · See [README.md](README.md)
for decisions already locked in.

## 1. Background

Hotel Beach Way's admin panel currently runs **one hotel**. Rooms, room types, guests
(`customers`), and bookings are all global tables with no concept of "which hotel." There is
already a working custom RBAC system (`roles` + `role_permissions`, module-based, see
`app/Support/ModuleRegistry.php`) and a solid multi-room booking model
(`room_bookings` header + `booking_rooms` line items).

Bangladesh law requires hotels to register guest identity information and make it available to
local government/law enforcement (District Commissioner's office, Upazila administration,
Police/SB/NSI) for oversight — historically done on paper. The client wants to turn this system
into a **multi-tenant SaaS platform hosting many hotels**, with a **built-in government
monitoring layer** that gives DC/UNO/Police accounts read-only, jurisdiction-scoped visibility
into guest and hotel data, replacing the paper process.

## 2. Goals

1. Onboard multiple independent hotels ("tenants") into one system, each with their own rooms,
   bookings, staff, and guest registry.
2. Capture the full KYC dataset the R&D specifies for every guest (identity, address,
   documents, foreign-guest fields, marriage/companion info).
3. Give government roles (DC Office, UNO Office, Police Admin) automatic, jurisdiction-scoped
   read access to hotel and guest data — no manual data sharing, no paper.
4. Provide the specific dashboards and reports listed in the R&D (today's check-in/out, current
   occupancy, foreign guest stats, nationality breakdown, hotel-wise / guest-wise / date-wise
   reports, NID search) with PDF/Excel export.
5. Do all of this without breaking Hotel Beach Way's existing day-to-day operation — it becomes
   Tenant #1, not a special case.

## 3. Non-goals (explicitly out of scope for this phase)

- SaaS billing/subscription/metering for hotel tenants (nothing in the R&D asks for this; can be
  a later PRD if the client wants to charge other hotels to join).
- Automated NID verification against the Election Commission's database (no public API exists
  for this) — manual entry + document upload only, for now.
- Self-service public hotel sign-up flow, unless confirmed in the open question in README.md.
- Multi-language UI (Bengali labels for gov-facing fields are handled via seeded reference-data
  names and field labels, not full i18n).
- Mobile apps. This stays a responsive web admin panel.

## 4. Personas / Roles

| Role | Scope | What they can do |
|---|---|---|
| **Super Admin / Platform Owner** | All hotels, all data | Everything today's Super Admin can do, plus create/approve/suspend hotel tenants, manage geography reference data, create government accounts and assign their jurisdiction. |
| **Hotel Owner / Admin** | One hotel | Everything today's hotel admin can do (rooms, bookings, staff, reports), scoped to their own hotel only. |
| **Hotel Staff (Receptionist, etc.)** | One hotel | Subset of hotel admin capability per existing permission matrix — unchanged behavior, just tenant-scoped now. |
| **DC Office** (জেলা প্রশাসন) | One district | Read-only dashboards/reports across **every hotel in their district**. NID search, hotel-wise / guest-wise / date-wise / foreign-guest / nationality reports, PDF/Excel export. Cannot see hotel financials (rates, income) or edit anything. |
| **UNO Office** (উপজেলা প্রশাসন) | One upazila | Same report set as DC Office, scoped to hotels in their upazila. |
| **Police Admin** (থানা) | One police station (থানা) | Same report set, scoped to hotels under their police station's jurisdiction, plus a "suspicious person" flag/search on guest records. |
| *(future)* SB / NSI | District or national | Same shape as DC/Police; deferred until the client specifies their exact scope — the `roles.scope_type` design already supports adding these without a schema change. |
| Guest | N/A — data subject, not a system user | Their identity/stay data is captured by hotel staff at check-in, not self-entered. |

## 5. Functional requirements

### 5.1 Hotel Registration (Super Admin manages tenants)

Fields, per the R&D, captured per hotel:

- Hotel Name, Hotel Category, Total Room count
- Trade License, BIN, TIN (business registration numbers)
- Owner Name, Owner NID, Mobile, Email, Address
- Location: District, Upazila, Police Station (drives government jurisdiction routing —
  see § 5.6)
- Hotel Photo(s), Hotel Documents (license/BIN/TIN certificates, scanned)

A hotel record has a status (pending / active / suspended / rejected) so it can be taken offline
without deleting its data.

### 5.2 Multi-tenant hotel operations (existing features, now tenant-scoped)

Everything the admin panel already does — Room Types, Rooms, Bookings, Customers, Reports,
Website Management, Global Settings — continues to work exactly as today for hotel staff, except
every query is automatically filtered to their own hotel. A hotel admin should not be able to see
or affect another hotel's data by any route, including guessing IDs in a URL.

### 5.3 Guest / Customer KYC

**Personal details**: Full Name, Father's Name, Mother's Name, Gender, Date of Birth,
Nationality, Occupation, Mobile, Email, Emergency Contact, Present Address, Permanent Address,
District, Upazila, Police Station, Post Code.

**Identity information**: NID Number (+ scan upload, front & back), Birth Certificate Number,
Passport Number (+ scan upload), Driving License (+ upload).

**Photo capture**: webcam capture or mobile camera / file upload for the guest photo.

**Foreign guest fields** (required when nationality ≠ Bangladeshi): Passport, Country, Visa
number, Date of Arrival in Bangladesh.

**Marriage / companion info** (when checking in as a couple): Husband Name, Wife Name, Marriage
Date, Nikahnama copy upload. MVP scope per README open question #2.

**Document uploads** (general): Guest Photo, NID, Passport, Visa, Marriage Certificate, Other
Documents — all stored against the guest record with a category label.

### 5.4 Government Monitoring Module (HGRM)

Per office, all read-only over their jurisdiction's hotels:

- See every hotel in their jurisdiction (district / upazila / police station as applicable).
- Search guests by NID number, across all in-jurisdiction hotels.
- Hotel-wise report, Guest-wise report, Date-wise report, Foreign Guest report, Nationality
  report.
- Export any report to PDF or Excel/CSV.
- Police Admin additionally gets a "suspicious person" flag/search over guest records (a boolean
  + note field set by police on a guest record, visible to police roles only).

### 5.5 Government Dashboard

On login, DC / UNO (and Police, by extension) see:

- Today's check-ins, today's check-outs, current in-house guest count.
- Guest count per hotel.
- Foreign guest count.
- Male / female split.
- District-wise, upazila-wise, hotel-wise breakdowns.
- Last 30 days trend.

**Hotel-wise report table**: Hotel Name | Current Guests | Today Check-in | Today Check-out.

**Guest report table**: Guest Name | Photo | NID | Mobile | Room | Check-in | Check-out |
Status — with the foreign-guest fields and couple/marriage fields shown when applicable.

### 5.6 Jurisdiction routing

A hotel's District / Upazila / Police Station (captured at registration, § 5.1) is what
determines *which* DC / UNO / Police accounts automatically see it. See
[DATABASE-SCHEMA.md § Jurisdiction access model](DATABASE-SCHEMA.md#jurisdiction-access-model)
for the mechanics.

## 6. Data privacy & security requirements

This system stores sensitive citizen PII (NID numbers, passport numbers, photos, marriage
documents) and gives government accounts cross-hotel visibility — both raise the bar above a
normal hotel booking app:

- **Isolation is a correctness requirement, not just UX**: a hotel admin must never be able to
  read another hotel's guest/booking data, and a DC in District A must never see District B.
  This needs explicit automated tests (Phase 7), not just "the UI doesn't show a link to it."
- **Audit trail**: every view/export of guest identity data by a government or platform account
  should be logged (who, what record, when) — expected of a system positioned as a government
  compliance tool. See `audit_logs` in the schema doc (Phase 7).
- **Document storage**: NID/passport scans and photos should not be publicly reachable by
  guessable URL (matches how `storage/app` uploads should already be handled — verify during
  Phase 5 build that new upload paths aren't put under `public/` without access control).

## 7. Reporting & export requirements

- PDF export: already available in the codebase (`barryvdh/laravel-dompdf`) — reuse.
- Excel/CSV export: no package currently installed. Recommend a plain CSV streamed response for
  the tabular reports (no new dependency) unless the client specifically wants styled `.xlsx`
  output, in which case add `maatwebsite/excel` — decide at Phase 6.

## 8. Assumptions

- Bangladesh's administrative hierarchy (Division → District → Upazila, and District → Police
  Station/Thana, which does not perfectly nest under Upazila in metro areas) is seeded once as
  reference data, not user-editable day to day.
- "Hotel Beach Way" itself becomes the first tenant; no functionality is expected to change for
  its staff other than everything now being implicitly scoped to their hotel.
- Existing `roles`/`role_permissions`/`ModuleRegistry` system is extended, not replaced.

## 9. Success criteria

- A second hotel can be onboarded and its staff can operate independently with zero visibility
  into Hotel Beach Way's data, and vice versa.
- A seeded DC Office account for a given district sees exactly the hotels in that district (and
  the guests within them), automatically, with no manual per-hotel sharing step.
- All R&D-listed report types are producible and exportable for a government account, correctly
  scoped to their jurisdiction.
- All new guest KYC fields from § 5.3 are capturable at check-in and visible on the guest record.
