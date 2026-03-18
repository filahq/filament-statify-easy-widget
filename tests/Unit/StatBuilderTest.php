<?php

use FilaHQ\StatifyEasyWidget\Builder\Stat;

it('sets label via make()', function () {
    $stat = Stat::make('Revenue Today');
    expect($stat->getLabel())->toBe('Revenue Today');
});

it('stores model class', function () {
    $stat = Stat::make('Test')->model('App\\Models\\Order');
    expect($stat->getModel())->toBe('App\\Models\\Order');
});

it('stores attribute', function () {
    $stat = Stat::make('Test')->model('App\\Models\\Order')->attribute('total');
    expect($stat->getAttribute())->toBe('total');
});

it('normalizes two-argument where to equals tuple', function () {
    $stat = Stat::make('Test')->model('App\\Models\\Order')->where('status', 'paid');
    expect($stat->getWheres())->toBe([['status', '=', 'paid']]);
});

it('stores three-argument where with explicit operator', function () {
    $stat = Stat::make('Test')->model('App\\Models\\Order')->where('created_at', '>=', today());
    expect($stat->getWheres()[0][0])->toBe('created_at');
    expect($stat->getWheres()[0][1])->toBe('>=');
});

it('accumulates multiple where conditions', function () {
    $stat = Stat::make('Test')
        ->model('App\\Models\\Order')
        ->where('status', 'paid')
        ->where('created_at', '>=', today());
    expect($stat->getWheres())->toHaveCount(2);
});

it('stores aggregate type', function () {
    expect(Stat::make('Test')->model('App\\Models\\Order')->count()->getAggregate())->toBe('count');
    expect(Stat::make('Test')->model('App\\Models\\Order')->attribute('total')->sum()->getAggregate())->toBe('sum');
    expect(Stat::make('Test')->model('App\\Models\\Order')->attribute('total')->avg()->getAggregate())->toBe('avg');
    expect(Stat::make('Test')->model('App\\Models\\Order')->attribute('total')->min()->getAggregate())->toBe('min');
    expect(Stat::make('Test')->model('App\\Models\\Order')->attribute('total')->max()->getAggregate())->toBe('max');
});

it('stores prefix and suffix', function () {
    $stat = Stat::make('Test')->model('App\\Models\\Order')->count()->prefix('$')->suffix('k');
    expect($stat->getPrefix())->toBe('$');
    expect($stat->getSuffix())->toBe('k');
});

it('stores color, description', function () {
    $stat = Stat::make('Test')->model('App\\Models\\Order')->count()->color('success')->description('up');
    expect($stat->getColor())->toBe('success');
    expect($stat->getDescription())->toBe('up');
});

it('stores static chart array', function () {
    $stat = Stat::make('Test')->model('App\\Models\\Order')->count()->chart([1, 2, 3]);
    expect($stat->getChart())->toBe([1, 2, 3]);
});

it('chartLastDays defaults date column to created_at', function () {
    $stat = Stat::make('Test')->model('App\\Models\\Order')->count()->chartLastDays(7);
    expect($stat->getChartLastDays())->toBe(7);
    expect($stat->getChartDateColumn())->toBe('created_at');
});

it('chartLastDays accepts custom date column', function () {
    $stat = Stat::make('Test')->model('App\\Models\\Order')->count()->chartLastDays(7, 'completed_at');
    expect($stat->getChartDateColumn())->toBe('completed_at');
});

it('icon accepts a BackedEnum and returns it unchanged', function () {
    $icon = StatBuilderTestIcon::Users;

    $stat = Stat::make('Test')->model('App\\Models\\Order')->count()->icon($icon);
    expect($stat->getIcon())->toBe($icon);
});

// ---------------------------------------------------------------------------
// Fixtures
// ---------------------------------------------------------------------------

enum StatBuilderTestIcon: string
{
    case Users = 'heroicon-o-users';
}

it('fluent methods return static', function () {
    $stat = Stat::make('Test')->model('App\\Models\\Order');
    expect($stat->attribute('total'))->toBeInstanceOf(Stat::class);
    expect($stat->count())->toBeInstanceOf(Stat::class);
    expect($stat->prefix('$'))->toBeInstanceOf(Stat::class);
    expect($stat->suffix('k'))->toBeInstanceOf(Stat::class);
    expect($stat->color('success'))->toBeInstanceOf(Stat::class);
    expect($stat->description('text'))->toBeInstanceOf(Stat::class);
    expect($stat->chart([1]))->toBeInstanceOf(Stat::class);
    expect($stat->chartLastDays(7))->toBeInstanceOf(Stat::class);
});
