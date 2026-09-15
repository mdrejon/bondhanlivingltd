# Government User Guide & Go-Live Checklist

Companion to [PRD.md](PRD.md) (§ 5.4–5.5) and [WORKFLOW-ROADMAP.md](WORKFLOW-ROADMAP.md) Phase 7.
Two things in one short doc: what a DC/UNO/Police account actually sees day to day, and the
checklist for turning a _test_ government account into a _real_ one for a live office.

## Logging in

Same login page as hotel staff — there is no separate government portal. Go to the admin login
URL and sign in with the email/password given to you by the platform administrator. What you see
after logging in depends entirely on your account, not on how you got there.

## What you'll see

- **Dashboard** (first thing after login): today's check-ins, today's check-outs, how many guests
  are currently staying, how many are foreign nationals, a male/female breakdown (based on the
  primary guest registered per room — companions aren't separately tracked), and a hotel-by-hotel
  and district/upazila breakdown, all limited to your jurisdiction.
- **Monitoring Reports** (sidebar):
    - **Hotel-wise Report** — every hotel in your jurisdiction with its current guest count and
      today's check-in/check-out counts.
    - **Guest Report** — the full guest list (name, photo, NID, mobile, room, check-in/check-out,
      status) with filters for hotel, date, and foreign-guest-only.
    - **Date-wise Report** / **Foreign Guest Report** — the same Guest Report screen, opened with
      that filter already applied.
    - **Nationality Report** — guest counts grouped by nationality.
    - **NID Search** — find a guest by (full or partial) NID number, across every hotel in your
      jurisdiction.
- **Customers** (if granted): the same guest list/detail screens hotel staff use, but you'll only
  ever see guests who stayed at a hotel inside your jurisdiction.
- **Police Admin only**: a "flag as suspicious person" control on each guest's record, and a
  "flagged only" filter on the Guest Report. No other role can see or set this.

## What you will _not_ see

- Hotel financial data (income, discounts, room rates) — that's a separate `reports` permission
  government accounts are never granted.
- Hotels or guests outside your jurisdiction. Not "hidden in the menu" — the system cannot return
  that data to your account even via a direct link, and doing so is covered by automated tests
  (`tests/Feature/GovernmentReportsTest.php`, `tests/Feature/HotelTenantIsolationTest.php`).
- The Hotel Registration screen (creating/editing hotels) — platform-admin only.

## Exporting

Every report has "Export PDF" and "Export CSV" links at the top — both respect whatever filters
are currently applied and are scoped to your jurisdiction the same way the on-screen table is.
Every export is recorded in the audit trail (see below).

## Compliance / audit trail

Because this system gives your account cross-hotel visibility into citizen NID/passport/personal
data, every guest-record view, document view, NID search, and report export by a government or
platform account is logged (`audit_logs` table — who, what, when). Routine hotel staff using their
own hotel's data is not logged; oversight access is. This isn't visible in the UI today — ask the
platform administrator if you need a copy of your jurisdiction's access history.

---

## Go-live checklist (for the platform administrator)

The 4 accounts seeded by `Database\Seeders\GovernmentRoleSeeder`
(`dc.coxsbazar@hgrm.test`, `dc.dhaka@hgrm.test`, `uno.coxsbazarsadar@hgrm.test`,
`police.coxsbazarsadar@hgrm.test`, all password `password`) are **for development/QA only** —
predictable emails, a shared password, and jurisdictions picked to make testing easy, not because
those are the real offices going live first. Before handing an account to a real DC/UNO/Police
office:

- [ ] Create a real `User` with that office's real email — via the admin Users screen (`Admin →
    User Management → Add User`), not by editing a seeded test account.
- [ ] Assign the correct role (`DC Office` / `UNO Office` / `Police Admin`) — this sets the
      module permissions (`dashboard`, `gov-reports`, `customers`) automatically.
- [ ] Set the correct jurisdiction field for that role — `district_id` for DC, `upazila_id` for
      UNO, `police_station_id` for Police. Double-check against real district/upazila/police
      station data (Phase 1) — a wrong jurisdiction id silently shows the wrong hotels, not an
      error.
- [ ] Set a real, unique password (or trigger Laravel's password-reset flow, if wired up for
      this account) — never reuse the seeded test password for a real office.
- [ ] Confirm at least one real hotel is correctly assigned to that jurisdiction (`Admin → Hotel
    Registration`, checking District/Upazila/Police Station on the hotel record) — a
      jurisdiction with zero hotels will show a technically-correct but empty dashboard, which is
      easy to mistake for a bug.
- [ ] Log in as the new account once (or have the office do so) and confirm the dashboard/reports
      show the expected hotel(s) — the fastest way to catch a jurisdiction-assignment mistake.
- [ ] If the metropolitan police-station gap applies to this office (see
      [README.md decision 7](README.md#decisions-already-made-baked-into-the-docs-below) — rural
      police stations are seeded, Dhaka/Chattogram/etc. Metropolitan Police are not yet), source
      and seed that office's real police station first via `BdGeographySeeder` before assigning a
      Police Admin account to it.
- [ ] Decide whether the 4 seeded test accounts should be deactivated (not deleted — deleting
      would cascade-delete their `hotel_user_assignments`/`audit_logs` rows) once real accounts
      are live, to avoid confusion in the Users list. Toggle via `Admin → User Management`.
- [ ] For each real hotel: confirm its trade license/BIN/TIN/owner-NID documents have actually
      been uploaded (`Admin → Hotel Registration → [hotel] → Edit`) — these were nullable at
      registration time (see [README.md decision 8](README.md#decisions-already-made-baked-into-the-docs-below))
      specifically so a hotel could be created before its paperwork was in hand; go-live is the
      point to confirm that paperwork actually got attached.
