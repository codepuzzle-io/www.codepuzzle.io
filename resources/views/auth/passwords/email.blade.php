@include('inc-top')
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	@include('inc-meta')
    <title>{{ config('app.name') }} | {{ __('Demande de réinitialisation du mot de passe') }}</title>
</head>
<body>

	@include('inc-nav')

	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card mt-5 text-muted" style="background:none;border:none;">
					<p><b>{{ __('Demande de réinitialisation du mot de passe') }}</b></p>

					<div class="card-body">
						@if (session('status'))
							<div class="text-success text-monospace text-center pb-4" role="alert">
								{{ session('status') }}
							</div>
						@endif

						<form method="POST" action="{{ route('password.email') }}">
							@csrf

							<div class="form-group row">
								<label for="email" class="col-md-4 col-form-label text-md-right">{{__('adresse courriel')}}</label>

								<div class="col-md-6">
									<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autofocus />

									@error('email')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>

							<div class="form-group row mb-0">
								<div class="col-md-6 offset-md-4">
									<button type="submit" class="btn btn-primary"><i class="fas fa-check"></i></button>
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
