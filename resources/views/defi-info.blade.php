<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    @php
        $description = 'Défi - ' . strtoupper($defi_jeton);
        $description_og = '| Défi - ' . strtoupper($defi_jeton);
    @endphp
    @include('inc-meta')
    <title>{{ config('app.name') }} | Défi - {{strtoupper($defi_jeton)}}</title>
</head>
<body>

    @include('inc-nav')

    <?php
    $defi = App\Models\Defi::where('jeton', $defi_jeton)->first();
    $lang = ($defi->lang == 'fr') ? '/':'/en';
    ?>

	<div class="container mt-5 mb-5">

        <div class="text-monospace font-weight-bolder text-center" style="font-size:1.5em;color:silver">
            <a href="/D{{ strtoupper($defi->jeton) }}" target="_blank">www.codepuzzle.io/D{{ strtoupper($defi->jeton) }}</a>
        </div>
        <div class="text-center mt-2 mb-4">
            <img src="https://api.qrserver.com/v1/create-qr-code/?data={{urlencode('https://www.codepuzzle.io/D' . strtoupper($defi->jeton))}}&amp;size=100x100" style="width:100px" alt="www.codepuzzle.io/D{{strtoupper($defi->jeton)}}" data-toggle="tooltip" data-placement="right" title="{{__('clic droit + Enregistrer l image sous... pour sauvegarder l image')}}" />
        </div>

		<div class="row pt-3">

			<div class="col-md-2">

                <div class="text-right"><a class="btn btn-light btn-sm mb-4" href="{{$lang}}" role="button"><i class="fas fa-arrow-left"></i></a></div>

                <a class="btn btn-success btn-sm mb-4 text-monospace" href="{{route('defi-creer-get')}}" role="button" style="width:100%;">{{__('créer un nouveau défi')}}</a>

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

			<div class="col-md-10 pl-3 pr-3">

                <div id="frame" class="frame">

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
                                <p class="text-monospace mt-1" style="margin-left:22px;font-size:90%";color:silver>{{__('Remarque : ajuster la valeur de "height" en fonction de la taille du defi')}}</p>
                            </div>

                            @if ($defi->titre !== NULL)
                            <div class="row">
                                <div class="col-md-12 text-monospace text-muted">
                                    <h2 class="p-0 mt-4">{{ $defi->titre }}</h2>
                                </div>
                            </div>
                            @endif

                            @if ($defi->consignes !== NULL)
                                <div class="card card-body">
                                    <div class="text-monospace text-muted small consignes">
                                        <?php
                                        $Parsedown = new Parsedown();
                                        echo $Parsedown->text($defi->consignes);
                                        ?>
                                    </div>
                                </div>
                            @endif
                            <div class="mt-3 text-monospace text-muted small">{{__('solution possible')}}</div>
                            <div style="width:100%;margin:0px auto 0px auto;"><div id="editor_code" style="border-radius:5px;">{{$defi->code}}</div></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
	</div><!-- /container -->

    <script src="{{ asset('js/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>
	<script>
        editor_code = 'editor_code';
        editor_fakecode = 'editor_fakecode';
		var editor_code = ace.edit(editor_code, {
			theme: "ace/theme/puzzle_code",
			mode: "ace/mode/python",
			maxLines: 500,
			fontSize: 12,
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

	</script>

	@include('inc-bottom-js')

</body>
</html>
