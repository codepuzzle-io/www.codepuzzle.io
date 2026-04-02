<?php
function extractPythonCode(string $text): string
{
    // 1) Bloc ```python … ```
    if (preg_match('/```python\s*([\s\S]*?)\s*```/i', $text, $m1)) {
        return trim($m1[1]);
    }

    // 2) Bloc ``` … ```
    if (preg_match('/```\s*([\s\S]*?)\s*```/i', $text, $m2)) {
        return trim($m2[1]);
    }

    // 3) Pas de délimiteurs : on retourne tout (sans trim pour préserver indentations)
    return $text;
}
$capture = App\Models\Capture::find(Crypt::decryptString($capture_id));
$image = base64_encode($capture->image);
$code = extractPythonCode($capture->code);
?>
<!doctype html>
<html lang="fr">
<head>
    @include('inc-meta')
    <title>{{ config('app.name') }} | {{ ucfirst(__('console')) }}</title>
    <style>
        html,body {
          	height: 100%;
        }
        .grid {
            display: grid;
            grid-template-columns: 1fr 20px 1fr;
            height:calc(100% - 100px);
        }
        .gutter-col {
            grid-row: 1/-1;
            cursor: col-resize;
            background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAeCAYAAADkftS9AAAAIklEQVQoU2M4c+bMfxAGAgYYmwGrIIiDjrELjpo5aiZeMwF+yNnOs5KSvgAAAABJRU5ErkJggg==');
            background-color: rgb(229, 231, 235);
            background-repeat: no-repeat;
            background-position: 50%;
            border-radius:6px;
            width:20px;
            margin-top:27px;
            margin-bottom:10px;
        }
        .gutter-col-1 {
            grid-column: 2;
        }
    </style>
</head>
<body>

    @php
		$lang_switch = '<a href="/console/lang/fr" class="kbd mr-1">fr</a><a href="/console/lang/en" class="kbd">en</a>';
	@endphp
 

    <div class="p-3" style="height:100px;">
        <a class="btn btn-light btn-sm mr-4" href="/console/captures" role="button"><i class="fas fa-arrow-left"></i></a>
        <img src="https://codepuzzle-dev/img/code-puzzle.png" width="140" alt="CODE PUZZLE - PYTHON">
    </div>
    <div class="grid">

        <div style="overflow-y:scroll;direction:rtl;padding:0px;">
            <div style="direction:ltr;top:0px;z-index:1000;width:100%;height:100%">

                <!-- GAUCHE -->
                <div style="padding:0px 20px 20px 20px">
                
                    <!-- Code extrait -->
                    <div class="text-monospace">CODE EXTRAIT</div>
                    <div id="code_editor_eleve" class="mt-1 mb-2 code-editor"></div>
                    <!-- Console -->
                    <div class="row no-gutters">
                        <div class="col-auto mr-2">
                            <div class="text-right">
                                <!-- BOUTON RUN -->
                                <button id="run" data-pyodide="run" onclick="run()" type="button" class="btn btn-primary text-center mb-1" style="width:40px;"><i class="fas fa-circle-notch fa-spin"></i></button>
                                <!-- BOUTON RESTART -->
                                <button id="restart" data-pyodide="restart" onclick="restart()" type="button" class="btn btn-dark text-center mb-1" style="width:40px;display:none;" data-toggle="tooltip" data-placement="right"  data-trigger="hover" title="{{__('Cliquer ici pour interrompre  l\'exécution du code. Python redémarrera complètement mais votre code sera conservé dans l\'éditeur. Le redémarrage peut prendre quelques secondes.')}}"><i class="fas fa-stop"></i></button>
                            </div>
<!-- copier prompt dans presse papier -->
<?php
$prompt = "
Vous êtes un assistant pédagogique expert en Python.  
Je vais vous fournir le code que l'élève a proposé.  

Merci de me rendre un rapport structuré comportant deux parties.

**PARTIE 1 - Présentation synthétique**
Dire si code est correct. Et si il ne l'est pas, faire la liste des erreurs en faisant référence, si possible, aux numéros de ligne du code.

**PARTIE 2 - Présentation détaillée**
1. **Points techniques et logiques**  
- Erreurs à corriger (avec référence aux numéros de ligne).  
- Améliorations possibles de l'algorithme (complexité, clarté).  
2. **Qualité du code**  
- Respect des conventions Python (PEP 8, noms de variables, docstrings, etc.).  
- Lisibilité et structuration (fonctions, modularité).  
3. **Propositions de tests**  
- Jeux de données pour tester chaque situation (valeurs normales, extrêmes, cas particuliers).  
- Version avec des `assert` prêts à être copiés-collés
4. **Suggestions pédagogiques**  
- Explications à donner à l'élève pour chaque erreur.  
- Exercices complémentaires ou variantes pour approfondir.  

