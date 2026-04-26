# Story B1.4: Optional Image Processing Enhancements

Status: done

## Story

As a developer,  
I want optional image resize/crop enhancements when server support exists,  
so that product uploads can normalize toward consistent storefront framing without breaking unsupported environments.

## Acceptance Criteria

1. Image processing is optional and does not break upload flow on servers without GD support.
2. Upload processing can be enabled via configuration mode.
3. Processed images normalize toward 4:5 presentation when enabled and supported.

## Tasks / Subtasks

- [x] Task 1 — Add processing mode controls (AC: 1, 2)
  - [x] Added `PRODUCT_IMAGE_PROCESSING_MODE=off|auto|required`.
  - [x] Added runtime support probe for GD processing capabilities.
- [x] Task 2 — Add optional reframe pipeline (AC: 1, 3)
  - [x] Added center-crop reframing to 4:5 ratio for JPEG/PNG/WebP uploads.
  - [x] Kept safe fallback in `auto` mode when processing support is unavailable.
- [x] Task 3 — Update docs and validation (AC: 1, 2, 3)
  - [x] Updated env template and deployment documentation.
  - [x] Extended admin products smoke test for mode and support probe checks.

## Dev Notes

- Story source: `_bmad-output/planning-artifacts/epics.md` (Lane B, Story B1.4).
- This implementation intentionally keeps enhancement optional; it does not force environment-level GD requirements unless `required` mode is selected.

## Dev Agent Record

### Agent Model Used

gpt-5.3-codex

### Debug Log References

- `php -l public_html/includes/products.php`
- `php -l public_html/admin/partials/product-form.php`
- `npm run test-admin-products`

### Completion Notes List

- Added optional upload processing pipeline that can crop images to a 4:5 frame when GD is available.
- Introduced explicit processing modes (`off`, `auto`, `required`) to support safe rollout.
- Documented the enhancement and left default behavior unchanged (`off`).

### File List

- public_html/includes/products.php
- public_html/admin/partials/product-form.php
- .env.example
- docs/deployment.md
- scripts/test-admin-products.php
- _bmad-output/implementation-artifacts/b1-4-optional-image-processing-enhancements.md

### Change Log

- Implemented B1.4 optional image processing with environment-aware fallback behavior.
