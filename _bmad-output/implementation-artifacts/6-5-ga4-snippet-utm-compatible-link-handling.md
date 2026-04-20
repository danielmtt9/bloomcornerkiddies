# Story 6.5: GA4 Snippet & UTM-Compatible Link Handling

Status: ready-for-dev

## Story

As a seller,
I want to capture analytics-ready pageviews and preserve attribution data,
so that marketing traffic can be measured later without retrofitting the storefront shell.

## Acceptance Criteria

1. GA4 snippet placeholder exists in the storefront shell.
2. Storefront routes preserve UTM parameters when product detail state is opened client-side.
3. Product/share links do not strip known attribution parameters during navigation.

## Tasks / Subtasks

- [ ] Task 1 — Finalize analytics placeholder integration (AC: 1)
  - [ ] Keep the GA4 slot in the storefront shell.
  - [ ] Avoid coupling to operational GA setup.
- [ ] Task 2 — Preserve attribution through routing (AC: 2, 3)
  - [ ] Keep UTM params intact during client-side navigation.
  - [ ] Preserve shared link behavior.
- [ ] Task 3 — Verify attribution-safe routing (AC: 1, 2, 3)
  - [ ] Add smoke/test verification.
  - [ ] Record results in this story.

## Dev Notes

- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:730)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

### Completion Notes List

### File List

### Change Log

- Story created from Lane A sprint plan.
