@include('inc-top')
<html lang="{{ app()->getLocale() }}">
<head>
	@include('inc-meta')
	@include('inc-matomo')
    <title>{{ strtoupper(config('app.name')) }}</title>
</head>
<body>
	@php
		$lang_switch = '<a href="lang/fr" class="kbd mr-1">fr</a><a href="lang/en" class="kbd">en</a>';
	@endphp
	@include('inc-nav')

	<div class="container mt-3">
		<div class="row pt-3">
			<div class="intro col-md-2">
			</div>
			<div class="intro col-md-8 text-monospace">
				<h2>{!!__('Générateur de puzzles de Parsons', ['link' => route('about')])!!}</h2>
				<div class="mx-auto mt-4 text-center" style="width:160px">
					<a class="btn btn-success btn-sm btn-block" href="{{ route('site-puzzle-creer-get')}}" role="button">{{__('créer un puzzle')}}</a>
					<a class="btn btn-outline-secondary btn-sm btn-block mt-5 mb-1" style="font-size:80%;opacity:0.4" href="{{route('register')}}" role="button">{{__('créer un compte')}}</a>
					<span style="font-size:70%;color:#dadfe2;">{{__('pour créer, sauvegarder, modifier et partager des puzzles')}}</span>
				</div>
				<!--
				<p class="small text-justify">{{__('wikipedia_parsons')}} <a href="" target="_blank" class="wikipedia">w</a></p>
				-->
			</div>
		</div>
	</div>

	<p class="text-monospace text-muted small text-center mt-5">
		<a class="btn btn-light btn-sm mr-1" href="https://www.codepuzzle.io/p/NHVL" target="_blank" role="button">{{__('exemple 1')}}</a>
		<a class="btn btn-light btn-sm ml-1" href="https://www.codepuzzle.io/p/39K2" target="_blank" role="button">{{__('exemple 2')}}</a>
	</p>

	<p class="text-center mt-3 pt-3">
		<img src="{{ asset('img/demo.gif') }}" class="img-fluid" />
	</p>

	@include('inc-footer')
	@include('inc-bottom-js')

</body>
</html>
