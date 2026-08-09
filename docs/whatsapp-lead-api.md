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

| WhatsAppJet field | Lead behavior |
| --- | --- |
| `name` | `user_name` |
| `phone` (fallback `contact_wa_id`) | `phone_number` |
| `location` | Active city by `city_name`; stores city/state/country IDs from that row |
| `property` | Active hotel by exact `hotel_name`; must belong to the resolved city |
| `service` | Department name → `type`; blank defaults to `Restaurants`; unknown falls back to department id `2` |
| `guests` | `pax`, and included in `query` as `Guests: 2` |
| `date` | `booking_date`, and included in `query` as `Date: 2026-08-08` |
| `time` | Stored in `time`, and included in `query` as `Time: Breakfast` |
| Bot `status` | Ignored; lead `status` is always `Open` |
| — | `user_channel` = `WhatsApp` |
| `reservation_uid` | Stored in `remark` and appended in `query` |

Example `query` string:

`Date: 2026-08-08 | Time: Breakfast | Guests: 2 | Reservation UID: ...`

Required: `name`, phone (`phone` or `contact_wa_id`), `location`, `property`.

## Save behavior

1. Validate Bearer token.
2. Resolve location, property, and department against active (`is_deleted = 0`) records.
3. Reject if property city does not match location city.
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
