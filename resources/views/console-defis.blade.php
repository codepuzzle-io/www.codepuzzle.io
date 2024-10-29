<!doctype html>
<html lang="fr">
<head>
    @include('inc-meta')
    <link rel="stylesheet" href="{{ asset('lib/highlight/atom-one-dark.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/default.min.css">
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
                <div class="mb-1 text-center" style="opacity:1.0"><a class="btn btn-light btn-sm d-block" href="{{route('console-defis')}}" role="button">{{__('DÉFIS')}}</a></div>
                <div class="mb-1 text-center" style="opacity:0.4"><a class="btn btn-light btn-sm d-block" href="{{route('console-sujets')}}" role="button">{{__('SUJETS')}}</a></div>
                <div class="mb-1 text-center" style="opacity:0.4"><a class="btn btn-light btn-sm d-block" href="{{route('console-devoirs')}}" role="button">{{__('DEVOIRS')}}</a></div>
                <div class="mb-1 text-center" style="opacity:0.4"><a class="btn btn-light btn-sm d-block" href="{{route('console-programmes')}}" role="button">{{__('PROGRAMMES')}}</a></div>
                <div class="mb-3 text-center" style="opacity:0.4"><a class="btn btn-light btn-sm d-block" href="{{route('console-classes')}}" role="button">{{__('CLASSES')}}</a></div>           
            </div>

			<div class="col-md-10 pl-4 pr-4">

				@if (session('status'))
					<div class="text-success text-monospace text-center pb-4" role="alert">
						{{ session('status') }}
					</div>
				@endif

                <?php
                $defis = App\Models\Defi::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
                ?>

                <div class="mb-4 text-monospace">
                    <div class="form-inline">
                        @csrf
                        <div class="form-group">

                            <a class="btn btn-success btn-sm pl-3 pr-3" href="{{route('defi-creer-get')}}" role="button">{{__('nouveau défi')}}</a>
                        </div>
                        <div class="text-muted small">
                            <i class="fas fa-ellipsis-v ml-3 mr-2"></i> importer des défis existants <a data-toggle="collapse" href="#collapseImport" role="button" aria-expanded="false" aria-controls="collapseImport"> <i class="fa-solid fa-circle-chevron-down"></i></a>
                        </div>
                    </div>
                    <div class="collapse" id="collapseImport">
                        <form method="POST" action="{{route('defis-importer-codes')}}">
                            @csrf
                            <div class="small text-muted mt-3 pb-1">
                                Indiquez ci-dessous les codes des défis que vous souhaitez importer. Saisir les codes en les séparant par des virgules.<br />Exemple: DQMSK,DXSR8,DWMX2,DEHSD,DL92R<br />
                                Pour trouver des défis à importer: <a href="/defis-banque" target="_blank">banque de défis</a>. Des codes peuvent aussi être échangés entre enseignants.
                            </div>
                            <textarea id="codes_defis" name="codes_defis" class="form-control" rows="4"></textarea>
                            <button type="submit" class="btn btn-primary btn-sm mt-1 pl-3 pr-3"><i class="fas fa-check"></i></button>       
                        </form>
                    </div>
                </div>

                @foreach($defis as $defi)
                    <div id="frame_{{$loop->iteration}}" class="frame">

                        <div class="row">
                            <div class="col-md-12 text-monospace text-muted">

                                <!-- options -->
    							<div style="float:right;">

                                    <a class='btn btn-light btn-sm' data-toggle="collapse" href="#collapse-{{$loop->iteration}}" role='button' aria-expanded="false" aria-controls="collapse-{{$loop->iteration}}" ><i class="fas fa-bars" style="margin-top:0px;" data-toggle="tooltip" data-placement="top"  data-offset="0, 9" title="{{__('déplier plier')}}"></i></a>

    								<a class='btn btn-light btn-sm' href='/console/defi-modifier/{{ Crypt::encryptString($defi->id) }}' role='button' data-toggle="tooltip" data-placement="top" title="{{__('modifier')}}"><i class="fas fa-pen"></i></a>

                                    <!-- supprimer -->
                                    <span id="supprimer_button_{{$loop->iteration}}">
                                        <div onclick="showConfirm('supprimer_button_{{$loop->iteration}}', 'supprimer_confirm_{{$loop->iteration}}')" class="d-inline-block" type="button">
                                            <a tabindex='0' class='btn btn-light btn-sm' role='button'  style="cursor:pointer;outline:none;"><i class='fa-solid fa-xmark'></i></a>
                                        </div>
                                    </span>
                                    <span id="supprimer_confirm_{{$loop->iteration}}" style="display:none">
                                        <div id="supprimer_{{$loop->iteration}}" class="d-inline-block">
                                            <a href='/console/defi-supprimer/{{ Crypt::encryptString($defi->id) }}' class='btn btn-danger btn-sm text-light ml-3' role='button'  style="cursor:pointer;outline:none;" data-toggle="tooltip" data-placement="top" title="{{__('supprimer')}}"><i class='fas fa-trash fa-sm'></i></a>
                                        </div>
                                        <div id="supprimer_cancel_{{$loop->iteration}}" onclick="hideConfirm('supprimer_button_{{$loop->iteration}}', 'supprimer_confirm_{{$loop->iteration}}')" class="d-inline-block" type="button">
                                            <a tabindex='0' class='btn btn-light btn-sm' role='button' style="cursor:pointer;outline:none;" data-toggle="tooltip" data-placement="top" title="{{__('annuler')}}"><i class="fa-solid fa-chevron-right" ></i></a>
                                        </div>
                                    </span>
                                    <!-- /supprimer -->

    							</div>
    							<!-- /options -->

                                <h2 class="p-0 m-0">{{ $defi->titre_enseignant }}</h2>
                                @if ($defi->sous_titre_enseignant !== NULL)
                                    <div class="text-monospace small" style="color:silver;">{{ $defi->sous_titre_enseignant }}</div>
                                @endif

                                <div class="row mt-1" style="clear:both;">
                                    <div class="col-md-12 text-monospace text-muted">

                                        <span class="small"><i class="fas fa-share-alt ml-1 mr-2"></i>lien élèves: </span><a id="lien_{{$loop->iteration}}" href="/{{ strtoupper('D'.$defi->jeton) }}" target="_blank" >www.codepuzzle.io/D{{ strtoupper($defi->jeton) }}</a>

                                        <span class="pl-2" onclick="fullscreen('fullscreen_{{$loop->iteration}}')" style="cursor:pointer;"><i class="fas fa-expand"></i></span>
                                        <div id="fullscreen_{{$loop->iteration}}" class="bg-white text-center" style="display:none">
                                            <br /><br /><br /><br /><br /><br />
                                            <img src="{{ asset('img/code-puzzle.png') }}" width="200" />
                                            <br /><br /><br /><br /><br /><br /><br /><br />
                                            <div class="text-monospace text-dark font-weight-bold" style="font-size:5vw;">www.codepuzzle.io/D{{ strtoupper($defi->jeton) }}</div>
                                        </div>
                                        
                                        <span class="pl-2" onclick="copier('lien_{{$loop->iteration}}')" style="cursor:pointer;"><i class="fa-regular fa-copy"></i></span><span id="lien_{{$loop->iteration}}_copie_confirmation" class="pl-3 text-right small text-monospace text muted">&nbsp;</span>

                                    </div>
                                </div>
                                
                            </div>
                        </div>


                        <div class="collapse" id="collapse-{{$loop->iteration}}">
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="text-monospace text-muted mb-3 small">
                                        <i class="fas fa-share-alt ml-1 mr-1"></i> {{__('Code à insérer dans un site web')}}
                                        <div class="mt-1" style="margin-left:22px;">
                                            <input class="form-control form-control-sm" type="text" value='<iframe src="https://www.codepuzzle.io/ID{{ strtoupper($defi->jeton) }}" width="100%" height="600" frameborder="0"></iframe>' disabled readonly />
                                        </div>
                                        <p class="text-monospace mt-1" style="margin-left:22px;font-size:90%";color:silver>{{__('Remarque : ajuster la valeur de "height" en fonction de la taille du défi')}}</p>
                                    </div>
                                    <div class="text-monospace text-muted mb-4 small">
                                        <i class="fas fa-share-alt ml-1 mr-1"></i> {{__('Code à insérer dans une cellule code d un notebook Jupyter')}}
                                        <div class="mt-1" style="margin-left:22px;">
                                            <textarea class="form-control form-control-sm" rows="2" disabled readonly>from IPython.display import IFrame
