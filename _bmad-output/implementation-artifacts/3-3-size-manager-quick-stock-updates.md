# Story 3.3: Size Manager & Quick Stock Updates

Status: done

## Story

As a seller,
I want to manage size-level stock quickly,
so that storefront availability reflects real stock without slow admin workflows.

## Acceptance Criteria

1. Add/edit form supports multiple size rows with quantity and sold-out state.
2. Size rows can be added and removed before save.
3. Product list offers a quick stock update path for size quantities and sold-out toggles.
4. Storefront-ready sold-out state is derived from size stock and/or explicit sold-out flag.

## Tasks / Subtasks

- [x] Task 1 — Implement size-row management in add/edit flows (AC: 1, 2)
  - [x] Support add/remove/update of size rows before submit.
  - [x] Persist size rows with stock and sold-out state.
- [x] Task 2 — Implement quick stock updates from the list view (AC: 3, 4)
  - [x] Add a focused update path for size-level stock changes.
  - [x] Keep storefront sold-out semantics aligned with stored data.
- [x] Task 3 — Verify inventory flows (AC: 1, 2, 3, 4)
  - [x] Add coverage or smoke verification.
  - [x] Record results in this story.

## Dev Notes

- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:381)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

- `php -l public_html/admin/stock.php`
- `npm run test-admin-products`
- `npm run test-admin-products-db`

### Completion Notes List

- Added quick-add size chips plus dynamic add/remove size rows in the shared product form.
- Size persistence now stores stock quantity and explicit sold-out state while normalizing `stock_qty = 0` to sold-out semantics.
- Added `/admin/stock.php` as the focused quick stock update path linked from the product list.

### File List

- public_html/admin/stock.php
- public_html/admin/partials/product-form.php
- public_html/includes/products.php
- scripts/test-admin-products.php
- scripts/test-admin-products.mjs
- package.json
- _bmad-output/implementation-artifacts/3-3-size-manager-quick-stock-updates.md

### Change Log

- Story created from Lane A sprint plan.
- Implemented size management and quick stock updates.
