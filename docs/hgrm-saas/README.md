# HGRM SaaS — Hotel Government Reporting & Monitoring

This is the planning package for turning Hotel Beach Way from a **single-hotel admin panel**
into a **multi-tenant hotel SaaS** with a built-in **government monitoring module** (District
Commissioner / DC Office, Upazila Nirbahi Officer / UNO Office, Police), based on the R&D
summary provided on 2026-08-01.

**Progress: all 8 phases done** (Phase 0's decisions through Phase 7's rollout hardening — see
[WORKFLOW-ROADMAP.md](WORKFLOW-ROADMAP.md)). The one thing genuinely left undone is a user action,
not a build task: Phase 0's decisions were proceeded with under auto-mode rather than formally
reviewed and signed off — they held up through all 7 build phases without needing to be revised,
but a read-through is still worth doing. Everything else — multi-tenancy, RBAC/jurisdiction, hotel
registration, guest KYC, Monitoring Reports/dashboards, and isolation/audit/security hardening —
is built, tested (71 automated tests), and verified live against the real dev database.

## Doc map

| Doc                                                  | Purpose                                                                                                                                  |
| ---------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- |
| [PRD.md](PRD.md)                                     | What we're building and why — personas, functional requirements, non-goals, privacy/security requirements. Read this first.              |
| [DATABASE-SCHEMA.md](DATABASE-SCHEMA.md)             | Every new/changed table, field by field, with an ERD and the reasoning behind each modeling choice.                                      |
| [WORKFLOW-ROADMAP.md](WORKFLOW-ROADMAP.md)           | The build broken into 8 sequential phases with checkboxes. This is the file to update as work happens.                                   |
| [GOVERNMENT-USER-GUIDE.md](GOVERNMENT-USER-GUIDE.md) | What a DC/UNO/Police account actually sees day to day, plus the go-live checklist for turning a test government account into a real one. |

## How to track progress

- Work phase by phase, in order — each phase in `WORKFLOW-ROADMAP.md` lists what it depends on.
- Check off tasks (`- [ ]` → `- [x]`) as they're completed, and flip the phase `Status:` line.
- Commit changes to these docs in the same commit/PR as the code that completes them, so git
  history stays the source of truth for _when_ something shipped.
- If a decision below turns out wrong once we're building, update this doc rather than silently
  drifting from it.

## Decisions already made (baked into the docs below)

These resolve ambiguities in the original R&D notes. Flagging them up front so they can be
challenged now, before schema exists, rather than after:

