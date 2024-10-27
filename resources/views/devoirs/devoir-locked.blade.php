@include('inc-top')
<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
	@php
        $description = __('Générateur et gestionnaire de puzzles de Parsons') . ' | Devoir';
        $description_og = '| Devoir';
    @endphp
	@include('inc-meta')
    <title>ENTRAÎNEMENT / DEVOIR | VERROUILLÉ</title>
</head>

<body class="bg-danger">
	<div class="container mb-5">

		<div class="row mt-5">

			<div class="col-md-4 offset-md-4">
				<form method="post" action="/devoir-unlock" style="display:inline;" role="form" autocomplete="off">

					@csrf

					<div class="text-white pb-4 text-monospace text-justify" role="alert">
						<b style="font-weight:600">SESSION VERROUILLÉE</b><br />
						Votre session est verrouillée car vous avez quitté la page, quitté le mode plein écran ou cliqué en dehors de la page.
						<br />
						Pour continuer, appelez votre enseignant.
					</div>

					<div class="form-group">
						<input id="unlock_code" class="form-control @error('unlock_code') is-invalid d-block @enderror" type="text" name="unlock_code" value="{{ old('unlock_code') }}" placeholder="code secret" autocomplete="off" style="-webkit-text-security:disc" />
						@error('unlock_code')
							<span class="invalid-feedback d-block" role="alert">
								<strong>{{ $message }}</strong>
							</span>
						@enderror
					</div>

					<p class="text-center">
						<button type="submit" class="btn btn-primary pl-4 pr-4 text-monospace">reprendre</button>
					</p>

				</form>
			</div>
		</div>

	</div><!-- /container -->

	<script>
        setInterval(function() {
			fetch('/devoir-eleve-check-lock-status', {
				method: 'POST',
				headers: {"Content-Type": "application/x-www-form-urlencoded", "X-CSRF-Token": "{{ csrf_token() }}"},
			})
			.then(function(response) {
				return response.text();
			})
			.then(function(data) {
				// Affiche la réponse du serveur dans la console
				// Cette fonction de rappel sera exécutée uniquement si la réponse est 200
				console.log('Lock status: ' + data); 
				if (data == 0){
					window.location.replace("/devoir");
				}
			})			
			.catch(function(error) {
				// Gère les erreurs liées à la requête Fetch
				console.error('Erreur:', error); 
			});            
        }, 5000);
	</script>

</body>
</html>
