# Statify Easy Widget

Build Filament stat widgets from Eloquent models with a fluent API.

## Installation

```bash
composer require filahq/statify-easy-widget
```

## Usage

Extend `EasyStatsWidget` and define your stats in a `stats()` method using the fluent `Stat` builder:

```php
use FilaHQ\StatifyEasyWidget\Builder\Stat;
use FilaHQ\StatifyEasyWidget\Widgets\EasyStatsWidget;

class RevenueStatsWidget extends EasyStatsWidget
{
    protected function stats(): array
    {
        return [
            Stat::make('Revenue Today')
                ->model(Order::class)
                ->attribute('total')
                ->where('created_at', '>=', today())
                ->sum()
                ->prefix('$')
                ->color('success')
                ->description('+12% from yesterday')
                ->chartLastDays(7),

            Stat::make('Users Today')
                ->model(User::class)
                ->where('created_at', '>=', today())
                ->count()
                ->color('info')
                ->chartLastDays(30),

            Stat::make('Avg Order Value')
                ->model(Order::class)
                ->attribute('total')
                ->where('created_at', '>=', now()->startOfMonth())
                ->avg()
                ->prefix('$')
                ->suffix(' avg'),
        ];
    }
}
```

Register the widget in your Filament panel as you normally would:

```php
$panel->widgets([
    RevenueStatsWidget::class,
]);
```

## Builder API

### Query

| Method | Description |
|--------|-------------|
| `model(string $class)` | Eloquent model class to query |
| `attribute(string $column)` | Column to aggregate (required for `sum`, `avg`, `min`, `max`) |
| `where(string $column, mixed $value)` | Adds a `=` condition |
| `where(string $column, string $operator, mixed $value)` | Adds a condition with explicit operator |
| `count()` | Aggregate: row count |
| `sum()` | Aggregate: column sum |
| `avg()` | Aggregate: column average |
| `min()` | Aggregate: column minimum |
| `max()` | Aggregate: column maximum |

Multiple `where()` calls accumulate — all conditions are applied to the query.

### Decoration

| Method | Description |
|--------|-------------|
| `prefix(string $prefix)` | Prepended to the computed value (e.g. `'$'`) |
| `suffix(string $suffix)` | Appended to the computed value (e.g. `' users'`) |
| `color(string $color)` | Filament color (`'success'`, `'danger'`, `'warning'`, `'info'`, etc.) |
| `icon(string\|BackedEnum $icon)` | Heroicon name or `Heroicon` enum case |
| `description(string $description)` | Secondary label shown below the value |
| `chart(array $data)` | Static array of numeric values for the sparkline |
| `chartLastDays(int $days, string $dateColumn = 'created_at')` | Generates a sparkline from the last N days of model data |

### Chart

`chartLastDays()` automatically builds a sparkline by running the same aggregate for each of the last N days. The same `where()` conditions are applied per day, scoped to that day's date range.

```php
// Sparkline from the last 7 days, using created_at (default)
->chartLastDays(7)

// Sparkline from the last 30 days, using a custom date column
->chartLastDays(30, 'completed_at')
```

If both `chart()` and `chartLastDays()` are set, the static `chart()` array takes precedence.

## Filament Plugin (optional)

If you want to register the plugin with your Filament panel:

```php
use FilaHQ\StatifyEasyWidget\StatifyEasyWidgetPlugin;

$panel->plugin(StatifyEasyWidgetPlugin::make());
```

## Statify Compatibility

Widgets built with `EasyStatsWidget` are compatible with the `filahq/statify` package out of the box. Register them normally and they can be exposed through the Statify API.

```bash
composer require filahq/statify
```

## License

MIT
