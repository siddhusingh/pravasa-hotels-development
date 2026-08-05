# Leads Module — Functional and Technical Guide

Last reviewed: 5 August 2026

## Purpose

This document is the starting point for future work on the Leads module. It explains the current lead lifecycle, role-specific entry points, dynamic department behavior, assignment, reporting, follow-ups, restaurant table reservations, server validation, persistence, and safe extension practices.

The module contains both modernized and legacy paths. Do not assume that every role uses the same controller or view even when the screens look similar.

## High-level lifecycle

1. A lead enters through a manual form, an external source, an import, or another integration.
2. The selected hotel/property and department determine the available fields and assignable users.
3. The stage controls which department-specific fields are visible and required.
4. Client-side validation gives immediate feedback; server-side validation remains authoritative.
5. The system either creates a lead or updates a recent duplicate according to the active flow.
6. Assignment, escalation, timestamps, notifications, and department-specific data are saved.
7. The lead appears in the role-scoped report and can be searched, filtered, edited, transferred, called, messaged, or followed up.
8. Status changes may create history records and may trigger department-specific actions.

## Roles and primary files

| Role | Create controller | Create view | Report/edit controller | Report/edit view |
| --- | --- | --- | --- | --- |
| Super Admin | `application/controllers/LeadController.php` | `application/views/super_admin/add_lead.php` | `application/controllers/LeadController.php` | `application/views/super_admin/lead_report.php` |
| Hotel Admin | `application/controllers/hotelAdmin/Leads.php` | `application/views/hotel_admin/add_lead.php` | `application/controllers/hotelAdmin/Leads.php` | `application/views/hotel_admin/lead_report.php` |
| Agent | `application/controllers/agent/Leads.php` | Shared `application/views/hotel_admin/add_lead.php` with Agent URLs/labels | `application/controllers/agent/Leads.php` | `application/views/agent/lead_report.php` |
| Agency/other legacy flows | Separate controllers and views | Inspect the relevant role directory | Separate implementation | Not automatically covered by the three-role changes above |

### Shared Agent create form

Agent lead creation intentionally renders the Hotel Admin create view. The Agent controller supplies:

- `lead_form_role_label = Agent`
- `lead_form_submit_url = insert-lead-agents`
- `lead_form_redirect_url = view-agents-leads`
- the Agent's fixed property and allowed department data

The Hotel Admin view provides Hotel Admin defaults when these variables are absent. Preserve these defaults so Hotel Admin behavior is not changed while working on Agent creation.

## Main routes

### Pages and saves

| Purpose | Route | Handler |
| --- | --- | --- |
| Super Admin report | `manage-leads` | `LeadController/view_leads` |
| Super Admin create | `add-lead` | `LeadController/add_lead` |
| Super Admin update | `update-lead-super-admin` | `LeadController/update_lead` |
| Hotel Admin report | `view-leads` | `hotelAdmin/Leads/index` |
| Hotel Admin create | `add-lead-admin` | `hotelAdmin/Leads/add_lead` |
| Hotel Admin insert | `insert-lead-admin` | `hotelAdmin/Leads/insert_lead` |
| Hotel Admin update | `update-lead-admin` | `hotelAdmin/Leads/update_lead` |
| Agent report | `view-agents-leads` | `agent/Leads/index` |
| Agent create | `add-lead-agents` | `agent/Leads/add_lead` |
| Agent insert | `insert-lead-agents` | `agent/Leads/insert_lead` |
| Agent update | `update-lead-agent` | `agent/Leads/update_lead` |

### Related pages

| Purpose | Route |
| --- | --- |
| Super Admin follow-ups | `followups` |
| Hotel Admin follow-ups | `view-followups-admin` |
| Agent follow-ups | `view-followups-agent` |
| Super Admin lead details | `view-lead-details/{id}` |
| Hotel Admin lead details | `view-lead-details-admin/{id}` |
| Agent lead details | `view-lead-details-agent/{id}` |
| Customer history | `customer-lead-history/{phone}` and role-specific variants |
| CSV sample/import | `download-sample-file`, `import-leads` |

