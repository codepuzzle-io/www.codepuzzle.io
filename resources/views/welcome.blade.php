@include('inc-top')
<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
	@php
		$description = __('Puzzles de Parsons, défis & entraînements/devoirs pour apprendre Python');
		$description_og = __('Puzzles de Parsons, défis & entraînements/devoirs pour apprendre Python');
	@endphp
	@include('inc-meta')
    <title>{{ strtoupper(config('app.name')) }} | {{__('Puzzles de Parsons & Défis')}}</title>
</head>
<body>
	@php
		$lang_switch = '<a href="lang/fr" class="kbd mr-1">fr</a><a href="lang/en" class="kbd">en</a>';
	@endphp
	@include('inc-nav')

	<div class="container mt-3">

		<div class="row pt-3">
			<div class="card-deck text-monospace">
				
				<div class="card">
					<div class="card-body p-0">
						<h2>{!!__('Créer et partager des puzzles de Parsons', ['link' => route('about')])!!}</h2>
						<div class="mx-auto mt-3 text-center" style="width:60%">
							<a class="btn btn-success btn-sm btn-block p-2" href="{{ route('puzzle-creer-get')}}" role="button"><i class="fa-solid fa-circle-plus pt-1 pb-1 mr-2"></i>{!!__('puzzle')!!}</a>
						</div>
						<div class="mt-3 small text-muted text-justify">
							Puzzles de Parsons en mode "réorganiser" ou "glisser-déposer". Avec ou sans code à compléter. Pour apprendre Python sans écrire de code.
						</div>	
					</div>
					<div class="text-center pt-2">
						<a class="btn btn-light btn-sm pl-3 pr-3" href="#puzzle" role="button" data-toggle="tooltip" data-placement="top" title="plus d'informations"><i class="fa-solid fa-circle-info"></i></a>
					</div>
				</div>

				<div class="card">
					<div class="card-body p-0">
						<h2>{!!__('Créer et partager des défis')!!}</h2>				
						<div class="mx-auto mt-3 text-center" style="width:60%">
							<a class="btn btn-success btn-sm btn-block p-2" href="{{ route('defi-creer-get')}}" role="button"><i class="fa-solid fa-circle-plus pt-1 pb-1 mr-2"></i>{!!__('défi')!!}</a>
						</div>
						<div class="mt-3 small text-muted text-justify">
							Défis Python avec jeux de tests à valider. Écrire ou compléter un programme Python en suivant les consignes fournies et tester le code en ligne.
						</div>	
					</div>
					<div class="text-center pt-2">
						<a class="btn btn-light btn-sm pl-3 pr-3" href="#defi" role="button" data-toggle="tooltip" data-placement="top" title="plus d'informations"><i class="fa-solid fa-circle-info"></i></a>
						<a class="btn btn-light btn-sm pl-3 pr-3" href="/defis-banque" role="button" data-toggle="tooltip" data-placement="top" title="banque de défis"><i class="fa-solid fa-box-archive"></i></a>
					</div>
				</div>

				<div class="card">
					<div class="card-body p-0">
						<h2>{!!__('Créer et partager des devoirs')!!}</h2>				
						<div class="mx-auto mt-3 text-center" style="width:60%">
							<a class="btn btn-success btn-sm btn-block p-2" href="{{ route('devoir-creer-get')}}" role="button"><i class="fa-solid fa-circle-plus pt-1 pb-1 mr-2"></i>{!!__('devoir')!!}</a>
						</div>
						<div class="mt-3 small text-muted text-justify">
							Activités en classe de type examen dans un environnement anti-triche.<br />Récupération automatique des travaux avec exécution du code et correction en ligne.
						</div>	
					</div>
					<div class="text-center pt-2">
						<a class="btn btn-light btn-sm pl-3 pr-3" href="#devoir" role="button" data-toggle="tooltip" data-placement="top" title="plus d'informations"><i class="fa-solid fa-circle-info"></i></a>
					</div>
				</div>

				<div class="card pt-1 pb-0" style="border:none !important;background-color:transparent !important">
					<div class="card-body p-0">				
						<div class="mx-auto text-center" style="width:80%">
							<a class="btn btn-dark btn-sm btn-block p-2" href="{{ route('classe-creer-get')}}" role="button"><i class="fas fa-chalkboard mr-2"></i><b>{!!__('CLASSE')!!}</b> <sup class="text-danger">bêta</sup></a>
						</div>
						<div class="mt-2 small text-muted text-justify">
							Créer une classe pour proposer des activités (puzzles, défis...) aux élèves et suivre l'avancement de leur travail.

							<a class="text-dark" href="#classe" role="button" data-toggle="tooltip" data-placement="top" title="plus d'informations"><i class="fa-solid fa-circle-info"></i></a>
						</div>
						<div class="pt-5 mx-auto text-center" style="width:80%">
							<i style="font-size:70%;color:silver;">optionnel</i>
							<a class="btn btn-secondary btn-sm btn-block" style="font-size:80%;opacity:0.6" href="{{route('register')}}" role="button">{{__('créer un compte')}}</a>
						</div>
						<div class="text-center mt-1" style="line-height:1;">
							<span style="font-size:70%;color:silver;">{{__('pour créer, sauvegarder, modifier et partager les activités proposées aux élèves')}}</span>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>

	<div id="bas" class="container pt-5" style="background-color:#f8fafc;overflow:auto;">
		<div class="row">

			<div class="col-md-1 text-right">

				<!-- boutons run / stop / restart -->
				<div class="mb-2">
					<div>
						<button id="run" style="width:40px;" type="button" class="btn btn-primary text-center mb-1"><i class="fas fa-circle-notch fa-spin"></i></button>
					</div>
					<div id="stop" class="mt-1 mb-1">
						<button style="width:40px;" type="button" class="btn btn-dark text-center mb-1" style="padding-top:6px;display:none;" data-toggle="tooltip" data-placement="right"  data-trigger="hover" title="{{__('Interruption de l\'exécution du code (en cas de boucle infinie ou de traitement trop long). L\'arrêt peut prendre quelques secondes.')}}"><i class="fas fa-stop"></i></button>
					</div>
					<div id="restart">
						<button style="width:40px;" type="button" class="btn btn-warning" style="padding-top:6px;display:none;" data-toggle="tooltip" data-placement="right"  data-trigger="hover" title="{{__('Si le bouton d\'arrêt ne permet pas d\'interrompre  l\'exécution du code, cliquer ici. Python redémarrera complètement mais votre code sera conservé dans l\'éditeur. Le redémarrage peut prendre quelques secondes.')}}"><i class="fas fa-skull"></i></button>
					</div>
				</div>
				<!-- /boutons run / stop / restart -->

				<!-- options -->
				<div class="mb-2">
					<a class="btn btn-light text-center" href="#" onclick="fullscreen('bas')" role="button" style="width:40px;">
						<i id="fs_on" class="fas fa-expand"></i>
						<i id="fs_off" class="fas fa-compress" style="display:none;"></i>
					</a>
				</div>
				<div class="btn-group-vertical mb-2">
					<button id="increaseFont" style="width:40px;" type="button" class="btn btn-light text-center"><i class="fas fa-plus"></i></button>
					<button id="decreaseFont" style="width:40px;" type="button" class="btn btn-light text-center"><i class="fas fa-minus"></i></button>
				</div>
				<div>
					<button id="editeur_console" style="width:40px;" type="button" class="btn btn-light text-center"><i class="fas fa-sort fa-rotate-90"></i></button>
				</div>
				<!-- /options -->				
			</div>

			<div class="col-md-10">
				<div class="row">

					<div id="editeur" class="col-md-12 mb-2">
						<div style="width:100%;">
							<div id="editor_code" style="border-radius:5px;">for _ in range(4):
    print("Code Puzzle")</div>
						</div>
					</div>
				
					<div id="console" class="col-md-12 mb-2">
						<div class="text-muted small text-monospace" style="float:right;padding:5px 12px 0px 0px">console</div>
						<pre id="output" class="text-monospace p-3 text-white bg-dark" style="border-radius:4px;min-height:100px;height:100%;font-size:20px;"></pre>
					</div>

				</div><!-- row --> 
			</div>

		</div><!-- row --> 
	</div><!-- container -->

	<div class="container mt-3">

		<a name="puzzle"></a>
		<div class="row mt-5 text-monospace">
			<div class="col-md-2">
				<div class="font-weight-bold text-success">PUZZLE DE PARSONS</div>
				<div class="font-weight-bold">Exemple 1</div>
				<div class="small text-muted">en mode "réorganiser"</div>
			</div>
			<div class="col-md-10 exemple">
				<div id="exemple1_div">
					<iframe id="exemple1_iframe" src="https://www.codepuzzle.io/IPNHVL" height="100%" width="100%" frameborder="0"></iframe>
				</div>
			</div>
		</div>

		<div class="row mt-5 text-monospace">
			<div class="col-md-2">
				<div class="font-weight-bold">Exemple 2</div>
				<div class="small text-muted">en mode "glisser-déposer" avec des lignes de code inutiles</div>
			</div>
			<div class="col-md-10 exemple">
				<div id="exemple2_div">
					<iframe id="exemple2_iframe" src="https://www.codepuzzle.io/IP39K2" height="100%" width="100%" frameborder="0"></iframe>
				</div>
			</div>
		</div>

		<div class="row mt-5 text-monospace">
			<div class="col-md-2">
				<div class="font-weight-bold">Exemple 3</div>
				<div class="small text-muted">en mode "réorganiser" avec code à compléter</div>
			</div>
			<div class="col-md-10 exemple">
				<div id="exemple3_div">
					<iframe id="exemple3_iframe" src="https://www.codepuzzle.io/IPUAH8" height="100%" width="100%" frameborder="0"></iframe>
				</div>
			</div>
		</div>

		<a name="defi"></a>
		<div class="row mt-5 text-monospace">
			<div class="col-md-2">
				<div class="font-weight-bold text-success">DÉFI</div>
				<div class="font-weight-bold">Exemple</div>
				<div class="small text-muted">avec quatre tests</div>
			</div>
			<div class="col-md-10 exemple">
				<div id="exemple4_div">
					<iframe id="exemple4_iframe" src="https://www.codepuzzle.io/IDZLB8" height="100%" width="100%" frameborder="0"></iframe>
				</div>
			</div>
		</div>	
		
		<a name="devoir"></a>
		<div class="row mt-5 text-monospace">
			<div class="col-md-2">
				<div class="font-weight-bold text-success">DEVOIRS</div>
				<div class="font-weight-bold">Diaporama</div>
			</div>
			<div class="col-md-10 exemple">

				<div id="carouselCaptions" class="carousel slide" data-interval="false">
					<div class="carousel-inner">
						<div class="carousel-item active" style="margin-top:100px;">
							<img src="{{ asset('img/devoir/devoir-00.png') }}" class="d-block w-100" alt="E1">
							<div class="carousel-caption d-none d-md-block text-center text-white small">
								<span class="bg-dark p-1 pl-2 pr-2">console enseignant</span>
							</div>
						</div>
						<div class="carousel-item" style="margin-top:100px;">
							<img src="{{ asset('img/devoir/devoir-01.png') }}" class="d-block w-100" alt="E1">
							<div class="carousel-caption d-none d-md-block text-center text-white small">
								<span class="bg-dark p-1 pl-2 pr-2">création d'un nouvel entraînement/devoir</span>
							</div>
						</div>
						<div class="carousel-item" style="margin-top:100px;">
							<img src="{{ asset('img/devoir/devoir-02.png') }}" class="d-block w-100" alt="E1">
							<div class="carousel-caption d-none d-md-block text-center text-white small">
								<span class="bg-dark p-1 pl-2 pr-2">correction du travail d'un élève</span>
							</div>
						</div>
						<div class="carousel-item" style="margin-top:100px;">
							<img src="{{ asset('img/devoir/devoir-03.png') }}" class="d-block w-100" alt="E1">
							<div class="carousel-caption d-none d-md-block text-center text-white small">
								<span class="bg-dark p-1 pl-2 pr-2">lancement de l'entraînement/devoir côté élève</span>
							</div>
						</div>
						<div class="carousel-item" style="margin-top:100px;">
							<img src="{{ asset('img/devoir/devoir-04.png') }}" class="d-block w-100" alt="E1">
							<div class="carousel-caption d-none d-md-block text-center text-white small">
								<span class="bg-dark p-1 pl-2 pr-2">page de l'entraînement/devoir</span>
							</div>
						</div>	
						<div class="carousel-item" style="margin-top:100px;">
							<img src="{{ asset('img/devoir/devoir-05.png') }}" class="d-block w-100" alt="E1">
							<div class="carousel-caption d-none d-md-block text-center text-white small">
								<span class="bg-dark p-1 pl-2 pr-2">page verrouillée - appel de l'enseignant</span>
							</div>
						</div>																		
					</div>
					<a class="carousel-control-prev" href="#carouselCaptions" role="button" data-slide="prev">
						<i class="fa-solid fa-circle-chevron-left text-dark fa-2xl"></i>
						<span class="sr-only">Previous</span>
					</a>
					<a class="carousel-control-next" href="#carouselCaptions" role="button" data-slide="next">
						<i class="fa-solid fa-circle-chevron-right text-dark fa-2xl"></i>
						<span class="sr-only">Next</span>
					</a>
				</div>

			</div>
		</div>	

		<a name="classe"></a>
		<div class="row mt-5 text-monospace">
			<div class="col-md-2">
				<div class="font-weight-bold text-success">CLASSE</div>
				<div class="font-weight-bold">Suivi des activités</div>
			</div>
			<div class="col-md-10 exemple">
				<img src="{{ asset('img/classe/classe_suivi.png') }}" class="d-block w-100" />
			</div>
		</div>	

	</div><!--container -->

	@include('inc-footer')
	<div style="padding-bottom:1000px;">&nbsp;</div>
	@include('inc-bottom-js')

	<script>
		// PYODIDE

		const run = document.getElementById("run");
		const stop = document.getElementById("stop");
		const restart = document.getElementById("restart");
		const output = document.getElementById("output");

		// webworker
		let pyodideWorker = createWorker();

		function createWorker() {
			output.innerText = "Initialisation...\n";
			run.disabled = true;
			stop.style.display = 'none';
			restart.style.display = 'none';

			let pyodideWorker = new Worker("{{ asset('pyodideworker/bas-pyodideWorker.js') }}");

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
						stop.style.display = 'block';
					}

					if (event.data.status == 'completed'){
						run.disabled = false;
						run.innerHTML = '<i class="fas fa-play"></i>';
						stop.style.display = 'none';
						restart.style.display = 'none';
					}
				}

				if (typeof event.data.output !== 'undefined') {
					output.innerHTML += event.data.output;
				}	

			};

			@if(App::isProduction())
				// ne fonctionne pas en local a cause de COEP et COOP
				// interruption python
				let interruptBuffer = new Uint8Array(new SharedArrayBuffer(1));
				pyodideWorker.postMessage({ cmd: "setInterruptBuffer", interruptBuffer });
			@endif

			stop.onclick = function() {
				@if(App::isProduction())
					// ne fonctionne pas en local a cause de COEP et COOP
					// 2 stands for SIGINT.
					interruptBuffer[0] = 2;
				@endif
				// bouton 'restart'
				restart.style.display = 'block';
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
				const code = editor_code.getSession().getValue();
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

	<script>
		for (var i = 1; i <= 4; i++) {
			(function(index) {
				document.getElementById('exemple' + index + '_iframe').addEventListener('load', function() {
					var iframeHeight = document.getElementById('exemple' + index + '_iframe').contentWindow.document.body.scrollHeight;
					document.getElementById('exemple' + index + '_div').style.height = iframeHeight+20 + 'px';
				});
			})(i);
		}
	</script>

	<script>
		function fullscreen(id) {
			var el = document.getElementById(id);
			var isFullscreen = document.fullscreenElement || document.webkitFullscreenElement || document.msFullscreenElement;

			if (isFullscreen) {
				// Quitter le plein écran
				if (document.exitFullscreen) {
					document.exitFullscreen();
				} else if (document.webkitExitFullscreen) { /* Safari */
					document.webkitExitFullscreen();
				} else if (document.msExitFullscreen) { /* IE11 */
					document.msExitFullscreen();
				}
			} else {
				// Entrer en plein écran
				if (el.requestFullscreen) {
					el.requestFullscreen();
				} else if (el.webkitRequestFullscreen) { /* Safari */
					el.webkitRequestFullscreen();
				} else if (el.msRequestFullscreen) { /* IE11 */
					el.msRequestFullscreen();
				}
			}
		}

		document.addEventListener("fullscreenchange", function() {
			updateFsButton();
		}, false);

		document.addEventListener("webkitfullscreenchange", function() {
			updateBupdateFsButtonutton();
		}, false);

		document.addEventListener("mozfullscreenchange", function() {
			updateFsButton();
		}, false);

		document.addEventListener("MSFullscreenChange", function() {
			updateFsButton();
		}, false);

		function updateFsButton() {
			var fullscreenElement = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement;
			var button = document.getElementById("fullscreenButton");
			
			if (fullscreenElement) {
				document.getElementById('fs_on').style.display = "none";
				document.getElementById('fs_off').style.display = "inline";
			} else {
				document.getElementById('fs_on').style.display = "inline";
				document.getElementById('fs_off').style.display = "none";
			}
		}
	</script>   

	<script src="{{ asset('js/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>
	<script>
		var editor_code = ace.edit("editor_code", {
			theme: "ace/theme/puzzle_code",
			mode: "ace/mode/python",
			maxLines: 500,
			minLines: 8,
			//fontSize: 20,
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
		var fontSize = 20;
		editor_code.setFontSize(fontSize);
	</script> 

	<script>
		document.getElementById("increaseFont").addEventListener("click", function() {
			fontSize++;
			editor_code.setFontSize(fontSize);
			document.getElementById("output").style.fontSize = fontSize + 'px';
		});

		document.getElementById("decreaseFont").addEventListener("click", function() {
			if (fontSize > 10) {
				fontSize--;
				editor_code.setFontSize(fontSize);
				document.getElementById("output").style.fontSize = fontSize + 'px';
			}
		});
	</script>
	
	<script>
		var div_editeur_console = document.getElementById("editeur_console");
		div_editeur_console.addEventListener("click", function() {
			var div_editeur = document.getElementById("editeur");
			var div_console = document.getElementById("console");
			if (div_editeur.classList.contains("col-md-12")) {
				div_editeur_console.innerHTML = '<i class="fas fa-sort"></i>';
				div_editeur.classList.remove("col-md-12");
    			div_editeur.classList.add("col-md-6");
				div_console.classList.remove("col-md-12");
    			div_console.classList.add("col-md-6");				
			} else {
				div_editeur_console.innerHTML = '<i class="fas fa-sort fa-rotate-90"></i>';
				div_editeur.classList.remove("col-md-6");
    			div_editeur.classList.add("col-md-12");
				div_console.classList.remove("col-md-6");
    			div_console.classList.add("col-md-12");
			}
		});
	</script>





	<script>
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		  return new bootstrap.Tooltip(tooltipTriggerEl)
		})
	</script>

</body>
</html>
