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
                $devoirs = App\Models\Devoir::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
                ?>

                <form method="POST" action="{{route('devoir-ajouter-console')}}" class="form-inline text-monospace mb-5">
                    @csrf
                    <div class="form-group">
                        <a class="btn btn-success btn-sm pl-3 pr-3 text-monospace" href="{{route('devoir-creer-get')}}" role="button">{{__('nouveau devoir')}}</a>
                    </div>
                    <div class="form-group text-muted small">
                        <i class="fas fa-ellipsis-v ml-3 mr-3"></i> enregistrer un devoir existant <sup><i class="fas fa-info-circle ml-1 mr-1"></i></sup>: <input name="jeton_secret" type="text" class="ml-1 mr-1 form-control form-control-sm" placeholder="code" />
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

                                    <a class='btn btn-dark btn-sm' href='/devoir-console/{{ $devoir->jeton_secret }}' role='button' data-toggle="tooltip" data-placement="top" title="{{__('devoirs')}}"><i class="fas fa-check"></i></a>
               
                                    <a class='btn btn-light btn-sm' data-toggle="collapse" href="#collapse-{{$loop->iteration}}" role='button' aria-expanded="false" aria-controls="collapse-{{$loop->iteration}}"><i class="fas fa-bars" style="margin-top:0px;" data-toggle="tooltip" data-offset="0, 9" data-placement="top" title="{{__('déplier plier')}}"></i></a>

    								<a class='btn btn-light btn-sm' href='/devoir-creer/{{ $devoir->jeton_secret }}' role='button' data-toggle="tooltip" data-placement="top" title="{{__('modifier')}}"><i class="fas fa-pen"></i></a>

                                    <a tabindex='0' id='/console/defi-supprimer/{{ Crypt::encryptString($devoir->id) }}' class='btn btn-danger btn-sm text-light' role='button'  style="cursor:pointer;outline:none;" data-toggle="popover" data-trigger="focus" data-placement="left" data-html="true" data-content="<a href='/console/defi-supprimer/{{ Crypt::encryptString($devoir->id) }}' class='btn btn-danger btn-sm text-light' role='button'>{{__('confirmer')}}</a><a class='btn btn-light btn-sm ml-2' href='#' role='button'>{{__('annuler')}}</a>"><i class='fas fa-trash fa-sm' data-toggle="tooltip" data-placement="top" data-offset="0, 15" title="{{__('supprimer')}}"></i></a>

    							</div>
    							<!-- /options -->

                                <h2 class="p-0 m-0">
                                    @if ($devoir->titre_enseignant == NULL)
                                        Devoir {{$devoir->jeton_secret}}
                                    @else
                                    {{$devoir->titre_enseignant}}
                                    @endif
                                </h2>
                                <div class="text-monospace small" style="color:silver;">{{ $devoir->sous_titre_enseignant }}</div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-monospace small text-muted">
                                <i class="fas fa-share-alt ml-1 mr-2"></i>lien élèves: <a href="/{{ strtoupper('E'.$devoir->jeton) }}" target="_blank">www.codepuzzle.io/E{{ strtoupper($devoir->jeton) }}</a>
                            </div>
                        </div>

                        <div class="collapse" id="collapse-{{$loop->iteration}}">
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    @if ($devoir->titre_eleve !== NULL OR $devoir->consignes_eleve !== NULL)
                                        <div class="pt-3 pl-3 pr-3 pb-1" style="background-color:#f3f5f7;border-radius:5px;">
                                            @if ($devoir->titre_eleve !== NULL)
                                                <div class="text-monospace mb-1 font-weight-bold">{{ $devoir->titre_eleve }}</div>
                                            @endif
                                            @if ($devoir->consignes_eleve !== NULL)
                                                <div class="text-monospace text-muted mathjax consignes">
                                                    {!! $Parsedown->text($devoir->consignes_eleve) !!}
                                                </div>
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

    <script>
		MathJax = {
			tex: {
				inlineMath: [['$', '$'], ['\\(', '\\)']],
				displayMath: [ ['$$','$$'], ["\\[","\\]"] ],
				processEscapes: true
			},
			options: {
				ignoreHtmlClass: "no-mathjax",
				processHtmlClass: "mathjax"
			}
		};        
	</script>  
	<script type="text/javascript" id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-svg.js"></script> 

	@include('inc-bottom-js')

</body>
</html>
