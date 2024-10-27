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
                <div class="mb-1 text-center" style="opacity:1.0"><a class="btn btn-light btn-sm d-block" href="{{route('console-devoirs')}}" role="button">{{__('DEVOIRS')}}</a></div>
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
                $devoirs = App\Models\Devoir::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
                ?>

                <form method="POST" action="{{route('devoir-ajouter-console')}}" class="form-inline text-monospace mb-5">
                    @csrf
                    <div class="form-group">
                        <a class="btn btn-success btn-sm pl-3 pr-3 text-monospace" href="{{route('sujet-creer-get')}}" role="button">{{__('nouveau sujet')}}</a>
                    </div>
                    <div class="form-group text-muted small">
                        <i class="fas fa-ellipsis-v ml-3 mr-3"></i> importer un devoir existant <sup><i class="fas fa-info-circle ml-1 mr-1" data-toggle="tooltip" data-placement="top" title="{{__('si le lien secret du devoir est: "www.codepuzzle.io/devoir-console/BAZU4DML3C", le code à saisir est "BAZU4DML3C".')}}"></i></sup>: <input name="jeton_secret" type="text" class="ml-1 mr-1 form-control form-control-sm" placeholder="code" />
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm mr-3"><i class="fas fa-check"></i></button>
                    @if ($errors->has('wrong_code'))
                        <span class="text-danger small">{{ $errors->first('wrong_code') }}</span>
                    @endif
                </form>

                @foreach($devoirs as $devoir)
                    <div id="frame_{{$loop->iteration}}" class="frame pb-3">

                        <div class="row">

                            <div class="col-md-12 text-monospace text-muted">

                                <!-- options -->
    							<div style="float:right;">

                                    <a class='btn btn-dark btn-sm' href='/devoir-console/{{ $devoir->jeton_secret }}' role='button' data-toggle="tooltip" data-placement="top" title="{{__('voir / corriger')}}"><i class="fas fa-eye"></i></a>
               
                                    <!-- supprimer -->
                                    <span id="supprimer_button_{{$loop->iteration}}">
                                        <div onclick="showConfirm('supprimer_button_{{$loop->iteration}}', 'supprimer_confirm_{{$loop->iteration}}')" class="d-inline-block" type="button">
                                            <a tabindex='0' class='btn btn-light btn-sm' role='button'  style="cursor:pointer;outline:none;"><i class='fa-solid fa-xmark'></i></a>
                                        </div>
                                    </span>
                                    <span id="supprimer_confirm_{{$loop->iteration}}" style="display:none">
                                        <div id="supprimer_{{$loop->iteration}}" class="d-inline-block">
                                            <a href='/console/devoir-supprimer/{{ Crypt::encryptString($devoir->id) }}' class='btn btn-danger btn-sm text-light ml-3' role='button'  style="cursor:pointer;outline:none;" data-toggle="tooltip" data-placement="top" title="{{__('supprimer')}}"><i class='fas fa-trash fa-sm'></i></a>
                                        </div>
                                        <div id="supprimer_cancel_{{$loop->iteration}}" onclick="hideConfirm('supprimer_button_{{$loop->iteration}}', 'supprimer_confirm_{{$loop->iteration}}')" class="d-inline-block" type="button">
                                            <a tabindex='0' class='btn btn-light btn-sm' role='button' style="cursor:pointer;outline:none;" data-toggle="tooltip" data-placement="top" title="{{__('annuler')}}"><i class="fa-solid fa-chevron-right" ></i></a>
                                        </div>
                                    </span>
                                    <!-- /supprimer -->

    							</div>
    							<!-- /options -->

                                <h2 class="p-0 m-0">
                                    @if ($devoir->titre_enseignant == NULL)
                                        Devoir {{$devoir->jeton_secret}}
                                    @else
                                        {{$devoir->titre_enseignant}}
                                    @endif
                                </h2>
                                @if ($devoir->sous_titre_enseignant !== NULL)
                                    <div class="text-monospace small" style="color:silver;">{{ $devoir->sous_titre_enseignant }}</div>
                                @endif

                                <div class="row mt-1" style="clear:both;">
                                    <div class="col-md-12 text-monospace text-muted">
                                        <span class="small"><i class="fas fa-share-alt ml-1 mr-2"></i>lien élèves: </span><a id="lien_{{$loop->iteration}}" href="/{{ strtoupper('E'.$devoir->jeton) }}" target="_blank">www.codepuzzle.io/E{{ strtoupper($devoir->jeton) }}</a><span class="pl-2" onclick="copier('lien_{{$loop->iteration}}')" style="cursor:pointer;"><i class="fa-regular fa-copy"></i></span><span id="lien_{{$loop->iteration}}_copie_confirmation" class="pl-3 text-right small text-monospace text muted">&nbsp;</span>
                                    </div>
                                </div>                           

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

</body>
</html>
