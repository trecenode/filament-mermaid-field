# Filament Mermaid Field

A custom field for Filament that allows creating and visualizing Mermaid diagrams with zoom and pan functionality created by 13Node.com

## Features

- ✨ Integrated text editor with Filament
- 🖼️ Real-time Mermaid diagram rendering
- 🔍 Interactive zoom and pan
- 🌙 Follows the Filament light/dark theme
- 🌍 Translatable interface (English and Spanish bundled)
- 📱 Responsive design
- 🚀 Livewire 4 / Alpine integration

## Requirements

| | Version |
|---|---|
| PHP | 8.2+ |
| Laravel | 11, 12 or 13 |
| Filament | 5.x |

For Filament 3 use version `2.x` of this package.

## Installation

```bash
composer require trecenode/filament-mermaid-field
```

The field's CSS and the `svg-pan-zoom` script are registered as Filament assets. Publish them like any other Filament package asset:

```bash
php artisan filament:assets
```

Mermaid itself is loaded on demand from the jsDelivr CDN (`mermaid@11`), only on pages that actually render the field.

## Usage

### In your Resource or Schema

```php
use Trecenode\FilamentMermaidField\FilamentMermaidField;

FilamentMermaidField::make('diagram_content')
    ->label('Mermaid Diagram')
    ->placeholder("graph TD\n    A[Start] --> B[Process]\n    B --> C[End]")
    ->rows(10)
```

### Complete example

```php
<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Trecenode\FilamentMermaidField\FilamentMermaidField;

class DiagramResource extends Resource
{
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                FilamentMermaidField::make('content')
                    ->label('Diagram Content')
                    ->placeholder("graph TD\n    A[Start] --> B{Condition?}\n    B -->|Yes| C[Process A]\n    B -->|No| D[Process B]")
                    ->rows(15)
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->rows(3),
            ]);
    }
}
```

`FilamentMermaidField` extends `Filament\Forms\Components\Textarea`, so every textarea method (`autosize()`, `maxLength()`, `disabled()`, …) is available.

## Translations

The field's own UI strings (layout toggle button, empty state, render error) follow your app locale. English (`en`) and Spanish (`es`) ship with the package.

To customize them or add another language, publish the language files:

```bash
php artisan vendor:publish --tag=filament-mermaid-field-translations
```

They land in `lang/vendor/filament-mermaid-field/{locale}/mermaid-field.php`.

The field `label` and `placeholder` are yours to translate as usual:

```php
FilamentMermaidField::make('content')
    ->label(__('diagram.content'))
```

## Mermaid Diagram Examples

### Flowchart
```
graph TD
    A[Start] --> B{Condition?}
    B -->|Yes| C[Process A]
    B -->|No| D[Process B]
    C --> E[End]
    D --> E
```

### Sequence Diagram
```
sequenceDiagram
    participant A as Client
    participant B as Server
    A->>B: Request
    B-->>A: Response
```

### Class Diagram
```
classDiagram
    class Animal {
        +String name
        +eat()
    }
    class Dog {
        +bark()
    }
    Animal <|-- Dog
```

## Development

```bash
composer install
composer test
```

The suite boots a Testbench app with Filament and renders the field through a Livewire component.

### Project Structure

- `src/` - PHP source code
- `resources/views/` - Blade view
- `resources/lang/` - Language files
- `resources/js/` - `svg-pan-zoom`
- `resources/css/` - Field stylesheet
- `tests/` - Testbench test suite

## Changelog

* **3.0** - Laravel 13 and Filament 5 support, translatable UI (en/es), Alpine-based rendering, assets registered through Filament, mermaid 11, svg-pan-zoom 3.6.2, test suite
* **1.1** - Local assets support, UI/UX improvements, Filament asset integration
* **1.0** - First version, only works if row is called "content"

## Credits

-   [Danilo Ulloa](https://github.com/trecenode)
-   [Mermaid.js](https://mermaid.js.org/) - Diagram library
-   [svg-pan-zoom](https://github.com/bumbu/svg-pan-zoom) - Pan and zoom functionality

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
