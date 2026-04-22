# Story 6.2: WhatsApp Order & Enquiry Handoff

Status: done

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

- [x] Task 1 — Implement WhatsApp handoff templates (AC: 1, 2, 3, 4)
  - [x] Build order and enquiry variants.
  - [x] Encode message content safely.
- [x] Task 2 — Implement CTA behavior and presentation (AC: 5)
  - [x] Make WhatsApp the clear primary handoff action.
  - [x] Keep the CTA mobile-prominent.
- [x] Task 3 — Verify WhatsApp handoff behavior (AC: 1, 2, 3, 4, 5)
  - [x] Add smoke/test verification.
  - [x] Record results in this story.

## Dev Notes

- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:668)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

- `npm run test-storefront-handoff`

### Completion Notes List

- Added prefilled WhatsApp enquiry and size-specific order templates with encoded product context and optional referral code support.
- Kept WhatsApp as the primary commerce CTA in the detail surface, including sold-out-aware per-size handling.

### File List

- `public_html/index.html`
- `scripts/test-storefront-handoff.mjs`

### Change Log

- Story created from Lane A sprint plan.
- Story completed and verified for Sprint 6.
