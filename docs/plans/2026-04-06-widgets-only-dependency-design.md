# Statify Easy Widget Widgets-Only Dependency Design

## Goal

Allow the core `filahq/statify-easy-widget` package to depend on `filament/widgets` instead of the full `filament/filament` meta-package, while keeping `StatifyEasyWidgetPlugin` available as an optional integration for apps that install full Filament.

## Current State

The package mixes two concerns:

- core widget-building behavior based on `Filament\Widgets\StatsOverviewWidget`
- optional panel plugin registration via `StatifyEasyWidgetPlugin`

Only the plugin class needs `Filament\Contracts\Plugin` and `Filament\Panel`. The core builder, resolver, and widget base class only rely on the widgets package.

## Approach

Match the `statify` contract:

- change Composer to require `filament/widgets`
- add a Composer `suggest` entry for `filament/filament`
- update the README to state that core usage works with widgets only
- document that `StatifyEasyWidgetPlugin` requires full Filament

No code-path refactor is needed because the plugin class can remain unused unless the consumer opts into panel plugin registration.

## Verification

Add a small Composer metadata test that locks the package contract:

- `filament/widgets` is required
- `filament/filament` is not required
- `filament/filament` is suggested for `StatifyEasyWidgetPlugin`
