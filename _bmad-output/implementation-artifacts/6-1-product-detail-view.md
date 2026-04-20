# Story 6.1: Product Detail View

Status: ready-for-dev

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

- [ ] Task 1 — Build the detail view layout (AC: 1, 2)
  - [ ] Render media, pricing, metadata, and size states.
  - [ ] Keep optional fields conditional.
- [ ] Task 2 — Wire delivery and size-guide content (AC: 3, 4)
  - [ ] Pull delivery info from seller config.
  - [ ] Add the size-guide interaction.
- [ ] Task 3 — Verify detail behavior (AC: 1, 2, 3, 4)
  - [ ] Add smoke/test verification.
  - [ ] Record results in this story.

## Dev Notes

- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:649)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

### Completion Notes List

### File List

### Change Log

- Story created from Lane A sprint plan.
