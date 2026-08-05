# Form Download Module - Functional and Technical Guide

Last reviewed: 5 August 2026

## Purpose

This document is the starting point for future work on the Super Admin
`Download Lead Form` module. It explains how the downloadable HTML form is
generated, how it loads lookup data, how department-specific fields behave,
how submissions reach the public lead API, and which current limitations must
be considered before making changes.

The downloaded file is intended to be portable and may be hosted or embedded
outside the main application. Its API base URL and any optional default
selections are written into the file at download time.

## User entry point

The module appears near the bottom of the Super Admin sidebar:

```text
My Profile
Download Lead Form
Sign Out
```

The sidebar item opens an optional-default modal. The modal submits to
`download-lead-form`, which is handled by the Super Admin `Main` controller.

| Purpose | Route | Handler |
| --- | --- | --- |
| Download the lead form | `download-lead-form` | `superAdmin/main/download_lead_form` |

Primary files:

| File | Responsibility |
| --- | --- |
| `application/views/super_admin/include/sidebar.php` | Displays the Super Admin modal trigger |
| `application/views/super_admin/include/download_lead_form_modal.php` | Optional defaults, dependent lookups, CSRF-safe request, and browser download |
| `application/config/routes.php` | Maps the download and public API routes |
| `application/controllers/superAdmin/Main.php` | Authorizes and prepares the HTML download |
| `leadform.html` | Standalone enquiry form, client validation, dynamic fields, and API calls |
| `application/controllers/API.php` | Public lookup endpoints and lead submission handling |
| `application/models/API_Model.php` | Property, department, restaurant, banquet, and time-slot lookups |
| `application/models/LeadModel.php` | Persists a new lead through `insert_lead()` |

## Download flow

1. A signed-in Super Admin selects `Download Lead Form` in the sidebar.
2. A modal opens with optional Property and Department selections.
3. Selecting Restaurants or Banquets displays an optional dependent selector.
   Its options load for the selected Property.
4. The modal may be submitted with no selections, partial selections, or all
   available selections.
5. The modal sends a CSRF-protected POST request to `download-lead-form`.
6. The `Main` controller constructor confirms that
   `super_admin_session` exists and `role_as` is `super_admin`.
7. Every supplied ID is resolved against an active/non-deleted database record.
   Missing, invalid, stale, or mismatched optional IDs are omitted rather than
   blocking the download.
8. The controller reads `leadform.html` from the application root.
9. It calculates the current CodeIgniter `base_url()` and replaces exactly one
   JavaScript `const BASE_URL = ...;` declaration in the HTML.
10. It replaces exactly one `DEFAULT_SELECTIONS` declaration with the resolved
    numeric IDs or `null` for each omitted selection.
11. If the source file cannot be read, or either declaration cannot be replaced
    exactly once, the request ends with an error.
12. The prepared content is returned as a binary attachment named
   `leadform.html` with private/no-store cache headers.

The source template currently contains a localhost base URL. This value is only
a template placeholder for direct local use; the controller rewrites it in the
downloaded copy.

## Optional default-selection modal

All modal fields are optional. There is no required-field validation before
download.

| Modal selection | Downloaded form behavior |
| --- | --- |
| Nothing | Normal form with no defaults |
| Property only | Only Property is preselected |
| Department only | Only Department is preselected and its dynamic fields render |
| Property and Department | Both are preselected |
| Property, Restaurants, and Restaurant | All three are preselected |
| Property, Banquets, and Banquet | All three are preselected |

Restaurant and Banquet options require a Property because they are property
scoped. Selecting one department displays the matching dependent control;
other departments do not show a third control. Changing Property or Department
clears the old dependent selection.

The modal has no top-right close button, uses a static backdrop, and disables
Escape-key closing. A successful download leaves it open. Only the explicit
`Cancel` button closes and resets the modal.

The downloaded form applies defaults only after its asynchronous lookups have
finished. It loads and selects Property and Department first, renders the
department branch, loads the dependent venue list, and then selects the saved
Restaurant or Banquet. All preselected controls remain editable.

## Authentication and exposure

- The `download-lead-form` controller route is protected by the Super Admin
  session check in `Main::__construct()`.
- The root-level source file, `leadform.html`, is also directly reachable as a
  static web file when the web server allows normal document-root access.
- The lookup and save endpoints are callable without a logged-in application
  session so the downloaded form can operate on another website.
- These public endpoints send `Access-Control-Allow-Origin: *` so cross-origin
  forms can call the application.

If the form source itself must be restricted, storing it in the public document
root conflicts with that requirement. Moving or protecting it would need to be
handled separately from the controller download authorization.

## Form fields

### Common fields

The form always shows:

- Full Name
- Email Address
- Phone Number
- Property
- Department
- Comments / Query

Property and Department are disabled while their lookup requests are loading.
The client displays an error option and leaves the control disabled if a lookup
fails.