All route definitions live in `application/config/routes.php`.

## Lead report and list behavior

The central querying logic is in `application/models/LeadModel.php`, especially:

- `get_filtered_leads()`
- `get_leads_status_counts()`
- `get_followup_leads()`
- `get_lead_by_id_with_joins()`
- `get_all_assignable_users()`
- role-specific lead retrieval and count methods

The report supports combinations of:

- city
- property/hotel
- department
- status
- lead source/channel
- stage/disposition
- business versus non-business grouping
- created date range
- created-by user and role
- assigned-to user and role
- guest name or phone search
- exact normalized 10-digit phone history
- due follow-ups

`Not Assigned` is a calculated report bucket based on assignment fields; it is not simply a normal lead status value.

The Super Admin report at `manage-leads`, Hotel Admin report at `view-leads`,
and Agent report at `view-agents-leads` do not expose `Not Assigned` as a
status indicator tab or Status filter option. Its backend calculation is
retained for compatibility and for dashboards or other lead sections that
still use it.

The Delete action on Super Admin `manage-leads` permanently removes the active
row from the `leads` table through
`LeadController::permanentlyDeleteLead()`. The endpoint accepts POST only and
requires the primary Super Admin session (`role_as = super_admin` and
`user_role = 1`). The older `LeadController::deleteLead()` soft-delete path is
intentionally retained for future use and for unchanged role-specific flows.
The hard-delete implementation does not explicitly delete calls, status
history, Sales Visits, feedback, or records from other modules. Database-level
foreign-key behavior still applies; currently `lead_reserved_tables` cascades
when its parent Lead is deleted. After a successful Super Admin deletion, the
active filtered list is fetched again so its cards, status counts, total count,
pagination offset, and Load More state stay synchronized.

The Agent report uses the separate POST-only
`agent/Leads/permanently_delete_lead` endpoint. It permanently deletes only an
active Lead in the Agent's selected hotel and rejects a Lead assigned to a
different Agent. Its Delete button uses the same scope rule, and a successful
delete refetches the current Agent list so the card, counts, total, offset, and
Load More state refresh automatically. The shared legacy soft-delete endpoint
remains available and unchanged.

The Hotel Admin report uses the POST-only
`hotelAdmin/Leads/permanently_delete_lead` endpoint. It permanently deletes
only an active Lead belonging to the hotel in `hotel_admin_session`; posted
property values cannot widen that scope. After success, the active filtered
Hotel Admin list and counts are fetched again. The shared legacy soft-delete
endpoint remains unchanged.

On Super Admin, Hotel Admin, and Agent reports, the Open, In Progress, and Closed badges
show the overall counts under the non-status filters. `Total Leads` instead
shows the filtered count for the currently selected status tab, matching the
cards returned by that selection.

Unless a report request contains a status, phone, search, or follow-up override, some report flows default to Open leads.

## Common lead fields

The `leads` table is the primary record. Common fields used throughout the module include:

- guest: `user_name`, `phone_number`, `email`
- classification: `property`, `type`, `lead_type`, `purpose`
- source: `user_channel`, `template_name`
- workflow: `status`, `disposition`, `reason`, `query`, `remark`
- assignment: `is_assigned`, `assigned_to`, `assigned_person_user_role`, `assigned_person_email`
- ownership: `created_by`, `creator_user_role`
- timing: `created_at`, `updated_on`, `responded_time`, `completed_time`
- follow-up/escalation: `followup_date`, `second_followup_date`, `esc_next_followup_at`, `esc_follow_up_level`
- commercial: `amount`, `revenue_room`, `revenue_fnb`, `revenue_other`, `promotional_offers`
- soft deletion: `is_deleted`

