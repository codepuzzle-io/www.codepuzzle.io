<?php
if (isset($jeton_secret)) {
	$sujet = App\Models\Sujet::where('jeton_secret', $jeton_secret)->first();
	if (!$sujet) {
		echo "<pre>Ce sujet n'existe pas.</pre>";
		exit();
	} else {
		if ($sujet->user_id !== 0 && (!Auth::check() || (Auth::check() && Auth::id() !== $sujet->user_id))) {
			echo "<pre>Vous ne pouvez pas accéder à ce sujet.</pre>";
			exit();
		}
		$sujet_json = json_decode($sujet->sujet);
		$page_sujet_console = true;
	}
}
?>
<!doctype html>
<html lang="fr">
<head>
	@include('inc-meta')
    <link href="{{ asset('css/highlight.css') }}" rel="stylesheet">
	<link href="{{ asset('css/easymde.css') }}" rel="stylesheet">
	<link href="{{ asset('css/easymde-custom.css') }}" rel="stylesheet">
	<link href="{{ asset('css/dropzone-basic.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dropzone.css') }}" rel="stylesheet">
    <title>SUJET | CONSOLE</title>
</head>
<body>

	@if(Auth::check())
		@include('inc-nav-console')
	@else
		@include('inc-nav')
	@endif

	<div class="container mt-4">

		<div class="row">

			<div class="col-md-2 text-right pb-5">
				@if(Auth::check())
				    <a class="btn btn-light btn-sm" href="/console/sujets" role="button"><i class="fas fa-arrow-left"></i></a>
				@else
				    <a class="btn btn-light btn-sm" href="/" role="button"><i class="fas fa-arrow-left"></i></a>
				    <div class="mt-3 small text-monospace text-muted">Vous pouvez <a href="/creer-un-compte" target="_blank">créer un compte</a> pour regrouper, gérer et partager vos sujets.</div>
				@endif
			</div>

			<div class="col-md-10">

				<h1 class="text-center">SUJET</h1>

                @if($sujet->user_id == 0 OR !Auth::check())
                    <div class="row ml-1 mr-1">
                        <div class="col-md-10 offset-md-1 text-monospace p-2 pl-5 pr-5 mb-3" style="border:dashed 2px #e3342f;border-radius:8px;">
                            @if(isset($_GET['i']))
                                <div class="text-danger text-center font-weight-bold mb-2">SAUVEGARDEZ LES INFORMATIONS CI-DESSOUS AVANT DE QUITTER CETTE PAGE</div>
                            @endif
                            <div class="text-center font-weight-bold small">lien secret</div>
                            <div class="text-center p-2 text-break align-middle rounded bg-danger text-white"><a href="/sujet-console/{{strtoupper($sujet->jeton_secret)}}" target="_blank" class="text-white">www.codepuzzle.io/sujet-console/{{strtoupper($sujet->jeton_secret)}}</a></div>
                            <div class="small text-muted p-1"><span class="text-danger"><i class="fas fa-exclamation-circle"></i> Sauvegarder ce lien et ne pas le partager car il permet de revenir sur cette page.</span></div>
                        </div>
                    </div>
                @endif



				<div class="row">
					<div class="col offset-md-1">

						<div class="mt-2">
							<div class="text-left small text-muted p-0">lien sujet</div>
							<div class="text-left font-weight-bold text-monospace">
								<a id="lien_sujet" href="/S{{strtoupper($sujet->jeton)}}" target="_blank" class="text-dark" style="font-size:24px">www.codepuzzle.io/S{{strtoupper($sujet->jeton)}}</a>
												
								<span class="pl-2 align-text-bottom" onclick="fullscreen('fullscreen_lien_sujet')" style="cursor:pointer;"><i class="fas fa-expand"></i></span>
								<div id="fullscreen_lien_sujet" class="bg-white text-center" style="display:none">
									<br /><br /><br /><br /><br /><br />
									<img src="{{ asset('img/code-puzzle.png') }}" width="200" />
									<br /><br /><br /><br /><br /><br /><br /><br />
									<div class="text-monospace text-dark font-weight-bold" style="font-size:5vw;">www.codepuzzle.io/S{{ strtoupper($sujet->jeton) }}</div>
								</div>

								<span class="pl-1 align-text-bottom" onclick="copier('lien_sujet')" style="cursor:pointer;"><i class="fa-regular fa-copy"></i></span>
								<span id="lien_sujet_copie_confirmation" class="text-center small text-monospace text muted align-text-bottom">&nbsp;</span>
							</div>
						</div>


						<div class="mt-2 mb-4">
							<div class="text-left small text-muted p-0">lien sujet + copie</div>
							<div class="text-left font-weight-bold text-monospace">
								<a id="lien_sujet_copie" href="/S{{strtoupper($sujet->jeton)}}/copie" target="_blank" class="text-dark" style="font-size:24px">www.codepuzzle.io/S{{strtoupper($sujet->jeton)}}/copie</a>
												
								<span class="pl-2 align-text-bottom" onclick="fullscreen('fullscreen_lien_sujet_copie')" style="cursor:pointer;"><i class="fas fa-expand"></i></span>
								<div id="fullscreen_lien_sujet_copie" class="bg-white text-center" style="display:none">
									<br /><br /><br /><br /><br /><br />
									<img src="{{ asset('img/code-puzzle.png') }}" width="200" />
									<br /><br /><br /><br /><br /><br /><br /><br />
									<div class="text-monospace text-dark font-weight-bold" style="font-size:5vw;">www.codepuzzle.io/S{{ strtoupper($sujet->jeton) }}/copie</div>
								</div>

								<span class="pl-1 align-text-bottom" onclick="copier('lien_sujet_copie')" style="cursor:pointer;"><i class="fa-regular fa-copy"></i></span>
								<span id="lien_sujet_copie_copie_confirmation" class="text-center small text-monospace text muted align-text-bottom">&nbsp;</span>
							</div>
						</div>	

					</div>	
				</div><!-- /row -->

				<div class="mt-2 mb-4 text-center">
					<a class="btn btn-dark btn-sm text-monospace ml-1 mr-1" href="/sujet-{{$sujet->type}}-creer/{{Crypt::encryptString($sujet->id)}}" role="button"><i class="fa-solid fa-pen mr-2"></i>modifier</a>
					<a class="btn btn-outline-secondary btn-sm text-monospace ml-1 mr-1" href="/sujet-{{$sujet->type}}-creer/{{Crypt::encryptString($sujet->id)}}/dupliquer" role="button" target="_blank">dupliquer</a>
					<a class="btn btn-outline-secondary btn-sm text-monospace ml-1 mr-1" href="/S{{strtoupper($sujet->jeton)}}/copie" role="button" target="_blank">sujet + copie</a>
					<a class="btn btn-outline-secondary btn-sm text-monospace ml-1 mr-1" href="/devoir-creer/{{Crypt::encryptString($sujet->id)}}" role="button" target="_blank">créer un devoir</a>
				</div>

				<div class="mb-1 text-monospace">{{strtoupper(__("sujet"))}}</div>
				<!-- SUJET -->
				@include('sujets/inc-sujet-afficher')
				<!-- /SUJET -->

            </div>
        </div>
    </div>

	<br />

	@include('inc-bottom-js')
    @include('sujets/inc-sujet-afficher-js')


    {{-- == Copie lien ======================================================= --}}	
	<script>
	function copier(id) {
		var texte = document.getElementById(id).textContent;
		if (!navigator.clipboard) {
			// Alternative pour les navigateurs ne prenant pas en charge navigator.clipboard
			var zoneDeCopie = document.createElement("textarea");
			zoneDeCopie.value = texte;
			document.body.appendChild(zoneDeCopie);
			zoneDeCopie.select();
			document.execCommand("copy");
			document.body.removeChild(zoneDeCopie);
			return;
		}

		navigator.clipboard.writeText(texte).then(function() {
			//alert("Le texte a été copié dans le presse-papiers.");
		}, function() {
			// Gérer les erreurs éventuelles
			//alert("Impossible de copier le texte dans le presse-papiers. Veuillez le faire manuellement.");
		});
		
		var status = document.getElementById(id+'_copie_confirmation');
        status.innerText = "copié";
		
		status.style.opacity = '1';
		var fadeOutInterval = setInterval(function() {
			var opacity = parseFloat(status.style.opacity);
			if (opacity <= 0) {
				clearInterval(fadeOutInterval);
				status.innerHTML = "&nbsp;"; // Effacer le texte après l'animation
			} else {
				status.style.opacity = (opacity - 0.1).toString();
			}
		}, 150);
	}
	</script>
    {{-- == /Copie lien ====================================================== --}}	

    {{-- == Fullscreen lien ================================================== --}}
    <script>
        function fullscreen(id) {
            var el = document.getElementById(id);
            var isFullscreen = document.fullscreenElement || document.webkitFullscreenElement || document.msFullscreenElement || document.mozFullScreenElement;

            if (isFullscreen) {
                // Quitter le plein écran
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) { /* Safari */
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) { /* IE11 */
                    document.msExitFullscreen();
                } else if (document.mozCancelFullScreen) { /* Firefox */
                    document.mozCancelFullScreen();
                }
            } else {
                // Afficher l'élément et entrer en plein écran
                el.style.display = 'block';
                if (el.requestFullscreen) {
                    el.requestFullscreen();
                } else if (el.webkitRequestFullscreen) { /* Safari */
                    el.webkitRequestFullscreen();
                } else if (el.msRequestFullscreen) { /* IE11 */
                    el.msRequestFullscreen();
                } else if (el.mozRequestFullScreen) { /* Firefox */
                    el.mozRequestFullScreen();
                }
            }
        }

        function updateFsButton() {
            if (!document.fullscreenElement && !document.webkitFullscreenElement && 
                !document.msFullscreenElement && !document.mozFullScreenElement) {
                
                if (currentFullscreenElement) {
                    // L'élément n'est plus en plein écran, le cacher
                    currentFullscreenElement.style.display = "none";
                    currentFullscreenElement = null;  // Réinitialiser pour éviter toute ambiguïté
                }
            } else {
                // Enregistrer l'élément en plein écran si ce n'est pas déjà fait
                currentFullscreenElement = document.fullscreenElement || 
                                        document.webkitFullscreenElement || 
                                        document.msFullscreenElement || 
                                        document.mozFullScreenElement;
            }
            console.log("État du plein écran changé");
        }

        document.addEventListener("fullscreenchange", updateFsButton, false);
        document.addEventListener("webkitfullscreenchange", updateFsButton, false);
        document.addEventListener("mozfullscreenchange", updateFsButton, false);
        document.addEventListener("MSFullscreenChange", updateFsButton, false);
    </script>
    {{-- == /Fullscreen lien ================================================= --}}
	
</body>
</html>