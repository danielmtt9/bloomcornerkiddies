# Story 2.2: Session Guard & Logout

Status: ready-for-dev

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

- [ ] Task 1 — Implement the shared auth guard (AC: 1, 2)
  - [ ] Create/reuse one guard include for protected admin routes.
  - [ ] Standardize redirect behavior.
- [ ] Task 2 — Implement logout flow (AC: 3, 4)
  - [ ] Destroy session state safely.
  - [ ] Ensure post-logout navigation does not leak protected content.
- [ ] Task 3 — Verify route protection (AC: 1, 2, 3, 4)
  - [ ] Test unauthenticated, authenticated, and post-logout flows.
  - [ ] Record verification in this story.

## Dev Notes

- Build on shared PHP bootstrap helpers from `0-3`.
- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:276)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

### Completion Notes List

### File List

### Change Log

- Story created from Lane A sprint plan.
