<?php
$classe_eleve = App\Models\Classes_eleve::where('jeton_eleve', $jeton_eleve)->first();
if (!$classe_eleve){
    echo "<pre>Ce code n'existe pas</pre>";
    exit();
}
$activites_eleve = App\Models\Classes_activite::where('jeton_eleve', $jeton_eleve)->pluck('jeton_activite')->all();
$classe = App\Models\Classe::find($classe_eleve->id_classe);

$activites_codes = unserialize($classe->activites);
?>
<!doctype html>
<html lang="fr">
<head>
    @include('inc-meta')
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
                @if(sizeof($activites_codes) != 0)
                    <div class="text-monospace pt-4">{{strtoupper(__('ACTIVITÉS DE LA CLASSE'))}}</div>
                    <div id="frame" class="frame">
                        <table class="table table-hover table-borderless table-sm text-monospace small m-0">
                            @foreach($activites_codes as $code)
                                @php
                                if (substr($code, 0, 1) == 'D') {
                                    $activite_info = App\Models\Defi::where('jeton', substr($code, 1))->first();
                                }
                                if (substr($code, 0, 1) == 'P') {
                                    $activite_info = App\Models\Puzzle::where('jeton', substr($code, 1))->first();
                                }
                                @endphp
                                <tr>
                                    <td style="width:100%">{{ $activite_info->titre_eleve ?? 'Activité '.$code }}</td>
                                    <td><a href="/{{ $code }}/{{ $jeton_eleve }}" target="_blank">www.codepuzzle.io/{{ $code }}/{{ $jeton_eleve }}</a></td>
                                    <td class="text-monospace small pl-4">
                                        @if (in_array($code, $activites_eleve))
                                            <div class="bg-success text-white rounded text-center pl-3 pr-3">fait</div>
                                        @else
                                            <div class="bg-light text-secondary rounded text-center pl-3 pr-3">non&nbsp;fait</div>
                                        @endif
                                    </td>    
                                </tr>
                            @endforeach
                        </table>
                    </div>
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
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		  return new bootstrap.Tooltip(tooltipTriggerEl)
		})
	</script>

</body>
</html>
