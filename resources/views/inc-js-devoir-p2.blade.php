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
            inlineMath: [['$', '$'], ['\\(', '\\)']]
        },
        options: {
            ignoreHtmlClass: "no-mathjax",
            processHtmlClass: "mathjax"
        },
        svg: {
            fontCache: 'global'
        }
    };
</script>
<script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
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
    var textarea_code = $('#code');
    editor_code.getSession().on('change', function () {
        textarea_code.val(editor_code.getSession().getValue());
    });
    textarea_code.val(editor_code.getSession().getValue());
</script> 

<script>
    var nbverif = {{ $devoir_eleve->nbverif }};
    var count = {{ $devoir_eleve->chrono }};

    // autosave
    function autosave(){
        var formData = new URLSearchParams();
        formData.append('code', encodeURIComponent(document.getElementById('code').value));
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
                //window.location.replace("/devoir-fin?s1");
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
    // PYTHON
    var code = document.getElementById("code");

    function addToOutput(output_content) {
        //document.getElementById("output1").innerText = ""
        if (typeof(output_content) !== 'undefined'){
            document.getElementById("output1").innerText += output_content
        }
    }

    // init Pyodide
    async function main() {
        let pyodide = await loadPyodide();
        document.getElementById('attendre').style.display = 'none';
        document.getElementById('commencer').style.display = 'block';
        console.log("Prêt!");
        return pyodide;
    }

    let pyodideReadyPromise = main();

    async function evaluatePython() {
        console.log('EVALUATE PYTHON')
        nbverif++;
        document.getElementById('nbverif').innerText = nbverif;
        let pyodide = await pyodideReadyPromise;
        await pyodide.loadPackagesFromImports(code.value);
        
        try {
            // pas d'erreur python
            document.getElementById("output1").innerText = "";
            pyodide.setStdout({batched: (str) => {
                document.getElementById("output1").innerText += str+"\n";
                console.log(str);
            }})
            let output = pyodide.runPython(code.value);
            addToOutput(output); 
        } catch (err) {
            // erreur python
            let error_message = err.message.split("File \"<exec>\", ");
            error_message = "Error " + error_message[1];
            addToOutput(error_message);
        }				
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
        formData.append('code', encodeURIComponent(document.getElementById('code').value));
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