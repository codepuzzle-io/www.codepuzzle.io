<?php
$devoir = App\Models\Devoir::where('jeton_secret', $jeton_secret)->first();
if (!$devoir) {
    echo "<pre>Ce devoir n'existe pas</pre>";
    exit();
}
$devoir_eleves = App\Models\Copie::where('jeton_devoir', $devoir->jeton)->orderBy('pseudo')->get();
?>
<!doctype html>
<html lang="fr">
<head>
    @include('inc-meta')
    <meta name="robots" content="noindex">

    <script src="https://cdn.jsdelivr.net/pyodide/v0.24.1/full/pyodide.js"></script>
    <title>DEVOIR | {{$devoir->jeton}} | CONSOLE</title>
</head>
<body class="no-mathjax">

    @include('inc-nav')

	<div class="container">

		<div class="row pt-3">

			<div class="col-md-2">

                <div class="text-right mb-3">
                    @if(Auth::check())
                    <a class="btn btn-light btn-sm" href="/console/devoirs" role="button"><i class="fas fa-arrow-left"></i></a>
                    @else
                    <a class="btn btn-light btn-sm" href="/" role="button"><i class="fas fa-arrow-left"></i></a>
                    @endif
                </div>

            </div>

			<div class="col-md-10 pl-4 pr-4">
                <div class="col-md-12 text-monospace p-2 pl-3 pr-3 mb-3 text-danger" style="border:dashed 2px #e3342f;border-radius:8px;">
                Les "devoirs" ont changé! Ceci est une ancienne version des "devoirs". Si vous n'arrivez à récupérer des informations ou si vous avez des questions, écrivez à contact@codepuzzle.io en indiquant le code du devoir ({{$devoir->jeton_secret}}).
                </div>
                <?php
                /*
                <div id="frame" class="frame">

                    <div class="row">
                        <div class="col-md-12">

                            @if(isset($_GET['i']))
                                <div class="text-monospace text-danger text-center font-weight-bold m-2">SAUVEGARDEZ LES INFORMATIONS CI-DESSOUS AVANT DE QUITTER CETTE PAGE</div>
                            @endif

                            <table class="table table-borderless text-monospace m-0" style="border-spacing:5px;border-collapse:separate;">
                                <tr>
                                    @if($devoir->user_id == 0 OR !Auth::check()) <td class="text-center font-weight-bold p-0" style="width:33%">lien secret</td> @endif
                                    <td class="text-center font-weight-bold p-0" style="width:33%">code secret</td>
                                    <td class="text-center font-weight-bold p-0" style="width:33%">lien élèves</td>
                                </tr>
                                <tr>
                                    @if ($devoir->user_id == 0 OR !Auth::check()) <td class="text-center p-2 text-break align-middle border border-danger rounded"><a href="/devoir-console/{{strtoupper($devoir->jeton_secret)}}" target="_blank" class="text-danger">www.codepuzzle.io/devoir-console/{{strtoupper($devoir->jeton_secret)}}</a></td> @endif
                                    <td class="text-center p-2 text-break align-middle border border-danger rounded text-danger">{{$devoir->mot_secret}}</td>
                                    <td class="text-center text-white p-2 text-break align-middle rounded bg-secondary"><a href="/E{{strtoupper($devoir->jeton)}}" target="_blank" class="text-white">www.codepuzzle.io/E{{strtoupper($devoir->jeton)}}</a></td>
                                </tr>
                                <tr>
                                    @if ($devoir->user_id == 0 OR !Auth::check()) <td class="small text-muted p-0"><span class="text-danger"><i class="fas fa-exclamation-circle"></i> Ne pas partager ce lien</span><br />Il permet d'accéder à la console du devoir (sujet, lien pour les élèves, correction...).</td> @endif
                                    <td class="small text-muted p-0"><span class="text-danger"><i class="fas fa-exclamation-circle"></i> Ne pas partager ce code</span><br />Il permet de déverrouiller la copie d'un élève.</td>
                                    <td class="small text-muted p-0">Lien à fournir aux élèves.<!--<br />QR code: <img src="https://api.qrserver.com/v1/create-qr-code/?data={{urlencode('https://www.codepuzzle.io/E' . strtoupper($devoir->jeton))}}&amp;size=200x200" style="width:50px" alt="www.codepuzzle.io/E{{strtoupper($devoir->jeton)}}" data-toggle="tooltip" data-placement="bottom" title="{{__('clic droit + Enregistrer l image sous... pour sauvegarder l image')}}" />--></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                </div>

                <div class="row mt-3 mb-3">
                    <div class="col-md-4 offset-4 text-center">
                        <a class="btn btn-dark btn-sm" href="/devoir-creer/{{$jeton_secret}}" role="button"><i class="fa-solid fa-pen mr-2"></i> modifier</a>
                        <a class="btn btn-dark btn-sm ml-3" href="/devoir-supervision/{{$jeton_secret}}" role="button"><i class="fa-solid fa-eye mr-2"></i></i> superviser</a>
                    </div>
                </div>
                */
                ?>


                <div id="frame" class="frame">

                    <div class="row">
                        <div class="col-md-12">

                            <div class="text-monospace font-weight-bold"><a data-toggle="collapse" href="#collapseSujet" role="button" aria-expanded="false" aria-controls="collapseSujet"><i class="fas fa-plus-square"></i></a> SUJET</div>
                            
                            <div class="collapse mb-3" id="collapseSujet">

                                <!-- CONSIGNES -->
                                <div class="text-monospace mt-3">{{strtoupper(__('consignes'))}}</div>
                                <div class="markdown_content border rounded bg-light text-monospace p-3">{{ $devoir->consignes_eleve }}</div>
                                <!-- CONSIGNES -->

                                <!-- CODE ELEVE --> 
                                <div class="mt-2 text-monospace">{{strtoupper(__('code ÉlÈve'))}}</div>
                                <textarea name="code_eleve" style="display:none;" id="code_eleve"></textarea>
                                <div id="editor_code_eleve" style="border-radius:5px;">{{ $devoir->code_eleve }}</div>
                                <!-- /CODE ELEVE -->

                                <!-- CODE ENSEIGNANT --> 
                                <div class="mt-2 text-monospace">{{strtoupper(__('code enseignant'))}}</div>
                                <textarea name="code_enseignant" style="display:none;" id="code_enseignant"></textarea>
                                <div id="editor_code_enseignant" style="border-radius:5px;">{{ $devoir->code_enseignant }}</div>
                                <!-- /CODE ENSEIGNANT -->                            

                                <!-- SOLUTION --> 
                                <div class="mt-2 text-monospace">{{strtoupper(__('solution possible'))}}</div>
                                <textarea name="solution" style="display:none;" id="solution"></textarea>
                                <div id="editor_solution" style="border-radius:5px;">{{ $devoir->solution }}</div>
                                <!-- /SOLUTION --> 	

                            </div>

                        </div>
                    </div>

                </div>  
                
                <div class="row mt-3">
                    <div class="col-md-4 offset-4 text-center">
                        <a class="btn btn-dark btn-sm d-block" href="/devoir-imprimer/{{$jeton_secret}}" role="button"><i class="fa-solid fa-print mr-2"></i> imprimer les comptes-rendus</a>
                        <div class="text-muted small mt-1">pour les annoter si nécessaire et les distribuer aux élèves</div>
                    </div>
                </div>

                <div class="row mt-3 mb-5">
                    <div class="col-md-12">
                        @foreach($devoir_eleves as $devoir_eleve)

                            <?php
                            $secondes = floor($devoir_eleve->chrono/1000);
                            $heures = gmdate("H", $secondes);
                            $minutes = gmdate("i", $secondes);
                            $secondes = gmdate("s", $secondes);
                            $chrono = "{$heures}h {$minutes}m {$secondes}s";
                            ?>

                            <div id="frame" class="frame mt-1 mb-1">

                                <div class="text-monospace">

                                    <div style="float:right;right:0px">

                                        <span class="small text-muted" data-toggle="tooltip" data-placement="top" title="temps" style="cursor:help"><i class="fa-solid fa-stopwatch"></i> {{$chrono}}</span>
                                        <span class="small text-muted mr-3" data-toggle="tooltip" data-placement="top" title="nombre d'exécutions du code" style="cursor:help"><i class="fa-solid fa-square-check"></i> {{$devoir_eleve->nbverif}}</span>

                                        @if($devoir_eleve->submitted == 1)
                                            <span class="text-white mr-1" style="font-size:70%;background-color:#94C58C;padding:2px 8px;border-radius:3px;vertical-align:2px;">rendu</span>
                                        @endif
                                        @if($devoir_eleve->revised == 1)
                                            <i class="fas fa-check-circle mr-3" style="color:#94C58C;"></i>
                                        @else
                                            <i class="fas fa-check-circle mr-3" style="color:#ecf0f1;"></i>
                                        @endif
                                        <a tabindex='0' role="button" style="cursor:pointer;outline:none;color:#e2e6ea;font-size:95%" data-toggle="collapse" data-target="#collapseSupprimer-{{$loop->iteration}}" aria-expanded="false" aria-controls="collapseSupprimer-{{$loop->iteration}}"><i class='fas fa-trash fa-sm'></i></a>

                                    </div>

                                    <a data-toggle="collapse" class="text-dark" href="#collapseEntrainement-{{$loop->iteration}}" role="button" aria-expanded="false" aria-controls="collapseEntrainement-{{$loop->iteration}}"><i class="fas fa-plus-square"></i></a>

                                    <span class="">{{$devoir_eleve->pseudo}}</span>
                                </div>

                                <div class="collapse text-right" id="collapseSupprimer-{{$loop->iteration}}">
                                    <div class="mt-3">
                                        <a href='/devoir-eleve-supprimer/{{ Crypt::encryptString($devoir_eleve->id) }}' class='btn btn-danger btn-sm text-white' role='button'>{{__('supprimer')}}</a>                                            
                                        <button type="button" class="btn btn-light btn-sm ml-1" data-toggle="collapse" data-target="#collapseSupprimer-{{$loop->iteration}}">annuler</button>
                                    </div>
                                </div>

                                <div class="collapse" id="collapseEntrainement-{{$loop->iteration}}">

                                    <!-- CODE ELEVE --> 
                                    <div class="text-monospace mt-2">Code élève <i class="text-muted small">en lecture seule</i></div>
                                    <div id="editor_code_eleve_devoir-{{$loop->iteration}}" style="border-radius:5px;">{{$devoir_eleve->code_eleve}}</div>
                                    <!-- /CODE ELEVE --> 

                                    <!-- CODE ENSEIGNANT --> 
                                    <div class="text-monospace mt-2">Code enseignant</div>
                                    <?php
                                    if ($devoir_eleve->code_enseignant == NULL){
                                        $code_enseignant = $devoir->code_enseignant;
                                    } else {
                                        $code_enseignant = $devoir_eleve->code_enseignant;
                                    }
                                    ?>
                                    <div id="editor_code_enseignant_devoir-{{$loop->iteration}}" style="border-radius:5px;">{{$code_enseignant}}</div>
                                    <!-- /CODE ENSEIGNANT --> 

                                    <table class="mt-2 mb-2" style="width:100%">
                                        <tr>
                                            <td style="width:50%">
                                                <div style="line-height:1">
                                                    <div class="form-check d-block m-0 text-right">
                                                        <span class="text-monospace small text-muted">code élève</span>
                                                        <input id="code_option_1_devoir-{{$loop->iteration}}" name="code_option_devoir-{{$loop->iteration}}" class="align-middle" style="display:inline;cursor:pointer" type="radio" />
                                                    </div>
                                                    <div class="form-check d-block m-0 text-right">
                                                        <span class="text-monospace small text-muted">code élève + code enseignant</span>
                                                        <input id="code_option_2_devoir-{{$loop->iteration}}" name="code_option_devoir-{{$loop->iteration}}" class="align-middle" style="display:inline;cursor:pointer" type="radio" checked />
                                                    </div>
                                                    <div class="form-check d-block m-0 text-right">
                                                        <span class="text-monospace small text-muted">code enseignant</span>
                                                        <input id="code_option_3_devoir-{{$loop->iteration}}" name="code_option_devoir-{{$loop->iteration}}" class="align-middle" style="display:inline;cursor:pointer" type="radio" />
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="width:50%;vertical-align:top">

                                                <div class="row pl-2">
                                                    <div class="col-md-6 text-left">
                                                        <button id="run-{{$loop->iteration}}" data-pyodide="run" onclick="run({{$loop->iteration}})" type="button" class="btn btn-primary btn-sm" style="width:60px;"><i class="fas fa-circle-notch fa-spin"></i></button>
                                                        <button id="stop-{{$loop->iteration}}" data-pyodide="stop" onclick="stop({{$loop->iteration}})" type="button" class="btn btn-dark btn-sm pl-3 pr-3" style="padding-top:6px;display:none;" data-bs-toggle="tooltip" data-bs-placement="right"  data-bs-trigger="hover" title="{{__('Interruption de l\'exécution du code (en cas de boucle infinie ou de traitement trop long). L\'arrêt peut prendre quelques secondes.')}}"><i class="fas fa-stop"></i></button>
                                                    </div>
                                                    <div class="col-md-6 text-right">
                                                        <button id="restart-{{$loop->iteration}}" data-pyodide="restart" onclick="restart()" type="button" class="btn btn-warning btn-sm pl-3 pr-3" style="padding-top:6px;display:none;" data-bs-toggle="tooltip" data-bs-placement="right"  data-bs-trigger="hover" title="{{__('Si le bouton d\'arrêt ne permet pas d\'interrompre  l\'exécution du code, cliquer ici. Python redémarrera complètement mais votre code sera conservé dans l\'éditeur. Le redémarrage peut prendre quelques secondes.')}}"><i class="fas fa-skull"></i></button>
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                    </table>

                                    <div>
                                        <div class="text-monospace">Console</div>
                                        <pre id="output-{{$loop->iteration}}" class="bg-dark text-monospace p-3 small text-white" style="border-radius:4px;border:1px solid silver;min-height:80px;"></pre>
                                    </div>

                                    <div class="text-monospace text-success font-weight-bold mt-2">Commentaires</div>
                                    <textarea id="commentaires-{{$loop->iteration}}" class="form-control border border-success text-success" rows="6">{{$devoir_eleve->commentaires}}</textarea>
                                    <a id="save-{{$loop->iteration}}" href="#" onclick="save_commentaires({{$loop->iteration}}, {{$devoir_eleve->id}}, event)" role="button" class="btn btn-sm text-monospace mt-2 pt-2 pb-2 pl-3 pr-3" style="background-color:#4cbf56;color:white;"><i class="fas fa-save"></i></a>
                                    <span id="save-confirmation-{{$loop->iteration}}" style="opacity:0"><i class="pl-2 fas fa-check" style="color:#4cbf56"></i></span>
                                
                                </div>

                            </div>

                        @endforeach
                    </div>
                </div>

            </div>
        </div>
	</div><!-- /container -->

    @include('inc-bottom-js')

    @include('markdown/inc-markdown-afficher-js')

    <script src="{{ asset('js/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>

	<script>
		// Chargement de ace et initialisation des éditeurs.
		async function init_editors() {
			var editor_code_eleve = ace.edit("editor_code_eleve", {
				theme: "ace/theme/puzzle_code",
				mode: "ace/mode/python",
				maxLines: 500,
				minLines: 1,
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

			var editor_code_enseignant = ace.edit("editor_code_enseignant", {
				theme: "ace/theme/puzzle_code",
				mode: "ace/mode/python",
				maxLines: 500,
				minLines: 1,
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

			var editor_solution = ace.edit("editor_solution", {
				theme: "ace/theme/puzzle_fakecode",
				mode: "ace/mode/python",
				maxLines: 500,
				minLines: 1,
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

        }

        (async function() {
			// Chargement asynchrone de ace et initialisation des éditeurs
			const editors_initialized_promise = init_editors();
			// Pour être sur que ace est chargé et les éditeurs initialisés.
			await editors_initialized_promise;		
		})();	

        var editor_code_eleve_devoir = []
        var editor_code_enseignant_devoir = []
        for (var i = 1; i <= {{$devoir_eleves->count() }}; i++) {

            editor_code_eleve_devoir[i] = ace.edit('editor_code_eleve_devoir-' + i, {
                theme: "ace/theme/puzzle_code",
                mode: "ace/mode/python",
                maxLines: 500,
                fontSize: 14,
                wrap: true,
                useWorker: false,
                highlightActiveLine: false,
                highlightGutterLine: false,
                showPrintMargin: false,
                displayIndentGuides: true,
                showLineNumbers: true,
                showGutter: true,
                showFoldWidgets: false,
                useSoftTabs: true,
                navigateWithinSoftTabs: false,
                tabSize: 4,
                readOnly: true
            });
            editor_code_eleve_devoir[i].container.style.lineHeight = 1.5;

            editor_code_enseignant_devoir[i] = ace.edit('editor_code_enseignant_devoir-' + i, {
                theme: "ace/theme/puzzle_code",
                mode: "ace/mode/python",
                maxLines: 500,
                fontSize: 14,
                wrap: true,
                useWorker: false,
                highlightActiveLine: false,
                highlightGutterLine: false,
                showPrintMargin: false,
                displayIndentGuides: true,
                showLineNumbers: true,
                showGutter: true,
                showFoldWidgets: false,
                useSoftTabs: true,
                navigateWithinSoftTabs: false,
                tabSize: 4
            });
            editor_code_enseignant_devoir[i].container.style.lineHeight = 1.5;
        }        

	</script>

    <script>

        // PYODIDE

        // webworker
        let pyodideWorker = createWorker();

        var runButton, stopButton, restartButton, output, interruptBuffer;

        function createWorker() {          

            let pyodideWorker = new Worker("{{ asset('pyodideworker/devoir-pyodideWorker.js') }}");

            pyodideWorker.onmessage = function(event) {
                
                // reponses du WebWorker
                console.log("EVENT: ", event.data);

                if (typeof event.data.init !== 'undefined') {
                    console.log("Prêt!");
                    var runButtons = document.querySelectorAll('button[data-pyodide="run"]');
                    runButtons.forEach(function(runButton) {
                        runButton.innerHTML = '<i class="fas fa-play"></i>';
                        runButton.disabled = false;
                    });
                }

                if (typeof event.data.status !== 'undefined') {

                    if (event.data.status == 'running'){
                        runButton.disabled = true;
                        runButton.innerHTML = '<i class="fas fa-cog fa-spin"></i>';
                        //stopButton.style.display = 'inline';
                    }

                    if (event.data.status == 'completed'){
                        runButton.disabled = false;
                        runButton.innerHTML = '<i class="fas fa-play"></i>';
                        //stopButton.style.display = 'none';
                        restartButton.style.display = 'none';
                    }
                    
                }

                if (typeof event.data.output !== 'undefined') {
                    output.innerHTML += event.data.output;
                }	

            };

            /*
            @if(App::isProduction())
                // ne fonctionne pas en local a cause de COEP et COOP
                // interruption python
                interruptBuffer = new Uint8Array(new SharedArrayBuffer(1));
                pyodideWorker.postMessage({ cmd: "setInterruptBuffer", interruptBuffer });
            @endif
            */
         
            return pyodideWorker

        }

        // envoi des donnees au webworker pour execution
        function run(id){

            runButton = document.getElementById("run-"+id);
            //stopButton = document.getElementById("stop-"+id);
            restartButton = document.getElementById("restart-"+id);
            output = document.getElementById("output-"+id);

            /*
            @if(App::isProduction())
                // ne fonctionne pas en local a cause de COEP et COOP
                interruptBuffer[0] = 0;
            @endif
            */

            let code = "";
            if (document.getElementById("code_option_1_devoir-" + id).checked) {
                code = editor_code_eleve_devoir[id].getValue();
            } else if (document.getElementById("code_option_2_devoir-" + id).checked) {
                code = editor_code_eleve_devoir[id].getValue() + "\n" + editor_code_enseignant_devoir[id].getValue();
            } else if (document.getElementById("code_option_3_devoir-" + id).checked) {
                code = editor_code_enseignant_devoir[id].getValue();
            }

            output.innerHTML = "";
            pyodideWorker.postMessage({ code: code });	

        } 

        // interruption de python
        function stop(id) {
            @if(App::isProduction())
                // ne fonctionne pas en local a cause de COEP et COOP
                // 2 stands for SIGINT.
                interruptBuffer[0] = 2;
            @endif
            // bouton 'restart'
            restartButton.style.display = 'inline';
        }
			   

        // arrete et redemarre le webworker   
        function restart() {
            if (pyodideWorker) {
                pyodideWorker.terminate();
                console.log("Web Worker supprimé.");
            }
            pyodideWorker = createWorker();
            console.log("Web Worker redémarré.");
            runButton.disabled = true;
            //stopButton.style.display = 'none';
            restartButton.style.display = 'none';
        }

	</script>


    <script>
        function save_commentaires(i, id, event) {
            event.preventDefault();
            var formData = new URLSearchParams();
            formData.append('devoir_eleve_id', id);
            formData.append('code_enseignant', encodeURIComponent(editor_code_enseignant_devoir[i].getValue()));
            formData.append('commentaires', encodeURIComponent(document.getElementById('commentaires-'+i).value));
            fetch('/devoir-save-commentaires', {
                method: 'POST',
                headers: {"Content-Type": "application/x-www-form-urlencoded", "X-CSRF-Token": "{{ csrf_token() }}"},
                body: formData
            })
            .then(function(response) {
                // Renvoie la réponse du serveur (peut contenir un message de confirmation)
                //return response.text();

                // confirmation de l'enregistrement
                document.getElementById('save-' + i).blur();
                var element = document.getElementById("save-confirmation-" + i)
                element.style.opacity = 1;
                var intervalID = setInterval(function() {
                    element.style.opacity = element.style.opacity - 0.01;
                }, 30); 
                setTimeout(function() {
                    clearInterval(intervalID);
                }, 3000);
                return response.text();
                
            })
            .then(function(data) {
                // Affiche la réponse du serveur dans la console
                console.log('Réponse du serveur:', data); 
            })
            .catch(function(error) {
                // Gère les erreurs liées à la requête Fetch
                console.error('Erreur:', error); 
            });
        }
    </script>

    <script>
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		  return new bootstrap.Tooltip(tooltipTriggerEl)
		})
	</script>

</body>
</html>
