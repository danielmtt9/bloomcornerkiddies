# Story B1.2: Telegram Conversational Order Flow

Status: done

## Story

As a parent,  
I want to complete a guided order conversation inside Telegram,  
so that I can submit complete order intent through chat.

## Acceptance Criteria

1. Bot stores per-chat session state.
2. Bot collects product, size, buyer name, address, and gift flow data.
3. Completion is explicitly defined as reaching confirmation step.

## Tasks / Subtasks

- [x] Task 1 — Implement session state persistence (AC: 1)
  - [x] Persist per-chat conversation state between updates.
  - [x] Support reset/restart behavior.
- [x] Task 2 — Implement guided collection flow (AC: 2)
  - [x] Collect product, size, buyer name, address, and gift flow in sequence.
  - [x] Handle invalid gift-flow input with corrective prompt.
- [x] Task 3 — Implement explicit confirmation completion (AC: 3)
  - [x] Show summary at confirmation step.
  - [x] Mark flow completed only after `CONFIRM`.
- [x] Task 4 — Verify flow behavior (AC: 1, 2, 3)
  - [x] Add deterministic test script for step-by-step transition coverage.
  - [x] Record command evidence.

## Dev Notes

- Story source: `_bmad-output/planning-artifacts/epics.md` (Lane B, Story B1.2).
- Seller notification routing remains out of scope in B1.3.

## Dev Agent Record

### Agent Model Used

gpt-5.3-codex

### Debug Log References

- `php -l public_html/includes/telegram_bot.php`
- `php -l public_html/telegram-webhook.php`
- `npm run test-telegram-bot-flow`

### Completion Notes List

- Added a Telegram bot conversation state machine with per-chat session persistence.
- Wired webhook update handling to the state machine and in-chat reply dispatch via Telegram `sendMessage`.
- Added explicit confirmation summary and completion gating on `CONFIRM`.

### File List

- public_html/includes/telegram_bot.php
- public_html/telegram-webhook.php
- scripts/test-telegram-bot-flow.php
- package.json
- _bmad-output/implementation-artifacts/b1-2-telegram-conversational-order-flow.md

### Change Log

- Implemented B1.2 guided Telegram conversation flow and tests.