IFrame('https://www.codepuzzle.io/ID{{ strtoupper($defi->jeton) }}', width='100%', height=600)</textarea>
                                        </div>
                                        <p class="text-monospace mt-1" style="margin-left:22px;font-size:90%";color:silver>{{__('Remarque : ajuster la valeur de "height" en fonction de la taille du défi')}}</p>
                                    </div>
                                    <!--
                                    <div class="text-monospace text-muted mb-4 small">
                                        <i class="fas fa-share-alt ml-1 mr-1"></i> QR code : <img src="https://api.qrserver.com/v1/create-qr-code/?data={{urlencode('https://www.codepuzzle.io/D' . strtoupper($defi->jeton))}}&amp;size=100x100" style="width:100px" alt="wwww.codepuzzle.io/D{{strtoupper($defi->jeton)}}" data-toggle="tooltip" data-placement="right" title="{{__('clic droit + Enregistrer l image sous... pour sauvegarder l image')}}" />
                                    </div>
                                    -->

                                    @if ($defi->titre_eleve !== NULL OR $defi->consignes_eleve !== NULL)
                                        @if ($defi->titre_eleve !== NULL)
                                            <div class="mt-3 text-monospace small">{{ strtoupper($defi->titre_eleve) }}</div>
                                        @else
                                            <div class="mt-3 text-monospace small">{{__('CONSIGNES')}}</div>
                                        @endif
                                        @if ($defi->consignes_eleve !== NULL)
                                            <div class="markdown_content border rounded bg-light p-3">{{ $defi->consignes_eleve }}</div>
                                        @endif
                                    @endif

                                    <div class="mt-3 text-monospace small">{{__('RÉPONSE POSSIBLE')}}</div>
                                    <div style="width:100%;margin:0px auto 0px auto;"><div id="editor_code-{{$loop->iteration}}" style="border-radius:5px;">{{$defi->solution}}</div></div>
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach

            </div>
        </div>
	</div><!-- /container -->

    <script src="{{ asset('js/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>
	<script>
        for (var i = 1; i <= {{ $defis->count() }}; i++) {
            editor_code = 'editor_code-' + i;
            editor_fakecode = 'editor_fakecode-' + i;
    		var editor_code = ace.edit(editor_code, {
    			theme: "ace/theme/puzzle_code",
    			mode: "ace/mode/python",
    			maxLines: 500,
    			fontSize: 14,
    			wrap: true,
    			useWorker: false,
                highlightActiveLine: false,
                highlightGutterLine: false,
    			showPrintMargin: false,
    			displayIndentGuides: true,
    			showLineNumbers: true,
    			showGutter: true,
    			showFoldWidgets: false,
    			useSoftTabs: true,
    			navigateWithinSoftTabs: false,
    			tabSize: 4,
                readOnly: true
    		});

            editor_code.container.style.lineHeight = 1.5;
        }
	</script>

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
