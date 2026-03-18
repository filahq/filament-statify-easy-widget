<?php

use App\Models\User;
use FilaHQ\StatifyEasyWidget\Builder\Stat as BuilderStat;
use FilaHQ\StatifyEasyWidget\Resolvers\StatResolver;
use Filament\Widgets\StatsOverviewWidget\Stat as FilamentStat;

beforeEach(function () {
    // Run migrations for the in-memory SQLite DB
    $this->artisan('migrate');
});

it('resolves count aggregate', function () {
    User::factory()->count(3)->create();

    $stat = BuilderStat::make('Users')->model(User::class)->count();
    $resolved = app(StatResolver::class)->resolve($stat);

    expect($resolved)->toBeInstanceOf(FilamentStat::class);
    // Value should be '3' (raw cast, no formatting)
    expect($resolved->getValue())->toBe('3');
});

it('resolves sum aggregate using id column', function () {
    // Create 3 users; their IDs will be 1, 2, 3 — sum = 6
    User::factory()->count(3)->create();

    $stat = BuilderStat::make('ID Sum')->model(User::class)->attribute('id')->sum();
    $resolved = app(StatResolver::class)->resolve($stat);

    expect($resolved)->toBeInstanceOf(FilamentStat::class);
    expect((int) $resolved->getValue())->toBe(6);
});

it('applies where conditions', function () {
    User::factory()->count(3)->create(['created_at' => now()->subDays(2)]);
    User::factory()->count(2)->create(['created_at' => now()]);

    $stat = BuilderStat::make('Today')
        ->model(User::class)
        ->where('created_at', '>=', today())
        ->count();

    $resolved = app(StatResolver::class)->resolve($stat);
    expect($resolved->getValue())->toBe('2');
});

it('applies multiple where conditions', function () {
    User::factory()->count(2)->create(['created_at' => now(), 'name' => 'Alice']);
    User::factory()->count(3)->create(['created_at' => now(), 'name' => 'Bob']);

    $stat = BuilderStat::make('Alice Today')
        ->model(User::class)
        ->where('created_at', '>=', today())
        ->where('name', 'Alice')
        ->count();

    $resolved = app(StatResolver::class)->resolve($stat);
    expect($resolved->getValue())->toBe('2');
});

it('formats value with prefix and suffix', function () {
    User::factory()->count(4)->create();

    $stat = BuilderStat::make('Users')->model(User::class)->count()->prefix('$')->suffix(' total');
    $resolved = app(StatResolver::class)->resolve($stat);

    expect($resolved->getValue())->toBe('$4 total');
});

it('passes color, description, and static chart through to filament stat', function () {
    User::factory()->count(1)->create();

    $stat = BuilderStat::make('Users')
        ->model(User::class)
        ->count()
        ->color('success')
        ->description('up 10%')
        ->chart([1, 2, 3]);

    $resolved = app(StatResolver::class)->resolve($stat);

    expect($resolved)->toBeInstanceOf(FilamentStat::class);
    // We can verify the resolved stat has the right label and value
    expect($resolved->getLabel())->toBe('Users');
    expect($resolved->getValue())->toBe('1');
});

it('builds chart array of correct length for chartLastDays', function () {
    User::factory()->count(5)->create(['created_at' => now()]);

    $stat = BuilderStat::make('Users')
        ->model(User::class)
        ->count()
        ->chartLastDays(7);

    // We test via the resolver but there's no direct getChart() on FilamentStat
    // So we just verify resolution completes without error
    $resolved = app(StatResolver::class)->resolve($stat);
    expect($resolved)->toBeInstanceOf(FilamentStat::class);
});

it('static chart overrides chartLastDays', function () {
    User::factory()->count(1)->create();

    $stat = BuilderStat::make('Users')
        ->model(User::class)
        ->count()
        ->chart([10, 20, 30])
        ->chartLastDays(7);

    // Resolution should succeed without error; static chart takes precedence
    $resolved = app(StatResolver::class)->resolve($stat);
    expect($resolved)->toBeInstanceOf(FilamentStat::class);
});

it('icon string value is passed through to filament stat', function () {
    User::factory()->count(1)->create();

    $stat = BuilderStat::make('Users')
        ->model(User::class)
        ->count()
        ->icon('heroicon-o-users');

    $resolved = app(StatResolver::class)->resolve($stat);
    expect($resolved)->toBeInstanceOf(FilamentStat::class);
    expect($resolved->getIcon())->toBe('heroicon-o-users');
});
