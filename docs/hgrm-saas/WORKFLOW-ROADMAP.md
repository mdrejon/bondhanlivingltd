# Workflow Roadmap — HGRM SaaS

Companion to [PRD.md](PRD.md) and [DATABASE-SCHEMA.md](DATABASE-SCHEMA.md). Work through phases
**in order** — each depends on the previous one being live. Check off tasks as they're done and
update the `Status:` line per phase. This file is the single source of truth for "where are we."

Legend: `Status: Not Started | In Progress | Blocked | Done`

---

## Phase 0 — Decisions & sign-off

**Status:** In Progress — proceeded with documented defaults to unblock Phase 1; not yet formally
signed off by the client.
**Depends on:** nothing
**Goal:** lock the architecture before any migration exists, so Phase 1+ isn't rework.

- [ ] Client/stakeholder reads [PRD.md](PRD.md) and [README.md](README.md) decisions list.
- [ ] Confirm or revise the 5 decisions already baked into the docs (tenancy model, jurisdiction
      auto-scope, per-hotel guests, Hotel Beach Way as Tenant #1, reuse existing Admin shell).
- [ ] Answer the open questions in README.md: hotel category values, marriage/companion MVP
      scope, hotel onboarding flow (self-serve vs. admin-created), NID scan approach.
- [x] Decide whether `divisions` table is in scope for Phase 1 or deferred — **decided: in
      scope**, built in Phase 1 (see README.md decision #6).

**Definition of done:** no open architecture question remains that would change a table shape in
Phase 1–3. Phase 1's table shapes are now built, so any change to decisions 1–5 above would mean
revisiting Phase 2's design before it starts — worth confirming with the client now rather than
after Phase 2.

---

## Phase 1 — Bangladesh geography reference data

**Status:** Done (2026-08-01)
**Depends on:** Phase 0
**Goal:** districts/upazilas/police stations exist and are seeded, ready for `hotels` and
`users` to reference them.

- [x] Source a verified Bangladesh administrative dataset — used
      [nuhil/bangladesh-geocode](https://github.com/nuhil/bangladesh-geocode) (cites
      bangladesh.gov.bd + Wikipedia; source `id`s preserved on import so e.g. district id 9 is
      reliably Cox's Bazar in every environment).
- [x] Migration: `divisions` table — `2026_08_01_100001_create_divisions_table.php`. Kept in
      scope (Phase 0 decision resolved: cheap now, expensive to retrofit).
- [x] Migration: `districts` table (+ `division_id` FK) — `2026_08_01_100002_create_districts_table.php`.
- [x] Migration: `upazilas` table (`district_id` FK) — `2026_08_01_100003_create_upazilas_table.php`.
- [x] Migration: `police_stations` table (`district_id` FK, nullable `upazila_id` FK) —
      `2026_08_01_100004_create_police_stations_table.php`.
- [x] Models: `Division`, `District`, `Upazila`, `PoliceStation` (`app/Models/`) with
      `hasMany`/`belongsTo` relations.
- [x] Seeder: `Database\Seeders\BdGeographySeeder`, idempotent (skips per-table if already
      populated), reads JSON snapshots from `database/seeders/data/bd-geocode/`, wired into
      `DatabaseSeeder`.
- [x] Spot-checked via `artisan tinker`: 8 divisions, 64 districts, 494 upazilas seeded; Cox's
      Bazar (district id 9) correctly resolves to Chattagram division and its 9 real upazilas
      (Sadar, Chakaria, Kutubdia, Ukhiya, Moheshkhali, Pekua, Ramu, Teknaf, Eidgaon).
- [ ] **Follow-up, not blocking**: `police_stations` currently holds a rural approximation only
      (one row per upazila, name mirrored — correct for the large majority of the country,
      including Cox's Bazar). Metropolitan police stations (Dhaka Metropolitan Police ~50 thanas,
      plus Chattogram/Rajshahi/Khulna/Sylhet/Barishal/Rangpur/Gazipur/Narayanganj Metropolitan
      Police) are **not seeded** — no verified open dataset was found for these. Needed before a
      hotel located in one of those metro areas can be registered with a correct police station.
      Source an official list (e.g. from the relevant Metropolitan Police website) and extend
      `BdGeographySeeder` before Phase 4 onboards a metro-area hotel.

**Definition of done:** `php artisan migrate --seed` produces a complete, queryable geography
tree; no admin UI needed yet (plain Eloquent + `artisan tinker` verification is enough). ✅ Met —
the one open follow-up (metro police stations) doesn't block Phase 2/3 since neither `hotels` nor
`users` require police-station data to exist for districts that don't have metro areas.

---

## Phase 2 — Hotels & multi-tenancy core

**Status:** Done (2026-08-01)
**Depends on:** Phase 1
**Goal:** the system has a real concept of "hotel," every existing tenant-owned table is scoped
to one, and Hotel Beach Way's existing data is preserved as Tenant #1 with zero functional
regression.

- [x] Migration: `hotels` table — `2026_08_01_100005_create_hotels_table.php`. Legal-ID fields
      (`trade_license_no`, `owner_name`, `owner_nid`) made nullable and `is_primary_site` added
      during the build — both are real deviations from the original plan, documented in
      [DATABASE-SCHEMA.md § Phase 2](DATABASE-SCHEMA.md#phase-2--hotels--multi-tenancy-core) and
      [README.md decisions 8–9](README.md#decisions-already-made-baked-into-the-docs-below).
- [x] Migration: `documents` polymorphic table — `2026_08_01_100006_create_documents_table.php`.
      Table exists; nothing writes to it yet (starts being used in Phase 4/5).
- [x] Model: `Hotel` (+ relations: `district`, `upazila`, `policeStation`, `roomTypes`, `rooms`,
      `customers`, `bookings`, `inquiries`, `users`, `documents`; static `primarySite()` helper).
- [x] Model: `Document` (+ `documentable()` morphTo, `uploadedBy()`).
- [x] Migration: additive nullable `hotel_id` on `room_types`, `rooms`, `customers`,
      `room_bookings`, `inquiries`, `users` — `2026_08_01_100007_add_hotel_id_to_tenant_tables.php`.
      Also fixed `customers.phone` from a global unique constraint to `unique(hotel_id, phone)` in
      the same migration — required once guests became per-hotel, see schema doc.
- [x] Backfill: done inside the same `2026_08_01_100007` migration (not a separate script — it's
      a one-off historical operation, not something a fresh install repeats). Hotel Beach Way's
      real district/upazila/police station resolved from Phase 1 data, real mobile/email/address
      pulled from `global_settings`; every existing `room_types` (8), `rooms` (58), `customers`
      (14), `room_bookings` (2), `inquiries` (32), `users` (3) row backfilled onto it. Verified via
      `artisan tinker` — counts match exactly.
- [x] Follow-up migration: `2026_08_01_100008_make_hotel_id_required_on_tenant_tables.php` — made
      `hotel_id` `NOT NULL` on `room_types`, `rooms`, `customers`, `room_bookings`, `inquiries`
      (raw `MODIFY COLUMN`, matching this project's existing no-doctrine/dbal convention).
      `users.hotel_id` left nullable as planned.
- [x] Added `hotel()` relation + `hotel_id` to `$fillable` on `RoomType`, `Room`, `Customer`,
      `RoomBooking`, `Inquiry`, and `User`.
- [x] Built the tenant-scoping mechanism — **differs from the original plan, see
      [DATABASE-SCHEMA.md § Tenant-scoping mechanism](DATABASE-SCHEMA.md#tenant-scoping-mechanism)
      for the full reasoning**:
    - `App\Support\CurrentHotel` resolves the scope id (super admin → unrestricted; hotel staff →
      their own hotel; **unauthenticated public site/console/queue → the hotel flagged
      `is_primary_site`**, not just "no scoping" as originally sketched — this was the missing
      piece that lets the public frontend keep working unchanged).
    - `App\Models\Concerns\BelongsToHotel` trait applies the global scope + auto-stamps `hotel_id`
      on create. Applied to `RoomType`, `Room`, `Customer`, `RoomBooking`, `Inquiry`.
    - **`User` deliberately excluded** from the trait (auth-guard recursion risk discovered during
      implementation) — gets the column/relation only, scoped manually per-query when needed.
- [x] Audited every Admin/Frontend controller for raw `DB::table`/`DB::select`/`join` queries
      against `room_types`, `rooms`, `customers`, `room_bookings`, `inquiries` — none found
      (`ReportController`, `DashboardController`, `RoomAvailabilityController` all go through
      Eloquent), so the global scope's coverage is complete.
- [x] Regression check: **not** a live browser click-through — verified instead via `artisan
    tinker` inside a rolled-back DB transaction (created a throwaway second hotel + room type +
      hotel-scoped user, confirmed cross-tenant isolation, auto-stamping, and super-admin
      unrestricted access, then rolled back with zero persisted test data). Super admin's counts
      matched pre-existing totals exactly (8 room types, 58 rooms, etc.), confirming zero
      behavior change for Hotel Beach Way staff. A live browser smoke-check of the admin panel
      hasn't been done — worth doing before treating this as fully production-verified.
- [ ] **Not done — flagged, not silently skipped**: a permanent automated PHPUnit test for tenant
      isolation. While verifying this manually, discovered `php artisan test` /
      `vendor/bin/phpunit` currently runs `RefreshDatabase` migrations against the **real MySQL
      dev database** (no `.env.testing`, sqlite lines commented out in `phpunit.xml`) — running
      the existing test suite right now would wipe real data. Also, sqlite isn't actually a safe
      fix here: this migration set (including two from this phase) uses MySQL-only raw
      `ALTER TABLE ... MODIFY COLUMN` statements sqlite can't run. See
      [README.md § Known gaps](README.md#known-gaps-to-close-before-relying-on-this-in-production).
      **Carried forward to Phase 3**, which is where the first _required_ automated isolation
      tests show up — fixing the test DB config is a prerequisite for that task.

**Definition of done:** Hotel Beach Way operates exactly as before; the schema is ready for a
second hotel to be added without any code change. ✅ Met, with two honest caveats carried forward
rather than hidden: no live browser regression pass yet, and no permanent automated test yet
(blocked on the test-database fix above).

---

## Phase 3 — RBAC & jurisdiction

**Status:** Done (2026-08-01)
**Depends on:** Phase 2
**Goal:** government role types exist and automatically see the right hotels, with no way to
escalate scope by guessing a URL.

- [x] **Prerequisite carried over from Phase 2**: fixed the test database setup. Root cause was
      actually one step earlier than expected — `bootstrap/cache/config.php` was a **stale cached
      config** (from 2026-07-28), so Laravel was ignoring `.env` entirely and running everything
      against frozen values, which is also why `.env.testing` alone wouldn't have been enough.
      Ran `php artisan config:clear` (safe — deletes a cache file, no data impact) and added
      `.env.testing` pointing at a dedicated `hotel-beach-way-testing` MySQL database (not
      sqlite — confirmed this project's migrations use MySQL-only raw `ALTER TABLE ... MODIFY
    COLUMN` statements sqlite can't run). Verified end-to-end by snapshotting real-DB row
      counts, running the full `RefreshDatabase` suite, and re-checking the real DB was
      byte-for-byte unchanged afterward.
- [x] **Bug found and fixed while doing the above**: `2026_08_01_100007_add_hotel_id_to_tenant_tables.php`
      assumed `BdGeographySeeder` had already run. On a genuinely fresh database — which is
      exactly what `RefreshDatabase` does, and what a first-time `php artisan migrate` on a new
      clone would also do — districts/upazilas/police_stations are empty, so the district/
      police-station lookup resolved to `null` and the `hotels` insert failed its `NOT NULL`
      constraint. Fixed by having the migration call `BdGeographySeeder` itself (idempotent, so
      free if it already ran) before doing the lookup — makes the migration chain self-sufficient
      on `php artisan migrate` alone, no `--seed` flag required. This was a real production-
      readiness bug in Phase 2's migration, not a test-only artifact — worth knowing if you ever
      set up a fresh environment from these migrations.
- [x] Migration: `roles.scope_type` enum column — `2026_08_01_100009_add_scope_type_to_roles_table.php`.
- [x] Migration: `users.district_id`, `users.upazila_id`, `users.police_station_id` (nullable
      FKs) — `2026_08_01_100010_add_jurisdiction_to_users_table.php`.
- [x] Migration: `hotel_user_assignments` pivot table — `2026_08_01_100011_create_hotel_user_assignments_table.php`.
- [x] Added `hotel-registration` and `gov-reports` to `ModuleRegistry::all()` + preemptive
      `resolveModule()` entries (`admin.hotels`, `admin.government`) ahead of the Phase 4/6 routes
      that will use them — lets the existing Roles UI already grant these modules today.
- [x] Built `App\Support\HotelAccess::visibleHotelIds(User $user): array` — checks
      `hotel_user_assignments` first (exact override), falls back to jurisdiction match on
      `district_id`/`upazila_id`/`police_station_id` per the role's `scope_type`. Wired into
      `App\Support\CurrentHotel::visibleHotelIds()`, which is what
      `App\Models\Concerns\BelongsToHotel`'s global scope now calls — **generalized from a single
      hotel_id to a `whereIn` over a set**, so Phase 2's exact same mechanism now serves both
      "one hotel" (hotel staff) and "every hotel in my jurisdiction" (government roles) without
      two separate code paths.
- [x] **Route-model-binding guard — turned out to need no new code.** Investigated before
      building anything: Laravel's implicit route-model binding (e.g. `CustomerController::
    show(Customer $customer)`) already calls `Customer::findOrFail()`, which already respects
      the global scope. Once that scope understood jurisdiction sets (previous bullet), a
      government/hotel user hitting `/admin/customers/{id}` for an out-of-scope id already gets a
      404 automatically — proven by `HotelTenantIsolationTest`, not assumed. Deliberately 404 (not
      403): a 403 would confirm the record exists at all, which is worse for a system whose whole
      point is restricting who can even know a guest stayed somewhere.
- [x] Seeder: `Database\Seeders\GovernmentRoleSeeder` — `DC Office` (`scope_type=district`),
      `UNO Office` (`scope_type=upazila`), `Police Admin` (`scope_type=police_station`), each
      granted `dashboard` + `gov-reports` + `customers` (view-only). Also sets the existing
      `super-admin` role's `scope_type` to `platform` for hygiene (behavior is unaffected either
      way — `is_super_admin` is still the unconditional bypass). Wired into `DatabaseSeeder`
      (after `RoleSeeder` and `BdGeographySeeder`, which it depends on).
- [x] Seeded 4 test government accounts across 2 districts (dev/QA only — password `password`
      for all): `dc.coxsbazar@hgrm.test` (DC, Cox's Bazar), `dc.dhaka@hgrm.test` (DC, Dhaka),
      `uno.coxsbazarsadar@hgrm.test` (UNO, Coxsbazar Sadar upazila),
      `police.coxsbazarsadar@hgrm.test` (Police, Coxsbazar Sadar thana).
- [x] **Automated isolation tests**: `tests/Feature/HotelTenantIsolationTest.php`, 10 tests
      covering hotel-role isolation (including the "misconfigured user with no hotel sees
      nothing" fail-closed case), district/upazila/police-station jurisdiction scoping, the
      explicit-assignment-overrides-jurisdiction semantic, super-admin unrestricted access, and
      unauthenticated public-site scoping — via real HTTP requests
      (`$this->actingAs($user)->get(route('admin.customers.show', $customer))->assertNotFound()`),
      not just Eloquent queries. All 10 pass; full suite (35 tests) passes; real dev database
      confirmed unchanged after the run.
- [ ] **Not done — live browser login QA.** The 4 seeded accounts above are ready for a manual
      login smoke-check whenever convenient; this session verified correctness via the automated
      suite only, not a real browser session.

**Definition of done:** seeded DC/UNO/Police test accounts each see exactly their jurisdiction's
hotels and nothing else, verified by both manual login and automated tests. ✅ Automated half
solidly met (10 passing isolation tests); manual login QA carried forward as a quick, low-risk
follow-up rather than blocking here.

---

## Phase 4 — Hotel registration module (Super Admin side)

**Status:** Done (2026-08-01)
**Depends on:** Phase 3 (needs `hotel-registration` module permission wired up)
**Goal:** Super Admin can onboard a second real hotel end-to-end through the UI — the point
where multi-tenancy stops being theoretical.

- [x] `Admin\HotelController`: index/create/store/edit/update/show/updateStatus/deleteDocument,
      gated by `hotel-registration` module permission (already wired via `ModuleRegistry` in
      Phase 3).
- [x] Create/Edit form (shared `Admin/Hotels/HotelForm.vue`): every PRD § 5.1 field — name,
      category (plain text — no fixed classification exists yet, see README open question),
      total rooms, trade license/BIN/TIN, owner name/NID, mobile/email/address,
      district→upazila→police-station cascading selects (client-side filtering over all
      districts/upazilas/police stations passed as props — no extra round-trips), a single hotel
      photo (logo), and 4 single-file document uploads (trade license/BIN/TIN/owner NID) +
      multi-file gallery photos, all via the `documents` polymorphic table from Phase 2 — its
      first real use.
- [x] Inertia pages: `Admin/Hotels/{Index,Create,Edit,Show}.vue` + shared `HotelForm.vue`,
      following the Rooms/RoomTypes page conventions (DropZone, `useForm`, table + modal
      patterns).
- [x] Hotel status control: 4-state changer (pending/active/suspended/rejected) on the Show page,
      with a JS `confirm()` gate before suspending or rejecting.
- [x] Sidebar entry added (`Hotel Registration`, module `hotel-registration`) — naturally
      Super-Admin-only today since no other role has been granted that module.
- [x] **Added beyond the original plan**: a `hotel_id` select on the existing Users Create/Edit
      forms (`Admin\UserController` + `Admin/Users/{Create,Edit}.vue`), plus a "Hotel" column on
      the Users index. This is _how_ "assign a hotel-admin user" actually happens — the original
      plan didn't specify where that UI would live; reusing the existing Users screen avoided
      building a second, redundant user-management surface on the Hotel page itself. Also created
      a `Hotel Admin` role (scope_type `hotel`, granted dashboard/bookings/room-management/
      customers/inquiries/reports) as the first real non-super-admin hotel-role role.
- [x] **Created a second real hotel and proved isolation live**, not just via automated tests:
      "Grand Dhaka Hotel" (id 3, Dhaka district) now exists in the real dev database alongside
      Hotel Beach Way, with a real "Grand Dhaka Hotel Admin" user (`Hotel Admin` role,
      `hotel_id = 3`). No browser automation tool was available in this environment (no
      chromium-cli/Playwright), so this was done via genuine HTTP requests (PowerShell
      `Invoke-WebRequest` against a temporary `php artisan serve` instance, with real CSRF/session
      cookie handling — not tinker/Eloquent shortcuts) through the actual login → create-hotel →
      create-user → assign-role flow. **Confirmed live**: the new hotel admin's dashboard loads
      (200), a direct request for Hotel Beach Way's real customer record returns **404**, and
      requesting the Hotel Registration screen (a module they weren't granted) redirects them
      away (302) — the exact three checks the Phase 3 automated suite makes, now reproduced
      against real production-adjacent data. The scripting-only temporary super-admin account
      used to drive the HTTP flow was deleted afterward; the real hotel and its real admin user
      were kept.
- [x] Backend also covered by 7 new automated tests, `tests/Feature/HotelRegistrationTest.php`
      (permission gating, file upload + replacement + deletion, duplicate trade-license
      rejection, status changes, and the assign-then-isolate flow through the real
      `UserController::store` endpoint). Full suite: 42 tests passing.
- [ ] **Not done — live visual browser check.** No headless browser tool (chromium-cli,
      Playwright) was available in this environment to click through the actual rendered Vue UI
      (cascading selects behaving correctly in the DOM, DropZone previews, modal interactions).
      The backend behind every one of those interactions is verified (above); the UI code
      compiles cleanly (`npm run build` succeeded, `HotelForm-*.js` present in the build output).
      Worth a quick manual look next time this is opened in an actual browser.

**Definition of done:** two independently operating hotels exist in the system, each manageable
only by their own staff, both visible to Super Admin. ✅ Met and verified live — see above.

---

## Phase 5 — Guest / customer KYC expansion

**Status:** Done (2026-08-02)
**Depends on:** Phase 2 (needs `hotel_id` on `customers`); independent of Phases 3–4
**Goal:** the full guest KYC dataset from PRD § 5.3 is capturable and stored per hotel.

- [x] Migration: new `customers` columns — `2026_08_02_100001_add_kyc_fields_to_customers_table.php`
      (father/mother name, gender, DOB, occupation, emergency contact, present/permanent address,
      district/upazila/police-station, post code, birth certificate number, driving license
      number, foreign-guest fields, couple/marriage fields, `is_flagged`/`flagged_note`).
- [x] Updated `Customer` model `$fillable`/casts (date + boolean casts for the new columns);
      added `district()`/`upazila()`/`policeStation()`/`documents()` relations.
- [x] **Decision made during implementation — KYC completion lives on a new
      `Admin/Customers/Edit.vue`, not the booking-creation form.** The roadmap left this open
      ("wherever guest details are captured"). Bookings still capture only
      name/phone/email/nationality/document quickly at reservation time (unchanged,
      `RoomBookingController::resolveCustomer()` untouched); the full ~25-field PRD § 5.3 KYC
      form is a dedicated front-desk registration screen, matching how hotels actually complete
      compliance paperwork at physical check-in rather than at the moment of reservation. Sections
      match PRD § 5.3 exactly: Personal / Identity / Foreign Guest / Marriage / Photo / Documents.
- [x] Conditional required-field validation via `required_if`: `visa_number`/`arrival_date_bd`
      required when `is_foreign_guest`; `spouse_name` required when `is_couple`.
- [x] Document uploads wired to the `documents` table with exactly the categories from the schema
      doc (`nid_front`, `nid_back`, `passport_scan`, `visa`, `marriage_certificate`, `guest_photo`,
      `other`) via a new shared `App\Support\DocumentUploader` (extracted from Phase 4's
      `HotelController` — same single-file-replace / multi-file-append logic, now used by both
      controllers instead of duplicated). Storage path guessability confirmed the same way as
      Phase 4 (random hashed filenames, no directory listing).
- [x] Guest photo: new shared `WebcamCapture.vue` component (`getUserMedia` + canvas snapshot →
      `File`) as one path, existing `DropZone` upload as the other — a toggle switches between
      them, both feeding the same `guest_photo_doc` form field/`documents` category so the backend
      doesn't care which path was used.
- [x] Police-only `is_flagged`/`flagged_note`: `User::canManagePoliceFlag()` (super admin or
      `scope_type = police_station`) gates this in `Admin\CustomerController` — the fields are
      stripped from the Inertia response via `makeHidden()` for unauthorized roles (not just
      hidden in Vue) _and_ silently ignored on write if a non-police request includes them.
      Verified by `CustomerKycTest` asserting the keys are actually absent from the response
      props, not just unrendered.
- [x] **Also done (not separately called out in the original checklist, but required for the
      Roles UI to make any of this grantable)**: added `edit`/`delete` to the `customers` module's
      allowed actions in `ModuleRegistry` — it was `['view']`-only before, which meant no role
      (other than super admin) could ever have been granted permission to edit a guest record
      even after this screen existed.
- [x] **Real bug found and fixed while manually verifying over live HTTP** (not caught by the
      first round of automated tests): Inertia's `forceFormData` — required here because the form
      has file uploads — serializes JS checkbox booleans through `FormData` as the literal strings
      `"true"`/`"false"`, and Laravel's `boolean` validation rule actually **rejects** the string
      `"false"` outright. Every real update silently failed validation whenever a checkbox was
      left unchecked. Fixed by normalizing with `$request->boolean()` before validation; added a
      dedicated regression test (`test_update_succeeds_with_string_boolean_values_matching_real_
    form_submissions`) using raw string values specifically, since the other tests used
      PHP-native booleans and would never have caught this. Re-verified live afterward against the
      real dev database.
- [x] Automated tests: `tests/Feature/CustomerKycTest.php`, 8 tests (conditional validation,
      document category correctness, the police-flag gating in both directions, and the
      boolean-string regression above). Full suite: 50 tests passing.
- [x] **Verified live** against the real dev database (same pattern as Phase 4 — genuine HTTP
      requests via a temporary `artisan serve`, reusing the real "Grand Dhaka Hotel Admin" account
      from Phase 4 rather than creating a new throwaway account): opened the edit page for a real
      guest at Grand Dhaka Hotel (200), confirmed a Hotel Beach Way guest's edit page 404s for this
      user (cross-tenant isolation still holds for the new routes), and successfully updated the
      real guest record end to end.
- [ ] **Not done — live visual browser check**, same recurring caveat as Phase 4: no headless
      browser tool available in this environment. `npm run build` succeeds; the webcam-vs-upload
      toggle and cascading location selects are unverified visually.

**Definition of done:** a front-desk user can capture a complete guest record matching every
field in PRD § 5.3, for both domestic and foreign guests, with documents attached. ✅ Met and
verified live.

---

## Phase 6 — Monitoring Reports & dashboards

**Status:** Done (2026-08-02)
**Depends on:** Phases 3 (jurisdiction scoping) and 5 (KYC fields to report on)
**Goal:** every report/dashboard listed in PRD § 5.4–5.5 works, correctly scoped, exportable.

- [x] **Decision made during implementation — extended `DashboardController`, not a new
      controller.** `DashboardController::index()` now branches on the current user's role
      `scope_type`: district/upazila/police_station renders the new government dashboard;
      everyone else (hotel roles, super admin) sees the existing hotel-operations dashboard
      unchanged. Same route (`admin.dashboard`), same `dashboard` module permission (already
      granted to government roles by `GovernmentRoleSeeder`) — no new route needed.
- [x] New `App\Support\GovernmentStats` — shared occupancy aggregation (per-hotel current
      guests/today's check-in/check-out, 30-day trend) used by _both_ the government dashboard
      and the standalone Hotel-wise report, so that number is computed in exactly one place.
      Relies entirely on the existing tenant/jurisdiction global scope for correctness (see
      note below) — no manual `hotel_id IN (...)` filtering anywhere in this class.
- [x] `Admin\GovernmentReportController`: Hotel-wise report, Guest report (also serves
      **Date-wise** and **Foreign Guest** via query-param filters/preset sidebar links rather
      than 3 separate near-identical screens), Nationality report, NID search — all
      jurisdiction-scoped.
- [x] PDF export via existing `barryvdh/laravel-dompdf`, new `admin.government.pdf` blade view
      (couldn't reuse `admin.reports.pdf` — it hardcodes "Hotel Beach Way" in the title, wrong
      for a report spanning multiple hotels).
- [x] Excel/CSV export: plain streamed CSV, no new dependency, as planned.
- [x] Inertia pages: `Admin/Government/{Dashboard,HotelReport,GuestReport,Nationality,
    NidSearch}.vue` + shared `ExportLinks.vue` component.
- [x] Sidebar entries under a new "Monitoring Reports" section, gated by `gov-reports` — added
      query-param support (`route(name, query)`) to `SidebarNavItem.vue`, which didn't exist
      before and would have silently dropped the Date-wise/Foreign-Guest preset filters.
- [x] Police-only flagged-guest filter on the Guest report, gated by the same
      `User::canManagePoliceFlag()` from Phase 5.
- [x] **Correctness note worth keeping**: `Customer` carries the tenant global scope directly,
      so `Customer::` queries (nationality report, NID search) are automatically scoped with no
      extra code. `BookingRoom` does **not** carry it (by Phase 2 design — it inherits scope via
      its parent booking); every query against it here goes through
      `whereHas('booking', ...)` or eager-loads `booking`, and since `RoomBooking` _is_ scoped,
      the subquery/join is what actually restricts results. Get this pattern wrong (e.g. filter
      `BookingRoom` directly without touching `booking`) and jurisdiction scoping silently stops
      applying — automated tests exist specifically to catch that class of regression.
- [x] Automated tests: `tests/Feature/GovernmentReportsTest.php`, 8 tests — jurisdiction scoping
      on the hotel report, guest report, and NID search; confirms government roles are actually
      blocked from `admin.reports.*` (hotel financials); confirms the dashboard branch picks the
      right component for government vs. super-admin accounts. Full suite: 58 tests passing.
- [x] **Verified live** against the real dev database (same practice as Phases 4–5): logged in
      as the real seeded `dc.coxsbazar@hgrm.test` account, confirmed the dashboard renders the
      government component with correctly scoped stats, the Hotel-wise report shows Hotel Beach
      Way but not Grand Dhaka Hotel (Dhaka — different jurisdiction), `admin.reports.income`
      redirects away, NID search finds an in-jurisdiction guest but not Grand Dhaka Hotel's, and
      both CSV and PDF export endpoints return real, correctly-scoped output (verified byte
      count/content-type, not just status code).
- [ ] **Not done — live visual browser check**, same recurring caveat as Phases 4–5.

**Definition of done:** every report type in PRD § 5.4 renders correct, jurisdiction-scoped data
and exports to both PDF and CSV. ✅ Met and verified live.

---

## Phase 7 — Isolation testing, audit logging, rollout

**Status:** Done (2026-08-02)
**Depends on:** Phases 1–6 complete
**Goal:** confidence this is safe to hand to real government offices, plus the compliance
audit trail expected of that positioning.

- [x] Full regression pass on Hotel Beach Way's original functionality. **Found a real gap while
      doing this**: the booking flow — the app's original, core feature — had _zero_ automated
      test coverage before this, despite six phases of changes touching every model it depends on
      (`Customer`/`RoomBooking`/`BookingRoom` all gained `hotel_id` + global scopes). Added
      `tests/Feature/BookingFlowRegressionTest.php` (public availability check, public booking
      creation, admin manual booking creation, all with `Mail::fake()` — this environment's mail
      config routes to a real SMTP server even from `tinker`/local testing, so any booking-flow
      check that skips `Mail::fake()` risks sending real email to real inboxes) rather than just
      asserting nothing broke. Also caught and fixed two Phase 4/5 tests that still referenced the `public`
      disk after this phase's document-storage change (see below) — `HotelRegistrationTest` and
      `CustomerKycTest` — and cleaned up real orphaned files those tests had written to
      `storage/app/` before the fix.
- [x] Expanded Phase 3/6 isolation tests: a hotel-role account has zero permission on any
      `admin.government.*` route (added to `GovernmentReportsTest`); a government user cannot use
      the Guest report's `?hotel_id=` filter to see another jurisdiction's data (the filter ANDs
      onto an already-scoped query, so this was safe by construction — now proven, not assumed).
- [x] Migration + model: `audit_logs`, `App\Models\AuditLog`, and a new `App\Support\AuditLogger`
      helper — logs only super-admin/government-scoped access (`viewed_guest`,
      `viewed_guest_document`, `searched_nid`, `exported_report`), deliberately not routine
      hotel-staff activity. Wired into `CustomerController::show/edit`, `DocumentController::show`
      (guest documents only), and `GovernmentReportController`'s NID search and both export
      methods.
- [x] **Security pass found a real gap, not just confirmed one was absent.** The `documents`
      table (Hotel legal docs + all guest KYC documents from Phases 4–5) was stored on the
      `public` disk — served directly by the webserver with **zero authentication**. Random
      hashed filenames made paths hard to _guess_, which is not the same as "not publicly
      reachable without auth." Fixed by moving all `documents`-table storage to the `local` disk
      (`storage_path('app')`, not web-accessible) and adding `Admin\DocumentController::show`,
      which re-checks module permission _and_ tenant/jurisdiction scope (via `Customer::find()`
      respecting the same global scope everything else relies on) on every request. Confirmed
      live: the old direct path is unreachable, an in-jurisdiction government account gets the
      file, an out-of-jurisdiction one gets 404, unauthenticated gets redirected to login. Hotel
      `logo` and future public hotel marketing photos were deliberately left on the `public` disk
      — see [DATABASE-SCHEMA.md § Phase 7](DATABASE-SCHEMA.md#phase-7--security-hardening--audit-trail)
      for why that split is fine. Confirmed government accounts still can't reach `admin.reports.*`
      (unchanged from Phase 6).
- [x] `docs/hgrm-saas/GOVERNMENT-USER-GUIDE.md` — what each role sees day to day, plus the
      go-live checklist for turning a seeded test government account into a real one.
- [x] Go-live checklist written (in the guide above) rather than executed — seeding _real_ DC/UNO/
      Police accounts for specific real offices is a client rollout decision, not something to do
      unprompted. The 4 Phase 3 test accounts remain clearly test-only (`@hgrm.test` emails, shared
      password) and are documented as not-for-production in the guide.
- [x] Automated tests: `tests/Feature/DocumentSecurityTest.php` (8 tests — auth required, tenant/
      jurisdiction scoping on document access, hotel-registration permission gating, audit logging
      fires for government/platform accounts and not for routine staff), plus the isolation/
      regression additions above. Full suite: **71 tests passing**.
- [x] **Verified live** against the real dev database (same practice as every prior phase):
      uploaded a real trade-license PDF to the real Grand Dhaka Hotel and a real NID scan to the
      real guest "Rafiq Ahmed," confirmed the files landed under `storage/app/` (not
      `public/storage/`), confirmed `dc.dhaka@hgrm.test` (in-jurisdiction) can view the NID scan
      and `dc.coxsbazar@hgrm.test` (out-of-jurisdiction) gets 404, and confirmed a real
      `audit_logs` row was written with the correct category metadata. **Also found and fixed a
      real environment issue while doing this**: a stale duplicate `artisan serve` process left
      over from earlier verification sessions was silently intercepting some requests, and two of
      the four seeded government test accounts' passwords had drifted from the documented
      `password` value at some point in this session — both fixed (killed the stale process; reset
      the two accounts via `tinker`, matching `GovernmentRoleSeeder`'s intended state).

**Definition of done:** isolation is verified by tests (not just manual spot-checks), every
sensitive-data access is logged, and real government accounts are seeded and confirmed working.
✅ Isolation and audit logging fully met and verified live. Real (non-test) government account
seeding is deliberately left as a client go-live decision — the checklist for it is written and
ready in [GOVERNMENT-USER-GUIDE.md](GOVERNMENT-USER-GUIDE.md).
