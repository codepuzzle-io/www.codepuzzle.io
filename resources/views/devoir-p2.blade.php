<?php
$devoir = App\Models\Devoir::where('jeton', Session::get('jeton_devoir'))->first();
$devoir_eleve = App\Models\Devoir_eleve::where('jeton_copie', Session::get('jeton_copie'))->first();
if ($devoir_eleve->code_eleve == "") {
	$code_eleve = $devoir->code_eleve;
} else {
	$code_eleve = $devoir_eleve->code_eleve;
}
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

	<meta http-equiv="Cache-Control" content="no-cache, max-age=0, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />

    <script src="https://cdn.jsdelivr.net/pyodide/v0.24.1/full/pyodide.js"></script>

    <title>ENTRAÎNEMENT</title>

</head>

<body class="no-mathjax" oncontextmenu="return false" onselectstart="return false" ondragstart="return false">

    <!-- Écran de démarrage -->
    <div id="demarrer" class="demarrer">
		<div id="commencer" @if ($devoir->with_console == 1) style="display:none" @endif>
			<i class="fas fa-exclamation-triangle text-danger"></i>
			<br />
			<div class="text-monospace text-danger text-left" style="width:320px;margin:0px auto 0px auto;">
			&#8226; ne pas quitter le mode plein écran<br />
			&#8226; ne pas revenir en arrière<br />
			&#8226; ne pas quitter la page<br />
			&#8226; ne pas recharger la page<br />
			&#8226; ne pas cliquer en-dehors de la page
			</div>
			<br/>
			<button onclick="commencer()" type="button" class="btn btn-primary btn-lg text-monospace" style="width:80px;font-size:100%;"><i class="fas fa-check"></i></button>
		</div>
		@if ($devoir->with_console == 1)
		<button id="attendre" type="button" class="btn btn-primary btn-lg text-monospace" style="width:180px;" disabled><img src="{{ asset('img/chargement.gif') }}" width="30" /></button>
		@endif
    </div>

	<div class="bg-danger text-white p-2 text-monospace text-center mb-4">ne pas quitter cette page - ne pas recharger cette page - ne pas cliquer en-dehors de cette page - ne pas quitter le mode plein écran</div>

    <div class="container mt-5">

		<table align="center" cellpadding="2" style="text-align:center;margin-bottom:20px;color:#bdc3c7;border-spacing:5px;border-collapse:separate;">
			<tr>
				<td class="dashboard" @if ($devoir->with_chrono == 0) style="display:none" @endif><i class="fas fa-clock"></i>&nbsp;&nbsp;<span id="chrono">00:00</span></td>
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
							// Fonction pour encoder en base 64
							$encodeBase64 = function ($matches) {
								return '$$'.base64_encode($matches[1]).'$$';
							};
							// Fonction pour décoder de base 64
							$decodeBase64 = function ($matches) {
								return '$$'.base64_decode($matches[1]).'$$';
							};
							$consignes = preg_replace_callback('/\$\$(.*?)\$\$/s', $encodeBase64, $devoir->consignes_eleve);
							$Parsedown = new Parsedown();
							$Parsedown->setSafeMode(true);
							$consignes = $Parsedown->text($consignes);
							$consignes = preg_replace_callback('/\$\$(.*?)\$\$/s', $decodeBase64, $consignes);
							echo $consignes;
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
		        <div style="width:100%;margin:0px auto 0px auto;"><div id="editor_code" style="border-radius:5px;">{{$code_eleve}}</div></div>
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

    @include('inc-js-devoir-p2')

</body>
</html>
