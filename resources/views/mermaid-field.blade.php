@php
    use Filament\Support\Facades\FilamentView;

    $hasInlineLabel = $hasInlineLabel();
    $isConcealed = $isConcealed();
    $isDisabled = $isDisabled();
    $rows = $getRows();
    $shouldAutosize = $shouldAutosize();
    $statePath = $getStatePath();

    $initialHeight = max(25, (($rows ?? 2) * 1.5) + 0.75); // Minimum 400px (25rem ≈ 400px)
@endphp

@pushOnce('styles')
    <link rel="stylesheet" href="{{ asset('css/trecenode/filament-mermaid-field/mermaid-field.css') }}">
@endPushOnce

@pushOnce('scripts')
    <script src="{{ asset('js/trecenode/filament-mermaid-field/svg-pan-zoom.js') }}"></script>
    <script type="module">
        import mermaid from 'https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.esm.min.mjs';
        const mermaidTheme = (localStorage.getItem('bsTheme') === 'light') ? 'default' : 'dark';
        mermaid.initialize({ startOnLoad: false, theme: mermaidTheme });

        const drawDiagram = async function () {
            const fieldId = '{{ $getId() }}'.replace(/[^a-zA-Z0-9-_]/g, '-');
            const element = document.querySelector('#mermaid-render-' + fieldId);
            const textarea = document.querySelector('textarea[wire\\:model="{{ $statePath }}"]');
            
            if (!element) {
                console.error('Mermaid render element not found:', '#mermaid-render-' + fieldId);
                return;
            }
            
            if (!textarea) {
                console.error('Textarea not found');
                return;
            }
            
            const graphDefinition = textarea.value;
            if (!graphDefinition.trim()) {
                element.innerHTML = '<p>No diagram definition provided.</p>';
                return;
            }
            try {
                const svgId = 'mermaidSvg-' + fieldId;
                const { svg } = await mermaid.render(svgId, graphDefinition);
                element.innerHTML = svg.replace(/[ ]*max-width:[ 0-9\.]*px;/i, '');
                svgPanZoom('#' + svgId, {
                    zoomEnabled: true,
                    controlIconsEnabled: true,
                    fit: true,
                    center: true
                });
            } catch (error) {
                element.innerHTML = `<p>Error rendering diagram: ${error.message}</p>`;
            }
        };

        document.addEventListener('DOMContentLoaded', function () {
            const fieldId = '{{ $getId() }}'.replace(/[^a-zA-Z0-9-_]/g, '-');
            
            // Check if CSS is loaded
            const testEl = document.createElement('div');
            testEl.className = 'mermaid-layout-vertical';
            testEl.style.visibility = 'hidden';
            document.body.appendChild(testEl);
            const computedStyle = getComputedStyle(testEl);
            document.body.removeChild(testEl);
            
            drawDiagram();

            // Function to sync heights between textarea and preview
            const syncHeights = function() {
                const textarea = document.querySelector('textarea[wire\\:model="{{ $statePath }}"]');
                const renderElement = document.querySelector('#mermaid-render-' + fieldId);
                const container = document.getElementById('mermaid-container-' + fieldId);
                
                if (textarea && renderElement && container && container.classList.contains('mermaid-layout-horizontal')) {
                    // In horizontal layout, sync the heights
                    const textareaHeight = textarea.scrollHeight;
                    const minHeight = Math.max(textareaHeight, 400);
                    
                    renderElement.style.height = minHeight + 'px';
                    renderElement.style.minHeight = minHeight + 'px';
                }
            };

            // Setup input listener
            const textarea = document.querySelector('textarea[wire\\:model="{{ $statePath }}"]');
            if (textarea) {
                textarea.addEventListener('input', function () {
                    drawDiagram();
                    setTimeout(syncHeights, 100); // Small delay to allow for text area resize
                });
                
                // Sync heights when textarea is resized
                const resizeObserver = new ResizeObserver(function() {
                    syncHeights();
                });
                resizeObserver.observe(textarea);
            }

            // Layout toggle functionality
            const layoutToggle = document.getElementById('layout-toggle-' + fieldId);
            const layoutText = document.getElementById('layout-text-' + fieldId);
            const container = document.getElementById('mermaid-container-' + fieldId);

            let isParallel = true; // Start with parallel view as default

            if (layoutToggle && layoutText && container) {
                // Set initial state to horizontal/parallel layout
                container.className = 'mermaid-layout-horizontal';
                container.style.display = 'grid';
                container.style.gridTemplateColumns = '1fr 1fr';
                container.style.gap = '1rem';
                container.style.alignItems = 'stretch';
                layoutText.textContent = 'Vertical View';
                layoutToggle.querySelector('svg').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2v3a2 2 0 01-2 2H9V7z"></path>';
                // Sync heights after initial setup
                setTimeout(syncHeights, 100);
                layoutToggle.addEventListener('click', function() {
                    isParallel = !isParallel;
                    
                    if (isParallel) {
                        container.className = 'mermaid-layout-horizontal';
                        // Apply inline styles as fallback
                        container.style.display = 'grid';
                        container.style.gridTemplateColumns = '1fr 1fr';
                        container.style.gap = '1rem';
                        container.style.alignItems = 'stretch';
                        layoutText.textContent = 'Vertical View';
                        layoutToggle.querySelector('svg').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2v3a2 2 0 01-2 2H9V7z"></path>';
                        // Sync heights after layout change
                        setTimeout(syncHeights, 100);
                    } else {
                        container.className = 'mermaid-layout-vertical';
                        // Apply inline styles as fallback
                        container.style.display = 'flex';
                        container.style.flexDirection = 'column';
                        container.style.gap = '1rem';
                        container.style.gridTemplateColumns = '';
                        container.style.alignItems = '';
                        layoutText.textContent = 'Parallel View';
                        layoutToggle.querySelector('svg').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>';
                        // Reset heights in vertical mode
                        const renderElement = document.querySelector('#mermaid-render-' + fieldId);
                        if (renderElement) {
                            renderElement.style.height = '';
                            renderElement.style.minHeight = '400px';
                        }
                    }
                });
            }
        });
    </script>
