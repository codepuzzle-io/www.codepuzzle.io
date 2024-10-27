@if ($sujet->type == 'exo')

    {{-- ============== --}}
    {{-- ==== EXO ===== --}}
    {{-- ============== --}}

    @include('markdown/inc-markdown-afficher-js')

    @if (isset($page_sujet_console) or isset($page_devoir_console) or isset($page_devoir_creer) or isset($page_sujet))
        <script>
            var editor_code_eleve = [];
            var editor_code_enseignant = [];
            var editor_code_solution = [];
        </script>
        @foreach($sujet_json->code AS $code)
            <script src="{{ asset('js/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>
            <script>
                editor_code_eleve[{{ $loop->iteration }}] = ace.edit('code_editor_eleve_' + {{ $loop->iteration }}, {
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
                editor_code_eleve[{{ $loop->iteration }}].container.style.lineHeight = 1.5;

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
                    readOnly: true,
                    tabSize: 4
                });
                editor_code_enseignant[{{ $loop->iteration }}].container.style.lineHeight = 1.5;

                editor_code_solution[{{ $loop->iteration }}] = ace.edit('code_editor_solution_' + {{ $loop->iteration }}, {
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
                editor_code_solution[{{ $loop->iteration }}].container.style.lineHeight = 1.5;		
            </script>
        @endforeach
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