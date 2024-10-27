{{-- == Webworker ======================================================== --}}
<script>

    var pyodideWorker = new Worker("{{ asset('pyodideworker/copie-pyodideWorker.js') }}");

    // Initialisation des éditeurs
    function initializeEditors() {
        let code_editors = document.querySelectorAll('.code-editor');
        for (let i = 0; i < code_editors.length; i++) {
            let editor_id = code_editors[i].id.substring(code_editors[i].id.lastIndexOf('_') + 1);
            document.getElementById("output_" + editor_id).innerText = "Initialisation...\n";
            document.getElementById("run_" + editor_id).disabled = true;
            document.getElementById("restart_" + editor_id).style.display = 'none';
        }
    }

    // Mise à jour des éditeurs en fonction du statut (init, running, completed)
    function updateEditors(status) {
        let code_editors = document.querySelectorAll('.code-editor');
        for (let i = 0; i < code_editors.length; i++) {
            let editor_id = code_editors[i].id.substring(code_editors[i].id.lastIndexOf('_') + 1);
            if (status === 'init') {
                console.log
                document.getElementById("output_" + editor_id).innerText = "Prêt!\n";
                document.getElementById("run_" + editor_id).innerHTML = '<i class="fas fa-play"></i>';
                document.getElementById("run_" + editor_id).disabled = false;
                document.getElementById("restart_" + editor_id).style.display = 'none';
            }
            if (status === 'completed') {
                document.getElementById("run_" + editor_id).innerHTML = '<i class="fas fa-play"></i>';
                document.getElementById("run_" + editor_id).disabled = false;
                document.getElementById("restart_" + editor_id).style.display = 'none';
            }            
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
                    document.getElementById("run_" + event.data.id).innerHTML = '<i class="fas fa-cog fa-spin"></i>';
                    document.getElementById("run_" + event.data.id).disabled = true;
                    document.getElementById("restart_" + event.data.id).style.display = 'block';
                }

                if (event.data.status === 'completed') {
                    updateEditors('completed');
                }
            }

            if (typeof event.data.output !== 'undefined') {
                document.getElementById("output_" + event.data.id).innerHTML += event.data.output;
            }
        };
    }

    // Attacher les événements au Web Worker initial
    setupWorkerListener(pyodideWorker);

    // Envoi des données au Web Worker pour exécution
    function run(id) {
        console.log('RUN');

        var code = "";

        console.log(id);

        if (document.getElementById("code_option_1_devoir-" + id).checked) {
            code = editor_code_eleve[id].getValue();
        } else if (document.getElementById("code_option_2_devoir-" + id).checked) {
            code = editor_code_eleve[id].getValue() + "\n" + editor_code_enseignant[id].getValue();
        } else if (document.getElementById("code_option_3_devoir-" + id).checked) {
            code = editor_code_enseignant[id].getValue();
        }

        document.getElementById("output_" + id).innerHTML = "";
        pyodideWorker.postMessage({ code: code, id: id });
    }

    // Fonction pour redémarrer le Web Worker
    function restart(id) {
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
        document.getElementById("run_" + id).innerHTML = '<i class="fas fa-circle-notch fa-spin"></i>';
        document.getElementById("run_" + id).disabled = true;
        document.getElementById("restart_" + id).style.display = 'none';
    }

    // Initialisation au chargement
    initializeEditors();

</script>
{{-- == /Webworker ======================================================= --}}


