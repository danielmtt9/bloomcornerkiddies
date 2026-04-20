# Story 2.1: Admin Login

Status: done

## Story

As a seller,
I want to log in using one password,
so that only I can enter the admin area.

## Acceptance Criteria

1. `/admin/login.php` displays a password-only login form.
2. Submitted password is verified against `ADMIN_PASSWORD_HASH`.
3. Successful login creates an authenticated PHP session and redirects to `/admin/index.php`.
4. Failed login shows an error and creates no session.
5. Login route works only over HTTPS in production.

## Tasks / Subtasks

- [x] Task 1 — Implement the login page and POST flow (AC: 1, 2, 3, 4)
  - [x] Render a password-only form.
  - [x] Verify against the configured password hash.
- [x] Task 2 — Establish secure session behavior (AC: 3, 5)
  - [x] Set authenticated session state correctly.
  - [x] Respect HTTPS production expectations.
- [x] Task 3 — Add verification coverage (AC: 1, 2, 3, 4, 5)
  - [x] Add PHP-level tests or smoke coverage for login success/failure.
  - [x] Record results in this story.

## Dev Notes

- Depends on config bootstrap and DB/config contract.
- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:258)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

- `php -l public_html/includes/admin.php`
- `php -l public_html/admin/login.php`
- `php -l scripts/test-admin-auth.php`
- `npm run test-admin-auth`

### Completion Notes List

- Added `/admin/login.php` as a password-only login form backed by `ADMIN_PASSWORD_HASH`.
- Added shared admin auth helpers for password verification, session renewal, timeout handling, and HTTPS redirect calculation.
- Successful login now creates an authenticated PHP session and redirects to `/admin/index.php`; failed login renders an inline error without creating session state.
- Production HTTP login requests now calculate an HTTPS redirect target to keep the login route HTTPS-only.

### File List

- public_html/includes/admin.php
- public_html/admin/login.php
- scripts/test-admin-auth.php
- package.json
- _bmad-output/implementation-artifacts/2-1-admin-login.md

### Change Log

- Story created from Lane A sprint plan.
- Implemented admin login flow and auth smoke coverage.
