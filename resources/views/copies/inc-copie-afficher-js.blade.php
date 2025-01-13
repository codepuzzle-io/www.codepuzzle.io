{{-- == Webworker ======================================================== --}}
<script>

    var pyodideWorker = new Worker("{{ asset('pyodideworker/copie-pyodideWorker.js') }}");

    // Initialisation des éditeurs
    function initializeEditors() {
        let code_editors = document.querySelectorAll('.code-editor');
        for (let i = 0; i < code_editors.length; i++) {
            let editor_id = code_editors[i].id.split("_")[2];
            document.getElementById("output_" + editor_id).innerText = "Initialisation...\n";
            document.getElementById("run_" + editor_id).disabled = true;
            document.getElementById("restart_" + editor_id).style.display = 'none';
        }
    }

    // Mise à jour des éditeurs en fonction du statut (init, running, completed)
    function updateEditors(status) {
        let code_editors = document.querySelectorAll('.code-editor');
        for (let i = 0; i < code_editors.length; i++) {
            let editor_id = code_editors[i].id.split("_")[2];
            if (status === 'init') {
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
        const code = editor_code[id].getValue();
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


@if ($sujet->type == 'exo')

    {{-- ============== --}}
    {{-- ==== EXO ===== --}}
    {{-- ============== --}}


    {{-- == Éditeur ACE ================================================== --}}
    <script src="{{ asset('js/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>
    <script>
        var editor_code = [];
        @foreach($sujet_json->code AS $code)
            editor_code[{{ $loop->iteration }}] = ace.edit('code_editor_' + {{ $loop->iteration }}, {
                theme: "ace/theme/puzzle_code",
                mode: "ace/mode/python",
                maxLines: 500,
                minLines: 10,
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
            editor_code[{{ $loop->iteration }}].container.style.lineHeight = 1.5;
        @endforeach
    </script>
    
    {{-- == /Éditeur ACE ================================================= --}}


    {{-- == Chargement de la copie avec la copie sauvegardée =========== --}}
    <script>
        @if (isset($page_sujet_copie))
            let saved_copie = JSON.parse(localStorage.getItem('copie-{{$sujet->jeton}}'));
        @endif
        @if (isset($page_devoir))
            let saved_copie = {!! $copie->copie !!};
        @endif
        if (saved_copie !== null) {
            console.log('copie-{{$sujet->jeton}}');
            console.log(saved_copie);
            // Parcourir chaque cellule du JSON
            saved_copie.cells.forEach((cell, index) => {
                if (cell.cell_type === "code") {
                    console.log('code: '+cell.source.join(''));
                    editor_code[index+1].setValue(cell.source.join(''), -1);
                }
            });
        }
    </script>
    {{-- == /Chargement de la copie avec la copie sauvegardée ========== --}}


    {{-- == Interdiction du copier-coller extérieur ====================== --}}
    <script>		
        // INTERDICTION DU COPIER-COLLER DE CODE EXTERIEUR
        editor_code[1].on("paste", function(texteColle) {
            console.log("Text collé: " + texteColle.text);
            if (!editor_code[1].getValue().includes(texteColle.text)) {
                texteColle.text = "";
                console.log("Le collage de ce texte N'est PAS autorisé.");
            } else {
                console.log("Le collage de ce texte est autorisé.");
            }
        });
    </script>
    {{-- == /Interdiction du copier-coller extérieur ===================== --}} 


    {{-- ============== --}}
    {{-- ==== /EXO ==== --}}
    {{-- ============== --}}

@endif


@if ($sujet->type == 'pdf' OR $sujet->type == 'md')

    {{-- =============== --}}
    {{-- == PDF - MD === --}}
    {{-- =============== --}}


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


    {{-- == Gestion des cellules ========================================= --}}
    <script src="{{ asset('js/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>
    <script>
        var editor_code = [];
        let div_id = 0;

        function md_render(id) {
            var textarea = document.getElementById('textarea_'+id);
            var markedarea = document.getElementById('markedarea_'+id)
            var markedarea_content = document.getElementById('markedarea_content_'+id)
            textarea.style.display = 'none';
            markedarea.style.display = 'block';
            // Remplacement des doubles slashes par des triples dans les blocs LaTex
            var texte = textarea.value.replace(/\$\$(.+?)\$\$/gs, function(match) {
                return match.replace(/\\\\/g, '\\\\\\\\');
            });
            markedarea_content.innerHTML = DOMPurify.sanitize(marked.parse(texte));
        }

        function edit(id) {
            var divsExclus = document.querySelectorAll('.exclure');
            for (var i = 0; i < divsExclus.length; i++) {
                ids = divsExclus[i].id.split("_")[1];
                md_render(ids)
            }
            document.getElementById('markedarea_'+id).style.display = 'none';
            document.getElementById('textarea_'+id).style.display = 'block';
            document.getElementById('textarea_'+id).focus();
        }

        function textarea_autosize(textarea) {
            textarea.style.height = 'auto';
            textarea.style.height = 2 + textarea.scrollHeight + 'px';
        }

        function ajouterDiv(referenceDivId = null, position = 'bas', type, content = '') {
            div_id++;
            const div = document.createElement('div');
            div.className = 'cellule';
            div.id = 'div_'+div_id;

            if (type == 'text') {
                var div_content = `<textarea id="textarea_`+div_id+`" class="form-control cellule_content exclure" oninput="textarea_autosize(this)" row="2">`+content+`</textarea>
                <div id="markedarea_`+div_id+`" class="cellule_content exclure cellule_marked hover-edit" style="position:relative;display:none;min-height:40px;">
                    <div class="markedarea_icon" onclick="edit('`+div_id+`')"><i class="fas fa-pen-square fa-lg"></i></div>
                    <div id="markedarea_content_`+div_id+`"></div>
                </div>`;
            }

            if (type == 'code') {
                var div_content = `<div class="cellule_content"><div id="code_editor_`+div_id+`" class="mb-2 code-editor"></div>
                <div class="row no-gutters">
                    <div class="col-auto mr-2">
                        <div>
                            <button id="run_`+div_id+`" onclick="run('`+div_id+`')" style="width:40px;" type="button" class="btn btn-primary text-center mb-1 btn-sm"><i class="fas fa-play"></i></button>
                        </div>
                        <div id="restart_`+div_id+`" style="display:none;">
                            <button style="width:40px;" type="button" onclick="restart()"  class="btn btn-dark btn-sm" style="padding-top:6px;" data-toggle="tooltip" data-placement="right"  data-trigger="hover" title="{{__('Si le bouton d\'arrêt ne permet pas d\'interrompre  l\'exécution du code, cliquer ici. Python redémarrera complètement mais votre code sera conservé dans l\'éditeur. Le redémarrage peut prendre quelques secondes.')}}"><i class="fas fa-stop"></i></button>
                        </div>
                    </div>
                    <div id="console_`+div_id+`" class="col">
                        <div class="text-dark small text-monospace" style="float:right;padding:5px 12px 0px 0px">console</div>
                        <div id="output_`+div_id+`" class="text-monospace p-3 text-white bg-secondary small" style="white-space: pre-wrap;border-radius:4px;min-height:100px;height:100%;">Prêt!</div>
                    </div>
                </div></div>`;
            }

            div_content += `<div class="mt-1 mb-3 text-right">

                <div class="control_bouton" onclick="deplacer('haut', '${div.id}')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M1.52899 6.47101C1.23684 6.76316 1.23684 7.23684 1.52899 7.52899V7.52899C1.82095 7.82095 2.29426 7.82116 2.58649 7.52946L6.25 3.8725V12.25C6.25 12.6642 6.58579 13 7 13V13C7.41421 13 7.75 12.6642 7.75 12.25V3.8725L11.4027 7.53178C11.6966 7.82619 12.1736 7.82641 12.4677 7.53226V7.53226C12.7617 7.2383 12.7617 6.7617 12.4677 6.46774L7.70711 1.70711C7.31658 1.31658 6.68342 1.31658 6.29289 1.70711L1.52899 6.47101Z" fill="#000000"></path></svg></div>

                <div class="control_bouton" onclick="deplacer('bas', '${div.id}')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M12.471 7.52899C12.7632 7.23684 12.7632 6.76316 12.471 6.47101V6.47101C12.179 6.17905 11.7057 6.17884 11.4135 6.47054L7.75 10.1275V1.75C7.75 1.33579 7.41421 1 7 1V1C6.58579 1 6.25 1.33579 6.25 1.75V10.1275L2.59726 6.46822C2.30338 6.17381 1.82641 6.17359 1.53226 6.46774V6.46774C1.2383 6.7617 1.2383 7.2383 1.53226 7.53226L6.29289 12.2929C6.68342 12.6834 7.31658 12.6834 7.70711 12.2929L12.471 7.52899Z" fill="#000000"></path></svg></div>

                <div class="control_bouton" onclick="ajouterDiv('${div.id}', 'haut', 'text')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><g xmlns="http://www.w3.org/2000/svg"><path d="M4.75 4.93066H6.625V6.80566C6.625 7.01191 6.79375 7.18066 7 7.18066C7.20625 7.18066 7.375 7.01191 7.375 6.80566V4.93066H9.25C9.45625 4.93066 9.625 4.76191 9.625 4.55566C9.625 4.34941 9.45625 4.18066 9.25 4.18066H7.375V2.30566C7.375 2.09941 7.20625 1.93066 7 1.93066C6.79375 1.93066 6.625 2.09941 6.625 2.30566V4.18066H4.75C4.54375 4.18066 4.375 4.34941 4.375 4.55566C4.375 4.76191 4.54375 4.93066 4.75 4.93066Z" fill="#000000" stroke="#000000" stroke-width="0.7"></path></g><path fill-rule="evenodd" clip-rule="evenodd" d="M11.5 9.5V11.5L2.5 11.5V9.5L11.5 9.5ZM12 8C12.5523 8 13 8.44772 13 9V12C13 12.5523 12.5523 13 12 13L2 13C1.44772 13 1 12.5523 1 12V9C1 8.44772 1.44771 8 2 8L12 8Z" fill="#000000"></path></svg></div>

                <div class="control_bouton" onclick="ajouterDiv('${div.id}', 'bas', 'text')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><g xmlns="http://www.w3.org/2000/svg"><path d="M9.25 10.0693L7.375 10.0693L7.375 8.19434C7.375 7.98809 7.20625 7.81934 7 7.81934C6.79375 7.81934 6.625 7.98809 6.625 8.19434L6.625 10.0693L4.75 10.0693C4.54375 10.0693 4.375 10.2381 4.375 10.4443C4.375 10.6506 4.54375 10.8193 4.75 10.8193L6.625 10.8193L6.625 12.6943C6.625 12.9006 6.79375 13.0693 7 13.0693C7.20625 13.0693 7.375 12.9006 7.375 12.6943L7.375 10.8193L9.25 10.8193C9.45625 10.8193 9.625 10.6506 9.625 10.4443C9.625 10.2381 9.45625 10.0693 9.25 10.0693Z" fill="#000000" stroke="#000000" stroke-width="0.7"></path></g><path fill-rule="evenodd" clip-rule="evenodd" d="M2.5 5.5L2.5 3.5L11.5 3.5L11.5 5.5L2.5 5.5ZM2 7C1.44772 7 1 6.55228 1 6L1 3C1 2.44772 1.44772 2 2 2L12 2C12.5523 2 13 2.44772 13 3L13 6C13 6.55229 12.5523 7 12 7L2 7Z" fill="#000000"></path></svg></div>

                <div class="control_bouton" onclick="ajouterDiv('${div.id}', 'haut', 'code')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><g xmlns="http://www.w3.org/2000/svg"><path d="M4.75 4.93066H6.625V6.80566C6.625 7.01191 6.79375 7.18066 7 7.18066C7.20625 7.18066 7.375 7.01191 7.375 6.80566V4.93066H9.25C9.45625 4.93066 9.625 4.76191 9.625 4.55566C9.625 4.34941 9.45625 4.18066 9.25 4.18066H7.375V2.30566C7.375 2.09941 7.20625 1.93066 7 1.93066C6.79375 1.93066 6.625 2.09941 6.625 2.30566V4.18066H4.75C4.54375 4.18066 4.375 4.34941 4.375 4.55566C4.375 4.76191 4.54375 4.93066 4.75 4.93066Z" fill="#000000" stroke="#000000" stroke-width="0.7"></path></g><path xmlns="http://www.w3.org/2000/svg" d="M11.5 9.5V11.5L2.5 11.5V9.5L11.5 9.5ZM12 8C12.5523 8 13 8.44772 13 9V12C13 12.5523 12.5523 13 12 13L2 13C1.44772 13 1 12.5523 1 12V9C1 8.44772 1.44771 8 2 8L12 8Z" fill="#000000"></path></svg></div>

                <div class="control_bouton" onclick="ajouterDiv('${div.id}', 'bas', 'code')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><g xmlns="http://www.w3.org/2000/svg"><path d="M9.25 10.0693L7.375 10.0693L7.375 8.19434C7.375 7.98809 7.20625 7.81934 7 7.81934C6.79375 7.81934 6.625 7.98809 6.625 8.19434L6.625 10.0693L4.75 10.0693C4.54375 10.0693 4.375 10.2381 4.375 10.4443C4.375 10.6506 4.54375 10.8193 4.75 10.8193L6.625 10.8193L6.625 12.6943C6.625 12.9006 6.79375 13.0693 7 13.0693C7.20625 13.0693 7.375 12.9006 7.375 12.6943L7.375 10.8193L9.25 10.8193C9.45625 10.8193 9.625 10.6506 9.625 10.4443C9.625 10.2381 9.45625 10.0693 9.25 10.0693Z" fill="#000000" stroke="#000000" stroke-width="0.7"></path></g><path d="M2.5 5.5L2.5 3.5L11.5 3.5L11.5 5.5L2.5 5.5ZM2 7C1.44772 7 1 6.55228 1 6L1 3C1 2.44772 1.44772 2 2 2L12 2C12.5523 2 13 2.44772 13 3L13 6C13 6.55229 12.5523 7 12 7L2 7Z" fill="#000000"></path></svg></div>
                <span class="pl-4">
                    <span id="supprimer_button_${div.id}">
                        <div style="display:inline-block;width:20px;">&nbsp;</div>
                        <div onclick="showConfirm('supprimer_button_${div.id}', 'supprimer_confirm_${div.id}')" class="control_bouton" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16px" height="16px"><path d="M0 0h24v24H0z" fill="none"></path><path fill="#000000" d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"></path></svg>
                        </div>
                    </span>
                    <span id="supprimer_confirm_${div.id}" style="display:none">
                        <div id="supprimer_${div.id}" class="control_bouton_delete" onclick="supprimerDiv('${div.id}')" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16px" height="16px"><path d="M0 0h24v24H0z" fill="none"></path><path fill="currentColor" d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"></path></svg>
                        </div>
                        <div id="supprimer_cancel_${div.id}" onclick="hideConfirm('supprimer_button_${div.id}', 'supprimer_confirm_${div.id}')" class="control_bouton_cancel" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="12px" height="12px"><path d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>
                        </div>
                    </span>
                </span>
            </div>`;
       
            div.innerHTML = div_content;
                
            const mainContainer = document.getElementById('mainContainer');
            const referenceDiv = referenceDivId ? document.getElementById(referenceDivId) : null;
            
            if (referenceDiv) {
                if (position === 'haut') {
                    mainContainer.insertBefore(div, referenceDiv);
                } else {
                    mainContainer.insertBefore(div, referenceDiv.nextSibling);
                }
            } else {
                mainContainer.appendChild(div);
            }
            
            if (type == 'code') {
                editor_code[div_id] = ace.edit('code_editor_' + div_id, {
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
                editor_code[div_id].container.style.lineHeight = 1.5;
                editor_code[div_id].setValue(content, -1);
            }

            // Faire défiler pour rendre le div créé visible
            document.getElementById('div_'+div_id).scrollIntoView({
                behavior: 'smooth',
                block: 'end'
            });
            
        }

        // Deplacer une cellule
        function deplacer(direction, div_id) {
            const div = document.getElementById(div_id);
            if (direction === 'haut' && div.previousElementSibling) {
                div.parentNode.insertBefore(div, div.previousElementSibling);
            } else if (direction === 'bas' && div.nextElementSibling) {
                div.parentNode.insertBefore(div.nextElementSibling, div);
            }
        }

        // Supprimer une cellule
        function supprimerDiv(div_id) {
            const div = document.getElementById(div_id);
            div.parentNode.removeChild(div);
        }
    </script>
    {{-- == /Gestion des cellules ======================================== --}}


    {{-- == Chargement de la copie avec la copie sauvegardée =========== --}}
    <script>
        @if (isset($page_sujet_copie))
            let saved_copie = JSON.parse(localStorage.getItem('copie-{{$sujet->jeton}}'));
        @endif
        @if (isset($page_devoir))
            let saved_copie = {!! $copie->copie !!};
        @endif
        console.log('saved copie: '+saved_copie);
        if (saved_copie !== null) {
            console.log('copie-{{$sujet->jeton}}');
            // Parcourir chaque cellule du JSON
            saved_copie.cells.forEach(cell => {
                if (cell.cell_type === "code") {
                    console.log('code: '+cell.source.join(''));
                    ajouterDiv(null, position = 'bas', 'code', cell.source.join(''))
                }
                if (cell.cell_type === "markdown") {
                    console.log('md: '+cell.source.join(''));  
                    ajouterDiv(null, position = 'bas', 'text', cell.source.join(''))
                }
            });
        } else {
            ajouterDiv(null, position = 'bas', 'text', '')
        }
    </script>
    {{-- == /Chargement de la copie avec la copie sauvegardée ========== --}}

 
    {{-- =============== --}}
    {{-- == /PDF - MD == --}}
    {{-- =============== --}}

@endif


{{-- == Mécanisme confirmation suppression cellule ======================= --}}
<script>
    function showConfirm(buttonId, confirmId) {
        // Cacher le bouton delete_button et afficher delete_confirm
        document.getElementById(buttonId).style.display = 'none';
        document.getElementById(confirmId).style.display = 'inline';
    }

    function hideConfirm(buttonId, confirmId) {
        // Cacher delete_confirm et réafficher delete_button
        document.getElementById(confirmId).style.display = 'none';
        document.getElementById(buttonId).style.display = 'inline';
    }
</script>
{{-- == /Mécanisme bouton confirmation =================================== --}}


{{-- == Récupération du contenu de la copie ============================== --}}
<script>
    // Get copie au format ipynb
    function get_copie_ipynb() {
        var container = document.getElementById("mainContainer");
        var children = container.children;
        let copie = {
            metadata: {
                kernelspec:{
                    name:"python3",
                    display_name:"Python 3",
                    language:"python"
                }
            },
            nbformat:4,
            nbformat_minor:2,
            cells: []
        };
        for (var i = 0; i < children.length; i++) {
            var id = children[i].id.substring(children[i].id.lastIndexOf('_') + 1);
            if (document.getElementById('textarea_'+id)) {
                copie.cells.push({
                    cell_type: "markdown",
                    metadata: {},
                    source: [document.getElementById('textarea_'+id).value]
                });
            }
            if (document.getElementById('code_editor_'+id)) {
                copie.cells.push({
                    cell_type: "code",
                    execution_count: null,
                    metadata: {},
                    outputs: [],
                    source: [editor_code[id].getValue()]
                });
                
            }
        }
        return JSON.stringify(copie, null, 2);
    } 

    // Get copie au format text
    function get_copie_text() {
        var container = document.getElementById("mainContainer");
        var children = container.children;
        let copie = '';
        for (var i = 0; i < children.length; i++) {
            var id = children[i].id.substring(children[i].id.lastIndexOf('_') + 1);
            if (document.getElementById('textarea_'+id)) {
                copie += document.getElementById('textarea_'+id).value + "\n\n";
            }
            if (document.getElementById('code_editor_'+id)) {
                copie += "--------\n" + editor_code[id].getValue() + "\n--------\n\n";
            }
        }
        return copie;
    }
</script>
{{-- == /Récupération du contenu de la copie ============================= --}}


@if (isset($page_sujet_copie))

    {{-- == Sauvegarde automatique dans localstorage ===================== --}}
    <script>
        // Sauvegarde automatique dans localstorage (toutes les 10s)
        setInterval(function() {
            localStorage.setItem('copie-{{$sujet->jeton}}', get_copie_ipynb());
            console.log('Copie sauvegardée dans localstorage.');
            console.log(get_copie_ipynb());
        }, 10000);

        // Vide localstorage
        function delete_localstorage() {
            localStorage.removeItem('copie-{{$sujet->jeton}}');
            location.reload();
        }
    </script>
    {{-- == /Sauvegarde automatique dans localstorage ==================== --}}

    {{-- == Téléchargement de la copie =================================== --}}
    <script>
        // Téléchargement de la copie au format ipynb
        function download_copie_ipynb(el) {
            el.href = URL.createObjectURL(new Blob([get_copie_ipynb()], {type: "application/json"}));
            el.download = "copie-sujet-{{$sujet->jeton}}.ipynb";
        }

        // Téléchargement de la copie au format ipynb
        function download_copie_text(el) {
            el.href = URL.createObjectURL(new Blob([get_copie_text()], {type: "text/plain"}));
            el.download = "copie-sujet-{{$sujet->jeton}}.txt";
        }
    </script>       

    {{-- == /Téléchargement de la copie ================================== --}}
@endif


@if (isset($page_devoir))

    <script>
        function copie_sauvegarder(mode) {
            // Créer l'objet formData
            var formData = new URLSearchParams();
            formData.append('copie', encodeURIComponent(get_copie_ipynb()));
            formData.append('chrono', count);
            formData.append('jeton_copie', '{{ Session::get('jeton_copie') }}');
            formData.append('mode', mode);

            // Effectuer la requête fetch
            return fetch('/copie-sauvegarder', {
                method: 'POST',
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded", 
                    "X-CSRF-Token": "{{ csrf_token() }}"
                },
                body: formData
            })
            .then(function(response) {
                // Vérifier la réponse
                if (!response.ok) {
                    throw new Error('Erreur HTTP, statut : ' + response.status);
                }
                return response.text();
            })
            .then(function(data) {
                // Retourner les données pour les utiliser après
                console.log(data);
                return data;
            })
            .catch(function(error) {
                // Gérer les erreurs
                console.error('Erreur:', error); 
                throw error; // Relancer l'erreur pour que la fonction appelante sache qu'il y a eu un problème
            });
        }
    </script>	

    <script>	
        copie_sauvegarder('auto');    
        setInterval(function() {
            copie_sauvegarder('auto');
        }, 10000);        
    </script>	

    <script>
        // rendre
        function rendre() {
            copie_sauvegarder('rendre')
                .then(function(data) {
                    // Exécuter la redirection uniquement après succès
                    window.location.replace("/devoir-fin?s" + data);
                })
                .catch(function(error) {
                    // Gérer l'erreur ou faire autre chose si nécessaire
                    console.log('Aucune redirection en raison d\'une erreur.');
                });
        }
    </script>

@endif
