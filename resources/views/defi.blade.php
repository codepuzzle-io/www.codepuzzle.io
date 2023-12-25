<?php
// recuperation du défi en fonction du jeton
$defi = App\Models\Defi::where('jeton', $jeton)->first();
$tests = unserialize($defi->tests);
$asserts = '';
foreach($tests as $test){
	$asserts .=  '["assert '.$test[0].'", "'.addslashes($test[1]).'"],';
}
$asserts = '[' . trim($asserts, ',') . ']';
?>
@include('inc-top')
<!doctype html>
<html lang="fr">
<head>
	@php
        $description = __('Générateur et gestionnaire de puzzles de Parsons') . ' | Défi - D' . strtoupper($jeton);
        $description_og = 'Défi - D' . strtoupper($jeton);
    @endphp
	@include('inc-meta-jeton')
    <title>{{ config('app.name') }} | Défi - D{{ $jeton }}</title>
	<style>
		pre {
			padding:10px;
			border: solid 1px #fafcfe;
			background-color:#fafcfe;
			border-radius:4px;
			color:#6c757d;
			font-size:95%;
		}
	</style>
</head>

<body class="no-mathjax" oncontextmenu="return false" onselectstart="return false" ondragstart="return false">

	<?php
	// defi avec jeton eleve
	if(isset($jeton_eleve)) {
		$eleve = App\Models\Classes_eleve::where('jeton_eleve', $jeton_eleve)->first();
		if (!$eleve) {
			$segments = explode('/', $_SERVER['REQUEST_URI']);
			?>
			<div class="container text-monospace">
				<div class="row">
					<div class="col-md-6 offset-md-3 text-center mt-5 mb-4">
						CODE INDIVIDUEL
						<p class="small text-muted">Votre code individuel vous a été fourni par votre enseignant. Si vous n'avez pas de code individuel, cliquez sur le bouton "continuer sans code".</p>
					</div>
				</div>
				<div class="row">
            		<div class="col-md-5 text-right">
						<a class="btn btn-primary btn-sm pl-3 pr-3" href="/{{$segments[1]}}/" role="button">continuer sans code</a>
					</div>
					<div class="col-md-2 text-center text-muted">
						<i class="fas fa-ellipsis-v"></i>
					</div>
					<div class="col-md-5">
						<form class="form-inline">
							<div class="form-group">
								<input id="code" type="text" class="form-control form-control-sm" placeholder="code" />
							</div>
							<a class="btn btn-primary btn-sm ml-2" href="#" role="button" onclick="
								if (document.getElementById('code').value){
									code = document.getElementById('code').value;
								} else {
									code = '@';
								}
								window.location.href = '/{{$segments[1]}}/'+code;
							"><i class="fas fa-check"></i></a>
						</form>
					</div>
				</div>
			</div>
			<?php
			exit();
		}
	}
	?>

	<div class="container-fluid">

		@if(!$iframe)
		<h1 class="mt-2 mb-5 text-center"><a class="navbar-brand m-1" href="{{ url('/') }}"><img src="{{ asset('img/codepuzzle.png') }}" height="25" alt="CODE PUZZLE" /></a></h1>
		@endif

		@if ($defi->with_chrono == 1 OR $defi->with_nbverif == 1)
		<table align="center" cellpadding="2" style="text-align:center;margin-bottom:10px;color:#bdc3c7;">
			<tr>
				@if ($defi->with_chrono == 1)
				<td><i class="fas fa-clock"></i></td>
				@endif
				@if ($defi->with_nbverif == 1)
				<td><i class="fas fa-check"></i></td>
				@endif
			</tr>
			<tr>
				@if ($defi->with_chrono == 1)
				<td><span id="chrono" class="dashboard">00:00</span></td>
				@endif
				@if ($defi->with_nbverif == 1)
				<td><span id="nb_tentatives" class="dashboard">0</span></td>
				@endif
			</tr>
		</table>
		@endif

        @if ($defi->titre_eleve !== NULL OR $defi->consignes_eleve !== NULL)
        <div class="row" style="padding-top:10px;">
            <div class="col-md-10 offset-md-1">
                <div class="frame text-monospace">
                    @if ($defi->titre_eleve !== NULL)
                        <div class="mb-1">{{ $defi->titre_eleve }}</div>
                    @endif

					<?php
					include('lib/parsedownmath/ParsedownMath.php');
					$Parsedown = new ParsedownMath([
						'math' => [
							'enabled' => true, // Write true to enable the module
							'matchSingleDollar' => true // default false
						]
					]);
					$consignes_parsed = $Parsedown->text($defi->consignes_eleve)
					?>

                    @if ($defi->consignes_eleve !== NULL)
                        <div class="consignes mathjax" style="text-align:justify;">
							<?php
							echo $consignes_parsed;
							?>
                        </div>
					@endif

					<div id="consignes_hidden" class="mathjax" style="padding:30px 20px 0px 20px;width:1200px;height:630px;background-color:white;display:none;">
						<img src="{{ asset('img/codepuzzle.png') }}" height="30" />
						<div class="consignes" style="text-align:justify;padding:20px 40px 20px 40px;margin-top:25px;border-radius:10px;font-size:28px;background-color:#F8FAFC;">
							<?php
							echo $consignes_parsed;
							?>
						</div>
					</div>

                </div>
            </div>
        </div><!-- row -->
        @endif

    </div>

    <div class="container-fluid pb-5">
        <div class="row">
            <div class="col-md-10 offset-md-1 text-center" style="position:relative;height:30px;">
				<!-- bouton reinitialiser -->
				<a id="reinitialiser" href="{{ request()->fullUrl() }}" style="position:absolute;left:25px;top:10px;" class="text-muted" data-bs-toggle="tooltip" data-bs-placement="right"  data-bs-trigger="hover" title="{{__('réinitialiser')}}"><i class="fas fa-sync-alt"></i></a>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-10 offset-md-1 text-center">
                <textarea name="code" style="display:none;" id="code"></textarea>
		        <div style="width:100%;margin:0px auto 0px auto;"><div id="editor_code" style="border-radius:5px;">{{$defi->code}}</div></div>
                <!-- annonce enregistrement reponse -->
				<div id="enregistrement_reponse" class="text-monospace small mt-2"></div>

                <!-- boutons run / stop -->
				<div class="text-left">
                	<button id="run" type="button" class="btn btn-primary pl-4 pr-4" disabled><i class="fas fa-play"></i></button>
                	<button id="stop" type="button" class="btn btn-dark pl-4 pr-4" style="display:none"><i class="fas fa-stop"></i></button>
				</div>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-md-4 offset-md-4 text-monospace small">
				<table style="width:100%">
                @foreach($tests AS $test)
				<tr>
				<td class="text-center" style="vertical-align:top"><div id="test_{{$loop->index}}" class="test"><i class="fas fa-question-circle"></i></div></td>
				<td style="width:100%;">
					<div id="test_message_{{$loop->index}}" class="text-muted pl-2" style="height:100%;">
						<div>Test {{$loop->index + 1}}</div>
					</div>
				</td>
				</tr>
                @endforeach
				</table>
            </div>
        </div>

        <div class="row mt-3 pb-5" @if(!$defi->with_console) style="display:none" @endif  >
            <div class="col-md-10 offset-md-1">
                <div>Console</div>
                <pre id="output1" class="text-monospace p-2 small text-muted" style="border-radius:4px;border:1px solid silver"></pre>
				<div>Sortie</div>
                <pre id="output2" class="text-monospace p-3 text-white bg-dark" style="border-radius:4px;border:1px solid silver;min-height:100px;"></pre>
            </div>
        </div>    
		  
    </div><!-- container -->

    @include('inc-bottom-js')

	<script src="https://cdn.jsdelivr.net/pyodide/v0.24.1/full/pyodide.js"></script>
    <script>
		// PYODIDE

		const run = document.getElementById("run");
		const stop = document.getElementById("stop");
		const output1 = document.getElementById("output1");
		const output2 = document.getElementById("output2");
		const status = document.getElementById("status");
		
		// webworker
		let pyodideWorker = new Worker("{{ asset('pyodideworker/pyodideWorker.js') }}");

		// interruption python
		let interruptBuffer = new Uint8Array(new SharedArrayBuffer(1));
		pyodideWorker.postMessage({ cmd: "setInterruptBuffer", interruptBuffer });
		stop.onclick = function() {
			// 2 stands for SIGINT.
  			interruptBuffer[0] = 2;
		}

		output1.innerText = "Initialisation...\n";

		// envoi des donnees au webworker pour execution
		run.onclick = function() {
			interruptBuffer[0] = 0;
			const code = document.getElementById("code").value;
			const asserts = {!!$asserts!!};
			output1.innerHTML = "";
			output2.innerHTML = "";
			pyodideWorker.postMessage({ code: code, asserts: asserts });		
		}

		pyodideWorker.onmessage = function(event) {
			
			// reponses du WebWorker
			console.log("EVENT: ", event.data);

			if (typeof event.data.init !== 'undefined') {
				output1.innerText = "Prêt!\n";
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
				}

				if (event.data.status == 'success'){
					run.disabled = true;
					run.classList.remove('btn-primary');
					run.classList.add('btn-light');
					run.innerHTML = '<i class="fas fa-check"></i>';
					@if(isset($jeton_eleve))
						classe_activite_enregistrer();
					@endif
					bravo();
				}
			}

			if (typeof event.data.output1 !== 'undefined') {
				output1.innerHTML = event.data.output1;
			}	

			if (typeof event.data.output2 !== 'undefined') {
				output2.innerHTML += event.data.output2;
			}	

			if (typeof event.data.assert_erreur !== 'undefined') {
				var test_message = "Test non validé :-/";
				//if (assert[1]) {
				//	var test_message = assert[1];
				//}
				document.getElementById('test_' + event.data.assert_erreur).className = "test_failed";
				document.getElementById('test_' + event.data.assert_erreur).innerHTML = '<i class="fas fa-times-circle"></i>';
                document.getElementById('test_message_' + event.data.assert_erreur).innerHTML = test_message;
			}

			if (typeof event.data.assert_valide !== 'undefined') {
				document.getElementById('test_' + event.data.assert_valide).className = "test_success";
				document.getElementById('test_' + event.data.assert_valide).innerHTML = '<i class="fas fa-check-circle"></i>';
                document.getElementById('test_message_' + event.data.assert_valide).innerHTML = 'Test validé!';
			}

		};
	</script>	

	<script src="{{ asset('js/html2canvas.min.js') }}" type="text/javascript" charset="utf-8"></script>
	<script>
		html2canvas(document.getElementById('consignes_hidden'), {
			onclone: function (clonedDoc) {
				clonedDoc.getElementById('consignes_hidden').style.display = 'block';
			}	
		}).then(function (canvas) {

			var imgData = canvas.toDataURL('image/png');
			// Envoie des données de l'image au serveur (voir l'étape suivante)
			var formData = new URLSearchParams();
			formData.append('imgData', imgData);
			formData.append('jeton', '{{ 'D'.$jeton }}');
			fetch('/save-opengraph-image', {
				method: 'POST',
				mode: "cors",
				headers: {"Content-Type": "application/x-www-form-urlencoded", "X-CSRF-Token": "{{ csrf_token() }}"},
				body: formData
			})
			.then(response => {
				if (response.ok) {
					// Le serveur a répondu avec succès, vous pouvez traiter la réponse ici
					return response.text();
				}
				throw new Error('Erreur lors de la sauvegarde de la capture d\'écran.');
			})
			.then(data => {
				console.log('Capture d\'écran sauvegardée avec succès sur le serveur.');
				console.log('Chemin de l\'image sauvegardée : ' + data);
			})
			.catch(error => {
				// Il y a eu une erreur lors de la requête
				console.error(error);
			});
		});
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
		editor_code.container.style.lineHeight = 1.5;
		var textarea_code = $('#code');
		editor_code.getSession().on('change', function () {
			textarea_code.val(editor_code.getSession().getValue());
		});
		textarea_code.val(editor_code.getSession().getValue());
	</script>   
	
	
	<script>
		var count;
		var intervalRef = null;

		var chrono = {
			start: function () {
				let start = new Date();
				intervalRef = setInterval(_ => {
					let current = new Date();
					count = +current - +start;
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


	@if(isset($jeton_eleve))
		<script>
			function classe_activite_enregistrer() {
				var formData = new URLSearchParams();
				formData.append('jeton_activite', '{{ Crypt::encryptString('D'.$jeton) }}');
				formData.append('jeton_eleve', '{{ Crypt::encryptString($jeton_eleve) }}');				
				fetch('/classe-activite-enregistrer', {
					method: 'POST',
					mode: "cors",
					headers: {"Content-Type": "application/x-www-form-urlencoded", "X-CSRF-Token": "{{ csrf_token() }}"},
					body: formData
				})
				.then(response => {
					if (response.ok) {
						// le serveur a repondu avec succes
						document.getElementById('enregistrement_reponse').innerHTML = "<span class='text-success'>Réponse enregistrée. Retour à la <a href='/@/{{ $jeton_eleve }}'>console</a>.<span>";
					} else {
						document.getElementById('enregistrement_reponse').innerHTML = "<span class='text-danger'>Erreur lors de l'enregistrement - renvoyer la réponse</span>";
					}
				})
				.then(data => {
					console.log(data);
				})
				.catch(error => {
					// erreur lors de la requete
					document.getElementById('enregistrement_reponse').innerHTML = "<span class='text-danger'>Erreur lors de l'enregistrement - renvoyer la réponse</span>";
					console.error(error);
				});
				
			};
		</script>	
	@endif


	<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>	
	<script>
		function bravo() {
			var defaults = {
				spread: 360,
				ticks: 400,
				gravity: 1,
				decay: 0.94,
				startVelocity: 40,
				shapes: ['star'],
				colors: ['FFE400', 'FFBD00', 'E89400', 'FFCA6C', 'FDFFB8']
			};
			function shoot() {
				confetti({
					...defaults,
					particleCount: 80,
					scalar: 2,
					shapes: ['star']
				});

				confetti({
					...defaults,
					particleCount: 40,
					scalar: 5,
					shapes: ['circle']
				});
			}
			setTimeout(shoot, 0);
			setTimeout(shoot, 200);
			setTimeout(shoot, 400);
			setTimeout(shoot, 600);
			setTimeout(shoot, 800);
			setTimeout(shoot, 1000);
			setTimeout(shoot, 1200);
			setTimeout(shoot, 1400);
			setTimeout(shoot, 1500);
		}
	</script>	


	@if(!Auth::check())
	<script>
	/*		
		editor_code.on("paste", function(texteColle) {
			console.log("Text collé: " + texteColle.text);
			if (!editor_code.getSession().getValue().includes(texteColle.text)) {
				texteColle.text = "";
				console.log("Le collage de ce texte N'est PAS autorisé.");
			} else {
				console.log("Le collage de ce texte est autorisé.");
			}
		});
		*/
	</script>	
	@endif

    <script>
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		  return new bootstrap.Tooltip(tooltipTriggerEl)
		})
	</script>

</body>
</html>
