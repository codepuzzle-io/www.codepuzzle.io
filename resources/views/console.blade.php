<!doctype html>
<html lang="fr">
<head>
    @include('inc-meta')
    <title>Code Puzzle | Console</title>
</head>
<body>

    @include('inc-nav-console')

	<div class="container mt-4 mb-5">

		<div class="row pt-3">

			<div class="col-md-2">

                <a class="btn btn-primary btn-sm mb-4" href="{{route('code-creer-get')}}" role="button" style="width:100%;">nouveau puzzle</a>

                <a href="https://github.com/codepuzzle-io/www.codepuzzle.io/discussions" target="_blank" role="button" class="mt-3 btn btn-light btn-sm text-left text-muted" style="width:100%;opacity:0.8;">
                	<span style="font-size:80%"><i class="fas fa-comment-alt" style="float:left;margin:4px 8px 5px 0px;"></i> discussions <span style="opacity:0.6;font-size:90%;">&</span> annonces</span>
                </a>

                <a href="https://github.com/codepuzzle-io/www.codepuzzle.io/issues/new/choose" target="_blank" role="button"  class="mt-1 btn btn-light text-left btn-sm text-muted" style="width:100%;opacity:0.8;">
                	<span style="font-size:80%"><i class="fas fa-bug" style="float:left;margin:4px 8px 5px 0px;"></i> signalement de bogue <span style="opacity:0.6;font-size:90%;">&</span> questions techniques</span>
                </a>

                <div class="mt-3 text-muted text-monospace text-center" style="font-size:70%;opacity:0.8;">
                	<span><i class="fa fa-envelope"></i> contact@codepuzzle.io</span>
                </div>

            </div>

			<div class="col-md-10 pl-5 pr-5">

				@if (session('status'))
					<div class="text-success text-monospace text-center pb-4" role="alert">
						{{ session('status') }}
					</div>
				@endif

                <?php
                $codes = App\Models\Code::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
                ?>

                @foreach($codes as $code)
                    <div id="frame_{{$loop->iteration}}" class="frame">

                        <div class="row">

                            <div class="col-md-12 text-monospace text-muted">
                                <!-- options -->
    							<div style="float:right;">

                                    <a class='btn btn-light btn-sm' data-toggle="collapse" href="#collapse-{{$loop->iteration}}" role='button' aria-expanded="false" aria-controls="collapse-{{$loop->iteration}}" ><i class="fas fa-bars" style="margin-top:0px;" data-toggle="tooltip" data-placement="top" title="déplier / plier"></i></a>

    								<a class='btn btn-light btn-sm' href='/console/code-modifier/{{ Crypt::encryptString($code->id) }}' role='button'><i class="fas fa-pen" data-toggle="tooltip" data-placement="top" title="modifier"></i></a>

                                    <a tabindex='0' id='/console/code-supprimer/{{ Crypt::encryptString($code->id) }}' class='btn btn-danger btn-sm text-light' role='button'  style="cursor:pointer;outline:none;" data-toggle="popover" data-trigger="focus" data-placement="left" data-html="true" data-content="<a href='/console/code-supprimer/{{ Crypt::encryptString($code->id) }}' class='btn btn-danger btn-sm text-light' role='button'>confirmer</a><a class='btn btn-light btn-sm ml-2' href='#' role='button'>annuler</a>"><i class='fas fa-trash fa-sm' data-toggle="tooltip" data-placement="top" title="supprimer"></i></a>

    							</div>
    							<!-- /options -->

                                <h2 class="p-0 m-0">{{ $code->titre_enseignant }}</h2>
                                <div class="text-monospace small" style="color:silver;">{{ $code->sous_titre_enseignant }}</div>

                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-12 text-monospace small text-muted">
                                <i class="fas fa-share-alt ml-1 mr-1" style="cursor:help" data-toggle="tooltip" data-placement="top" title="lien à partager avec les élèves"></i> <a href="p/{{ strtoupper($code->jeton) }}" target="_blank" data-toggle="tooltip" data-placement="top" title="ouvrir ce puzzle dans un nouvel onglet pour le tester">https://www.codepuzzle.io/p/{{ strtoupper($code->jeton) }}</a>
                            </div>
                        </div>

                        <div class="collapse" id="collapse-{{$loop->iteration}}">
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="text-monospace text-muted mb-3 small">
                                        <i class="fas fa-share-alt ml-1 mr-1"></i> Code à insérer dans un site web
                                        <div class="mt-1" style="margin-left:22px;">
                                            <input class="form-control form-control-sm" type="text" value='<iframe src="https://www.codepuzzle.io/iframe/{{ strtoupper($code->jeton) }}" width="100%" height="600" frameborder="0"></iframe>' disabled readonly />
                                        </div>
                                        <p class="text-monospace mt-1" style="margin-left:22px;font-size:90%";color:silver>Remarque : ajuster la valeur de "height" en fonction de la taille du puzzle</p>
                                    </div>
                                    <div class="text-monospace text-muted mb-4 small">
                                        <i class="fas fa-share-alt ml-1 mr-1"></i> Code à insérer dans une cellule code d'un "notebook" Jupyter
                                        <div class="mt-1" style="margin-left:22px;">
                                            <textarea class="form-control form-control-sm" rows="2" disabled readonly>from IPython.display import IFrame
IFrame('https://www.codepuzzle.io/iframe/{{ strtoupper($code->jeton) }}', width='100%', height=600)</textarea>
                                        </div>
                                        <p class="text-monospace mt-1" style="margin-left:22px;font-size:90%";color:silver>Remarque : ajuster la valeur de "height" en fonction de la taille du puzzle</p>
                                    </div>
                                    @if ($code->titre_eleve !== NULL OR $code->consignes_eleve !== NULL)
                                        <div class="card card-body">
                                            @if ($code->titre_eleve !== NULL)
                                                <div class="text-monospace small mb-1">{{ $code->titre_eleve }}</div>
                                            @endif
                                            @if ($code->consignes_eleve !== NULL)
                                                <div class="text-monospace text-muted small consignes">
                                                    <?php
                                                    $Parsedown = new Parsedown();
                                                    echo $Parsedown->text($code->consignes_eleve);
                                                    ?>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    <div class="mt-3 text-monospace text-muted small">code</div>
                                    <div style="width:100%;margin:0px auto 0px auto;"><div id="editor_code-{{$loop->iteration}}" style="border-radius:5px;">{{$code->code}}</div></div>
                                    <div class="mt-3 text-monospace text-muted small">faux code</div>
                                    <div style="width:100%;margin:0px auto 0px auto;"><div id="editor_fakecode-{{$loop->iteration}}" style="border-radius:5px;">{{$code->fakecode}}</div></div>
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
        for (var i = 1; i <= {{ $codes->count() }}; i++) {
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

    		var editor_fakecode = ace.edit(editor_fakecode, {
    			theme: "ace/theme/puzzle_fakecode",
    			mode: "ace/mode/python",
    			maxLines: 500,
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
    		editor_fakecode.container.style.lineHeight = 1.5;
        }
	</script>

	@include('inc-bottom-js')

</body>
</html>
