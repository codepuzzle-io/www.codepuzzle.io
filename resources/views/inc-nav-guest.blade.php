<nav class="navbar navbar-expand-md navbar-light">
	<div class="container">
		<div class="text-center">
			<div>
				<img src="{{ asset('img/nav-home.png') }}" width="40" />
				<br />
				<span class="text-monospace small" style="color:#c5c7c9;">puzzle</span>
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
				</li>

			</ul>
		</div>
	</div>
</nav>
