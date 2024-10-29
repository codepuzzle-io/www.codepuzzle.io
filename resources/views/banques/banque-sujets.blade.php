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


    $sujets = [
        '[Bac NSI 2024] Métropole - Réunion - Mayotte - Jour 1 - 24-NSIJ1ME1' => 'S2K3Y6',
        '[Bac NSI 2024] Métropole - Réunion - Mayotte - Jour 2 - 24-NSIJ2ME1' => 'SSDHAQ',
        '[Bac NSI 2024] Asie - Jour 1 - 24-NSIJ1JA1' => 'S7PYZQ',
        '[Bac NSI 2024] Asie - Jour 2 - 24-NSIJ2JA1' => 'SWU7SZ',
        '[Bac NSI 2024] Amérique du Nord - Jour 1 - 24-NSIJ1AN1' => 'S2WNRK',
        '[Bac NSI 2024] Amérique du Nord - Jour 2 - 24-NSIJ2AN1' => 'SA6T2M',
    ]
    ?>

	<div class="container mt-4 mb-5">

		<div class="row">

			<div class="col-md-2">
                <div class="text-right"><a class="btn btn-light btn-sm mb-4" href="{{$lang}}" role="button"><i class="fas fa-arrow-left"></i></a></div>
                <a class="btn btn-success btn-sm mb-4 text-monospace" href="{{route('sujet-creer-get')}}" role="button" style="width:100%;">{{__('créer un sujet')}}</a>
            </div>

			<div class="col-md-10 pl-3 pr-3">

                <h1>Banque de sujets</h1>

                <ul class="text-monospace">
                    @foreach($sujets AS $titre => $code)
                        <li>{{$titre}}: <a href="/{{$code}}" target="_blank">www.codepuzzle.io/{{$code}}</a></li>
                    @endforeach
                </ul>

            </div>
        </div>
	</div><!-- /container -->

	@include('inc-bottom-js')
</body>
</html>
