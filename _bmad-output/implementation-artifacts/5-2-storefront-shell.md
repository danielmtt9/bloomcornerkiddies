# Story 5.2: Storefront Shell

Status: ready-for-dev

## Story

As a parent,
I want the storefront to load quickly on mobile,
so that first paint is fast and the shell is usable without a build pipeline.

## Acceptance Criteria

1. `index.html` loads Alpine.js from CDN.
2. Page title and meta description are set.
3. OG tags are present.
4. GA4 snippet placeholder is present.
5. Layout has no horizontal scrolling at 320px width.
6. Initial HTML + JS footprint stays within the defined lightweight target.

## Tasks / Subtasks

- [ ] Task 1 — Build the storefront shell markup/head (AC: 1, 2, 3, 4)
  - [ ] Add Alpine.js CDN usage.
  - [ ] Set page metadata and analytics placeholder.
- [ ] Task 2 — Implement the responsive shell layout (AC: 5, 6)
  - [ ] Keep markup/CSS lightweight.
  - [ ] Prevent horizontal scroll at 320px.
- [ ] Task 3 — Verify shell performance/structure (AC: 1, 2, 3, 4, 5, 6)
  - [ ] Add static or smoke validation.
  - [ ] Record results in this story.

## Dev Notes

- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:564)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

### Completion Notes List

### File List

### Change Log

- Story created from Lane A sprint plan.
