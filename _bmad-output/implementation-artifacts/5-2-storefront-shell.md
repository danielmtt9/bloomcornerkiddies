# Story 5.2: Storefront Shell

Status: done

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

- [x] Task 1 — Build the storefront shell markup/head (AC: 1, 2, 3, 4)
  - [x] Add Alpine.js CDN usage.
  - [x] Set page metadata and analytics placeholder.
- [x] Task 2 — Implement the responsive shell layout (AC: 5, 6)
  - [x] Keep markup/CSS lightweight.
  - [x] Prevent horizontal scroll at 320px.
- [x] Task 3 — Verify shell performance/structure (AC: 1, 2, 3, 4, 5, 6)
  - [x] Add static or smoke validation.
  - [x] Record results in this story.

## Dev Notes

- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:564)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

- `npm run test-storefront-shell`

### Completion Notes List

- Replaced the storefront placeholder with a full inline-CSS, Alpine.js CDN shell in `public_html/index.html`.
- Added title, meta description, OG tags, and GA4 placeholder snippet in the document head.
- Layout now guards against horizontal overflow at narrow mobile widths and keeps the initial shell lightweight without a build step.

### File List

- public_html/index.html
- scripts/test-storefront-shell.mjs
- package.json
- _bmad-output/implementation-artifacts/5-2-storefront-shell.md

### Change Log

- Story created from Lane A sprint plan.
- Implemented the responsive storefront shell and metadata contract.
