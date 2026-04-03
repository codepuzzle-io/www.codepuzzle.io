<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (isset($sujet_id)) {
    $sujet = App\Models\Sujet::find(Crypt::decryptString($sujet_id));
	if (!isset($sujet)) {
		echo "<pre>Ce sujet n'existe pas.</pre>";
		exit();
	} else {
		/*
		if ($sujet->user_id !== 0 && (!Auth::check() || (Auth::check() && Auth::id() !== $sujet->user_id))) {
			echo "<pre>Vous ne pouvez pas accéder à ce sujet.</pre>";
			exit();
		}    
		*/
		$sujet_json = json_decode($sujet->sujet);
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
	@include('inc-meta')
	@include('markdown/inc-markdown-css')
    <link href="{{ asset('css/dropzone-basic.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dropzone.css') }}" rel="stylesheet">
    <title>SUJET PYTHON | CRÉER / MODIFIER</title>
</head>
<body>

	@if(Auth::check())
		@include('inc-nav-console')
	@else
		@include('inc-nav')
	@endif

	<div class="container mt-4 mb-5">

		<div class="row">

			<div class="col-md-2 text-right mt-1">
				@if(Auth::check())
					<a class="btn btn-light btn-sm" href="/console/sujets" role="button"><i class="fas fa-arrow-left"></i></a>
				@else
					@if (isset($jeton_secret))
						<a class="btn btn-light btn-sm" href="/sujet-console/{{ $jeton_secret }}" role="button"><i class="fas fa-arrow-left"></i></a>
					@else
						<a class="btn btn-light btn-sm" href="/sujet-creer" role="button"><i class="fas fa-arrow-left"></i></a>
					@endif
					<div class="mt-3 small text-monospace text-muted">Vous pouvez <a href="/creer-un-compte" target="_blank">créer un compte</a> pour regrouper, gérer et partager vos sujets.</div>
				@endif
			</div>

			<div class="col-md-10 pl-4 pr-4">

				<h1 class="mb-0">{{__('sujet')}}</h1>
				<div class="mb-4 text-muted">Exercice(s) Python / Épreuve Pratique</div>

				<form id="sujet_form" method="POST" action="{{route('sujet-exo-creer-post')}}" enctype="multipart/form-data">

					@csrf

					<!-- TITRE -->
					<div class="text-monospace">{{mb_strtoupper(__('titre'))}}<sup class="ml-1 text-danger small">*</sup></div>
					<input id="titre" type="text" class="form-control @error('titre') is-invalid @enderror" name="titre" value="{{ old('titre') ?? $sujet->titre ?? '' }}" autofocus>
					@error('titre')
						<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
					@enderror
					<!-- /TITRE -->		

					<!-- ÉNONCÉ -->
					{{-- L'énoncé peut être saisi en Markdown (textarea) ou fourni sous forme de PDF uploadé.
					     Le choix est stocké dans le champ caché "enonce_type" ('markdown' | 'pdf').
					     La valeur initiale est restaurée depuis old() après une erreur de validation,
					     ou depuis $sujet_json si on est en mode modification/duplication. --}}
					<div class="mt-4 text-monospace">{{mb_strtoupper(__('ÉNONCÉ'))}}<sup class="ml-1 text-danger small">*</sup></div>

					@php
						// Priorité : old() (retour erreur validation) > valeur existante > défaut 'markdown'
						$currentEnonceType = old('enonce_type') ?? ($sujet_json->enonce_type ?? 'markdown');
					@endphp

					{{-- Boutons de bascule Markdown / PDF --}}
					<div class="mb-2">
						<div class="btn-group btn-group-sm" role="group">
							<button type="button" id="btn_enonce_markdown"
								class="btn {{ $currentEnonceType === 'markdown' ? 'btn-dark' : 'btn-outline-dark' }} text-monospace"
								onclick="switchEnonce('markdown')">Markdown</button>
							<button type="button" id="btn_enonce_pdf"
								class="btn {{ $currentEnonceType === 'pdf' ? 'btn-dark' : 'btn-outline-dark' }} text-monospace"
								onclick="switchEnonce('pdf')">PDF</button>
						</div>
					</div>

					{{-- Champ caché transmis au contrôleur pour connaître le mode actif --}}
					<input type="hidden" id="enonce_type" name="enonce_type" value="{{ $currentEnonceType }}">

					{{-- Section Markdown : visible uniquement si enonce_type = 'markdown' --}}
					<div id="section_enonce_markdown" @if($currentEnonceType === 'pdf') style="display:none" @endif>
						<textarea id="markdown_content" class="form-control @error('enonce') is-invalid @enderror" name="enonce" rows="6">{{ old('enonce') ?? $sujet_json->enonce ?? '' }}</textarea>
						@error('enonce')
							<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
						@enderror
					</div>

					{{-- Section PDF : visible uniquement si enonce_type = 'pdf' --}}
					<div id="section_enonce_pdf" @if($currentEnonceType === 'markdown') style="display:none" @endif>
						{{-- En mode modification, affiche un lien vers le PDF actuel.
						     Si l'utilisateur ne dépose rien, le PDF existant est conservé. --}}
						@if(isset($sujet_json->enonce_pdf) && $sujet_json->enonce_pdf && $currentEnonceType === 'pdf')
							<div class="mb-2 small text-monospace text-muted">
								PDF actuel : <a href="{{ Storage::url($sujet_json->enonce_pdf) }}" target="_blank">voir le PDF</a>
								<span class="ml-2 font-italic">(laisser vide pour conserver)</span>
							</div>
						@endif
						{{-- Zone de dépôt Dropzone pour le PDF d'énoncé.
						     Les fichiers sont mis en attente côté client (autoProcessQueue: false)
						     puis injectés dans l'input caché #enonce_pdf_input via DataTransfer
						     juste avant la soumission du formulaire. --}}
						<div id="dropzone_enonce_pdf" class="dropzone text-monospace"></div>
						<div class="mt-1 text-danger text-monospace" style="font-size:70%">
							@error('enonce_pdf')<strong>{{ $message }}</strong>@else&nbsp;@enderror
						</div>
						{{-- Input caché alimenté par JS (DataTransfer) avant soumission --}}
						<input type="file" id="enonce_pdf_input" name="enonce_pdf" accept=".pdf" style="display:none">
					</div>
					<!-- /ÉNONCÉ -->

					<!-- SCOPE -->
					<div class="mt-4">
						<div class="text-monospace">{{ mb_strtoupper(__("Portée d'exécution du code")) }}<sup class="ml-1 text-danger small">*</sup></div>

						@php
							$currentScope = old('run_scope') ?? ($sujet_json->run_scope ?? 'cell');
						@endphp

						<div class="form-check">
							<input
							class="form-check-input @error('run_scope') is-invalid @enderror"
							type="radio"
							name="run_scope"
							id="scope_cell"
							value="cell"
							{{ $currentScope === 'cell' ? 'checked' : '' }}>
							<label class="form-check-label text-monospace small" for="scope_cell">
							Exécution par cellule <span class="text-muted">(chaque cellule s'exécute indépendamment des autres)</span>
							</label>
						</div>

						<div class="form-check">
							<input
							class="form-check-input @error('run_scope') is-invalid @enderror"
							type="radio"
							name="run_scope"
							id="scope_session"
							value="session"
							{{ $currentScope === 'session' ? 'checked' : '' }}>
							<label class="form-check-label text-monospace small" for="scope_session">
							Exécution partagée <span class="text-muted">(toutes les cellules partagent le même environnement)</span>
							</label>
						</div>

						@error('run_scope')
							<div class="invalid-feedback d-block"><strong>{{ $message }}</strong></div>
						@enderror
					</div>
					<!-- /SCOPE -->

					<!-- BIBLIOTHEQUES -->
					<div class="mt-4 text-monospace">{{mb_strtoupper(__('bibliotheques'))}} <span class="font-italic small" style="color:silver;">optionnel</span></div>
					<div class="mb-1 small text-monospace text-muted text-justify">Séparer les noms des bibliothèques par des virgules. Chaque bibliothèque sera chargée au démarrage.</div>
					<input id="bibliotheques" class="form-control @error('bibliotheques') is-invalid @enderror" name="bibliotheques" value="{{ old('bibliotheques') ?? $sujet_json->bibliotheques ?? '' }}" />
					@error('bibliotheques')
						<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
					@enderror
					<!-- /BIBLIOTHEQUES -->	

					<!-- FICHIERS -->
					{{-- Deux moyens d'ajouter des fichiers accessibles par le code Python de l'élève :
					     1. Upload direct (fichiers_upload[]) : le fichier est stocké sur le serveur
					        dans SUJETS/FICHIERS/{uuid}/{nom_original}. L'URL publique générée est
					        automatiquement ajoutée au champ "fichiers" dans le JSON du sujet.
					        Le nom original est préservé car Pyodide le déduit depuis l'URL.
					     2. URL distante (textarea "fichiers") : l'URL est validée (schéma, host,
					        taille max) puis stockée telle quelle.
					     Les deux sources sont fusionnées en une seule liste dans le JSON. --}}
					<div class="mt-4 text-monospace">{{mb_strtoupper(__('fichiers'))}} <span class="font-italic small" style="color:silver;">optionnel</span></div>

					{{-- Téléversement direct : zone Dropzone, chaque fichier max 2 Mo, max 10 fichiers.
					     Extensions acceptées : .py .txt .csv .json .xml .tsv
					     Les fichiers sont mis en attente côté client (autoProcessQueue: false)
					     puis injectés dans l'input caché #fichiers_upload_input via DataTransfer
					     juste avant la soumission du formulaire. --}}
					<div class="mb-1 small text-monospace text-muted">Téléverser des fichiers depuis votre ordinateur <span class="font-italic">(.py, .txt, .csv, .json, .xml, .tsv — max 2 Mo par fichier, 10 fichiers maximum)</span> :</div>
					<div id="dropzone_fichiers" class="dropzone text-monospace"></div>
					<div class="mt-1 text-danger text-monospace" style="font-size:70%">
						@error('fichiers_upload.*')<strong>{{ $message }}</strong>@else&nbsp;@enderror
					</div>
					{{-- Input caché alimenté par JS (DataTransfer) avant soumission --}}
					<input type="file" id="fichiers_upload_input" name="fichiers_upload[]" multiple style="display:none">

					{{-- URLs distantes : une par ligne, validées côté serveur (anti-SSRF, taille, schéma) --}}
					<div class="mb-1 small text-monospace text-muted text-justify">Ou saisir des URLs de fichiers hébergés sur internet, une par ligne.<br />Chaque fichier sera téléchargé au démarrage. Le nom du fichier est déduit du dernier segment de l'URL (ex. <code>…/donnees.py</code> → <code>donnees.py</code>).</div>
					<textarea id="fichiers" class="form-control @error('fichiers') is-invalid @enderror" name="fichiers" rows="2" style="overflow:hidden;resize:none;" oninput="this.style.height='auto';this.style.height=this.scrollHeight+2+'px'">{{ old('fichiers') ?? $sujet_json->fichiers ?? '' }}</textarea>
					@error('fichiers')
						<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
					@enderror
					<!-- /FICHIERS -->	
					
					<div class="row mt-4">
						<div class="col-md-3 pt-2">
							<div class="small text-monospace text-muted text-justify">
								Si le sujet nécessite l'écriture de plusieurs programmes indépendants, vous pouvez ajouter des options en cliquant sur le bouton ci-dessous. 
							</div>
							<div class="mt-2 text-center">
								<button type="button" class="btn btn-dark btn-sm text-monospace pl-3 pr-3" onclick="ajouterDiv(null, 'bas', 'code', [], true)"><i class="fas fa-plus"></i></button>
							</div>
						</div>
						<div class="col-md-9">

							<div id="editeurs_container"></div>

							@if(isset($sujet))
								<input id="sujet_id" type="hidden" name="sujet_id" value="{{Crypt::encryptString($sujet->id)}}" />
							@endif

							@if(isset($dupliquer))
								<input id="dupliquer" type="hidden" name="dupliquer" value="true" />
							@endif

							<button type="submit" class="btn btn-primary mb-5 pl-4 pr-4"><i class="fas fa-check"></i></button>
						</div>
					</div>

				</form>

			</div>			

		</div><!-- /row -->
	</div><!-- /container -->

	@include('inc-bottom-js')
	@include('markdown/inc-markdown-editeur-js')

	{{-- == Gestion des cellules ========================================= --}}

    <script src="{{ asset('js/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>

	<script>
	document.addEventListener('DOMContentLoaded', () => {
		const ta = document.getElementById('fichiers');
		ta.dispatchEvent(new Event('input', {bubbles:true}));
	});
	</script>

    <script>
        var editor_code_eleve = [];
        var editor_code_enseignant = [];
        var editor_code_solution = [];
        var div_id = 0;

        function ajouterDiv(referenceDivId = null, position = 'bas', type, content = [], scroll = false) {
            div_id++;
            const div = document.createElement('div');
            div.className = 'cellule';
            div.id = 'div_'+div_id;

			content[0] = content[0] || '';
    		content[1] = content[1] || '';
    		content[2] = content[2] || '';	
			
            if (type == 'code') {
				var div_content = ``;
				if (div_id == 1) {
				 	div_content += `<div class="font-weight-bold text-monospace">PROGRAMME</div>`;
				} else {
					div_content += `<div class="font-weight-bold text-monospace">PROGRAMME ${div_id}</div>`;
				}
                div_content += `
				<div class="p-3 mb-4" style="border:solid #ced4da 1px;border-radius:4px;background-color:white;">`;

					if (div_id != 1) {
						div_content += `
						<div style="float:right;">
							<span id="supprimer_button_${div.id}">
								<div style="display:inline-block;width:20px;">&nbsp;</div>
								<div onclick="showConfirm('supprimer_button_${div.id}', 'supprimer_confirm_${div.id}')" class="control_bouton" type="button">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20px" height="20px"><path d="M0 0h24v24H0z" fill="none"></path><path fill="#000000" d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"></path></svg>
								</div>
							</span>
							<span id="supprimer_confirm_${div.id}" style="display:none">
								<div id="supprimer_${div.id}" class="control_bouton_delete" onclick="supprimerDiv('${div.id}')" type="button">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20px" height="20px"><path d="M0 0h24v24H0z" fill="none"></path><path fill="currentColor" d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"></path></svg>
								</div>
								<div id="supprimer_cancel_${div.id}" onclick="hideConfirm('supprimer_button_${div.id}', 'supprimer_confirm_${div.id}')" class="control_bouton_cancel" type="button">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="12px" height="12px"><path d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>
								</div>
							</span>
						</div>`;
					}

					div_content += `
					<div class="text-monospace">{{mb_strtoupper(__("code ÉlÈve"))}} <span class="font-italic small" style="color:silver;">{{__("optionnel")}}</span></div>
					<div class="text-monospace text-muted small text-justify mb-1">{{__("Ce code sera proposé à l'élève comme point de départ.")}}</div>
					<textarea id="code_eleve_`+div_id+`" name="code[`+div_id+`][code_eleve]" style="display:none;"></textarea>
					<div id="code_editor_eleve_`+div_id+`" class="mb-2 code-editor"></div>

					<div class="mt-4 text-monospace">{{mb_strtoupper(__("code enseignant"))}} <span class="font-italic small" style="color:silver;">{{__("optionnel")}}</span></div>
					<div class="text-monospace text-muted small text-justify mb-1">{{__("Non visible par les élèves. Vous pouvez y placer un jeu de tests par exemple. Ce code pourra être exécuté en même temps que celui de l'élève ou seul lors de l'évaluation.")}}</div>
					<textarea id="code_enseignant_`+div_id+`" name="code[`+div_id+`][code_enseignant]" style="display:none;"></textarea>
					<div id="code_editor_enseignant_`+div_id+`" class="mb-2 code-editor"></div>
					
					<div class="mt-4 text-monospace">{{mb_strtoupper(__('solution possible'))}} <span class="font-italic small" style="color:silver;">{{__("optionnel")}}</span></div>
					<div class="text-monospace text-muted small text-justify mb-1">{{__("Non visible par les élèves. Cette soluton possible sert seulement de référence.")}}</div>
					<textarea id="code_solution_`+div_id+`" name="code[`+div_id+`][code_solution]" style="display:none;"></textarea>
					<div id="code_editor_solution_`+div_id+`" class="mb-2 code-editor"></div>
				</div>
				`;
			}
			
            div.innerHTML = div_content;
                
            const editeurs_container = document.getElementById('editeurs_container');
            const referenceDiv = referenceDivId ? document.getElementById(referenceDivId) : null;
            
            if (referenceDiv) {
                if (position === 'haut') {
                    editeurs_container.insertBefore(div, referenceDiv);
                } else {
                    editeurs_container.insertBefore(div, referenceDiv.nextSibling);
                }
            } else {
                editeurs_container.appendChild(div);
            }
            
            if (type == 'code') {
				(function(divIdLocal) {
					editor_code_eleve[divIdLocal] = ace.edit('code_editor_eleve_' + divIdLocal, {
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
						tabSize: 4
					});
					editor_code_eleve[divIdLocal].container.style.lineHeight = 1.5;
					editor_code_eleve[divIdLocal].setValue(content[0], -1);
					editor_code_eleve[divIdLocal].getSession().on('change', function () {
						document.getElementById('code_eleve_'+divIdLocal).value = editor_code_eleve[divIdLocal].getValue();
					});
					document.getElementById('code_eleve_'+divIdLocal).value = editor_code_eleve[divIdLocal].getValue();
				})(div_id);

				(function(divIdLocal) {
					editor_code_enseignant[divIdLocal] = ace.edit('code_editor_enseignant_' + divIdLocal, {
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
						tabSize: 4
					});

					editor_code_enseignant[divIdLocal].container.style.lineHeight = 1.5;
					editor_code_enseignant[divIdLocal].setValue(content[1], -1);
					editor_code_enseignant[divIdLocal].getSession().on('change', function () {
						document.getElementById('code_enseignant_' + divIdLocal).value = editor_code_enseignant[divIdLocal].getValue();
					});
					document.getElementById('code_enseignant_' + divIdLocal).value = editor_code_enseignant[divIdLocal].getValue();
				})(div_id);
				
				(function(divIdLocal) {
					editor_code_solution[divIdLocal] = ace.edit('code_editor_solution_' + divIdLocal, {
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
						tabSize: 4
					});
					editor_code_solution[divIdLocal].container.style.lineHeight = 1.5;
					editor_code_solution[divIdLocal].setValue(content[2], -1);		
					editor_code_solution[divIdLocal].getSession().on('change', function () {
						document.getElementById('code_solution_'+divIdLocal).value = editor_code_solution[divIdLocal].getValue();
					});
					document.getElementById('code_solution_'+divIdLocal).value = editor_code_solution[divIdLocal].getValue();	
				})(div_id);
            }

            // Faire défiler pour rendre le div créé visible (uniquement si demandé explicitement)
            if (scroll) {
                document.getElementById('div_'+div_id).scrollIntoView({
                    behavior: 'smooth',
                    block: 'end'
                });
            }
            
        }

        // Supprimer une cellule
        function supprimerDiv(id) {
            const div = document.getElementById(id);
            div.parentNode.removeChild(div);
			editor_code_eleve[id];
			editor_code_enseignant[id];
			editor_code_solution[id];
			div_id--;
        }
		
    </script>
    {{-- == /Gestion des cellules ======================================== --}}


	<script>
		@if (session()->has('_old_input') or isset($sujet_json))

			@if(session()->has('_old_input'))
				let old = {!! json_encode(old()) !!};
			@else
				@if(isset($sujet_json))
					let old = {!! json_encode($sujet_json) !!};
				@endif
			@endif
			for (let key in old.code) {
				ajouterDiv(null, position = 'bas', 'code', [old.code[key].code_eleve, old.code[key].code_enseignant, old.code[key].code_solution]);
			}
		@else
			ajouterDiv(null, position = 'bas', 'code');
		@endif
	</script>


	{{-- == Mécanisme confirmation suppression cellule ======================= --}}
	<script>
		function showConfirm(buttonId, confirmId) {
			// Cacher le bouton delete_button et afficher delete_confirm
			document.getElementById(buttonId).style.display = 'none';
			document.getElementById(confirmId).style.display = 'inline';
		}

		function hideConfirm(buttonId, confirmId) {
			// Cacher delete_confirm et réafficher delete_button
			document.getElementById(confirmId).style.display = 'none';
			document.getElementById(buttonId).style.display = 'inline';
		}
	</script>
	{{-- == /Mécanisme bouton confirmation =================================== --}}

	{{-- == Dropzone : upload PDF énoncé + fichiers ========================= --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/min/dropzone.min.js"></script>
	<script>
		// Désactiver la découverte automatique de Dropzone (on initialise manuellement)
		Dropzone.autoDiscover = false;

		var dzEnonce   = null; // instance Dropzone pour le PDF d'énoncé
		var dzFichiers = null; // instance Dropzone pour les fichiers Python/données

		// Template de prévisualisation commun aux deux zones (même rendu que sujet-pdf-creer)
		const dzPreviewTemplate = `
			<div class="dz-preview dz-file-preview">
				<div class="dz-details">
					<div class="dz-filename" data-dz-name></div>
					<div class="dz-size" data-dz-size></div>
				</div>
				<div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
				<div class="dz-error-message" data-dz-errormessage></div>
				<div class="dz-remove" data-dz-remove></div>
			</div>
		`;

		document.addEventListener('DOMContentLoaded', function() {

			// ---------------------------------------------------------------
			// Dropzone 1 : PDF d'énoncé
			// - Un seul fichier, format .pdf uniquement, max 2 Mo
			// - La zone n'est visible que lorsque enonce_type = 'pdf'
			// ---------------------------------------------------------------
			dzEnonce = new Dropzone('#dropzone_enonce_pdf', {
				url:                  '#',    // non utilisé (autoProcessQueue: false)
				autoProcessQueue:     false,  // on ne déclenche pas l'upload XHR
				maxFilesize:          2,      // Mo
				maxFiles:             1,
				acceptedFiles:        '.pdf',
				createImageThumbnails: false,
				addRemoveLinks:       true,
				previewTemplate:      dzPreviewTemplate,
				dictFileTooBig:       'Erreur : 2 Mo maximum',
				dictInvalidFileType:  'Erreur : format PDF uniquement',
				dictRemoveFile:       'supprimer',
				dictMaxFilesExceeded: 'un seul fichier autorisé',
				dictDefaultMessage:   "déposer le PDF ici ou <span class='btn btn-outline-secondary btn-sm'>parcourir</span>",
			});

			dzEnonce.on('addedfile', function() {
				// Mettre la bordure en vert et limiter à 1 fichier
				document.getElementById('dropzone_enonce_pdf').style.borderColor = '#79C824';
				if (this.files.length > 1) this.removeFile(this.files[0]);
			});
			dzEnonce.on('removedfile', function() {
				// Remettre la bordure en bleu si la zone est vide
				if (this.files.length === 0) {
					document.getElementById('dropzone_enonce_pdf').style.borderColor = '#2980b9';
				}
			});

			// ---------------------------------------------------------------
			// Dropzone 2 : fichiers Python/données
			// - Plusieurs fichiers, max 2 Mo chacun, 10 fichiers max
			// - Extensions autorisées : .py .txt .csv .json .xml .tsv
			// ---------------------------------------------------------------
			dzFichiers = new Dropzone('#dropzone_fichiers', {
				url:                  '#',
				autoProcessQueue:     false,
				maxFilesize:          2,      // Mo
				maxFiles:             10,
				acceptedFiles:        '.py,.txt,.csv,.json,.xml,.tsv',
				createImageThumbnails: false,
				addRemoveLinks:       true,
				previewTemplate:      dzPreviewTemplate,
				dictFileTooBig:       'Erreur : 2 Mo maximum',
				dictInvalidFileType:  'Erreur : type non autorisé (.py, .txt, .csv, .json, .xml, .tsv)',
				dictRemoveFile:       'supprimer',
				dictMaxFilesExceeded: '10 fichiers maximum',
				dictDefaultMessage:   "déposer des fichiers ici ou <span class='btn btn-outline-secondary btn-sm'>parcourir</span>",
			});

			dzFichiers.on('addedfile', function() {
				document.getElementById('dropzone_fichiers').style.borderColor = '#79C824';
			});
			dzFichiers.on('removedfile', function() {
				if (this.files.length === 0) {
					document.getElementById('dropzone_fichiers').style.borderColor = '#2980b9';
				}
			});

			// ---------------------------------------------------------------
			// Injection des fichiers Dropzone dans les inputs cachés
			// avant la soumission native du formulaire.
			//
			// Principe : Dropzone met les fichiers en mémoire (staging).
			// Au moment du submit, on crée un DataTransfer, on y copie les
			// fichiers acceptés, et on l'affecte au .files de l'input caché.
			// Le formulaire est ensuite soumis normalement (multipart/form-data).
			// ---------------------------------------------------------------
			// Indique si un PDF d'énoncé est déjà stocké (mode modification) — si oui, pas obligatoire d'en uploader un nouveau
			const enonceHasPdfExistant = {{ (isset($sujet_json->enonce_type) && $sujet_json->enonce_type === 'pdf' && isset($sujet_json->enonce_pdf) && $sujet_json->enonce_pdf) ? 'true' : 'false' }};

			document.getElementById('sujet_form').addEventListener('submit', function(e) {

				let valid = true;
				let firstInvalid = null;

				// --- Titre ---
				const titreInput = document.getElementById('titre');
				const titreVal   = titreInput.value.trim();
				const existingTitreError = document.getElementById('titre_error_js');
				if (existingTitreError) existingTitreError.remove();

				if (titreVal.length === 0) {
					titreInput.classList.add('is-invalid');
					titreInput.insertAdjacentHTML('afterend', '<span id="titre_error_js" class="invalid-feedback" role="alert"><strong>champ obligatoire</strong></span>');
					valid = false;
					firstInvalid = firstInvalid ?? titreInput;
				} else if (titreVal.length < 6) {
					titreInput.classList.add('is-invalid');
					titreInput.insertAdjacentHTML('afterend', '<span id="titre_error_js" class="invalid-feedback" role="alert"><strong>6 caractères minimum</strong></span>');
					valid = false;
					firstInvalid = firstInvalid ?? titreInput;
				} else {
					titreInput.classList.remove('is-invalid');
				}

				// --- Énoncé ---
				const enonceType = document.getElementById('enonce_type').value;

				if (enonceType === 'markdown') {
					// Markdown : le textarea ne doit pas être vide
					const enonceInput = document.getElementById('markdown_content');
					const existingEnonceError = document.getElementById('enonce_error_js');
					if (existingEnonceError) existingEnonceError.remove();

					if (enonceInput.value.trim().length === 0) {
						enonceInput.classList.add('is-invalid');
						enonceInput.insertAdjacentHTML('afterend', '<span id="enonce_error_js" class="invalid-feedback" role="alert"><strong>champ obligatoire</strong></span>');
						valid = false;
						firstInvalid = firstInvalid ?? enonceInput;
					} else {
						enonceInput.classList.remove('is-invalid');
					}
				} else {
					// PDF : obligatoire seulement si pas de PDF existant
					const existingPdfError = document.getElementById('enonce_pdf_error_js');
					if (existingPdfError) existingPdfError.remove();

					if (dzEnonce.getAcceptedFiles().length === 0 && !enonceHasPdfExistant) {
						const dzEl = document.getElementById('dropzone_enonce_pdf');
						dzEl.insertAdjacentHTML('afterend', '<span id="enonce_pdf_error_js" class="text-danger text-monospace" style="font-size:70%"><strong>champ obligatoire</strong></span>');
						valid = false;
						firstInvalid = firstInvalid ?? dzEl;
					}
				}

				if (!valid) {
					e.preventDefault();
					firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
					return;
				}

				// PDF d'énoncé : injecter le fichier accepté dans l'input caché
				const enonceAccepted = dzEnonce.getAcceptedFiles();
				if (enonceAccepted.length > 0) {
					const dt = new DataTransfer();
					dt.items.add(enonceAccepted[0]);
					document.getElementById('enonce_pdf_input').files = dt.files;
				}

				// Fichiers : injecter tous les fichiers acceptés dans l'input caché multiple
				const fichiersAccepted = dzFichiers.getAcceptedFiles();
				if (fichiersAccepted.length > 0) {
					const dt = new DataTransfer();
					for (const file of fichiersAccepted) dt.items.add(file);
					document.getElementById('fichiers_upload_input').files = dt.files;
				}
			});

		});
	</script>
	{{-- == /Dropzone ======================================================= --}}

	{{-- == Toggle Markdown / PDF pour l'énoncé ============================ --}}
	<script>
		/**
		 * Bascule l'interface entre les deux modes de saisie de l'énoncé.
		 *
		 * @param {string} type  'markdown' ou 'pdf'
		 *
		 * Actions :
		 *  - Met à jour le champ caché #enonce_type (transmis au contrôleur).
		 *  - Affiche/masque la section correspondante (textarea Markdown ou input PDF).
		 *  - Met à jour le style des boutons (actif = btn-dark, inactif = btn-outline-dark).
		 */
		function switchEnonce(type) {
			document.getElementById('enonce_type').value = type;
			const isMd = type === 'markdown';
			document.getElementById('section_enonce_markdown').style.display = isMd ? '' : 'none';
			document.getElementById('section_enonce_pdf').style.display     = isMd ? 'none' : '';
			document.getElementById('btn_enonce_markdown').className = 'btn text-monospace ' + (isMd ? 'btn-dark' : 'btn-outline-dark');
			document.getElementById('btn_enonce_pdf').className      = 'btn text-monospace ' + (isMd ? 'btn-outline-dark' : 'btn-dark');
		}
	</script>
	{{-- == /Toggle Markdown / PDF ========================================= --}}

</body>
</html>
