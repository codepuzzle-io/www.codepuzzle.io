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
		if ($sujet->user_id !== 0 && (!Auth::check() || (Auth::check() && Auth::id() !== $sujet->user_id))) {
			echo "<pre>Vous ne pouvez pas accéder à ce sujet.</pre>";
			exit();
		}    
		$sujet_json = json_decode($sujet->sujet);
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
	@include('inc-meta')
	@include('markdown/inc-markdown-css')
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
				<div class="mb-4 text-muted">Exercice Python</div>

				<form method="POST" action="{{route('sujet-exo-creer-post')}}">

					@csrf

					<!-- TITRE -->
					<div class="text-monospace">{{strtoupper(__('titre'))}}<sup class="ml-1 text-danger small">*</sup></div>
					<input id="titre" type="text" class="form-control @error('titre') is-invalid @enderror" name="titre" value="{{ old('titre') ?? $sujet->titre ?? '' }}" autofocus>
					@error('titre')
						<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
					@enderror
					<!-- /TITRE -->		

					<!-- ÉNONCÉ -->
					<div class="mt-4 text-monospace">{{strtoupper(__('ÉNONCÉ'))}}<sup class="ml-1 text-danger small">*</sup></div>
					<textarea id="markdown_content" class="form-control @error('enonce') is-invalid @enderror" name="enonce" rows="6">{{ old('enonce') ?? $sujet_json->enonce ?? '' }}</textarea>
					@error('enonce')
						<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
					@enderror
					<!-- /ÉNONCÉ -->
					
					<div class="row mt-3">
						<div class="col-md-3 pt-2">
							<div class="small text-monospace text-muted text-justify">
								Si le sujet nécessite l'écriture de plusieurs programmes indépendants, vous pouvez ajouter des options en cliquant sur le bouton ci-dessous. 
							</div>
							<div class="mt-2 text-center">
								<button type="button" class="btn btn-dark btn-sm text-monospace pl-3 pr-3" onclick="ajouterDiv(null, 'bas', 'code')"><i class="fas fa-plus"></i></button>
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
        var editor_code_eleve = [];
        var editor_code_enseignant = [];
        var editor_code_solution = [];
        var div_id = 0;

        function ajouterDiv(referenceDivId = null, position = 'bas', type, content = []) {
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
					<div class="text-monospace">{{strtoupper(__("code ÉlÈve"))}} <span class="font-italic small" style="color:silver;">{{__("optionnel")}}</span></div>
					<div class="text-monospace text-muted small text-justify mb-1">{{__("Ce code sera proposé à l'élève comme point de départ.")}}</div>
					<textarea id="code_eleve_`+div_id+`" name="code[`+div_id+`][code_eleve]" style="display:none;"></textarea>
					<div id="code_editor_eleve_`+div_id+`" class="mb-2 code-editor"></div>

					<div class="mt-4 text-monospace">{{strtoupper(__("code enseignant"))}} <span class="font-italic small" style="color:silver;">{{__("optionnel")}}</span></div>
					<div class="text-monospace text-muted small text-justify mb-1">{{__("Non visible par les élèves. Vous pouvez y placer un jeu de tests par exemple. Ce code pourra être exécuté en même temps que celui de l'élève ou seul lors de l'évaluation.")}}</div>
					<textarea id="code_enseignant_`+div_id+`" name="code[`+div_id+`][code_enseignant]" style="display:none;"></textarea>
					<div id="code_editor_enseignant_`+div_id+`" class="mb-2 code-editor"></div>
					
					<div class="mt-4 text-monospace">{{strtoupper(__('solution possible'))}} <span class="font-italic small" style="color:silver;">{{__("optionnel")}}</span></div>
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

            // Faire défiler pour rendre le div créé visible
            document.getElementById('div_'+div_id).scrollIntoView({
                behavior: 'smooth',
                block: 'end'
            });
            
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

</body>
</html>
