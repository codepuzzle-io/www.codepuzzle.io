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
                <div class="mb-1 text-center" style="opacity:1.0"><a class="btn btn-light btn-sm d-block" href="{{route('console-sujets')}}" role="button">{{__('SUJETS')}}</a></div>
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
                $sujets = App\Models\Sujet::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
                ?>

                <form method="POST" action="" class="form-inline text-monospace mb-4">
                    @csrf
                    <div class="form-group">
                        <a class="btn btn-success btn-sm pl-3 pr-3 text-monospace" href="{{route('sujet-creer-get')}}" role="button">{{__('nouveau sujet')}}</a>
                    </div>
                    <div class="form-group text-muted small">
                        <i class="fas fa-ellipsis-v ml-3 mr-3"></i> importer un sujet existant <sup><i class="fas fa-info-circle ml-1 mr-1" data-toggle="tooltip" data-placement="top" title="{{__('si le lien secret du sujet est: "www.codepuzzle.io/sujet-console/BAZU4DML3C", le code à saisir est "BAZU4DML3C".')}}"></i></sup>: <input name="jeton_secret" type="text" class="ml-1 mr-1 form-control form-control-sm" placeholder="code" />
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm mr-3"><i class="fas fa-check"></i></button>
                    @if ($errors->has('wrong_code'))
                        <span class="text-danger small">{{ $errors->first('wrong_code') }}</span>
                    @endif
                </form>

                @foreach($sujets as $sujet)
                    <div id="frame_{{$loop->iteration}}" class="frame pb-3">

                        <div class="row">

                            <div class="col-md-12 text-monospace text-muted">
                                <!-- options -->
    							<div style="float:right;">

                                    <a class='btn btn-dark btn-sm' href='/sujet-console/{{ $sujet->jeton_secret }}' role='button' data-toggle="tooltip" data-placement="top" title="{{__('voir / corriger')}}"><i class="fas fa-check"></i></a>
               
                                    <a class='btn btn-light btn-sm' data-toggle="collapse" href="#collapse-{{$loop->iteration}}" role='button' aria-expanded="false" aria-controls="collapse-{{$loop->iteration}}"><i class="fas fa-bars" style="margin-top:0px;" data-toggle="tooltip" data-offset="0, 9" data-placement="top" title="{{__('déplier plier')}}"></i></a>

    								<a class='btn btn-light btn-sm' href='/sujet-creer/{{ $sujet->jeton_secret }}' role='button' data-toggle="tooltip" data-placement="top" title="{{__('modifier')}}"><i class="fas fa-pen"></i></a>

                                    <a tabindex='0' id='/console/sujet-supprimer/{{ Crypt::encryptString($sujet->id) }}' class='btn btn-danger btn-sm text-light' role='button'  style="cursor:pointer;outline:none;" data-toggle="popover" data-trigger="focus" data-placement="left" data-html="true" data-content="<a href='/console/sujet-supprimer/{{ Crypt::encryptString($sujet->id) }}' class='btn btn-danger btn-sm text-light' role='button'>{{__('confirmer')}}</a><a class='btn btn-light btn-sm ml-2' href='#' role='button'>{{__('annuler')}}</a>"><i class='fas fa-trash fa-sm' data-toggle="tooltip" data-placement="top" data-offset="0, 15" title="{{__('supprimer')}}"></i></a>

    							</div>
    							<!-- /options -->

                                <h2 class="p-0 m-0">
                                    @if ($sujet->titre_enseignant == NULL)
                                        Devoir {{$sujet->jeton_secret}}
                                    @else
                                    {{$sujet->titre_enseignant}}
                                    @endif
                                </h2>
                                <div class="text-monospace small" style="color:silver;">{{ $sujet->sous_titre_enseignant }}</div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-monospace small text-muted">
                                <i class="fas fa-share-alt ml-1 mr-2"></i>lien élèves: <a href="/{{ strtoupper('E'.$sujet->jeton) }}" target="_blank">www.codepuzzle.io/E{{ strtoupper($sujet->jeton) }}</a>
                            </div>
                        </div>

                        <div class="collapse" id="collapse-{{$loop->iteration}}">
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    @if ($sujet->titre_eleve !== NULL OR $sujet->consignes_eleve !== NULL)
                                        <div class="pt-3 pl-3 pr-3 pb-1" style="background-color:#f3f5f7;border-radius:5px;">
                                            @if ($sujet->titre_eleve !== NULL)
                                                <div class="text-monospace mb-1 font-weight-bold">{{ $sujet->titre_eleve }}</div>
                                            @endif
                                            @if ($sujet->consignes_eleve !== NULL)
                                                <div class="markdown_content border rounded bg-light text-monospace p-3">{{ $sujet->consignes_eleve }}</div>
                                            @endif
                                        </div>
                                    @endif
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
