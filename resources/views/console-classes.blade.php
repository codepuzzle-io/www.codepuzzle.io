<!doctype html>
<html lang="fr">
<head>
    @include('inc-meta')
    <title>{{ config('app.name') }} | {{ ucfirst(__('console')) }}</title>
</head>
<body class="no-mathjax">
    @php
		$lang_switch = '<a href="/console/lang/fr" class="kbd mr-1">fr</a><a href="/console/lang/en" class="kbd">en</a>';
	@endphp
    @include('inc-nav-console')

    <?php
    include('lib/parsedownmath/ParsedownMath.php');
    $Parsedown = new ParsedownMath([
        'math' => [
            'enabled' => true, // Write true to enable the module
            'matchSingleDollar' => true // default false
        ]
    ]);
    ?>

	<div class="container mt-4 mb-5">

		<div class="row pt-3">

			<div class="col-md-2">

                <div class="text-right mb-3">
                    <a class="btn btn-light btn-sm" href="/console" role="button"><i class="fas fa-arrow-left"></i></a>
                </div>


                <a href="https://github.com/codepuzzle-io/www.codepuzzle.io/discussions" target="_blank" role="button" class="mt-2 btn btn-light btn-sm text-left text-muted" style="width:100%;opacity:0.8;">
                	<span style="font-size:80%"><i class="fas fa-comment-alt" style="float:left;margin:4px 8px 5px 0px;"></i> {{__('discussions')}} <span style="opacity:0.6;font-size:90%;">&</span> {{__('annonces')}}</span>
                </a>

                <a href="https://github.com/codepuzzle-io/www.codepuzzle.io/issues/new/choose" target="_blank" role="button"  class="mt-1 btn btn-light text-left btn-sm text-muted" style="width:100%;opacity:0.8;">
                	<span style="font-size:80%"><i class="fas fa-bug" style="float:left;margin:4px 8px 5px 0px;"></i> {{__('signalement de bogue')}} <span style="opacity:0.6;font-size:90%;">&</span> {{__('questions techniques')}}</span>
                </a>

                <div class="mt-3 text-muted text-monospace pl-1 mb-5" style="font-size:70%;opacity:0.8;">
                	<span><i class="fa fa-envelope"></i> contact@codepuzzle.io</span>
                </div>

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

                <form method="POST" action="{{route('classe-ajouter-console')}}" class="form-inline text-monospace mb-5">
                    @csrf
                    <div class="form-group">
                        <a class="btn btn-success btn-sm pl-3 pr-3 text-monospace" href="{{route('classe-creer-get')}}" role="button">{{__('nouvelle classe')}}</a>
                    </div>
                    <div class="form-group text-muted small">
                        <i class="fas fa-ellipsis-v ml-3 mr-3"></i> enregistrer une classe existante <sup><i class="fas fa-info-circle ml-1 mr-1" data-toggle="tooltip" data-placement="top" title="{{__('si le lien secret de la classe est: "www.codepuzzle.io/classe-console/BAZU4DML3C", le code à saisir est "BAZU4DML3C".')}}"></i></sup>: <input name="jeton_secret" type="text" class="ml-1 mr-1 form-control form-control-sm" placeholder="code" />
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
               
    								<a class='btn btn-light btn-sm' href='/classe-modifier/{{ $classe->jeton_secret }}' role='button' data-toggle="tooltip" data-placement="top" title="{{__('modifier')}}"><i class="fas fa-pen"></i></a>

                                    <a tabindex='0' id='/console/classe-supprimer/{{ Crypt::encryptString($classe->id) }}' class='btn btn-danger btn-sm text-light' role='button'  style="cursor:pointer;outline:none;" data-toggle="popover" data-trigger="focus" data-placement="left" data-html="true" data-content="<a href='/console/classe-supprimer/{{ Crypt::encryptString($classe->id) }}' class='btn btn-danger btn-sm text-light' role='button'>{{__('confirmer')}}</a><a class='btn btn-light btn-sm ml-2' href='#' role='button'>{{__('annuler')}}</a>"><i class='fas fa-trash fa-sm' data-toggle="tooltip" data-placement="top" data-offset="0, 15" title="{{__('supprimer')}}"></i></a>

    							</div>
    							<!-- /options -->

                                <h2 class="p-0 m-0 pt-1">{{ $classe->nom_classe }}</h2>

                            </div>
                        </div>

                    </div>
                @endforeach

            </div>
        </div>
	</div><!-- /container -->

	@include('inc-bottom-js')

</body>
</html>