Additional columns are populated according to department and stage.

## Base validation rules

The browser validates for usability, but every save endpoint must repeat validation on the server.

Current shared expectations are:

- Phone is normalized to its last 10 digits and must match a valid Indian mobile pattern beginning with 6–9.
- Guest name is required except when the stage is `Not Contacted`.
- Email is optional, but must be valid when provided.
- Hotel/property, department, source, stage, lead status, and query are required.
- `Lead Lost` requires a reason.
- On Super Admin Lead creation, an entered Follow-up Date and 2nd Follow-up
  Date must each be strictly before the available Booking Date. An entered 2nd
  Follow-up Date requires a Follow-up Date and must be strictly later than it.
  The date fields remain optional, and the rules are enforced by both browser
  and server validation. This includes the Banquet Booking Date field and the
  Restaurant Booking Date committed from the table-reservation modal.
- Stage-specific and department-specific validation applies when the stage is `Quotation Sent`.
- Server responses use field-keyed errors so the browser can highlight the correct control.
- CSRF hashes are returned/refreshed in JSON flows.

Typical validation responses:

- HTTP 422: invalid or missing fields
- HTTP 403/404: role scope or lead access failure
- HTTP 409: restaurant table/time conflict
- HTTP 500: persistence failure

## Statuses and stages

The commonly exposed lead statuses are:

- Open
- On Hold
- In Progress
- Closed

The main stages currently include:

- Not Contacted
- General Information or Contacted, depending on the screen/legacy flow
- Quotation Sent
- Negotiations
- Contract Done
- Advance Received
- Lead Won
- Lead Lost

Older code also contains compatibility paths such as `Reservation` and `Shopping - Follow Up`. Before removing or renaming a stage, search all controllers, views, reports, scheduled tasks, and integrations for the stored string.

When status becomes Closed, `completed_time` is set. Non-closed updates generally refresh `responded_time`.

## Dynamic department fields

Dynamic fields are mainly created by JavaScript when the property, department, or stage changes. The important department names are stored as human-readable strings and are normalized in validation (`Restaurants` → `restaurant`, `Banquets` → `banquet`).

### Rooms

Typical fields include:

- check-in and check-out dates/times
- room type
- number of rooms
- pax, adults, and children
- meal plan
- room, F&B, other, and calculated expected revenue
- rate-related fields in Super Admin/legacy MyCloud paths

When room requirement is explicitly turned off, controlled create/edit flows clear check-in and check-out dates. Preserve the `room_requirement_controlled` and cross-department room count behavior when changing dynamic fields.

For `Quotation Sent`, meal plan is required for Rooms.

### Restaurants

The current restaurant flow uses the Reserve Table modal rather than exposing reservation selectors inline. See the dedicated restaurant section below.

Fields outside the modal remain on the lead form where applicable:

- arrival time
- number of pax
- expected revenue
- special occasion

### Banquets

Typical fields include:

- booking date
- number of pax
- banquet selection
- expected revenue
- special request
- optional room requirement; when enabled, check-in date, check-out date, and a positive whole-number room count are mandatory
- legacy banquet-specific reservation/manager fields

For `Quotation Sent`, a banquet is required.

### Spa

Typical fields include booking date/time, pax or service-related details, expected revenue, and special requests. Confirm the exact fields in the active create and edit view before modifying this department.

### Water Park

Typical fields include booking date/time, pax, expected revenue, and special requests. Confirm exact behavior in the active role view.

### Wedding

Wedding combines room and banquet concepts. It may include:

- stay dates and room counts
- pax/adults/children
- room type and meal plan
- banquet selection
- multiple revenue components

For `Quotation Sent`, meal plan and banquet are required.

## Create flow

### Browser flow

