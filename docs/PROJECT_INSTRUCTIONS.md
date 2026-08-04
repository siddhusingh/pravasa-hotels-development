# Pravas Hotels — Project Working Instructions

Last reviewed: 4 August 2026

This file is the primary working guide for future development and testing in
this project. Read it before changing code or using a live role session.

## 1. Real-time testing names and content

- Never use assistant, product, model, automation, or similar technology names
  in real-time test data or user-visible content.
- This restriction applies to guest names, companies, contacts, agendas,
  discussions, remarks, special requests, banquet names, restaurant names,
  table names, filenames, screenshots, logs, notifications, and test records.
- Use neutral business-safe labels such as `QA Restaurant Visit`,
  `Reservation Regression Test`, or `Sales Visit Verification`.
- Do not place internal implementation notes in fields that real users can see.
- Clearly identify test records using neutral QA wording and the test date.
- Do not submit or modify live records unless the requested task authorizes
  real-time creation or editing.
- After testing, report which records were created. Do not delete them unless
  deletion is requested or clearly authorized.

## 2. Start with project documentation

- Read `docs/README.md` and the relevant module document before implementation.
- For Leads, Sales Visits, restaurant reservations, and Banquet room
  requirements, read `docs/leads-module.md` completely.
- Inspect recent repository history when documentation and current behavior
  appear different.
- Update the relevant document whenever behavior, validation, persistence,
  routes, or role responsibilities change.

## 3. Project architecture

- This is a CodeIgniter application containing both legacy and modernized
  paths. Similar-looking screens may use different controllers and views.
- Confirm the exact role, route file, controller, view, model, and session scope
  before editing.
- Sales routes are defined separately in `application/config/routes/Sales.php`.
- Sales Executive Sales Visit add/edit wrappers reuse the established Super
  Admin Sales Visit forms while replacing protected URLs with Sales-only
  endpoints.
- Do not expose a privileged role endpoint merely to make a shared view work.

## 4. Change scope and safety

- Change only files and behavior required by the current request.
- Preserve unrelated working code, user changes, and role-specific behavior.
- Do not remove legacy fallbacks until historical data has been migrated and
  verified.
- Remove code only when it is demonstrably unused, duplicated, debug-only, or
  harmful within the requested scope.
- Keep client validation for usability and server validation as the authority.
- Validate encrypted or posted identifiers against active database records and
  the current user's permitted scope.
- Use database transactions for multi-record operations that must succeed or
  fail together.

## 5. Leads and Sales Visits

- A Sales Visit creates or updates both a Lead and a `sales_visits` record.
- Preserve role ownership, property scope, department scope, creator fields,
  assignment fields, escalation timing, and CSRF refresh behavior.
- Dynamic fields depend on department and stage. Confirm the active screen
  before changing them.
- Do not overwrite expected revenue with zero during create or edit.
- When a controlled option is disabled, explicitly clear its stored values if
  stale data would otherwise remain.

## 6. Restaurant reservations

- Show reservation controls only for Restaurants + Quotation Sent.
- Use `RestaurantBookingModel` as the shared source of truth for selection
  validation, table normalization, availability, overlap conflicts, locks, and
  lead-table mappings.
- Recheck availability inside the final save transaction after locking tables.
- Store every selected table in `lead_reserved_tables` and keep the first table
  in `leads.table_id` only for legacy compatibility.
- During edit, exclude the current Lead ID from its own conflict check.
- Preload normalized table mappings and use the legacy table value only as a
  fallback.
- Modal confirmation commits a draft to the full form; it must not save the
  Lead independently.
- Cancel and Close must discard uncommitted modal changes.
- In the Sales Executive edit reservation modal, use the existing native styled
  select controls. Do not initialize an enhanced select plugin for those modal
  fields.

## 7. Banquet room requirements

- For Banquets + Quotation Sent, show `Is Room Required?`.
- When enabled, require valid check-in and check-out dates and a positive whole
  number of rooms in both browser and server validation.
- Check-out cannot be earlier than check-in.
- Create cannot accept a past check-in date; edit may display an existing past
  date while still validating any submitted values.
- When the controlled option is disabled during edit, clear check-in,
  check-out, and room count.

## 8. Browser and real-time verification

- Test through the actual signed-in role and exact route affected by the change.
- For create flows, submit a complete valid record and verify it appears in the
  role-scoped list.
- Reopen the record and verify database-backed values, not only success toasts.
- For edit flows, change the relevant values, save, reopen, and verify again.
- Test dependent dropdowns, validation errors, cancel behavior, and persisted
  identifiers where applicable.
- Do not accept camera, location, microphone, or other browser permissions
  unless the task specifically requires and authorizes them.
- Avoid changing unrelated live data during verification.

## 9. Required checks before handoff

- Run PHP syntax checks for every changed PHP file.
- Run `git diff --check`.
- Review the final diff for unrelated changes.
- Check the affected page for browser console errors.
- Confirm that no form was submitted unintentionally.
- Report created test records and whether they remain in the system.
- Summarize exactly what changed and which verification steps passed.

