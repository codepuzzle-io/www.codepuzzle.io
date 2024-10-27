<?php
if (isset($jeton_secret)) {
	$sujet = App\Models\Sujet::where('jeton_secret', $jeton_secret)->first();
	if (!$sujet) {
		echo "<pre>Ce sujet n'existe pas.</pre>";
		exit();
	} else {
		$titre_enseignant = $sujet->titre_enseignant;
		$sous_titre_enseignant = $sujet->sous_titre_enseignant;
		$titre_eleve = $sujet->titre_eleve;
		$consignes = $sujet->consignes_eleve;

		if ($sujet->user_id !== 0 && (!Auth::check() || (Auth::check() && Auth::id() !== $sujet->user_id))) {
			echo "<pre>Vous ne pouvez pas accéder à ce sujet.</pre>";
			exit();
		}
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

	<!-- MODAL MARKDOWN HELP -->
	<div class="modal fade" id="markdown_help" tabindex="-1" aria-labelledby="markdown_helpLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<b class="modal-title" id="exampleModalLabel">Formatage du texte</b>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<table class="table table-bordered table-hover small">
						<tr>
							<td></td>
							<td class="p-2 text-center">SYNTAXE</td>
							<td class="p-2 text-center">RENDU</td>
						</tr>
						<tr>
							<td class="p-2">CODE DANS DU TEXTE</td>
							<td class="p-2 text-monospace text-muted">Écrire la fonction `puissance(a, n)`.</td>
							<td class="p-2" style="vertical-align:top">Écrire la fonction <code>puissance(a, n)</code>.</td>
						</tr>		
						<tr>
							<td class="p-2">BLOC DE CODE</td>
							<td class="p-2 text-monospace text-muted">Soit la fonction:<br />```<br />def carre(n):<br />&nbsp;&nbsp;&nbsp;&nbsp;return n**2<br />```</td>
							<td class="p-2" style="vertical-align:top">Soit la fonction:<pre>def carre(n):<br />&nbsp;&nbsp;&nbsp;&nbsp;return n**2<br /></pre></td>
						</tr>					
						<tr>
							<td class="p-2">PARAGRAPHES</td>
							<td class="p-2 text-monospace text-muted">paragraphe<br /><br />paragraphe<p class="mt-2 mb-0" style="color:silver">Laisser une ligne vide pour marquer un nouveau paragraphe.</p></td>
							<td class="p-2" style="vertical-align:top"><p class="mb-1">paragraphe</p>paragraphe</td>
						</tr>
						<tr>
							<td class="p-2">RETOUR À LA LIGNE</td>
							<td class="p-2 text-monospace text-muted">ligne &lt;br&gt;<br />ligne<p class="mt-2 mb-0" style="color:silver">Ajouter &lt;br&gt; en bout de ligne pour forcer le retour à la ligne.</p></td>
							<td class="p-2" style="vertical-align:top">ligne<br />ligne</td>
						</tr>
						<tr>
							<td class="p-2">LISTES</td>
							<td class="p-2 text-monospace text-muted">* point 1<br />* point 2<br /></td>
							<td class="p-2" style="vertical-align:top"><ul style="padding-left:20px;margin-left:0;margin-bottom:0"><li>point 1</li><li>point 2</li></ul></td>
						</tr>
						<tr>
							<td class="p-2">ITALIQUE</td>
							<td class="p-2 text-monospace text-muted">*italique*</td>
							<td class="p-2"><em>italique</em></td>
						</tr>
						<tr>
							<td class="p-2">GRAS</td>
							<td class="p-2 text-monospace text-muted">**gras**</td>
							<td class="p-2"><b>gras</b></td>
						</tr>
						<tr>
							<td class="p-2">SOULIGNÉ</td>
							<td class="p-2 text-monospace text-muted">__souligné__</td>
							<td class="p-2"><u>souligné</u></td>
						</tr>
						<tr>
							<td class="p-2">IMAGE</td>
							<td class="p-2 text-monospace text-muted">
								<p>![](url-image)</p>
								<p class="mb-0"><i>Exemple : ![](https://www.codepuzzle.io/img/codepuzzle.png)<i></p>
							</td>
							<td class="p-2"><img src="https://www.codepuzzle.io/img/codepuzzle.png" width="160"/></td>
						</tr>
						<tr>
							<td class="p-2">LIEN</td>
							<td class="p-2 text-monospace text-muted">
								<p>[texte-cliquable](url-site)</p>
								<p class="mb-1"><i>Exemple 1 : Un [lien](https://eduscol.education.fr) vers Eduscol.</i></p>
								<p class="mb-0"><i>Exemple 2 : Un lien vers [Eduscol](https://eduscol.education.fr).</i></p>
							</td>
							<td class="p-2">
								<p><br /></p>
								<p class="mb-1">Un <a href="http://pep8online.com/" target="_blank">lien</a> vers Eduscol.</p>
								<p class="mb-0">Un lien vers <a href="http://pep8online.com/" target="_blank">Eduscol</a>.</p>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
	<!-- MODAL MARKDOWN HELP -->



	<div class="container mt-4 mb-5">

		<div class="row">

			<div class="col-md-2 text-right">
				@if(Auth::check())
					<a class="btn btn-light btn-sm" href="/console/devoirs" role="button"><i class="fas fa-arrow-left"></i></a>
				@else
					@if (isset($jeton_secret))
						<a class="btn btn-light btn-sm" href="/sujet-console/{{ $jeton_secret }}" role="button"><i class="fas fa-arrow-left"></i></a>
					@else
						<a class="btn btn-light btn-sm" href="/" role="button"><i class="fas fa-arrow-left"></i></a>
					@endif
					<div class="mt-3 small text-monospace text-muted">Vous pouvez <a href="/creer-un-compte" target="_blank">créer un compte</a> pour regrouper, gérer et partager vos sujets.</div>
				@endif
			</div>

			<div class="col-md-10 pl-4 pr-4">

				<h1>{{__('nouveau sujet')}}</h1>

					<div class="nav nav-pills mb-3 text-monospace" id="pills-tab" role="tablist">
						<button class="btn btn-dark btn-sm mr-1" id="pills-exo-tab" data-toggle="pill" data-target="#pills-exo" type="button" role="tab" aria-controls="pills-exo" aria-selected="false">Exercice Python</button>
						<button class="btn btn-dark btn-sm ml-1" id="pills-pdf-tab" data-toggle="pill" data-target="#pills-pdf" type="button" role="tab" aria-controls="pills-pdf" aria-selected="false">Sujet au format PDF</button>
					</div>

					<div class="tab-content" id="pills-tabContent">

						<!-- EXERCICE PYTHON -->
						<div id="pills-exo" class="tab-pane fade active" role="tabpanel" aria-labelledby="pills-exo-tab">

							<div class="text-center mt-4 mb-3"><span style="display:inline-block;padding:12px 0px 0px 0px;height:60px;width:60px;background-color:#D9ED92;border-radius:50%;"><i class="fa-brands fa-python" style="font-size:35px;color:#79C824"></i></span></div>

							<form method="POST" action="{{route('sujet-creer-post')}}">

								@csrf

								<!-- TITRE -->
								<div class="text-monospace">{{strtoupper(__('titre'))}}<sup class="text-danger small">*</sup></div>
								<input id="titre_enseignant" type="text" class="form-control @error('titre_enseignant') is-invalid @enderror" name="titre_enseignant" value="{{ old('titre_enseignant') ?? $titre_enseignant ?? '' }}" autofocus>
								@error('titre_enseignant')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
								<!-- /TITRE -->		

								@if (isset($jeton_sujet))

									<div class="mt-4 text-monospace">{{strtoupper(__('sujet'))}}</div>
									@if ($sujet->sujet_type == 'pdf')
										<div class="row no-gutters mt-1 mb-4">
											<div class="col">
												<iframe id="sujet_pdf" src="{{Storage::url('SUJETS/PDF/'.$sujet->jeton.'.pdf')}}" width="100%" height="400" style="border: none;" class="rounded"></iframe>
											</div>
										</div>  
									@endif
									<input type="hidden" name="sujet" value="{{$sujet->jeton}}" />

								@else

									<!-- CONSIGNES -->
									<div class="mt-4 text-monospace">
										{{strtoupper(__('consignes'))}}<sup class="text-danger small">*</sup>
										<i class="fas fa-info-circle" style="cursor:pointer;color:#e74c3c;opacity:0.5" data-toggle="modal" data-target="#markdown_help"></i>
									</div>
									<textarea class="form-control @error('consignes_eleve') is-invalid @enderror" name="consignes_eleve" id="consignes_eleve" rows="6">{{ old('consignes_eleve') ?? $consignes ?? '' }}</textarea>
									@error('consignes_eleve')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
									<!-- /CONSIGNES -->


							





									
									<!-- CODE ELEVE --> 
									<div class="mt-4 text-monospace">{{strtoupper(__("code ÉlÈve"))}} <span class="font-italic small" style="color:silver;">{{__("optionnel")}}</span></div>
									<div class="text-monospace text-muted small text-justify mb-1">{{__("Ce code sera proposé à l'élève comme point de départ de l'entraînement.")}}</div>
									<textarea name="code_eleve" style="display:none;" id="code_eleve"></textarea>
									<div id="editor_code_eleve" style="border-radius:5px;">{{ old('code_eleve') ?? $code_eleve ?? '' }}</div>
									<!-- /CODE ELEVE -->

									<!-- CODE ENSEIGNANT --> 
									<div class="mt-4 text-monospace">{{strtoupper(__("code enseignant"))}} <span class="font-italic small" style="color:silver;">{{__("optionnel")}}</span></div>
									<div class="text-monospace text-muted small text-justify mb-1">{{__("Pour les enseignants seulement. Vous pouvez y placer un jeu de tests par exemple. Ce code pourra être exécuté en même temps que celui de l'élève ou seul pendant l'évaluation de l'entraînement quand l'entraînement apparaîtra dans la console de l'enseignant.")}}</div>
									<textarea name="code_enseignant" style="display:none;" id="code_enseignant"></textarea>
									<div id="editor_code_enseignant" style="border-radius:5px;">{{ old('code_enseignant') ?? $code_enseignant ?? '' }}</div>
									<!-- /CODE ENSEIGNANT -->

									<!-- SOLUTION --> 
									<div class="mt-4 text-monospace">{{strtoupper(__('solution possible'))}} <span class="font-italic small" style="color:silver;">{{__("optionnel")}}</span></div>
									<div class="text-monospace text-muted small text-justify mb-1">{{__("Pour les enseignants seulement. Cette soluton possible sert seulement de référence.")}}</div>
									<textarea name="solution" style="display:none;" id="solution"></textarea>
									<div id="editor_solution" style="border-radius:5px;">{{ old('solution') ?? $solution ?? '' }}</div>
									<!-- /SOLUTION --> 	

								@endif				


								<input id="lang" type="hidden" name="lang" value="{{app()->getLocale()}}" />

								@if (isset($jeton_secret))
									<input type="hidden" name="jeton_secret" value="{{$jeton_secret}}" />
								@endif

								<button type="submit" class="btn btn-primary mt-4 mb-5 pl-4 pr-4"><i class="fas fa-check"></i></button>

							</form>





						</div>

						<!-- SUJET PDF -->
						<div id="pills-pdf" class="tab-pane fade mb-5"  role="tabpanel" aria-labelledby="pills-pdf-tab">

							<div class="text-center mt-4 mb-3"><span style="padding:12px 0px 0px 8px;;display:inline-block;height:60px;width:60px;background-color:#D9ED92;border-radius:50%;"><i class="fa-regular fa-file-pdf" style="font-size:35px;color:#79C824;vertical-align:middle;"></i></span></div>

							<form method="POST" action="{{route('sujet-creer-post')}}">

								@csrf

								<!-- TITRE -->
								<div class="text-monospace">{{strtoupper(__('titre'))}}<sup class="text-danger small">*</sup></div>
								<input id="titre_enseignant" type="text" class="form-control @error('titre_enseignant') is-invalid @enderror" name="titre_enseignant" value="{{ old('titre_enseignant') ?? $titre_enseignant ?? '' }}" autofocus>
								@error('titre_enseignant')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
								<!-- /TITRE -->

								<!-- CONSIGNES -->
								<div class="mt-4 text-monospace">{{strtoupper(__('consignes'))}} <i class="fas fa-info-circle" style="cursor:pointer;color:#e74c3c;opacity:0.5" data-toggle="modal" data-target="#markdown_help"></i> <span class="font-italic small" style="color:silver;">{{__('optionnel')}}</span></div>

								<div class="text-monospace text-muted small text-justify mb-1">{{__('Le contenu de ce champ optionnel apparaîtra juste au-dessus du sujet.')}}</div>
								<textarea class="form-control @error('consignes_eleve') is-invalid @enderror" name="consignes_eleve" id="consignes_eleve" rows="6">{{ old('consignes_eleve') ?? $consignes ?? '' }}</textarea>
								@error('consignes_eleve')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
								<!-- /CONSIGNES -->

								<!-- FICHIER PDF -->
								<div class="mt-4 text-monospace">{{strtoupper(__('FICHIER PDF'))}}<sup class="text-danger small">*</sup></div>
								<!-- dropzone -->
								<div id="dropzonepdf" class="dropzone text-monospace"></div>
								<!-- FICHIER PDF -->

								<button type="submit" id="dropzone_submit" class="btn btn-primary mt-4 mb-5 pl-4 pr-4" disabled><i class="fas fa-check"></i></button>

							</form>

						</div>
					</div>




		
		</div><!-- /row -->
	</div><!-- /container -->

	@include('inc-bottom-js')

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

    <script src="https://cdn.jsdelivr.net/npm/dompurify/dist/purify.min.js"></script>
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
            element: document.getElementById('consignes_eleve'),
            autofocus: true,
			minHeight: "200px",
			spellChecker: false,
			hideIcons: ["heading", "quote"],
            showIcons: ["code", "undo", "redo", "table"],
			previewRender: (plainText) => DOMPurify.sanitize(marked.parse(plainText)),
            autosave: {
                enabled: true,
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/min/dropzone.min.js"></script>
    <script>
        // Désactiver l'auto-découverte de Dropzone
        Dropzone.autoDiscover = false;

        // Initialiser Dropzone quand le DOM est chargé
        document.addEventListener('DOMContentLoaded', function() {
            var dropzonepdf = new Dropzone("#dropzonepdf", {
                url: "/sujet-creer",
                paramName: "sujet_pdf",
                autoProcessQueue: false,
                uploadMultiple: false, // uplaod files in a single request
                maxFilesize: 10, // MB
                maxFiles: 1,
                
                previewTemplate: `
                    <div class="dz-preview dz-file-preview">
                        <div class="dz-details">
                            <div class="dz-filename" data-dz-name></div>
                            <div class="dz-size" data-dz-size></div>
                        </div>
                        <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
                        <div class="dz-error-message" data-dz-errormessage></div>
                        <div id="dz-remove" class="dz-remove" data-dz-remove></div>
                    </div>
                `,
                addRemoveLinks: true,
                //disablePreviews:true,
                createImageThumbnails: false,
                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                acceptedFiles: ".pdf",
                // Language Strings
                dictFileTooBig: "Erreur: 10 Mb maximum",
                dictInvalidFileType: "Erreur: format invalide",
                dictCancelUpload: "annuler",
                dictRemoveFile: "supprimer",
                dictDefaultMessage: "déposer le fichier PDF ici ou <span class='btn btn-outline-secondary btn-sm'>parcourir</span>",
            });
        });

        // Configuration des options de Dropzone
        Dropzone.options.dropzonepdf = {
            init: function() {
                var dz = this;

                document.getElementById("dropzone_submit").addEventListener("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dz.processQueue();
                });
                
                this.on('addedfile', function(file) {
                    document.getElementById("dropzonepdf").style.borderColor = "#79C824";
                    document.querySelector('a.dz-remove').addEventListener("click", function(e) {
                        document.getElementById("dropzonepdf").style.borderColor="#2980b9";
                        document.getElementById("dropzone_submit").disabled = true;                        
                    });
                    document.getElementById("dropzone_submit").disabled = false
                    if (this.files.length > 1) {
                        this.removeFile(this.files[0]);
                    }
                });

                //send all the form data along with the files:
                    this.on("sending", function(data, xhr, formData) {
                    //formData.append("sujet_id", "{{ Crypt::encryptString('xx') }}");
                    //formData.append("sujet_jeton", "");
                });                

                // envoi reussi
                this.on("success", function(files, response) {
                    console.log("DZ SUCCESS");
                    //window.location = "/sujet-console/";
                });

                // erreur
                this.on("error", function(files, response) {
                    console.log("DZ ERROR");
                    document.getElementById("dropzonepdf").style.borderColor = "red";
                    document.getElementById("dropzone_submit").disabled = true;
                    document.querySelector('a.dz-remove').addEventListener("click", function(e) {
                        document.getElementById("dropzonepdf").style.borderColor="#2980b9";                        
                    }); 
                });
            }
        };
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
