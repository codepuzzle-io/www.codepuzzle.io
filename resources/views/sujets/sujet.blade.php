<?php
if (isset($jeton)) {
	$sujet = App\Models\Sujet::where('jeton', $jeton)->first();
	if (!$sujet) {
		echo "<pre>Ce sujet n'existe pas.</pre>";
		exit();
	} else {
		$sujet_json = json_decode($sujet->sujet);
	}
}
$page_sujet = true;
?>
<!doctype html>
<html lang="fr">
<head>
	@include('inc-meta')
    <title>SUJET</title>
</head>
<body>

	<div class="container">

		<div class="row">

			<div class="col">

                <div class="mt-2 mb-4 text-center"><a class="navbar-brand m-1" href="{{ url('/') }}"><img src="{{ asset('img/codepuzzle.png') }}" height="25" alt="CODE PUZZLE" /></a></div>

				<h1 class="mb-0">{{__('sujet')}}</h1>
				<div class="text-muted">Exercice Python</div>

                <div class="mt-3 mb-4 text-center">
                    <a class="btn btn-outline-secondary btn-sm text-monospace ml-1 mr-1" href="/sujet-{{$sujet->type}}-creer/{{Crypt::encryptString($sujet->id)}}/dupliquer" role="button" target="_blank">dupliquer</a>
                    <a class="btn btn-outline-secondary btn-sm text-monospace ml-1 mr-1" href="/S{{strtoupper($sujet->jeton)}}/copie" role="button" target="_blank">sujet + copie</a>
                    <a class="btn btn-outline-secondary btn-sm text-monospace ml-1 mr-1" href="/devoir-creer/{{Crypt::encryptString($sujet->id)}}" role="button">créer un devoir</a>
				</div>

				<!-- SUJET -->
				@include('sujets/inc-sujet-afficher')
				<!-- /SUJET -->

			</div>

		</div><!-- /row -->
	</div><!-- /container -->

	<br />

	@include('inc-bottom-js')
    @include('sujets/inc-sujet-afficher-js')

</body>
</html>