1. Load role-scoped hotels, departments, assignable users, and lookup data.
2. Select hotel/property where the role permits it.
3. Select a department and stage.
4. Rebuild the dynamic field area.
5. Load dependent lookup options with AJAX.
6. Validate the base form and dynamic fields.
7. Serialize normal and dynamic inputs into `FormData`.
8. Submit to the role-specific insert endpoint.
9. Refresh the CSRF token, display the server result, and redirect to the correct role report.

### Server flow

1. Confirm POST and authenticated role scope.
2. Resolve the hotel and department from trusted database records.
3. Confirm the selected department/property is allowed for the current user.
4. Run base, stage, department, and restaurant validation.
5. Resolve and validate the assignee.
6. Build common and optional lead data.
7. Set creator, assignment, escalation, and timing fields.
8. Apply duplicate handling.
9. For restaurant leads, lock tables, recheck conflicts, save the lead and table mappings in one transaction.
10. Trigger assignment notifications without making mail delivery a prerequisite for a successful save.

## Duplicate handling

The manual Hotel Admin and Agent flows treat a recent active lead as a duplicate when the same normalized 10-digit phone exists for the same property within approximately two hours and is not Closed. When both stay dates are present, the duplicate lookup also compares those dates.

The recent record is updated instead of inserting another lead. The response contains `duplicate: true`.

Restaurant duplicate updates must exclude the existing lead ID during availability checks and replace its normalized table mappings in the same transaction.

Super Admin and external intake paths contain their own duplicate logic. Always inspect the exact entry point before changing duplicate rules; do not assume one controller's query covers every source.

## Assignment and role scope

### Super Admin

- Can work across hotels subject to application permissions.
- Can assign to supported active users.
- Uses the central `LeadController` flows.

### Hotel Admin

- Property is fixed from `hotel_admin_session`.
- Posted property values must not override the session property.
- Fetch and update queries must include the hotel property scope.

### Agent

- Hotel and department access comes from `staff_hotel_department_mapping`.
- Create must only accept a department mapped to that Agent and hotel.
- Fetch/update must include the Agent's hotel scope.
- A lead assigned to another Agent must return access denied.
- The Agent create form uses the shared Hotel Admin view but Agent endpoints.

Assignment fields must be derived from a validated active user rather than trusting posted role/email values.

## Edit flow

1. The report requests lead details from the role-specific controller.
2. The controller scopes the lead to the current hotel/user before returning JSON.
3. Existing values are placed into the base fields.
4. The dynamic field area is rebuilt from the current stage and department.
5. Async option lists load, then existing values are selected.
6. The user edits and submits to the role-specific update endpoint.
7. The controller revalidates every selection, performs a scoped update, and returns the refreshed joined lead.
8. The report card/list refreshes without requiring a full-page reload where supported.

Super Admin Manage Leads edit applies the same optional follow-up ordering as
creation: `Follow-up Date < 2nd Follow-up Date < Booking Date`. A second
follow-up requires the first. Browser and server validation both enforce the
rule, including Banquet Booking Date and the Restaurant reservation Booking
Date.

For Super Admin Banquet edits at `Quotation Sent`, enabling `Is Room Required?` requires check-in, check-out, and a positive whole-number room count in both the Manage Leads modal and Sales Visit edit. Disabling it clears all three values. Existing stored values are preloaded before validation.

Hotel Admin and Agent Banquet create/edit apply the same conditional rule. Both roles use the shared Hotel Admin create template with role-specific insert endpoints, while each role uses its own report edit modal and update endpoint.

Important: restaurant edit detail responses append `table_ids` from `lead_reserved_tables`. If no normalized rows exist, legacy `leads.table_id` is split as a fallback.

## Restaurant table reservation

### Activation condition

The reservation control is shown when:

- department is Restaurants, and
- stage is Quotation Sent.

The main lead form displays a `Reserve Table` button. After a saved or committed modal selection exists, the button changes to `Edit Reservation` and a summary is shown.

On edit screens the reservation control is positioned after Query and Remark. Other department fields remain in the normal dynamic field section.

