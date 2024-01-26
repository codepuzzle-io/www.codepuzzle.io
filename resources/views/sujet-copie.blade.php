<?php
if (isset($jeton_secret)) {
	$devoir = App\Models\Devoir::where('jeton_secret', $jeton_secret)->first();
	if (!$devoir) {
		echo "<pre>Ce devoir n'existe pas.</pre>";
		exit();
	} else {
		$titre_enseignant = $devoir->titre_enseignant;
		$sous_titre_enseignant = $devoir->sous_titre_enseignant;
		$titre_eleve = $devoir->titre_eleve;
		$consignes = $devoir->consignes_eleve;
		$code_eleve = $devoir->code_eleve;
		$code_enseignant = $devoir->code_enseignant;
		$solution = $devoir->solution;
		$with_chrono = $devoir->with_chrono;
		$with_console = $devoir->with_console;

		if ($devoir->user_id !== 0 && (!Auth::check() || (Auth::check() && Auth::id() !== $devoir->user_id))) {
			echo "<pre>Vous ne pouvez pas accéder à ce devoir.</pre>";
			exit();
		}
	}
}
?>
<!doctype html>
<html lang="fr">
<head>
	@include('inc-meta')
    <title>SUJET | CRÉER / MODIFIER</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
    <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
    <style>
        .cellule {
            position: relative;
            margin: 5px 0px 5px 0px;
        }
        .cellule_content {
            position: relative;
            padding: 8px;
            border: 1px solid #CED4DA;
            border-radius:4px;
            background-color:white;
            overflow: hidden;
            resize: none;
        }
        .control {
            position:absolute;
            top:3px;
            right:3px;
        }
        .control_bouton {
            display:inline-block;
            width:20px;
            text-align:center;
            cursor:pointer;
            border-radius:2px;
            opacity:0.3;
        }
        .control_bouton:hover {
            background-color:#e2e6ea;
            opacity:1;
        }
    </style>

</head>
<body>

	@if(Auth::check())
		@include('inc-nav-console')
	@else
		@include('inc-nav')
	@endif

	<div class="container mt-4 mb-5">

		<div class="row">

			<div class="col-md-2 text-right">
				@if(Auth::check())
				<a class="btn btn-light btn-sm" href="/console/devoirs" role="button"><i class="fas fa-arrow-left"></i></a>
				@else
				<a class="btn btn-light btn-sm" href="/" role="button"><i class="fas fa-arrow-left"></i></a>
				<div class="mt-3 small text-monospace text-muted">Vous pouvez <a href="/creer-un-compte" target="_blank">créer un compte</a> pour regrouper, gérer et partager vos sujets.</div>
				@endif
			</div>

			<div class="col-md-10 pl-4 pr-4">

				<h1>{{__('nouveau sujet')}}</h1>

                <button type="button" class="btn btn-dark btn-sm text-monospace" onclick="ajouterDiv()"><i class="fa-solid fa-plus mr-2"></i>cellule code</button>
                <button type="button" class="btn btn-dark btn-sm text-monospace" onclick="ajouterDiv()"><i class="fa-solid fa-plus mr-2"></i>texte</button>
                <div id="mainContainer"></div>








			</div>
		</div><!-- /row -->
	</div><!-- /container -->

	@include('inc-bottom-js')

    <script>
let divId = 0;

function textarea_autosize(textarea) {
    // Remettre la hauteur à 'auto' pour réinitialiser la hauteur
    textarea.style.height = 'auto';

    // Ajuster la hauteur du textarea à son contenu
    textarea.style.height = 2 + textarea.scrollHeight + 'px';
}

