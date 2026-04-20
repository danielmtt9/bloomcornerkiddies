# Story 0.1: Environment & Config Bootstrap

Status: ready-for-dev

## Story

As a developer,
I want the root-level environment/config pattern set up,
so that sensitive values stay out of git and every PHP entrypoint can load shared configuration consistently.

## Acceptance Criteria

1. `.env` and `config.php` are expected one level above `public_html/`, with `.env.example` and `config.php.example` committed as templates.
2. `config.php` loads `.env` without Composer and exposes shared configuration helpers, including `get_db()` and `get_config($key, $default = '')`.
3. The config contract covers DB, auth, Telegram placeholder token, and storefront contact settings used by the MVP bootstrap.
4. The baseline `public_html/` structure exists for `index.html`, `api/`, `admin/`, `telegram-webhook.php`, and `uploads/`.
5. The config/bootstrap pattern is verifiable with an executable smoke test rather than manual inspection only.

## Tasks / Subtasks

- [x] Task 1 — Tighten the root config contract and committed templates (AC: 1, 2, 3)
  - [x] Update `.env.example` so the committed template matches the expected runtime contract.
  - [x] Update `config.php.example` to load env values consistently and expose the shared helper surface.
  - [x] Ensure the config layer can be exercised safely from CLI for smoke testing.
- [x] Task 2 — Create the baseline public entrypoint structure (AC: 4)
  - [x] Add committed placeholders for `public_html/index.html`, `public_html/api/`, `public_html/admin/`, and `public_html/telegram-webhook.php`.
  - [x] Preserve `public_html/uploads/` as a committed directory without introducing tracked uploads.
- [x] Task 3 — Add verification coverage and validate the bootstrap (AC: 5)
  - [x] Add an executable config smoke test.
  - [x] Run syntax and smoke-test commands against the updated bootstrap files.
  - [x] Record results, changed files, and completion notes in this story.

## Dev Notes

- Source of truth for execution order is [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md#lane-a-execution-order). First story to start is `A0.1`. [Source: _bmad-output/planning-artifacts/epics.md#Lane A Execution Order]
- Shared config contract must support DB access and seller config access via `get_db()` and `get_config($key, $default = '')`. [Source: _bmad-output/planning-artifacts/epics.md#Epic A0 — Infrastructure & Configuration]
- Required seeded/storefront-facing config keys for MVP context are `store_name`, `tagline`, `intro_text`, `wa_number`, `telegram_link`, `delivery_info`, `status_message`, `payment_info`, `seller_status`, `referral_discount_percent`. This story only needs the bootstrap/config layer ready to support that access pattern. [Source: _bmad-output/planning-artifacts/epics.md#seller_config Keys]
- Existing committed config templates already exist at repo root and should be tightened, not replaced blindly. [Source: .env.example, config.php.example]
- Existing repo utility surface already includes Node-based setup scripts and DB smoke tests. Add verification in the same pragmatic spirit rather than introducing a heavy test framework. [Source: package.json, scripts/test-connection.mjs]

### Project Structure Notes

- Repo currently contains `.env.example`, `config.php.example`, `.gitignore`, `scripts/`, and `public_html/uploads/README.md`.
- `public_html/api/`, `public_html/admin/`, `public_html/index.html`, and `public_html/telegram-webhook.php` do not yet exist as committed placeholders.

### References

- [Source: _bmad-output/planning-artifacts/epics.md#Epic A0 — Infrastructure & Configuration]
- [Source: _bmad-output/planning-artifacts/epics.md#Lane A Execution Order]
- [Source: .env.example]
- [Source: config.php.example]
- [Source: package.json]

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

- `php -l config.php.example`
- `php -l public_html/telegram-webhook.php`
- `php -l scripts/test-config-example.php`
- `npm run test-config`
- `git status --short --ignored .env config.php`

### Completion Notes List

- Tightened `config.php.example` to expose a cleaner bootstrap surface with `DB_PORT`, centralized env loading, and CLI-safe failure behavior for smoke testing.
- Added baseline committed public entrypoints so the repo now reflects the expected Hostinger `public_html/` shape before feature implementation begins.
- Added `scripts/test-config-example.php` plus `npm run test-config` to prove the committed bootstrap works from templates without a live DB.

### File List

- .env.example
- config.php.example
- package.json
- public_html/index.html
- public_html/api/.gitkeep
- public_html/admin/.gitkeep
- public_html/telegram-webhook.php
- scripts/test-config-example.php
- _bmad-output/implementation-artifacts/0-1-environment-config-bootstrap.md

### Change Log

- Story created for first Lane A execution item.
- Implemented A0.1 bootstrap hardening, public entrypoint scaffolding, and executable config smoke-test coverage.
- Reopened after review; status moved back to ready-for-dev pending further iteration or approval.
