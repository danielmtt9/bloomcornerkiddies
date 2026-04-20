# Story 1.2: Store Settings & Referral Tables

Status: done

## Story

As a developer,
I want seller config and referral tracking tables created,
so that admin settings and referral history can be persisted without hardcoded values.

## Acceptance Criteria

1. `seller_config`, `referral_codes`, and `referral_uses` tables exist.
2. `seller_config` is seeded with the full MVP key set:
   `store_name`, `tagline`, `intro_text`, `wa_number`, `telegram_link`, `delivery_info`, `status_message`, `payment_info`, `seller_status`, `referral_discount_percent`.
3. Placeholder defaults are inserted for open business-copy values.
4. `referral_codes.code` is unique.
5. `referral_uses` snapshots discount percent at redemption time.

## Tasks / Subtasks

- [x] Task 1 — Define seller config and referral schema (AC: 1, 4, 5)
  - [x] Model `seller_config`, `referral_codes`, and `referral_uses`.
  - [x] Enforce uniqueness and snapshot behavior.
- [x] Task 2 — Add seeds and defaults (AC: 2, 3)
  - [x] Seed the full key set with placeholder-safe values where needed.
  - [x] Keep open business copy configurable rather than hardcoded.
- [x] Task 3 — Verify table and seed behavior (AC: 1, 2, 3, 4, 5)
  - [x] Test/verify uniqueness and baseline rows.
  - [x] Record evidence in this story.

## Dev Notes

- seller_config contract source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:79)
- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:215)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

- `npm run migrate -- --fresh`
- `npm run migrate -- --seed-only`
- `npm run test-schema`

### Completion Notes List

- Added `seller_config`, `referral_codes`, and `referral_uses` to the MVP schema with uniqueness on `seller_config.key` and `referral_codes.code`.
- Seeded the full required `seller_config` key set with placeholder-safe defaults, including a placeholder `telegram_link` per the locked MVP decision.
- Preserved referral redemption snapshot behavior by storing `discount_percent` on `referral_uses` at insert time instead of deriving it later.
- Verified the seeded rows remain stable across repeat seed runs.

### File List

- database/schema.sql
- scripts/migrate.mjs
- scripts/verify-schema.mjs
- package.json
- _bmad-output/implementation-artifacts/1-2-store-settings-referral-tables.md

### Change Log

- Story created from Lane A sprint plan.
- Implemented store settings and referral persistence with repeatable baseline seeds.
