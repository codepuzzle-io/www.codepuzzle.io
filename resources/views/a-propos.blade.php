@include('inc-top')
<html lang="{{ app()->getLocale() }}">
<head>
	@php
		$description = __('Générateur et gestionnaire de puzzles de Parsons') . ' | ' . ucfirst(__('à propos'));
		$description_og = '| ' . ucfirst(__('à propos'));
	@endphp
	@include('inc-meta')
    <title>{{ strtoupper(config('app.name')) }} | {{ ucfirst(__('à propos')) }}</title>
</head>
<body>

	<?php
	$lang = (app()->getLocale() == 'fr') ? '/':'/en';
	?>

	@include('inc-nav')

	<div class="container mt-5 mb-3">

		<div class="row">

			<div class="col-md-2 text-center">
				<a class="btn btn-light btn-sm" href="{{ $lang }}" role="button"><i class="fas fa-arrow-left"></i></a>
			</div>

			<div class="col-md-10 text-muted text-justify">
				{{__('wikipedia_parsons')}} <a href="https://en.wikipedia.org/wiki/Parsons_problems" class="wikipedia small" target="_blank">w</a>
			</div>
		</div>
	</div>


	@include('inc-bottom-js')

</body>
</html>
