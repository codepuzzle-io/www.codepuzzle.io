@include('inc-top')
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	@include('inc-meta')
    <title>{{ config('app.name') }} | {{ ucfirst(__('confirmation du mot de passe')) }}</title>
</head>
<body>

	@include('inc-nav')

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<a class="navbar-brand" href="/"><img src="{{ asset('img/txtpuzzle.png') }}" width="150" alt="CODE PUZZLE" /></a>
			</div>
		</div>		
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">{{ ucfirst(__('confirmation du mot de passe')) }}</div>

					<div class="card-body">
						{{__('Veuillez confirmer votre mot de passe avant de continuer')}}

						<form method="POST" action="{{ route('password.confirm') }}">
							@csrf

							<div class="form-group row">
								<label for="password" class="col-md-4 col-form-label text-md-right">{{__(mot de passe)}}</label>

								<div class="col-md-6">
									<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" />

									@error('password')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>

							<div class="form-group row mb-0">
								<div class="col-md-8 offset-md-4">
									<button type="submit" class="btn btn-warning"><i class="fas fa-check"></i></button>

									@if (Route::has('password.request'))
										<a class="btn btn-link" href="{{ route('password.request') }}">{{__('vous avez oublié votre mot de passe ?')}}</a>
									@endif
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div><!-- container -->

	@include('inc-bottom-js')

</body>
</html>
