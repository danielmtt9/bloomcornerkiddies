# Story 6.2: WhatsApp Order & Enquiry Handoff

Status: ready-for-dev

## Story

As a parent,
I want to start a WhatsApp conversation with prefilled product context,
so that I do not need to retype item details.

## Acceptance Criteria

1. Selected-size orders open `wa.me` with an encoded order template.
2. No-size state uses an enquiry template instead.
3. Message template includes optional referral code line.
4. Emoji and special characters are preserved by proper URL encoding.
5. WhatsApp CTA is visually prominent on mobile.

## Tasks / Subtasks

- [ ] Task 1 — Implement WhatsApp handoff templates (AC: 1, 2, 3, 4)
  - [ ] Build order and enquiry variants.
  - [ ] Encode message content safely.
- [ ] Task 2 — Implement CTA behavior and presentation (AC: 5)
  - [ ] Make WhatsApp the clear primary handoff action.
  - [ ] Keep the CTA mobile-prominent.
- [ ] Task 3 — Verify WhatsApp handoff behavior (AC: 1, 2, 3, 4, 5)
  - [ ] Add smoke/test verification.
  - [ ] Record results in this story.

## Dev Notes

- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:668)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

### Completion Notes List

### File List

### Change Log

- Story created from Lane A sprint plan.
