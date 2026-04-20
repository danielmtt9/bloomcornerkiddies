# Story 2.1: Admin Login

Status: ready-for-dev

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

- [ ] Task 1 — Implement the login page and POST flow (AC: 1, 2, 3, 4)
  - [ ] Render a password-only form.
  - [ ] Verify against the configured password hash.
- [ ] Task 2 — Establish secure session behavior (AC: 3, 5)
  - [ ] Set authenticated session state correctly.
  - [ ] Respect HTTPS production expectations.
- [ ] Task 3 — Add verification coverage (AC: 1, 2, 3, 4, 5)
  - [ ] Add PHP-level tests or smoke coverage for login success/failure.
  - [ ] Record results in this story.

## Dev Notes

- Depends on config bootstrap and DB/config contract.
- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:258)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

### Completion Notes List

### File List

### Change Log

- Story created from Lane A sprint plan.
