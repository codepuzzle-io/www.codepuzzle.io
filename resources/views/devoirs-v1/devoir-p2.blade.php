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


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/default.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
    <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
    <style>
        .cellule {
            position: relative;
            margin: 5px 0px 5px 0px;
        }
        .cellule_content {
            position: relative;
            padding: 8px;
            border: 1px solid #CED4DA;
            border-radius:4px;
            background-color:white;
            overflow: hidden;
            resize: none;
        }
        .cellule_marked {
            background-color:#fafafa;
        }
        .cellule_type {
            position:absolute;
            top:3px;
            left:8px;
        }
        .control {
            position:absolute;
            bottom:0;
            right:3px;
        }
        .control_bouton {
            display:inline-block;
            width:20px;
            text-align:center;
            cursor:pointer;
            border-radius:2px;
            opacity:0.2;
        }
        .control_bouton:hover {
            background-color:#e2e6ea;
            opacity:0.8;
        }

        .ace_editor {
            border-radius:4px;    
        }


        .markedarea_icon {
            position: absolute;
            left:0;
            top:0;
            width:100%;
            height:100%;
            z-index:1000;         
        }

        .markedarea_icon i {
            position: absolute;
            top: 50%;
            left: 50%;
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            display:none;
        }

        .markedarea_icon:hover {
            cursor:text;
        }

        .markedarea_icon:hover i {
            display:inline;
        }

        .cellule_marked pre {
            display:block;
            border:solid 1px #CED4DA;
            background-color:#F8FAFC;
            border-radius:4px;
        }

    </style>

    <style>
        html,body {
          height: 100%;
        }
        .grid {
            display: grid;
            grid-template-columns: 1fr 10px 1fr;
            height:100%;
			border:solid 0px silver;
			margin:0px;
			border-radius:0px;
        }
        .gutter-col {
            grid-row: 1/-1;
            cursor: col-resize;
            background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAeCAYAAADkftS9AAAAIklEQVQoU2M4c+bMfxAGAgYYmwGrIIiDjrELjpo5aiZeMwF+yNnOs5KSvgAAAABJRU5ErkJggg==');
            background-color: rgb(229, 231, 235);
            background-repeat: no-repeat;
            background-position: 50%;
        }
        .gutter-col-1 {
            grid-column: 2;
        }
        .video {
          aspect-ratio: 16 / 9;
          width: 100%;
        }
    </style>

    <title>ENTRAÎNEMENT / DEVOIR</title>

</head>

