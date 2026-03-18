<?php

namespace FilaHQ\StatifyEasyWidget;

use Filament\Contracts\Plugin;
use Filament\Panel;

class StatifyEasyWidgetPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'statify-easy-widget';
    }

    public function register(Panel $panel): void {}

    public function boot(Panel $panel): void {}
}
