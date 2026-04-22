# Story 6.5: GA4 Snippet & UTM-Compatible Link Handling

Status: done

## Story

As a seller,
I want to capture analytics-ready pageviews and preserve attribution data,
so that marketing traffic can be measured later without retrofitting the storefront shell.

## Acceptance Criteria

1. GA4 snippet placeholder exists in the storefront shell.
2. Storefront routes preserve UTM parameters when product detail state is opened client-side.
3. Product/share links do not strip known attribution parameters during navigation.

## Tasks / Subtasks

- [x] Task 1 — Finalize analytics placeholder integration (AC: 1)
  - [x] Keep the GA4 slot in the storefront shell.
  - [x] Avoid coupling to operational GA setup.
- [x] Task 2 — Preserve attribution through routing (AC: 2, 3)
  - [x] Keep UTM params intact during client-side navigation.
  - [x] Preserve shared link behavior.
- [x] Task 3 — Verify attribution-safe routing (AC: 1, 2, 3)
  - [x] Add smoke/test verification.
  - [x] Record results in this story.

## Dev Notes

- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:730)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

- `npm run test-storefront-shell`
- `npm run test-storefront-handoff`

### Completion Notes List

- Preserved the GA4 placeholder snippet in the storefront shell.
- Added attribution-safe client routing so product detail links retain known UTM and click-id parameters during open, close, and share flows.

### File List

- `public_html/index.html`
- `scripts/test-storefront-handoff.mjs`

### Change Log

- Story created from Lane A sprint plan.
- Story completed and verified for Sprint 6.
