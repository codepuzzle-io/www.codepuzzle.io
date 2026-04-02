<?php
$public = true;
if (isset($jeton_secret)) {
    $python = App\Models\Python::where('jeton_secret', $jeton_secret)->first();
    if (!isset($python)) {
        echo "<pre>Cette page n'existe pas.</pre>";
        exit();
    }
    $public = false;
}
if (isset($jeton)) {
    $python = App\Models\Python::where('jeton', $jeton)->first();
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
        #panePython, #paneSettings {position:absolute; top:0; right:0; bottom:0; left:0;}
        #paneSettings { position:absolute; inset:0; overflow-y:auto; -webkit-overflow-scrolling: touch; }
        #editor_python {position:absolute; top:0; right:0; bottom:0; left:0;}

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

        .fade-btn { transition: opacity 200ms linear; }
        .fade-btn.is-fading { opacity: 0; }

        .console-theme-sombre {
            background-color:#0d0d0d;
            color:white;
        }
        .console-theme-clair {
            background-color:white;
            color:#24292e;
        }

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
        .scrollbar-theme {
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

        .ace_scrollbar::-webkit-scrollbar-corner {
            background: #15222e;
        }

        .ace_scrollbar::-webkit-scrollbar-thumb:hover {
            background: #555; /* Couleur au survol */
        }

        .scrollbar-theme::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        .scrollbar-theme::-webkit-scrollbar-track {
            background: #15222e;
            border-radius: 5px;
        }
        .scrollbar-theme::-webkit-scrollbar-thumb {
            background: #2c3e50;
        }
        .scrollbar-theme::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .scrollbar-theme::-webkit-scrollbar-corner {
            background: #15222e;
        }
        </style>
    @endif

</head>
<body>

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
                                    <a href="/" target="_blank"><img src="{{ asset('img/code-puzzle-icon.png') }}" height="28" alt="CODE PUZZLE" /></a>
                                </div>

                                <div class="col-auto ml-4 pt-1">
                                    <span id="downloadPython" data-toggle="tooltip" data-placement="top" title="télécharger" style="cursor: pointer;"><i class="fa-solid fa-file-arrow-down" style="color:#93a3bc;cursor:pointer;"></i></span>
                                </div>

                                <div class="col-auto ml-3 pt-1">
                                    <span data-toggle="tooltip" data-placement="top" title="copier"><i id="copyPython" class="fa-solid fa-copy copy-icon" style="color:#93a3bc;cursor:pointer;"></i></span>
                                </div>

                                @if (!$public)
                                <div class="col-auto ml-3 pt-1">
                                    <i id="btnSettings" class="fa-solid fa-gear" style="color:#71bb22;cursor:pointer;"></i>
                                </div>
                                @endif

                                <div class="col-auto ml-3 pt-1">
                                    <span data-toggle="tooltip" data-placement="top" title="redémarrer l'environnement" onclick="location.reload()"><i id="btnSettings" class="fa-solid fa-rotate" style="color:#93a3bc;cursor:pointer;"></i></span>
                                </div>

                                <div class="col text-right ml-3">
                                    <div>
                                        <button id="run_0" onclick="run('0')" style="width:40px;" type="button" class="btn btn-primary text-center mb-1 btn-sm"><i class="fas fa-circle-notch fa-spin"></i></button>
                                    </div>
                                    <div id="restart_0" style="display:none;">
                                        <button style="width:40px;" type="button" onclick="restart('0')"  class="btn btn-dark2 btn-sm" style="padding-top:6px;" data-toggle="tooltip" data-placement="right"  data-trigger="hover" title="{{__('Cliquer ici pour interrompre l\'exécution du code et relancer l\'environnement. Le redémarrage peut prendre quelques secondes.')}}"><i class="fas fa-stop"></i></button>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                    <!-- /EN-TETE -->

                    <!-- ÉDITEURS -->
                    <div id="workspace">

                        <!-- PYTHON -->
                        <div id="panePython">
                            <div id="editor_python"></div>
                        </div>

                        <!-- SETTINGS -->
                        <div id="paneSettings" class="scrollbar-theme" style="display:none;">

                            <div class="pl-3 pr-3 mb-5 text-monospace">
                                
                                <div class="mb-2">
                                    <button id="btnPython" type="button" class="btn btn-dark2 btn-sm"><i class="fa-solid fa-xmark"></i></button>
                                </div>

                                <div class="mt-3 text-danger small"><b>LIEN PRIVÉ - NE PAS DIFFUSER</b> - Ce lien permet de rouvrir l'éditeur et de reprendre le développement.</div>
                                <div class="mt-1 p-2 text-danger border border-danger rounded">www.codepuzzle.io/python-studio/{{strtoupper($python->jeton_secret)}}</div>
                                <div class="mt-4 small"><b>Lien public</b> - Ce lien permet de consulter cette page sans pouvoir la modifier.</div>
                                <div class="mt-1 p-2 border border-secondary rounded">www.codepuzzle.io/python/{{strtoupper($python->jeton)}}</div>

                                <div class="mt-5 text-center"><i class="fa-solid fa-sliders fa-lg" style="color:#93a3bc"></i></div>

                                <!-- BIBLIOTHEQUES -->
                                <div class="mt-3 text-monospace">{{mb_strtoupper(__('bibliotheques'))}} <span class="font-italic small" style="color:silver;">optionnel</span></div>
                                <div class="mb-1 small text-monospace text-muted text-justify">Séparer les noms des bibliothèques par des virgules. Chaque bibliothèque sera chargée au démarrage.</div>
                                <input id="bibliotheques" class="form-control" name="bibliotheques" value="{{ $python->bibliotheques ?? '' }}" />
                                <button id="btnSaveBibliotheques" type="button" class="mt-2 pl-3 pr-3 btn btn-success btn-sm fade-btn" onclick="saveToDatabase(this)"><i class="fa-solid fa-check"></i></button>
                                <!-- /BIBLIOTHEQUES -->	

                                <!-- FICHIERS -->
                                <div class="mt-4 text-monospace">{{mb_strtoupper(__('fichiers'))}} <span class="font-italic small" style="color:silver;">optionnel</span></div>
                                <div class="mb-1 small text-monospace text-muted text-justify">Les fichiers doivent être hébergés sur internet. Saisir une URL par ligne.<br />Chaque fichier sera téléchargé au démarrage et copié dans le système de fichiers. Ainsi, le code Python pourra lire/importer/manipuler ces fichiers. Le nom du fichier est déduit du dernier segment de l'URL (ex. <code>…/donnees.py</code> → <code>donnees.py</code>).</div>
                                <textarea id="fichiers" class="form-control" name="fichiers" rows="2" style="overflow:hidden;resize:none;" oninput="this.style.height='auto';this.style.height=this.scrollHeight+2+'px'">{{ $python->fichiers ?? '' }}</textarea>
                                <button id="btnSaveFichiers" type="button" class="mt-2 pl-3 pr-3 btn btn-success btn-sm fade-btn" onclick="saveToDatabase(this)"><i class="fa-solid fa-check"></i></button>
                                <!-- /FICHIERS -->	

                            </div>
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
            <div id="droite" class="text-monospace console-theme-sombre" style="width:100%; height:100%; overflow-y:scroll;">

                <!-- FICHIERS CHARGES -->
                <div id="pyodide_fs_block" class="text-white d-none">
                    <div class="p-3 pb-4" style="background-color:#ebf1f7">
                        <div class="font-weight-bold text-dark">FICHIERS</div>
                        <pre id="pyodide_fs" class="mt-0 mb-0 text-muted ml-1" style="white-space:pre-wrap;line-height:1.2"></pre>
                    </div>
                </div>
                <!-- /FICHIERS CHARGES -->

                <!-- CONSOLE -->
                <div id="consoleDiv" class="p-3 ">
                    <div id="console_0" style="position:relative;">
                        <div class="small text-monospace" style="position:absolute;color:gray;right:10px;top:0px;">console</div>
                        <div class="small text-monospace" style="position:absolute;color:gray;right:20px;top:25px;"><i class="fa-solid fa-eraser fa-xl" style="cursor:pointer;" onclick="(function(){const el = document.getElementById('output_0');el.innerHTML = '';})()" data-toggle="tooltip" data-placement="left" title="effacer"></i></div>
                        <div class="small text-monospace" style="position:absolute;color:gray;right:20px;top:55px;"><i class="fa-solid fa-circle-half-stroke fa-xl" style="cursor:pointer;" onclick="basculeTheme()" data-toggle="tooltip" data-placement="left" title="clair / sombre"></i></div>
                        <div id="output_0" class="text-monospace" style="white-space: pre-wrap;min-height:100px;height:100%;"></div>
                    </div>
                </div>
                <!-- /CONSOLE -->
            </div>
        </div>

    </div>

    @include('inc-bottom-js')


    <!-- GRID -->
    <script src="{{ asset('lib/split-grid/split-grid.js') }}" charset="utf-8"></script>
    <script>
        function resizeEditors() {
            if (window.editor_python) editor_python.resize();
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

    <!-- BASCULE THEME -->
    <script>
        function basculeTheme() {
            const el = document.getElementById('droite');
            if (!el) return;

            if (el.classList.contains('console-theme-sombre')) {
                el.classList.remove('console-theme-sombre');
                el.classList.add('console-theme-clair');
            } else {
                el.classList.remove('console-theme-clair');
                el.classList.add('console-theme-sombre');
            }
        }
    </script>
    <!-- /BASCULE THEME -->

    <!-- ACE -->
    <script src="{{ asset('js/ace/ace.js') }}" charset="utf-8"></script>
    <script>
        var editor_python = ace.edit("editor_python", {
            theme: "ace/theme/puzzle_vscode",
            mode: "ace/mode/python",
            fontSize: 15,
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
            tabSize: 4
        });
        editor_python.container.style.lineHeight = 1.5;

        editor_python.setValue({!! json_encode(trim($python->python ?? '')) !!}, -1); 

        @if ($public)
        editor_python.setReadOnly(true); 
        @endif

    </script>
    <!-- /ACE -->


    <!-- BASCULE EDITEURS -->
    <script>
        const panePython  = document.getElementById('panePython');
        const paneSettings   = document.getElementById('paneSettings');
        document.getElementById('btnPython').addEventListener('click', function () {
            panePython.style.display = 'block';
            paneSettings.style.display  = 'none';
            setTimeout(() => { editor_python.resize(); editor_python.focus(); }, 0);
        });
        document.getElementById('btnSettings').addEventListener('click', function () {
            paneSettings.style.display  = 'block';
            panePython.style.display = 'none';
        });
    </script>
    <!-- /BASCULE EDITEURS -->


    @if (!$public)
    <!-- SAUVEGARDE AUTOMATIQUE -->
    <script>
        async function saveToDatabase(btnId = null) {
            try {
                const response = await fetch('/python/autosave', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id: '{{ Crypt::encryptString($python->id) }}', 
                        python: editor_python.getValue(),
                        bibliotheques: document.getElementById('bibliotheques').value,
                        fichiers: document.getElementById('fichiers').value
                    })
                });
                if (!response.ok) {
                    // Afficher une erreur si le statut HTTP n'est pas 2xx
                    console.error('Échec de la sauvegarde automatique.', response.statusText);
                } else {
                    const message = await response.text();
                    console.log('Réponse du contrôleur:', message);
                    if (btnId) {
                        btnId.disabled = true;
                        btnId.classList.add('is-fading');
                        setTimeout(() => {
                            btnId.classList.remove('is-fading'); 
                            setTimeout(() => { btnId.disabled = false; }, 200);
                        }, 1000);                       
                    }
                }
            } catch (error) {
                console.error('Erreur réseau ou lors de la requête:', error);
            }
        }

        let saveTimer;
        function saveEditorsSoon() {
            clearTimeout(saveTimer);
            saveTimer = setTimeout(() => {
                saveToDatabase(); 
            }, 4000);
        }

        editor_python.session.on('change', saveEditorsSoon);

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

        const dlPythonIcon = document.getElementById('downloadPython');

        // Téléchargement HTML
        if (dlPythonIcon) {
            dlPythonIcon.addEventListener('click', () => {
                const code = editor_python.getValue();
                downloadText('{{$python->jeton}}-code.py', code, 'text/html;charset=utf-8');
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

        bindCopy('copyPython', editor_python, 'code.py');
    </script>
    <!-- /COPIER -->


    {{-- == Helpers ========================================================== --}}
    <script>

        // == ==
        // Mime
        function guessMime(name) {
            const ext = (name.split('.').pop() || '').toLowerCase();
            const map = {
                'txt':'text/plain;charset=utf-8','md':'text/markdown;charset=utf-8','csv':'text/csv;charset=utf-8',
                'json':'application/json','pdf':'application/pdf',
                'png':'image/png','jpg':'image/jpeg','jpeg':'image/jpeg','gif':'image/gif','webp':'image/webp','svg':'image/svg+xml',
                'wav':'audio/wav','mp3':'audio/mpeg','ogg':'audio/ogg',
                'mp4':'video/mp4','webm':'video/webm',
                'py':'text/plain;charset=utf-8',
                'html':'text/html;charset=utf-8',
                'css':'text/plain;charset=utf-8',
                'js':'text/plain;charset=utf-8',
                'zip':'application/zip'
            };
            return map[ext] || 'application/octet-stream';
        }


        function normalizeInlineMime(mime, filename) {
            const ext = (filename.split('.').pop() || '').toLowerCase();
            // Pour éviter tout téléchargement parasite, on force du texte brut
            // sur les types "code" et assimilés.
            const forcePlain = new Set(['py','js','ts','css','md','csv','tsv','log','ipynb']);
            if (forcePlain.has(ext)) return 'text/plain;charset=utf-8';

            // JSON/SVG: certains navigateurs se méfient si le mime est incohérent
            if (ext === 'json') return 'application/json';
            if (ext === 'svg')  return 'image/svg+xml';

            return mime || 'application/octet-stream';
        }

        function canOpenInline(mime, filename='') {
            const m = (mime || '').toLowerCase();
            const ext = (filename.split('.').pop() || '').toLowerCase();

            const inlineTypes = [
                /^text\//, /^image\//, /^audio\//, /^video\//,
                /^application\/pdf$/, /^application\/json$/, /^application\/xml$/,
                /^image\/svg\+xml$/
            ];
            const textLikeExt = new Set(['md','csv','tsv','log','py','js','ts','css','html','htm','svg','ipynb','json','txt']);

            return inlineTypes.some(rx => rx.test(m)) || textLikeExt.has(ext);
        }

        function saveOrOpenBuffer(buffer, filename, mimeFromWorker) {
            const type     = normalizeInlineMime(mimeFromWorker || guessMime(filename), filename);
            const blob     = new Blob([buffer], { type });
            const url      = URL.createObjectURL(blob);
            const canInline= canOpenInline(type, filename);

            // Ouverture inline : on NE déclenche PAS le download.
            if (canInline) {
                // Technique <a target="_blank"> : plus fiable que window.open sur certains bloqueurs
                const a = document.createElement('a');
                a.href = url;
                a.target = '_blank';   // ouverture dans un onglet
                a.rel = 'noopener';
                // surtout PAS d’attribut download ici
                document.body.appendChild(a);
                a.click();
                a.remove();

                // Laisser au nouvel onglet le temps de charger le blob avant révocation
                setTimeout(() => URL.revokeObjectURL(url), 60_000);
                return; // <- on sort ici : pas de fallback download
            }

            // Sinon : téléchargement explicite
            const a = document.createElement('a');
            a.href = url;
            a.download = filename || 'fichier';
            document.body.appendChild(a);
            a.click();
            a.remove();
            setTimeout(() => URL.revokeObjectURL(url), 0);
        }

        // Envoi une demande de lecture au worker et attend la réponse
        function requestFileFromWorker(worker, path, { timeoutMs = 30000 } = {}) {
            return new Promise((resolve, reject) => {
                const id = 'rf_' + Math.random().toString(36).slice(2);
                let timer = null;

                const onMsg = (e) => {
                const d = e.data || {};
                if (d.type !== 'fs.readFile.result' || d.id !== id) return;
                worker.removeEventListener('message', onMsg);
                if (timer) clearTimeout(timer);
                if (d.error) return reject(new Error(d.error));
                resolve({ buffer: d.buffer, path: d.path, mime: d.mime });
                };

                worker.addEventListener('message', onMsg);

                // Sécurité : timeout
                timer = setTimeout(() => {
                worker.removeEventListener('message', onMsg);
                reject(new Error(`Timeout lecture worker pour ${path}`));
                }, timeoutMs);

                worker.postMessage({ cmd: 'readFile', id, path });
            });
        }

        function setupFsDownloadDelegation(containerEl, worker) {
            if (!containerEl || !worker) return;
            if (containerEl._fsDlBound) return; // évite les doublons si rerender
            containerEl._fsDlBound = true;

            containerEl.addEventListener('click', async (evt) => {
                const a = evt.target.closest('a.fs-download');
                if (!a) return;
                evt.preventDefault();

                const path = a.getAttribute('data-path');
                const filename = path.split('/').pop() || 'fichier';
                try {
                    //const { buffer, mime } = await requestFileFromWorker(worker, path);
                    //saveBufferAsFile(buffer, filename, mime);
                    const { buffer, mime } = await requestFileFromWorker(worker, path);
                    saveOrOpenBuffer(buffer, filename, mime);
                } catch (err) {
                    console.error(err);
                    alert(`Impossible de télécharger ${filename} : ${err.message || err}`);
                }
            });
        }

        // ========================================================================

        function formatBytes(n) {
            if (n == null) return '';
            const k = 1024, units = ['octets','Ko','Mo','Go','To'];
            let i = 0, v = n;
            while (v >= k && i < units.length - 1) { v /= k; i++; }
            return (i === 0 ? v : v.toFixed(1)) + ' ' + units[i];
        }

        // == Systeme de fichiers de Pyodide ======================================
        function renderFsListing(fsPayload) {
            const card = document.getElementById('pyodide_fs_block');
            const el   = document.getElementById('pyodide_fs');
            if (!card || !el) return;

            if (fsPayload.error) {
                card.classList.remove('d-none');
                el.textContent = `Erreur FS (${fsPayload.path}) : ${fsPayload.error}`;
                return;
            }

            const basePath = fsPayload.path || '/';
            const rawItems = fsPayload.items || [];

            // Normalise : accepte soit [{name, path, kind, size}], soit ["nom.ext", ...]
            const items = rawItems.map(it => {
                if (typeof it === 'string') {
                const full = (basePath.endsWith('/') ? basePath : basePath + '/') + it;
                return { name: it, path: full, kind: 'file', size: null };
                }
                return it;
            });

            if (items.length === 0) {
                card.classList.add('d-none');
                el.textContent = '';
                return;
            }

            card.classList.remove('d-none');

            // Tri simple : dossiers d’abord, puis fichiers
            items.sort((a, b) => (a.kind === b.kind) ? a.name.localeCompare(b.name) : (a.kind === 'dir' ? -1 : 1));

            const html = items.map(({ name, path, kind, size }, i, arr) => {
                const isLast = i === arr.length - 1;
                const filePrefix = isLast ? '┗ ' : '┣ ';   // ← symbole différent pour le dernier
                if (kind === 'dir') {
                    // Dossier : pas de téléchargement (on pourrait plus tard naviguer)
                    return `📁 <span>${name}</span>`;
                } else {
                    // Fichier : lien + taille
                    const tail = size != null ? ` <span class="opacity-50 small" style="vertical-align:1px;">(${formatBytes(size)})</span>` : '';
                    return `<span style="vertical-align:-2px">${filePrefix}</span><a href="#" class="fs-download" data-path="${path}" >${name}</a>${tail}`;
                }
            }).join('\n');

            el.innerHTML = html; // <pre> accepte l'HTML, et garde tes retours à la ligne
        }
        // Peut être un texte (1 URL par ligne) ou déjà un tableau.
        const urlsFromSujet = @json($python->fichiers ?? '');
        // Normalise en tableau d’URLs (trim + suppression des lignes vides)
        function normalizeUrls(input) {
            if (Array.isArray(input)) {
            return input.map(s => (s ?? '').toString().trim()).filter(Boolean);
            }
            const raw = (input ?? '').toString();
            return raw.split(/\r?\n/).map(s => s.trim()).filter(Boolean);
        }
        const PRELOAD_URLS = normalizeUrls(urlsFromSujet);
        // ========================================================================

        // == Affichage bouton restart ============================================
        let restartTimer0 = null;      // timer pour le bouton restart_0
        let restartVisible0 = false;   // état visuel actuel (évite les clignotements)

        function showRestartDelayed() {
            // Si un timer précédent existe, on l'annule
            if (restartTimer0) {
                clearTimeout(restartTimer0);
                restartTimer0 = null;
            }
            // Lance un nouveau timer de 4s
            restartTimer0 = setTimeout(() => {
                const el = document.getElementById("restart_0");
                if (!el) return;
                el.style.display = 'block';
                restartVisible0 = true;
                restartTimer0 = null; // le timer a servi
            }, 4000);
        }

        function hideRestartNow() {
            // Annule tout affichage différé encore en attente
            if (restartTimer0) {
                clearTimeout(restartTimer0);
                restartTimer0 = null;
            }
            // Cache si visible
            const el = document.getElementById("restart_0");
            if (el && restartVisible0) {
                el.style.display = 'none';
            }
            restartVisible0 = false;
        }
        // ========================================================================



        // == Gestion des messages du Web Worker ==================================
        function setupWorkerListener(worker) {
            worker.onmessage = function(event) {
                const data = event.data || {};  
                console.log("WEBWORKER EVENT: ", data);

                if (data.init) {
                    console.log("PRET!");

                    document.getElementById("output_0").innerText = "Prêt!\n";
                    document.getElementById("run_0").innerHTML = '<i class="fas fa-play"></i>';
                    document.getElementById("run_0").disabled = false;
                    document.getElementById("restart_0").style.display = 'none';

                    // ➜ Envoyer les URLs au worker (si présentes)
                    if (PRELOAD_URLS.length > 0) {
                        worker.postMessage({ cmd: 'putUrls', urls: PRELOAD_URLS, dir: '/home/pyodide' });
                    }

                    // Lister d’abord: si putUrls prend  un petit temps, on relistera après
                    worker.postMessage({ cmd: 'ls', path: '/home/pyodide' });
                    return;
                }

                // ➜ Réception des résultats de téléchargement
                if (data.putUrls) {
                    if (data.putUrls.error) {
                        console.error('putUrls error:', data.putUrls.error);
                    } else {
                        console.log('putUrls results:', data.putUrls.results);
                    }
                    // Rafraîchir la liste après dépôts
                    worker.postMessage({ cmd: 'ls', path: data.putUrls.dir || '/home/pyodide' });
                    return;
                }

                // [FS GLOBAL] — afficher la réponse de listing global
                if (data.fs) {
                    renderFsListing(data.fs);
                    return;
                }

                if (data.status) {
                    if (data.status === 'running') {
                        document.getElementById("run_0").innerHTML = '<i class="fas fa-cog fa-spin"></i>';
                        document.getElementById("run_0").disabled = true;
                        // Démarre l'affichage différé (4 s)
                        showRestartDelayed();
                    }

                    if (data.status === 'completed') {
                        document.getElementById("run_0").innerHTML = '<i class="fas fa-play"></i>';
                        document.getElementById("run_0").disabled = false;
                        // Cache immédiatement (et annule le timer si en attente)
                        hideRestartNow();
                        // Rafraîchir le listing global après chaque exécution Python
                        // (petit délai pour laisser le FS “finir” d’écrire si besoin)
                        setTimeout(() => {
                            worker.postMessage({ cmd: 'ls', path: '/home/pyodide' });
                        }, 0);
                    }
                    return;
                }

                if (data.output) {
                    const out = document.getElementById("output_0");
                    if (out) out.innerHTML += data.output;
                    return;
                }
            };
        }
        // ========================================================================

    </script>
    {{-- == /Helpers ========================================================= --}}


    {{-- == Webworker ======================================================== --}}
    <script>

        var pyodideWorker = new Worker("{{ asset('pyodideworker/python-pyodideWorker.js') }}");

        // Attacher les événements au Web Worker initial
        setupWorkerListener(pyodideWorker);

        // Délégation de clic pour les liens de fichiers (une fois)
        setupFsDownloadDelegation(document.getElementById('pyodide_fs'), pyodideWorker);

        // Envoi des données au Web Worker pour exécution
        function run() {
            console.log('RUN');
            const code = editor_python.getValue();
            const bibliotheques = @json($python->bibliotheques ?? []);
            document.getElementById("output_0").innerHTML = "";
            pyodideWorker.postMessage({ code: code, bibliotheques: bibliotheques});
        }

        // Fonction pour redémarrer le Web Worker
        function restart(id) {
            if (pyodideWorker) {
                pyodideWorker.terminate();
                console.log("Web Worker supprimé.");
            }

            // Recréer un nouveau Web Worker
            pyodideWorker = new Worker("{{ asset('pyodideworker/python-pyodideWorker.js') }}");
            console.log("Web Worker redémarré.");

            // Réattacher l'écouteur onmessage au nouveau worker
            setupWorkerListener(pyodideWorker);

            // Réinitialiser les boutons d'exécution et d'arrêt
            document.getElementById("output_0").innerText = "Initialisation...\n";
            document.getElementById("run_0").innerHTML = '<i class="fas fa-circle-notch fa-spin"></i>';
            document.getElementById("run_0").disabled = true;
            document.getElementById("restart_0").style.display = 'none';
        }

        // Initialisation
        document.getElementById("output_0").innerText = "Initialisation...\n";
        document.getElementById("run_0").disabled = true;
        document.getElementById("restart_0").style.display = 'none';

    </script>
    {{-- == /Webworker ======================================================= --}}


</body>
</html>
