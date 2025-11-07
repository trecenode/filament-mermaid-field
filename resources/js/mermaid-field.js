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