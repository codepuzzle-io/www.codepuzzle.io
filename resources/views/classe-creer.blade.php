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
				<a class="btn btn-light btn-sm" href="/console/defis" role="button"><i class="fas fa-arrow-left"></i></a>
				@else
				<a class="btn btn-light btn-sm" href="/" role="button"><i class="fas fa-arrow-left"></i></a>
				@endif
			</div>

			<div class="col-md-6 pl-4 pr-4">

				<h1>{{__('nouvelle classe')}}</h1>

				<form method="POST" action="{{route('classe-creer-post')}}">

					@csrf
				
					<!-- NOM DE LA CLASSE -->
					<div class="text-monospace">{{strtoupper(__('nom de la classe'))}}<sup class="text-danger small">*</sup></div>
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
					<div class="text-monospace small text-muted">Un identifiant (prénom, initiales, pseudo...) par ligne</div>
					<textarea id="eleves" name="eleves" class="form-control @error('eleves') is-invalid @enderror"" rows="20">{{ old('eleves') }}</textarea>
					@error('eleves')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
						
					<!-- /ELEVES -->

					<input id="lang" type="hidden" name="lang" value="{{app()->getLocale()}}" />
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
