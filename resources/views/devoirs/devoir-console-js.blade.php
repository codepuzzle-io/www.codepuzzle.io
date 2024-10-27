<script>
    MathJax = {
        tex: {
            inlineMath: [['$', '$'], ['\\(', '\\)']],
            displayMath: [ ['$$','$$'], ["\\[","\\]"] ],
            processEscapes: true
        },
        options: {
            ignoreHtmlClass: "no-mathjax",
            processHtmlClass: "mathjax"
        }
    };        
</script>  
<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

<script src="{{ asset('js/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>

<script>
    // Chargement de ace et initialisation des éditeurs.
    async function init_editors() {
        var editor_code_eleve = ace.edit("editor_code_eleve", {
            theme: "ace/theme/puzzle_code",
            mode: "ace/mode/python",
            maxLines: 500,
            minLines: 1,
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

        var editor_code_enseignant = ace.edit("editor_code_enseignant", {
            theme: "ace/theme/puzzle_code",
            mode: "ace/mode/python",
            maxLines: 500,
            minLines: 1,
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

        var editor_solution = ace.edit("editor_solution", {
            theme: "ace/theme/puzzle_fakecode",
            mode: "ace/mode/python",
            maxLines: 500,
            minLines: 1,
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

    var editor_code_eleve_devoir = []
    var editor_code_enseignant_devoir = []
    for (var i = 1; i <= {{$copies->count() }}; i++) {

        editor_code_eleve_devoir[i] = ace.edit('editor_code_eleve_devoir-' + i, {
            theme: "ace/theme/puzzle_code",
            mode: "ace/mode/python",
            maxLines: 500,
            fontSize: 14,
            wrap: true,
            useWorker: false,
            highlightActiveLine: false,
            highlightGutterLine: false,
            showPrintMargin: false,
            displayIndentGuides: true,
            showLineNumbers: true,
            showGutter: true,
            showFoldWidgets: false,
            useSoftTabs: true,
            navigateWithinSoftTabs: false,
            tabSize: 4,
            readOnly: true
        });
        editor_code_eleve_devoir[i].container.style.lineHeight = 1.5;

        editor_code_enseignant_devoir[i] = ace.edit('editor_code_enseignant_devoir-' + i, {
            theme: "ace/theme/puzzle_code",
            mode: "ace/mode/python",
            maxLines: 500,
            fontSize: 14,
            wrap: true,
            useWorker: false,
            highlightActiveLine: false,
            highlightGutterLine: false,
            showPrintMargin: false,
            displayIndentGuides: true,
            showLineNumbers: true,
            showGutter: true,
            showFoldWidgets: false,
            useSoftTabs: true,
            navigateWithinSoftTabs: false,
            tabSize: 4
        });
        editor_code_enseignant_devoir[i].container.style.lineHeight = 1.5;
    }        

</script>

<script>

    // PYODIDE

    // webworker
    let pyodideWorker = createWorker();

    var runButton, stopButton, restartButton, output, interruptBuffer;

    function createWorker() {          

        let pyodideWorker = new Worker("{{ asset('pyodideworker/devoir-pyodideWorker.js') }}");

        pyodideWorker.onmessage = function(event) {
            
            // reponses du WebWorker
            console.log("EVENT: ", event.data);

            if (typeof event.data.init !== 'undefined') {
                console.log("Prêt!");
                var runButtons = document.querySelectorAll('button[data-pyodide="run"]');
                runButtons.forEach(function(runButton) {
                    runButton.innerHTML = '<i class="fas fa-play"></i>';
                    runButton.disabled = false;
                });
            }

            if (typeof event.data.status !== 'undefined') {

                if (event.data.status == 'running'){
                    runButton.disabled = true;
                    runButton.innerHTML = '<i class="fas fa-cog fa-spin"></i>';
                    //stopButton.style.display = 'inline';
                }

                if (event.data.status == 'completed'){
                    runButton.disabled = false;
                    runButton.innerHTML = '<i class="fas fa-play"></i>';
                    //stopButton.style.display = 'none';
                    restartButton.style.display = 'none';
                }
                
            }

            if (typeof event.data.output !== 'undefined') {
                output.innerHTML += event.data.output;
            }	

        };

        /*
        @if(App::isProduction())
            // ne fonctionne pas en local a cause de COEP et COOP
            // interruption python
            interruptBuffer = new Uint8Array(new SharedArrayBuffer(1));
            pyodideWorker.postMessage({ cmd: "setInterruptBuffer", interruptBuffer });
        @endif
        */
        
        return pyodideWorker

    }

    // envoi des donnees au webworker pour execution
    function run(id){

        runButton = document.getElementById("run-"+id);
        //stopButton = document.getElementById("stop-"+id);
        restartButton = document.getElementById("restart-"+id);
        output = document.getElementById("output-"+id);

        /*
        @if(App::isProduction())
            // ne fonctionne pas en local a cause de COEP et COOP
            interruptBuffer[0] = 0;
        @endif
        */

        let code = "";
        if (document.getElementById("code_option_1_devoir-" + id).checked) {
            code = editor_code_eleve_devoir[id].getValue();
        } else if (document.getElementById("code_option_2_devoir-" + id).checked) {
            code = editor_code_eleve_devoir[id].getValue() + "\n" + editor_code_enseignant_devoir[id].getValue();
        } else if (document.getElementById("code_option_3_devoir-" + id).checked) {
            code = editor_code_enseignant_devoir[id].getValue();
        }

        output.innerHTML = "";
        pyodideWorker.postMessage({ code: code });	

    } 

    // interruption de python
    function stop(id) {
        @if(App::isProduction())
            // ne fonctionne pas en local a cause de COEP et COOP
            // 2 stands for SIGINT.
            interruptBuffer[0] = 2;
        @endif
        // bouton 'restart'
        restartButton.style.display = 'inline';
    }
            

    // arrete et redemarre le webworker   
    function restart() {
        if (pyodideWorker) {
            pyodideWorker.terminate();
            console.log("Web Worker supprimé.");
        }
        pyodideWorker = createWorker();
        console.log("Web Worker redémarré.");
        runButton.disabled = true;
        //stopButton.style.display = 'none';
        restartButton.style.display = 'none';
    }

</script>


<script>
    function save_commentaires(i, id, event) {
        event.preventDefault();
        var formData = new URLSearchParams();
        formData.append('copie_id', id);
        formData.append('code_enseignant', encodeURIComponent(editor_code_enseignant_devoir[i].getValue()));
        formData.append('commentaires', encodeURIComponent(document.getElementById('commentaires-'+i).value));
        fetch('/devoir-save-commentaires', {
            method: 'POST',
            headers: {"Content-Type": "application/x-www-form-urlencoded", "X-CSRF-Token": "{{ csrf_token() }}"},
            body: formData
        })
        .then(function(response) {
            // Renvoie la réponse du serveur (peut contenir un message de confirmation)
            //return response.text();

            // confirmation de l'enregistrement
            document.getElementById('save-' + i).blur();
            var element = document.getElementById("save-confirmation-" + i)
            element.style.opacity = 1;
            var intervalID = setInterval(function() {
                element.style.opacity = element.style.opacity - 0.01;
            }, 30); 
            setTimeout(function() {
                clearInterval(intervalID);
            }, 3000);
            return response.text();
            
        })
        .then(function(data) {
            // Affiche la réponse du serveur dans la console
            console.log('Réponse du serveur:', data); 
        })
        .catch(function(error) {
            // Gère les erreurs liées à la requête Fetch
            console.error('Erreur:', error); 
        });
    }
</script>

<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