### Modal IDs

- Create: `reserveTableModal`
- Edit: `editReserveTableModal`

### Modal fields

- restaurant
- booking date
- slot type
- time slot
- table category
- one or more tables
- special instructions/request
- reservation status

Reservation status is a radio-card UI with these stored values:

- Reserved
- Seated
- Completed
- Cancelled

Only one status can be selected.

### Modal close rule

The modal uses a static backdrop and disabled keyboard close. Backdrop clicks and Escape must not close it. It may close only through the explicit modal Close, Cancel, or successful Reserve Table action.

The code also guards Bootstrap's hide event, because data attributes alone are not reliable across the Bootstrap versions used by this project.

### Draft versus committed values

The modal is a draft editor. It does not save the database directly.

On successful modal confirmation:

1. Availability is checked again.
2. Modal values are copied into hidden inputs inside the lead form.
3. Selected table IDs are stored as selected options in hidden `table_id[]`.
4. The summary and button label are updated.
5. The modal closes.
6. The values reach the database only when the complete lead form is submitted.

Cancel/Close must discard uncommitted modal changes and must not alter the hidden lead-form values.

### Lookup and availability endpoints

| Route | Purpose |
| --- | --- |
| `lead/get-restaurants` | Active restaurants for a hotel |
| `lead/get-slot-types` | Active slot types |
| `lead/get-time-slots` | Active time slots for a slot type |
| `lead/get-table-categories` | Active categories for a restaurant |
| `lead/get-tables` | Active tables for a restaurant/category |
| `lead/check-restaurant-availability` | Validate selection and return availability/conflicts |

These routes are handled by `LeadController`, even when called from Hotel Admin or Agent views.

Edit availability requests include `exclude_lead_id` so a lead does not conflict with its own existing reservation.

### Server-side reservation validation

`application/models/RestaurantBookingModel.php` is the shared source of truth.

It verifies:

- valid `Y-m-d` booking date
- booking date is not in the past unless explicitly allowed
- restaurant exists in the submitted context
- category belongs to the restaurant and is active
- every selected table belongs to the restaurant/category and is active
- slot type is present
- time slot belongs to the slot type and is active
- no selected table overlaps another blocking reservation

Never rely only on the availability cards shown in the browser. Availability must be rechecked inside the save transaction.

### Blocking statuses and overlap

Only `Reserved` and `Seated` block another reservation.

`Completed` and `Cancelled` do not block availability.

Conflicts are based on time overlap, not only identical time-slot IDs. The overlap logic also supports slots that cross midnight.

### Concurrency protection

Restaurant create/edit follows this sequence:

1. Normalize and de-duplicate table IDs.
2. Begin a database transaction.
3. Lock selected rows in `tables` using `FOR UPDATE` in stable numeric order.
4. Recheck availability/conflicts.
5. Insert or update the lead.
6. Replace mappings in `lead_reserved_tables`.
7. Commit only if both the lead and mappings save successfully; otherwise roll back.

This sequence prevents two simultaneous requests from successfully reserving the same table and overlapping time.

### Reservation persistence

The normalized many-to-many mapping is:

```text
leads.id -> lead_reserved_tables.lead_id
tables.id -> lead_reserved_tables.table_id
```

Migration:

`database/migrations/20260729_create_lead_reserved_tables.sql`

The table has a unique `(lead_id, table_id)` key and foreign keys to `leads` and `tables`.

For backward compatibility, `leads.table_id` stores the first selected table. New code must treat `lead_reserved_tables` as authoritative for multi-table reservations.

The migration backfills valid legacy single-table values into the normalized mapping table.

## Follow-ups and escalation

Lead creation derives an escalation deadline from the selected department's escalation configuration and initializes the escalation level.

Follow-up pages use `followup_date` and `second_followup_date`. A lead is due when either populated date is on or before today.

The follow-up list is still subject to role/property/department scope and other selected filters.

