@include('inc-top')
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	@include('inc-meta')
    <title>{{ config('app.name') }} | {{ ucfirst(__('courriel de confirmation')) }}</title>
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
				<div class="card mt-5 text-muted" style="background:none;border:none;">
					<p><b>{{__('Consultez votre boîte aux lettres')}}</b></p>

					<div class="card-body">
						@if (session('resent'))
							<div class="text-monospace text-success pb-3" role="alert">
								{{__('Un lien de vérification a été envoyé à l adresse courriel que vous avez indiquée lors de votre inscription.')}}
							</div>
						@endif

						<p>{{__('Adresse courriel indiquée lors de l inscription')}} : <span class="text-danger text-monospace"> {{ Auth::user()->email }}</span></p>

						<p>{{__("Vous allez recevoir dans quelques minutes un courriel de vérification. Ouvrez-le puis cliquez sur le lien pour valider votre inscription.")}}</p>

						<ul>
							<li class="mt-3">
							{{__('Si le courriel n apparaît pas dans votre boîte de réception, vérifiez vos "spams".')}}
							</li>
							<li class="mt-3">{{__('Si vous ne l avez pas reçu après plusieurs minutes,')}}
							<form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
								@csrf
								<button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{__('cliquez ici')}}</button> {{__('pour le renvoyer.')}}
							</form>
							</li>
							<li class="mt-3">
							{{__('Si vous avez commis une erreur lors de l inscription (adresse courriel erronée par exemple), complétez une')}} <a href="{{ route('direct-register') }}" class="button text-center">{{__('nouvelle inscription')}}</a>.
							</li>
							<li class="mt-3">
							{{__('Si vous avez reçu le courriel de vérification, que vous avez cliqué sur le lien mais que vous arrivez tout de même sur cette page,')}} <a href="{{ route('direct-login') }}" class="button text-center">{{__('cliquez ici')}}</a> {{__('pour vous connecter.')}}
							</li>
							<li class="mt-3">
							{{__('Si un problème persiste et vous empêche de vous connecter, vous pouvez écrire à')}} <a href="mailto:contact@codepuzzle.io">contact@codepuzzle.io</a>.
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div><!-- container -->

</body>
</html>
