<?php
$classe_eleve = App\Models\Classes_eleve::where('jeton_eleve', $jeton_eleve)->first();
if (!$classe_eleve){
    echo "<pre>Ce code n'existe pas</pre>";
    exit();
}
$activites_eleve = App\Models\Classes_activite::where('jeton_eleve', $jeton_eleve)->pluck('jeton_activite')->all();
$classe = App\Models\Classe::find($classe_eleve->id_classe);

$activites_classe = unserialize($classe->activites);


include('lib/parsedownmath/ParsedownMath.php');
$Parsedown = new ParsedownMath([
    'math' => [
        'enabled' => true, // Write true to enable the module
        'matchSingleDollar' => true // default false
    ]
]);
?>
<!doctype html>
<html lang="fr">
<head>
    @include('inc-meta')
    <meta name="robots" content="noindex">
    <title>CONSOLE ÉLÈVE</title>
</head>
<body>

	<div class="container mb-5">
		<div class="row pt-3">
			<div class="col-md-10 offset-md-1 pl-4 pr-4">
                <div class="text-monospace text-center">
                    <span class="text-muted small">Code individuel à conserver</span><br />
                    <kbd class="pl-4 pr-4" style="font-size:220%">{{ $jeton_eleve }}</kbd><br />
                    <span class="text-danger small"><i class="fas fa-exclamation-circle"></i> Ne pas partager ce code</span>
                </div>

                <div class="mt-5 text-monospace font-weight-bold">{{strtoupper($classe->nom_classe)}}</div>
                @if(sizeof($activites_classe) > 1)
                    <div class="text-monospace pt-4 pb-2">{{strtoupper(__('ACTIVITÉS DE LA CLASSE'))}}</div>
                    <table class="table table-borderless table-sm text-monospace small m-0">
                        @foreach($activites_classe as $code)
                            @php
                            if (substr($code, 0, 1) == 'D') {
                                $label = "défi";
                                $activite_info = App\Models\Defi::where('jeton', substr($code, 1))->first();
                            }
                            if (substr($code, 0, 1) == 'P') {
                                $label = "puzzle";
                                $activite_info = App\Models\Puzzle::where('jeton', substr($code, 1))->first();
                            }
                            @endphp
                            <tr>
                                <td><a class="pl-2 pr-2" data-toggle="collapse" href="#collapse_1_{{$loop->iteration}}" aria-expanded="false" aria-controls="collapse_1_{{$loop->iteration}}"><i class="fas fa-bars"></i></a></td>
                                <td><div class="text-center bg-primary rounded text-white" style="width:60px;">{{ $label ?? "" }}</div></td>
                                <td style="width:100%">{{ $activite_info->titre_eleve ?? 'Activité '.$code }}</td>
                                <td><a href="/{{ $code }}/{{ $jeton_eleve }}" target="_blank">www.codepuzzle.io/{{ $code }}/{{ $jeton_eleve }}</a></td>
                                <td class="text-monospace pl-4">
                                    @if (in_array($code, $activites_eleve))
                                        <div class="bg-success text-white rounded text-center" style="width:60px;">fait</div>
                                    @else
                                        <div class="bg-light text-secondary rounded text-center" style='width:60px;'>à&nbsp;faire</div>
                                    @endif
                                </td>    
                            </tr>
                            <tr>
                                <td colspan="5"><div class="collapse" id="collapse_1_{{$loop->iteration}}"><div class="consignes mathjax rounded bg-white pl-3 pr-3 pt-3 pb-1 mb-3">{!! trim($Parsedown->text($activite_info->consignes_eleve)) !!}</div></div></td>
                            </tr>                            
                        @endforeach
                    </table>
                @else
                    <div class='text-muted small text-monospace'>Pas d'activités proposées pour le moment.</div>
                @endif


                @if(sizeof($activites_eleve) > 0)
                    <div class="text-monospace pt-4 pb-2">{{strtoupper(__('AUTRES ACTIVITÉS'))}}</div>
                    <table class="table table-borderless table-sm text-monospace small m-0">
                        @foreach($activites_eleve as $code)
                            @if (!in_array($code, $activites_classe))
                                @php
                                if (substr($code, 0, 1) == 'D') {
                                    $label = "défi";
                                    $activite_info = App\Models\Defi::where('jeton', substr($code, 1))->first();
                                }
                                if (substr($code, 0, 1) == 'P') {
                                    $label = "puzzle";
                                    $activite_info = App\Models\Puzzle::where('jeton', substr($code, 1))->first();
                                }
                                @endphp
                                <tr>
                                    <td><a class="pl-2 pr-2" data-toggle="collapse" href="#collapse_2_{{$loop->iteration}}" aria-expanded="false" aria-controls="collapse_2_{{$loop->iteration}}"><i class="fas fa-bars"></i></a></td>
                                    <td><div class="text-center bg-primary rounded text-white" style="width:60px;">{{ $label ?? "" }}</div></td>
                                    <td style="width:100%">{{ $activite_info->titre_eleve ?? 'Activité '.$code }}</td>
                                    <td><a href="/{{ $code }}/{{ $jeton_eleve }}" target="_blank">www.codepuzzle.io/{{ $code }}/{{ $jeton_eleve }}</a></td>
                                    <td class="text-monospace pl-4"><div class="bg-success text-white rounded text-center" style="width:60px;">fait</div></td>    
                                </tr>
                                <tr>
                                    <td colspan="5"><div class="collapse" id="collapse_2_{{$loop->iteration}}"><div class="consignes mathjax rounded bg-white pl-3 pr-3 pt-3 pb-1 mb-3">{!! trim($Parsedown->text($activite_info->consignes_eleve)) !!}</div></div></td>
                                </tr>
                            @endif
                        @endforeach
                    </table>
                @else
                    <div class='text-muted small text-monospace'>Pas d'activités validées pour le moment.</div>
                @endif


            </div>
        </div>
	</div><!-- /container -->

    <br />
    <br />
    <br />
    <br />

    @include('inc-bottom-js')

	<script>
		MathJax = {
			tex: {
				inlineMath: [['$', '$'], ['\\(', '\\)']],
				displayMath: [ ['$$','$$'], ["\\[","\\]"] ],
				processEscapes: true
			},
			options: {
				ignoreHtmlClass: "no-mathjax",
				processHtmlClass: "mathjax"
			}
		};        
	</script>  
	<script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
	<script type="text/javascript" id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-svg.js"></script>     

    <script>
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		  return new bootstrap.Tooltip(tooltipTriggerEl)
		})
	</script>

</body>
</html>
