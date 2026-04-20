#!/usr/bin/env bash
set -euo pipefail

workflow=".github/workflows/deploy.yml"

fail() {
  echo "FAIL: $1" >&2
  exit 1
}

[ -f "$workflow" ] || fail "workflow file missing"

grep -q '^name: Deploy to Hostinger' "$workflow" || fail "workflow name missing"
grep -q 'workflow_dispatch' "$workflow" || fail "workflow_dispatch missing"
grep -q 'concurrency:' "$workflow" || fail "concurrency missing"
grep -q 'group: production-deploy' "$workflow" || fail "concurrency group missing"
grep -q 'branches:' "$workflow" || fail "push branches missing"
grep -q '\- main' "$workflow" || fail "main branch trigger missing"
grep -q 'rsync -avz --delete' "$workflow" || fail "rsync deploy command missing"
grep -q "./public_html/" "$workflow" || fail "public_html deploy source missing"

for pattern in \
  "--exclude='.github/'" \
  "--exclude='.env'" \
  "--exclude='*.md'" \
  "--exclude='_bmad-output/'" \
  "--exclude='docs/'" \
  "--exclude='uploads/'"
do
  grep -F -q -- "$pattern" "$workflow" || fail "missing exclusion: $pattern"
done

git check-ignore .env >/dev/null || fail ".env is not ignored"
git check-ignore config.php >/dev/null || fail "config.php is not ignored"
git check-ignore public_html/uploads/test-placeholder >/dev/null || fail "public_html/uploads is not ignored"

ruby -e "require 'yaml'; require 'date'; YAML.safe_load(File.read('$workflow'), permitted_classes: [Date], aliases: true); puts 'PASS: deploy workflow validation'" >/dev/null

echo "PASS: deploy workflow validation"
