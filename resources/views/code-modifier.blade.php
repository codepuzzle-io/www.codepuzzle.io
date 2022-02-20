<!doctype html>
<html lang="fr">
<head>
	@include('inc-meta')
    <title>{{ config('app.name') }} | {{ ucfirst(__('modifier')) }}</title>
</head>
<body>

    @include('inc-nav-console')

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

            <div class="col-md-2 text-center pt-4">
				<a class="btn btn-light btn-sm" href="/console" role="button"><i class="fas fa-arrow-left"></i></a>
			</div>

            <div class="col-md-9 pt-4">

				<form method="POST" action="{{route('code-modifier-post')}}">

					@csrf

                    <?php
                    $code = App\Models\Code::where([['user_id', Auth::id()], ['id', Crypt::decryptString($code_id)]])->first();
                    ?>
					<div class="text-monospace">TITRE<sup class="text-danger small">*</span></div>
					<div class="text-monospace text-muted small text-justify mb-1">Visible par vous seulement</div>
					<input id="titre_enseignant" type="text" class="form-control @error('titre_enseignant') is-invalid @enderror" name="titre_enseignant" value="{{ old('titre_enseignant', $code->titre_enseignant) }}" autofocus>
					@error('titre_enseignant')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror

					<div class="mt-3 text-monospace">SOUS-TITRE <span class="font-italic small" style="color:silver;">optionnel</span></div>
					<div class="text-monospace text-muted small text-justify mb-1">Visible par vous seulement</div>
					<input id="sous_titre_enseignant" type="text" class="form-control @error('sous_titre_enseignant') is-invalid @enderror" name="sous_titre_enseignant" value="{{ old('sous_titre_enseignant', $code->sous_titre_enseignant) }}" autofocus>

					<div class="mt-3 text-monospace">TITRE ÉLÈVE <span class="font-italic small" style="color:silver;">optionnel</span></div>
					<div class="text-monospace text-muted small text-justify mb-1">Visible par l'élève</div>
					<input id="titre_eleve" type="text" class="form-control @error('titre_eleve') is-invalid @enderror" name="titre_eleve" value="{{ old('titre_eleve', $code->titre_eleve) }}" autofocus>

					<div class="mt-3 text-monospace">
						CONSIGNES <span class="font-italic small" style="color:silver;">optionnel</span>
						<i class="fas fa-info-circle pl-1" style="cursor:pointer;color:#e74c3c;opacity:0.5" data-toggle="modal" data-target="#markdown_help"></i>
					</div>
					<div class="text-monospace text-muted small text-justify mb-1">Consignes pour l'élève</div>
					<textarea class="form-control" name="consignes_eleve" id="consignes_eleve" rows="6">{{ old('consignes_eleve', $code->consignes_eleve) }}</textarea>

					<div class="mt-3 text-monospace">OPTIONS</div>
					<?php
					$with_chrono_checked = ($code->with_chrono == 0) ? "checked" : "";
					$with_score_checked = ($code->with_score == 0) ? "checked" : "";
					$with_shuffle_checked = ($code->with_shuffle == 0) ? "checked" : "";
					if (old()) {
						$with_chrono_checked = (old('with_chrono') !== null) ? "checked" : "";
						$with_score_checked = (old('with_score') !== null) ? "checked" : "";
						$with_shuffle_checked = (old('with_shuffle') !== null) ? "checked" : "";
					}
					?>
					<div class="form-check">
						<input class="form-check-input" name="with_chrono" type="checkbox" value="0" id="with_chrono" {{$with_chrono_checked}} />
						<label class="form-check-label text-monospace text-muted small" for="with_chrono">ne pas afficher le chronomètre</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" name="with_score" type="checkbox" value="0" id="with_score" {{$with_score_checked}} />
						<label class="form-check-label text-monospace text-muted small" for="with_score">ne pas afficher les points</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" name="with_shuffle" type="checkbox" value="0" id="is_shuffled" {{$with_shuffle_checked}} />
						<label class="form-check-label text-monospace text-muted small" for="with_shuffle">ne pas mélanger les lignes de code</label>
					</div>

					<div class="mt-3 text-monospace">CODE<sup class="text-danger small">*</span></div>
					<div class="text-monospace text-muted small text-justify">
						Avant de valider le formulaire, assurez-vous que votre code respecte les standards de formatage <a href="https://pep8.org/" target="_blank">PEP8</a>.
						Pour vous aider:
						<ul>
							<li>un vérificateur : <a href="http://pep8online.com/" target="_blank">pep8online.com</a></li>
							<li>un correcteur automatique de code à utiliser avec prudence : <a href="https://black.vercel.app/" target="_blank">black.vercel.app</a></li>
						</ul>
					</div>
					<div class="text-monospace text-muted small text-justify mb-2 p-2" style="border:solid 1px silver;border-radius:4px;">
						SYNTAXE POUR CODE À TROUS<br />
						Code à compléter: [?code?]</br>
						Choix multiples: [?code_correct?distracteur1?distracteur2?distracteur3?]
					</div>

					<textarea name="code" style="display:none;" id="code"></textarea>
					<div style="width:100%;margin:0px auto 0px auto;"><div id="editor_code" style="border-radius:5px;">{{ old('code', $code->code) }}</div></div>
					@error('code')
						<span class="invalid-feedback d-block" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror

					<div class="mt-3 text-monospace">FAUX CODE <span class="font-italic small" style="color:silver;">optionnel</span></div>
					<div class="text-monospace text-muted small text-justify mb-1">
						Vous pouvez ajouter de fausses lignes de code qui seront mélangées aux lignes de code du code ci-dessus mais qui seront considérées comme des lignes inutiles qui ne doivent pas être placées dans le code final.
					</div>
					<textarea name="fakecode" style="display:none;" id="fakecode"></textarea>
					<div style="width:100%;margin:0px auto 0px auto;"><div id="editor_fakecode" style="border-radius:5px;">{{ old('fakecode', $code->fakecode) }}</div></div>

                    <input type="hidden" name="code_id" value="{{ $code_id }}" />

					<input id="lang" type="hidden" name="lang" value="{{app()->getLocale()}}" />

					<button type="submit" class="btn btn-primary mt-4 pl-4 pr-4"><i class="fas fa-check"></i></button>

				</form>

			</div>

		</div><!-- row -->

	</div><!-- container -->

	@include('inc-bottom-js')

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
