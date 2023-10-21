<?php
$devoir = App\Models\Devoir::where('jeton', Session::get('jeton_devoir'))->first();
$devoir_eleve = App\Models\Devoir_eleve::where('jeton_copie', Session::get('jeton_copie'))->first();
?>
@include('inc-top')
<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>

	<script>
		// Événement lorsque l'utilisateur quitte la page
		window.addEventListener('blur', function() {
			window.location.replace("/devoir");
		});
	</script>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Favicon -->
	<link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<!-- Font Awesome -->
	<script src="https://kit.fontawesome.com/fd76a35a36.js" crossorigin="anonymous"></script>

	<!-- Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200&display=swap" rel="stylesheet">

	<!-- Styles -->
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('css/custom.css') }}" rel="stylesheet">

	@include('inc-matomo')
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
    <script src="https://cdn.jsdelivr.net/pyodide/v0.24.1/full/pyodide.js"></script>
    <title>ENTRAÎNEMENT</title>

</head>

<body class="no-mathjax" oncontextmenu="return false" onselectstart="return false" ondragstart="return false">

    <!-- Écran de démarrage -->
    <div id="demarrer" class="demarrer">
		<div id="commencer" style="display:none">
			<button onclick="commencer()" type="button" class="btn btn-primary btn-lg text-monospace" style="width:180px;font-size:100%;">commencer</button>
			<br/><br/>
			<i class="fas fa-exclamation-triangle text-danger"></i>
			<br />
			<span class="text-monospace text-danger">ne pas quitter le mode<br />plein écran</span>
		</div>
		<button id="attendre" type="button" class="btn btn-primary btn-lg text-monospace" style="width:180px;" disabled><img src="{{ asset('img/chargement.gif') }}" width="40" /></button>
    </div>

	<div class="bg-danger text-white p-2 text-monospace text-center mb-4">ne pas quitter cette page - ne pas recharger cette page - ne pas cliquer en-dehors de cette page - ne pas quitter le mode plein écran</div>

    <div class="container mt-5">

		<table align="center" cellpadding="2" style="text-align:center;margin-bottom:20px;color:#bdc3c7;border-spacing:5px;border-collapse:separate;">
			<tr>
				@if ($devoir->with_chrono == 1)
				<td class="dashboard"><i class="fas fa-clock"></i>&nbsp;&nbsp;<span id="chrono">00:00</span></td>
				@endif
				@if ($devoir->with_nbverif == 1)
				<td class="dashboard"><i class="fas fa-check"></i>&nbsp;&nbsp;<span id="nbverif">{{ $devoir_eleve->nbverif }}</span></td>
				@endif
				<td class="m-0 p-0">
					<a tabindex='0' class='btn btn-success text-monospace' role='button'  style="cursor:pointer;outline:none;" data-toggle="popover" data-trigger="focus" data-placement="left" data-html="true" data-sanitize="false" data-content="<a href='#' id='rendre' class='btn btn-danger btn-sm text-light' role='button'>{{__('confirmer')}}</a><a class='btn btn-light btn-sm ml-2' href='#' role='button'>{{__('annuler')}}</a>">rendre</a>
				</td>
			</tr>
		</table>

        @if ($devoir->titre_eleve !== NULL OR $devoir->consignes_eleve !== NULL)
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="frame">
                    @if ($devoir->titre_eleve !== NULL)
                        <div class="font-monospace small mb-1">{{ $devoir->titre_eleve }}</div>
                    @endif
                    @if ($devoir->consignes_eleve !== NULL)
                        <div class="text-monospace text-muted consignes mathjax" style="text-align:justify;">
                            <?php
                            $Parsedown = new Parsedown();
                            echo $Parsedown->text($devoir->consignes_eleve);
                            ?>
                        </div>
					@endif

                </div>
            </div>
        </div><!-- row -->
        @endif

    </div>

    <div class="container">

        <div class="row">
            <div class="col-md-8 offset-md-2 text-center">
                <textarea name="code" style="display:none;" id="code"></textarea>
		        <div style="width:100%;margin:0px auto 0px auto;"><div id="editor_code" style="border-radius:5px;">{{$devoir_eleve->code_eleve}}</div></div>
                <!-- bouton verifier -->
				@if ($devoir->with_console == 1)
                <button onclick="evaluatePython()" type="button" class="btn btn-primary btn-sm mt-2 text-monospace" style="display:inline">exécuter le code</button>
				@endif
            </div>
        </div>
        
		@if ($devoir->with_console == 1)
        <div class="row mt-3">
            <div class="col-md-6 offset-md-3">
                <div>Console</div>
                <pre id="output1" class="bg-dark text-monospace p-3 small text-white" style="border-radius:4px;border:1px solid silver;min-height:100px;"></pre>
            </div>
        </div>    
		@endif
		  
    </div><!-- container -->

    @include('inc-bottom-js')

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
		// CHRONO
		var count;
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
		const code = document.getElementById("code");
		var nbverif = {{ $devoir_eleve->nbverif }};

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
		// autosave
		setInterval(function() {
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
				return response.text();
			})
			.then(function(data) {
				// Affiche la réponse du serveur dans la console
				console.log(data); 
			})
			.catch(function(error) {
				// Gère les erreurs liées à la requête Fetch
				console.error('Erreur:', error); 
			});
		}, 10000);
	</script>

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
				window.location.replace("/devoir-fin");
			})
			.catch(function(error) {
				// Gère les erreurs liées à la requête Fetch
				//console.error('Erreur:', error); 
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

    <script>
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		  return new bootstrap.Tooltip(tooltipTriggerEl)
		})
	</script>

</body>
</html>
