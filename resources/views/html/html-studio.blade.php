<?php
$public = true;
if (isset($jeton_secret)) {
    $page = App\Models\Html::where('jeton_secret', $jeton_secret)->first();
    if (!isset($page)) {
        echo "<pre>Cette page n'existe pas.</pre>";
        exit();
    }
    $public = false;
}
if (isset($jeton)) {
    $page = App\Models\Html::where('jeton', $jeton)->first();
}
?>
<!doctype html>
<html lang="fr">
<head>
    @include('inc-meta')
    <title>HTML Studio</title>

    <style>

        html, body { height:100%; margin:0; }

        .grid {
            display: grid;
            grid-template-columns: 1fr 15px 1fr; /* gauche | poignée | droite */
            height:100%;
            margin:0;
            border-radius:0;
        }

        .gutter-col {
            grid-row: 1 / -1;
            cursor: col-resize;
            background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAeCAYAAADkftS9AAAAAXNSR0IB2cksfwAAAARnQU1BAACxjwv8YQUAAAAgY0hSTQAAeiYAAICEAAD6AAAAgOgAAHUwAADqYAAAOpgAABdwnLpRPAAAAAlwSFlzAAAuIwAALiMBeKU/dgAAACNJREFUKM9jNLFy+M/AwMBw5tgBRhibiYEiwDhq5qiZeM0EAAvMLYFPpp3FAAAAAElFTkSuQmCC');
            background-color: rgb(229, 231, 235);
            background-repeat: no-repeat;
            background-position: 50%;
            width:15px;
        }

        .gutter-col-1 { grid-column:2;}

        #header {position:relative; z-index:2; padding:0;margin:0;}
        #workspace {position:absolute; left:0; right:0; bottom:0; top:70px; z-index:1;}
        #paneHtml, #paneCss {position:absolute; top:0; right:0; bottom:0; left:0;}
        #editor_html, #editor_css {position:absolute; top:0; right:0; bottom:0; left:0;}

        .ace_editor {border-radius: 0px;}

        .btn-dark2 {
            /* Couleur de fond : #0c1822 */
            background-color: #0c1822; 
            /* Couleur de la bordure */
            border-color: #0c1822; 
            /* Assurez-vous que le texte reste blanc pour un bon contraste */
            color: #93a3bc; 
        }

        /* Styles pour l'état au survol (hover) */
        .btn-dark2:hover {
            /* Couleur au survol : Noir (#000000) */
            background-color: #081118; 
            border-color: #081118;
            color: #93a3bc;
        }

        /* Styles pour l'état actif (clic) ou en focus */
        .btn-dark2:focus,
        .btn-dark2:active {
            /* Garde la couleur de base */
            background-color: #0c1822;
            border-color: #0c1822;
            /* Ombre de focus ajustée : couleur #0c1822 avec opacité 50% */
            box-shadow: 0 0 0 0.2rem rgba(12, 24, 34, 0.5); 
            color: #93a3bc;
        }

        .copy-icon { cursor: pointer; transition: opacity .8s ease; }
        .copy-icon.is-hidden { opacity: 0; pointer-events: none; }

    </style>

    @php
        $userAgent = request()->header('User-Agent');
        $isFirefox = strpos($userAgent, 'Firefox') !== false;
    @endphp


    @if ($isFirefox)
        <style>
        .ace_scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #2c3e50 #15222e;
        }
        </style>
    @else
        <style>
        .ace_scrollbar::-webkit-scrollbar {
            width: 8px; /* Largeur de la barre de défilement verticale */
            height: 8px; /* Hauteur de la barre de défilement horizontale */
        }

        .ace_scrollbar::-webkit-scrollbar-track {
            background: #15222e; /* Couleur de fond de la piste de défilement */
            border-radius: 5px;
        }

        .ace_scrollbar::-webkit-scrollbar-thumb {
            background: #2c3e50; /* Couleur du "pouce" (la poignée) */
        }

        .ace_scrollbar::-webkit-scrollbar-thumb:hover {
            background: #555; /* Couleur au survol */
        }
        </style>
    @endif

