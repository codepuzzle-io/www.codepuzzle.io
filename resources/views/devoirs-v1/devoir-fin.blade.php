@include('inc-top')
<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
	@php
        $description = __('Générateur et gestionnaire de puzzles de Parsons') . ' | Devoir';
        $description_og = '| Devoir';
    @endphp
	@include('inc-meta')
    <title>ENTRAÎNEMENT / DEVOIR | FIN</title>
</head>

<body>
	<div class="container mb-5">

		<div class="row mt-5">

			<div class="col-md-12 text-center">
				@if(isset($_GET['s0']))
					<div><img src="{{ asset('img/rendu.png') }}" height="200" alt="BRAVO!" /></div>
					<h1 class="text-center mt-4">TRAVAIL RENDU!</h1>
					<div class="text-muted mt-5 text-monospace">vous pouvez quitter cette page</div>
				@else
					<h1 class="text-center mt-4">DÉJÀ RENDU!</h1>
					<div class="text-muted mt-5 text-monospace">vous pouvez quitter cette page</div>
				@endif
			</div>
		</div>

	</div><!-- /container -->

	<script>
		history.pushState(null, null, location.href);
		window.onpopstate = function () {
			history.go(1);
		};
	</script>

</body>
</html>
