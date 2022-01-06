@include('inc-top')
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	@include('inc-meta')
    <title>Code Puzzle | Inscription</title>
</head>
<body>

	@include('inc-nav')

	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card mt-5" style="background:none;border:none;">
					<h1>{{__('inscription')}}</h1>

					<div class="card-body">
						<form method="POST" action="{{ route('register', app()->getLocale()) }}">

							@csrf

							<div class="form-group row">
								<label for="email" class="col-md-4 col-form-label text-md-right text-info" style=";line-height:1">{{__('adresse courriel')}} <sup class="text-danger">*</sup></label>
								<div class="col-md-6">
									<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" />
									@error('email')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>

							<div class="form-group row">
								<label for="password" class="col-md-4 col-form-label text-md-right text-info">{{__('mot de passe')}} <sup class="text-danger">*</sup></label>
								<div class="col-md-6">
									<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" />
									@error('password')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>

							<div class="form-group row">
								<label for="password-confirm" class="col-md-4 col-form-label text-md-right text-info">{{__('confirmation du mot de passe')}} <sup class="text-danger">*</sup></label>
								<div class="col-md-6">
									<input id="password-confirm" type="password" class="form-control" name="password_confirmation" />
								</div>
							</div>

							<div class="form-group row pt-3">
								<label for="password-confirm" class="col-md-4 text-right"><span class="badge badge-warning small" style="padding-top:5px;">{{__('RGPD')}}</span></label>
								<div class="col-md-6">
									<div class="form-check">
										<input class="form-check-input" style="cursor:pointer" type="checkbox"  onchange="document.getElementById('inscription').disabled = !this.checked;" >
										<label class="form-check-label text-monospace small text-justify pr-1 text-muted" style="padding-top:2px;">{{__('J autorise ce site à conserver les données transmises via ce formulaire. Ces données peuvent être supprimées à tout moment en sélectionnant "supprimer ce compte" dans la console.')}}</label>
									</div>
								</div>
							</div>

							<div class="form-group row mb-5">
								<div class="col-md-6 offset-md-4">
									<button type="submit" id="inscription" class="btn btn-primary pl-4 pr-4" disabled><i class="fas fa-check"></i></button>
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
