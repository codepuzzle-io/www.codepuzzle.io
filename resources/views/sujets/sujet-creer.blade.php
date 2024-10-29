<!doctype html>
<html lang="fr">
<head>
	@include('inc-meta')
    <title>SUJET | CRÉER / MODIFIER</title>
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
					<a class="btn btn-light btn-sm" href="/console/devoirs" role="button"><i class="fas fa-arrow-left"></i></a>
				@else
					@if (isset($jeton_secret))
						<a class="btn btn-light btn-sm" href="/sujet-console/{{ $jeton_secret }}" role="button"><i class="fas fa-arrow-left"></i></a>
					@else
						<a class="btn btn-light btn-sm" href="/" role="button"><i class="fas fa-arrow-left"></i></a>
					@endif
					<div class="mt-3 small text-monospace text-muted">Vous pouvez <a href="/creer-un-compte" target="_blank">créer un compte</a> pour regrouper, gérer et partager vos sujets.</div>
				@endif
			</div>

			<div class="col-md-10 pl-4 pr-4">

				<h1>{{__('nouveau sujet')}}</h1>

				<div class="text-center text-monospace">
					<a class="btn btn-dark mr-1" href="/sujet-exo-creer" role="button">Exercice Python</a>
					<a class="btn btn-dark ml-1" href="/sujet-pdf-creer" role="button">Sujet au format PDF</a>
					<a class="btn btn-dark ml-1" href="/sujet-md-creer" role="button">Sujet au format Markdown</a>
				</div>

			</div>
		</div><!-- /row -->
	</div><!-- /container -->

</body>
</html>