</head>
<body>

    @if (!$public)
    <!-- MODAL -->
    <div class="modal fade" id="liensModal" tabindex="-1" aria-labelledby="liensModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body text-monospace">
                    <div class="mb-2 text-right">
                        <button type="button" class="btn btn-dark btn-sm" data-dismiss="modal"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <div class="text-danger font-weight-bold">Lien privé</div>
                    <div class="text-danger small"><b>NE PAS DIFFUSER</b>. Ce lien permet de rouvrir les éditeurs et de reprendre le développement.</div>
                    <div class="mt-1 p-3 text-danger border border-danger rounded">www.codepuzzle.io/html-studio/{{strtoupper($page->jeton_secret)}}</div>
                    <div class="mt-4 font-weight-bold">Lien public</div>
                    <div class="small">Ce lien permet de consulter cette page sans pouvoir la modifier.</div>
                    <div class="mt-1 p-3 border border-secondary rounded">www.codepuzzle.io/html/{{strtoupper($page->jeton)}}</div>
                </div>
            </div>
        </div>
    </div>
    <!-- /MODAL -->
    @endif

    <div class="grid">

        <!-- COLONNE GAUCHE -->
        <div style="height:100%; overflow:hidden; position:relative;background:#15222e; color:#fff;">
            <div id="gauche" style="width:100%; height:100%;">
                <div id="root" style="height:100%; position:relative;">

                    <!-- EN-TETE -->
                    <div id="header" class="pt-3 text-monospace">
                        <div class="container-fluid">
                            <div class="row mb-3 no-gutters flex-nowrap">

                                <div class="col-auto">
                                    <img src="{{ asset('img/code-puzzle-icon.png') }}" height="28" alt="CODE PUZZLE" />
                                </div>

                                <div class="col-auto ml-3 mr-2">
                                    <button id="btnHtml" type="button" class="mb-1 btn btn-dark2 btn-sm" style="width:85px">index.html</button>
                                </div>

                                <div class="col-auto mr-2">
                                    <button id="btnCss" type="button" class="mb-1 btn btn-dark2 btn-sm" style="width:85px">style.css</button>
                                </div>

                                @if (!$public)
                                <div class="col-auto ml-2 pt-1">
                                    <i class="fa-solid fa-gear" style="color:#93a3bc;cursor:pointer;" data-toggle="modal" data-target="#liensModal"></i>
                                </div>
                                @endif

                                <div class="col-auto ml-3 pt-1">
                                    <a href="/html/{{$page->jeton}}/zip" data-toggle="tooltip" data-placement="auto" title="télécharger un zip des fichiers" style="cursor:pointer; color:#93a3bc;"><i class="fa-solid fa-file-zipper" style="color:#93a3bc;cursor:pointer;"></i></a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- /EN-TETE -->

                    <!-- ÉDITEURS -->
                    <div id="workspace">

                        <div class="ml-4 text-monospace">
                            <div id="titleHtml" style="color:#93a3bc;">
                                <b>HTML</b> 
                                <span id="downloadHtml" data-toggle="tooltip" data-placement="top" title="télécharger" style="cursor: pointer;"><i class="fa-solid fa-file-arrow-down fa-xs" ></i></span>
                                <span data-toggle="tooltip" data-placement="top" title="copier"><i id="copyHtml" class="fa-solid fa-copy fa-xs copy-icon"></i></span>
                            </div>
                            <div id="titleCss" style="color:#93a3bc;display:none;">
                                <b>CSS</b> 
                                <span id="downloadCss" data-toggle="tooltip" data-placement="top" title="télécharger" style="cursor: pointer;"><i class="fa-solid fa-file-arrow-down fa-xs"></i></span>
                                <span data-toggle="tooltip" data-placement="top" title="copier"><i id="copyCss" class="fa-solid fa-copy fa-xs copy-icon"></i></span>
                            </div>
                        </div>

                        <!-- HTML -->
                        <div id="paneHtml" class="mt-4">
                            <div id="editor_html"></div>
                        </div>

                        <!-- CSS -->
                        <div id="paneCss" class="mt-4" style="display:none;">
                            <div id="editor_css"></div>
                        </div>

                    </div>
                    <!-- /ÉDITEURS -->

                </div>
            </div>
        </div>

        <!-- POIGNÉE CENTRALE -->
        <div id="poignee" class="gutter-col gutter-col-1"></div>

        <!-- COLONNE DROITE -->
        <div style="overflow-y:hidden; position:relative;">
            <div id="droite" style="width:100%; height:100%; overflow-y:scroll;">
                <!-- RENDU -->
                <iframe
                    id="preview"
                    title="Aperçu"
                    sandbox=""
                    referrerpolicy="no-referrer"
                    style="display:block; width:100%; height:100%; border:0;">
                </iframe>
                <!-- /RENDU -->
            </div>
        </div>

    </div>

    @include('inc-bottom-js')


    <!-- GRID -->
    <script src="{{ asset('lib/split-grid/split-grid.js') }}" charset="utf-8"></script>
    <script>
        function resizeEditors() {
            if (window.editor_html) editor_html.resize();
            if (window.editor_css)  editor_css.resize();
        }
        Split({
            minSize: 0,
            columnGutters: [{ track: 1, element: document.querySelector('.gutter-col-1') }],
            onDrag: resizeEditors,
            onDragEnd: resizeEditors
        });
        window.addEventListener('resize', resizeEditors);
    </script>
    <!-- /GRID -->


    <!-- ACE -->
    <script src="{{ asset('js/ace/ace.js') }}" charset="utf-8"></script>
    <script>
        var editor_html = ace.edit("editor_html", {
            theme: "ace/theme/puzzle_vscode",
            mode: "ace/mode/html",
            fontSize: 13,
            wrap: true,
            useWorker: false,
            autoScrollEditorIntoView: false,
            highlightActiveLine: false,
            highlightSelectedWord: false,
            highlightGutterLine: true,
            showPrintMargin: false,
            displayIndentGuides: true,
            showLineNumbers: true,
            showGutter: true,
            showFoldWidgets: false,
            useSoftTabs: true,
            navigateWithinSoftTabs: false,
            tabSize: 2
        });
        editor_html.container.style.lineHeight = 1.5;

        var editor_css = ace.edit("editor_css", {
            theme: "ace/theme/puzzle_vscode",
            mode: "ace/mode/css",
            fontSize: 13,
            wrap: true,
            useWorker: false,
            autoScrollEditorIntoView: false,
            highlightActiveLine: false,
            highlightSelectedWord: false,
            highlightGutterLine: true,
            showPrintMargin: false,
            displayIndentGuides: true,
            showLineNumbers: true,
            showGutter: true,
            showFoldWidgets: false,
            useSoftTabs: true,
            navigateWithinSoftTabs: false,
            tabSize: 2
        });
        editor_css.container.style.lineHeight = 1.5;

        editor_html.setValue({!! json_encode(trim($page->html ?? '')) !!}, -1); 
        editor_css.setValue({!! json_encode(trim($page->css ?? '')) !!}, -1);

        @if ($public)
        editor_html.setReadOnly(true); 
        editor_css.setReadOnly(true);
        @endif

    </script>
    <!-- /ACE -->


    <!-- BASCULE EDITEURS -->
    <script>
        const titleHtml = document.getElementById('titleHtml');
        const titleCss  = document.getElementById('titleCss');
        const paneHtml  = document.getElementById('paneHtml');
        const paneCss   = document.getElementById('paneCss');
        document.getElementById('btnHtml').addEventListener('click', function () {
            titleHtml.style.display = 'block';
            titleCss.style.display  = 'none';
            paneHtml.style.display = 'block';
            paneCss.style.display  = 'none';
            setTimeout(() => { editor_html.resize(); editor_html.focus(); }, 0);
        });
        document.getElementById('btnCss').addEventListener('click', function () {
            titleCss.style.display  = 'block';
            titleHtml.style.display = 'none';
            paneCss.style.display  = 'block';
            paneHtml.style.display = 'none';
            setTimeout(() => { editor_css.resize(); editor_css.focus(); }, 0);
        });
    </script>
    <!-- /BASCULE EDITEURS -->


    <!-- RENDU IFRAME -->
    <script>
        function buildSandboxedCode(htmlSource, cssSource) {
            const csp = [
                "default-src 'none'",
                "style-src 'unsafe-inline'", // autorise <style>
                "img-src 'self' https: data: blob:",
                "font-src data: blob:",
                "media-src 'self' https: data: blob:",
            ].join('; ');
            const cspTag = `<meta http-equiv="Content-Security-Policy" content="${csp}">`;
            const styleTag = `<style>
                /* Reset minimal pour l'aperçu */
                html, body { margin: 0; padding: 0; }
                /* Code de l'éditeur CSS */
                ${cssSource || ""}
                </style>`;
            const injection = `\n${cspTag}\n${styleTag}\n`;
            const injectionPoint = /(<\/head>|<body>)/i;
            if (!injectionPoint.test(htmlSource)) {
                return injection + htmlSource;
            }
            return htmlSource.replace(injectionPoint, `${injection}$1`);
        }

        // --- met à jour l’iframe d’aperçu en toute sécurité
        function renderPreview() {
            try {
                const htmlCode = editor_html.getValue();
                const cssCode  = editor_css.getValue();
                const doc = buildSandboxedCode(htmlCode, cssCode); 
                const iframe = document.getElementById('preview');
                iframe.srcdoc = doc;
            } catch (e) {
                console.error('Erreur rendu aperçu:', e);
            }
        }

        let t;
        function scheduleRender() {
            clearTimeout(t);
            t = setTimeout(renderPreview, 250);
        }
        
        editor_html.session.on('change', scheduleRender);
        editor_css.session.on('change', scheduleRender);

        renderPreview();
    </script>
    <!-- /RENDU IFRAME -->


    @if (!$public)
    <!-- SAUVEGARDE AUTOMATIQUE -->
    <script>
        async function saveToDatabase(htmlContent, cssContent) {
            try {
                const response = await fetch('/html/autosave', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id: '{{ Crypt::encryptString($page->id) }}', 
                        html: htmlContent,
                        css: cssContent,
                    })
                });
                if (!response.ok) {
                    // Afficher une erreur si le statut HTTP n'est pas 2xx
                    console.error('Échec de la sauvegarde automatique.', response.statusText);
                } else {
                    const message = await response.text();
                    console.log('Réponse du contrôleur:', message);
                }
            } catch (error) {
                console.error('Erreur réseau ou lors de la requête:', error);
            }
        }

        let saveTimer;
        function saveEditorsSoon() {
            clearTimeout(saveTimer);
            saveTimer = setTimeout(() => {
                const html = editor_html.getValue();
                const css  = editor_css.getValue();
                saveToDatabase(html, css); 
            }, 5000);
        }

        editor_html.session.on('change', saveEditorsSoon);
        editor_css.session.on('change',  saveEditorsSoon);

    </script>
    <!-- /SAUVEGARDE AUTOMATIQUE -->
    @endif


    <!-- TELECHARGER -->
    <script>
        function downloadText(filename, text, mime='text/plain;charset=utf-8') {
            const blob = new Blob([text], { type: mime });
            const url  = URL.createObjectURL(blob);
            const a    = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);   // nécessaire pour Safari
            a.click();
            a.remove();
            setTimeout(() => URL.revokeObjectURL(url), 0);
        }

        const dlHtmlIcon = document.getElementById('downloadHtml');
        const dlCssIcon  = document.getElementById('downloadCss');

        // Téléchargement HTML
        if (dlHtmlIcon) {
            dlHtmlIcon.addEventListener('click', () => {
                const code = editor_html.getValue();
                downloadText('{{$page->jeton}}-index.html', code, 'text/html;charset=utf-8');
            });
        }

        // Téléchargement CSS
        if (dlCssIcon) {
            dlCssIcon.addEventListener('click', () => {
                const code = editor_css.getValue();
                downloadText('{{$page->jeton}}-style.css', code, 'text/css;charset=utf-8');
            });
        }
    </script>
    <!-- /TELECHARGER -->


    <!-- COPIER -->
    <script>
        async function copyToClipboard(text) {
            // 1) API moderne si dispo (HTTPS/localhost)
            if (navigator.clipboard?.writeText) {
                await navigator.clipboard.writeText(text);
                return true;
            }
            // 2) Fallback DOM
            try {
                const ta = document.createElement('textarea');
                ta.value = text;
                ta.setAttribute('readonly', '');
                ta.style.position = 'fixed';
                ta.style.top = '-1000px';
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
                return true;
            } catch {
                return false;
            }
        }

        function flashIcon(iconEl) {
            // Disparition immédiate…
            iconEl.classList.add('is-hidden');
            // … puis réapparition en fondu
            setTimeout(() => {
                iconEl.classList.remove('is-hidden');
            }, 500);
        }

        function bindCopy(iconId, editor, filenameHint) {
            const el = document.getElementById(iconId);
            if (!el) return;
            el.addEventListener('click', async () => {
                const ok = await copyToClipboard(editor.getValue());
                if (ok) {
                    flashIcon(el);
                    // feedback accessibilité/discret en tooltip
                    const old = el.title;
                    el.title = 'Copié !';
                    setTimeout(() => { el.title = old; }, 1200);
                } else {
                    el.title = 'Échec de la copie';
                }
            });
        }

        bindCopy('copyHtml', editor_html, 'index.html');
        bindCopy('copyCss',  editor_css,  'style.css');
    </script>
    <!-- /COPIER -->

</body>
</html>
