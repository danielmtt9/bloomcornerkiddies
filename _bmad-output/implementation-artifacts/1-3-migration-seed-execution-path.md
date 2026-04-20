# Story 1.3: Migration & Seed Execution Path

Status: done

## Story

As a developer,
I want a repeatable migration and seed path for local and production setup,
so that schema setup is deterministic and verifiable.

## Acceptance Criteria

1. Migration script(s) create all MVP tables in correct dependency order.
2. Seed script(s) insert baseline categories and seller_config rows safely.
3. Local DB test script can confirm connectivity and list existing tables.

## Tasks / Subtasks

- [x] Task 1 — Align migration tooling with the updated schema (AC: 1, 2)
  - [x] Update scripts and SQL references to current Lane A schema.
  - [x] Ensure dependency order is correct.
- [x] Task 2 — Ensure seed execution is safe and repeatable (AC: 2)
  - [x] Prevent broken partial seed assumptions.
  - [x] Keep seeds compatible with fresh and non-fresh flows where intended.
- [x] Task 3 — Verify execution path locally (AC: 1, 2, 3)
  - [x] Run the DB test and migration path against the configured DB where safe.
  - [x] Record the exact commands and results in this story.

## Dev Notes

- Existing scripts: [scripts/migrate.mjs](/home/danielaroko/applications/bloomrocxx/scripts/migrate.mjs:1), [scripts/test-connection.mjs](/home/danielaroko/applications/bloomrocxx/scripts/test-connection.mjs:1)
- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:242)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

- `npm run test-db`
- `npm run migrate -- --fresh`
- `npm run test-schema`
- `npm run migrate -- --seed-only`
- `npm run test-schema`

### Completion Notes List

- Reworked the migration runner to split schema and seed sections deterministically instead of relying on fragile statement splitting.
- Updated the drop order for `--fresh` to respect the MVP foreign key dependency graph.
- Added `scripts/verify-schema.mjs` plus `npm run test-schema` so the execution path validates tables, category seeds, seller config seeds, and multi-category constraints directly against MariaDB.
- Verified the configured Hostinger DB connection, a full fresh migration, and a repeat seed-only run all pass cleanly.

### File List

- scripts/migrate.mjs
- scripts/test-connection.mjs
- scripts/verify-schema.mjs
- package.json
- database/schema.sql
- _bmad-output/implementation-artifacts/1-3-migration-seed-execution-path.md

### Change Log

- Story created from Lane A sprint plan.
- Implemented and verified the repeatable Lane A migration and seed execution path.
