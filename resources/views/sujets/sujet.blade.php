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
    <link href="{{ asset('css/highlight.css') }}" rel="stylesheet">
	<link href="{{ asset('css/easymde.css') }}" rel="stylesheet">
	<link href="{{ asset('css/easymde-custom.css') }}" rel="stylesheet">
    <title>SUJET</title>
</head>
<body>

	<div class="mt-2 mb-4 text-center"><a class="navbar-brand m-1" href="{{ url('/') }}"><img src="{{ asset('img/code-puzzle.png') }}" width="200" alt="CODE PUZZLE" /></a></div>
 
	<div class="container">

        <div>
            <a class="text-dark mr-2" data-toggle="collapse" href="#collapseSujet" role="button" aria-expanded="false" aria-controls="collapseSujet"><i class="fa-solid fa-bars"></i></a>SUJET
        </div>
        
        <div class="collapse show" id="collapseSujet">

            <div class="text-monospace text-center">

                <a class="btn btn-success font-weight-bold" href="/devoir-creer/{{Crypt::encryptString($sujet->id)}}" role="button" style="width:230px;font-size:18px;display:inline-block;" data-toggle="tooltip" data-placement="top" title="créer un devoir avec ce sujet"><i class="fa-regular fa-file-lines mr-2"></i>créer un devoir</a>
                  
                <a class="btn btn-light font-weight-bold" href="/sujet-{{$sujet->type}}-creer/{{Crypt::encryptString($sujet->id)}}/dupliquer" role="button" target="_blank" style="width:170px;font-size:18px;display:inline-block;" data-toggle="tooltip" data-placement="top" title="dupliquer ce sujet afin de l'adapter à vos besoins"><i class="fa-solid fa-repeat mr-2"></i>dupliquer</a>
                 
            </div>

            <div class="row justify-content-md-center text-monospace pt-4">

                <div class="col-md-auto text-center">
                    <div class="small text-muted pb-1">Lien sujet + copie à fournir aux élèves pour un entraînement en autonomie</div>
                    <span class="small text-muted pl-2" style="vertical-align:3px;"><i class="fa-solid fa-share fa-flip-vertical"></i></span>
                    <a id="lien_sujet_copie" href="/K{{ \App\Functions\SujetId::encodeSujetId($sujet->id) }}" target="_blank" class="text-dark font-weight-bold" style="font-size:18px">www.codepuzzle.io/K{{ \App\Functions\SujetId::encodeSujetId($sujet->id) }}</a>
                    
                    <span class="pl-3">
                        <button onclick="fullscreen('lien_sujet_copie_fullscreen')" type="button" class="btn btn-light btn-sm"><i class="fas fa-expand"></i></button>
                        <div id="lien_sujet_copie_fullscreen" class="bg-white text-center" style="display:none">
                            <br /><br /><br /><br /><br /><br />
                            <img src="{{ asset('img/code-puzzle.png') }}" width="200" />
                            <br /><br /><br /><br /><br /><br /><br /><br />
                            <div class="text-monospace text-dark font-weight-bold" style="font-size:5vw;">www.codepuzzle.io/K{{ \App\Functions\SujetId::encodeSujetId($sujet->id) }}</div>
                        </div>
                        <button onclick="copier('lien_sujet_copie', this)" type="button" class="btn btn-light btn-sm"><i class="fa-regular fa-clone"></i></button>
                    </span>
 
                </div>
            </div>

        </div>

		<div class="row mt-3 mb-3">
			<div class="col">		

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