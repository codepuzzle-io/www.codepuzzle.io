<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
	@php
		$description = ucfirst(__('créer un nouveau puzzle'));
		$description_og = '| ' . ucfirst(__('créer un nouveau puzzle'));
	@endphp
	@include('inc-meta')
    <title>{{ config('app.name') }} | {{ ucfirst(__('créer un nouveau puzzle')) }}</title>
</head>
<body>

	<?php
	$lang = (app()->getLocale() == 'fr') ? '/':'/en';
	?>

	@include('inc-nav-guest')

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
				<a class="btn btn-light btn-sm" href="{{ $lang }}" role="button"><i class="fas fa-arrow-left"></i></a>
			</div>

			<div class="col-md-9">

				<h1>{{__('nouveau puzzle')}}</h1>

				<form method="POST" action="{{route('guest-puzzle-creer-post')}}">

					@csrf

					<div class="text-monospace">{{strtoupper(__('titre'))}} <span class="font-italic small" style="color:silver;">{{__('optionnel')}}</span></div>
					<input id="titre" type="text" class="form-control @error('titre') is-invalid @enderror" name="titre" value="{{ old('titre') }}" autofocus />

					<div class="mt-3 text-monospace">
						{{strtoupper(__('consignes'))}} <span class="font-italic small" style="color:silver;">{{__('optionnel')}}</span>
						<i class="fas fa-info-circle pl-1" style="cursor:pointer;color:#e74c3c;opacity:0.5" data-toggle="modal" data-target="#markdown_help"></i>
					</div>
					<textarea class="form-control" name="consignes" id="consignes" rows="6">{{ old('consignes') }}</textarea>

					<div class="mt-3 text-monospace">OPTIONS</div>
					<?php
					$with_chrono_checked = (old('with_chrono') !== null AND old('with_chrono') == 0) ? "checked" : "";
					$with_score_checked = (old('with_score') !== null AND old('with_score') == 0) ? "checked" : "";
					$with_shuffle_checked = (old('with_shuffle') !== null AND old('with_shuffle') == 0) ? "checked" : "";
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
						<label class="form-check-label text-monospace text-muted small" for="with_shuffle">ne pas mélanger les lignes</label>
					</div>

					<div class="mt-3 text-monospace">TEXTE<sup class="text-danger small">*</span></div>
					<div class="text-monospace text-muted small text-justify mb-2 p-3" style="background-color: #f3f5f7;border-radius:4px;">
						SYNTAXE<br />
						Trou: [?texte?]</br>
						Choix multiples: [?texte_correct?distracteur1?distracteur2?distracteur3?]
					</div>

					<textarea id="puzzle" name="puzzle" class="form-control" rows="8">{{ old('puzzle') }}</textarea>
					@error('puzzle')
						<span class="invalid-feedback d-block" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror

					<input id="lang" type="hidden" name="lang" value="{{app()->getLocale()}}" />

					<button type="submit" class="btn btn-primary mt-4 pl-4 pr-4"><i class="fas fa-check"></i></button>

				</form>

			</div>

		</div><!-- row -->

	</div><!-- container -->

	@include('inc-bottom-js')

</body>
</html>
