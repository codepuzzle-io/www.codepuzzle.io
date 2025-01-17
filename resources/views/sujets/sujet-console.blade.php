<?php
if (isset($jeton_secret)) {
	$sujet = App\Models\Sujet::where('jeton_secret', $jeton_secret)->first();
	if (!$sujet) {
		echo "<pre>Ce sujet n'existe pas.</pre>";
		exit();
	} else {
		if ($sujet->user_id !== 0 && (!Auth::check() || (Auth::check() && Auth::id() !== $sujet->user_id))) {
			echo "<pre>Vous devez vous connecter pour accéder à ce sujet.</pre>";
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
	<meta name="robots" content="noindex">
    <link href="{{ asset('css/highlight.css') }}" rel="stylesheet">
	<link href="{{ asset('css/easymde.css') }}" rel="stylesheet">
	<link href="{{ asset('css/easymde-custom.css') }}" rel="stylesheet">
	<title>SUJET | {{$sujet->jeton}} | CONSOLE</title>
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

				<!-- LIENS -->
                <div class="row">

                    <div class="col-md-12">
                        <div class="text-monospace pt-2 pl-3 pr-3 pb-3 mb-3" style="background-color:#dae0e5;border-radius:6px;">

                            <div>
                                <span class="text-center small text-muted p-0" style="vertical-align:2px;"><i class="fa-solid fa-share"></i> Lien sujet:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                <span class="text-center font-weight-bold text-monospace" style="font-size:24px">
                                    <a id="lien_sujet" href="/S{{strtoupper($sujet->jeton)}}" target="_blank" class="text-dark">www.codepuzzle.io/S{{strtoupper($sujet->jeton)}}</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </span>
                                <span class="pl-3">
                                    <button onclick="fullscreen('lien_sujet_fullscreen')" type="button" class="btn btn-light btn-sm" style="vertical-align:4px;"><i class="fas fa-expand"></i></button>
                                    <div id="lien_sujet_fullscreen" class="bg-white text-center" style="display:none">
                                        <br /><br /><br /><br /><br /><br />
                                        <img src="{{ asset('img/code-puzzle.png') }}" width="200" />
                                        <br /><br /><br /><br /><br /><br /><br /><br />
                                        <div class="text-monospace text-dark font-weight-bold" style="font-size:5vw;">www.codepuzzle.io/S{{strtoupper($sujet->jeton)}}</div>
                                    </div>
                                    <button onclick="copier('lien_sujet', this)" type="button" class="btn btn-light btn-sm" style="vertical-align:4px;"><i class="fa-regular fa-clone"></i></button>
                                </span>
                            </div>

                            <div>
                                <span class="text-center small text-muted p-0" style="vertical-align:2px;"><i class="fa-solid fa-share"></i> Lien sujet + copie:</span>
                                <span class="text-center font-weight-bold text-monospace" style="font-size:24px">
                                    <a id="lien_sujet_copie" href="/S{{strtoupper($sujet->jeton)}}/copie" target="_blank" class="text-dark">www.codepuzzle.io/S{{strtoupper($sujet->jeton)}}/copie</a>
                                </span>
                                <span class="pl-3">
                                    <button onclick="fullscreen('lien_sujet_copie_fullscreen')" type="button" class="btn btn-light btn-sm" style="vertical-align:4px;"><i class="fas fa-expand"></i></button>
                                    <div id="lien_sujet_copie_fullscreen" class="bg-white text-center" style="display:none">
                                        <br /><br /><br /><br /><br /><br />
                                        <img src="{{ asset('img/code-puzzle.png') }}" width="200" />
                                        <br /><br /><br /><br /><br /><br /><br /><br />
                                        <div class="text-monospace text-dark font-weight-bold" style="font-size:5vw;">www.codepuzzle.io/S{{strtoupper($sujet->jeton)}}/copie</div>
                                    </div>
                                    <button onclick="copier('lien_sujet_copie', this)" type="button" class="btn btn-light btn-sm" style="vertical-align:4px;"><i class="fa-regular fa-clone"></i></button>
                                </span>
                            </div>							

                        </div>
                    </div>

                </div><!-- /row -->
				<!-- /LIENS -->

				<div class="mt-3 mb-3 text-center">
					<a class="btn btn-dark btn-sm text-monospace ml-1 mr-1" href="/sujet-{{$sujet->type}}-creer/{{Crypt::encryptString($sujet->id)}}" role="button"><i class="fa-solid fa-pen mr-2"></i>modifier</a>
					<a class="btn btn-dark btn-sm text-monospace ml-1 mr-1" href="/sujet-{{$sujet->type}}-creer/{{Crypt::encryptString($sujet->id)}}/dupliquer" role="button" target="_blank"><i class="fa-solid fa-repeat mr-2"></i>dupliquer</a>
					<a class="btn btn-dark btn-sm text-monospace ml-1 mr-1" href="/devoir-creer/{{Crypt::encryptString($sujet->id)}}" role="button" target="_blank"><i class="fa-regular fa-file-lines mr-2"></i>créer un devoir</a>
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
	function copier(id, element) {
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

        let icon = element.innerHTML;
		element.style.opacity = '0.2';
        element.innerHTML = '<i class="fa-solid fa-check"></i>';
		var fadeOutInterval = setInterval(function() {
			var opacity = parseFloat(element.style.opacity);
			if (opacity == 1) {
				clearInterval(fadeOutInterval);
			} else {
                if (opacity > 0.8){
                    element.blur();
                    element.innerHTML = icon;
                }
				element.style.opacity = (opacity + 0.1).toString();
			}
		}, 200);

		console.log('copied');
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