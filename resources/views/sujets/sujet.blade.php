<?php
if (isset($jeton)) {
	$sujet = App\Models\Sujet::where('jeton', $jeton)->first();
	if (!$sujet) {
		echo "<pre>Ce sujet n'existe pas.</pre>";
		exit();
	} else {
		$sujet_json = json_decode($sujet->sujet);
	}
}
$page_sujet = true;
?>
<!doctype html>
<html lang="fr">
<head>
	@include('inc-meta')
    <title>SUJET</title>
</head>
<body>

	<div class="mt-2 mb-4 text-center"><a class="navbar-brand m-1" href="{{ url('/') }}"><img src="{{ asset('img/code-puzzle.png') }}" width="200" alt="CODE PUZZLE" /></a></div>

	<div class="container">

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

		<div class="row">
			<div class="col">		

				<div class="mt-2 mb-4 text-center">
					<a class="btn btn-outline-secondary btn-sm text-monospace ml-1 mr-1" href="/sujet-{{$sujet->type}}-creer/{{Crypt::encryptString($sujet->id)}}/dupliquer" role="button" target="_blank">dupliquer</a>
					<a class="btn btn-outline-secondary btn-sm text-monospace ml-1 mr-1" href="/S{{strtoupper($sujet->jeton)}}/copie" role="button" target="_blank">sujet + copie</a>
					<a class="btn btn-outline-secondary btn-sm text-monospace ml-1 mr-1" href="/devoir-creer/{{Crypt::encryptString($sujet->id)}}" role="button">créer un devoir</a>
				</div>

				<div class="mb-1 text-monospace">{{strtoupper(__("sujet"))}}</div>
				<!-- SUJET -->
				@include('sujets/inc-sujet-afficher')
				<!-- /SUJET -->

			</div>
		</div><!-- /row -->
	</div><!-- /container -->

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