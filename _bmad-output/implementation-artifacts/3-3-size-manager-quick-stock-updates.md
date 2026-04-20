# Story 3.3: Size Manager & Quick Stock Updates

Status: ready-for-dev

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

- [ ] Task 1 — Implement size-row management in add/edit flows (AC: 1, 2)
  - [ ] Support add/remove/update of size rows before submit.
  - [ ] Persist size rows with stock and sold-out state.
- [ ] Task 2 — Implement quick stock updates from the list view (AC: 3, 4)
  - [ ] Add a focused update path for size-level stock changes.
  - [ ] Keep storefront sold-out semantics aligned with stored data.
- [ ] Task 3 — Verify inventory flows (AC: 1, 2, 3, 4)
  - [ ] Add coverage or smoke verification.
  - [ ] Record results in this story.

## Dev Notes

- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:381)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

### Completion Notes List

### File List

### Change Log

- Story created from Lane A sprint plan.
