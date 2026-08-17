<?php

namespace Trecenode\FilamentMermaidField\Tests;

use Filament\Actions\ActionsServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Schemas\SchemasServiceProvider;
use Filament\Support\SupportServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;
use Trecenode\FilamentMermaidField\FilamentMermaidFieldServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function defineEnvironment($app): void
    {
        $app['view']->addNamespace('mermaid-field-test', __DIR__ . '/Fixtures/views');
    }

    protected function getPackageProviders($app): array
    {
        return [
            ActionsServiceProvider::class,
            BladeCaptureDirectiveServiceProvider::class,
            FormsServiceProvider::class,
            InfolistsServiceProvider::class,
            NotificationsServiceProvider::class,
            SchemasServiceProvider::class,
            SupportServiceProvider::class,
            LivewireServiceProvider::class,
            FilamentMermaidFieldServiceProvider::class,
        ];
    }
}