Some legacy stage/status handlers contain additional `Shopping - Follow Up` behavior and fields. Search those exact stored strings before modifying follow-up rules.

## Status history

The central status-update flow can insert into `lead_status_history` with:

- lead ID
- status
- remark
- role that changed it
- user/assignee ID
- change time

History display resolves the user's name according to the stored role. Full edit endpoints and quick status-update endpoints are separate flows; modifying one does not automatically modify the other.

## Notifications and integrations

Depending on entry point, department, stage, and status, the module can interact with:

- assigned-user email through the configured SMTP worker
- WhatsApp templates and messages
- call history/correlation IDs
- restaurant booking confirmations and feedback links
- MyCloud room booking/rate availability flows
- Google Business, iframe, API, and import lead sources

Notification failures should not undo a correctly persisted lead unless the specific integration is intentionally part of the transaction.

The central external intake methods live near the beginning of `LeadController`; MyCloud, WhatsApp, lookup, and restaurant endpoints are later in the same controller.

## Key models and supporting files

| File | Responsibility |
| --- | --- |
| `application/models/LeadModel.php` | Lead retrieval, filters, counts, joins, reports, follow-ups, assignee/creator helpers |
| `application/models/RestaurantBookingModel.php` | Reservation validation, availability, conflicts, locks, and lead-table mappings |
| `application/models/Common_model.php` / `Comman_model.php` | Generic legacy data access used by controllers |
| `application/config/routes.php` | Public route-to-controller mapping |
| `assets/css/style.css` | Shared reservation modal/card styling |
| `database/migrations/20260729_create_lead_reserved_tables.sql` | Multi-table reservation schema and legacy backfill |

## Safe change procedure

When adding the same functionality to another role or lead screen:

1. Identify the exact role controller, create view, report/edit view, and routes.
2. Confirm session scope and allowed properties/departments.
3. Compare both client and server validation.
4. Reuse shared lookup endpoints and models where possible.
5. Keep role-specific submit and redirect URLs explicit.
6. Preserve unrelated dynamic department branches.
7. For edit, return all persisted IDs needed to rebuild multi-select controls.
8. For restaurant edit, pass the current lead ID to conflict checks.
9. Recheck availability within a transaction after locking tables.
10. Return field-keyed errors and a new CSRF hash.
11. Test create and edit through the actual signed-in role.
12. Update this document.

## Sales Visit lead creation and edit

Super Admin Sales Visits create a Lead and a `sales_visits` record together through:

- View: `application/views/super_admin/sales_visits/add.php`
- Controller: `application/controllers/superAdmin/SalesVisits.php`
- Routes: `add-sales-visit` and `insert-sales-visit`

For `Quotation Sent` Sales Visits:

- Banquets expose `Is Room Required?`. When enabled, check-in date, check-out date, and a positive whole-number room count are required. Check-in cannot be in the past, and check-out cannot precede check-in. This rule applies to normal Super Admin Lead creation/edit and Super Admin Sales Visit creation/edit; Sales Visits store the values on their generated Lead.
- Restaurants expose the same reservation modal used by normal Lead creation. Modal confirmation only commits values into hidden Sales Visit form fields; the reservation is persisted when the complete Sales Visit form is submitted.
- Restaurant lookup data is read from the shared Lead endpoints. Availability is checked before modal confirmation and rechecked after locking the selected tables during final save.
- The generated Lead retains the first table in legacy `leads.table_id`, while every selected table is stored in `lead_reserved_tables`.
- Restaurant Lead creation, table mappings, and Sales Visit creation share one database transaction so a partial reservation cannot be left behind.
- The modal uses a static backdrop and disables Escape. It closes only through its Close/Cancel controls or successful reservation confirmation.
- Super Admin Sales Visit edit preloads Banquet room details and every normalized Restaurant table mapping from the linked Lead.
- Restaurant availability checks during edit exclude the linked Lead so its current tables remain selectable, while conflicts with other Leads are still rejected.
- Updating a Restaurant Sales Visit locks the selected tables and updates the Lead, normalized table mappings, and Sales Visit inside one transaction.
- Existing past reservation dates can be reviewed during edit; new selections are still validated by the server before update.
- Sales Visit validation reads the committed hidden reservation values while displaying any combined reservation error beside the Reserve/Edit Reservation button.
- Expected Revenue from Restaurant and Banquet Quotation Sent fields must persist on both create and edit; legacy status handling must not overwrite it with zero.

