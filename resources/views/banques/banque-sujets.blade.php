<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    @php
        $description = 'Banque de sujets';
        $description_og = 'Banque de sujets';
    @endphp
    @include('inc-meta')
    <title>Banque de sujets</title>
</head>
<body class="no-mathjax">

    @include('inc-nav')
    <?php
    $lang ='/';
    ?>

	<div class="container mt-4 mb-5">

		<div class="row">

			<div class="col-md-2">
                <div class="text-right"><a class="btn btn-light btn-sm mb-4" href="{{$lang}}" role="button"><i class="fas fa-arrow-left"></i></a></div>
                <a class="btn btn-success btn-sm mb-4 text-monospace" href="{{route('sujet-creer-get')}}" role="button" style="width:100%;">{{__('créer un sujet')}}</a>
            </div>

			<div class="col-md-10 pl-3 pr-3">

                <h1>Banque de sujets</h1>

                <pre>en travaux</pre>

            </div>
        </div>
	</div><!-- /container -->

	@include('inc-bottom-js')
</body>
</html>