### Rooms

Selecting `Rooms` adds:

- check-in date
- check-out date
- number of rooms
- number of pax
- purpose

The browser requires check-in to be today or later and check-out to be after
check-in. Room count and pax must be positive whole numbers.

### Restaurants

Selecting `Restaurants` adds:

- restaurant for the selected property
- booking date
- number of pax
- purpose

Restaurant options are loaded from `api/restaurant-list` using the selected
property ID. Changing the property rebuilds the department fields and reloads
the dependent restaurant list.

### Banquets

Selecting `Banquets` adds:

- banquet for the selected property
- `Is Room Required?`
- conditional check-in and check-out dates
- booking date
- number of pax
- purpose

Banquet options are loaded from `api/banquet-list`. When room requirement is
enabled, check-in and check-out become visible and required. Check-out may be
the same day as check-in for this branch. Turning room requirement off clears
both dates before submission.

### Purpose values

The current controlled values are:

- Corporate
- Family
- Vacation
- Leisure
- Social
- Wedding
- Pilgrimage

Dynamic rendering depends on the normalized department names `rooms`,
`restaurant`/`restaurants`, and `banquet`/`banquets`. A new or renamed
department will not receive specialized fields until it is explicitly handled
in `renderDepartmentFields()` and in server validation.

## Lookup APIs

| Method | Route | Request | Result used by the form |
| --- | --- | --- | --- |
| GET | `api/property-list` | None | Active, non-deleted properties |
| GET | `api/department-list` | None | Non-deleted departments |
| POST | `api/restaurant-list` | JSON `hotel_id` | Active, non-deleted restaurants for the property |
| POST | `api/banquet-list` | JSON `hotel_id` | Non-deleted banquets for the property |
| POST | `api/save-lead` | Lead JSON payload | Creates or updates a recent lead |

Property and department options display their names, retain their numeric IDs
in `data-id`, and submit the IDs to the API. Restaurant and banquet options use
their numeric IDs as option values.

## Submission payload

The form sends JSON to `api/save-lead`.

Common payload fields:

```text
name
email
phone
property          numeric property ID
department        numeric department ID
query             comments/query text
```

Conditional payload fields can include:

```text
checkin_date
checkout_date
number_of_rooms
booking_date
pax
purpose
restaurant_id
banquet_id
is_room_required
```

After a successful response, the browser displays the success message, resets
the common controls, and removes the department-specific fields.

## Server processing

The current `API::save_lead()` flow is:

1. Answer CORS preflight requests.
2. Decode the JSON request body.
3. Reject array/object values, invalid control characters, HTML tags, and
   selected script/event-handler patterns in accepted fields.
4. Require name, phone, property, and department.
5. Resolve an active property and a non-deleted department from the database.
6. For Restaurants, confirm that the restaurant is active and belongs to the
   selected property.
7. For Banquets, confirm that the banquet belongs to the property. When rooms
   are required, validate the two stay dates.
8. Map the request into the `leads` table fields and set the source to
   `Website` when no source is supplied.
9. Apply the recent-duplicate rule.
10. Insert through `LeadModel::insert_lead()` when no duplicate exists.
11. Trigger the agent WhatsApp notification method for a newly inserted lead.
12. Return a JSON success or failure response.

Important field mapping:

| Form/API field | `leads` column |
| --- | --- |
| `name` | `user_name` |
| `phone` | `phone_number` |
| `query` | `query` |
| `property` | `property` |
| `department` | `type` |
| `user_channel`/default | `user_channel` |

New leads are created with status `Open`, plus created date/time and the
request IP address.

## Duplicate handling

The public save endpoint searches for an existing non-Closed lead when:

- the last 10 digits of the phone number match;
- the property matches; and
- the lead was created within the previous two hours.

When both check-in and check-out dates are present, those dates are also added
to the lookup. If a match is found, the existing lead is updated instead of a
new lead being inserted. The original `created_at` is preserved, while other
submitted fields and the separate `date`/`time` values are updated.

Restaurant and ordinary Banquet enquiries usually do not include both stay
dates. Their booking date, department, restaurant, and banquet are not part of
the duplicate query, so another enquiry from the same phone/property within
two hours can update the earlier record.

## Current review findings

These findings describe the implementation as reviewed on 5 August 2026. They
are not evidence that a change has already been made.

### High priority

1. The public save endpoint has no rate limiting, CAPTCHA, API token, or other
   anti-automation control. Public deployment can therefore receive automated
   spam or repeated notification-triggering requests.
2. Server validation is weaker than browser validation. Email, comments, phone
   format, purpose, pax, Rooms dates/room count, and ordinary booking dates are
   not fully revalidated by the server. A direct API caller can bypass the HTML
   rules.
3. The two-hour duplicate rule can overwrite a separate valid enquiry,
   particularly for Restaurant or Banquet submissions that share a phone and
   property.

