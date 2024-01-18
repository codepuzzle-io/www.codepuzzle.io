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
	<link href="{{ asset('lib/fontawesome/css/all.min.css') }}" rel="stylesheet">

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

    <title>ENTRAÎNEMENT / DEVOIR</title>

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
    </div>
	

	<div class="bg-danger text-white p-2 text-monospace text-center mb-4">ne pas quitter cette page - ne pas recharger cette page - ne pas cliquer en-dehors de cette page - ne pas quitter le mode plein écran</div>

    <div class="container mt-5">

		<table align="center" cellpadding="2" style="text-align:center;color:#bdc3c7;border-spacing:5px;border-collapse:separate;">
			<tr>
				<td class="dashboard" @if ($devoir->with_chrono == 0) style="display:none" @endif><i class="fas fa-clock"></i>&nbsp;&nbsp;<span id="chrono">00:00</span></td>
				<td class="m-0 p-0">
  					<button class="btn btn-success text-monospace" type="button" data-toggle="collapse" data-target="#collapseRendre" aria-expanded="false" aria-controls="collapseRendre">rendre</button>
				</td>
			</tr>
		</table>

		<div class="collapse text-center" id="collapseRendre">
			<div class="mt-3">
				<button type="button" id='rendre' class='btn btn-danger btn-sm text-white mr-1' role='button'>{{__('confirmer')}}</button>
				<button type="button" class="btn btn-light btn-sm ml-1" data-toggle="collapse" data-target="#collapseRendre">annuler</button>
			</div>
		</div>

        @if ($devoir->titre_eleve !== NULL OR $devoir->consignes_eleve !== NULL)
        <div class="row mt-5">
            <div class="col-md-10 offset-md-1">
                <div class="frame">
                    @if ($devoir->titre_eleve !== NULL)
                        <div class="font-monospace small mb-1">{{ $devoir->titre_eleve }}</div>
                    @endif
                    @if ($devoir->consignes_eleve !== NULL)
                        <div class="text-monospace text-muted consignes mathjax" style="text-align:justify;">
							<?php
							include('lib/parsedownmath/ParsedownMath.php');
							$Parsedown = new ParsedownMath([
								'math' => [
									'enabled' => true, // Write true to enable the module
									'matchSingleDollar' => true // default false
								]
							]);
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
            <div class="col-md-10 offset-md-1 text-center">
		        <div style="width:100%;margin:0px auto 8px auto;"><div id="editor_code" style="border-radius:5px;">{{$code_eleve}}</div></div>

				<!-- boutons run / stop / restart -->
				@if ($devoir->with_console == 1)

					<div class="row" style="min-height:40px;">
						<div class="col-md-6 text-left">
							<button id="run" type="button" class="btn btn-primary btn-sm pl-4 pr-4"><i class="fas fa-circle-notch fa-spin"></i></button>
							<button id="stop" type="button" class="btn btn-dark btn-sm pl-3 pr-3" style="padding-top:6px;display:none;" data-bs-toggle="tooltip" data-bs-placement="right"  data-bs-trigger="hover" title="{{__('Interruption de l\'exécution du code (en cas de boucle infinie ou de traitement trop long). L\'arrêt peut prendre quelques secondes.')}}"><i class="fas fa-stop"></i></button>
						</div>
						<div class="col-md-6 text-right">
							<button id="restart" type="button" class="btn btn-warning btn-sm pl-3 pr-3" style="padding-top:6px;display:none;" data-bs-toggle="tooltip" data-bs-placement="right"  data-bs-trigger="hover" title="{{__('Si le bouton d\'arrêt ne permet pas d\'interrompre  l\'exécution du code, cliquer ici. Python redémarrera complètement mais votre code sera conservé dans l\'éditeur. Le redémarrage peut prendre quelques secondes.')}}"><i class="fas fa-skull"></i></button>
						</div>
					</div>
				@endif
            </div>
        </div>
        
        <div class="row mt-3 pb-5" @if($devoir->with_console == 0) style="display:none" @endif  >

			<div class="col-md-10 offset-md-1">
				<div>Console</div>
                <pre id="output" class="text-monospace p-3 text-white bg-dark" style="border-radius:4px;border:1px solid silver;min-height:150px;"></pre>
            </div>
        </div>  
		  
    </div><!-- container -->

    @include('inc-js-devoir-p2')

</body>
</html>
