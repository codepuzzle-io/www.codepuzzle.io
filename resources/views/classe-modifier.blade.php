<?php
$classe = App\Models\Classe::where('jeton_secret', $jeton_secret)->first();
if (!$classe){
    echo "<pre>Cet classe n'existe pas</pre>";
    exit();
}
$eleves = App\Models\Classes_eleve::where('id_classe', $classe->id)->get();
?>
<!doctype html>
<html lang="fr">
<head>
	@include('inc-meta')
    <title>{{ config('app.name') }} | {{ ucfirst(__('nouveau défi')) }}</title>
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
				<a class="btn btn-light btn-sm" href="/classe-console/{{$jeton_secret}}" role="button"><i class="fas fa-arrow-left"></i></a>
			</div>

			<div class="col-md-6 pl-4 pr-4">

				<form method="POST" action="{{route('classe-modifier-post')}}">

					@csrf
				
					<!-- NOM DE LA CLASSE -->
					<div class="text-monospace">{{strtoupper(__('nom de la classe'))}}<sup class="text-danger small">*</sup></div>
					<input id="nom_classe" type="text" class="form-control @error('nom_classe') is-invalid @enderror" name="nom_classe" value="{{ old('nom_classe') ?? $classe->nom_classe }}" autofocus>
					@error('nom_classe')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
					<!-- /NOM DE LA CLASSE -->

					<!-- ELEVES -->
					<a id="eleves_anchor"></a>
					<div class="mt-4 text-monospace">{{strtoupper(__('ÉLÈVES'))}}</div>
					
					<div id="frame" class="frame">
						<table>
							@foreach($eleves AS $eleve)
								<tr>
									<td style="width:100%">{{$eleve->eleve}}</td>
									<td class="text-monospace pl-3 pr-3">{{$eleve->jeton_eleve}}</td>
									<td><a href="/classe-eleve-supprimer/{{ Crypt::encryptString($eleve->id) }}"><i class="ml-2 fas fa-trash text-danger" aria-hidden="true"></i></a></td>
								</tr>
							@endforeach
						</table>
					</div>
					<!-- /ELEVES -->


					<!-- AJOUTER ELEVES -->
					<a id="eleves_anchor"></a>
					<div class="mt-4 text-monospace">{{strtoupper(__('AJOUTER DES ÉLÈVES'))}}</div>
					<div class="text-monospace small text-danger">Ne pas utiliser le nom complet des élèves</div>
					<div class="text-monospace small text-muted">Un identifiant (prénom, initiales, pseudo...) par ligne</div>
					<textarea id="eleves" name="eleves" class="form-control @error('eleves') is-invalid @enderror"" rows="10">{{ old('eleves') }}</textarea>
					@error('eleves')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
					<!-- /AJOUTER ELEVES -->

					<input type="hidden" name="jeton_secret" value="{{$jeton_secret}}" />
					<div>
						<button type="submit" class="btn btn-primary mt-4 mb-5 pl-4 pr-4"><i class="fas fa-check"></i></button>
					</div>
				</form>

			</div>

		</div><!-- row -->

	</div><!-- container -->

	@include('inc-bottom-js')

</body>
</html>
