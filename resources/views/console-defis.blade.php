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
                $defis = App\Models\Defi::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
                ?>

                <div class="mb-5 text-monospace">
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

                                    <a tabindex='0' id='/console/defi-supprimer/{{ Crypt::encryptString($defi->id) }}' class='btn btn-danger btn-sm text-light' role='button'  style="cursor:pointer;outline:none;" data-toggle="popover" data-trigger="focus" data-placement="left" data-html="true" data-content="<a href='/console/defi-supprimer/{{ Crypt::encryptString($defi->id) }}' class='btn btn-danger btn-sm text-light' role='button'>{{__('confirmer')}}</a><a class='btn btn-light btn-sm ml-2' href='#' role='button'>{{__('annuler')}}</a>"><i class='fas fa-trash fa-sm' data-toggle="tooltip" data-placement="top" data-offset="0, 15" title="{{__('supprimer')}}"></i></a>

    							</div>
    							<!-- /options -->

                                <h2 class="p-0 m-0">{{ $defi->titre_enseignant }}</h2>
                                <div class="text-monospace small" style="color:silver;">{{ $defi->sous_titre_enseignant }}</div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-monospace small text-muted">
                                <i class="fas fa-share-alt ml-1 mr-1" style="cursor:help" data-toggle="tooltip" data-placement="top" title="{{__('lien à partager avec les élèves')}}"></i> <a href="/{{ strtoupper('D'.$defi->jeton) }}" target="_blank" data-toggle="tooltip" data-placement="top" title="{{__('ouvrir ce défi dans un nouvel onglet pour le tester')}}">www.codepuzzle.io/D{{ strtoupper($defi->jeton) }}</a>
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
                                        <div class="pt-3 pl-3 pr-3 pb-1" style="background-color:#f3f5f7;border-radius:5px;">
                                            @if ($defi->titre_eleve !== NULL)
                                                <div class="text-monospace mb-1 font-weight-bold">{{ $defi->titre_eleve }}</div>
                                            @endif
                                            @if ($defi->consignes_eleve !== NULL)
                                                <div class="text-monospace text-muted mathjax consignes">
                                                    {!! $Parsedown->text($defi->consignes_eleve) !!}
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    <div class="mt-3 text-monospace text-muted small">{{__('réponse possible')}}</div>
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
