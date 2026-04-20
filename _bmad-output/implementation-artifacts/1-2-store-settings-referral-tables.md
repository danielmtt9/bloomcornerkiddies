# Story 1.2: Store Settings & Referral Tables

Status: ready-for-dev

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

- [ ] Task 1 — Define seller config and referral schema (AC: 1, 4, 5)
  - [ ] Model `seller_config`, `referral_codes`, and `referral_uses`.
  - [ ] Enforce uniqueness and snapshot behavior.
- [ ] Task 2 — Add seeds and defaults (AC: 2, 3)
  - [ ] Seed the full key set with placeholder-safe values where needed.
  - [ ] Keep open business copy configurable rather than hardcoded.
- [ ] Task 3 — Verify table and seed behavior (AC: 1, 2, 3, 4, 5)
  - [ ] Test/verify uniqueness and baseline rows.
  - [ ] Record evidence in this story.

## Dev Notes

- seller_config contract source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:79)
- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:215)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

### Completion Notes List

### File List

### Change Log

- Story created from Lane A sprint plan.