@if ($sujet->type == 'pdf')

    {{-- ============= --}}
    {{-- ==== PDF ==== --}}
    {{-- ============= --}}

    {{-- == Markdown + MathJax + coloration ============================== --}}
    <script src="https://cdn.jsdelivr.net/npm/dompurify/dist/purify.min.js"></script>
    <script>
        MathJax = {
            tex: {
                inlineMath: [["$","$"]], 
                displayMath: [["$$","$$"]], 
            },
            svg: {
                fontCache: 'global'
            }
        };
    </script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/python.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script>
        // Markdown Options - pour limiter les options voir https://github.com/Ionaru/easy-markdown-editor/issues/245
        marked.use({
            tokenizer: {
                //space() { return undefined },
                //code() { return undefined },
                //fences() { return undefined },
                heading() { return undefined },
                //hr() { return undefined },
                blockquote() { return undefined },
                //list() { return undefined },
                html() { return undefined },
                //def() { return undefined },
                table() { return undefined },
                lheading() { return undefined },
                //paragraph() { return undefined },
                //text() { return undefined },
                //escape() { return undefined },
                tag() { return undefined },
                link() { return undefined },
                reflink() { return undefined },
                //emStrong() { return undefined },
                //codespan() { return undefined },
                //br() { return undefined },
                del() { return undefined },
                autolink() { return undefined },
                url() { return undefined },
                //inlineText() { return undefined },              
            },       
        })     

        // Rendu des cellules
        function processPage() {
            var divsExclus = document.querySelectorAll('.exclure');
            
            function estDivExclu(element) {
                for (var i = 0; i < divsExclus.length; i++) {
                    if (divsExclus[i].contains(element)) {
                        return true;
                    }
                }
                return false;
            }

            // Logique liée au clic
            if (!estDivExclu(event ? event.target : null)) {
                for (var i = 0; i < divsExclus.length; i++) {
                    var id = divsExclus[i].id.split("_")[1];
                    md_render(id);
                }
            }

            // Coloration syntaxique
            document.querySelectorAll('pre code').forEach(el => {
                el.classList.add('language-python');
                hljs.highlightElement(el);
            });

            // LaTeX
            document.querySelectorAll('div.cellule_marked').forEach(el => {
                MathJax.typesetPromise([el]).then(() => {
                    console.log('Mathématiques rendues.');
                }).catch((err) => console.log('Erreur de rendu MathJax : ', err));
            });
        }

        // Déclencher au clic
        document.addEventListener('click', processPage);

        // Déclencher au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            processPage(); // Appel de la fonction lors du chargement de la page
        });

    </script>
    {{-- == /Markdown + MathJax + coloration ============================= --}}

    {{-- ============== --}}
    {{-- ==== /PDF ==== --}}
    {{-- ============== --}}

@endif


{{-- == Éditeur ACE ================================================== --}}
<script src="{{ asset('js/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>
<script>
    var editor_code_eleve = [];
    var editor_code_enseignant = [];
    
    @foreach($copie_cells AS $copie_cell)
        @if ($copie_cell->cell_type == 'code')
            editor_code_eleve[{{ $loop->iteration }}] = ace.edit('code_editor_eleve_' + {{ $loop->iteration }}, {
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
                readOnly: true,
                tabSize: 4
            });
            editor_code_eleve[{{ $loop->iteration }}].container.style.lineHeight = 1.5;
            editor_code_eleve[{{ $loop->iteration }}].setValue({!! json_encode($copie_cell->source[0]) ?? '' !!}, -1);

            editor_code_enseignant[{{ $loop->iteration }}] = ace.edit('code_editor_enseignant_' + {{ $loop->iteration }}, {
                theme: "ace/theme/puzzle_code",
                mode: "ace/mode/python",
                maxLines: 500,
                minLines: 4,
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
            editor_code_enseignant[{{ $loop->iteration }}].container.style.lineHeight = 1.5;
            @if ($copie->correction_enseignant != null)
                editor_code_enseignant[{{ $loop->iteration }}].setValue({!! json_encode($copie_cell->source[1]) ?? '' !!}, -1);
            @elseif ($sujet->type == "exo")
                editor_code_enseignant[{{ $loop->iteration }}].setValue({!! json_encode($sujet_json->code->{$loop->iteration}->code_enseignant) ?? '' !!}, -1);
            @endif
            
        @endif
    @endforeach
</script>
{{-- == /Éditeur ACE ================================================= --}}