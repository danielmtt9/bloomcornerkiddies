# Story 4.4: Admin Password Change

Status: ready-for-dev

## Story

As a seller,
I want to change the admin password without editing server files manually,
so that admin security maintenance is practical for a single operator.

## Acceptance Criteria

1. Settings page includes current password, new password, and confirm fields.
2. Current password must verify before updating.
3. New password is stored as a one-way cryptographic hash.
4. Plain-text password is never persisted or displayed.

## Tasks / Subtasks

- [ ] Task 1 — Add password change form and validation (AC: 1, 2)
  - [ ] Require current password verification.
  - [ ] Validate new password + confirm fields.
- [ ] Task 2 — Implement secure hash update flow (AC: 3, 4)
  - [ ] Store only the new hash.
  - [ ] Avoid exposing plain-text values in logs or output.
- [ ] Task 3 — Verify password change behavior (AC: 1, 2, 3, 4)
  - [ ] Add smoke/test coverage.
  - [ ] Record results in this story.

## Dev Notes

- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:517)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

### Completion Notes List

### File List

### Change Log

- Story created from Lane A sprint plan.
