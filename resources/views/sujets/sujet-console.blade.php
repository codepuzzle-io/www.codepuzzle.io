<?php
if (isset($jeton_secret)) {
	$sujet = App\Models\Sujet::where('jeton_secret', $jeton_secret)->first();
	if (!$sujet) {
		echo "<pre>Ce sujet n'existe pas.</pre>";
		exit();
	} else {
		if ($sujet->user_id !== 0 && (!Auth::check() || (Auth::check() && Auth::id() !== $sujet->user_id))) {
			echo "<pre>Vous ne pouvez pas accéder à ce sujet.</pre>";
			exit();
		}
		$sujet_json = json_decode($sujet->sujet);
		$page_sujet_console = true;
	}
}
?>
<!doctype html>
<html lang="fr">
<head>
	@include('inc-meta')
    <link href="{{ asset('css/highlight.css') }}" rel="stylesheet">
	<link href="{{ asset('css/easymde.css') }}" rel="stylesheet">
	<link href="{{ asset('css/easymde-custom.css') }}" rel="stylesheet">
	<link href="{{ asset('css/dropzone-basic.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dropzone.css') }}" rel="stylesheet">
    <title>SUJET | CONSOLE</title>
</head>
<body>

	@if(Auth::check())
		@include('inc-nav-console')
	@else
		@include('inc-nav')
	@endif

	<div class="container mt-4">

		<div class="row">

			<div class="col-md-2 text-right pb-5">
				@if(Auth::check())
				    <a class="btn btn-light btn-sm" href="/console/sujets" role="button"><i class="fas fa-arrow-left"></i></a>
				@else
				    <a class="btn btn-light btn-sm" href="/" role="button"><i class="fas fa-arrow-left"></i></a>
				    <div class="mt-3 small text-monospace text-muted">Vous pouvez <a href="/creer-un-compte" target="_blank">créer un compte</a> pour regrouper, gérer et partager vos sujets.</div>
				@endif
			</div>

			<div class="col-md-10">

                @if($sujet->user_id == 0 OR !Auth::check())
                    <div class="row">
                        <div class="col-md-10 offset-md-1 text-monospace p-2 pl-5 pr-5 mb-3" style="border:dashed 2px #e3342f;border-radius:8px;">
                            @if(isset($_GET['i']))
                                <div class="text-danger text-center font-weight-bold mb-2">SAUVEGARDEZ LES INFORMATIONS CI-DESSOUS AVANT DE QUITTER CETTE PAGE</div>
                            @endif
                            <div class="text-center font-weight-bold small">lien secret</div>
                            <div class="text-center p-2 text-break align-middle rounded bg-danger text-white"><a href="/sujet-console/{{strtoupper($sujet->jeton_secret)}}" target="_blank" class="text-white">www.codepuzzle.io/sujet-console/{{strtoupper($sujet->jeton_secret)}}</a></div>
                            <div class="small text-muted p-1"><span class="text-danger"><i class="fas fa-exclamation-circle"></i> Sauvegarder ce lien et ne pas le partager car il permet de revenir sur cette page.</span></div>
                        </div>
                    </div>
                @endif

				<div class="row">
					<div class="col-md-8 offset-md-2 text-monospace pb-3">
						<div class="mt-3 mb-4 text-center">
							<a class="btn btn-dark btn-sm text-monospace ml-1 mr-1" href="/sujet-{{$sujet->type}}-creer/{{Crypt::encryptString($sujet->id)}}" role="button"><i class="fa-solid fa-pen mr-2"></i>modifier</a>
							<a class="btn btn-outline-secondary btn-sm text-monospace ml-1 mr-1" href="/sujet-{{$sujet->type}}-creer/{{Crypt::encryptString($sujet->id)}}/dupliquer" role="button" target="_blank">dupliquer</a>
							<a class="btn btn-outline-secondary btn-sm text-monospace ml-1 mr-1" href="/S{{strtoupper($sujet->jeton)}}/copie" role="button" target="_blank">sujet + copie</a>
							<a class="btn btn-outline-secondary btn-sm text-monospace ml-1 mr-1" href="/devoir-creer/{{Crypt::encryptString($sujet->id)}}" role="button" target="_blank">créer un devoir</a>
						</div>
						<div class="mt-3 mb-4 text-center">
							<i class="fas fa-share-alt mr-1 text-primary"></i> Lien public à partager: <a class="text-monospace ml-1" href="/S{{strtoupper($sujet->jeton)}}" role="button" target="_blank">www.codepuzzle.io/S{{strtoupper($sujet->jeton)}}</a>
						</div>
					</div>
				</div>

				<div class="mb-1 text-monospace">{{strtoupper(__("sujet"))}}</div>
				<!-- SUJET -->
				@include('sujets/inc-sujet-afficher')
				<!-- /SUJET -->

            </div>
        </div>
    </div>

	<br />

	@include('inc-bottom-js')
    @include('sujets/inc-sujet-afficher-js')
</body>
</html>