@endPushOnce

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
    :has-inline-label="$hasInlineLabel"
>
    <x-slot
        name="label"
        @class([
            'sm:pt-1.5' => $hasInlineLabel,
        ])
    >
        {{ $getLabel() }}
    </x-slot>

    <div class="mt-4">
        <div class="flex items-center justify-between mb-3">
            <button type="button" 
                    id="layout-toggle-{{ preg_replace('/[^a-zA-Z0-9-_]/', '-', $getId()) }}" 
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-all duration-200">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2v3a2 2 0 01-2 2H9V7z"></path>
                </svg>
                <span id="layout-text-{{ preg_replace('/[^a-zA-Z0-9-_]/', '-', $getId()) }}">Vertical View</span>
            </button>
        </div>
        
        <div id="mermaid-container-{{ preg_replace('/[^a-zA-Z0-9-_]/', '-', $getId()) }}" class="mermaid-layout-horizontal">
            <div class="mermaid-editor-section">
                <x-filament::input.wrapper
                    :disabled="$isDisabled"
                    :valid="! $errors->has($statePath)"
                    :attributes="
                        \Filament\Support\prepare_inherited_attributes($getExtraAttributeBag())
                            ->class(['fi-fo-textarea overflow-hidden'])
                    "
                >
                    <div wire:ignore.self style="height: '{{ $initialHeight . 'rem' }}'">
                        <textarea
                            @if (FilamentView::hasSpaMode())
                                {{-- format-ignore-start --}}x-load="visible || event (ax-modal-opened)"{{-- format-ignore-end --}}
                            @else
                                x-load
                            @endif
                            x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('textarea', 'filament/forms') }}"
                            x-data="textareaFormComponent({
                                        initialHeight: @js($initialHeight),
                                        shouldAutosize: @js($shouldAutosize),
                                        state: $wire.$entangle('{{ $statePath }}'),
                                    })"
                            @if ($shouldAutosize)
                                x-intersect.once="resize()"
                                x-on:resize.window="resize()"
                            @endif
                            x-model="state"
                            {{ $getExtraAlpineAttributeBag() }}
                            {{
                                $getExtraInputAttributeBag()
                                    ->merge([
                                        'autocomplete' => $getAutocomplete(),
                                        'autofocus' => $isAutofocused(),
                                        'cols' => $getCols(),
                                        'disabled' => $isDisabled,
                                        'id' => $getId(),
                                        'maxlength' => (! $isConcealed) ? $getMaxLength() : null,
                                        'minlength' => (! $isConcealed) ? $getMinLength() : null,
                                        'placeholder' => $getPlaceholder(),
                                        'readonly' => $isReadOnly(),
                                        'required' => $isRequired() && (! $isConcealed),
                                        'rows' => $rows,
                                        $applyStateBindingModifiers('wire:model') => $statePath,
                                    ], escape: false)
                                    ->class([
                                        'block h-full w-full border-none bg-transparent px-3 py-1.5 text-base text-gray-950 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6',
                                        'resize-none' => $shouldAutosize,
                                    ])
                            }}
                        ></textarea>
                    </div>
                </x-filament::input.wrapper>
            </div>
            <div class="mermaid-preview-section">
                <div id="mermaid-render-{{ preg_replace('/[^a-zA-Z0-9-_]/', '-', $getId()) }}" 
                     class="overflow-hidden border border-gray-300 dark:border-gray-600 rounded-md min-h-[400px] max-h-[600px] bg-white dark:bg-gray-800">
                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>