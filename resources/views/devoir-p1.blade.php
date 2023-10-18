@include('inc-top')
<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
	@php
        $description = __('Générateur et gestionnaire de puzzles de Parsons') . ' | Entraînement';
        $description_og = '| Devoir';
    @endphp
	@include('inc-meta')
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
    <title>ENTRAÎNEMENT</title>
</head>

<body>
	<div class="container mb-5">

		<div class="row mt-5">

			<div class="col-md-4 offset-md-4">
				<form method="post" action="/devoir" style="display:inline;" role="form">

					@csrf

					<div class="form-group">
						<label for="code-entrainement" style="line-height:1em">Choisir un identifiant <sup style="color:red">*</sup><br /><span class="text-monospace" style="font-size:70%;color:silver">entre 3 et 10 lettres/chiffres</span></label>
						<input id="pseudo" class="form-control @error('pseudo') is-invalid d-block @enderror" type="text" name="pseudo" value="{{ old('pseudo') }}"  />
						@error('pseudo')
							<span class="invalid-feedback d-block" role="alert">
								<strong>{{ $message }}</strong>
							</span>
						@enderror
					</div>

					<div class="bg-danger text-white p-3 text-monospace text-justify rounded" role="alert"><b style="font-weight:600">IMPORTANT</b><br />
						L'entraînement commence dès que vous cliquez sur "commencer". Pendant l'entraînement, vous ne pouvez pas quitter la page. Si vous cliquez en dehors de la page, si vous rechargez la page ou si vous tentez de changer de page, l'entraînement se verrouillera et seul votre enseignant pourra le déverrouiller.
					</div>

					<div class="form-check mt-3">
						<input class="form-check-input" style="cursor:pointer" type="checkbox" onchange="document.getElementById('commencer').disabled = !this.checked;" value="">
    					<label class="form-check-label text-monospace small text-justify pr-1" style="padding-top:2px;" for="defaultCheck1">J'ai compris</label>
					</div>					

					<p class="text-center mt-3">
						<button id="commencer" type="submit" class="btn btn-primary mt-2 pl-4 pr-4" disabled>commencer</button>
					</p>

				</form>
			</div>
		</div>

	</div><!-- /container -->

</body>
</html>
