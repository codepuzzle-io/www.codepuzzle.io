@include('inc-top')
<html lang="{{ app()->getLocale() }}">
<head>
	@php
		$description = __('Puzzles de Parsons & Défis pour apprendre Python');
		$description_og = __('Puzzles de Parsons & Défis pour apprendre Python');
	@endphp
	@include('inc-meta')
    <title>{{ strtoupper(config('app.name')) }} | {{__('Puzzles de Parsons & Défis')}}</title>
</head>
<body>
	@php
		$lang_switch = '<a href="lang/fr" class="kbd mr-1">fr</a><a href="lang/en" class="kbd">en</a>';
	@endphp
	@include('inc-nav')

	<div class="container mt-3">
		<div class="row pt-3">

			<div class="intro col-md-4 offset-md-2 text-monospace">
				<h2>{!!__('Créer et partager des puzzles de Parsons', ['link' => route('about')])!!}</h2>
				<div class="mx-auto mt-3 text-center" style="width:160px">
					<a class="btn btn-success btn-sm btn-block" href="{{ route('puzzle-creer-get')}}" role="button">{{__('créer un puzzle')}}</a>
				</div>
			</div>
			<div class="intro col-md-4 text-monospace">
				<h2>{!!__('Créer et partager des défis', ['link' => route('about')])!!}</h2>				
				<div class="mx-auto mt-3 text-center" style="width:160px">
					<a class="btn btn-success btn-sm btn-block" href="{{ route('defi-creer-get')}}" role="button">{{__('créer un défi')}}</a>
				</div>
			</div>
		</div>


		<div class="mx-auto mt-4 text-center" style="width:160px">
			<a class="btn btn-outline-secondary btn-sm btn-block mt-5 mb-1" style="font-size:80%;opacity:0.4" href="{{route('register')}}" role="button">{{__('créer un compte')}}</a>
			<span style="font-size:70%;color:#dadfe2;">{{__('pour créer, sauvegarder, modifier et partager des puzzles et les défis')}}</span>
		</div>

		<div class="row mt-5 text-center text-monospace">
			<div class="col-md-3 offset-3">
				<div><a class="btn btn-light btn-sm mr-1" href="https://www.codepuzzle.io/PNHVL" target="_blank" role="button" style="font-size:70%">{{__('exemple 1')}}</a><a class="btn btn-light btn-sm ml-1" href="https://www.codepuzzle.io/P39K2" target="_blank" role="button" style="font-size:70%">{{__('exemple 2')}}</a></div>
				<div class="mt-3 mb-5"><img src="{{ asset('img/codepuzzle-puzzle.png') }}" class="img-fluid" /></div>
			</div>
			<div class="col-md-3">
				<div><a class="btn btn-light btn-sm mr-1" href="https://www.codepuzzle.io/DEHW2" target="_blank" role="button" style="font-size:70%">{{__('exemple 1')}}</a><a class="btn btn-light btn-sm ml-1" href="https://www.codepuzzle.io/DMH3R" target="_blank" role="button" style="font-size:70%">{{__('exemple 2')}}</a></div>
				<div class="mt-3 mb-5"><img src="{{ asset('img/codepuzzle-challenge.png') }}" class="img-fluid" /></div>
			</div>
		</div>

	</div><!--container -->

	@include('inc-footer')
	<a rel="me" href="https://mastodon.social/@codepuzzle" target="_blank"><button type="button" class="btn btn-light btn-sm text-muted ml-1 mr-1 pt-2"><i class="fa-brands fa-mastodon"></i></button></a>

	@include('inc-bottom-js')

</body>
</html>
