# WhatsApp Lead API

Last reviewed: 9 August 2026

## Purpose

WhatsAppJet integration APIs for this LMS:

1. Catalog GETs for locations/properties and departments (required before flow work)
2. Lead save webhook for hotel-reservation confirm push

Incoming lead JSON is validated with a Bearer token, mapped to a lead, and saved
with the same core insert/update behavior as `LeadController::receive_lead()`,
without changing that legacy endpoint.

## Files

| File | Responsibility |
| --- | --- |
| `application/controllers/WhatsappLeadApi.php` | CORS, Bearer auth, catalog GETs, payload mapping, responses |
| `application/models/WhatsappLead_model.php` | Soft-delete-aware lookups, catalog queries, duplicate check, insert/update |
| `application/config/routes.php` | WhatsApp API routes |
| `.env` / `env` | `WHATSAPP_LEAD_API_TOKEN` shared secret |

## Common contract

| Item | Value |
| --- | --- |
| Auth | `Authorization: Bearer <WHATSAPP_LEAD_API_TOKEN>` |
| Accept | `application/json` |
| Success | `{ "status": true, "message": "OK", "data": ... }` |
| Fail | `{ "status": false, "message": "...", "data": [] }` |
| Unauthorized | HTTP `401` |

## Catalog API 1 — Locations with properties

| Item | Value |
| --- | --- |
| Method | `GET` |
| Route | `api/whatsapp/catalog/locations-with-properties` |

Response shape:

```json
{
  "status": true,
  "message": "OK",
  "data": [
    {
      "id": "8",
      "name": "Indore",
      "properties": [
        {
          "id": "1",
          "title": "Playotel Premier, Vijay Nagar, Indore",
          "description": "Playotel Premier, Vijay Nagar, Indore",
          "image_url": "http://localhost/pravasahotels/uploads/hotel_images/...."
        }
      ]
    }
  ]
}
```

- `name` is city name for `save-lead` `location`
- `title` is exact hotel name for `save-lead` `property`

## Catalog API 2 — Departments (global)

| Item | Value |
| --- | --- |
| Method | `GET` |
| Route | `api/whatsapp/catalog/departments` |

No `property_id` query. Returns the global WhatsJet department list.

Response shape:

```json
{
  "status": true,
  "message": "OK",
  "data": [
    { "id": "1", "name": "Rooms", "code": "rooms" },
    { "id": "2", "name": "Restaurants", "code": "restaurants" },
    { "id": "3", "name": "Banquets", "code": "banquets" }
  ]
}
```

Important: `name` values stay exactly `Rooms`, `Restaurants`, `Banquets` for WhatsJet flow routing / `save-lead` `service`.

## Catalog API 3 — Restaurants by property

| Item | Value |
| --- | --- |
| Method | `GET` |
| Route | `api/whatsapp/catalog/restaurants?property_id={lms_property_id}` |
| Auth | Bearer (same catalog token) |

Required query: `property_id` (LMS hotel id from WhatsJet session).

Response shape:

```json
{
  "status": true,
  "message": "OK",
  "data": [
    { "id": "17", "title": "PlayDine" },
    { "id": "20", "title": "Divine Cafe" }
  ]
}
```

| Field | Required | Notes |
| --- | --- | --- |
| query `property_id` | yes | LMS property id |
| `data[].id` | yes | LMS restaurant id |
| `data[].title` | yes | WhatsApp / Flow dropdown label |

Inactive or unknown property → `422` with fail envelope.

## Catalog API 4 — Dining schedule

| Item | Value |
| --- | --- |
| Method | `GET` |
| Route | `api/whatsapp/catalog/dining-schedule` |
| Auth | Bearer (same catalog token) |

Builds nested slot types → time slots from active, non-deleted rows only.

- Type `title` = `slot_types.slot_name`
- Slot `title` = formatted `start_time - end_time` (e.g. `06:00 AM - 07:00 AM`)
- No `code` field

```json
{
  "status": true,
  "message": "OK",
  "data": [
    {
      "id": "1",
      "title": "Break Fast",
      "slots": [
        {
          "id": "6",
          "title": "06:00 AM - 07:00 AM",
          "start_time": "06:00:00",
          "end_time": "07:00:00"
        }
      ]
    }
  ]
}
```

