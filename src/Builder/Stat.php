<?php

namespace FilaHQ\StatifyEasyWidget\Builder;

use BackedEnum;

class Stat
{
    private ?string $modelClass = null;

    private ?string $attribute = null;

    /** @var array<int, array{0: string, 1: string, 2: mixed}> */
    private array $wheres = [];

    private ?string $aggregate = null;

    private ?string $prefix = null;

    private ?string $suffix = null;

    private ?string $color = null;

    private string|BackedEnum|null $icon = null;

    private ?string $description = null;

    private ?array $chart = null;

    private ?int $chartLastDays = null;

    private string $chartDateColumn = 'created_at';

    public function __construct(public readonly string $label) {}

    public static function make(string $label): static
    {
        return new static($label);
    }

    public function model(string $modelClass): static
    {
        $this->modelClass = $modelClass;

        return $this;
    }

    public function attribute(string $column): static
    {
        $this->attribute = $column;

        return $this;
    }

    public function where(string $column, mixed $operatorOrValue, mixed $value = null): static
    {
        if ($value === null) {
            $this->wheres[] = [$column, '=', $operatorOrValue];
        } else {
            $this->wheres[] = [$column, $operatorOrValue, $value];
        }

        return $this;
    }

    public function count(): static
    {
        $this->aggregate = 'count';

        return $this;
    }

    public function sum(): static
    {
        $this->aggregate = 'sum';

        return $this;
    }

    public function avg(): static
    {
        $this->aggregate = 'avg';

        return $this;
    }

    public function min(): static
    {
        $this->aggregate = 'min';

        return $this;
    }

    public function max(): static
    {
        $this->aggregate = 'max';

        return $this;
    }

    public function prefix(string $prefix): static
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function suffix(string $suffix): static
    {
        $this->suffix = $suffix;

        return $this;
    }

    public function color(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function icon(string|BackedEnum $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function description(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function chart(array $data): static
    {
        $this->chart = $data;

        return $this;
    }

    public function chartLastDays(int $days, string $dateColumn = 'created_at'): static
    {
        $this->chartLastDays = $days;
        $this->chartDateColumn = $dateColumn;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getModel(): string
    {
        return $this->modelClass ?? '';
    }

    public function getAttribute(): ?string
    {
        return $this->attribute;
    }

    /**
     * @return array<int, array{0: string, 1: string, 2: mixed}>
     */
    public function getWheres(): array
    {
        return $this->wheres;
    }

    public function getAggregate(): string
    {
        return $this->aggregate ?? '';
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    public function getSuffix(): ?string
    {
        return $this->suffix;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function getIcon(): string|BackedEnum|null
    {
        return $this->icon;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getChart(): ?array
    {
        return $this->chart;
    }

    public function getChartLastDays(): ?int
    {
        return $this->chartLastDays;
    }

    public function getChartDateColumn(): string
    {
        return $this->chartDateColumn;
    }
}