<body class="no-mathjax" oncontextmenu="return false" onselectstart="return false" ondragstart="return false">

	<!-- Écran de démarrage -->
    <div id="demarrer" class="demarrer">
		<div id="commencer">
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
	
	<div class="bg-danger text-white p-2 text-monospace text-center mb-4" style="position:fixed;top:0;left:0;width:100%;">ne pas quitter cette page - ne pas recharger cette page - ne pas cliquer en-dehors de cette page - ne pas quitter le mode plein écran</div>

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

    </div>

    <div class="container">

		@if ($devoir->sujet)

			<div class="grid">
				<div style="overflow-y:hidden;">
					<div id="gauche" style="height:100%;position:relative;">
						<h1 id="entete" class="mt-2 mb-2 text-center"><a class="navbar-brand m-1" href="{{ url('/') }}"><img src="{{ asset('img/codepuzzle.png') }}" height="25" alt="CODE PUZZLE" /></a></h1>
						<?php
						$sujet = App\Models\Sujet::where('jeton', $devoir->sujet)->first();
						?>
						@if ($sujet->sujet_type == 'pdf')
							<iframe id="sujet" src="{{Storage::url('SUJETS/PDF/'.$sujet->jeton.'.pdf')}}" width="100%" style="border: none;"></iframe>
						@endif
					</div>
				</div>

				<div id="poignee" class="gutter-col gutter-col-1"></div>

				<div style="overflow-y:hidden;position:relative">
					<div id="droite" class="p-4" style="width:100%;height:100%;overflow-y:scroll;">

						<div class="mb-4">
							<button type="button" class="btn btn-dark btn-sm text-monospace" onclick="ajouterDiv(null, 'bas', 'text')">
								<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 16" fill="none"><g xmlns="http://www.w3.org/2000/svg"><path d="M9.25 10.0693L7.375 10.0693L7.375 8.19434C7.375 7.98809 7.20625 7.81934 7 7.81934C6.79375 7.81934 6.625 7.98809 6.625 8.19434L6.625 10.0693L4.75 10.0693C4.54375 10.0693 4.375 10.2381 4.375 10.4443C4.375 10.6506 4.54375 10.8193 4.75 10.8193L6.625 10.8193L6.625 12.6943C6.625 12.9006 6.79375 13.0693 7 13.0693C7.20625 13.0693 7.375 12.9006 7.375 12.6943L7.375 10.8193L9.25 10.8193C9.45625 10.8193 9.625 10.6506 9.625 10.4443C9.625 10.2381 9.45625 10.0693 9.25 10.0693Z" fill="#ffffff" stroke="#ffffff" stroke-width="0.7"></path></g><path fill-rule="evenodd" clip-rule="evenodd" d="M2.5 5.5L2.5 3.5L11.5 3.5L11.5 5.5L2.5 5.5ZM2 7C1.44772 7 1 6.55228 1 6L1 3C1 2.44772 1.44772 2 2 2L12 2C12.5523 2 13 2.44772 13 3L13 6C13 6.55229 12.5523 7 12 7L2 7Z" fill="#ffffff"></path></svg> texte
							</button>
							<button type="button" class="btn btn-dark btn-sm text-monospace" onclick="ajouterDiv(null, 'bas', 'code')"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><g xmlns="http://www.w3.org/2000/svg"><path d="M9.25 10.0693L7.375 10.0693L7.375 8.19434C7.375 7.98809 7.20625 7.81934 7 7.81934C6.79375 7.81934 6.625 7.98809 6.625 8.19434L6.625 10.0693L4.75 10.0693C4.54375 10.0693 4.375 10.2381 4.375 10.4443C4.375 10.6506 4.54375 10.8193 4.75 10.8193L6.625 10.8193L6.625 12.6943C6.625 12.9006 6.79375 13.0693 7 13.0693C7.20625 13.0693 7.375 12.9006 7.375 12.6943L7.375 10.8193L9.25 10.8193C9.45625 10.8193 9.625 10.6506 9.625 10.4443C9.625 10.2381 9.45625 10.0693 9.25 10.0693Z" fill="#ffffff" stroke="#ffffff" stroke-width="0.7"></path></g><path d="M2.5 5.5L2.5 3.5L11.5 3.5L11.5 5.5L2.5 5.5ZM2 7C1.44772 7 1 6.55228 1 6L1 3C1 2.44772 1.44772 2 2 2L12 2C12.5523 2 13 2.44772 13 3L13 6C13 6.55229 12.5523 7 12 7L2 7Z" fill="#ffffff"></path></svg> code</button>

							<div style="float:right">
								<a id="notebook_download" class="btn btn-outline-secondary btn-sm text-monospace" href="" role="button"><i class="fas fa-file-download"></i> notebook</a>
							</div>
						</div>

						<div id="mainContainer">

							<div class="cellule" id="div_1">

								<div style="position:relative;padding-bottom:25px;">
									<div class="control">

										<div class="control_bouton" onclick="deplacer('haut', 'div_1')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M1.52899 6.47101C1.23684 6.76316 1.23684 7.23684 1.52899 7.52899V7.52899C1.82095 7.82095 2.29426 7.82116 2.58649 7.52946L6.25 3.8725V12.25C6.25 12.6642 6.58579 13 7 13V13C7.41421 13 7.75 12.6642 7.75 12.25V3.8725L11.4027 7.53178C11.6966 7.82619 12.1736 7.82641 12.4677 7.53226V7.53226C12.7617 7.2383 12.7617 6.7617 12.4677 6.46774L7.70711 1.70711C7.31658 1.31658 6.68342 1.31658 6.29289 1.70711L1.52899 6.47101Z" fill="#000000"></path></svg></div>

										<div class="control_bouton" onclick="deplacer('bas', 'div_1')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M12.471 7.52899C12.7632 7.23684 12.7632 6.76316 12.471 6.47101V6.47101C12.179 6.17905 11.7057 6.17884 11.4135 6.47054L7.75 10.1275V1.75C7.75 1.33579 7.41421 1 7 1V1C6.58579 1 6.25 1.33579 6.25 1.75V10.1275L2.59726 6.46822C2.30338 6.17381 1.82641 6.17359 1.53226 6.46774V6.46774C1.2383 6.7617 1.2383 7.2383 1.53226 7.53226L6.29289 12.2929C6.68342 12.6834 7.31658 12.6834 7.70711 12.2929L12.471 7.52899Z" fill="#000000"></path></svg></div>

										<div class="control_bouton" onclick="ajouterDiv('div_1', 'haut', 'text')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><g xmlns="http://www.w3.org/2000/svg"><path d="M4.75 4.93066H6.625V6.80566C6.625 7.01191 6.79375 7.18066 7 7.18066C7.20625 7.18066 7.375 7.01191 7.375 6.80566V4.93066H9.25C9.45625 4.93066 9.625 4.76191 9.625 4.55566C9.625 4.34941 9.45625 4.18066 9.25 4.18066H7.375V2.30566C7.375 2.09941 7.20625 1.93066 7 1.93066C6.79375 1.93066 6.625 2.09941 6.625 2.30566V4.18066H4.75C4.54375 4.18066 4.375 4.34941 4.375 4.55566C4.375 4.76191 4.54375 4.93066 4.75 4.93066Z" fill="#000000" stroke="#000000" stroke-width="0.7"></path></g><path fill-rule="evenodd" clip-rule="evenodd" d="M11.5 9.5V11.5L2.5 11.5V9.5L11.5 9.5ZM12 8C12.5523 8 13 8.44772 13 9V12C13 12.5523 12.5523 13 12 13L2 13C1.44772 13 1 12.5523 1 12V9C1 8.44772 1.44771 8 2 8L12 8Z" fill="#000000"></path></svg></div>

										<div class="control_bouton" onclick="ajouterDiv('div_1', 'bas', 'text')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><g xmlns="http://www.w3.org/2000/svg"><path d="M9.25 10.0693L7.375 10.0693L7.375 8.19434C7.375 7.98809 7.20625 7.81934 7 7.81934C6.79375 7.81934 6.625 7.98809 6.625 8.19434L6.625 10.0693L4.75 10.0693C4.54375 10.0693 4.375 10.2381 4.375 10.4443C4.375 10.6506 4.54375 10.8193 4.75 10.8193L6.625 10.8193L6.625 12.6943C6.625 12.9006 6.79375 13.0693 7 13.0693C7.20625 13.0693 7.375 12.9006 7.375 12.6943L7.375 10.8193L9.25 10.8193C9.45625 10.8193 9.625 10.6506 9.625 10.4443C9.625 10.2381 9.45625 10.0693 9.25 10.0693Z" fill="#000000" stroke="#000000" stroke-width="0.7"></path></g><path fill-rule="evenodd" clip-rule="evenodd" d="M2.5 5.5L2.5 3.5L11.5 3.5L11.5 5.5L2.5 5.5ZM2 7C1.44772 7 1 6.55228 1 6L1 3C1 2.44772 1.44772 2 2 2L12 2C12.5523 2 13 2.44772 13 3L13 6C13 6.55229 12.5523 7 12 7L2 7Z" fill="#000000"></path></svg></div>

										<div class="control_bouton" onclick="ajouterDiv('div_1', 'haut', 'code')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><g xmlns="http://www.w3.org/2000/svg"><path d="M4.75 4.93066H6.625V6.80566C6.625 7.01191 6.79375 7.18066 7 7.18066C7.20625 7.18066 7.375 7.01191 7.375 6.80566V4.93066H9.25C9.45625 4.93066 9.625 4.76191 9.625 4.55566C9.625 4.34941 9.45625 4.18066 9.25 4.18066H7.375V2.30566C7.375 2.09941 7.20625 1.93066 7 1.93066C6.79375 1.93066 6.625 2.09941 6.625 2.30566V4.18066H4.75C4.54375 4.18066 4.375 4.34941 4.375 4.55566C4.375 4.76191 4.54375 4.93066 4.75 4.93066Z" fill="#000000" stroke="#000000" stroke-width="0.7"></path></g><path xmlns="http://www.w3.org/2000/svg" d="M11.5 9.5V11.5L2.5 11.5V9.5L11.5 9.5ZM12 8C12.5523 8 13 8.44772 13 9V12C13 12.5523 12.5523 13 12 13L2 13C1.44772 13 1 12.5523 1 12V9C1 8.44772 1.44771 8 2 8L12 8Z" fill="#000000"></path></svg></div>

										<div class="control_bouton" onclick="ajouterDiv('div_1', 'bas', 'code')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><g xmlns="http://www.w3.org/2000/svg"><path d="M9.25 10.0693L7.375 10.0693L7.375 8.19434C7.375 7.98809 7.20625 7.81934 7 7.81934C6.79375 7.81934 6.625 7.98809 6.625 8.19434L6.625 10.0693L4.75 10.0693C4.54375 10.0693 4.375 10.2381 4.375 10.4443C4.375 10.6506 4.54375 10.8193 4.75 10.8193L6.625 10.8193L6.625 12.6943C6.625 12.9006 6.79375 13.0693 7 13.0693C7.20625 13.0693 7.375 12.9006 7.375 12.6943L7.375 10.8193L9.25 10.8193C9.45625 10.8193 9.625 10.6506 9.625 10.4443C9.625 10.2381 9.45625 10.0693 9.25 10.0693Z" fill="#000000" stroke="#000000" stroke-width="0.7"></path></g><path d="M2.5 5.5L2.5 3.5L11.5 3.5L11.5 5.5L2.5 5.5ZM2 7C1.44772 7 1 6.55228 1 6L1 3C1 2.44772 1.44772 2 2 2L12 2C12.5523 2 13 2.44772 13 3L13 6C13 6.55229 12.5523 7 12 7L2 7Z" fill="#000000"></path></svg></div>

										<div class="control_bouton" onclick="supprimerDiv('div_1')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16px" height="16px"><path d="M0 0h24v24H0z" fill="none"></path><path fill="#000000" d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"></path></svg></div>
									</div>

									<textarea id="textarea_1" class="form-control cellule_content exclure" oninput="textarea_autosize(this)" row="2" style="height: 60px;"></textarea>
									<div id="markedarea_1" class="cellule_content exclure cellule_marked hover-edit" style="position:relative;display:none;min-height:60px;"><div class="markedarea_icon" onclick="edit('1')"><i class="fas fa-pen-square fa-lg"></i></div><div id="markedarea_content_1"></div></div>
								</div>
								<div class="text-center text-muted mb-3"><i class="fas fa-ellipsis-h"></i></div>
							</div>

						</div>
			
					</div>
				</div>
			</div>

		@else

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

		@endif
		  
    </div><!-- container -->

    @include('inc-js-devoir-p2')


    <script src="https://cdn.jsdelivr.net/npm/dompurify/dist/purify.min.js"></script>

    <script>
        MathJax = {
        tex: {
            inlineMath: [["$","$"]], 
            displayMath: [["$$","$$"]], 
        },
        svg: {
            fontCache: 'global'
        }
        };
    </script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/python.min.js"></script>

    <script src="{{ asset('lib/split-grid/split-grid.js') }}" type="text/javascript" charset="utf-8"></script>
    <script>
	    Split({
	        minSize: 300,
	        columnGutters: [{
	            track: 1,
	            element: document.querySelector('.gutter-col-1'),
	        }],
	    })
    </script>

    <script>
    function hauteur_iframe() {
        var div1 = document.getElementById('entete');
        var div2 = document.getElementById('sujet');
        var conteneur = document.getElementById('gauche');

        var hauteurDiv1 = div1.offsetHeight; // Obtient la hauteur du premier div
        var hauteurConteneur = conteneur.offsetHeight; // Hauteur totale du conteneur

        div2.style.top = hauteurDiv1 + 'px'; // Positionne le deuxième div juste en dessous du premier
        div2.style.height = (hauteurConteneur - hauteurDiv1) + 'px'; // Ajuste la hauteur pour remplir l'espace restant
    }

    // Ajuster les divs au chargement de la page
    window.onload = hauteur_iframe;

    // Ajuster les divs lors du redimensionnement de la fenêtre
    window.onresize = hauteur_iframe;


    </script>

    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script>
        // pour limiter les options
        // voir https://github.com/Ionaru/easy-markdown-editor/issues/245
        marked.use({
            tokenizer: {
                //space() { return undefined },
                //code() { return undefined },
                //fences() { return undefined },
                heading() { return undefined },
                //hr() { return undefined },
                blockquote() { return undefined },
                //list() { return undefined },
                html() { return undefined },
                //def() { return undefined },
                table() { return undefined },
                lheading() { return undefined },
                //paragraph() { return undefined },
                //text() { return undefined },
                //escape() { return undefined },
                tag() { return undefined },
                link() { return undefined },
                reflink() { return undefined },
                //emStrong() { return undefined },
                //codespan() { return undefined },
                //br() { return undefined },
                del() { return undefined },
                autolink() { return undefined },
                url() { return undefined },
                //inlineText() { return undefined },              
            },
/*
            walkTokens(token) {
                const { type, raw } = token;
                // Modify paragraph blocks beginning and ending with $$.
                if (type === 'paragraph' && raw.startsWith('$$\n') && raw.endsWith('\n$$')) {
                    token.type = 'code';
                    token.lang = 'mathematics';
                    //token.text = token.raw.slice(3, -3); // Remove the $$ boundaries.
                    token.tokens.length = 0; // Remove child tokens.
                }
            },
            
            renderer: {
                code(code, language) {
                    // Use custom mathematics renderer.
                    if (language === 'mathematics') {
                        return code;
                    }
                    // Use default code renderer.
                    return false;
                },

            }   
            */         
        })     
    </script>
    <script src="{{ asset('js/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>
    <script>

        var editor_code = [];
        let div_id = 1;

        function md_render(id) {
            var textarea = document.getElementById('textarea_'+id);
            var markedarea = document.getElementById('markedarea_'+id)
            var markedarea_content = document.getElementById('markedarea_content_'+id)
            textarea.style.display = 'none';
            markedarea.style.display = 'block';
            
            // Expression régulière pour trouver les blocs entre $$
            var regex = /\$\$(.+?)\$\$/gs;

            // Fonction pour remplacer les doubles slashes par des triples dans chaque bloc
            var texte = textarea.value.replace(regex, function(match) {
                // Remplacer les doubles slashes par des triples dans le bloc
                return match.replace(/\\\\/g, '\\\\\\\\');
            });

            markedarea_content.innerHTML = DOMPurify.sanitize(marked.parse(texte));
            save_cellules();
        }

        function edit(id) {
            var divsExclus = document.querySelectorAll('.exclure');
            for (var i = 0; i < divsExclus.length; i++) {
                ids = divsExclus[i].id.split("_")[1];
                md_render(ids)
            }
            document.getElementById('markedarea_'+id).style.display = 'none';
            document.getElementById('textarea_'+id).style.display = 'block';
            document.getElementById('textarea_'+id).focus();
        }

        function textarea_autosize(textarea) {
            textarea.style.height = 'auto';
            textarea.style.height = 2 + textarea.scrollHeight + 'px';
        }

        function ajouterDiv(referenceDivId = null, position = 'bas', type) {
            div_id++;
            const div = document.createElement('div');
            div.className = 'cellule';
            div.id = 'div_'+div_id;
            var div_content = `<div style="position:relative;padding-bottom:25px;">`;
            div_content += `<div class="control">

                <div class="control_bouton" onclick="deplacer('haut', '${div.id}')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M1.52899 6.47101C1.23684 6.76316 1.23684 7.23684 1.52899 7.52899V7.52899C1.82095 7.82095 2.29426 7.82116 2.58649 7.52946L6.25 3.8725V12.25C6.25 12.6642 6.58579 13 7 13V13C7.41421 13 7.75 12.6642 7.75 12.25V3.8725L11.4027 7.53178C11.6966 7.82619 12.1736 7.82641 12.4677 7.53226V7.53226C12.7617 7.2383 12.7617 6.7617 12.4677 6.46774L7.70711 1.70711C7.31658 1.31658 6.68342 1.31658 6.29289 1.70711L1.52899 6.47101Z" fill="#000000"></path></svg></div>

                <div class="control_bouton" onclick="deplacer('bas', '${div.id}')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M12.471 7.52899C12.7632 7.23684 12.7632 6.76316 12.471 6.47101V6.47101C12.179 6.17905 11.7057 6.17884 11.4135 6.47054L7.75 10.1275V1.75C7.75 1.33579 7.41421 1 7 1V1C6.58579 1 6.25 1.33579 6.25 1.75V10.1275L2.59726 6.46822C2.30338 6.17381 1.82641 6.17359 1.53226 6.46774V6.46774C1.2383 6.7617 1.2383 7.2383 1.53226 7.53226L6.29289 12.2929C6.68342 12.6834 7.31658 12.6834 7.70711 12.2929L12.471 7.52899Z" fill="#000000"></path></svg></div>

                <div class="control_bouton" onclick="ajouterDiv('${div.id}', 'haut', 'text')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><g xmlns="http://www.w3.org/2000/svg"><path d="M4.75 4.93066H6.625V6.80566C6.625 7.01191 6.79375 7.18066 7 7.18066C7.20625 7.18066 7.375 7.01191 7.375 6.80566V4.93066H9.25C9.45625 4.93066 9.625 4.76191 9.625 4.55566C9.625 4.34941 9.45625 4.18066 9.25 4.18066H7.375V2.30566C7.375 2.09941 7.20625 1.93066 7 1.93066C6.79375 1.93066 6.625 2.09941 6.625 2.30566V4.18066H4.75C4.54375 4.18066 4.375 4.34941 4.375 4.55566C4.375 4.76191 4.54375 4.93066 4.75 4.93066Z" fill="#000000" stroke="#000000" stroke-width="0.7"></path></g><path fill-rule="evenodd" clip-rule="evenodd" d="M11.5 9.5V11.5L2.5 11.5V9.5L11.5 9.5ZM12 8C12.5523 8 13 8.44772 13 9V12C13 12.5523 12.5523 13 12 13L2 13C1.44772 13 1 12.5523 1 12V9C1 8.44772 1.44771 8 2 8L12 8Z" fill="#000000"></path></svg></div>

                <div class="control_bouton" onclick="ajouterDiv('${div.id}', 'bas', 'text')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><g xmlns="http://www.w3.org/2000/svg"><path d="M9.25 10.0693L7.375 10.0693L7.375 8.19434C7.375 7.98809 7.20625 7.81934 7 7.81934C6.79375 7.81934 6.625 7.98809 6.625 8.19434L6.625 10.0693L4.75 10.0693C4.54375 10.0693 4.375 10.2381 4.375 10.4443C4.375 10.6506 4.54375 10.8193 4.75 10.8193L6.625 10.8193L6.625 12.6943C6.625 12.9006 6.79375 13.0693 7 13.0693C7.20625 13.0693 7.375 12.9006 7.375 12.6943L7.375 10.8193L9.25 10.8193C9.45625 10.8193 9.625 10.6506 9.625 10.4443C9.625 10.2381 9.45625 10.0693 9.25 10.0693Z" fill="#000000" stroke="#000000" stroke-width="0.7"></path></g><path fill-rule="evenodd" clip-rule="evenodd" d="M2.5 5.5L2.5 3.5L11.5 3.5L11.5 5.5L2.5 5.5ZM2 7C1.44772 7 1 6.55228 1 6L1 3C1 2.44772 1.44772 2 2 2L12 2C12.5523 2 13 2.44772 13 3L13 6C13 6.55229 12.5523 7 12 7L2 7Z" fill="#000000"></path></svg></div>

                <div class="control_bouton" onclick="ajouterDiv('${div.id}', 'haut', 'code')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><g xmlns="http://www.w3.org/2000/svg"><path d="M4.75 4.93066H6.625V6.80566C6.625 7.01191 6.79375 7.18066 7 7.18066C7.20625 7.18066 7.375 7.01191 7.375 6.80566V4.93066H9.25C9.45625 4.93066 9.625 4.76191 9.625 4.55566C9.625 4.34941 9.45625 4.18066 9.25 4.18066H7.375V2.30566C7.375 2.09941 7.20625 1.93066 7 1.93066C6.79375 1.93066 6.625 2.09941 6.625 2.30566V4.18066H4.75C4.54375 4.18066 4.375 4.34941 4.375 4.55566C4.375 4.76191 4.54375 4.93066 4.75 4.93066Z" fill="#000000" stroke="#000000" stroke-width="0.7"></path></g><path xmlns="http://www.w3.org/2000/svg" d="M11.5 9.5V11.5L2.5 11.5V9.5L11.5 9.5ZM12 8C12.5523 8 13 8.44772 13 9V12C13 12.5523 12.5523 13 12 13L2 13C1.44772 13 1 12.5523 1 12V9C1 8.44772 1.44771 8 2 8L12 8Z" fill="#000000"></path></svg></div>
                
                <div class="control_bouton" onclick="ajouterDiv('${div.id}', 'bas', 'code')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><g xmlns="http://www.w3.org/2000/svg"><path d="M9.25 10.0693L7.375 10.0693L7.375 8.19434C7.375 7.98809 7.20625 7.81934 7 7.81934C6.79375 7.81934 6.625 7.98809 6.625 8.19434L6.625 10.0693L4.75 10.0693C4.54375 10.0693 4.375 10.2381 4.375 10.4443C4.375 10.6506 4.54375 10.8193 4.75 10.8193L6.625 10.8193L6.625 12.6943C6.625 12.9006 6.79375 13.0693 7 13.0693C7.20625 13.0693 7.375 12.9006 7.375 12.6943L7.375 10.8193L9.25 10.8193C9.45625 10.8193 9.625 10.6506 9.625 10.4443C9.625 10.2381 9.45625 10.0693 9.25 10.0693Z" fill="#000000" stroke="#000000" stroke-width="0.7"></path></g><path d="M2.5 5.5L2.5 3.5L11.5 3.5L11.5 5.5L2.5 5.5ZM2 7C1.44772 7 1 6.55228 1 6L1 3C1 2.44772 1.44772 2 2 2L12 2C12.5523 2 13 2.44772 13 3L13 6C13 6.55229 12.5523 7 12 7L2 7Z" fill="#000000"></path></svg></div>

                <div id="supprimer_${div.id}" class="control_bouton" onclick="supprimerDiv('${div.id}')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16px" height="16px"><path d="M0 0h24v24H0z" fill="none"></path><path fill="#000000" d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"></path></svg></div>`

            div_content += `</div>`;

            if (type == 'text') {
                div_content += `<textarea id="textarea_`+div_id+`" class="form-control cellule_content exclure" oninput="textarea_autosize(this)" row="2" style="height: 60px;"></textarea>`;
                div_content += `<div id="markedarea_`+div_id+`" class="cellule_content exclure cellule_marked hover-edit" style="position:relative;display:none;min-height:60px;"><div class="markedarea_icon" onclick="edit('`+div_id+`')"><i class="fas fa-pen-square fa-lg"></i></div><div id="markedarea_content_`+div_id+`"></div></div>`;
            }

            if (type == 'code') {
                div_content += `<div id="code_editor_`+div_id+`" class="mb-2 code-editor"></div>`;
				div_content += `<div class="row no-gutters">`;
                div_content += `<div class="col-auto mr-2">
					<div>
						<button id="run_`+div_id+`" onclick="run('`+div_id+`')" style="width:40px;" type="button" class="btn btn-primary text-center mb-1 btn-sm"><i class="fas fa-play"></i></button>
					</div>
					<div id="restart_`+div_id+`" style="display:none;">
						<button style="width:40px;" type="button" onclick="restart()"  class="btn btn-dark btn-sm" style="padding-top:6px;" data-toggle="tooltip" data-placement="right"  data-trigger="hover" title="{{__('Si le bouton d\'arrêt ne permet pas d\'interrompre  l\'exécution du code, cliquer ici. Python redémarrera complètement mais votre code sera conservé dans l\'éditeur. Le redémarrage peut prendre quelques secondes.')}}"><i class="fas fa-stop"></i></button>
					</div>
				</div>`;
                div_content += `<div id="console_`+div_id+`" class="col">
                    <div class="text-dark small text-monospace" style="float:right;padding:5px 12px 0px 0px">console</div>
				    <div id="output_`+div_id+`" class="text-monospace p-3 text-white bg-secondary small" style="white-space: pre-wrap;border-radius:4px;min-height:100px;height:100%;"></div>
                </div>`;
                div_content += `</div>`;
            }    

            div_content += `</div><div class="text-center text-muted mb-3"><i class="fas fa-ellipsis-h"></i></div>`;

            div.innerHTML = div_content;
                
            const mainContainer = document.getElementById('mainContainer');
            const referenceDiv = referenceDivId ? document.getElementById(referenceDivId) : null;
            
            if (referenceDiv) {
                if (position === 'haut') {
                    mainContainer.insertBefore(div, referenceDiv);
                } else {
                    mainContainer.insertBefore(div, referenceDiv.nextSibling);
                }
            } else {
                mainContainer.appendChild(div);
            }
            
            if (type == 'code') {
                editor_code[div_id] = ace.edit('code_editor_' + div_id, {
                    theme: "ace/theme/puzzle_code",
                    mode: "ace/mode/python",
                    maxLines: 500,
                    minLines: 6,
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
                editor_code[div_id].container.style.lineHeight = 1.5;
            }
            save_cellules()
        }

        function deplacer(direction, div_id) {
            const div = document.getElementById(div_id);
            if (direction === 'haut' && div.previousElementSibling) {
                div.parentNode.insertBefore(div, div.previousElementSibling);
            } else if (direction === 'bas' && div.nextElementSibling) {
                div.parentNode.insertBefore(div.nextElementSibling, div);
            }
            save_cellules()
        }

        function supprimerDiv(div_id) {
            const div = document.getElementById(div_id);
            div.parentNode.removeChild(div);
            save_cellules();
        }


        function save_cellules() {
            var container = document.getElementById("mainContainer");
            var children = container.children;
            let notebook = {
                metadata: {
                    kernelspec:{
                        name:"python3",
                        display_name:"Python 3",
                        language:"python"
                    }
                },
                nbformat:4,
                nbformat_minor:2,
                cells: []
            };
            var copie_dico = {};
            for (var i = 0; i < children.length; i++) {
                var id = children[i].id.substring(4);
                if (document.getElementById('textarea_'+id)) {
                    notebook.cells.push({
                        cell_type: "markdown",
                        metadata: {},
                        source: [document.getElementById('textarea_'+id).value]
                    });
                } else {
                    notebook.cells.push({
                        cell_type: "code",
                        execution_count: null,
                        metadata: {},
                        outputs: [],
                        source: [editor_code[id].getValue()]
                    });
                    
                }
            }
            let notebookContent = JSON.stringify(notebook, null, 2);

            // download notebook
            let notebook_download_button = document.getElementById("notebook_download")
            notebook_download_button.href = URL.createObjectURL(new Blob([notebookContent], {type: "application/json"}));
            notebook_download_button.download = "copie-sujet-{{$sujet->jeton}}.ipynb";

            console.log(notebookContent);
        }

        document.addEventListener('click', function(event) {
            var divsExclus = document.querySelectorAll('.exclure');
            function estDivExclu(element) {
                for (var i = 0; i < divsExclus.length; i++) {
                    if (divsExclus[i].contains(element)) {
                        return true;
                    }
                }
                return false;
            }
            if (!estDivExclu(event.target)) {
                for (var i = 0; i < divsExclus.length; i++) {
                    id = divsExclus[i].id.split("_")[1];






                    md_render(id);



                }
            }

            // coloration syntaxique
            document.querySelectorAll('pre code').forEach(el => {
                el.classList.add('language-python');
                
                hljs.highlightElement(el);
            });

            // LaTex
            document.querySelectorAll('div.cellule_marked').forEach(el => {
                MathJax.typesetPromise([el]).then(() => {
                    console.log('Mathématiques rendues.');
                }).catch((err) => console.log('Erreur de rendu MathJax : ', err));
            });

        });
    </script>

    <script>
		// PYODIDE

		// webworker
		let pyodideWorker = createWorker();

		function createWorker() {

            var code_editors = document.querySelectorAll('.code-editor');
            for (var i = 0; i < code_editors.length; i++) {
                editor_id = code_editors[i].id.split("_")[2];
                console.log(editor_id);
                document.getElementById("output_"+editor_id).innerText = "Initialisation...\n";
                document.getElementById("run_"+editor_id).disabled = true;
                document.getElementById("restart_"+editor_id).style.display = 'none';
            }

			let pyodideWorker = new Worker("{{ asset('pyodideworker/copie-pyodideWorker.js') }}");

			pyodideWorker.onmessage = function(event) {
				
				// reponses du WebWorker
				console.log("EVENT: ", event.data);

				if (typeof event.data.init !== 'undefined') {
                    var code_editors = document.querySelectorAll('.code-editor');
                    for (var i = 0; i < code_editors.length; i++) {
                        editor_id = code_editors[i].id.split("_")[2];
                        document.getElementById("output_"+editor_id).innerText = "Prêt!\n";
                        document.getElementById("run_"+editor_id).innerHTML = '<i class="fas fa-play"></i>';
                        document.getElementById("run_"+editor_id).disabled = false;
                        document.getElementById("restart_"+editor_id).style.display = 'none';
                    }
				}

				if (typeof event.data.status !== 'undefined') {

					if (event.data.status == 'running') {
                        var code_editors = document.querySelectorAll('.code-editor');
                        document.getElementById("run_"+event.data.id).innerHTML = '<i class="fas fa-cog fa-spin"></i>';
                        document.getElementById("run_"+event.data.id).disabled = true;
                        document.getElementById("restart_"+event.data.id).style.display = 'block';
					}

					if (event.data.status == 'completed') {
                        var code_editors = document.querySelectorAll('.code-editor');
                        for (var i = 0; i < code_editors.length; i++) {
                            editor_id = code_editors[i].id.split("_")[2];
                            document.getElementById("run_"+editor_id).innerHTML = '<i class="fas fa-play"></i>';
                            document.getElementById("run_"+editor_id).disabled = false;
                            document.getElementById("restart_"+editor_id).style.display = 'none';
                        }
					}
				}

				if (typeof event.data.output !== 'undefined') {
                    console.log('ID: ', event.data.id);
                    console.log('EVENT: ', event.data);
					document.getElementById("output_"+event.data.id).innerHTML += event.data.output;
				}	

			};

			return pyodideWorker

		}

        // envoi des donnees au webworker pour execution
        function run(id) {
            const code = editor_code[id].getValue();
            document.getElementById("output_"+id).innerHTML = "";
            pyodideWorker.postMessage({ code: code, id: id });		
        }

		function restart() {
			if (pyodideWorker) {
				pyodideWorker.terminate();
				console.log("Web Worker supprimé.");
			}
			pyodideWorker = createWorker();
			console.log("Web Worker redémarré.");
		}

	</script>

</body>
</html>
