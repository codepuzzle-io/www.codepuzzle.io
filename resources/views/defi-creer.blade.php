<!doctype html>
<html lang="fr">
<head>
	@include('inc-meta')
    <title>{{ config('app.name') }} | {{ ucfirst(__('nouveau défi')) }}</title>
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
							<td class="p-2">PARAGRAPHES</td>
							<td class="p-2 text-monospace text-muted">paragraphe<br /><br />paragraphe<p class="mt-2 mb-0" style="color:silver">Laisser une ligne vide pour marquer un nouveau paragraphe.</p></td>
							<td class="p-2" style="vertical-align:top"><p class="mb-1">paragraphe</p>paragraphe</td>
						</tr>
						<tr>
							<td class="p-2">RETOUR À LA LIGNE</td>
							<td class="p-2 text-monospace text-muted">ligne \<br />ligne<p class="mt-2 mb-0" style="color:silver">Ajouter un \ en bout de ligne pour forcer le retour à la ligne.</p></td>
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
								<p class="mb-1">Un <a href="http://pep8online.com/" target="_blank">lien</a> vers PEP8 online.</p>
								<p class="mb-0">Un lien vers <a href="http://pep8online.com/" target="_blank">PEP8 online</a>.</p>
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
				<a class="btn btn-light btn-sm" href="/console/defis" role="button"><i class="fas fa-arrow-left"></i></a>
				@else
				<a class="btn btn-light btn-sm" href="/" role="button"><i class="fas fa-arrow-left"></i></a>
				@endif
			</div>

			<div class="col-md-10 pl-4 pr-4">

				<h1>{{__('nouveau défi')}}</h1>

				<form method="POST" action="{{route('defi-creer-post')}}">

					@csrf
				
					<!-- TITRE -->
					@if(Auth::check())
					<div class="text-monospace">{{strtoupper(__('titre'))}}<sup class="text-danger small">*</sup></div>
					<div class="text-monospace text-muted small text-justify mb-1">{{__('Visible par vous seulement')}}</div>
					<input id="titre_enseignant" type="text" class="form-control @error('titre_enseignant') is-invalid @enderror" name="titre_enseignant" value="{{ old('titre_enseignant') }}" autofocus>
					@error('titre_enseignant')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
					@endif
					<!-- /TITRE -->

					<!-- SOUS TITRE -->
					@if(Auth::check())
					<div class="mt-4 text-monospace">{{strtoupper(__('sous-titre'))}} <span class="font-italic small" style="color:silver;">{{__('optionnel')}}</span></div>
					<div class="text-monospace text-muted small text-justify mb-1">{{__('Visible par vous seulement')}}</div>
					<input id="sous_titre_enseignant" type="text" class="form-control @error('sous_titre_enseignant') is-invalid @enderror" name="sous_titre_enseignant" value="{{ old('sous_titre_enseignant') }}" autofocus>
					@endif
					<!-- /SOUS TITRE -->

					<!-- TITRE ELEVE -->
					@if(Auth::check())
					<div class="mt-4 text-monospace">{{strtoupper(__('titre élève'))}} <span class="font-italic small" style="color:silver;">{{__('optionnel')}}</span></div>
					<div class="text-monospace text-muted small text-justify mb-1">{{__('Visible par l élève')}}</div>
					<input id="titre_eleve" type="text" class="form-control @error('titre_eleve') is-invalid @enderror" name="titre_eleve" value="{{ old('titre_eleve') }}" autofocus>
					@endif
					<!-- /TITRE ELEVE -->

					<!-- CONSIGNES -->
					<div class="mt-4 text-monospace">
						{{strtoupper(__('consignes'))}}<sup class="text-danger small">*</sup>
						<i class="fas fa-info-circle" style="cursor:pointer;color:#e74c3c;opacity:0.5" data-toggle="modal" data-target="#markdown_help"></i>
					</div>
					@if(Auth::check())
					<div class="text-monospace text-muted small text-justify mb-1">{{__('Consignes pour l élève')}}</div>
					@endif
					<textarea class="form-control @error('consignes_eleve') is-invalid @enderror" name="consignes_eleve" id="consignes_eleve" rows="6">{{ old('consignes_eleve') }}</textarea>
					@error('consignes_eleve')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror


					<!-- TESTS -->
					<a id="tests_anchor"></a>
					<div class="mt-4 text-monospace">{{strtoupper(__('tests'))}}</div>
					<table id="tests_table">
						<tr>
							<td class="text-monospace text-muted small text-justify mb-1 w-50">{{__('Condition')}}<sup class="text-danger small">*</sup></td>
							<td class="text-monospace text-muted small text-justify mb-1 w-50">{{__('Message')}}</td>
							<td class="pl-4"></td>
						</tr>
						<tr>
							<td class="align-top"><input type="text" class="form-control @error('condition.0') is-invalid @enderror" name="condition[]" value="{{old('condition.0')}}" autofocus></td>
							<td class="align-top"><input type="text" class="form-control" name="description[]" value="{{old('description.0')}}" autofocus></td>
							<td></td>
						</tr>					
						@if (!empty(old('condition')))
						@foreach(old('condition') as $key => $condition)
						@if ($key !== 0)
							<tr>
								<td><input type="text" class="form-control" name="condition[]" value="{{$condition}}" autofocus></td>
								<td><input type="text" class="form-control" name="description[]" value="{{old('description')[$key]}}" autofocus></td>
								<td><a href="#tests_anchor" onclick="removeTest(this)"><i class="ml-2 fas fa-trash" aria-hidden="true"></i></a></td>
							</tr>
						@endif
						@endforeach
						@endif
					</table>
					<a id="add_button" class="btn btn-light btn-sm mt-1" href="#tests_anchor" role="button"><i class="fas fa-plus"></i></a>		
					<!-- /TESTS -->

					<!-- CODE --> 
					<div class="mt-4 text-monospace">{{strtoupper(__('code'))}} <span class="font-italic small" style="color:silver;">{{__('optionnel')}}</span></div>
					<div class="text-monospace text-muted small text-justify mb-1">{{__('Ce code sera proposé comme point de départ du défi')}}</div>
					<textarea name="code" style="display:none;" id="code"></textarea>
					<div id="editor_code" style="border-radius:5px;">{{old('code')}}</div>
					<!-- /CODE --> 

					<!-- SOLUTION --> 
					@if(Auth::check())
					<div class="mt-4 text-monospace">{{strtoupper(__('solution possible'))}} <span class="font-italic small" style="color:silver;">{{__('optionnel')}}</span></div>
					<div class="text-monospace text-muted small text-justify mb-1">{{__('Cette soluton possible ne sera pas publiée. Elle sert seulement de référence.')}}</div>
					<textarea name="solution" style="display:none;" id="solution"></textarea>
					<div id="editor_solution" style="border-radius:5px;">{{old('solution')}}</div>
					@endif
					<!-- /SOLUTION --> 					

					<!-- OPTIONS -->
					<div class="mt-4 text-monospace">OPTIONS</div>
					<?php
					$with_chrono_checked = (old('with_chrono') !== null) ? "checked" : "";
					$with_nbverif_checked = (old('with_nbverif') !== null) ? "checked" : "";
					$with_message_checked = (old('with_message') !== null) ? "checked" : "";
					$with_console_checked = (old('with_console') !== null) ? "checked" : "";
					?>
					<div class="form-check">
						<input class="form-check-input" name="with_chrono" type="checkbox" value="0" id="with_chrono" {{$with_chrono_checked}} />
						<label class="form-check-label text-monospace text-muted small" for="with_chrono">{{__('ne pas afficher le chronomètre')}}</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" name="with_nbverif" type="checkbox" id="with_nbverif" {{$with_nbverif_checked}} />
						<label class="form-check-label text-monospace text-muted small" for="with_nbverif">{{__('ne pas afficher le nombre de vérifications')}}</label>
					</div>		
					<div class="form-check">
						<input class="form-check-input" name="with_message" type="checkbox" id="with_message" {{$with_message_checked}} />
						<label class="form-check-label text-monospace text-muted small" for="with_message">{{__('ne pas afficher les messages des tests')}}</label>
					</div>	
					<div class="form-check">
						<input class="form-check-input" name="with_console" type="checkbox" id="with_console" {{$with_console_checked}} />
						<label class="form-check-label text-monospace text-muted small" for="with_console">{{__('ne pas afficher la console')}}</label>
					</div>													
					<!-- /OPTIONS -->

					<input id="lang" type="hidden" name="lang" value="{{app()->getLocale()}}" />

					<button type="submit" class="btn btn-primary mt-4 mb-5 pl-4 pr-4"><i class="fas fa-check"></i></button>
					
				</form>

			</div>

		</div><!-- row -->

	</div><!-- container -->

	@include('inc-bottom-js')

	<script src="{{ asset('js/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>
	<script>
		// Chargement de ace et initialisation des éditeurs.
		var editor_code;
		
		async function init_editors() {
			editor_code = ace.edit("editor_code", {
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
			editor_code.container.style.lineHeight = 1.5;
			editor_code.getSession().on('change', function () {
				document.getElementById('code').value = editor_code.getSession().getValue();
			});
			document.getElementById('code').value = editor_code.getSession().getValue();

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

	<script>

		const table = document.getElementById('tests_table');
		function addTest() {
			removeButton = document.createElement('a');
			removeButton.href = "#tests_anchor";
			removeButton.innerHTML = '<i class="ml-2 fas fa-trash"></i>';	
			newRow = table.insertRow();
			newCell1 = newRow.insertCell();
			newCell2 = newRow.insertCell();
			newCell3 = newRow.insertCell();
			newCell1.innerHTML = '<input type="text" class="form-control" name="condition[]" />';
			newCell2.innerHTML = '<input type="text" class="form-control" name="description[]" />';
			newCell3.appendChild(removeButton);		
			removeButton.addEventListener('click', function() {
				this.parentNode.parentNode.remove();
			});
		}
		function removeTest(tag) {
			tag.parentNode.parentNode.remove();
		}
		document.getElementById('add_button').addEventListener('click', addTest);
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
