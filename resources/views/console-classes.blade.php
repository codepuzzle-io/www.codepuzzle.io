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
                <div class="mb-3 text-center" style="opacity:1.0"><a class="btn btn-light btn-sm d-block" href="{{route('console-classes')}}" role="button">{{__('CLASSES')}}</a></div>           
            </div>

			<div class="col-md-10 pl-4 pr-4">

				@if (session('status'))
					<div class="text-success text-monospace text-center pb-4" role="alert">
						{{ session('status') }}
					</div>
				@endif

                <?php
                $classes = App\Models\Classe::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
                ?>

                <form method="POST" action="{{route('classe-ajouter-console')}}" class="form-inline text-monospace mb-4">
                    @csrf
                    <div class="form-group">
                        <a class="btn btn-success btn-sm pl-3 pr-3 text-monospace" href="{{route('classe-creer-get')}}" role="button">{{__('nouvelle classe')}}</a>
                    </div>
                    <div class="form-group text-muted small">
                        <i class="fas fa-ellipsis-v ml-3 mr-3"></i> importer une classe existante <sup><i class="fas fa-info-circle ml-1 mr-1" data-toggle="tooltip" data-placement="top" title="{{__('si le lien secret de la classe est: "www.codepuzzle.io/classe-console/BAZU4DML3C", le code à saisir est "BAZU4DML3C".')}}"></i></sup>: <input name="jeton_secret" type="text" class="ml-1 mr-1 form-control form-control-sm" placeholder="code" />
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm mr-3"><i class="fas fa-check"></i></button>
                    @if ($errors->has('wrong_code'))
                        <span class="text-danger small">{{ $errors->first('wrong_code') }}</span>
                    @endif
                </form>

                @foreach($classes as $classe)
                    <div id="frame_{{$loop->iteration}}" class="frame">

                        <div class="row">

                            <div class="col-md-12 text-monospace text-muted">
                                <!-- options -->
    							<div style="float:right;">

                                    <a class='btn btn-dark btn-sm' href='/classe-console/{{ $classe->jeton_secret }}' role='button' data-toggle="tooltip" data-placement="top" title="{{__('voir')}}"><i class="fas fa-eye"></i></a>
               
                                    <!-- supprimer -->
                                    <span id="supprimer_button_{{$loop->iteration}}">
                                        <div onclick="showConfirm('supprimer_button_{{$loop->iteration}}', 'supprimer_confirm_{{$loop->iteration}}')" class="d-inline-block" type="button">
                                            <a tabindex='0' class='btn btn-light btn-sm' role='button'  style="cursor:pointer;outline:none;"><i class='fa-solid fa-xmark'></i></a>
                                        </div>
                                    </span>
                                    <span id="supprimer_confirm_{{$loop->iteration}}" style="display:none">
                                        <div id="supprimer_{{$loop->iteration}}" class="d-inline-block">
                                            <a href='/console/classe-supprimer/{{ Crypt::encryptString($classe->id) }}' class='btn btn-danger btn-sm text-light ml-3' role='button'  style="cursor:pointer;outline:none;" data-toggle="tooltip" data-placement="top" title="{{__('supprimer')}}"><i class='fas fa-trash fa-sm'></i></a>
                                        </div>
                                        <div id="supprimer_cancel_{{$loop->iteration}}" onclick="hideConfirm('supprimer_button_{{$loop->iteration}}', 'supprimer_confirm_{{$loop->iteration}}')" class="d-inline-block" type="button">
                                            <a tabindex='0' class='btn btn-light btn-sm' role='button' style="cursor:pointer;outline:none;" data-toggle="tooltip" data-placement="top" title="{{__('annuler')}}"><i class="fa-solid fa-chevron-right" ></i></a>
                                        </div>
                                    </span>
                                    <!-- /supprimer -->

    							</div>
    							<!-- /options -->

                                <h2 class="p-0 m-0">{{ $classe->nom_classe }}</h2>

                            </div>
                        </div>

                    </div>
                @endforeach

            </div>
        </div>
	</div><!-- /container -->

	@include('inc-bottom-js')

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

</body>
</html>
