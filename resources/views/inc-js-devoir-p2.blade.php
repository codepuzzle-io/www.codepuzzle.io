<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>

<script>
	$(function () {
	  $('[data-toggle="popover"]').popover()
	})

	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
	})

	$(function () {
		$('.popover-dismiss').popover({
		  trigger: 'focus'
		})
	})

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>

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
<script type="text/javascript" id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-svg.js"></script> 	

<script src="{{ asset('js/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>
<script>
    var editor_code = ace.edit("editor_code", {
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
    editor_code.container.style.lineHeight = 1.5;
</script> 

<script>
    var nbverif = {{ $devoir_eleve->nbverif }};
    var count = {{ $devoir_eleve->chrono }};

    // autosave
    function autosave(){
        var formData = new URLSearchParams();
        formData.append('code', encodeURIComponent(editor_code.getSession().getValue()));
        @if ($devoir->with_chrono == 1)
            formData.append('chrono', count);
        @else
            formData.append('chrono', 0);
        @endif
        @if ($devoir->with_nbverif == 1)
            formData.append('nbverif', nbverif);
        @else
            formData.append('nbverif', 0);
        @endif
        formData.append('jeton_copie', '{{ Session::get('jeton_copie') }}');
        fetch('/devoir-autosave', {
            method: 'POST',
            headers: {"Content-Type": "application/x-www-form-urlencoded", "X-CSRF-Token": "{{ csrf_token() }}"},
            body: formData
        })
        .then(function(response) {
            // Renvoie la réponse du serveur (peut contenir un message de confirmation)
            if (response.ok) {
                return response.text();
            } else {
                window.location.replace("/devoir-fin?s1");
            }
        })
        .then(function(data) {
            // Affiche la réponse du serveur dans la console
            // Cette fonction de rappel sera exécutée uniquement si la réponse est 200
            console.log(data); 
        })
        .catch(function(error) {
            // Gère les erreurs liées à la requête Fetch
            console.error('Erreur:', error); 
        });			
    }

    autosave();
    setInterval(function() {
        autosave();
    }, 10000);

</script>	

<script>
    // CHRONO
    var intervalRef = null;
    var chrono = {
        start: function () {
            let start = new Date();
            intervalRef = setInterval(_ => {
                let current = new Date();
                count = {{ $devoir_eleve->chrono }} + +current - +start;
                let s = Math.floor((count /  1000)) % 60;
                let m = Math.floor((count / 60000)) % 60;
                if (s < 10) {
                    s_display = '0' + s;
                } else {
                    s_display = s;
                }
                if (m < 10) {
                    m_display = '0' + m;
                } else {
                    m_display = m;
                }
                $('#chrono').text(m_display + ":" + s_display);
            }, 1000);
        },
        stop: function () {
            clearInterval(intervalRef);
            delete intervalRef;
        },
    }
    chrono.start();	
</script>

@if ($devoir->with_console == 1)
<script>
    // PYODIDE

    const run = document.getElementById("run");
    const stop = document.getElementById("stop");
    const restart = document.getElementById("restart");
    const output = document.getElementById("output");
    const status = document.getElementById("status");

    // webworker
    let pyodideWorker = createWorker();

    function createWorker() {
        output.innerText = "Initialisation...\n";
        run.disabled = true;
        stop.style.display = 'none';
        restart.style.display = 'none';

        let pyodideWorker = new Worker("{{ asset('pyodideworker/devoir-pyodideWorker.js') }}");

        pyodideWorker.onmessage = function(event) {
            
            // reponses du WebWorker
            console.log("EVENT: ", event.data);

            if (typeof event.data.init !== 'undefined') {
                output.innerText = "Prêt!\n";
                run.innerHTML = '<i class="fas fa-play"></i>';
                run.disabled = false;
            }

            if (typeof event.data.status !== 'undefined') {

                if (event.data.status == 'running'){
                    run.disabled = true;
                    run.innerHTML = '<i class="fas fa-cog fa-spin"></i>';
                    stop.style.display = 'inline';
                }

                if (event.data.status == 'completed'){
                    run.disabled = false;
                    run.innerHTML = '<i class="fas fa-play"></i>';
                    stop.style.display = 'none';
                    restart.style.display = 'none';
                }

                if (event.data.status == 'success'){
                    run.style.display = "none";
                }
            }

            if (typeof event.data.output !== 'undefined') {
                output.innerHTML = event.data.output;
            }	

        };

        @if(App::isProduction())
            // ne fonctionne pas en local a cause de COEP et COOP
            // interruption python
            let interruptBuffer = new Uint8Array(new SharedArrayBuffer(1));
            pyodideWorker.postMessage({ cmd: "setInterruptBuffer", interruptBuffer });
        @endif

        stop.onclick = function() {
            // 2 stands for SIGINT.
            interruptBuffer[0] = 2;
            // bouton 'restart'
            restart.style.display = 'inline';
        }
        
        // arrete et redemarre le webworker
        restart.onclick = function() {
            restartWorker();
        }

        // envoi des donnees au webworker pour execution
        run.onclick = function() {
            @if(App::isProduction())
                // ne fonctionne pas en local a cause de COEP et COOP
                interruptBuffer[0] = 0;
            @endif
            const code = editor_code.getSession().getValue();;
            output.innerHTML = "";
            pyodideWorker.postMessage({ code: code });		
        }

        return pyodideWorker

    }

    function restartWorker() {
        if (pyodideWorker) {
            pyodideWorker.terminate();
            console.log("Web Worker supprimé.");
        }
        pyodideWorker = createWorker();
        console.log("Web Worker redémarré.");
    }

</script>
@endif

<script>
    // rendre
    $(document).on("click", "#rendre", function() {
        rendre();
    });
    function rendre() {
        var formData = new URLSearchParams();
        formData.append('code', encodeURIComponent(editor_code.getSession().getValue()));
        @if ($devoir->with_chrono == 1)
            formData.append('chrono', count);
        @else
            formData.append('chrono', 0);
        @endif
        @if ($devoir->with_nbverif == 1)
            formData.append('nbverif', nbverif);
        @else
            formData.append('nbverif', 0);
        @endif
        formData.append('jeton_copie', '{{ Session::get('jeton_copie') }}');

        fetch('/devoir-rendre', {
            method: 'POST',
            headers: {"Content-Type": "application/x-www-form-urlencoded", "X-CSRF-Token": "{{ csrf_token() }}"},
            body: formData
        })
        .then(function(response) {
            return response.text();
        })
        .then(function(data) {
            // Affiche la réponse du serveur dans la console
            // Cette fonction de rappel sera exécutée uniquement si la réponse est 200
            console.log(data); 
            window.location.replace("/devoir-fin?s"+data);
        })			
        .catch(function(error) {
            // Gère les erreurs liées à la requête Fetch
            console.error('Erreur:', error); 
        });
    }
</script>			

<script>		
    editor_code.on("paste", function(texteColle) {
        console.log("Text collé: " + texteColle.text);
        if (!editor_code.getSession().getValue().includes(texteColle.text)) {
            texteColle.text = "";
            console.log("Le collage de ce texte N'est PAS autorisé.");
        } else {
            console.log("Le collage de ce texte est autorisé.");
        }
    });
</script>

<script>
    function commencer() {
        if (document.documentElement.requestFullscreen) {
            document.documentElement.requestFullscreen(); // Méthode pour les navigateurs récents
        } else if (document.documentElement.mozRequestFullScreen) {
            document.documentElement.mozRequestFullScreen(); // Méthode pour Firefox
        } else if (document.documentElement.webkitRequestFullscreen) {
            document.documentElement.webkitRequestFullscreen(); // Méthode pour Chrome, Safari et Opera
        } else if (document.documentElement.msRequestFullscreen) {
            document.documentElement.msRequestFullscreen(); // Méthode pour Internet Explorer/Edge
        }
        document.getElementById('demarrer').remove();
    }

    // Ajoutez un gestionnaire d'événements à l'événement 'fullscreenchange'
    document.addEventListener('fullscreenchange', exitFullscreenHandler);
    document.addEventListener('webkitfullscreenchange', exitFullscreenHandler);
    document.addEventListener('mozfullscreenchange', exitFullscreenHandler);
    document.addEventListener('MSFullscreenChange', exitFullscreenHandler);

    function exitFullscreenHandler() {
        if (document.fullscreenElement === null || // Standard de la W3C
            document.webkitFullscreenElement === null || // Anciens navigateurs Webkit
            document.mozFullScreenElement === null || // Anciens navigateurs Firefox
            document.msFullscreenElement === null) { // Anciens navigateurs Internet Explorer/Edge
            console.log('La sortie du mode plein écran a été détectée.');
            window.location.replace("/devoir");
        }
    }
</script>
