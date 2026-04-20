# Story 2.3: Admin Layout & Dashboard

Status: done

## Story

As a seller,
I want to navigate the admin area from a phone without friction,
so that the admin panel has a reusable shell and a useful landing page.

## Acceptance Criteria

1. Shared admin header appears on all admin pages.
2. Nav links include Products, Referrals, Settings, and Logout.
3. Dashboard shows total products, sold-out product count, total referral codes, and recently updated products.
4. Layout is usable on a 375px-wide mobile screen with no horizontal scrolling.

## Tasks / Subtasks

- [x] Task 1 — Build the shared admin shell (AC: 1, 2, 4)
  - [x] Create reusable header/navigation markup and styling.
  - [x] Keep the layout mobile-usable.
- [x] Task 2 — Implement dashboard data queries and rendering (AC: 3)
  - [x] Show the required counts and recent products.
  - [x] Reuse the shared config/DB layer.
- [x] Task 3 — Verify shell and dashboard behavior (AC: 1, 2, 3, 4)
  - [x] Add coverage or smoke verification.
  - [x] Record results in this story.

## Dev Notes

- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:294)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

- `php -l public_html/admin/index.php`
- `php -l public_html/admin/referrals.php`
- `php -l public_html/admin/settings.php`
- `php -l scripts/test-admin-dashboard.php`
- `node --check scripts/test-admin-dashboard.mjs`
- `npm run test-admin-dashboard`
- `npm run test-admin-dashboard-db`
- `npm run test-db`

### Completion Notes List

- Added a reusable mobile-first admin shell with shared header, status badge, and nav links for Products, Referrals, Settings, and Logout.
- Implemented `/admin/index.php` as the dashboard landing page with total products, sold-out products, total referral codes, and recently updated products.
- Added protected placeholder screens for referrals and settings so the nav is usable before Sprint 4 feature work lands.
- Verified shell rendering in PHP and dashboard DB queries against the live MariaDB instance using a rollback-based smoke test.

### File List

- public_html/includes/admin.php
- public_html/admin/index.php
- public_html/admin/referrals.php
- public_html/admin/settings.php
- scripts/test-admin-dashboard.php
- scripts/test-admin-dashboard.mjs
- package.json
- _bmad-output/implementation-artifacts/2-3-admin-layout-dashboard.md

### Change Log

- Story created from Lane A sprint plan.
- Implemented the shared admin shell and dashboard metrics surface.
