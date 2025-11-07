# Filament Mermaid Field

A custom field for Filament that allows creating and visualizing Mermaid diagrams with zoom and pan functionality created by 13Node.com

## Features

- ✨ Integrated text editor with Filament
- 🖼️ Real-time Mermaid diagram rendering  
- 🔍 Interactive zoom and pan using local files
- 🌙 Light and dark theme support
- 📱 Responsive design
- 🚀 Easy Livewire integration

## Installation

1. Install the package:

```bash
composer require trecenode/filament-mermaid-field
```

2. Publish the assets:

```bash
php artisan vendor:publish --tag=filament-mermaid-field-assets
```

This will copy the CSS and JavaScript files to your public directory.

## Usage

### In your Resource or Form

```php
use Trecenode\FilamentMermaidField\FilamentMermaidField;

FilamentMermaidField::make('diagram_content')
    ->label('Mermaid Diagram')
    ->placeholder('graph TD\n    A[Start] --> B[Process]\n    B --> C[End]')
    ->rows(10)
```

### Complete example

```php
<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Resources\Resource;
use Trecenode\FilamentMermaidField\FilamentMermaidField;

class DiagramResource extends Resource
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                
                FilamentMermaidField::make('content')
                    ->label('Diagram Content')
                    ->placeholder('graph TD\n    A[Start] --> B{Condition?}\n    B -->|Yes| C[Process A]\n    B -->|No| D[Process B]\n    C --> E[End]\n    D --> E')
                    ->rows(15)
                    ->required(),
                    
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->rows(3),
            ]);
    }
}
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

### Local Development Setup

1. Clone the repository
2. Run `composer install`
3. Install assets:

```bash
php artisan vendor:publish --tag=filament-mermaid-field-assets

### Project Structure

- `src/` - PHP source code
- `resources/views/` - Blade views
- `resources/js/` - JavaScript source files  
- `resources/css/` - CSS source files

## Changelog

* **1.1** - Local assets support, UI/UX improvements, Filament asset integration
* **1.0** - First version, only works if row is called "content"

## Credits

-   [Danilo Ulloa](https://github.com/trecenode)
-   [Mermaid.js](https://mermaid.js.org/) - Diagram library
-   [svg-pan-zoom](https://github.com/ariutta/svg-pan-zoom) - Pan and zoom functionality

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.