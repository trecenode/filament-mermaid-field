<?php

namespace Trecenode\FilamentMermaidField;

use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentMermaidFieldServiceProvider extends PackageServiceProvider
{
    public const PACKAGE = 'trecenode/filament-mermaid-field';

    public function configurePackage(Package $package): void
    {
        $package->name('filament-mermaid-field')
            ->hasViews()
            ->hasTranslations();
    }

    public function packageBooted(): void
    {
        FilamentAsset::register([
            Css::make('mermaid-field', __DIR__ . '/../resources/css/mermaid-field.css')
                ->loadedOnRequest(),
            Js::make('svg-pan-zoom', __DIR__ . '/../resources/js/svg-pan-zoom.js')
                ->loadedOnRequest(),
        ], package: static::PACKAGE);
    }
}
