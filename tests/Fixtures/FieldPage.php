<?php

namespace Trecenode\FilamentMermaidField\Tests\Fixtures;

use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Trecenode\FilamentMermaidField\FilamentMermaidField;

class FieldPage extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    /** @var array<string, mixed> */
    public array $data = [];

    public function mount(): void
    {
        $this->form->fill(['diagram' => 'graph TD; A-->B;']);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FilamentMermaidField::make('diagram')
                    ->label('Diagram')
                    ->rows(10),
            ])
            ->statePath('data');
    }

    public function render(): View
    {
        return view('mermaid-field-test::page');
    }
}
