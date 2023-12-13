<?php
/*
$classe = App\Models\Classe::where('jeton_secret', $jeton_secret)->first();
if (!$classe){
    echo "<pre>Cet classe n'existe pas</pre>";
    exit();
}
$activites_eleve = App\Models\Classes_eleve::where('id_classe', $classe->id)->orderby('eleve')->get();
*/
?>
<!doctype html>
<html lang="fr">
<head>
    @include('inc-meta')
    <title>CONSOLE ELEVE</title>
</head>
<body>

	<div class="container mb-5">

		<div class="row pt-3">



			<div class="col-md-10 pl-4 pr-4">






                <div class="text-monospace pt-4">{{strtoupper(__('ACTIVITÉS'))}}</div>
                <div id="frame" class="frame">

                
                </div>

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
