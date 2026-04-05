# Statify Easy Widget Widgets-Only Dependency Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Narrow `statify-easy-widget` to `filament/widgets` for core usage while keeping the plugin API optional and documented.

**Architecture:** This is a dependency-contract change, not a runtime refactor. Composer and README carry the public contract, and a small metadata test prevents the package from drifting back to the full Filament requirement.

**Tech Stack:** PHP 8.3, Composer, Pest, Filament 4

---

### Task 1: Lock the dependency contract with a failing test

**Files:**
- Create: `tests/Unit/ComposerMetadataTest.php`
- Test: `tests/Unit/ComposerMetadataTest.php`

**Step 1: Write the failing test**

Add a Pest test that asserts:

- `require` contains `filament/widgets`
- `require` does not contain `filament/filament`
- `suggest.filament/filament` mentions `StatifyEasyWidgetPlugin`

**Step 2: Run test to verify it fails**

Run: `php artisan test --compact --filter=ComposerMetadata`
Expected: FAIL because `composer.json` still requires `filament/filament`

### Task 2: Update package metadata and docs

**Files:**
- Modify: `composer.json`
- Modify: `README.md`

**Step 1: Write minimal implementation**

- replace `filament/filament` with `filament/widgets`
- add a `suggest` entry for `filament/filament`
- update installation and plugin documentation to explain the optional dependency split

**Step 2: Run focused verification**

Run: `php artisan test --compact --filter=ComposerMetadata`
Expected: PASS

Run: `composer validate --strict`
Expected: PASS

Run: `composer install --dry-run`
Expected: PASS with `filament/widgets` and related transitive packages