### Sales Executive Sales Visits

Sales Executive Sales Visits use a separate role-scoped controller while
reusing the established Super Admin Sales Visit forms:

- Controller: `application/controllers/Sales/Visits.php`
- Add wrapper: `application/views/sales/sales_visits/add.php`
- Edit wrapper: `application/views/sales/sales_visits/edit.php`
- Routes: `application/config/routes/Sales.php`

The wrappers replace protected Super Admin URLs with Sales-only endpoints. Do
not expose Super Admin save or lookup actions to make a shared form work.

For Restaurants + Quotation Sent, the Sales controller must use
`RestaurantBookingModel` for selection validation, transaction-time table
locking, overlap checks, and normalized `lead_reserved_tables` persistence.
Edit preloads every normalized table ID and falls back to the legacy
`leads.table_id` value only when no normalized rows exist. Availability checks
during update exclude the linked Lead ID.

For Banquets + Quotation Sent, the inherited `Is Room Required?` control follows
the same create/edit rules as Super Admin Sales Visits. When enabled, valid
check-in and check-out dates and a positive whole-number room count are
required and persisted on the generated Lead. When the controlled option is
disabled, edit clears all three stored values.

## Regression checklist

### Common create

- Valid lead saves for each role.
- Invalid phone, email, missing stage, missing source, and missing query show field errors.
- `Not Contacted` allows an empty guest name.
- `Lead Lost` requires a reason.
- Super Admin, Hotel Admin, and Agent reject a Follow-up Date on or after Booking Date.
- Super Admin, Hotel Admin, and Agent reject a 2nd Follow-up Date on or after Booking Date, without a
  first Follow-up Date, or on/before the first Follow-up Date.
- Valid ordering for all three roles satisfies `Follow-up Date < 2nd Follow-up Date < Booking Date`.
  When the current stage exposes Booking Enquiry Date instead, that date is the
  booking boundary used by the same validation.
- Verify this ordering for both Banquets and Restaurants; Restaurant validation
  must use the committed reservation Booking Date.
- Correct creator role/user and property are stored.
- Assignment only accepts an active permitted user.
- Recent duplicate behavior matches the intended source.

### Dynamic departments

- Switching hotel, department, or stage rebuilds fields without duplicate controls.
- Rooms retain/clear controlled dates correctly.
- Banquet lookup and required validation work.
- Super Admin Banquet creation and edit require check-in, check-out, and a positive whole-number room count only when `Is Room Required?` is enabled, through both normal Leads and Sales Visits.
- Hotel Admin and Agent Banquet creation/edit require the same three values when `Is Room Required?` is enabled, with validation repeated in each role-specific controller.
- Turning off `Is Room Required?` clears and does not persist the room dates or room count.
- Restaurant modal appears only for Restaurants + Quotation Sent.
- Wedding room/banquet requirements still work.
- Spa and Water Park branches still render and submit.

### Restaurant create

- Restaurant → category → table dependency loads.
- Slot type → time slot dependency loads.
- Availability counts/cards update after date, slot, category, or restaurant changes.
- At least one available table can be selected.
- Unavailable cards cannot be selected.
- Radio statuses render and save exact database values.
- Backdrop and Escape do not close the modal.
- Cancel does not mutate committed hidden values.
- Confirm copies values to the lead form but does not independently save the lead.
- Full lead submission stores both the lead fields and every table mapping.

