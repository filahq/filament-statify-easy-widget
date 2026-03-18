<?php

namespace FilaHQ\StatifyEasyWidget\Tests\Fixtures;

use App\Models\User;
use FilaHQ\StatifyEasyWidget\Builder\Stat;
use FilaHQ\StatifyEasyWidget\Widgets\EasyStatsWidget;

class TestStatsWidget extends EasyStatsWidget
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