1. **Tenancy model**: single database, shared schema, row-level `hotel_id` tenant column
   (not separate DBs per hotel, not a package like `tenancy/tenancy`). Justification in
   [DATABASE-SCHEMA.md § Tenancy strategy](DATABASE-SCHEMA.md#tenancy-strategy).
2. **Government access = jurisdiction auto-scope + optional manual override**, not pure manual
   assignment. A DC user tagged with `district_id = X` automatically sees every hotel in
   district X (matches "জেলার সব হোটেল দেখতে পারবে" in the R&D). An optional
   `hotel_user_assignments` table lets a specific hotel be added/restricted per user when the
   default jurisdiction match isn't right. Details in
   [DATABASE-SCHEMA.md § Jurisdiction access model](DATABASE-SCHEMA.md#jurisdiction-access-model).
3. **Guests are per-hotel, not a global deduplicated identity.** The same person staying at two
   different hotels produces two `customers` rows (one per hotel), exactly like two different
   paper registers today. Government NID search works across hotels in-jurisdiction by matching
   `nid_number`, not by merging identities. Avoids an identity-resolution problem nobody asked
   for.
4. **Existing "Hotel Beach Way" data becomes Tenant #1**, backfilled with a real `hotel_id`
   rather than treated as a special untenanted case.
5. **Reuse the existing Admin shell and permission system** (`roles` / `role_permissions` /
   `ModuleRegistry`) for government users instead of building a separate portal. A "DC Office"
   role is just a role with `dashboard` + `gov-reports` module permissions and no others — the
   existing permission-driven sidebar already hides everything else.
6. **`divisions` table is in scope** (resolved during Phase 1 build) — Bangladesh's real hierarchy
   is Division → District, cheap to seed alongside districts, expensive to retrofit later.
7. **Police stations are seeded as a rural approximation** (one row per upazila, name mirrored —
   correct for the large majority of the country and for Cox's Bazar specifically) because no
   verified nationwide open dataset exists for থানা (thana)/police-station boundaries. Metropolitan police
   stations (Dhaka Metropolitan Police and 8 other metro forces) are an explicit open follow-up,
   tracked in [WORKFLOW-ROADMAP.md Phase 1](WORKFLOW-ROADMAP.md#phase-1--bangladesh-geography-reference-data),
   not guessed at.
8. **Hotel legal-ID fields (`trade_license_no`, `owner_name`, `owner_nid`) are nullable**
   (resolved during Phase 2 build) — the system has never stored these anywhere, and backfilling
   Tenant #1 without inventing values meant they had to become optional, filled in later once the
   paperwork is on hand.
9. **`is_primary_site` added to `hotels`** (resolved during Phase 2 build, not in the original
   plan) — the public marketing website still represents exactly one hotel, so tenant-scoped
   queries need a deterministic way to answer "which hotel is the public site" for unauthenticated
   visitors. See [DATABASE-SCHEMA.md § Tenant-scoping mechanism](DATABASE-SCHEMA.md#tenant-scoping-mechanism).
10. **`users` is not tenant-scoped by the same automatic mechanism as everything else** (resolved
    during Phase 2 build) — a global scope on the auth model itself risked recursive/inconsistent
    session resolution. `users.hotel_id` exists; filtering it is done explicitly per-query instead.
    Full rationale in [DATABASE-SCHEMA.md § Tenant-scoping mechanism](DATABASE-SCHEMA.md#tenant-scoping-mechanism).
11. **The route-model-binding guard planned for Phase 3 needed no new code** — Laravel's implicit
    route binding already calls `findOrFail()`, which already respects the tenant global scope.
    Generalizing that scope to a jurisdiction _set_ (district/upazila/police-station) was enough;
    a separate middleware/guard would have been redundant. Verified by
    `HotelTenantIsolationTest`, not assumed.
12. **404, not 403, for out-of-jurisdiction direct access** — a 403 would confirm a record exists
    at all, which is exactly the kind of leak this system shouldn't produce for guest data a user
    isn't authorized to know about.
13. **Hotel onboarding is Super-Admin-direct-create, no approval queue** (resolved during Phase 4
    build — answers the "Hotel onboarding flow" open question below). New hotels default straight
    to `status = active`; the 4-state status control exists for suspending/rejecting a hotel
    later, not for gating a self-registration flow that doesn't exist yet.
14. **Hotel-to-user assignment lives on the existing Users screen, not a new UI on the Hotel
    page** (resolved during Phase 4 build) — added a `hotel_id` select to
    `Admin/Users/{Create,Edit}.vue` instead of building a second, redundant staff-management
    surface under Hotel Registration. The Hotel Show page lists current staff read-only with a
    link back to Users.
15. **Full guest KYC lives on a new dedicated `Admin/Customers/Edit.vue`, not the booking form**
    (resolved during Phase 5 build) — bookings still capture only name/phone/email/nationality
    quickly at reservation time; the ~25-field PRD § 5.3 form is filled in separately, matching how
    hotels actually do compliance paperwork at physical check-in.
16. **Document upload handling extracted into a shared `App\Support\DocumentUploader`** (Phase 5)
    — Phase 4's `HotelController` had this logic inline; Phase 5's `CustomerController` needed the
    identical single-file-replace/multi-file-append shape, so it was pulled out once a second real
    caller existed rather than duplicated. Same for `App\Support\Geography` (district/upazila/
    police-station select options), used by both controllers.
17. **Government dashboard reuses the existing `admin.dashboard` route, branching on role
    `scope_type`** (resolved during Phase 6 build) — rather than a separate
    `GovernmentDashboardController`/route, since the `dashboard` module permission was already
    shared between hotel and government roles.
18. **One Guest report screen serves Guest-wise, Date-wise, and Foreign Guest reports**
    (resolved during Phase 6 build) — three PRD-named report types, one filterable
    `Admin/Government/GuestReport.vue`, with Date-wise/Foreign-Guest as sidebar links carrying
    preset query params rather than three near-duplicate screens.
19. **Known limitation, not a bug: the dashboard's Male/Female split is by primary registrant
    only.** The KYC data model (Phase 5) only captures gender for the primary guest on a booking
    line, not for every companion/adult sharing the room — there was never a requirement to
    register each occupant individually. The dashboard labels this explicitly rather than
    presenting a headcount-accurate number it can't actually produce from the data on hand.
20. **All `documents`-table files moved from the `public` disk to the protected `local` disk**
    (resolved during Phase 7's security pass) — they were reachable by anyone with the exact URL,
    no login required, before this. See
    [DATABASE-SCHEMA.md § Phase 7](DATABASE-SCHEMA.md#phase-7--security-hardening--audit-trail)
    for the full reasoning and what was deliberately left public (`hotels.logo`).
21. **Audit logging covers super-admin/government access only, not routine hotel-staff use**
    (Phase 7) — matches PRD § 6's actual concern (oversight access to guest PII across hotels a
    user doesn't operate), not a request to audit ordinary front-desk work.
22. **Real (non-test) government account seeding is a client go-live decision, not something to
    do unprompted** — the checklist for it is written in
    [GOVERNMENT-USER-GUIDE.md](GOVERNMENT-USER-GUIDE.md), but executing it (creating accounts for
    specific real DC/UNO/Police offices) waits for the client to say which offices go live first.

## Known gaps

- **Resolved in Phase 3**: the test suite used to run against the real dev database — now fixed.
  Root cause was two layers deep: `bootstrap/cache/config.php` was a stale cached config
  (2026-07-28) that made Laravel ignore `.env`/`.env.testing` entirely, _and_ there was no
  `.env.testing` to begin with. Fixed both (`php artisan config:clear` + a new `.env.testing`
  pointing at an isolated `hotel-beach-way-testing` MySQL database — sqlite doesn't work here,
  this project's migrations use MySQL-only raw SQL). `tests/Feature/HotelTenantIsolationTest.php`
  (10 tests) now guards tenant/jurisdiction isolation permanently, verified via real HTTP
  requests. **If you ever see `bootstrap/cache/config.php` reappear, remember `.env` changes
  silently stop taking effect until `config:clear` runs again** — this cost real debugging time
  once already.
- **No headless browser tool available in this dev environment** (no chromium-cli, no
  Playwright install) — every phase's UI (Phase 4's Hotel Registration screens, Phase 5's guest
  KYC form, Phase 6's Monitoring Reports/dashboard) has been verified through its backend
  (automated HTTP-level tests, real `Invoke-WebRequest`/hand-built-multipart sessions against a
  temporary `artisan serve`) rather than a rendered screenshot. This is the one form of
  verification never completed across all 7 build phases. If a session ever has real browser
  tooling, a visual click-through is worth doing — nothing has failed, it's just unverified
  visually. **Concretely worth a real click**: the webcam-vs-upload toggle on the guest photo
  field, the cascading district→upazila→police-station selects (Phase 5), and the Guest report's
  filter form (Phase 6) — exactly the kind of DOM-interaction bugs a backend test can't see.
- **Verify-live practice paid off repeatedly, not just once.** Every phase from 4 onward ran real
  HTTP requests against the real dev database, not just the automated suite, and it kept finding
  things the tests didn't: Phase 5 found Inertia's `forceFormData` sending checkbox booleans as
  the strings `"true"`/`"false"` (which Laravel's `boolean` rule rejects outright — see
  [DATABASE-SCHEMA.md § Phase 5](DATABASE-SCHEMA.md#phase-5--guest--customer-kyc-expansion));
  Phase 7 found the `documents`-table public-disk exposure (see
  [DATABASE-SCHEMA.md § Phase 7](DATABASE-SCHEMA.md#phase-7--security-hardening--audit-trail)), a
  stale duplicate `artisan serve` process silently intercepting requests, and two seeded
  government test accounts' passwords having drifted from their documented value. **Any future
  form mixing file uploads with checkbox/boolean fields should get a test using raw string
  `"true"`/`"false"` payloads, not just PHP-native booleans** — the native-bool shape doesn't
  reproduce what a browser actually sends once `forceFormData` is involved. **Before any future
  live-HTTP verification session, check `netstat -ano` for stale listeners on whatever port
  you're about to use** — a leftover process from an earlier session silently answering requests
  cost real debugging time in Phase 7.
- **Resolved in Phase 7**: all 4 seeded government test accounts (`dc.coxsbazar`, `dc.dhaka`,
  `uno.coxsbazarsadar`, `police.coxsbazarsadar`) have now been exercised via real login at some
  point across Phases 6–7 — dashboard, reports, NID search, exports, and document access all
  confirmed working end to end for at least one account per role.
- **Found and fixed in Phase 3, worth knowing about**: the Phase 2 backfill migration
  (`2026_08_01_100007`) assumed geography data was already seeded. A genuinely fresh
  `php artisan migrate` (no `--seed`) would have failed on this — fixed by having the migration
  seed its own dependency. If you ever see a `district_id cannot be null` error on a fresh
  install, check that this fix is still in place.
- **Booking flow had zero automated test coverage before Phase 7** — the app's original, core
  feature, untested through six phases of changes to the models it depends on. Fixed with
  `tests/Feature/BookingFlowRegressionTest.php`. Worth remembering as a general lesson: this
  project's pre-existing features didn't have a test suite to begin with, so "the tests still
  pass" was never a complete regression signal for them — only for what's been touched since
  Phase 2.

## Remaining open questions

None of these blocked building Phases 1–7, and nothing is waiting on them — but they're worth the
client's input before go-live (see [GOVERNMENT-USER-GUIDE.md](GOVERNMENT-USER-GUIDE.md) for the
go-live checklist itself):

- **Hotel category values** — implemented in Phase 4 as a **plain free-text field**
  (`Admin/Hotels/HotelForm.vue`), specifically to avoid guessing at a classification standard
  Bangladesh doesn't have one universal version of. Still open whether this should later become a
  fixed select (star rating 1–5, tier label, or whatever BPC/Tourism Board standard the client
  actually uses) — low-cost to change later since it's a single nullable string column either way.
- **Marriage/companion info** — MVP models this as a few nullable fields on the primary guest
  (spouse name, marriage date, one Nikahnama document). If government reporting later needs the
  _spouse_ to have their own independently searchable NID/identity record, that's a bigger
  `booking_guests` table — see [DATABASE-SCHEMA.md § customers](DATABASE-SCHEMA.md#customers-guests)
  for the upgrade path.
- **NID smart scan** — the R&D lists "Smart NID Scan" as a field. There is no public API for
  private businesses to verify against the Bangladesh Election Commission's NID database. Treated
  as manual entry + document photo upload for now; OCR/scan hardware integration is a later,
  separately-scoped enhancement.
