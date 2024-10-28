<?php
$classe = App\Models\Classe::where('jeton_secret', $jeton_secret)->first();
if (!$classe){
    echo "<pre>Cet classe n'existe pas</pre>";
    exit();
}

// tous les eleves de la classe
$eleves = App\Models\Classes_eleve::where('id_classe', $classe->id)->orderby('eleve')->get();


// activites des eleves
$liste_activites_eleves = [];
foreach($eleves AS $eleve) {
    $activites_eleve = [];
    $activites = App\Models\Classes_activite::where('jeton_eleve', $eleve->jeton_eleve)->get();
    foreach($activites AS $activite) {
        if (substr($activite->jeton_activite, 0, 1) == 'D') {
            $activite_info = App\Models\Defi::where('jeton', substr($activite->jeton_activite, 1))->first();
        }
        if (substr($activite->jeton_activite, 0, 1) == 'P') {
            $activite_info = App\Models\Puzzle::where('jeton', substr($activite->jeton_activite, 1))->first();
        }
        $activites_eleve = array_merge($activites_eleve, [$activite->jeton_activite => $activite_info->titre_enseignant]);
    }
    $liste_activites_eleves = array_merge($liste_activites_eleves, $activites_eleve);
}
asort($liste_activites_eleves);
$liste_activites_eleves = array_unique($liste_activites_eleves);


// activites de la classe
$liste_activites_classe = [];
if (!empty(array_filter(unserialize($classe->activites)))) {
    foreach(unserialize($classe->activites) AS $jeton_activite) {
        if (substr($jeton_activite, 0, 1) == 'D') {
            $activite_info = App\Models\Defi::where('jeton', substr($jeton_activite, 1))->first();
        }
        if (substr($jeton_activite, 0, 1) == 'P') {
            $activite_info = App\Models\Puzzle::where('jeton', substr($jeton_activite, 1))->first();
        }
        $liste_activites_classe = array_merge($liste_activites_classe, [$jeton_activite => $activite_info->titre_enseignant]);
    }
}
asort($liste_activites_classe);


// autres activites
$liste_activites_autres = array_diff($liste_activites_eleves, $liste_activites_classe);

?>
<!doctype html>
<html lang="fr">
<head>
    @include('inc-meta')
    <meta name="robots" content="noindex">
    <title>CLASSE | CONSOLE</title>
