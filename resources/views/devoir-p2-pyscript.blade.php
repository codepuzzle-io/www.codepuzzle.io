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

	<link rel="stylesheet" href="https://pyscript.net/latest/pyscript.css"  />
    <script defer src="https://pyscript.net/latest/pyscript.js"></script>
    <link rel="stylesheet" href="./assets/css/examples.css" />

	@include('inc-matomo')
 
    <title>DEVOIR</title>
	<style>
		.py-repl-output {
			background-color:#343A40;
			color:white;
			padding:10px 20px 10px 20px;
			font-family: monospace;
			text-align:left;
			margin-top:20px;
			border-radius:4px;
			min-height:80px;
		}
		.py-terminal {
			/*display:none;*/
		}
		.py-error {
			background-color:#343A40;
			border:none;
			color:white;
		}
	</style>
</head>

<!--<body class="no-mathjax" oncontextmenu="return false" onselectstart="return false" ondragstart="return false">-->
<body class="no-mathjax">

	<div class="bg-danger text-white p-2 text-monospace text-center mb-4">ne pas quitter cette page - ne pas recharger cette page - ne pas cliquer en-dehors de cette page</div>

    <div class="container">

		<div class="row">
            <div class="col-md-8 offset-md-2">
				<div class="p-2 text-monospace text-right mb-4"><button type="button" class="btn btn-success">rendre ce devoir</button></div>
			</div>
		</div>

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
			
                <py-config>
                    packages = [
                      "matplotlib",
					  "numpy"
                    ]
                </py-config> 
                <py-repl></py-repl>
            </div>
			
        </div>
		  
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
    <script type="text/javascript" id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-svg.js"></script> 	

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
		}, 1000000);
	</script>	

    <script>
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		  return new bootstrap.Tooltip(tooltipTriggerEl)
		})
	</script>

</body>
</html>
