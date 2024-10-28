<!doctype html>
<html lang="fr">
<head>
	@include('inc-meta')
	@include('markdown/inc-markdown-css')
    <title>{{ config('app.name') }} | Puzzle | {{ ucfirst(__('modifier')) }}</title>
</head>
<body>

    @include('inc-nav-console')
	
    <div class="container mt-4 mb-5">

		<div class="row">

            <div class="col-md-2 text-right pt-4">
				<a class="btn btn-light btn-sm" href="/console/puzzles" role="button"><i class="fas fa-arrow-left"></i></a>
			</div>

            <div class="col-md-9 pt-4">

				<form method="POST" action="{{route('puzzle-modifier-post')}}">

					@csrf

                    <?php
                    $puzzle = App\Models\Puzzle::where([['user_id', Auth::id()], ['id', Crypt::decryptString($puzzle_id)]])->first();
                    ?>

					<!-- TITRE -->
					<div class="text-monospace">TITRE<sup class="text-danger small">*</span></div>
					<div class="text-monospace text-muted small text-justify mb-1">Visible par vous seulement</div>
					<input id="titre_enseignant" type="text" class="form-control @error('titre_enseignant') is-invalid @enderror" name="titre_enseignant" value="{{ old('titre_enseignant', $puzzle->titre_enseignant) }}" autofocus>
					@error('titre_enseignant')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
					<!-- /TITRE -->

					<!-- SOUS TITRE -->
					<div class="mt-4 text-monospace">SOUS-TITRE <span class="font-italic small" style="color:silver;">optionnel</span></div>
					<div class="text-monospace text-muted small text-justify mb-1">Visible par vous seulement</div>
					<input id="sous_titre_enseignant" type="text" class="form-control @error('sous_titre_enseignant') is-invalid @enderror" name="sous_titre_enseignant" value="{{ old('sous_titre_enseignant', $puzzle->sous_titre_enseignant) }}" autofocus>
					<!-- /SOUS TITRE -->

					<!-- TITRE ELEVE -->
					<div class="mt-4 text-monospace">TITRE ÉLÈVE <span class="font-italic small" style="color:silver;">optionnel</span></div>
					<div class="text-monospace text-muted small text-justify mb-1">Visible par l'élève</div>
					<input id="titre_eleve" type="text" class="form-control @error('titre_eleve') is-invalid @enderror" name="titre_eleve" value="{{ old('titre_eleve', $puzzle->titre_eleve) }}" autofocus>
					<!-- /TITRE ELEVE -->

					<!-- CONSIGNES -->
					<div class="mt-4 text-monospace">
						CONSIGNES <span class="font-italic small" style="color:silver;">optionnel</span>
						<i class="fas fa-info-circle pl-1" style="cursor:pointer;color:#e74c3c;opacity:0.5" data-toggle="modal" data-target="#markdown_help"></i>
					</div>
					<div class="text-monospace text-muted small text-justify mb-1">Consignes pour l'élève</div>
					<textarea id="markdown_content" class="form-control" name="consignes_eleve" id="consignes_eleve" rows="6">{{ old('consignes_eleve', $puzzle->consignes_eleve) }}</textarea>
					<!-- /CONSIGNES -->

					<!-- CODE -->
					<div class="mt-4 text-monospace">CODE<sup class="text-danger small">*</span></div>
					<div class="text-monospace text-muted small text-justify mb-2 p-2" style="border:solid 1px silver;border-radius:4px;">
						SYNTAXE POUR CODE À TROUS<br />
						Code à compléter: [?code?]</br>
						Choix multiples: [?code_correct?distracteur1?distracteur2?distracteur3?]
					</div>
					<textarea name="code" style="display:none;" id="code"></textarea>
					<div id="editor_code" style="border-radius:5px;">{{ old('code', $puzzle->code) }}</div>
					@error('code')
						<span class="invalid-feedback d-block" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
					<!-- /CODE -->

					<!-- FAUX CODE -->
					<div class="mt-4 text-monospace">FAUX CODE <span class="font-italic small" style="color:silver;">optionnel</span></div>
					<div class="text-monospace text-muted small text-justify mb-1">
						Vous pouvez ajouter de fausses lignes de code qui seront mélangées aux lignes de code du code ci-dessus mais qui seront considérées comme des lignes inutiles qui ne doivent pas être placées dans le code final.
					</div>
					<textarea name="fakecode" style="display:none;" id="fakecode"></textarea>
					<div id="editor_fakecode" style="border-radius:5px;">{{ old('fakecode', $puzzle->fakecode) }}</div>
					<!-- /FAUX CODE -->

					<!-- OPTIONS -->
					<div class="mt-4 text-monospace">OPTIONS</div>
					<?php
					$with_dragdrop_checked = ($puzzle->with_dragdrop == 1) ? "checked" : "";
					$with_chrono_checked = ($puzzle->with_chrono == 0) ? "checked" : "";
					$with_nbverif_checked = ($puzzle->with_nbverif == 0) ? "checked" : "";
					if (old()) {
						$with_dragdrop_checked = (old('with_dragdrop') !== null) ? "checked" : "";
						$with_chrono_checked = (old('with_chrono') !== null) ? "checked" : "";
						$with_nbverif_checked = (old('with_nbverif') !== null) ? "checked" : "";
					}
					?>
					<div class="form-check">
						<input class="form-check-input" name="with_dragdrop" type="checkbox" id="with_dragdrop" {{$with_dragdrop_checked}} />
						<label class="form-check-label text-monospace text-muted small" for="with_dragdrop">{{__('mode "glisser-déposer" même si le puzzle ne comporte pas de fausses lignes de code')}}</label>
					</div>					
					<div class="form-check">
						<input class="form-check-input" name="with_chrono" type="checkbox" id="with_chrono" {{$with_chrono_checked}} />
						<label class="form-check-label text-monospace text-muted small" for="with_chrono">ne pas afficher le chronomètre</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" name="with_nbverif" type="checkbox" id="with_nbverif" {{$with_nbverif_checked}} />
						<label class="form-check-label text-monospace text-muted small" for="with_nbverif">ne pas afficher le nombre de tentatives</label>
					</div>	
					<!-- /OPTIONS -->	

                    <input type="hidden" name="puzzle_id" value="{{ $puzzle_id }}" />

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
