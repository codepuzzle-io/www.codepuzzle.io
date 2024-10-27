@php
$lang = (app()->getLocale() == 'fr') ? '/':'/en';
@endphp
<nav class="navbar navbar-expand-md navbar-light mt-1">
	<div class="container" style="align-items: flex-start;">

		<div class="col-md-6">

			<div class="text-left pt-1 pr-3" style="float:left;">
				<a href="https://mastodon.social/@codepuzzle" class="d-block mb-1" target="_blank" data-toggle="tooltip" data-placement="auto" title="mastodon"><svg xmlns="http://www.w3.org/2000/svg" height="20" width="17.5" viewBox="0 0 448 512"><path fill="#dae0e5" d="M433 179.1c0-97.2-63.7-125.7-63.7-125.7-62.5-28.7-228.6-28.4-290.5 0 0 0-63.7 28.5-63.7 125.7 0 115.7-6.6 259.4 105.6 289.1 40.5 10.7 75.3 13 103.3 11.4 50.8-2.8 79.3-18.1 79.3-18.1l-1.7-36.9s-36.3 11.4-77.1 10.1c-40.4-1.4-83-4.4-89.6-54a102.5 102.5 0 0 1 -.9-13.9c85.6 20.9 158.7 9.1 178.8 6.7 56.1-6.7 105-41.3 111.2-72.9 9.8-49.8 9-121.5 9-121.5zm-75.1 125.2h-46.6v-114.2c0-49.7-64-51.6-64 6.9v62.5h-46.3V197c0-58.5-64-56.6-64-6.9v114.2H90.2c0-122.1-5.2-147.9 18.4-175 25.9-28.9 79.8-30.8 103.8 6.1l11.6 19.5 11.6-19.5c24.1-37.1 78.1-34.8 103.8-6.1 23.7 27.3 18.4 53 18.4 175z"></path></svg></a>
				<a href="https://x.com/codepuzzleio" class="d-block" target="_blank" data-toggle="tooltip" data-placement="auto" title="X / twitter"><svg xmlns="http://www.w3.org/2000/svg" height="20" width="17.5" viewBox="0 0 448 512"><path fill="#dae0e5" d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64zm297.1 84L257.3 234.6 379.4 396H283.8L209 298.1 123.3 396H75.8l111-126.9L69.7 116h98l67.7 89.5L313.6 116h47.5zM323.3 367.6L153.4 142.9H125.1L296.9 367.6h26.3z"></path></svg></a>
			</div>
			
			<h1 class="text-left"><a class="navbar-brand" href="{{ $lang }}"><img src="{{ asset('img/code-puzzle-welcome.png') }}" width="220" alt="CODE PUZZLE - PYTHON" /></a></h1>
			
			<button class="navbar-toggler mb-2" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('navigation') }}">
				<span class="navbar-toggler-icon"></span>
			</button>

		</div>

		<div class="col-md-6">

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav ml-auto text-monospace">
					@guest
						<li class="nav-item">
							<a class="btn btn-light btn-xs d-block text-muted mb-2" href="{{ route('login') }}">{{__('se connecter')}}</a>
							<a class="btn btn-outline-secondary btn-xs d-block" style="opacity:0.5" href="{{route('register')}}" role="button" data-toggle="tooltip" data-placement="left" title="[optionnel] Pour créer, sauvegarder, modifier et partager les activités proposées aux élèves">{{__('créer un compte')}}</a>
						</li>
					@else
						<li class="nav-item">
							<a class="btn btn-light btn-xs d-block text-muted mb-2" href="{{ route('console') }}">{{__('console')}}</a>
							<a class="btn btn-outline-secondary btn-xs d-block" style="opacity:0.5" href="{{ route('logout') }}"
							   onclick="event.preventDefault();document.getElementById('logout-form').submit();">
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
