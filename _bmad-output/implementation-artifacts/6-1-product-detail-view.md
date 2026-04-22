# Story 6.1: Product Detail View

Status: done

## Story

As a parent,
I want to see enough product detail to make a buying decision,
so that I can inspect the item, sizes, pricing, and delivery info before messaging.

## Acceptance Criteria

1. Detail view shows gallery, name, price, optional sale price, description, brand, material, season tag, and TikTok link if present.
2. Size chips show available and sold-out states clearly.
3. Delivery info is pulled from `seller_config.delivery_info`.
4. Size guide is collapsible and accessible from the detail view.

## Tasks / Subtasks

- [x] Task 1 — Build the detail view layout (AC: 1, 2)
  - [x] Render media, pricing, metadata, and size states.
  - [x] Keep optional fields conditional.
- [x] Task 2 — Wire delivery and size-guide content (AC: 3, 4)
  - [x] Pull delivery info from seller config.
  - [x] Add the size-guide interaction.
- [x] Task 3 — Verify detail behavior (AC: 1, 2, 3, 4)
  - [x] Add smoke/test verification.
  - [x] Record results in this story.

## Dev Notes

- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:649)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

- `npm run test-storefront-shell`
- `npm run test-storefront-api`
- `npm run test-storefront-handoff`

### Completion Notes List

- Added a full storefront detail overlay with gallery thumbnails, conditional metadata, a collapsible size guide, and delivery information from `seller_config.delivery_info`.
- Product payload now includes `image_urls[]` so the detail view can render a deterministic image gallery beyond the primary card image.

### File List

- `public_html/index.html`
- `public_html/includes/storefront.php`
- `scripts/test-storefront-api.mjs`
- `scripts/test-storefront-handoff.mjs`

### Change Log

- Story created from Lane A sprint plan.
- Story completed and verified for Sprint 6.
