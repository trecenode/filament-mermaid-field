@php
    use Filament\Support\Facades\FilamentView;

    $hasInlineLabel = $hasInlineLabel();
    $isConcealed = $isConcealed();
    $isDisabled = $isDisabled();
    $rows = $getRows();
    $shouldAutosize = $shouldAutosize();
    $statePath = $getStatePath();

    $initialHeight = (($rows ?? 2) * 1.5) + 0.75;
@endphp

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
</x-dynamic-component>

<pre id="mermaid-render" style="overflow: hidden;border: 1px solid #ccc;max-height: 500px;"></pre>
<style>
#mermaidSvg {
  width: 100%;
  height: 700px;
}
</style>
<script src="{{ url('js/svg-pan-zoom.js')}}"></script>
<script type="module">
    import mermaid from 'https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.esm.min.mjs';
    const mermaidTheme = (localStorage.getItem('bsTheme') === 'light') ? 'default' : 'dark';
    mermaid.initialize({ startOnLoad: false, theme: mermaidTheme });

    const drawDiagram = async function () {
        const element = document.querySelector('#mermaid-render');
        const graphDefinition = document.querySelector('textarea[wire\\:model="{{ $statePath }}"]').value;
        if (!graphDefinition.trim()) {
            element.innerHTML = '<p>No diagram definition provided.</p>';
            return;
        }
        try {
            const { svg } = await mermaid.render('mermaidSvg', graphDefinition);
            element.innerHTML = svg.replace(/[ ]*max-width:[ 0-9\.]*px;/i, '');
            svgPanZoom('#mermaidSvg', {
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
        drawDiagram();
    });

    document.querySelector('textarea[wire\\:model="{{ $statePath }}"]').addEventListener('input', function () {
        drawDiagram();
    });
</script>