### Restaurant edit

- Existing restaurant, date, slot type, time slot, category, tables, instructions, and status preload.
- Current reservation is excluded from its own availability conflict.
- Changing reservation values updates the summary.
- Reopening before submit shows the committed modal draft.
- Full update persists the new selection.
- Reopening after save proves database persistence.
- A real conflicting reservation returns HTTP 409 and keeps the edit form usable.
- Follow-up dates must remain strictly ordered before the committed Restaurant
  Booking Date, and an invalid edit must not update the Lead.

### Reports and follow-ups

- Status counts and cards agree under the same filters.
- Super Admin, Hotel Admin, and Agent `Total Leads` matches the currently selected status tab
  while the three status badges retain their overall counts.
- Super Admin Manage Leads shows only Open, In Progress, and Closed in both the
  status indicators and the Status filter.
- Agent Leads Management shows only Open, In Progress, and Closed in both the
  status indicators and the Status filter.
- Hotel Admin Leads Management shows only Open, In Progress, and Closed in both
  the status indicators and the Status filter.
- Not Assigned logic remains separate from normal status values, and its
  backend compatibility does not expose it in any of the three report UIs.
- Dashboards and other lead-section behavior remains unchanged by these
  role-scoped report updates.
- Super Admin Delete warns that the action is permanent, removes the selected
  active row from `leads`, rejects non-Super Admin or non-POST requests, and
  refreshes the filtered list and counts after success.
- The retained soft-delete endpoint continues to set `is_deleted` and
  `deleted_at`; it is not replaced by the Super Admin hard-delete endpoint.
- Agent Delete permanently removes only an allowed Lead in the selected hotel,
  rejects cross-hotel and other-Agent records, and refreshes the active list
  and counts after success.
- Hotel Admin Delete permanently removes only an active Lead belonging to its
  session hotel and refreshes the selected list and counts after success.
- A permanent Lead delete does not explicitly delete calls, status history,
  Sales Visits, feedback, or other module records.
- Search works by name and normalized phone.
- Customer history stays role-scoped.
- Due follow-ups include either due follow-up date.
- Super Admin, Hotel Admin, and Agent Banquet and Restaurant create/edit flows reject invalid
  follow-up ordering in both browser and server validation.
- Edit access cannot cross hotel or Agent assignment scope.

### Static verification

- Run PHP syntax checks for every changed PHP file.
- Parse changed inline JavaScript after replacing PHP interpolation blocks.
- Run `git diff --check`.
- Review the final diff to ensure no unrelated role or department was changed.

## Known maintenance cautions

- `LeadController.php` includes several legacy and integration flows in addition to the manual Super Admin flow.
- `Common_model` and `Comman_model` are both used; the spelling difference is real.
- Stored stage strings are not fully uniform across old and new screens.
- Some quick status flows and full edit flows update different sets of fields.
- The create views contain substantial inline JavaScript; IDs are shared by validation, serialization, and async loaders.
- Hotel Admin and Agent create share a view but not a save endpoint.
- Multi-table reservations require both legacy `leads.table_id` compatibility and normalized mappings.
- Do not remove fallback reading of legacy `table_id` until all historical data is migrated and verified.
- Do not remove transaction-time restaurant conflict checks even if the modal already checked availability.

## Fast orientation for a future Codex session

For a requested Leads change, read in this order:

1. This document.
2. `application/config/routes.php` for the requested role.
3. The role's controller create/update methods.
4. The role's create or report/edit view.
5. `application/models/LeadModel.php` if list/filter/report behavior is involved.
6. `application/models/RestaurantBookingModel.php` and the reservation migration if tables are involved.
7. The equivalent implementation in another completed role for comparison—but port only the required feature, not the whole view/controller.

After implementation, test using the actual role session in the browser and verify persisted values by reopening the lead.