### Medium priority

1. The static source `leadform.html` can be requested directly even though the
   sidebar download action is Super Admin-only.
2. The live Property list currently contains multiple indistinguishable
   `Playotel Premier Ujjain` entries. The numeric IDs differ, but the user sees
   the same label.
3. `API::response()` accepts only status, message, and data. Calls that pass a
   fourth HTTP status argument do not set the actual HTTP response code, so a
   validation failure can still be transported as HTTP 200 with
   `status: false` in the JSON body.

### Low priority

1. Date-selection constraints are inconsistent in the HTML. Banquet room dates
   receive a `min` attribute, while Rooms and booking dates rely on an error
   after selection.
2. The form depends on Bootstrap CSS from a public CDN, so the downloaded file
   is not completely self-contained and its styling requires network access.

## Verified behavior

During the 5 August 2026 review and optional-default implementation:

- the changed PHP files passed syntax checks;
- the modal and standalone-form JavaScript passed syntax checks;
- the standalone form loaded in the local browser without console errors;
- Property and Department lookups populated;
- the normal no-default fallback opened with no preselection;
- Property-only and Department-only defaults applied independently;
- complete Property + Restaurants + Restaurant defaults applied in order;
- complete Property + Banquets + Banquet defaults applied in order;
- no form was submitted and no lead/test record was created;
- the signed-in Super Admin modal interaction still requires verification in an
  authenticated role session.

## Safe change procedure

Before changing this module:

1. Confirm whether the requested change affects only the downloaded HTML, the
   public API, or both.
2. Preserve the single `const BASE_URL` declaration required by the download
   controller, or update the controller and template together.
3. Repeat every required browser rule in `API::save_lead()`; server validation
   is the authority.
4. Resolve posted property, department, restaurant, and banquet IDs against
   active records and their parent property.
5. Review duplicate behavior explicitly whenever payload identity or booking
   fields change.
6. Consider external hosting and CORS before restricting origins or changing
   request headers.
7. Avoid sending real notifications during testing unless submission testing
   is authorized.
8. Update this document whenever routes, payloads, validation, lookup rules,
   duplicate handling, or exposure changes.

## Regression checklist

### Download

- Super Admin sidebar item opens the optional-default modal.
- Download works with no selections and produces the normal form.
- Property-only and Department-only defaults apply independently.
- Property + Department defaults apply together.
- Restaurant and Banquet controls appear only for their departments.
- Changing Property or Department clears an old dependent selection.
- Complete Restaurant and Banquet selections apply after dependent lookups.
- Invalid or stale optional IDs are omitted without blocking the download.
- The modal does not close from its backdrop, the Escape key, or a completed
  download; only Cancel closes it.
- Signed-out users cannot use the controller download route.
- The downloaded file contains the current application base URL exactly once.
- The downloaded file contains exactly one defaults configuration.
- A second download from the same page works after CSRF regeneration.
- Missing/unreadable source and failed replacement return controlled errors.

### Initial form

- Property and Department show loading states and then populate.
- Lookup failure leaves a clear error option.
- Common required fields show field-specific errors.
- Valid and invalid Indian mobile numbers behave as expected.
- Email and comments respect their intended rules.

### Rooms

- Past check-in is rejected.
- Check-out must be after check-in.
- Room count and pax accept positive whole numbers only.
- Purpose is required and submitted.

### Restaurants

- Changing property reloads the restaurant list.
- Only restaurants belonging to the property are accepted by the server.
- Past booking dates are rejected by both browser and server.
- Pax and purpose are validated by both browser and server.

### Banquets

- Changing property reloads the banquet list.
- Only banquets belonging to the property are accepted by the server.
- Room dates remain hidden and absent when room requirement is off.
- Enabling room requirement makes both dates required.
- Disabling room requirement clears both dates.

### Submission and persistence

- Invalid direct API requests return the intended HTTP status and JSON body.
- A valid request creates one lead with the correct IDs and dynamic values.
- Duplicate behavior matches the agreed business rule for every department.
- A newly inserted lead triggers the intended notification once.
- The form resets only after confirmed persistence.
- Network and non-JSON server errors produce a usable message.

## Fast orientation for a future session

Read in this order:

1. This document.
2. `application/views/super_admin/include/sidebar.php` for the entry point.
3. `application/views/super_admin/include/download_lead_form_modal.php` for
   optional selection and download behavior.
4. `application/config/routes.php` for download and API mappings.
5. `application/controllers/superAdmin/Main.php::download_lead_form()`.
6. `leadform.html` for defaults, UI, validation, payload, and lookup behavior.
7. `application/controllers/API.php::save_lead()` and the four lookup methods.
8. `application/models/API_Model.php` and
   `application/models/LeadModel.php::insert_lead()` for database behavior.
