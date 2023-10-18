<?php
$devoir = App\Models\Devoir::where('jeton', Session::get('jeton_devoir'))->first();
$devoir_eleve = App\Models\Devoir_eleve::where('jeton_copie', Session::get('jeton_copie'))->first();

$tests = unserialize($devoir->tests);
$asserts = '';
foreach($tests as $test){
	$asserts .=  '["assert '.$test[0].'", "'.addslashes($test[1]).'"],';
}
$asserts = '[' . trim($asserts, ',') . ']';
?>
@include('inc-top')
<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>

	<script>
		// Événement lorsque l'utilisateur quitte la page
		window.addEventListener('blur', function() {
			//window.location.replace("/devoir");
		});
	</script>

	<link rel="stylesheet" href="https://pyscript.net/latest/pyscript.css" />
	<script defer src="https://pyscript.net/latest/pyscript.js"></script>

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
    <script src="https://cdn.jsdelivr.net/pyodide/v0.21.3/full/pyodide.js"></script>
    <title>DEVOIR</title>
</head>

<body class="no-mathjax" oncontextmenu="return false" onselectstart="return false" ondragstart="return false">

	<div class="bg-danger text-white p-2 text-monospace text-center mb-4">ne pas quitter cette page - ne pas recharger cette page - ne pas cliquer en-dehors de cette page</div>

    <div class="container">

		<div class="row">
            <div class="col-md-8 offset-md-2">
				<div class="p-2 text-monospace text-right mb-4"><button type="button" class="btn btn-success">rendre ce devoir</button></div>
			</div>
		</div>

		<py-repl std-out="output" std-err="errors"></py-repl>

		@if ($devoir->with_chrono == 1 OR $devoir->with_nbverif == 1)
		<table align="center" cellpadding="2" style="text-align:center;margin-bottom:20px;color:#bdc3c7;">
			<tr>
				@if ($devoir->with_chrono == 1)
				<td><i class="fas fa-clock"></i></td>
				@endif
				@if ($devoir->with_nbverif == 1)
				<td><i class="fas fa-check"></i></td>
				@endif
			</tr>
			<tr>
				@if ($devoir->with_chrono == 1)
				<td><span id="chrono" class="dashboard">00:00</span></td>
				@endif
				@if ($devoir->with_nbverif == 1)
				<td><span id="nbverif" class="dashboard">{{ $devoir_eleve->nbverif }}</span></td>
				@endif
			</tr>
		</table>
		@endif

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
		        <div style="width:100%;margin:0px auto 0px auto;"><div id="editor_code" style="border-radius:5px;">{{$devoir->code}}</div></div>
                <!-- bouton verifier -->
                <button onclick="evaluatePython()" type="button" class="btn btn-primary btn-sm mt-2 text-monospace" style="display:inline">exécuter le code</button>
            </div>
        </div>
        
        <div class="row mt-5">
            <div class="col-md-6 offset-md-3 text-monospace small">
				<table style="width:100%">
                @foreach($tests AS $test)
				<tr>
				<td class="text-center" style="vertical-align:top"><div id="test_{{$loop->index}}" class="test"><i class="fas fa-question-circle"></i></div></td>
				<td style="width:100%">
					<div id="test_message_{{$loop->index}}" class="test">
						<div>Test {{$loop->index + 1}}</div>
					</div>
				</td>
				</tr>
                @endforeach
				</table>
            </div>
        </div>

        <div class="row mt-3" @if(!$devoir->with_console) style="display:none" @endif  >
            <div class="col-md-6 offset-md-3">
                <div>Console</div>
                <pre id="console" class="bg-dark text-monospace p-3 small text-white" style="border-radius:4px;border:1px solid silver"></pre>
            </div>
        </div>    
		  
    </div><!-- container -->

    @include('inc-bottom-js')

	<script src="{{ asset('js/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>
	<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>	

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
    <script type="text/javascript" id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-svg.js">
    </script> 	

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

    <script>
		const code = document.getElementById("code");
		var nbverif = {{ $devoir_eleve->nbverif }};

		function addToOutput(s) {
			//output.innerText += ">>>" + code.value + "\n" + s + "\n";
			document.getElementById("console").innerText = ""
			if (typeof(s) !== 'undefined'){
				document.getElementById("console").innerText = s
			}
		}

		document.getElementById("console").innerText = "Initialisation...\n";

		// init Pyodide
		async function main() {
			let pyodide = await loadPyodide();
			document.getElementById("console").innerText = "Prêt!\n";
			return pyodide;
		}
		
		let pyodideReadyPromise = main();

		async function evaluatePython() {
			console.log('EVALUATE PYTHON')
			let pyodide = await pyodideReadyPromise;
			var asserts_tab = {!!$asserts!!};			
			var error_message = ""
			nbverif++;
			document.getElementById('nbverif').innerText = nbverif;
			try {
				console.log('--');
				let output = pyodide.runPython(code.value);
				console.log('--');
				console.log('code: --'+ code.value+'--');
				console.log('output: '+ output);
				addToOutput(output);
				
				if (asserts_tab.length !== 0) {
					// ASSERTS
					var n = 0;
					var ok = true;
					for (assert of asserts_tab){
						try {
							// pas d'erreur python
							// assert valide
							let output = pyodide.runPython(code.value + "\n" + assert[0] + ', "' + assert[1] + '"');	
							document.getElementById('test_'+n).innerHTML = '<i class="fas fa-check-circle"></i>';
							document.getElementById('test_message_'+n).innerHTML = 'Test validé!';
							document.getElementById('test_'+n).className = "test_success";
						} catch (err) {
							// pas d'erreur python
							// assert non valide
							var test_message = "Test non validé :-/";
							@if($devoir->with_message)
							var test_message = (assert[1]) ? assert[1] : "&nbsp;";
							@endif						
							//console.log(code.value + "\n" + assert[0]);
							//console.log(err.message);
							document.getElementById('test_'+n).innerHTML = '<i class="fas fa-times-circle"></i>';
							document.getElementById('test_message_'+n).innerHTML = test_message;
							document.getElementById('test_'+n).className = "test_failed";
							let message = err.message.split("<module>\n");
							test_nb = n+1;
							error_message += "Test "+ test_nb + "\n" + message[1] + "\n";
							ok = false;
							addToOutput(error_message.trim());
						}
						n++;			
					}

					if (ok) {
						addToOutput("Code correct et tests validés. Bravo!");
						//bravo();
					} 
						
				}
				
			} catch (err) {
				// erreur python
				let message = err.message.split("File \"<exec>\", ");
				error_message = "Error " + message[1];
				addToOutput(error_message);
			}			
						
		}
	</script>

	<script>
		// autosave
		setInterval(function() {
			var formData = new URLSearchParams();
			formData.append('code', encodeURIComponent(document.getElementById('code').value));
			formData.append('chrono', count);
			formData.append('nbverif', nbverif);
			formData.append('jeton_copie', '{{ Session::get('jeton_copie') }}');

			fetch('devoir-autosave', {
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
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		  return new bootstrap.Tooltip(tooltipTriggerEl)
		})
	</script>

</body>
</html>