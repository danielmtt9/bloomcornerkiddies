# Story 2.2: Session Guard & Logout

Status: done

## Story

As a seller,
I want all admin routes protected by one shared guard,
so that the admin area behaves as one secure application.

## Acceptance Criteria

1. Every protected admin page uses a shared session/auth guard.
2. Unauthenticated requests redirect to `/admin/login.php`.
3. `/admin/logout.php` destroys the session and redirects to login.
4. Back-button access after logout does not restore admin content.

## Tasks / Subtasks

- [x] Task 1 — Implement the shared auth guard (AC: 1, 2)
  - [x] Create/reuse one guard include for protected admin routes.
  - [x] Standardize redirect behavior.
- [x] Task 2 — Implement logout flow (AC: 3, 4)
  - [x] Destroy session state safely.
  - [x] Ensure post-logout navigation does not leak protected content.
- [x] Task 3 — Verify route protection (AC: 1, 2, 3, 4)
  - [x] Test unauthenticated, authenticated, and post-logout flows.
  - [x] Record verification in this story.

## Dev Notes

- Build on shared PHP bootstrap helpers from `0-3`.
- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:276)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

- `php -l public_html/admin/logout.php`
- `npm run test-admin-auth`
- `npm run test-admin-dashboard`

### Completion Notes List

- Centralized protected-route behavior in `public_html/includes/admin.php` so admin pages share one auth guard and one redirect target.
- Added `/admin/logout.php` to clear session state, expire the session cookie, and redirect back to login.
- Applied no-cache headers on protected routes and logout flow so back-button navigation does not restore cached admin content after logout.
- Protected placeholder routes for referrals and settings now use the same shared guard, proving the admin area behaves as one application.

### File List

- public_html/includes/admin.php
- public_html/admin/logout.php
- public_html/admin/referrals.php
- public_html/admin/settings.php
- scripts/test-admin-auth.php
- scripts/test-admin-dashboard.php
- package.json
- _bmad-output/implementation-artifacts/2-2-session-guard-logout.md

### Change Log

- Story created from Lane A sprint plan.
- Implemented shared admin route protection and logout flow.
