<?php

namespace FilaHQ\StatifyEasyWidget\Widgets;

use FilaHQ\StatifyEasyWidget\Builder\Stat;
use FilaHQ\StatifyEasyWidget\Resolvers\StatResolver;
use Filament\Widgets\StatsOverviewWidget;

abstract class EasyStatsWidget extends StatsOverviewWidget
{
    /** @return Stat[] */
    abstract protected function stats(): array;

    /** @return StatsOverviewWidget\Stat[] */
    protected function getStats(): array
    {
        $resolver = app(StatResolver::class);

        return array_map(
            fn (Stat $stat) => $resolver->resolve($stat),
            $this->stats()
        );
    }
}
