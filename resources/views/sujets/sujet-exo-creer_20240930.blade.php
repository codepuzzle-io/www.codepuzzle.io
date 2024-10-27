<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (isset($sujet_id)) {
    $sujet = App\Models\Sujet::find(Crypt::decryptString($sujet_id));
	if (!isset($sujet)) {
		echo "<pre>Ce sujet n'existe pas.</pre>";
		exit();
	} else {
		if ($sujet->user_id !== 0 && (!Auth::check() || (Auth::check() && Auth::id() !== $sujet->user_id))) {
			echo "<pre>Vous ne pouvez pas accéder à ce sujet.</pre>";
			exit();
		}    
		$sujet_json = json_decode($sujet->sujet);
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
	@include('inc-meta')
	<link href="{{ asset('css/highlight.css') }}" rel="stylesheet">
	<link href="{{ asset('css/easymde.css') }}" rel="stylesheet">
	<link href="{{ asset('css/easymde-custom.css') }}" rel="stylesheet">
	<link href="{{ asset('css/dropzone-basic.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dropzone.css') }}" rel="stylesheet">
    <title>SUJET | CRÉER / MODIFIER</title>
</head>
<body>

	@if(Auth::check())
		@include('inc-nav-console')
	@else
		@include('inc-nav')
	@endif

	<div class="container mt-4 mb-5">

		<div class="row">

			<div class="col-md-2 text-right mt-1">
				@if(Auth::check())
					<a class="btn btn-light btn-sm" href="/console/sujets" role="button"><i class="fas fa-arrow-left"></i></a>
				@else
					@if (isset($jeton_secret))
						<a class="btn btn-light btn-sm" href="/sujet-console/{{ $jeton_secret }}" role="button"><i class="fas fa-arrow-left"></i></a>
					@else
						<a class="btn btn-light btn-sm" href="/sujet-creer" role="button"><i class="fas fa-arrow-left"></i></a>
					@endif
					<div class="mt-3 small text-monospace text-muted">Vous pouvez <a href="/creer-un-compte" target="_blank">créer un compte</a> pour regrouper, gérer et partager vos sujets.</div>
				@endif
			</div>

			<div class="col-md-10 pl-4 pr-4">

				<h1 class="mb-0">{{__('sujet')}}</h1>
				<div class="mb-4 text-muted">Exercice Python</div>

				<form method="POST" action="{{route('sujet-exo-creer-post')}}">

					@csrf

					<!-- TITRE -->
					<div class="text-monospace">{{strtoupper(__('titre'))}}<sup class="ml-1 text-danger small">*</sup></div>
					<input id="titre" type="text" class="form-control @error('titre') is-invalid @enderror" name="titre" value="{{ old('titre') ?? $sujet->titre ?? '' }}" autofocus>
					@error('titre')
						<div class="mt-1 text-monospace text-danger" style="font-size:70%">{{ $message }}</div>
					@enderror
					<!-- /TITRE -->		

					<!-- ÉNONCÉ -->
					<div class="mt-4 text-monospace">{{strtoupper(__('ÉNONCÉ'))}}<sup class="ml-1 text-danger small">*</sup></div>
					<textarea class="form-control @error('enonce') is-invalid @enderror" name="enonce" id="enonce" rows="10">{{ old('enonce') ?? $sujet_json->enonce ?? '' }}</textarea>
					@error('enonce')
						<div class="mt-1 text-monospace text-danger" style="font-size:70%">{{ $message }}</div>
					@enderror
					<!-- /ÉNONCÉ -->

					<!-- CODE ELEVE --> 
					<div class="mt-4 text-monospace">{{strtoupper(__("code ÉlÈve"))}} <span class="font-italic small" style="color:silver;">{{__("optionnel")}}</span></div>
					<div class="text-monospace text-muted small text-justify mb-1">{{__("Ce code sera proposé à l'élève comme point de départ.")}}</div>
					<textarea name="code_eleve" style="display:none;" id="code_eleve"></textarea>
					<div id="editor_code_eleve" style="border-radius:5px;">{{ old('code_eleve') ?? $sujet_json->code_eleve ?? '' }}</div>
					<!-- /CODE ELEVE -->

					<!-- CODE ENSEIGNANT --> 
					<div class="mt-4 text-monospace">{{strtoupper(__("code enseignant"))}} <span class="font-italic small" style="color:silver;">{{__("optionnel")}}</span></div>
					<div class="text-monospace text-muted small text-justify mb-1">{{__("Pour les enseignants seulement. Vous pouvez y placer un jeu de tests par exemple. Ce code pourra être exécuté en même temps que celui de l'élève ou seul pendant l'évaluation de l'entraînement quand l'entraînement apparaîtra dans la console de l'enseignant.")}}</div>
					<textarea name="code_enseignant" style="display:none;" id="code_enseignant"></textarea>
					<div id="editor_code_enseignant" style="border-radius:5px;">{{ old('code_enseignant') ?? $sujet_json->code_enseignant ?? '' }}</div>
					<!-- /CODE ENSEIGNANT -->

					<!-- SOLUTION --> 
					<div class="mt-4 text-monospace">{{strtoupper(__('solution possible'))}} <span class="font-italic small" style="color:silver;">{{__("optionnel")}}</span></div>
					<div class="text-monospace text-muted small text-justify mb-1">{{__("Pour les enseignants seulement. Cette soluton possible sert seulement de référence.")}}</div>
					<textarea name="solution" style="display:none;" id="solution"></textarea>
					<div id="editor_solution" style="border-radius:5px;">{{ old('solution') ?? $sujet_json->solution ?? '' }}</div>
					<!-- /SOLUTION --> 						

                    @if(isset($sujet))
                        <input id="sujet_id" type="hidden" name="sujet_id" value="{{Crypt::encryptString($sujet->id)}}" />
                    @endif

					@if(isset($dupliquer))
                        <input id="dupliquer" type="hidden" name="dupliquer" value="true" />
                    @endif

					<button type="submit" class="btn btn-primary mt-4 mb-5 pl-4 pr-4"><i class="fas fa-check"></i></button>

				</form>

			</div>			

		</div><!-- /row -->
	</div><!-- /container -->

	@include('inc-bottom-js')

	<script src="https://cdn.jsdelivr.net/npm/dompurify/dist/purify.min.js"></script>

	<script>
        MathJax = {
			tex: {
				inlineMath: [["$","$"]], 
				displayMath: [["$$","$$"]], 
			},
			svg: {
				fontCache: 'global'
			}
        };
    </script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/python.min.js"></script>

	<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
	<script>
		marked.use({
			tokenizer: {
				//space() { return undefined },
				//code() { return undefined },
				//fences() { return undefined },
				heading() { return undefined },
				//hr() { return undefined },
				blockquote() { return undefined },
				//list() { return undefined },
				html() { return undefined },
				//def() { return undefined },
				//table() { return undefined },
				lheading() { return undefined },
				//paragraph() { return undefined },
				//text() { return undefined },
				//escape() { return undefined },
				tag() { return undefined },
				//link() { return undefined },
				reflink() { return undefined },
				//emStrong() { return undefined },
				//codespan() { return undefined },
				//br() { return undefined },
				//del() { return undefined }, // texte barré
				autolink() { return undefined },
				//url() { return undefined },
				//inlineText() { return undefined },           
			},      
		}) 	
	</script>

	<script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
    <script>
        const markdown_editor = new EasyMDE({
            element: document.getElementById('enonce'),
            autofocus: true,
			minHeight: "300px",
			spellChecker: false,
			hideIcons: ["heading", "quote"],
            showIcons: ["code", "undo", "redo", "table"],
			status: false,
			//previewRender: (plainText) => DOMPurify.sanitize(marked.parse(plainText)),
			previewRender: (plainText, preview) => { // Async method
				setTimeout(() => {
					// Remplacement des doubles slashes par des triples dans les blocs LaTex
					var plainText2 = plainText.replace(/\$\$(.+?)\$\$/gs, function(match) {
						return match.replace(/\\\\/g, '\\\\\\\\');
					});
					preview.innerHTML = DOMPurify.sanitize(marked.parse(plainText2));
					MathJax.typeset()
				}, 10);
				return "...";
    		},
            autosave: {
                enabled: false,
                uniqueId: "MyUniqueID",
                delay: 1000,
                submit_delay: 5000,
                timeFormat: {
                    locale: 'en-US',
                    format: {
                        year: 'numeric',
                        month: 'long',
                        day: '2-digit',
                        hour: '2-digit',
                        minute: '2-digit',
                    },
                },
                text: "> "
            },
            renderingConfig: {			
                singleLineBreaks: false,
                codeSyntaxHighlighting: true,
                sanitizerFunction: (renderedHTML) => {
                    return DOMPurify.sanitize(renderedHTML)
                },
            },
        });
    </script>

	<script src="{{ asset('js/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>
	<script>
			// Chargement de ace et initialisation des éditeurs.
			var editor_code;
			
			async function init_editors() {
				editor_code_eleve = ace.edit("editor_code_eleve", {
					theme: "ace/theme/puzzle_code",
					mode: "ace/mode/python",
					maxLines: 500,
					minLines: 4,
					fontSize: 14,
					wrap: true,
					useWorker: false,
					autoScrollEditorIntoView: true,
					highlightActiveLine: false,
					highlightSelectedWord: false,
					highlightGutterLine: true,
					showPrintMargin: false,
					displayIndentGuides: true,
					showLineNumbers: true,
					showGutter: true,
					showFoldWidgets: false,
					useSoftTabs: true,
					navigateWithinSoftTabs: false,
					tabSize: 4
				});
				editor_code_eleve.container.style.lineHeight = 1.5;
				editor_code_eleve.getSession().on('change', function () {
					document.getElementById('code_eleve').value = editor_code_eleve.getSession().getValue();
				});
				document.getElementById('code_eleve').value = editor_code_eleve.getSession().getValue();


				editor_code_enseignant = ace.edit("editor_code_enseignant", {
					theme: "ace/theme/puzzle_code",
					mode: "ace/mode/python",
					maxLines: 500,
					minLines: 4,
					fontSize: 14,
					wrap: true,
					useWorker: false,
					autoScrollEditorIntoView: true,
					highlightActiveLine: false,
					highlightSelectedWord: false,
					highlightGutterLine: true,
					showPrintMargin: false,
					displayIndentGuides: true,
					showLineNumbers: true,
					showGutter: true,
					showFoldWidgets: false,
					useSoftTabs: true,
					navigateWithinSoftTabs: false,
					tabSize: 4
				});
				editor_code_enseignant.container.style.lineHeight = 1.5;
				editor_code_enseignant.getSession().on('change', function () {
					document.getElementById('code_enseignant').value = editor_code_enseignant.getSession().getValue();
				});
				document.getElementById('code_enseignant').value = editor_code_enseignant.getSession().getValue();			


				var editor_solution;
				editor_solution = ace.edit("editor_solution", {
					theme: "ace/theme/puzzle_fakecode",
					mode: "ace/mode/python",
					maxLines: 500,
					minLines: 4,
					fontSize: 14,
					wrap: true,
					useWorker: false,
					autoScrollEditorIntoView: true,
					highlightActiveLine: false,
					highlightSelectedWord: false,
					highlightGutterLine: true,
					showPrintMargin: false,
					displayIndentGuides: true,
					showLineNumbers: true,
					showGutter: true,
					showFoldWidgets: false,
					useSoftTabs: true,
					navigateWithinSoftTabs: false,
					tabSize: 4
				});
				editor_solution.container.style.lineHeight = 1.5;
				editor_solution.getSession().on('change', function () {
					document.getElementById('solution').value = editor_solution.getSession().getValue();
				});
				document.getElementById('solution').value = editor_solution.getSession().getValue();
			}
			(async function() {
				// Chargement asynchrone de ace et initialisation des éditeurs
				const editors_initialized_promise = init_editors();
				// Pour être sur que ace est chargé et les éditeurs initialisés.
				await editors_initialized_promise;		
			})();	
		</script>

	<?php
	/*
	   IMPORTANT
	   To add 20px on top and bottom :
	   - modify .ace_gutter {padding-top: 20px;} and .ace_scroller {top: 20px;} see custom.css
	   - modify ace.js line 18642 "(this.$extraHeight || 0)" to "(this.$extraHeight || 30)"
	   OTHER MOFDIFICATION
	   Add margin left and right > change line 18074 "this.setPadding(20);" instead "this.setPadding(4);"
	*/
	?>

</body>
</html>
