# Story 0.2: Repository Safety & Deployment Pipeline

Status: review

## Story

As a developer,
I want safe repository defaults and automated deployment,
so that production deploys from `main` without pushing secrets or uploads.

## Acceptance Criteria

1. `.gitignore` excludes `.env`, `config.php`, and `public_html/uploads/`.
2. GitHub Actions deploys only `public_html/` contents to Hostinger on pushes to `main`.
3. rsync excludes `.env`, docs, planning artifacts, uploads, and CI metadata.
4. Deployment supports manual trigger and single-flight concurrency.

## Tasks / Subtasks

- [x] Task 1 — Audit and tighten tracked repository safety rules (AC: 1)
  - [x] Confirm ignore coverage for secrets and uploads.
  - [x] Add any missing ignore rules required by the deployment model.
- [x] Task 2 — Implement the deployment workflow (AC: 2, 3, 4)
  - [x] Add/update `.github/workflows/deploy.yml`.
  - [x] Ensure deploy scope is `public_html/` only and rsync exclusions match AC.
  - [x] Add `workflow_dispatch` and concurrency guard.
- [x] Task 3 — Validate the deploy contract (AC: 1, 2, 3, 4)
  - [x] Run static validation on the workflow YAML.
  - [x] Record changed files and verification commands in this story.

## Dev Notes

- Story order source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:124)
- Deployment constraints source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:219)
- Existing ignore file: [.gitignore](/home/danielaroko/applications/bloomrocxx/.gitignore:1)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

- `npm run test-deploy`
- `npm run test-config`
- `ruby -e "require 'yaml'; require 'date'; YAML.safe_load(File.read('.github/workflows/deploy.yml'), permitted_classes: [Date], aliases: true); puts 'YAML OK'"`

### Completion Notes List

- Existing `.gitignore` already satisfied the required secret and uploads ignore contract; no ignore-file change was needed.
- Hardened `.github/workflows/deploy.yml` by normalizing SSH port handling through job env and keeping deploy scope anchored to `./public_html/`.
- Added `scripts/test-deploy-workflow.sh` plus `npm run test-deploy` to validate deploy triggers, concurrency, rsync exclusions, and ignore coverage.

### File List

- .github/workflows/deploy.yml
- package.json
- scripts/test-deploy-workflow.sh
- _bmad-output/implementation-artifacts/0-2-repository-safety-deployment-pipeline.md

### Change Log

- Story created from Lane A sprint plan.
- Implemented deployment workflow hardening and executable deploy validation coverage.
