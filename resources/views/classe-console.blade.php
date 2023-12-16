<?php
$classe = App\Models\Classe::where('jeton_secret', $jeton_secret)->first();
if (!$classe){
    echo "<pre>Cet classe n'existe pas</pre>";
    exit();
}
$eleves = App\Models\Classes_eleve::where('id_classe', $classe->id)->orderby('eleve')->get();
?>
<!doctype html>
<html lang="fr">
<head>
    @include('inc-meta')
    <title>CLASSE | {{$classe->jeton}} | CONSOLE</title>
</head>
<body>

	<div class="container mb-5">

		<div class="row pt-3">

			<div class="col-md-2">

                <div class="text-right mb-3">
                    <a class="btn btn-light btn-sm" href="/" role="button"><i class="fas fa-arrow-left"></i></a>
                </div>

                <a class="btn btn-success btn-sm pl-3 pr-3 text-monospace" style="width:100%" href="{{route('classe-creer-get')}}" role="button">{{__('nouvelle classe')}}</a>

                <a href="https://github.com/codepuzzle-io/www.codepuzzle.io/discussions" target="_blank" role="button" class="mt-2 btn btn-light btn-sm text-left text-muted" style="width:100%;opacity:0.8;">
                	<span style="font-size:80%"><i class="fas fa-comment-alt" style="float:left;margin:4px 8px 5px 0px;"></i> {{__('discussions')}} <span style="opacity:0.6;font-size:90%;">&</span> {{__('annonces')}}</span>
                </a>

                <a href="https://github.com/codepuzzle-io/www.codepuzzle.io/issues/new/choose" target="_blank" role="button"  class="mt-1 btn btn-light text-left btn-sm text-muted" style="width:100%;opacity:0.8;">
                	<span style="font-size:80%"><i class="fas fa-bug" style="float:left;margin:4px 8px 5px 0px;"></i> {{__('signalement de bogue')}} <span style="opacity:0.6;font-size:90%;">&</span> {{__('questions techniques')}}</span>
                </a>

                <div class="mt-3 text-muted text-monospace pl-1 mb-3" style="font-size:70%;opacity:0.8;">
                	<span><i class="fa fa-envelope"></i> contact@codepuzzle.io</span>
                </div>

            </div>

			<div class="col-md-10 pl-4 pr-4">

                <div id="frame" class="frame">

                    <div class="row">
                        <div class="col-md-6 offset-md-3 text-monospace">

                            @if(isset($_GET['i']))
                                <div class="text-danger text-center font-weight-bold m-2">SAUVEGARDEZ LES INFORMATIONS CI-DESSOUS AVANT DE QUITTER CETTE PAGE</div>
                            @endif

                            <div class="text-center font-weight-bold">lien secret</div>
                            <div class="text-center p-2 text-break align-middle border border-danger rounded"><a href="/classe-console/{{strtoupper($classe->jeton_secret)}}" target="_blank" class="text-danger">www.codepuzzle.io/classe-console/{{strtoupper($classe->jeton_secret)}}</a></div>
                            <div class="small text-muted p-1"><span class="text-danger"><i class="fas fa-exclamation-circle"></i> Ne pas partager ce lien</span><br />Il permet d'accéder à la console de la classe.</div>

                        </div>
                    </div>

                </div>

                <div class="row mt-3 mb-3">
                    <div class="col-md-4 offset-4 text-center">
                        <a class="btn btn-dark btn-sm" href="/classe-modifier/{{$jeton_secret}}" role="button"><i class="fa-solid fa-pen mr-2"></i> modifier</a>
                    </div>
                </div>

                <div class="row mt-4 mb-3">
                    <div class="col-md-10 offset-1 text-monospace text-muted small" style="border:silver solid 1px;border-radius:4px;padding:10px;text-align:justify;">
                    Il suffit d'ajouter '<b>/@</b>' a la fin de l'adresse d'un défi ou d'un puzzle pour activer l'enregistrement de l'activité des élèves. Avec une telle adresse, les élèves seront invités à saisir le code individuel que vous leur aurez fourni (voir tableau ci-dessous).<br />
                    Par exemple, si l'adresse d'un défi est '<b>https://www.codepuzzle/DGD8F41W</b>', l'adresse à donner aux élèves est '<b>https://www.codepuzzle/DGD8F41W/@</b>'. De même avec l'adresse d'un puzzle.
                    </div>
                </div>                

                <div class="mt-5 text-monospace font-weight-bold">{{strtoupper($classe->nom_classe)}}</div>

                <div class="text-monospace pt-4">{{strtoupper(__('ACTIVITÉS'))}}</div>
                <div class="pt-2 text-monospace">
                    <?php                    
                    if (!empty(array_filter(unserialize($classe->activites)))) {
                        echo '<div class="frame">';
                        echo '<table class="table table-hover table-borderless table-sm text-monospace small m-0">';
                        foreach(unserialize($classe->activites) AS $code) {
                            if (substr($code, 0, 1) == 'D') {
                                $activite_info = App\Models\Defi::where('jeton', substr($code, 1))->first();
                            }
                            echo '<tr><td style="width:100%">' . $activite_info->titre_enseignant . '</td><td><a href="/' . $code . '" target="_blank">www.codepuzzle.io/' . $code . '</a></td></tr>';


                        }
                        echo '</table>';
                        echo '</div>';
                    } else {
                        echo "<div class='text-muted small'>Pas d'activités proposées. Cliquer sur \"modifier\" pour ajouter des activités à proposer aux élèves.</div>";
                    }
                    ?>
                </div>


                <div class="text-monospace pt-4">{{strtoupper(__('SUIVI DES ACTIVITÉS'))}}</div>
                <div class="pt-2">
					<?php
					$liste_activites = [];
                    
					foreach($eleves AS $eleve) {
                        $activites_eleve = [];
						$activites = App\Models\Classes_activite::where('jeton_eleve', $eleve->jeton_eleve)->get();
						foreach($activites AS $activite) {
                            if (substr($activite->jeton_activite, 0, 1) == 'D') {
                                $activite_info = App\Models\Defi::where('jeton', substr($activite->jeton_activite, 1))->first();
                            }
                            $activites_eleve = array_merge($activites_eleve, [$activite->jeton_activite => $activite_info->titre_enseignant]);
						}
						$liste_activites = array_merge($liste_activites, $activites_eleve);
					}

                    asort($liste_activites);

                    /*
					echo "<pre>";
					print_r($liste_activites);
					echo "</pre>";
                    */                  
                    if ($liste_activites) {
					?>
					<table class="table table-striped table-bordered table-hover table-sm text-monospace small">

						<tr>
							<td class="p-1"></td>
                            <?php
							foreach($liste_activites AS $activite_jeton => $activite_nom) {
								echo '<td class="p-1" style="vertical-align:middle;writing-mode:vertical-rl;transform:rotate(-180deg);"><a href="/'. $activite_jeton . '" target="_blank">' . $activite_nom . '</a></td>';
                            }
                            ?>
						</tr>

                        <?php
                        foreach($eleves AS $eleve) {

                            echo '<tr>';
                            echo '<td class="p-1" nowrap style="vertical-align:middle;">' . $eleve->eleve . '</td>';
                          
                                foreach($liste_activites AS $activite_jeton => $activite_nom) {
                                    echo '<td class="p-1">';
                                        if (App\Models\Classes_activite::where([['jeton_eleve', $eleve->jeton_eleve], ['jeton_activite', $activite_jeton]])->latest()->first()) {
                                            echo '<div class="bg-success text-white rounded text-center">&nbsp;</div>';
                                        } else {
                                            echo '&nbsp;';
                                        }
                                        echo '</td>';
                                }
                            echo '</tr>';
                        }
                        ?>

					</table>
                    <?php
                    } else {
                        echo "<div class='text-muted small text-monospace'>Aucune activité n'a été enregistrée pour le moment.</div>";
                    }
                    ?>
                </div>

                <div class="text-monospace pt-4">{{strtoupper(__('ÉLÈVES'))}}</div>
                @if (sizeof($eleves) != 0)
                    <div class="pt-2">
                        <div class="frame pt-2">
                            <table class="table-hover table-borderless table-sm text-monospace small m-0">
                                <thead>
                                    <tr>
                                        <th class="font-weight-bold pb-2" style="width:100%">identifiant</th>
                                        <th class="font-weight-bold pb-2">console</th>
                                        <th class="font-weight-bold pb-2">code&nbsp;individuel</th>
                                    </tr>  
                                </thead>    
                                <tbody>                  
                                    @foreach($eleves AS $eleve)
                                        <tr>                                
                                            <td>{{$eleve->eleve}}</td>
                                            <td nowrap><a href="/@/{{strtoupper($eleve->jeton_eleve)}}" target="_blank">www.codepuzzle.io/@/{{strtoupper($eleve->jeton_eleve)}}</a></td>
                                            <td class="text-monospace text-center">{{strtoupper($eleve->jeton_eleve)}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class='text-monospace text-muted small'>Auncun élève dans cette classe. Cliquer sur "modifier" pour ajouter des élèves.</div>
                @endif

                <br />
                <br />
                <br />
                <br />

            </div>
        </div>
	</div><!-- /container -->

    @include('inc-bottom-js')

    <script>
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		  return new bootstrap.Tooltip(tooltipTriggerEl)
		})
	</script>

</body>
</html>
