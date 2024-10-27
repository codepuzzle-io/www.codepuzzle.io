@php
$lang = (app()->getLocale() == 'fr') ? '/':'/en';
@endphp
<nav class="navbar navbar-expand-md navbar-light mt-1">
	<div class="container" style="align-items: flex-start;">

		<div class="col-md-12">
			<h1 class="text-left"><a class="navbar-brand" href="{{ $lang }}"><img src="{{ asset('img/code-puzzle.png') }}" width="140" alt="CODE PUZZLE - PYTHON" /></a></h1>
		</div>

	</div><!-- container -->
</nav>
