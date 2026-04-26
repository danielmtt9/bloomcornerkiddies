# Story B1.3: Seller Notification Routing

Status: done

## Story

As a seller,  
I want to receive completed Telegram bot orders at a defined destination,  
so that confirmed order details are routed to my chosen Telegram destination.

## Acceptance Criteria

1. Completed Telegram order flows trigger a seller notification.
2. Notification destination is configurable between seller DM and private channel.
3. Destination remains unresolved by default and does not force a hardcoded choice.

## Tasks / Subtasks

- [x] Task 1 — Add destination resolution strategy (AC: 2, 3)
  - [x] Support `TELEGRAM_ORDER_DESTINATION_MODE=dm|channel|disabled`.
  - [x] Resolve target chat id from `TELEGRAM_SELLER_CHAT_ID` or `TELEGRAM_SELLER_CHANNEL_ID`.
- [x] Task 2 — Trigger notifications on completed flows (AC: 1, 2)
  - [x] Detect confirmation transition from non-complete to complete.
  - [x] Send structured order summary to resolved destination.
- [x] Task 3 — Preserve unresolved placeholder behavior (AC: 3)
  - [x] Default mode remains `disabled`.
  - [x] Log skipped notification reason instead of forcing destination assumptions.
- [x] Task 4 — Verify behavior (AC: 1, 2, 3)
  - [x] Extend Telegram flow test to cover completion transition and notification payload generation.

## Dev Notes

- Story source: `_bmad-output/planning-artifacts/epics.md` (Lane B, Story B1.3).
- Destination selection is now runtime-configurable and intentionally defaults to disabled until business decision is finalized.

## Dev Agent Record

### Agent Model Used

gpt-5.3-codex

### Debug Log References

- `php -l public_html/includes/telegram_bot.php`
- `php -l public_html/telegram-webhook.php`
- `npm run test-telegram-bot-flow`

### Completion Notes List

- Added configurable seller notification routing with explicit `disabled` fallback.
- Wired notification trigger to order confirmation completion transitions only.
- Added structured order summary payload and maintained no-op behavior when destination mode is unresolved/disabled.

### File List

- public_html/includes/telegram_bot.php
- public_html/telegram-webhook.php
- .env.example
- docs/deployment.md
- scripts/test-telegram-bot-flow.php
- _bmad-output/implementation-artifacts/b1-3-seller-notification-routing.md

### Change Log

- Implemented B1.3 configurable seller notification routing for Telegram order confirmations.
