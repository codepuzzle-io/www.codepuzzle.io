<!doctype html>
<html lang="fr">
<head>
	@include('inc-meta')
	@include('markdown/inc-markdown-css')
    <title>{{ config('app.name') }} | {{ ucfirst(__('programme')) }} | {{ ucfirst(__('modifier')) }}</title>
</head>
<body>

    @include('inc-nav-console')

	<?php
        $programme = App\Models\Programme::where([['user_id', Auth::id()], ['id', Crypt::decryptString($programme_id)]])->first();
    ?>

    <div class="container mt-4 mb-5">

		<div class="row">

            <div class="col-md-2 text-right pt-4">
				<a class="btn btn-light btn-sm" href="/console/programmes" role="button"><i class="fas fa-arrow-left"></i></a>
			</div>

            <div class="col-md-9 pt-4">

				<form method="POST" action="{{route('programme-modifier-post')}}">

					@csrf

					<!-- TITRE -->
					<div class="text-monospace">{{strtoupper(__('titre'))}}<sup class="text-danger small">*</sup></div>
					<div class="text-monospace text-muted small text-justify mb-1">{{__('Visible par vous seulement')}}</div>
					<input id="titre_enseignant" type="text" class="form-control @error('titre_enseignant') is-invalid @enderror" name="titre_enseignant" value="{{ old('titre_enseignant', $programme->titre_enseignant) }}" autofocus>
					@error('titre_enseignant')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
					<!-- /TITRE -->

					<!-- SOUS TITRE -->
					<div class="mt-4 text-monospace">{{strtoupper(__('sous-titre'))}} <span class="font-italic small" style="color:silver;">{{__('optionnel')}}</span></div>
					<div class="text-monospace text-muted small text-justify mb-1">{{__('Visible par vous seulement')}}</div>
					<input id="sous_titre_enseignant" type="text" class="form-control @error('sous_titre_enseignant') is-invalid @enderror" name="sous_titre_enseignant" value="{{ old('sous_titre_enseignant', $programme->sous_titre_enseignant) }}" autofocus>
					<!-- /SOUS TITRE -->

					<!-- TITRE ELEVE -->
					<div class="mt-4 text-monospace">{{strtoupper(__('titre élève'))}} <span class="font-italic small" style="color:silver;">{{__('optionnel')}}</span></div>
					<div class="text-monospace text-muted small text-justify mb-1">{{__('Visible par l élève')}}</div>
					<input id="titre_eleve" type="text" class="form-control @error('titre_eleve') is-invalid @enderror" name="titre_eleve" value="{{ old('titre_eleve', $programme->titre_eleve) }}" autofocus>
					<!-- /TITRE ELEVE -->

					<!-- NOTES PERSONNELLES -->
					<div class="mt-4 text-monospace">
						{{strtoupper(__('notes personnelles'))}}
						<i class="fas fa-info-circle" style="cursor:pointer;color:#e74c3c;opacity:0.5" data-toggle="modal" data-target="#markdown_help"></i>
					</div>
					<div class="text-monospace text-muted small text-justify mb-1">{{__('Visibles par vous seulement')}}</div>
					<textarea id="markdown_content" class="form-control" name="notes_personnelles" id="notes_personnelles" rows="6">{{ old('notes_personnelles', $programme->notes_personnelles) }}</textarea>
					<!-- /NOTES PERSONNELLES -->

					<!-- CODE --> 
					<div class="mt-4 text-monospace">{{strtoupper(__('code'))}}<sup class="text-danger small ml-1">*</sup></div>
					<textarea name="code" style="display:none;" id="code"></textarea>
					<div id="editor_code" class="@error('code') is-invalid @enderror" style="border-radius:5px;">{{old('code', $programme->code)}}</div>
					@error('code')
						<div class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</div>
					@enderror					
					<!-- /CODE --> 

                    <input type="hidden" name="programme_id" value="{{ $programme_id }}" />
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
