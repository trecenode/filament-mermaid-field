<?php

namespace Trecenode\FilamentMermaidField\Tests;

use Livewire\Livewire;
use Trecenode\FilamentMermaidField\Tests\Fixtures\FieldPage;

class MermaidFieldTest extends TestCase
{
    public function test_it_renders_the_field(): void
    {
        Livewire::test(FieldPage::class)
            ->assertOk()
            ->assertSee('mermaid-field-container', escape: false)
            ->assertSee('wire:model="data.diagram"', escape: false)
            ->assertSee('mermaid.esm.min.mjs', escape: false)
            ->assertSee('svg-pan-zoom.js', escape: false)
            ->assertSee('mermaid-field.css', escape: false);
    }

    public function test_it_renders_english_strings_by_default(): void
    {
        Livewire::test(FieldPage::class)
            ->assertSee('Vertical View', escape: false)
            ->assertSee('Parallel View', escape: false)
            ->assertSee('No diagram definition provided.', escape: false)
            ->assertSee('Error rendering diagram:', escape: false);
    }

    public function test_it_translates_strings_to_the_app_locale(): void
    {
        app()->setLocale('es');

        Livewire::test(FieldPage::class)
            ->assertSee('Vista Vertical', escape: false)
            ->assertSee('Vista Paralela', escape: false)
            ->assertDontSee('Vertical View', escape: false);
    }
}
