# Project Instructions

## Soft-deleted records

- Records marked as deleted must never appear in active lists, dropdowns, filters, searches, autocomplete results, assignment options, or lookup endpoints.
- Every query used to populate an active interface must apply the relevant soft-delete condition, normally `is_deleted = 0`.
- Keep soft-deleted records in the database unless the user explicitly requests permanent deletion. Do not restore, expose, or make them selectable implicitly.
- Apply this rule consistently across Super Admin, Hotel Admin, Agent, Sales, APIs, and any future sections.
