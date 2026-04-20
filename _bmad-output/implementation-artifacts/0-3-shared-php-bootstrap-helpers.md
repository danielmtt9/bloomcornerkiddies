# Story 0.3: Shared PHP Bootstrap Helpers

Status: done

## Story

As a developer,
I want shared PHP bootstrap helpers for admin, API, and storefront entrypoints,
so that application code does not duplicate config, session, or response logic.

## Acceptance Criteria

1. Shared include(s) exist for session start, auth guard, JSON responses, and flash/session helpers where needed.
2. DB access flows through one PDO singleton.
3. Application code can read `seller_config` through a shared helper rather than raw repeated queries.

## Tasks / Subtasks

- [x] Task 1 — Design the shared bootstrap/include surface (AC: 1, 2, 3)
  - [x] Choose the helper file layout under the existing PHP project structure.
  - [x] Reuse `get_db()` and `get_config()` from the root config contract.
- [x] Task 2 — Implement shared helpers (AC: 1, 2, 3)
  - [x] Add helper(s) for session bootstrap and auth guard.
  - [x] Add helper(s) for JSON responses and flash/session messaging.
- [x] Task 3 — Verify helper usage is reusable (AC: 1, 2, 3)
  - [x] Add smoke coverage or syntax checks for the new include surface.
  - [x] Record changed files and commands in this story.

## Dev Notes

- This story depends on [0-1-environment-config-bootstrap.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/implementation-artifacts/0-1-environment-config-bootstrap.md:1).
- Acceptance source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:240)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

- `php -l public_html/includes/bootstrap.php`
- `php -l scripts/test-php-bootstrap.php`
- `npm run test-php-bootstrap`
- `npm run test-config`
- `npm run test-deploy`

### Completion Notes List

- Added a shared include surface at `public_html/includes/bootstrap.php` that reuses the root `config.php` contract and exposes session start, admin guard, JSON response, and flash helpers.
- Kept DB access and seller config access centralized through the existing `get_db()` and `get_config()` functions from the root config contract.
- Added `scripts/test-php-bootstrap.php` and `npm run test-php-bootstrap` to prove the helper surface works in isolation from a temp bootstrap fixture.
- Re-ran helper validation during Sprint 1 closeout to confirm the shared include layer still works against the finalized config contract.

### File List

- public_html/includes/bootstrap.php
- scripts/test-php-bootstrap.php
- package.json
- _bmad-output/implementation-artifacts/0-3-shared-php-bootstrap-helpers.md

### Change Log

- Story created from Lane A sprint plan.
- Implemented shared PHP bootstrap helpers and executable smoke-test coverage.
- Story revalidated and closed during Sprint 1 completion.
