# Story 4.4: Admin Password Change

Status: done

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

- [x] Task 1 — Add password change form and validation (AC: 1, 2)
  - [x] Require current password verification.
  - [x] Validate new password + confirm fields.
- [x] Task 2 — Implement secure hash update flow (AC: 3, 4)
  - [x] Store only the new hash.
  - [x] Avoid exposing plain-text values in logs or output.
- [x] Task 3 — Verify password change behavior (AC: 1, 2, 3, 4)
  - [x] Add smoke/test coverage.
  - [x] Record results in this story.

## Dev Notes

- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:517)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

- `php -l public_html/includes/settings.php`
- `npm run test-admin-settings`

### Completion Notes List

- Added current-password, new-password, and confirm-password fields to the settings page.
- Password change now verifies the current password, hashes the new password with bcrypt, and updates only `ADMIN_PASSWORD_HASH` in the root `.env` file.
- Fixed env update behavior so bcrypt hashes are written intact without exposing plain-text passwords in output or persistence.

### File List

- public_html/admin/settings.php
- public_html/includes/settings.php
- scripts/test-admin-settings.php
- package.json
- _bmad-output/implementation-artifacts/4-4-admin-password-change.md

### Change Log

- Story created from Lane A sprint plan.
- Implemented admin password change through secure hash update in the root env file.
