<?php

namespace FilaHQ\StatifyEasyWidget\Resolvers;

use FilaHQ\StatifyEasyWidget\Builder\Stat as BuilderStat;
use Filament\Widgets\StatsOverviewWidget\Stat as FilamentStat;
use Illuminate\Database\Eloquent\Builder;

class StatResolver
{
    public function resolve(BuilderStat $stat): FilamentStat
    {
        $modelClass = $stat->getModel();
        $query = $modelClass::query();

        foreach ($stat->getWheres() as [$column, $operator, $value]) {
            $query->where($column, $operator, $value);
        }

        $rawValue = $this->runAggregate($query, $stat);

        $formatted = ($stat->getPrefix() ?? '').((string) $rawValue).($stat->getSuffix() ?? '');

        $chartData = null;
        if ($stat->getChartLastDays() !== null) {
            $chartData = $this->resolveChart($stat);
        }

        $filamentStat = FilamentStat::make($stat->getLabel(), $formatted);

        if ($stat->getColor() !== null) {
            $filamentStat->color($stat->getColor());
        }

        if ($stat->getIcon() !== null) {
            $filamentStat->icon($stat->getIcon());
        }

        if ($stat->getDescription() !== null) {
            $filamentStat->description($stat->getDescription());
        }

        // Static chart overrides dynamic chart
        $chart = $stat->getChart() ?? $chartData ?? null;
        if ($chart !== null) {
            $filamentStat->chart($chart);
        }

        return $filamentStat;
    }

    /** @return array<int, int|float> */
    private function resolveChart(BuilderStat $stat): array
    {
        $days = $stat->getChartLastDays();
        $dateColumn = $stat->getChartDateColumn();
        $modelClass = $stat->getModel();

        $chartData = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $dayStart = now()->subDays($i)->startOfDay();
            $dayEnd = now()->subDays($i)->endOfDay();

            $query = $modelClass::query();

            foreach ($stat->getWheres() as [$column, $operator, $value]) {
                $query->where($column, $operator, $value);
            }

            $query->where($dateColumn, '>=', $dayStart)
                ->where($dateColumn, '<=', $dayEnd);

            $chartData[] = $this->runAggregate($query, $stat);
        }

        return $chartData;
    }

    private function runAggregate(Builder $query, BuilderStat $stat): int|float
    {
        return match ($stat->getAggregate()) {
            'count' => $query->count(),
            'sum' => $query->sum($stat->getAttribute()),
            'avg' => (float) $query->avg($stat->getAttribute()),
            'min' => $query->min($stat->getAttribute()),
            'max' => $query->max($stat->getAttribute()),
            default => 0,
        };
    }
}
