<!doctype html>
<html lang="fr">
<head>
    @include('inc-meta')
    <title>{{ config('app.name') }} | {{ ucfirst(__('console')) }}</title>
</head>
<body>
    @php
		$lang_switch = '<a href="/console/lang/fr" class="kbd mr-1">fr</a><a href="/console/lang/en" class="kbd">en</a>';
	@endphp
    @include('inc-nav-console')

	<div class="container mt-3 mb-5">

		<div class="row">

            <div class="col-md-2">
                <div class="text-right mb-3"><a class="btn btn-light btn-sm" href="/console" role="button"><i class="fas fa-arrow-left"></i></a></div>
                <div class="mb-1 text-center" style="opacity:0.4"><a class="btn btn-light btn-sm d-block" href="{{route('console-puzzles')}}" role="button">{{__('PUZZLES')}}</a></div>
                <div class="mb-1 text-center" style="opacity:0.4"><a class="btn btn-light btn-sm d-block" href="{{route('console-defis')}}" role="button">{{__('DÉFIS')}}</a></div>
                <div class="mb-1 text-center" style="opacity:0.4"><a class="btn btn-light btn-sm d-block" href="{{route('console-sujets')}}" role="button">{{__('SUJETS')}}</a></div>
                <div class="mb-1 text-center" style="opacity:0.4"><a class="btn btn-light btn-sm d-block" href="{{route('console-devoirs')}}" role="button">{{__('DEVOIRS')}}</a></div>
                <div class="mb-1 text-center" style="opacity:0.4"><a class="btn btn-light btn-sm d-block" href="{{route('console-programmes')}}" role="button">{{__('PROGRAMMES')}}</a></div>
                <div class="mb-1 text-center" style="opacity:1.0"><a class="btn btn-light btn-sm d-block" href="{{route('console-programmes')}}" role="button">{{__('CAPTURES')}}</a></div>
                <div class="mb-3 text-center" style="opacity:0.4"><a class="btn btn-light btn-sm d-block" href="{{route('console-classes')}}" role="button">{{__('CLASSES')}}</a></div>           
            </div>

			<div class="col-md-10 pl-4 pr-4">

				@if (session('status'))
					<div class="text-success text-monospace text-center pb-4" role="alert">
						{{ session('status') }}
					</div>
				@endif

                <?php
                $captures = App\Models\Capture::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
                ?>

                <div class="text-monospace">
                    <a class="btn btn-light btn-sm pl-3 pr-3" href="#" onclick="window.location.reload(); return false;" role="button"><i class="fa-solid fa-rotate"></i></a>
                    <a class="btn btn-success btn-sm pl-3 pr-3" href="/ocr" target="_blank" role="button">nouvelle capture</a>
                </div>

                <div class="mt-4 mb-3 text-monospace text-danger small"><i class="fa-solid fa-circle-exclamation"></i> Les captures sont supprimées au bout d'un mois</div>

                @foreach($captures as $capture)
                    <div id="frame_{{$loop->iteration}}" class="frame p-2">

                        <div class="row">

                            <div class="col-md-12 text-monospace text-muted">

                                <!-- options -->
    							<div style="float:right;">

                                    <a class='btn btn-dark btn-sm' href='/console/captures/{{ Crypt::encryptString($capture->id) }}' role='button' data-toggle="tooltip" data-placement="top" title="{{__('voir / éditer')}}"><i class="fa-solid fa-code"></i></a>
               
                                    <!-- supprimer -->
                                    <span id="supprimer_button_{{$loop->iteration}}">
                                        <div onclick="showConfirm('supprimer_button_{{$loop->iteration}}', 'supprimer_confirm_{{$loop->iteration}}')" class="d-inline-block" type="button">
                                            <a tabindex='0' class='btn btn-light btn-sm' role='button'  style="cursor:pointer;outline:none;"><i class='fa-solid fa-xmark'></i></a>
                                        </div>
                                    </span>
                                    <span id="supprimer_confirm_{{$loop->iteration}}" style="display:none">
                                        <div id="supprimer_{{$loop->iteration}}" class="d-inline-block">
                                            <a href='/console/capture-supprimer/{{ Crypt::encryptString($capture->id) }}' class='btn btn-danger btn-sm text-light ml-3' role='button'  style="cursor:pointer;outline:none;" data-toggle="tooltip" data-placement="top" title="{{__('supprimer')}}"><i class='fas fa-trash fa-sm'></i></a>
                                        </div>
                                        <div id="supprimer_cancel_{{$loop->iteration}}" onclick="hideConfirm('supprimer_button_{{$loop->iteration}}', 'supprimer_confirm_{{$loop->iteration}}')" class="d-inline-block" type="button">
                                            <a tabindex='0' class='btn btn-light btn-sm' role='button' style="cursor:pointer;outline:none;" data-toggle="tooltip" data-placement="top" title="{{__('annuler')}}"><i class="fa-solid fa-chevron-right" ></i></a>
                                        </div>
                                    </span>
                                    <!-- /supprimer -->

    							</div>
    							<!-- /options -->

                                @php
                                    // on récupère l'octet-array et on base64-encode
                                    $image = base64_encode($capture->image);
                                @endphp

                                <img src="data:image/png;base64,{{ $image }}" height="80" style="border-radius:4px;" alt="Capture d'écran">

                                                          

                            </div>
                        </div>
                        
                    </div>
                @endforeach

            </div>
        </div>
	</div><!-- /container -->

	@include('inc-bottom-js')
	@include('markdown/inc-markdown-afficher-js')

	{{-- == Mécanisme confirmation suppression cellule ======================= --}}
	<script>
		function showConfirm(buttonId, confirmId) {
			// Cacher le bouton delete_button et afficher delete_confirm
			document.getElementById(buttonId).style.display = 'none';
			document.getElementById(confirmId).style.display = 'inline';
		}

		function hideConfirm(buttonId, confirmId) {
			// Cacher delete_confirm et réafficher delete_button
			document.getElementById(confirmId).style.display = 'none';
			document.getElementById(buttonId).style.display = 'inline';
		}
	</script>
	{{-- == /Mécanisme bouton confirmation =================================== --}}	

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
