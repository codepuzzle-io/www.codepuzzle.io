@include('inc-top')
<html lang="{{ app()->getLocale() }}">
<head>
	@php
		$description = __('Générateur et gestionnaire de Textes Puzzles');
		$description_og = '';
	@endphp
	@include('inc-meta')
    <title>{{ strtoupper(config('app.name')) }} | {{__('Générateur et gestionnaire de puzzles de Parsons')}}</title>
</head>
<body>
	@php
		$lang_switch = '<a href="lang/fr" class="kbd mr-1">fr</a><a href="lang/en" class="kbd">en</a>';
	@endphp
	@include('inc-nav')

	<div class="container mt-3">
		<div class="row">
			<div class="col-md-4 offset-md-2 intro text-monospace">
				<h1><a class="navbar-brand" href="/"><img src="{{ asset('img/txtpuzzle.png') }}" width="150" alt="CODE PUZZLE" /></a></h1>
				<h2>{!!__('Générateur de puzzles de Parsons', ['link' => ''])!!}</h2>
				<div class="mx-auto mt-4 text-center" style="width:160px">
					<a class="btn btn-success btn-sm btn-block" href="{{ route('guest-puzzle-creer-get')}}" role="button">{{__('créer un puzzle')}}</a>
					<a class="btn btn-outline-secondary btn-sm btn-block mt-5 mb-1" style="font-size:80%;opacity:0.4" href="{{route('register')}}" role="button">{{__('créer un compte')}}</a>
					<span style="font-size:70%;color:#dadfe2;">{{__('pour créer, sauvegarder, modifier et partager des puzzles')}}</span>
				</div>
				<!--
				<p class="small text-justify">{{__('wikipedia_parsons')}} <a href="" target="_blank" class="wikipedia">w</a></p>
				-->
			</div>
			<div class="col-md-4 pt-3 text-center">
				<img src="{{ asset('img/presentation.png') }}" width="250" alt="TXTPUZZLE" />
			</div>
		</div>
	</div>


	<p class="text-monospace text-muted small text-center mt-5">
		<a class="btn btn-light btn-sm mr-1" href="https://www.txtpuzzle.net/p/ERDZ" target="_blank" role="button">exemple</a>
	</p>

	<div class="text-center">
	<video autoplay loop muted playsinline>
		<source src="{{ asset('txtpuzzle.mp4') }}" type="video/mp4">
	</video>
	</div>

	@include('inc-footer')
	@include('inc-bottom-js')

</body>
</html>