function ajouterDiv(referenceDivId = null, position = 'bas') {
    divId++;
    const div = document.createElement('div');
    div.className = 'cellule';
    div.id = 'div' + divId;
    div.innerHTML = `<textarea class="form-control cellule_content" oninput="textarea_autosize(this)" row="2"></textarea><div class="control">
        <div class="control_bouton" onclick="deplacer('haut', '${div.id}')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none" data-icon="ui-components:move-up"><path xmlns="http://www.w3.org/2000/svg" class="jp-icon3" d="M1.52899 6.47101C1.23684 6.76316 1.23684 7.23684 1.52899 7.52899V7.52899C1.82095 7.82095 2.29426 7.82116 2.58649 7.52946L6.25 3.8725V12.25C6.25 12.6642 6.58579 13 7 13V13C7.41421 13 7.75 12.6642 7.75 12.25V3.8725L11.4027 7.53178C11.6966 7.82619 12.1736 7.82641 12.4677 7.53226V7.53226C12.7617 7.2383 12.7617 6.7617 12.4677 6.46774L7.70711 1.70711C7.31658 1.31658 6.68342 1.31658 6.29289 1.70711L1.52899 6.47101Z" fill="#000000"></path></svg></div>
        <div class="control_bouton" onclick="deplacer('bas', '${div.id}')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none" data-icon="ui-components:move-down"><path xmlns="http://www.w3.org/2000/svg" class="jp-icon3" d="M12.471 7.52899C12.7632 7.23684 12.7632 6.76316 12.471 6.47101V6.47101C12.179 6.17905 11.7057 6.17884 11.4135 6.47054L7.75 10.1275V1.75C7.75 1.33579 7.41421 1 7 1V1C6.58579 1 6.25 1.33579 6.25 1.75V10.1275L2.59726 6.46822C2.30338 6.17381 1.82641 6.17359 1.53226 6.46774V6.46774C1.2383 6.7617 1.2383 7.2383 1.53226 7.53226L6.29289 12.2929C6.68342 12.6834 7.31658 12.6834 7.70711 12.2929L12.471 7.52899Z" fill="#000000"></path></svg></div>
        <div class="control_bouton" onclick="ajouterDiv('${div.id}', 'haut')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none" data-icon="ui-components:add-above"><g xmlns="http://www.w3.org/2000/svg" clip-path="url(#clip0_137_19492)"><path class="jp-icon3" d="M4.75 4.93066H6.625V6.80566C6.625 7.01191 6.79375 7.18066 7 7.18066C7.20625 7.18066 7.375 7.01191 7.375 6.80566V4.93066H9.25C9.45625 4.93066 9.625 4.76191 9.625 4.55566C9.625 4.34941 9.45625 4.18066 9.25 4.18066H7.375V2.30566C7.375 2.09941 7.20625 1.93066 7 1.93066C6.79375 1.93066 6.625 2.09941 6.625 2.30566V4.18066H4.75C4.54375 4.18066 4.375 4.34941 4.375 4.55566C4.375 4.76191 4.54375 4.93066 4.75 4.93066Z" fill="#000000" stroke="#000000" stroke-width="0.7"></path></g><path xmlns="http://www.w3.org/2000/svg" class="jp-icon3" fill-rule="evenodd" clip-rule="evenodd" d="M11.5 9.5V11.5L2.5 11.5V9.5L11.5 9.5ZM12 8C12.5523 8 13 8.44772 13 9V12C13 12.5523 12.5523 13 12 13L2 13C1.44772 13 1 12.5523 1 12V9C1 8.44772 1.44771 8 2 8L12 8Z" fill="#000000"></path><defs xmlns="http://www.w3.org/2000/svg"><clipPath id="clip0_137_19492"><rect class="jp-icon3" width="6" height="6" fill="white" transform="matrix(-1 0 0 1 10 1.55566)"></rect></clipPath></defs></svg></div>
        <div class="control_bouton" onclick="ajouterDiv('${div.id}', 'bas')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none" data-icon="ui-components:add-below"><g xmlns="http://www.w3.org/2000/svg" clip-path="url(#clip0_137_19498)"><path class="jp-icon3" d="M9.25 10.0693L7.375 10.0693L7.375 8.19434C7.375 7.98809 7.20625 7.81934 7 7.81934C6.79375 7.81934 6.625 7.98809 6.625 8.19434L6.625 10.0693L4.75 10.0693C4.54375 10.0693 4.375 10.2381 4.375 10.4443C4.375 10.6506 4.54375 10.8193 4.75 10.8193L6.625 10.8193L6.625 12.6943C6.625 12.9006 6.79375 13.0693 7 13.0693C7.20625 13.0693 7.375 12.9006 7.375 12.6943L7.375 10.8193L9.25 10.8193C9.45625 10.8193 9.625 10.6506 9.625 10.4443C9.625 10.2381 9.45625 10.0693 9.25 10.0693Z" fill="#000000" stroke="#000000" stroke-width="0.7"></path></g><path xmlns="http://www.w3.org/2000/svg" class="jp-icon3" fill-rule="evenodd" clip-rule="evenodd" d="M2.5 5.5L2.5 3.5L11.5 3.5L11.5 5.5L2.5 5.5ZM2 7C1.44772 7 1 6.55228 1 6L1 3C1 2.44772 1.44772 2 2 2L12 2C12.5523 2 13 2.44772 13 3L13 6C13 6.55229 12.5523 7 12 7L2 7Z" fill="#000000"></path><defs xmlns="http://www.w3.org/2000/svg"><clipPath id="clip0_137_19498"><rect class="jp-icon3" width="6" height="6" fill="white" transform="matrix(1 1.74846e-07 1.74846e-07 -1 4 13.4443)"></rect></clipPath></defs></svg></div>
        <div class="control_bouton" onclick="supprimerDiv('${div.id}')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16px" height="16px" data-icon="ui-components:delete"><path xmlns="http://www.w3.org/2000/svg" d="M0 0h24v24H0z" fill="none"></path><path xmlns="http://www.w3.org/2000/svg" class="jp-icon3" fill="#000000" d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"></path></svg></div>
    </div>`;

    const mainContainer = document.getElementById('mainContainer');
    const referenceDiv = referenceDivId ? document.getElementById(referenceDivId) : null;
    
    if (referenceDiv) {
        if (position === 'haut') {
            mainContainer.insertBefore(div, referenceDiv);
        } else {
            mainContainer.insertBefore(div, referenceDiv.nextSibling);
        }
    } else {
        mainContainer.appendChild(div);
    }
}

function deplacer(direction, divId) {
    const div = document.getElementById(divId);
    if (direction === 'haut' && div.previousElementSibling) {
        div.parentNode.insertBefore(div, div.previousElementSibling);
    } else if (direction === 'bas' && div.nextElementSibling) {
        div.parentNode.insertBefore(div.nextElementSibling, div);
    }
}

function supprimerDiv(divId) {
    const div = document.getElementById(divId);
    div.parentNode.removeChild(div);
}

</script>

</body>
</html>

