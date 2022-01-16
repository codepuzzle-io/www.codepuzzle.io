<nav class="navbar navbar-expand-md navbar-light">

	<div class="container">

		<div class="col-md-3">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="text-monospace text-center">
				{!! $lang_switch ?? '' !!}
			</div>
		</div>

		<div class="col-md-6 text-center">
			<h1><a class="navbar-brand m-1" href="{{ url('/') }}"><img src="{{ asset('img/codepuzzle.png') }}" height="20" alt="CODE PUZZLE" /></a></h1>
		</div>

		<div class="col-md-3">
			<div class="collapse navbar-collapse" id="navbarSupportedContent">

				<!-- Left Side Of Navbar -->
				<ul class="navbar-nav mr-auto">

				</ul>

				<!-- Right Side Of Navbar -->
				<ul class="navbar-nav ml-auto">

					<!-- Authentication Links -->
					@guest
						<li class="nav-item">
							<a class="btn btn-outline-secondary btn-xs" style="opacity:0.4;margin:2px 0px 0px 4px" href="{{ route('login') }}">{{__('se connecter')}}</a>
						</li>
						<!--
						@if (Route::has('register'))
							<li class="nav-item">
								<a class="btn btn-outline-secondary btn-sm " style="font-size:80%;opacity:0.4;margin:2px 0px 0px 4px" href="{{ route('register') }}">{{__('créer un compte')}}</a>
							</li>
						@endif
					-->
					@else
						<li class="nav-item">
							<a class="btn btn-outline-secondary btn-xs" style="opacity:0.4;margin:2px 0px 0px 4px" href="{{ route('console') }}">
								{{__('console')}}
							</a>
						</li>
						<li class="nav-item">
							<a class="btn btn-outline-secondary btn-xs" style="opacity:0.4;margin:2px 0px 0px 4px" href="{{ route('logout') }}"
							   onclick="event.preventDefault();
											 document.getElementById('logout-form').submit();">
								{{__('se déconnecter')}}
							</a>

							<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
								@csrf
							</form>
						</li>
					@endguest

				</ul>

			</div>
		</div>

	</div><!-- container -->

</nav>
