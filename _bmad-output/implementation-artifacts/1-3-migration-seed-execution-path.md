# Story 1.3: Migration & Seed Execution Path

Status: ready-for-dev

## Story

As a developer,
I want a repeatable migration and seed path for local and production setup,
so that schema setup is deterministic and verifiable.

## Acceptance Criteria

1. Migration script(s) create all MVP tables in correct dependency order.
2. Seed script(s) insert baseline categories and seller_config rows safely.
3. Local DB test script can confirm connectivity and list existing tables.

## Tasks / Subtasks

- [ ] Task 1 — Align migration tooling with the updated schema (AC: 1, 2)
  - [ ] Update scripts and SQL references to current Lane A schema.
  - [ ] Ensure dependency order is correct.
- [ ] Task 2 — Ensure seed execution is safe and repeatable (AC: 2)
  - [ ] Prevent broken partial seed assumptions.
  - [ ] Keep seeds compatible with fresh and non-fresh flows where intended.
- [ ] Task 3 — Verify execution path locally (AC: 1, 2, 3)
  - [ ] Run the DB test and migration path against the configured DB where safe.
  - [ ] Record the exact commands and results in this story.

## Dev Notes

- Existing scripts: [scripts/migrate.mjs](/home/danielaroko/applications/bloomrocxx/scripts/migrate.mjs:1), [scripts/test-connection.mjs](/home/danielaroko/applications/bloomrocxx/scripts/test-connection.mjs:1)
- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:242)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

### Completion Notes List

### File List

### Change Log

- Story created from Lane A sprint plan.
