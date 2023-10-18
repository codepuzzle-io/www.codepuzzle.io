<?php
$devoir = App\Models\Devoir::where('jeton_secret', $jeton_secret)->first();
if (!$devoir){
    echo "<pre>Cet entraînement n'existe pas</pre>";
    exit();
}
$lang = ($devoir->lang == 'fr') ? '/':'/en';
?>
<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    @php
        $description = 'Devoir';
        $description_og = '| Devoir';
    @endphp
    @include('inc-meta')
    <title>ENTRAÎNEMENT</title>
</head>
<body>

    @include('inc-nav')

	<div class="container mt-4 mb-5">

		<div class="row pt-3">

			<div class="col-md-2">

                <div class="text-right"><a class="btn btn-light btn-sm mb-4" href="{{$lang}}" role="button"><i class="fas fa-arrow-left"></i></a></div>

                <a class="btn btn-success btn-sm mb-4 text-monospace" href="{{route('devoir-creer-get')}}" role="button" style="width:100%;">{{__('créer un nouveau devoir')}}</a>

                <a href="https://github.com/codepuzzle-io/www.codepuzzle.io/discussions" target="_blank" role="button" class="mt-2 btn btn-light btn-sm text-left text-muted" style="width:100%;opacity:0.8;">
                	<span style="font-size:80%"><i class="fas fa-comment-alt" style="float:left;margin:4px 8px 5px 0px;"></i> {{__('discussions')}} <span style="opacity:0.6;font-size:90%;">&</span> {{__('annonces')}}</span>
                </a>

                <a href="https://github.com/codepuzzle-io/www.codepuzzle.io/issues/new/choose" target="_blank" role="button"  class="mt-1 btn btn-light text-left btn-sm text-muted" style="width:100%;opacity:0.8;">
                	<span style="font-size:80%"><i class="fas fa-bug" style="float:left;margin:4px 8px 5px 0px;"></i> {{__('signalement de bogue')}} <span style="opacity:0.6;font-size:90%;">&</span> {{__('questions techniques')}}</span>
                </a>

                <div class="mt-3 text-muted text-monospace pl-1 mb-5" style="font-size:70%;opacity:0.8;">
                	<span><i class="fa fa-envelope"></i> contact@codepuzzle.io</span>
                </div>

            </div>

			<div class="col-md-10 pl-3 pr-3">

                <div id="frame" class="frame">

                    <div class="row">
                        <div class="col-md-12">

                            <div class="text-monospace text-danger text-center font-weight-bold m-2">SAUVEGARDEZ LES INFORMATIONS CI-DESSOUS AVANT DE QUITTER CETTE PAGE</div>

                            <table class="table table-borderless text-monospace m-0" style="border-spacing:5px;border-collapse:separate;">
                                <tr>
                                    <td class="text-center font-weight-bold p-0" style="width:33%">lien secret</td>
                                    <td class="text-center font-weight-bold p-0" style="width:33%">code secret</td>
                                    <td class="text-center font-weight-bold p-0" style="width:33%">lien élèves</td>
                                </tr>
                                <tr>
                                    <td class="text-center text-white p-2 text-break align-middle" style="background-color:#d14d41;border-radius:3px;"><a href="/console-devoir/E{{strtoupper($devoir->jeton_secret)}}" target="_blank" class="text-white">www.codepuzzle.net/console-devoir/E{{strtoupper($devoir->jeton_secret)}}</a></td>
                                    <td class="text-center text-white p-2 text-break align-middle" style="background-color:#d0a215;border-radius:3px;">{{$devoir->mot_secret}}</td>
                                    <td class="text-center text-white p-2 text-break align-middle" style="background-color:#879a39;border-radius:3px;"><a href="/E{{strtoupper($devoir->jeton)}}" target="_blank" class="text-white">www.codepuzzle.net/E{{strtoupper($devoir->jeton)}}</a></td>
                                </tr>
                                <tr>
                                    <td class="small text-muted p-0"><span class="text-danger"><i class="fas fa-exclamation-circle"></i> Ne pas partager ce lien</span><br />Il permet d'accéder à la console de l'entraînement (informations, code des élèves, correction...).</td>
                                    <td class="small text-muted p-0"><span class="text-danger"><i class="fas fa-exclamation-circle"></i> Ne pas partager ce code</span><br />Il permet de déverrouiller la copie d'un élève.</td>
                                    <td class="small text-muted p-0">Lien à fournir aux élèves.<br />QR code: <img src="https://api.qrserver.com/v1/create-qr-code/?data={{urlencode('https://www.codepuzzle.io/E' . strtoupper($devoir->jeton))}}&amp;size=200x200" style="width:50px" alt="www.codepuzzle.io/E{{strtoupper($devoir->jeton)}}" data-toggle="tooltip" data-placement="bottom" title="{{__('clic droit + Enregistrer l image sous... pour sauvegarder l image')}}" /></td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>

                <div class="text-monospace text-center font-weight-bold p-1">ENTRAÎNEMENT</div>

                <div id="frame" class="frame pb-4">

                    <div class="row mt-3">
                        <div class="col-md-12">

                            <!-- CONSIGNES -->
                            <div class="text-monospace">{{strtoupper(__('consignes'))}}</div>
                            <div class="card card-body">
                                <div class="text-monospace text-muted small consignes">
                                    <?php
                                    $Parsedown = new Parsedown();
                                    echo $Parsedown->text($devoir->consignes_eleve);
                                    ?>
                                </div>
                            </div>
                            <!-- CONSIGNES -->

                            <!-- CODE ELEVE --> 
                            <div class="mt-4 text-monospace">{{strtoupper(__('code ÉlÈve'))}}</div>
                            <textarea name="code_eleve" style="display:none;" id="code_eleve"></textarea>
                            <div id="editor_code_eleve" style="border-radius:5px;">{{ $devoir->code_eleve }}</div>
                            <!-- /CODE ELEVE -->

                            <!-- CODE ENSEIGNANT --> 
                            <div class="mt-4 text-monospace">{{strtoupper(__('code enseignant'))}}</div>
                            <textarea name="code_enseignant" style="display:none;" id="code_enseignant"></textarea>
                            <div id="editor_code_enseignant" style="border-radius:5px;">{{ $devoir->code_enseignant }}</div>
                            <!-- /CODE ENSEIGNANT -->                            

                            <!-- SOLUTION --> 
                            <div class="mt-4 text-monospace">{{strtoupper(__('solution possible'))}}</div>
                            <textarea name="solution" style="display:none;" id="solution"></textarea>
                            <div id="editor_solution" style="border-radius:5px;">{{ $devoir->solution }}</div>
                            <!-- /SOLUTION --> 	

                        </div>
                    </div>

                </div>
            </div>
        </div>
	</div><!-- /container -->

	<script src="{{ asset('js/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>
	<script>
		// Chargement de ace et initialisation des éditeurs.
		var editor_code;
		
		async function init_editors() {
			editor_code_eleve = ace.edit("editor_code_eleve", {
				theme: "ace/theme/puzzle_code",
				mode: "ace/mode/python",
				maxLines: 500,
				minLines: 4,
				fontSize: 14,
				wrap: true,
				useWorker: false,
				autoScrollEditorIntoView: true,
				highlightActiveLine: false,
				highlightSelectedWord: false,
				highlightGutterLine: true,
				showPrintMargin: false,
				displayIndentGuides: true,
				showLineNumbers: true,
				showGutter: true,
				showFoldWidgets: false,
				useSoftTabs: true,
				navigateWithinSoftTabs: false,
                readOnly: true,
				tabSize: 4
			});
			editor_code_eleve.container.style.lineHeight = 1.5;
			editor_code_eleve.getSession().on('change', function () {
				document.getElementById('code_eleve').value = editor_code_eleve.getSession().getValue();
			});
			document.getElementById('code_eleve').value = editor_code_eleve.getSession().getValue();

			editor_code_enseignant = ace.edit("editor_code_enseignant", {
				theme: "ace/theme/puzzle_code",
				mode: "ace/mode/python",
				maxLines: 500,
				minLines: 4,
				fontSize: 14,
				wrap: true,
				useWorker: false,
				autoScrollEditorIntoView: true,
				highlightActiveLine: false,
				highlightSelectedWord: false,
				highlightGutterLine: true,
				showPrintMargin: false,
				displayIndentGuides: true,
				showLineNumbers: true,
				showGutter: true,
				showFoldWidgets: false,
				useSoftTabs: true,
				navigateWithinSoftTabs: false,
                readOnly: true,
				tabSize: 4
			});
			editor_code_enseignant.container.style.lineHeight = 1.5;
			editor_code_enseignant.getSession().on('change', function () {
				document.getElementById('code_enseignant').value = editor_code_enseignant.getSession().getValue();
			});
			document.getElementById('code_enseignant').value = editor_code_enseignant.getSession().getValue();

			var editor_solution;
			editor_solution = ace.edit("editor_solution", {
				theme: "ace/theme/puzzle_fakecode",
				mode: "ace/mode/python",
				maxLines: 500,
				minLines: 4,
				fontSize: 14,
				wrap: true,
				useWorker: false,
				autoScrollEditorIntoView: true,
				highlightActiveLine: false,
				highlightSelectedWord: false,
				highlightGutterLine: true,
				showPrintMargin: false,
				displayIndentGuides: true,
				showLineNumbers: true,
				showGutter: true,
				showFoldWidgets: false,
				useSoftTabs: true,
				navigateWithinSoftTabs: false,
                readOnly: true,                
				tabSize: 4
			});
			editor_solution.container.style.lineHeight = 1.5;
			editor_solution.getSession().on('change', function () {
				document.getElementById('solution').value = editor_solution.getSession().getValue();
			});
			document.getElementById('solution').value = editor_solution.getSession().getValue();
		}
		(async function() {
			// Chargement asynchrone de ace et initialisation des éditeurs
			const editors_initialized_promise = init_editors();
			// Pour être sur que ace est chargé et les éditeurs initialisés.
			await editors_initialized_promise;		
		})();	
	</script>

	@include('inc-bottom-js')

</body>
</html>
