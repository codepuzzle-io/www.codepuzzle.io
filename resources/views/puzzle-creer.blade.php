<!doctype html>
<html lang="fr">
<head>
	@include('inc-meta')
	@include('markdown/inc-markdown-css')
    <title>{{ config('app.name') }} | {{ ucfirst(__('nouveau puzzle')) }}</title>
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
				<a class="btn btn-light btn-sm" href="/console/puzzles" role="button"><i class="fas fa-arrow-left"></i></a>
				@else
					<a class="btn btn-light btn-sm" href="/" role="button"><i class="fas fa-arrow-left"></i></a>
					<div class="mt-3 small text-monospace text-muted">Vous pouvez <a href="/creer-un-compte" target="_blank">créer un compte</a> pour regrouper, gérer et partager vos puzzles.</div>
				@endif				
			</div>

			<div class="col-md-10 pl-4 pr-4">

				<h1>{{__('nouveau puzzle')}}</h1>

				<form method="POST" action="{{route('puzzle-creer-post')}}">

					@csrf

					<!-- TITRE -->
					@if(Auth::check())
					<div class="text-monospace">{{strtoupper(__('titre'))}}<sup class="text-danger small">*</sup></div>
					<div class="text-monospace text-muted small text-justify mb-1">{{__('Visible par vous seulement')}}</div>
					<input id="titre_enseignant" type="text" class="form-control @error('titre_enseignant') is-invalid @enderror" name="titre_enseignant" value="{{ old('titre_enseignant') }}" autofocus>
					@error('titre_enseignant')
						<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
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
						{{strtoupper(__('consignes'))}} <span class="font-italic small" style="color:silver;">{{__('optionnel')}}</span>
					</div>
					@if(Auth::check())
					<div class="text-monospace text-muted small text-justify mb-1">{{__('Consignes pour l élève')}}</div>
					@endif
					<textarea id="markdown_content" class="form-control" name="consignes_eleve" rows="4">{{ old('consignes_eleve') }}</textarea>
					<!-- /CONSIGNES -->
					
					<!-- CODE -->
					<div class="mt-4 text-monospace">{{strtoupper(__('code'))}}<sup class="text-danger small">*</sup></div>
					<div class="text-monospace text-muted small text-justify mb-2 p-2" style="border:solid 1px silver;border-radius:4px;">
						{{strtoupper(__('Syntaxe pour code à trous'))}}<br />
						{{__('Code à compléter')}}: [?code?]</br>
						{{__('Choix multiples')}}: {{__('[?code_correct?distracteur1?distracteur2?distracteur3?]')}}
					</div>
					<textarea name="code" style="display:none;" id="code"></textarea>
					<div id="editor_code" style="border-radius:5px;">{{ old('code') }}</div>
					@error('code')
						<span class="invalid-feedback d-block" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
					<!-- /CODE -->

					<!-- FAUX CODE -->
					<div class="mt-4 text-monospace">{{strtoupper(__('faux code'))}} <span class="font-italic small" style="color:silver;">{{__('optionnel')}}</span></div>
					<div class="text-monospace text-muted small text-justify mb-1">
						{{__('Vous pouvez ajouter de fausses lignes de code qui seront mélangées aux lignes de code du code ci-dessus mais qui seront considérées comme des lignes inutiles qui ne doivent pas être placées dans le code final.')}}
					</div>
					<textarea name="fakecode" style="display:none;" id="fakecode"></textarea>
					<div id="editor_fakecode" style="border-radius:5px;">{{ old('fakecode') }}</div>
					<!-- /FAUX CODE -->

					<!-- OPTIONS -->
					<div class="mt-4 text-monospace">OPTIONS</div>
					<?php
					$with_dragdrop_checked = (old('with_dragdrop') !== null) ? "checked" : "";
					$with_chrono_checked = (old('with_chrono') !== null) ? "checked" : "";
					$with_nbverif_checked = (old('with_nbverif') !== null) ? "checked" : "";
					?>
					<div class="form-check">
						<input class="form-check-input" name="with_dragdrop" type="checkbox" id="with_dragdrop" {{$with_dragdrop_checked}} />
						<label class="form-check-label text-monospace text-muted small" for="with_dragdrop">{{__('mode "glisser-déposer" même si le puzzle ne comporte pas de fausses lignes de code')}}</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" name="with_chrono" type="checkbox" id="with_chrono" {{$with_chrono_checked}} />
						<label class="form-check-label text-monospace text-muted small" for="with_chrono">{{__('ne pas afficher le chronomètre')}}</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" name="with_nbverif" type="checkbox" id="with_nbverif" {{$with_nbverif_checked}} />
						<label class="form-check-label text-monospace text-muted small" for="with_nbverif">{{__('ne pas afficher le nombre de vérifications')}}</label>
					</div>				
					<!-- /OPTIONS -->					

					<input id="lang" type="hidden" name="lang" value="{{app()->getLocale()}}" />

					<button type="submit" class="btn btn-primary mt-4 pl-4 pr-4 mb-5"><i class="fas fa-check"></i></button>

				</form>

			</div>

		</div><!-- row -->

	</div><!-- container -->

	@include('inc-bottom-js')
	@include('markdown/inc-markdown-editeur-js')

	<script src="{{ asset('js/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>
	<script>
		var editor_code = ace.edit("editor_code", {
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

		var editor_fakecode = ace.edit("editor_fakecode", {
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

		editor_code.container.style.lineHeight = 1.5;
		editor_fakecode.container.style.lineHeight = 1.5;

		var textarea_code = $('#code');
		editor_code.getSession().on('change', function () {
			textarea_code.val(editor_code.getSession().getValue());
		});
		textarea_code.val(editor_code.getSession().getValue());

		var textarea_fakecode = $('#fakecode');
		editor_fakecode.getSession().on('change', function () {
			textarea_fakecode.val(editor_fakecode.getSession().getValue());
		});
		textarea_fakecode.val(editor_fakecode.getSession().getValue());

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
