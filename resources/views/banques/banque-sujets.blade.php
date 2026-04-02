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
        '[Bac NSI 2024] Métropole - Réunion - Mayotte - Jour 1 - 24-NSIJ1ME1'   => 'S2K3Y6',
        '[Bac NSI 2024] Métropole - Réunion - Mayotte - Jour 2 - 24-NSIJ2ME1'   => 'SSDHAQ',
        '[Bac NSI 2024] Asie - Jour 1 - 24-NSIJ1JA1'                            => 'S7PYZQ',
        '[Bac NSI 2024] Asie - Jour 2 - 24-NSIJ2JA1'                            => 'SWU7SZ',
        '[Bac NSI 2024] Amérique du Nord - Jour 1 - 24-NSIJ1AN1'                => 'S2WNRK',
        '[Bac NSI 2024] Amérique du Nord - Jour 2 - 24-NSIJ2AN1'                => 'SA6T2M',
        '[Bac NSI 2024] Polynésie française - Jour 1 - 24-NSIJ1PO1'             => 'SSDTU2',
        '[Bac NSI 2024] Polynésie française - Jour 2 - 24-NSIJ2PO1'             => 'SBQGCN',
        '[Bac NSI 2024] Centres étrangers - Groupe 1 - Jour 1 - 24-NSIJ1G11'    => 'SARP2E',
        '[Bac NSI 2024] Centres étrangers - Groupe 1 - Jour 2 - 24-NSIJ2G11'    => 'SRQU25',
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

                <div class="text-monospace">
                    <b>ÉPREUVE PRATIQUE 2026</b>
                    <div class=" mb-4 border rounded p-3">
                        <ul class="mb-0">
                            <li>
                                <b>Sujet 0-1</b>
                                <ul style="list-style-type:square;">
                                    <li class="small">Sujet : <a href="https://www.codepuzzle.io/SDNFBK" target="_blank">www.codepuzzle.io/SDNFBK</a></li>
                                    <li class="small">Sujet + copie à fournir aux élèves pour un entraînement en autonomie : <a href="https://www.codepuzzle.io/K31L24 " target="_blank">www.codepuzzle.io/K31L24 </a></li>
                                </ul>
                            </li>
                            <li class="mt-2">
                                <b>Sujet 0-2</b>
                                <ul style="list-style-type:square;">
                                    <li class="small">Sujet : <a href="https://www.codepuzzle.io/SYQ7BP" target="_blank">www.codepuzzle.io/SYQ7BP</a></li>
                                    <li class="small">Sujet + copie à fournir aux élèves pour un entraînement en autonomie : <a href="https://www.codepuzzle.io/K31L1U " target="_blank">www.codepuzzle.io/K31L1U </a></li>
                                </ul>
                            </li>
                            <li class="mt-2">
                                <b>Sujet 0-3</b>
                                <ul style="list-style-type:square;">
                                    <li class="small">Sujet : <a href="https://www.codepuzzle.io/SZ5F2Q" target="_blank">www.codepuzzle.io/SZ5F2Q</a></li>
                                    <li class="small">Sujet + copie à fournir aux élèves pour un entraînement en autonomie : <a href="https://www.codepuzzle.io/K31L1V" target="_blank">www.codepuzzle.io/K31L1V</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="text-monospace">
                    <b>SUJETS BAC</b>
                    <ul>
                        @foreach($sujets AS $titre => $code)
                            <li>{{$titre}}: <a href="/{{$code}}" target="_blank">www.codepuzzle.io/{{$code}}</a></li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </div>
	</div><!-- /container -->

	@include('inc-bottom-js')
</body>
</html>
