@if ($sujet->type == 'exo')

    {{-- ============== --}}
    {{-- ==== EXO ===== --}}
    {{-- ============== --}}

    @include('markdown/inc-markdown-afficher-js')

    @if (isset($page_sujet_console) or isset($page_devoir_console))

        <script src="{{ asset('js/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>
        <script>
            // Chargement de ace et initialisation des éditeurs.
            var editor_code;
            
            async function init_editors() {
                editor_code_eleve = ace.edit("editor_code_eleve", {
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
                    readOnly: true,
                    tabSize: 4
                });
                editor_code_eleve.container.style.lineHeight = 1.5;

                editor_code_enseignant = ace.edit("editor_code_enseignant", {
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
                    readOnly: true,
                    tabSize: 4
                });
                editor_code_enseignant.container.style.lineHeight = 1.5;

                var editor_solution;
                editor_solution = ace.edit("editor_solution", {
                    theme: "ace/theme/puzzle_fakecode",
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
                    readOnly: true,
                    tabSize: 4
                });
                editor_solution.container.style.lineHeight = 1.5;
            }
            (async function() {
                // Chargement asynchrone de ace et initialisation des éditeurs
                const editors_initialized_promise = init_editors();
                // Pour être sur que ace est chargé et les éditeurs initialisés.
                await editors_initialized_promise;		
            })();	
        </script>  

        <?php
        /*
        IMPORTANT
        To add 20px on top and bottom :
        - modify .ace_gutter {padding-top: 20px;} and .ace_scroller {top: 20px;} see custom.css
        - modify ace.js line 18642 "(this.$extraHeight || 0)" to "(this.$extraHeight || 30)"
        OTHER MOFDIFICATION
        Add margin left and right > change line 18074 "this.setPadding(20);" instead "this.setPadding(4);"
        */
        ?>

    @endif

    {{-- ============== --}}
    {{-- ==== /EXO ==== --}}
    {{-- ============== --}}

@endif

@if ($sujet->type == 'pdf')

    {{-- ============== --}}
    {{-- ==== PDF ===== --}}
    {{-- ============== --}}

    <script>
        function hauteur_iframe() {
            var hauteur_grid_gauche = document.getElementById('gauche').offsetHeight;
            var hauteur_sujet_entete = document.getElementById('sujet_entete').offsetHeight; 
            var hauteur_sujet_titre = document.getElementById('sujet_titre').offsetHeight; 
            document.getElementById('sujet_pdf').style.height = (hauteur_grid_gauche - hauteur_sujet_entete - hauteur_sujet_titre - 15) + 'px';
        }
        window.onload = hauteur_iframe;
        window.onresize = hauteur_iframe;
    </script>

    {{-- ============== --}}
    {{-- ==== /PDF ==== --}}
    {{-- ============== --}}

@endif