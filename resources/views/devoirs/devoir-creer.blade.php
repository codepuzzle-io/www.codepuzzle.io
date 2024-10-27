<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// modification du devoir
if (isset($devoir_id)) {
	$devoir = App\Models\Devoir::find(Crypt::decryptString($devoir_id));
	if (!isset($devoir)) {
		echo "<pre>Ce devoir n'existe pas.</pre>";
		exit();
	} else {
		if ($devoir->user_id !== 0 && (!Auth::check() || (Auth::check() && Auth::id() !== $devoir->user_id))) {
			echo "<pre>Vous ne pouvez pas accéder à ce devoir.</pre>";
			exit();
		}
		$sujet = App\Models\Sujet::find($devoir->sujet_id);
		$sujet_json = json_decode($sujet->sujet);
	}
}

// nouveau devoir
if (isset($sujet_id)) {
	$sujet = App\Models\Sujet::find(Crypt::decryptString($sujet_id));
	if (!$sujet) {
		echo "<pre>Ce sujet n'existe pas.</pre>";
		exit();
	} else {
		if ($sujet->user_id !== 0 && (!Auth::check() || (Auth::check() && Auth::id() !== $sujet->user_id))) {
			echo "<pre>Vous ne pouvez pas accéder à ce sujet.</pre>";
			exit();
		}
		$sujet_json = json_decode($sujet->sujet);
	}
}
$page_devoir_creer = true;
?>
<!doctype html>
<html lang="fr">
<head>
	@include('inc-meta')
	<link href="{{ asset('css/highlight.css') }}" rel="stylesheet">
	<link href="{{ asset('css/easymde.css') }}" rel="stylesheet">
	<link href="{{ asset('css/easymde-custom.css') }}" rel="stylesheet">
    <title>DEVOIR | CRÉER / MODIFIER</title>
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
				<a class="btn btn-light btn-sm" href="/" role="button"><i class="fas fa-arrow-left"></i></a>
				<div class="mt-3 small text-monospace text-muted">Vous pouvez <a href="/creer-un-compte" target="_blank">créer un compte</a> pour regrouper, gérer et partager vos devoirs.</div>
				@endif
			</div>

			<div class="col-md-10 pl-4 pr-4">

				<h1>{{__('devoir')}}</h1>

				<form method="POST" action="{{route('devoir-creer-post')}}">

					@csrf

					<!-- TITRE -->
					<div class="text-monospace mb-1">{{strtoupper(__('titre'))}}<sup class="ml-1 text-danger small">*</sup></div>
					<input id="titre_enseignant" type="text" class="form-control @error('titre_enseignant') is-invalid @enderror" name="titre_enseignant" value="{{ old('titre_enseignant') ?? $devoir->titre_enseignant ?? '' }}" autofocus>
					@error('titre_enseignant')
						<span class="invalid-feedback text-danger text-monospace" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
					<!-- /TITRE -->

					<!-- CONSIGNES -->
					<div class="mt-4 mb-1 text-monospace">{{strtoupper(__('consignes'))}} <span class="font-italic small" style="color:silver;">{{__('optionnel')}}</span></div>
					<textarea class="form-control @error('consignes_eleve') is-invalid @enderror" name="consignes_eleve" id="consignes_eleve" rows="4">{{ old('consignes_eleve') ?? $devoir->consignes_eleve ?? '' }}</textarea>
					@error('consignes_eleve')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
					<!-- /CONSIGNES -->	

					<!-- SUJET -->	
						<div class="mt-4 mb-1 text-monospace">{{strtoupper(__('sujet'))}}</div>	
						@include('sujets/inc-sujet-afficher')				
					<!-- /SUJET -->	
			
					@if (isset($devoir_id))
						<input type="hidden" name="devoir_id" value="{{$devoir_id}}" />
					@endif

					@if (isset($sujet_id))
						<input type="hidden" name="sujet_id" value="{{$sujet_id}}" />
					@endif

					<button type="submit" class="btn btn-primary mt-4 mb-5 pl-4 pr-4"><i class="fas fa-check"></i></button>
					
				</form>

			</div>

		</div><!-- row -->

	</div><!-- container -->

	@include('inc-bottom-js')
	@include('sujets/inc-sujet-afficher-js')

</body>
</html>
