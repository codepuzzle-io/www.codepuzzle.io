<!doctype html>
<html lang="fr">
<head>
	@include('inc-meta')
	@include('markdown/inc-markdown-css')
    <title>{{ config('app.name') }} | {{ ucfirst(__('nouveau défi')) }}</title>
</head>
<body>

	@if(Auth::check())
		@include('inc-nav-console')
	@else
		@include('inc-nav')
	@endif

	<div class="container mt-4 mb-5">

		<div class="row">

			<div class="col-md-2 text-right">
				@if(Auth::check())
				<a class="btn btn-light btn-sm" href="/console/defis" role="button"><i class="fas fa-arrow-left"></i></a>
				@else
				<a class="btn btn-light btn-sm" href="/" role="button"><i class="fas fa-arrow-left"></i></a>
				<div class="mt-3 small text-monospace text-muted">Vous pouvez <a href="/creer-un-compte" target="_blank">créer un compte</a> pour regrouper, gérer et partager vos défis.</div>
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
					<textarea id="markdown_content" class="form-control @error('consignes_eleve') is-invalid @enderror" name="consignes_eleve" id="consignes_eleve" rows="20">{{ old('consignes_eleve') }}</textarea>
					@error('consignes_eleve')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
					<!-- /CONSIGNES -->


					<!-- TESTS -->
					<a id="tests_anchor"></a>
					<div class="mt-4 text-monospace">{{strtoupper(__('tests'))}}</div>
					<div><span class="text-monospace text-muted small text-justify mb-1 font-weight-bold">Code pré-tests</span><span class="pl-2 text-monospace font-italic small" style="color:silver;">{{__('optionnel')}}</span></div>
					<div class="text-monospace text-muted small text-justify mb-1">Ce code ne sera pas affiché</div>

					<textarea name="code_pre_tests" style="display:none;" id="code_pre_tests"></textarea>
					<div id="editor_pretests" style="border-radius:5px;">{{old('code_pre_tests')}}</div>

					<table id="tests_table" class="mt-3">
						<tr>
							<td></td>
							<td class="text-monospace text-muted small text-justify mb-1 w-50 font-weight-bold">{{__('condition')}}<sup class="text-danger small">*</sup></td>
							<td></td>
							<td class="text-monospace text-muted small text-justify mb-1 w-50 font-weight-bold">{{__('message')}}</td>
							<td class="pl-4"></td>
						</tr>
						<tr>
							<td><span class="text-monospace small" style="color:#af00db">assert&nbsp;</span></td>
							<td class="align-top pr-1"><input type="text" class="form-control @error('condition.0') is-invalid @enderror" name="condition[]" value="{{old('condition.0')}}" autofocus></td>
							<td><span class="text-monospace small">,&nbsp;</span></td>
							<td class="align-top"><input type="text" class="form-control" name="description[]" value="{{old('description.0')}}" autofocus></td>
							<td></td>
						</tr>					
						@if (!empty(old('condition')))
						@foreach(old('condition') as $key => $condition)
						@if ($key !== 0)
							<tr>
								<td><span class="text-monospace small" style="color:#af00db">assert&nbsp;</span></td>
								<td class="align-top pr-1"><input type="text" class="form-control" name="condition[]" value="{{$condition}}" autofocus></td>
								<td><span class="text-monospace small">,&nbsp;</span></td>
								<td class="align-top"><input type="text" class="form-control" name="description[]" value="{{old('description')[$key]}}" autofocus></td>
								<td><a href="#tests_anchor" onclick="removeTest(this)"><i class="ml-2 fas fa-trash" aria-hidden="true"></i></a></td>
							</tr>
						@endif
						@endforeach
						@endif
					</table>
					<div style="padding-left:47px;"><a id="add_button" class="btn btn-light btn-sm mt-1" href="#tests_anchor" role="button"><i class="fas fa-plus"></i></a></div>	
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
					<div class="text-monospace text-muted small text-justify mb-1">{{__('Cette soluton possible ne sera pas affichée. Elle sert seulement de référence.')}}</div>
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
	@include('markdown/inc-markdown-editeur-js')

	<script src="{{ asset('js/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>
	<script>
		// Chargement de ace et initialisation des éditeurs.
		async function init_editors() {
			
			// CODE ELEVE
			var editor_code;
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

			// PRE TEST
			var editor_pretests;
			editor_pretests = ace.edit("editor_pretests", {
				theme: "ace/theme/chrome",
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
			editor_pretests.container.style.lineHeight = 1.5;
			editor_pretests.getSession().on('change', function () {
				document.getElementById('code_pre_tests').value = editor_pretests.getSession().getValue();
			});
			document.getElementById('code_pre_tests').value = editor_pretests.getSession().getValue();

			// SOLUTION
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
			newCell4 = newRow.insertCell();
			newCell5 = newRow.insertCell();
			newCell2.classList.add("align-top");
			newCell2.classList.add("pr-1");
			newCell4.classList.add("align-top");
			newCell1.innerHTML = '<span class="text-monospace small" style="color:#af00db">assert&nbsp;</span>';
			newCell2.innerHTML = '<input type="text" class="form-control" name="condition[]" />';
			newCell3.innerHTML = '<span class="text-monospace small">,&nbsp;</span>';
			newCell4.innerHTML = '<input type="text" class="form-control" name="description[]" />';
			newCell5.appendChild(removeButton);		
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
