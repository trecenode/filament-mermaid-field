<?php 

namespace Trecenode\FilamentMermaidField;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

use Filament\Support\Assets\Js;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;

class FilamentMermaidFieldServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('filament-mermaid-field')
                ->hasViews();
    }

    public function packageBooted(): void
    {
        FilamentAsset::register([
            Js::make('svg-pan-zoom', __DIR__ . '/../resources/js/svg-pan-zoom.js'),
        ], package: 'trecenode/filament-mermaid-field');
    }
}