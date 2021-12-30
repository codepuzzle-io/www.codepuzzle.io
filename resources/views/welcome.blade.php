@include('inc-top')
<!doctype html>
<html lang="fr">
<head>
	@include('inc-meta')
	@include('inc-matomo')
    <title>CODE PUZZLE</title>
</head>
<body>

	@include('inc-nav')

	<p class="text-monospace text-muted small text-center mt-3">
		Générateur de "<b style="color:#4cbf56">puzzles de Parsons</b>"
	</p>

	<p class="text-monospace text-muted small text-center mt-4">
		<a class="btn btn-light btn-sm" href="https://www.codepuzzle.io/p/NHVL" target="_blank" role="button">exemple 1</a>
		<a class="btn btn-light btn-sm" href="https://www.codepuzzle.io/p/39K2" target="_blank" role="button">exemple 2</a>
	</p>

	<p class="text-center mt-5 pt-3">
		<img src="{{ asset('img/demo.gif') }}" class="img-fluid" />
	</p>

	@include('inc-footer')
	@include('inc-bottom-js')

</body>
</html>
