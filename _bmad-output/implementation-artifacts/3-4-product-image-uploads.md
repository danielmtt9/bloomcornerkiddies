# Story 3.4: Product Image Uploads

Status: ready-for-dev

## Story

As a seller,
I want to upload multiple product images safely,
so that the storefront can show consistent 4:5 images with one primary thumbnail.

## Acceptance Criteria

1. Photo upload accepts JPEG, PNG, and WebP only.
2. File validation uses MIME type and a 5MB max per image.
3. Files are saved under `/public_html/uploads/products/{product_id}/`.
4. First image is stored as primary image (`sort_order = 0`).
5. Save operation rolls back if image persistence fails.
6. UI communicates the 4:5 portrait requirement clearly.

## Tasks / Subtasks

- [ ] Task 1 — Implement upload validation and storage (AC: 1, 2, 3, 4)
  - [ ] Validate MIME and size.
  - [ ] Persist files and image rows in the correct path/order.
- [ ] Task 2 — Integrate uploads into transactional product save (AC: 4, 5)
  - [ ] Ensure product save fails cleanly if upload persistence fails.
  - [ ] Keep primary image behavior deterministic.
- [ ] Task 3 — Verify upload behavior (AC: 1, 2, 3, 4, 5, 6)
  - [ ] Add smoke/test coverage.
  - [ ] Record results in this story.

## Dev Notes

- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:403)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

### Completion Notes List

### File List

### Change Log

- Story created from Lane A sprint plan.
