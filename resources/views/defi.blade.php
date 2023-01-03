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
<html lang="{{ app()->getLocale() }}">
<head>
	@php
        $description = __('Générateur et gestionnaire de puzzles de Parsons') . ' | Défi - ' . strtoupper($jeton);
        $description_og = '| Défi - ' . strtoupper($jeton);
    @endphp
	@include('inc-meta')
    <script src="https://cdn.jsdelivr.net/pyodide/v0.21.3/full/pyodide.js"></script>
    <title>{{ config('app.name') }} | Défi - {{ $jeton }}</title>
</head>

<body oncontextmenu="return false" onselectstart="return false" ondragstart="return false">

    <div class="container">

		@if(!$iframe)
		<h1 class="mt-2 mb-5 text-center"><a class="navbar-brand m-1" href="{{ url('/') }}"><img src="{{ asset('img/codepuzzle.png') }}" height="20" alt="CODE PUZZLE" /></a></h1>
		@endif

		@if ($defi->with_chrono == 1 OR $defi->with_nbverif == 1)
		<table align="center" cellpadding="2" style="text-align:center;margin-bottom:20px;color:#bdc3c7;">
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
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="frame">
                    @if ($defi->titre_eleve !== NULL)
                        <div class="font-monospace small mb-1">{{ $defi->titre_eleve }}</div>
                    @endif
                    @if ($defi->consignes_eleve !== NULL)
                        <div class="font-monospace text-muted consignes">
                            <?php
                            $Parsedown = new Parsedown();
                            echo $Parsedown->text($defi->consignes_eleve);
                            ?>
                        </div>
                    @endif
                </div>
            </div>
        </div><!-- row -->
        @endif

    </div>

    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-md-6 offset-md-3 text-center" style="position:relative;height:30px;">
				<!-- bouton reinitialiser -->
				<a id="reinitialiser" href="#" style="position:absolute;left:25px;top:10px;" class="text-muted" data-bs-toggle="tooltip" data-bs-placement="top"  data-bs-trigger="hover" title="{{__('réinitialiser')}}"><i class="fas fa-sync-alt"></i></a>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6 offset-md-3 text-center">
                <textarea name="code" style="display:none;" id="code"></textarea>
		        <div style="width:100%;margin:0px auto 0px auto;"><div id="editor_code" style="border-radius:5px;">{{ old('code') }}</div></div>
                <!-- bouton verifier -->
                <button onclick="evaluatePython()" type="button" class="btn btn-primary btn-sm mt-2" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-trigger="hover" title="{{__('vérifier')}}" style="display:inline"><i class="fas fa-check"></i></button>
            </div>
        </div>
        
        <div class="row mt-5">
            <div class="col-md-4 offset-md-4 text-monospace small">
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

        <div class="row mt-3" @if(!$defi->with_console) style="display:none" @endif  >
            <div class="col-md-4 offset-md-4">
                <div>Console</div>
                <pre id="output" class="text-monospace p-2 small text-muted" style="border-radius:4px;border:1px solid silver"></pre>
            </div>
        </div>    
		  
    </div><!-- container -->

    @include('inc-bottom-js')

	<script src="{{ asset('js/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>
	<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>

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
		const output = document.getElementById("output");
		const code = document.getElementById("code");
		var nb_tentatives = 1;

		function addToOutput(s) {
			//output.innerText += ">>>" + code.value + "\n" + s + "\n";
			output.innerText = ""
			if (typeof(s) !== 'undefined'){
				output.innerText = s
			}
		}

		output.innerText = "Initializing...\n";
		// init Pyodide
		async function main() {
			let pyodide = await loadPyodide();
			output.innerText = "Ready!\n";
			return pyodide;
		}
		
		let pyodideReadyPromise = main();

		async function evaluatePython() {
			let pyodide = await pyodideReadyPromise;
			var asserts_tab = {!!$asserts!!};			
			var error_message = ""
			@if ($defi->with_nbverif == 1)
			document.getElementById('nb_tentatives').innerText = nb_tentatives++;
			@endif
			try {
				let output = pyodide.runPython(code.value);
				var n = 0;
				var ok = true;
				//console.log(asserts_tab);
				for (assert of asserts_tab){
					//console.log(assert[0]);
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
						@if($defi->with_message)
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
					}
					n++;			
				}
				
				if (ok) {
					bravo()
				} 	
			} catch (err) {
				// erreur python
				let message = err.message.split("File \"<exec>\", ");
				error_message = "Error " + message[1];
			}	
			addToOutput(error_message.trim());			
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