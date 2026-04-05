# Statify Easy Widget Filament 4 and 5 Support Design

## Goal

Broaden `filahq/statify-easy-widget` so it can be installed in projects using either Filament 4 or Filament 5.

## Constraints

- Keep the widgets-only dependency model introduced earlier.
- Keep `StatifyEasyWidgetPlugin` optional and document that it needs full Filament.
- Do not add runtime compatibility shims unless the code actually needs them.

## Approach

The code currently only relies on `StatsOverviewWidget`, `StatsOverviewWidget\Stat`, and Filament's plugin interface and panel type. Those APIs are still present in Filament 5. The package contract can therefore widen through Composer metadata and documentation instead of code changes.

Update the package to:

- lower PHP to `^8.2`
- widen `filament/widgets` to `^4.0|^5.0`
- widen `illuminate/support` and `illuminate/database` to `^11.28|^12.0`
- keep suggesting `filament/filament` for optional plugin registration
- state Filament 4 and 5 support clearly in the README

## Verification

Use one metadata test to lock the new contract, then verify:

- focused test pass
- `composer validate --strict`
- `composer install --dry-run` resolves the latest allowed set
- `composer update --dry-run --prefer-lowest` resolves the lowest allowed set
