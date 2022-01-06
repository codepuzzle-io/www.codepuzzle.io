@include('inc-top')
<html lang="{{ app()->getLocale() }}">
<head>
	@include('inc-meta')
	@include('inc-matomo')
    <title>CODE PUZZLE</title>
</head>
<body>
	@php
		$lang_switch = '<a href="lang/fr" class="kbd mr-1">fr</a><a href="lang/en" class="kbd">en</a>';
	@endphp
	@include('inc-nav')

	<p class="text-monospace text-muted small text-center mt-3">
		{!!__('Générateur de puzzles de Parsons')!!}
	</p>

	<p class="text-monospace text-muted small text-center mt-4">
		<a class="btn btn-light btn-sm" href="https://www.codepuzzle.io/p/NHVL" target="_blank" role="button">{{__('exemple 1')}}</a>
		<a class="btn btn-light btn-sm" href="https://www.codepuzzle.io/p/39K2" target="_blank" role="button">{{__('exemple 2')}}</a>
	</p>

	<p class="text-center mt-5 pt-3">
		<img src="{{ asset('img/demo.gif') }}" class="img-fluid" />
	</p>

	@include('inc-footer')
	@include('inc-bottom-js')

</body>
</html>
