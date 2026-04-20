# Story 4.2: Seller Status Control

Status: ready-for-dev

## Story

As a seller,
I want to update live availability state,
so that storefront status indicators stay truthful.

## Acceptance Criteria

1. Admin provides status values `online`, `brb`, and `offline`.
2. Status changes persist to `seller_config.seller_status`.
3. Optional status message is saved alongside status changes.
4. Storefront can reflect the latest value within its polling interval.

## Tasks / Subtasks

- [ ] Task 1 — Implement status controls in admin (AC: 1, 2, 3)
  - [ ] Add status UI and status-message editing.
  - [ ] Persist updates correctly.
- [ ] Task 2 — Verify storefront-facing contract (AC: 4)
  - [ ] Ensure updated values are available for polling/API use.
- [ ] Task 3 — Add verification coverage (AC: 1, 2, 3, 4)
  - [ ] Add smoke/test validation.
  - [ ] Record results in this story.

## Dev Notes

- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:478)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

### Completion Notes List

### File List

### Change Log

- Story created from Lane A sprint plan.
