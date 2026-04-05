# Statify Easy Widget Filament 4 and 5 Support Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Allow `statify-easy-widget` to declare support for both Filament 4 and Filament 5 without changing its runtime behavior.

**Architecture:** This is a Composer metadata and documentation change. A single metadata test defines the supported platform matrix, and Composer dry-runs verify both the newest and lowest allowed dependency sets resolve.

**Tech Stack:** PHP 8.2+, Composer, Pest, Filament 4/5, Laravel 11/12

---

### Task 1: Make the metadata test fail for the old contract

**Files:**
- Modify: `tests/Unit/ComposerMetadataTest.php`

**Step 1: Write the failing test**

Assert:

- `php` is `^8.2`
- `filament/widgets` is `^4.0|^5.0`
- `illuminate/support` is `^11.28|^12.0`
- `illuminate/database` is `^11.28|^12.0`
- full Filament is still only suggested for `StatifyEasyWidgetPlugin`

**Step 2: Run test to verify it fails**

Run: `php artisan test --compact --filter=ComposerMetadata`
Expected: FAIL because the package still has Filament 4 only and Laravel 12 only constraints

### Task 2: Widen Composer support and update docs

**Files:**
- Modify: `composer.json`
- Modify: `README.md`

**Step 1: Write minimal implementation**

- widen the Composer constraints to Filament 4 and 5
- lower the PHP floor to 8.2
- document Filament 4 and 5 support in the installation section

**Step 2: Run verification**

Run: `php artisan test --compact --filter=ComposerMetadata`
Expected: PASS

Run: `composer validate --strict`
Expected: PASS

Run: `composer install --dry-run`
Expected: PASS resolving the latest allowed set

Run: `composer update --dry-run --prefer-lowest`
Expected: PASS resolving the lowest allowed set