---

**CODE DE L'ÉLÈVE :**  
```python
{$code}
```
Merci de structurer votre réponse en sections numérotées et de citer les lignes de code concernées lorsque vous signalez un problème.";
?>
                            <!-- BOUTON IA -->
                            <div class="mt-1 text-right">
                                <button type="button" onclick='copyAndNotify(this, {!! json_encode($prompt, JSON_UNESCAPED_UNICODE|JSON_HEX_APOS) !!})' class="btn btn-ia btn-xs" data-toggle="tooltip" data-placement="right"  data-trigger="hover" title="{{__('Copier une requête prête à être collée dans votre modèle d\'IA préféré afin d\'analyser ce code.')}}"><i class="fa-solid fa-wand-magic-sparkles" style="padding-top:5px;padding-bottom:3px;"></i></button>
                            </div>

                        </div>
                        <div id="console" class="col">
                            <div class="text-dark text-monospace" style="float:right;font-size:70%;padding:5px 12px 0px 0px">console</div>
                            <div id="output" class="text-monospace p-3 text-white bg-secondary small" style="white-space: pre-wrap;border-radius:4px;min-height:100px;height:100%;">Prêt!</div>
                        </div>
                    </div>
            
                    <!-- Analyse -->
                    <div class="mt-4">
                        <div class="text-monospace">ANALYSE</div>
                        <div class="text-monospace text-muted small mb-1">Pour une analyse plus poussée, utilisez le bouton <i class="fa-solid fa-wand-magic-sparkles"></i> ci-dessus.</div>
                        
                        <div class="border p-3" style="border-radius:6px;">
                            @php
                            if ($capture->analyse !== NULL) {
                                $analyse = $capture->analyse;
                            } else {
                                $analyse = "Pas d'analyse...";
                            }
                            @endphp
                            <pre style="margin-bottom:0;white-space:pre-wrap;">{{ trim($analyse) }}</pre>
                        </div>	
                        
                    </div>

                </div>
                <!-- /GAUCHE -->

            </div>
        </div>

        <div class="gutter-col gutter-col-1"></div>

        <div style="overflow-y:scroll;padding:0px;margin:0px;">
            <div style="height:100%;position:relative;padding:0px;margin:0px;">

                <!-- GAUCHE -->
                <div style="padding:0px 20px 20px 20px">

                    <!-- Code capture -->
                    <div class="pb-4">
                        <div class="text-monospace">CODE CAPTURÉ</div>
                        <img src="data:image/png;base64,{{ $image }}" class="mt-1" style="border-radius:6px;width:100%" />
                    </div>

                </div>
                <!-- /DROITE -->

            </div>
        </div>
    </div>

    @include('inc-bottom-js')

    {{-- == Split ======================================================== --}}
    <script src="{{ asset('js/split-grid.js') }}"></script>
    <script>
    Split({
        minSize: 0,
        columnGutters: [{
            track: 1,
            element: document.querySelector('.gutter-col-1'),
        }],
    })
    </script>
    {{-- == /Split ======================================================= --}}


    {{-- == Éditeur ACE ================================================== --}}
    <script src="{{ asset('js/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>
    <script>
        editor_code_eleve = ace.edit('code_editor_eleve', {
            theme: "ace/theme/puzzle_code",
            mode: "ace/mode/python",
            maxLines: 500,
            minLines: 6,
            fontSize: 14,
            wrap: true,
            useWorker: false,
            autoScrollEditorIntoView: true,
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
        editor_code_eleve.container.style.lineHeight = 1.5;
        editor_code_eleve.setValue({!! json_encode($code) ?? '' !!}, -1);
    </script>
    {{-- == /Éditeur ACE ================================================= --}}


    {{-- == Webworker ======================================================== --}}
    <script>

        var pyodideWorker = new Worker("{{ asset('pyodideworker/copie-pyodideWorker.js') }}");

        // Initialisation des éditeurs
        function initializeEditors() {
            let code_editors = document.querySelectorAll('.code-editor');
            document.getElementById("output").innerText = "Initialisation...\n";
            document.getElementById("run").disabled = true;
            document.getElementById("restart").style.display = 'none';
        }

        // Mise à jour des éditeurs en fonction du statut (init, running, completed)
        function updateEditors(status) {
            let code_editors = document.querySelectorAll('.code-editor');
            if (status === 'init') {
                document.getElementById("output").innerText = "Prêt!\n";
                document.getElementById("run").style.display = 'inline-block';
                document.getElementById("run").innerHTML = '<i class="fas fa-play"></i>';
                document.getElementById("run").disabled = false;
                document.getElementById("restart").style.display = 'none';
            }
            if (status === 'completed') {
                document.getElementById("run").style.display = 'inline-block';
                document.getElementById("run").innerHTML = '<i class="fas fa-play"></i>';
                document.getElementById("run").disabled = false;
                document.getElementById("restart").style.display = 'none';
            } 
            if (status === 'running') {
                document.getElementById("run").style.display = 'none';
                document.getElementById("restart").style.display = 'inline-block';
            }           
        }

        // Gestion des messages du Web Worker
        function setupWorkerListener(worker) {
            worker.onmessage = function(event) {

                console.log("WEBWORKER EVENT: ", event.data);

                if (typeof event.data.init !== 'undefined') {
                    updateEditors('init');
                }

                if (typeof event.data.status !== 'undefined') {
                    if (event.data.status === 'running') {
                        updateEditors('running');
                    }

                    if (event.data.status === 'completed') {
                        updateEditors('completed');
                    }
                }

                if (typeof event.data.output !== 'undefined') {
                    document.getElementById("output").innerHTML += event.data.output;
                }
            };
        }

        // Attacher les événements au Web Worker initial
        setupWorkerListener(pyodideWorker);

        // Envoi des données au Web Worker pour exécution
        function run() {
            console.log('RUN');
            var code = "";
            code = editor_code_eleve.getValue();
            document.getElementById("output").innerHTML = "";
            pyodideWorker.postMessage({ code: code, id: '1' });
        }

        // Fonction pour redémarrer le Web Worker
        function restart() {
            if (pyodideWorker) {
                pyodideWorker.terminate();
                console.log("Web Worker supprimé.");
            }

            // Recréer un nouveau Web Worker
            pyodideWorker = new Worker("{{ asset('pyodideworker/copie-pyodideWorker.js') }}");
            console.log("Web Worker redémarré.");

            // Réattacher l'écouteur onmessage au nouveau worker
            setupWorkerListener(pyodideWorker);

            // Réinitialiser les boutons d'exécution et d'arrêt
            document.getElementById("run").style.display = 'inline-block';
            document.getElementById("run").innerHTML = '<i class="fas fa-circle-notch fa-spin"></i>';
            document.getElementById("run").disabled = true;
            document.getElementById("restart").style.display = 'none';
        }

        // Initialisation au chargement
        initializeEditors();

    </script>
    {{-- == /Webworker ======================================================= --}}


    <script>
        function copyAndNotify(button, text) {
            const onSuccess = () => {
            animateOpacity(button);
            showCopiedMessage(button);
            };

            if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(onSuccess, () => {
                fallbackCopy(text, button);
                showCopiedMessage(button);
            });
            } else {
            fallbackCopy(text, button);
            showCopiedMessage(button);
            }
        }

        function fallbackCopy(text, button) {
            const ta = document.createElement('textarea');
            ta.value = text;
            ta.style.position = 'fixed';
            ta.style.top = '-9999px';
            document.body.appendChild(ta);
            ta.select();
            try { document.execCommand('copy'); }
            catch (err) { console.error('Copy failed', err); }
            document.body.removeChild(ta);
        }

        function animateOpacity(button) {
            button.style.transition = 'none';
            button.style.opacity    = '0.1';
            void button.offsetWidth;
            button.style.transition = 'opacity 2s ease-in-out';
            button.style.opacity    = '1';
        }

        function showCopiedMessage(button) {
            // 1) Calculer la position du bouton dans la page
            const rect = button.getBoundingClientRect();
            const msg  = document.createElement('span');
            msg.innerText = 'copié !';

            // 2) Styles inline pour que rien d'autre ne bouge
            Object.assign(msg.style, {
            position:      'absolute',
            left:          (rect.left + rect.width/2 + window.pageXOffset) + 'px',
            top:           (rect.bottom + window.pageYOffset + 4)  + 'px',
            transform:     'translateX(-50%)',
            whiteSpace:    'nowrap',
            pointerEvents: 'none',
            opacity:       '1',
            zIndex:        '9999',
            fontSize:      '0.9em',
            transition:    'transform 2s ease-out, opacity 2s ease-out'
            });

            document.body.appendChild(msg);

            // 3) Déclencher l’animation de descente + disparition
            void msg.offsetWidth;
            msg.style.transform = 'translateX(-50%) translateY(20px)';
            msg.style.opacity   = '0';

            // 4) Nettoyage après animation
            msg.addEventListener('transitionend', () => msg.remove());
        }
    </script>

</body>
</html>