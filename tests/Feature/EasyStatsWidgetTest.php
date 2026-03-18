<?php

use App\Models\User;
use FilaHQ\StatifyEasyWidget\Builder\Stat;
use FilaHQ\StatifyEasyWidget\Widgets\EasyStatsWidget;
use Filament\Widgets\StatsOverviewWidget\Stat as FilamentStat;
use Livewire\Livewire;

beforeEach(function () {
    $this->artisan('migrate');
});

it('renders the widget without error', function () {
    User::factory()->count(3)->create();

    Livewire::test(TestEasyStatsWidget::class)
        ->assertSuccessful();
});

it('getStats returns correct number of stat objects', function () {
    User::factory()->count(5)->create();

    $widget = new TestEasyStatsWidget;
    $method = new ReflectionMethod($widget, 'getStats');
    $method->setAccessible(true);
    $stats = $method->invoke($widget);

    expect($stats)->toHaveCount(2);
    expect($stats[0])->toBeInstanceOf(FilamentStat::class);
    expect($stats[1])->toBeInstanceOf(FilamentStat::class);
});

it('computed values match seeded db state', function () {
    User::factory()->count(5)->create(['created_at' => now()->subDays(1)]);
    User::factory()->count(2)->create(['created_at' => now()]);

    $widget = new TestEasyStatsWidget;
    $method = new ReflectionMethod($widget, 'getStats');
    $method->setAccessible(true);
    $stats = $method->invoke($widget);

    // Total Users = 7
    expect($stats[0]->getValue())->toBe('7');
    // Users Today = 2
    expect($stats[1]->getValue())->toBe('2');
});

// ---------------------------------------------------------------------------
// Fixtures
// ---------------------------------------------------------------------------

class TestEasyStatsWidget extends EasyStatsWidget
{
    protected function stats(): array
    {
        return [
            Stat::make('Total Users')
                ->model(User::class)
                ->count(),

            Stat::make('Users Today')
                ->model(User::class)
                ->where('created_at', '>=', today())
                ->count(),
        ];
    }
}
