<?php 

namespace Trecenode\FilamentMermaidField;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentMermaidFieldServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('filament-mermaid-field')
                ->hasViews();
    }
}