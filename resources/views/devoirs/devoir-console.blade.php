<?php
$devoir = App\Models\Devoir::where('jeton_secret', $jeton_secret)->first();
if (!$devoir) {
    echo "<pre>Ce devoir n'existe pas</pre>";
    exit();
}
$copies = App\Models\Copie::where('jeton_devoir', $devoir->jeton)->orderBy('pseudo')->get();
$sujet = App\Models\Sujet::find($devoir->sujet_id);
$sujet_json = json_decode($sujet->sujet);
$page_devoir_console = true;
?>
<!doctype html>
<html lang="fr">
<head>
    @include('inc-meta')

    <meta name="robots" content="noindex">

    <title>DEVOIR | {{$devoir->jeton}} | CONSOLE</title>
</head>
<body>

    @include('inc-nav')

	<div class="container">
		<div class="row pt-3">

            <div class="col-md-2 text-right pb-5">
				@if(Auth::check())
				    <a class="btn btn-light btn-sm" href="/console/devoirs" role="button"><i class="fas fa-arrow-left"></i></a>
				@else
				    <a class="btn btn-light btn-sm" href="/" role="button"><i class="fas fa-arrow-left"></i></a>
				    <div class="mt-3 small text-monospace text-muted">Vous pouvez <a href="/creer-un-compte" target="_blank">créer un compte</a> pour regrouper, gérer et partager vos sujets.</div>
				@endif
			</div>

			<div class="col-md-10 pl-4 pr-4">
                <div class="row">
                    <div class="col-md-12 text-monospace p-2 pl-3 pr-3 mb-3" style="border:dashed 2px #e3342f;border-radius:8px;">
                 
                        @if(isset($_GET['i']))
                            <div class="text-monospace text-danger text-center font-weight-bold m-2">SAUVEGARDEZ LES INFORMATIONS CI-DESSOUS AVANT DE QUITTER CETTE PAGE</div>
                        @endif

                        <div class="row justify-content-md-center">
                            @if($devoir->user_id == 0 OR !Auth::check())
                            <div class="col-md-auto">
                                 <div class="text-center font-weight-bold small">lien secret</div>
                                 <div class="text-center rounded bg-danger text-white p-3"><a href="/devoir-console/{{strtoupper($devoir->jeton_secret)}}" target="_blank" class="text-white font-weight-bold">www.codepuzzle.io/devoir-console/{{strtoupper($devoir->jeton_secret)}}</a></div>
                                 <div class="small text-muted pt-1"><span class="text-danger"><i class="fas fa-exclamation-circle"></i> Ne pas partager ce lien</span><br />Il permet d'accéder à la console du devoir (sujet, lien pour les élèves, correction...).</div>
                            </div>
                            @endif
                            <div class="col-md-auto">
                                <div class="text-center font-weight-bold small">code secret</div>
                                <div class="text-center border border-danger rounded text-danger font-weight-bold p-3">{{$devoir->mot_secret}}</div>
                                <div class="small text-muted pt-1"><span class="text-danger"><i class="fas fa-exclamation-circle"></i> Ne pas partager ce code</span><br />Il permet de déverrouiller la copie d'un élève</div>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <div class="mt-2 mb-4">
                    <div class="text-center small text-muted p-0">lien à fournir aux élèves</div>
                    <div class="text-center font-weight-bold text-monospace">
						<a id="lien" href="/E{{strtoupper($devoir->jeton)}}" target="_blank" class="text-dark" style="font-size:24px">www.codepuzzle.io/E{{strtoupper($devoir->jeton)}}</a>
                    </div>
                    <div class="text-center">
                        <span class="pr-1" onclick="fullscreen('fullscreen')" style="cursor:pointer;"><i class="fas fa-expand"></i></span>
                        <div id="fullscreen" class="bg-white text-center" style="display:none">
                            <br /><br /><br /><br /><br /><br />
                            <img src="{{ asset('img/code-puzzle.png') }}" width="200" />
                            <br /><br /><br /><br /><br /><br /><br /><br />
                            <div class="text-monospace text-dark font-weight-bold" style="font-size:5vw;">www.codepuzzle.io/E{{ strtoupper($devoir->jeton) }}</div>
                        </div>

                        <span class="pl-1" onclick="copier('lien')" style="cursor:pointer;"><i class="fa-regular fa-copy"></i></span>
                        <div id="lien_copie_confirmation" class="text-center small text-monospace text muted">&nbsp;</div>
                    </div>

                </div>

                <!-- MODIFIER - SUPERVISER -->
                <div class="row mt-3 mb-3">
                    <div class="col-md-12 text-center">
                        <a class="btn btn-dark btn-sm" href="/devoir-modifier/{{ Crypt::encryptString($devoir->id) }}" role="button"><i class="fa-solid fa-pen mr-2"></i> modifier</a>
                        <a class="btn btn-dark btn-sm ml-3 mr-3" href="/devoir-supervision/{{ Crypt::encryptString($devoir->id) }}" role="button"><i class="fa-solid fa-eye mr-2"></i> superviser</a>
                    </div>
                </div>
                <!-- /MODIFIER - SUPERVISER -->

                <div class="mt-2 mb-1 text-monospace font-weight-bold">{{strtoupper(__($devoir->titre_enseignant))}}</div>
                @if ($devoir->consignes_eleve != '')
                    <div class="markdown_content" style="padding:20px;border:solid 1px #DBE0E5;border-radius:4px;background-color:#f3f5f7;border-radius:4px;">{{$devoir->consignes_eleve}}</div>
                @endif

                <div class="mt-3 mb-1 text-monospace">{{strtoupper(__('copies'))}}</div>
                @if ($copies->isNotEmpty())
                    <div class="row mb-5">
                        <div class="col-md-12">

                            <ul class="list-group">

                                @foreach($copies as $copie)

                                    <?php
                                    $secondes = floor($copie->chrono/1000);
                                    $heures = gmdate("H", $secondes);
                                    $minutes = gmdate("i", $secondes);
                                    $secondes = gmdate("s", $secondes);
                                    $chrono = "{$heures}h {$minutes}m {$secondes}s";


                                    $commentaires ='';
                                    $note = '';
                                    if ($copie->correction_enseignant != null) {
                                        foreach (json_decode($copie->correction_enseignant)->cells AS $notebook_cell) {
                                            if (isset($notebook_cell->metadata->correction_name) AND $notebook_cell->metadata->correction_name == 'commentaires') $commentaires = $notebook_cell->source[0];
                                            if (isset($notebook_cell->metadata->correction_name) AND $notebook_cell->metadata->correction_name == 'note') $note = $notebook_cell->source[0];
                                        }
                                    }
                                    ?>

                                    <li class="list-group-item">

                                        @if($copie->revised == 1)
                                            <a class="btn btn-sm btn-success" href="/devoir-corriger/{{ Crypt::encryptString($devoir->id) }}/{{ $loop->index }}" role="button" style="width:40px;" target="_blank"><i class="fas fa-check"></i></a>
                                        @else
                                            <a class="btn btn-sm btn-light" href="/devoir-corriger/{{ Crypt::encryptString($devoir->id) }}/{{ $loop->index }}" role="button" style="width:40px;" target="_blank"><i class="fas fa-question"></i></a>
                                        @endif                               
                                        
                                        <span class="pl-2 text-monospace">{{$copie->pseudo}}</span>

                                        <div style="float:right;right:0px">

                                            @if ($note != '')
                                                <span class=" text-muted text-monospace pr-3">{{$note}}</span>
                                            @endif
                                            @if ($commentaires != '')
                                                <span class=" text-muted" data-container="body" data-html="true" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="{{ nl2br($commentaires) }}" style="cursor:help"><i class="far fa-comment-alt"></i></span>
                                            @endif

                                            <span class="small text-muted pl-3 pr-3"><i class="mr-1 fa-solid fa-stopwatch"></i>{{$chrono}}</span>

                                            @if($copie->submitted == 1)
                                                <span class="text-white mr-1" style="font-size:70%;background-color:#6c757d;padding:3px 8px 2px 8px;border-radius:3px;vertical-align:2px;">rendu</span>
                                            @endif

                                            <a tabindex='0' role="button" class="pl-2" style="cursor:pointer;outline:none;color:#e2e6ea;font-size:95%" data-toggle="collapse" data-target="#collapseSupprimer-{{$loop->iteration}}" aria-expanded="false" aria-controls="collapseSupprimer-{{$loop->iteration}}"><i class='fas fa-trash fa-sm'></i></a>
                                            <div class="collapse text-right" id="collapseSupprimer-{{$loop->iteration}}">
                                                <div class="mt-3">
                                                    <a href='/devoir-eleve-supprimer/{{ Crypt::encryptString($copie->id) }}' class='btn btn-danger btn-sm text-white' role='button'>{{__('supprimer')}}</a>                                            
                                                    <button type="button" class="btn btn-light btn-sm ml-1" data-toggle="collapse" data-target="#collapseSupprimer-{{$loop->iteration}}">annuler</button>
                                                </div>
                                            </div>
                                        </div>

                                    </li>

                                @endforeach

                            </ul>

                        </div>
                    </div>
                @endif

                @if ($copies->where('revised', 1)->count() != 0)
                    <div class="mt-2 mb-1 text-monospace">{{strtoupper(__("comptes-rendus"))}}</div>
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="list-group text-monospace">

                                @foreach($copies as $copie)
                                    @if($copie->revised == 1)
                                        <li class="list-group-item">                           
                                            {{$copie->pseudo}}
                                            <div style="float:right;right:0px">
                                                <a href="/C{{ strtoupper($copie->jeton_copie) }}" target="_blank">www.copdepuzzle.io/C{{strtoupper($copie->jeton_copie)}}</a>
                                            </div>
                                        </li>
                                    @endif
                                @endforeach

                            </ul>
                            <div class="mt-1">
                                <a class="btn btn-dark btn-sm" href="/devoir-imprimer/{{ Crypt::encryptString($devoir->id) }}" role="button"><i class="fa-solid fa-print mr-2"></i> imprimer les comptes-rendus</a>
                                <span class="text-muted small">pour les annoter à la main si nécessaire et les distribuer aux élèves</span>
                            </div>
                        </div>                        
                    </div>
                @endif

                <div class="pt-5 mb-1 text-monospace">{{strtoupper(__("sujet"))}}</div>
                <!-- SUJET -->
                @include('sujets/inc-sujet-afficher')
                <!-- SUJET -->

            </div>
        </div>
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
