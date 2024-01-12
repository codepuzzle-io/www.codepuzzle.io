<nav class="navbar navbar-expand-md navbar-light">
	<div class="container">
		<div class="text-center">
			<div>
				<a href="/console"><img src="{{ asset('img/nav-home.png') }}" width="40" /></a>
				<br />
				<span class="text-monospace small" style="color:#c5c7c9;">{{__('console')}}</span>
			</div>

		</div>

		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<!-- Left Side Of Navbar -->
			<ul class="navbar-nav mr-auto"></ul>

			<!-- Right Side Of Navbar -->
			<ul class="navbar-nav ml-auto">
				<div class="text-monospace text-center mr-3">
					{!! $lang_switch ?? '' !!}
				</div>

				<li class="nav-item dropdown">

					<a id="navbarDropdown" class="nav-link dropdown-toggle small" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
						{{ Auth::user()->email }}<span class="caret"></span>
					</a>

					<div class="dropdown-menu dropdown-menu-right p-2" aria-labelledby="navbarDropdown">
						<a class="dropdown-item btn btn-light text-center" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span class="small text-muted">{{__('se déconnecter')}}</span></a>

						<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
							@csrf
						</form>
					</div>
				</li>

			</ul>
		</div>
	</div>
</nav>
