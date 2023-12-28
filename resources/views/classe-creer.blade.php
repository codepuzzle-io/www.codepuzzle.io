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
				@if(Auth::check())
				<a class="btn btn-light btn-sm" href="/console/classes" role="button"><i class="fas fa-arrow-left"></i></a>
				@else
				<a class="btn btn-light btn-sm" href="/" role="button"><i class="fas fa-arrow-left"></i></a>
				@endif
			</div>

			<div class="col-md-8 pl-4 pr-2">

				<h1>{{__('nouvelle classe')}}</h1>

				<form method="POST" action="{{route('classe-creer-post')}}">

					@csrf
				
					<!-- NOM DE LA CLASSE -->
					<div class="text-monospace pb-1">{{strtoupper(__('nom de la classe'))}}<sup class="pl-1 text-danger small">*</sup></div>
					<input id="nom_classe" type="text" class="form-control @error('nom_classe') is-invalid @enderror" name="nom_classe" value="{{ old('nom_classe') }}" autofocus>
					@error('nom_classe')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
					<!-- /NOM DE LA CLASSE -->

					<!-- ELEVES -->
					<a id="eleves_anchor"></a>
					<div class="mt-4 text-monospace">{{strtoupper(__('ÉLÈVES'))}}</div>
					<div class="text-monospace small text-danger">Ne pas utiliser le nom complet des élèves</div>
					<div class="text-monospace small text-muted pb-1">Un identifiant (prénom, initiales, pseudo...) par ligne</div>
					<textarea id="eleves" name="eleves" class="form-control @error('eleves') is-invalid @enderror"" rows="15">{{ old('eleves') }}</textarea>
					@error('eleves')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
					<!-- /ELEVES -->					

					<!-- ACTIVITES -->
					<a id="activites_anchor"></a>
					<div class="mt-4 text-monospace">{{strtoupper(__('ACTIVITES'))}} <i class="text-muted" style="font-size:70%;">optionnel</i></div>
					<div class="text-monospace small text-muted pb-1">Les activités indiquées ci-dessous apparaîtront dans la console de la classe et dans la console des élèves. Une activité peut être un puzzle ou un défi. Vous pouvez ajouter des activités maintenant ou plus tard. Saisir les codes des activités séparés par des virgules.<br />Exemple: DQMSK,DXSR8,DWMX2,DEHSD,DL92R</div>
					<textarea id="activites" name="activites" class="form-control @error('activites') is-invalid @enderror" rows="2">{{ old('activites') }}</textarea>
					@error('activites')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
					<!-- /ACTIVITES -->

					<input id="lang" type="hidden" name="lang" value="{{app()->getLocale()}}" />
					<div>
						<button type="submit" class="btn btn-success mt-4 mb-5 pl-4 pr-4"><i class="fas fa-check"></i></button>
					</div>
				</form>

			</div>

		</div><!-- row -->

	</div><!-- container -->

	@include('inc-bottom-js')

</body>
</html>