## Lead save endpoint

| Item | Value |
| --- | --- |
| Method | `POST` |
| Route | `api/whatsapp/save-lead` |
| Content-Type | `application/json` |

Example local URL:

`http://localhost/pravasahotels/api/whatsapp/save-lead`

## Bearer token

1. Generate a long random secret (32–64 characters).
2. Set it in `.env`:

```env
WHATSAPP_LEAD_API_TOKEN=your-long-random-secret
```

3. Paste the same value into WhatsAppJet’s **Bearer Token** field (without the
   word `Bearer`).
4. Anyone with this token can call the endpoint. Rotate it immediately if leaked.

## Sample WhatsAppJet payload

```json
{
  "event": "hotel_reservation.confirmed",
  "reservation_uid": "...",
  "vendor_uid": "...",
  "location": "Indore",
  "property": "Jardin Hotels - Teen Imli Indore",
  "service": "Restaurants",
  "date": "2026-08-08",
  "time": "Breakfast",
  "guests": 2,
  "name": "Sid",
  "phone": "917879553819",
  "contact_wa_id": "917879553819",
  "status": "confirmed",
  "raw_flow_response": {}
}
```

## Field mapping

ID fields are preferred (environment DB ids from catalog). Names are fallback.

| WhatsAppJet field | Lead behavior |
| --- | --- |
| `name` | `user_name` |
| `phone` (fallback `contact_wa_id`) | `phone_number` |
| `location_id` / `location` | Active city by id, else name; stores city/state/country |
| `property_id` / `property` | Active hotel by id, else name; must belong to resolved city |
| `department_id` / `service` | Active department by id, else name (`Restaurants` default / id `2` fallback for name path) |
| `restaurant_id` / `restaurant` | Active restaurant under property → `restaurant_id` |
| `slot_type_id` | Active slot type → `slot_type_id` (Reserve Table field) |
| `time_slot_id` | Active time slot under that slot type → `time_slot_id` |
| `guests` | `pax` + `query` |
| `date` / `checkin_date` | `booking_date` (+ `checkin_date` when sent) |
| `checkout_date` | `checkout_date` when sent |
| `time` | `time` and `arrival_time` |
| `meal` | Included in `query` |
| `occasion` | `special_occasion` + `query` |
| `special_requests` / `special_requirement` | `special_request` + `query` |
| `queries` | Appended in `query` |
| Bot `status` | Ignored; lead `status` is always `Open` |
| — | `user_channel` = `WhatsApp` |
| `reservation_uid` | `remark` + `query` |

Example `query` string:

`Date: 2026-08-10 | Time: 20:00 | Meal: Dinner | Guests: 4 | Restaurant: Rooftop Restaurant | Occasion: Birthday | Special request: Window seat | Reservation UID: ...`

Required: `name`, phone, and (`location_id` or `location`), and (`property_id` or `property`).

## Save behavior

1. Validate Bearer token.
2. Resolve location/property/department/restaurant by id first (active, `is_deleted = 0`), else by name.
3. Reject if property city does not match location city, or restaurant is not under that property.
4. Set escalation from department level-1 hours.
5. Duplicate window: same last-10 phone digits, created within 2 hours, not Closed, not soft-deleted → update existing lead.
6. Otherwise insert a new lead.
7. On insert only, fire-and-forget `EmailWorker/sendLeadEmail/{id}/{valuableFlag}`.

## Example request

```bash
curl -X POST "http://localhost/pravasahotels/api/whatsapp/save-lead" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer your-long-random-secret" \
  -d "{\"event\":\"hotel_reservation.confirmed\",\"location\":\"Indore\",\"property\":\"Jardin Hotels - Teen Imli Indore\",\"service\":\"Restaurants\",\"date\":\"2026-08-08\",\"time\":\"Breakfast\",\"guests\":2,\"name\":\"Sid\",\"phone\":\"917879553819\",\"status\":\"confirmed\"}"
```

## Scope note

This module is intentionally isolated. Do not change `LeadController::receive_lead()`
or other lead role modules when maintaining this webhook.
