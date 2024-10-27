<?php
// recuperation du programme en fonction du jeton
$programme = App\Models\Programme::where('jeton', $jeton)->first();
?>
@include('inc-top')
<!doctype html>
<html lang="fr">
<head>
	@php
        $description = __('Générateur et gestionnaire de puzzles de Parsons') . ' | Programme - R' . strtoupper($jeton);
        $description_og = 'Programme - R' . strtoupper($jeton);
    @endphp
	@include('inc-meta-jeton')
    <title>{{ config('app.name') }} | Programme - R{{ $jeton }}</title>
</head>

<body>

	<div id="bas" class="container-fluid pt-4" style="height:100%;background-color:#f8fafc;overflow:auto;">
		<div class="row" style="height:100%;">

			<div id="controle" style="position:absolute;bottom:40px;left:20px;width:42px;">

				<!-- boutons run / stop / restart -->
				<div class="mb-2">
					<div>
						<button id="run" style="width:40px;" type="button" class="btn btn-primary text-center mb-1"><i class="fas fa-circle-notch fa-spin"></i></button>
					</div>
					<!--
					<div id="stop" class="mt-1 mb-1">
						<button style="width:40px;" type="button" class="btn btn-dark text-center mb-1" style="padding-top:6px;display:none;" data-toggle="tooltip" data-placement="right"  data-trigger="hover" title="{{__('Interruption de l\'exécution du code (en cas de boucle infinie ou de traitement trop long). L\'arrêt peut prendre quelques secondes.')}}"><i class="fas fa-stop"></i></button>
					</div>
					-->
					<div id="restart">
						<button style="width:40px;" type="button" class="btn btn-dark text-center mb-1" style="padding-top:6px;display:none;" data-toggle="tooltip" data-placement="right"  data-trigger="hover" title="{{__('Interruption de l\'exécution du code (en cas de boucle infinie ou de traitement trop long). L\'arrêt et le redémarrage peuvent prendre quelques secondes.')}}"><i class="fas fa-stop"></i></button>
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

				@if(Auth::check())
				<?php
				$programmes_liens = "<div class='p-2' style='width:300px'><div class='text-monospace'><b>PROGRAMMES</b></div><ul class='text-monospace m-0 pl-3'>";
				$liste_programmes = App\Models\Programme::where('user_id', Auth::id())->get();
				foreach($liste_programmes AS $item) {
					$programmes_liens .= "<li class='mt-1 mb-1'><a href='/R".strtoupper($item->jeton)."' target='_blank'>" . $item->titre_enseignant . "</a></li>";
				}
				$programmes_liens .= '</ul></div>';
				?>
				<div>
					<button id="liste_programmes" style="width:40px;" type="button" class="mt-2 btn btn-light text-center"data-toggle="popover" data-html="true" data-content="{!! $programmes_liens !!}"><i class="fas fa-list-ul"></i></button>
				</div>
				@endif
				<!-- /options -->	

			</div>

			<div class="col-md-11 offset-md-1">
				<div class="row">
				
					<div class="col-md-12 mb-2">
						<h1><a href="/"><img src="{{ asset('img/codepuzzle.png') }}" width="220" alt="CODE PUZZLE" /></a></h1>
					</div>

				
					@if ($programme->titre_eleve !== NULL)
						<div class="col-md-12 mb-2 text-monospace ">
							<b>{{ $programme->titre_eleve}}</b>
						</div>
					@endif

					<div id="editeur" class="col-md-12 mb-2">
						<div style="width:100%;">
							<div id="editor_code" style="border-radius:5px;">{{ $programme->code}}</div>
						</div>
					</div>
				
					<div id="console" class="col-md-12" style="padding-bottom:1000px">
						<div class="text-muted small text-monospace" style="float:right;padding:5px 12px 0px 0px">console</div>
						<div id="output" class="text-monospace p-3 text-white bg-dark" style="white-space: pre-wrap;border-radius:4px;min-height:100px;height:100%;font-size:20px;"></div>
					</div>
										
				</div><!-- row --> 
			</div>
			
		</div><!-- row --> 
	</div><!-- container -->

	@include('inc-bottom-js')

	<script>
		// PYODIDE

		const run = document.getElementById("run");
		//const stop = document.getElementById("stop");
		const restart = document.getElementById("restart");
		const output = document.getElementById("output");

		// webworker
		let pyodideWorker = createWorker();

		function createWorker() {
			output.innerText = "Initialisation...\n";
			run.disabled = true;
			//stop.style.display = 'none';
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
						//stop.style.display = 'block';
						restart.style.display = 'block';
					}

					if (event.data.status == 'completed'){
						run.disabled = false;
						run.innerHTML = '<i class="fas fa-play"></i>';
						//stop.style.display = 'none';
						restart.style.display = 'none';
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
				let interruptBuffer = new Uint8Array(new SharedArrayBuffer(1));
				pyodideWorker.postMessage({ cmd: "setInterruptBuffer", interruptBuffer });
			@endif
			*/

			/*
			stop.onclick = function() {
				@if(App::isProduction())
					// ne fonctionne pas en local a cause de COEP et COOP
					// 2 stands for SIGINT.
					interruptBuffer[0] = 2;
				@endif
				// bouton 'restart'
				restart.style.display = 'block';
			}
			*/
			
			// arrete et redemarre le webworker
			restart.onclick = function() {
				restartWorker();
			}

			// envoi des donnees au webworker pour execution
			run.onclick = function() {
				/*
				@if(App::isProduction())
					// ne fonctionne pas en local a cause de COEP et COOP
					interruptBuffer[0] = 0;
				@endif
				*/
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
			highlightActiveLine: true,
			highlightSelectedWord: true,
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
        $("#liste_programmes").popover({
            html: true,
            sanitize: false,
			container: "#liste_programmes",
        });

        jQuery(function ($) {
            $("[data-toggle='popover']").popover({trigger: "hover"}).click(function (event) {
                event.stopPropagation();

            }).on('inserted.bs.popover', function () {
                $(".popover").click(function (event) {
                event.stopPropagation();
                })
            })

            $(document).click(function () {
                $("[data-toggle='popover']").popover('hide')
            })
        })
    </script>

    <script>
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		  return new bootstrap.Tooltip(tooltipTriggerEl)
		})
	</script>
	
</body>
</html>
