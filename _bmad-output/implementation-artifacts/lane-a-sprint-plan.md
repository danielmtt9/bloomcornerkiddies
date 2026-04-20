# Lane A Sprint Plan

Status: approved-for-dev
Source: [_bmad-output/planning-artifacts/epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:1)

## Scope

This sprint plan is generated from **Lane A only**.

- **Included:** developer MVP backlog (`A0` through `A6`)
- **Excluded:** Lane B deferred engineering
- **Excluded:** Lane C non-engineering operational/content work

Developer rule:
- implement stories in the exact order below unless a blocking technical issue forces resequencing
- do not pull Lane B or Lane C items into sprint execution

## First Story

Start with:

`0-1-environment-config-bootstrap`

Current status:
- `ready-for-dev`

Immediate follow-on after review or signoff:
- `0-2-repository-safety-deployment-pipeline`

## Sprint Sequence

### Sprint 1 — Foundation and DB Readiness

Goal:
- stabilize repo/config/deploy
- establish schema and seeds
- make DB/bootstrap helpers reusable

Stories:
- `0-1-environment-config-bootstrap`
- `0-2-repository-safety-deployment-pipeline`
- `0-3-shared-php-bootstrap-helpers`
- `1-1-core-commerce-tables`
- `1-2-store-settings-referral-tables`
- `1-3-migration-seed-execution-path`

Exit criteria:
- config contract is stable
- deploy path is safe
- schema and seed flow runs cleanly
- shared DB/config helpers are reusable

### Sprint 2 — Admin Access and Shell

Goal:
- ship a secure, mobile-usable admin shell

Stories:
- `2-1-admin-login`
- `2-2-session-guard-logout`
- `2-3-admin-layout-dashboard`

Exit criteria:
- seller can log in, log out, and navigate a working admin shell on mobile

### Sprint 3 — Product Management Core

Goal:
- enable full seller product management with multi-category support

Stories:
- `3-1-product-list-view`
- `3-2-product-create-edit-form`
- `3-3-size-manager-quick-stock-updates`
- `3-4-product-image-uploads`
- `3-5-edit-hide-delete`

Exit criteria:
- seller can create, edit, stock-manage, hide, and delete multi-category products with photos

### Sprint 4 — Settings and Referral Admin

Goal:
- make seller settings, status, referrals, and password management usable end-to-end

Stories:
- `4-1-store-settings-page`
- `4-2-seller-status-control`
- `4-3-referral-code-admin`
- `4-4-admin-password-change`

Exit criteria:
- store settings, seller status, referral admin, and password change all work end-to-end

### Sprint 5 — Storefront Browse Experience

Goal:
- ship catalogue browsing, trust hero, and shareable routing

Stories:
- `5-1-storefront-product-category-api`
- `5-2-storefront-shell`
- `5-3-hero-trust-section`
- `5-4-product-grid-filters`
- `5-5-shareable-product-routing`

Exit criteria:
- storefront loads products/categories/status
- trust hero renders
- catalogue filters work
- shareable product URLs work

### Sprint 6 — Commerce Handoff

Goal:
- convert browsing into WhatsApp/Telegram handoff behavior

Stories:
- `6-1-product-detail-view`
- `6-2-whatsapp-order-enquiry-handoff`
- `6-3-telegram-link-save-for-later-notify-me`
- `6-4-storefront-seller-status`
- `6-5-ga4-snippet-utm-compatible-link-handling`

Exit criteria:
- product detail works
- WhatsApp handoff works with prefilled context
- Telegram remains personal-link only
- save-for-later and notify-me work
- analytics-ready routing is preserved

## Story Order

1. `0-1-environment-config-bootstrap`
2. `0-3-shared-php-bootstrap-helpers`
3. `0-2-repository-safety-deployment-pipeline`
4. `1-1-core-commerce-tables`
5. `1-2-store-settings-referral-tables`
6. `1-3-migration-seed-execution-path`
7. `2-1-admin-login`
8. `2-2-session-guard-logout`
9. `2-3-admin-layout-dashboard`
10. `3-1-product-list-view`
11. `3-2-product-create-edit-form`
12. `3-3-size-manager-quick-stock-updates`
13. `3-4-product-image-uploads`
14. `3-5-edit-hide-delete`
15. `4-1-store-settings-page`
16. `4-2-seller-status-control`
17. `4-3-referral-code-admin`
18. `4-4-admin-password-change`
19. `5-1-storefront-product-category-api`
20. `5-2-storefront-shell`
21. `5-3-hero-trust-section`
22. `5-4-product-grid-filters`
23. `5-5-shareable-product-routing`
24. `6-1-product-detail-view`
25. `6-2-whatsapp-order-enquiry-handoff`
26. `6-3-telegram-link-save-for-later-notify-me`
27. `6-4-storefront-seller-status`
28. `6-5-ga4-snippet-utm-compatible-link-handling`

## Developer Notes

- Treat `0-1-environment-config-bootstrap` as the current active story.
- Do not begin Epic 1 implementation until Epic 0 review issues are cleared or consciously accepted.
- Multi-category support is required across schema, admin, API, and storefront.
- Telegram bot engineering remains out of scope for this sprint plan.
- Operational launch work remains out of scope for this sprint plan.