</head>
<body>

    @include('inc-nav')

	<div class="container">

		<div class="row pt-3">

            <div class="col-md-1 text-right">
                <div class=" mb-3">
                    @if(Auth::check())
                    <a class="btn btn-light btn-sm" href="/console/classes" role="button"><i class="fas fa-arrow-left"></i></a>
                    @else
                    <a class="btn btn-light btn-sm" href="/" role="button"><i class="fas fa-arrow-left"></i></a>
                    @endif
                </div>

                <div class="mt-2 mb-4"><a class="btn btn-dark btn-sm" href="/classe-modifier/{{$jeton_secret}}" role="button" data-toggle="tooltip" data-placement="right" title="{{__('modifier')}}"><i class="fa-solid fa-pen"></i></a></div>

            </div>

            @if($classe->user_id == 0 OR !Auth::check())
            <div class="col-md-10">
                <div id="frame" class="frame">
                    <div class="row">
                        <div class="col-md-6 offset-md-3 text-monospace pt-3 pb-3">
                            @if(isset($_GET['i']))
                                <div class="text-danger text-center font-weight-bold m-2">SAUVEGARDEZ LES INFORMATIONS CI-DESSOUS AVANT DE QUITTER CETTE PAGE</div>
                            @endif
                            <div class="text-center font-weight-bold">lien secret</div>
                            <div class="text-center p-2 text-break align-middle border border-danger rounded"><a href="/classe-console/{{strtoupper($classe->jeton_secret)}}" target="_blank" class="text-danger">www.codepuzzle.io/classe-console/{{strtoupper($classe->jeton_secret)}}</a></div>
                            <div class="small text-muted p-1"><span class="text-danger"><i class="fas fa-exclamation-circle"></i> Ne pas partager ce lien. </span> Il permet de revenir sur cette page.</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="col-md-2">
                <div class="text-monospace" style="color:silver;font-size:70%;margin-bottom:-5px;">classe</div>
                <div class="text-monospace font-weight-bold" style="font-size:120%;">{{strtoupper($classe->nom_classe)}}</div>
                <div class="text-monospace small">
                    <ul>
                        <li>{{ count($eleves)}} élèves</li>
                        <li>{{ count($liste_activites_classe)}} activités</li>
                    </ul>
                </div>
            </div>
            
        </div><!-- /row -->

        <div class="row pt-2">
            <div class="col-md-10 offset-md-1">
                <div class="text-monospace">{{strtoupper(__('SUIVI DES ACTIVITÉS'))}}</div>
                @if (!empty($liste_activites_classe) OR !empty($liste_activites_autres))
                    @if (!empty($liste_activites_classe))
                        <div class="text-monospace text-muted small">Activités de la classe: {{ implode(', ', array_keys($liste_activites_classe)) }}</div>
                    @endif
                    @if (!empty($liste_activites_autres))
                        <div class="text-monospace text-muted small">Autres activités: {{ implode(', ', array_keys($liste_activites_autres)) }}</div>
                    @endif
                @endif
            </div>
        </div><!-- /row -->

    </div><!-- /container -->

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <!-- SUIVI DES ACTIVITÉS -->
                <div>
                @if (!empty($liste_activites_classe) OR !empty($liste_activites_autres))
                    <table class="mt-1 table table-striped table-bordered table-hover table-sm text-monospace small">

                        <tr>
                            <td style="padding:1px"></td>
                            <?php
                            foreach($liste_activites_classe AS $activite_jeton => $activite_nom) {
                                echo '<td style="padding:4px 1px 8px 1px;vertical-align:middle;writing-mode:vertical-rl;transform:rotate(-180deg);"><a href="/'. $activite_jeton . '" target="_blank">' . $activite_nom . '&nbsp;<sup>*</sup></a></td>';
                            }
                            foreach($liste_activites_autres AS $activite_jeton => $activite_nom) {
                                echo '<td style="padding:4px 1px 8px 1px;vertical-align:middle;writing-mode:vertical-rl;transform:rotate(-180deg);"><a href="/'. $activite_jeton . '" target="_blank">' . $activite_nom . '</a></td>';
                            }
                            ?>
                        </tr>

                        <?php
                        foreach($eleves AS $eleve) {
                            echo '<tr>';
                            echo '<td style="padding:1px 1px 1px 6px" nowrap style="vertical-align:middle;"><a href="/@/'.strtoupper($eleve->jeton_eleve).'" target="_blank">' . $eleve->eleve . '</a></td>';
                        
                            foreach($liste_activites_classe AS $activite_jeton => $activite_nom) {
                                echo '<td style="padding:1px">';
                                $validations = App\Models\Classes_activite::where([['jeton_eleve', $eleve->jeton_eleve], ['jeton_activite', $activite_jeton]])->get();
                                $popover = "";
                                foreach ($validations as $validation){
                                    $popover .= "<div class='bg-light p-1 pl-2 pr-2 rounded m-1 text-monospace'>";
                                    $popover .= substr($validation->created_at, 0, 10);
                                    $popover .= "<a href='/classe-eleve-activite-supprimer/".Crypt::encryptString($validation->id)."' class='ml-3 text-muted' style='font-size:90%;'><i class='fas fa-trash'></i></a>";
                                    $popover .= "</div>";
                                }
                                if (sizeof($validations) != 0) {
                                    echo '<div class="validation_info bg-success text-white rounded text-center small" style="padding:2px;cursor:pointer;" data-container="body" data-toggle="popover" data-placement="top" data-content="'.$popover.'">'.sizeof($validations).'</div>';
                                } else {
                                    echo '&nbsp;';
                                }
                                echo '</td>';
                            }

                            foreach($liste_activites_autres AS $activite_jeton => $activite_nom) {
                                echo '<td style="padding:1px">';
                                $validations = App\Models\Classes_activite::where([['jeton_eleve', $eleve->jeton_eleve], ['jeton_activite', $activite_jeton]])->get();
                                $popover = "";
                                foreach ($validations as $validation){
                                    $popover .= "<div class='bg-light p-1 pl-2 pr-2 rounded m-1 text-monospace'>";
                                    $popover .= substr($validation->created_at, 0, 10);
                                    $popover .= "<a href='/classe-eleve-activite-supprimer/".Crypt::encryptString($validation->id)."' class='ml-3 text-muted' style='font-size:90%;'><i class='fas fa-trash'></i></a>";
                                    $popover .= "</div>";
                                }
                                if (sizeof($validations) != 0) {
                                    echo '<div class="validation_info bg-success text-white rounded text-center small" style="padding:2px;cursor:pointer;" data-container="body" data-toggle="popover" data-placement="top" data-content="'.$popover.'">'.sizeof($validations).'</div>';
                                } else {
                                    echo '&nbsp;'; 
                                }
                                echo '</td>';
                            }                                

                            echo '</tr>';
                        }
                        ?>

                    </table>
                @endif
                </div>
                <!-- /SUIVI DES ACTIVITÉS -->
            
            </div>
        </div><!-- /row -->
    </div><!-- /container -->
    
    <div class="container">
        <div class="row">
            <div class="col-md-10 offset-md-1">

                @if (empty($liste_activites_classe) AND empty($liste_activites_autres))
                    <div class='text-muted small text-monospace'>Aucune activité n'a été enregistrée pour le moment.</div>
                @endif

                <!-- /ACTIVITES -->
                <div class="text-monospace pt-4">{{strtoupper(__('ACTIVITÉS DE LA CLASSE'))}}</div>
                <div class="text-monospace text-muted small" style="border:silver solid 1px;border-radius:4px;padding:10px;text-align:justify;">
                    Deux façons de proposer des activités aux élèves:
                    <ul class="mb-0">
                        <li>Ajouter des activités dans la classe (cliquer sur "modifier" pour ajouter des activités). Ces activités apparaîtront ci-dessous et dans la console des élèves de la classe.</li>
                        <li>Ajouter '<b>/@</b>' à la fin de l'adresse d'un défi ou d'un puzzle et fournir cette adresse aux élèves. Avec une telle adresse, les élèves seront invités à saisir le code individuel que vous leur aurez fourni (voir tableau des élèves ci-dessous). Par exemple, si l'adresse d'un défi est '<b>https://www.codepuzzle/DGD8F41W</b>', l'adresse à donner aux élèves est '<b>https://www.codepuzzle/DGD8F41W/@</b>'. De même avec l'adresse d'un puzzle.</li>
                    </ul>
                </div>
                <div class="pt-2 text-monospace">
                    <?php                    
                    if (!empty($liste_activites_classe)) {
                        echo '<div class="frame">';
                        echo '<table class="table table-hover table-borderless table-sm text-monospace small m-0">';
                        foreach($liste_activites_classe AS $activite_jeton => $activite_nom) {
                            if (substr($activite_jeton, 0, 1) == 'D') {
                                $activite_info = App\Models\Defi::where('jeton', substr($activite_jeton, 1))->first();
                                $label = "défi";
                            }
                            if (substr($activite_jeton, 0, 1) == 'P') {
                                $activite_info = App\Models\Puzzle::where('jeton', substr($activite_jeton, 1))->first();
                                $label = "puzzle";
                            }
                            echo '<tr>';
                            echo '<td><div class="text-center pl-2 pr-2 bg-primary rounded text-white">'.$label.'</div></td>';
                            echo '<td style="width:100%">' . $activite_info->titre_enseignant . '</td><td><a href="/' . $activite_jeton . '" target="_blank">www.codepuzzle.io/' . $activite_jeton . '</a></td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                        echo '</div>';
                    } else {
                        echo "<div class='text-muted small'>Pas d'activités proposées. Cliquer sur \"modifier\" pour ajouter des activités à proposer aux élèves.</div>";
                    }
                    ?>
                </div>
                <!-- /ACTIVITES -->

                <!-- ELEVES -->
                <div class="text-monospace pt-4">{{strtoupper(__('ÉLÈVES'))}}</div>
                @if (sizeof($eleves) != 0)
                    <div class="pt-2">
                        <div class="frame pt-2">
                            <table class="table-hover table-borderless table-sm text-monospace small m-0">
                                <thead>
                                    <tr>
                                        <th class="font-weight-bold pb-2" style="width:100%">identifiant</th>
                                        <th class="font-weight-bold pb-2">console élève</th>
                                        <th class="font-weight-bold pb-2">code&nbsp;individuel</th>
                                        <th class="font-weight-bold pb-2"></th>
                                    </tr>  
                                </thead>    
                                <tbody>                  
                                    @foreach($eleves AS $eleve)
										<?php
										$nb_activites = App\Models\Classes_activite::where([['jeton_eleve', $eleve->jeton_eleve]])->count();
										?>
                                        <tr>                                
                                            <td>{{$eleve->eleve}}</td>
                                            <td nowrap><a href="/@/{{strtoupper($eleve->jeton_eleve)}}" target="_blank">www.codepuzzle.io/@/{{strtoupper($eleve->jeton_eleve)}}</a></td>
                                            <td class="text-monospace text-center">{{strtoupper($eleve->jeton_eleve)}}</td>
											<td>
												<div class="validation_info bg-success text-white rounded text-center small" style="padding:2px 6px 2px 6px;">
													{{ $nb_activites }}
												</div>
											</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class='text-monospace text-muted small'>Auncun élève dans cette classe. Cliquer sur "modifier" pour ajouter des élèves.</div>
                @endif
                <!-- /ELEVES -->

                <br />
                <br />
                <br />
                <br />

            </div>
        </div><!-- row -->
	</div><!-- /container -->

    @include('inc-bottom-js')

    <script>
        $(".validation_info").popover({
            html: true,
            sanitize: false
        });

        jQuery(function ($) {
            $("[data-toggle='popover']").popover({trigger: "hover"}).click(function (event) {
                event.stopPropagation();

            }).on('inserted.bs.popover', function () {
                $(".popover").click(function (event) {
                event.stopPropagation();
                })
            })

            $(document).click(function () {
                $("[data-toggle='popover']").popover('hide')
            })
        })
    </script>

    <script>
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		  return new bootstrap.Tooltip(tooltipTriggerEl)
		})
	</script>

</body>
</html>
