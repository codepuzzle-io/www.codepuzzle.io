@include('inc-top')
<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
	@php
		$description = __('Puzzles de Parsons, défis & entraînements/devoirs pour apprendre Python');
		$description_og = __('Puzzles de Parsons, défis & entraînements/devoirs pour apprendre Python');
	@endphp
	@include('inc-meta')
    <title>{{ strtoupper(config('app.name')) }} | Enseigner et apprendre Python</title>
</head>
<body>
	@php
		$lang_switch = '<a href="lang/fr" class="kbd mr-1">fr</a><a href="lang/en" class="kbd">en</a>';
	@endphp
	@include('inc-nav-welcome')

	<div class="welcome container mt-5">
		
		{{--
		<div class="row pb-3 text-monospace">
			<div class="col-md-8 offset-md-2">
				<div class="border border-danger rounded p-2 text-danger small">
					<b>beta</b><br />
					La nouvelle version du site a entraîné de nombreux changements dans le code. Des bugs sont donc certainement présents. Vous pouvez les signaler en ouvrant un ticket <a href="https://forge.apps.education.fr/code-puzzle/www-codepuzzle-io/-/issues/" target="_blank">ici</a> ou en écrivant à <a href="mailto:contact@codepuzzle.io">contact@codepuzzle.io</a>.<br />Vous pouvez aussi intervenir sur <a href="https://mastodon.social/@codepuzzle" target="_blank">Mastodon</a> ou <a href="https://x.com/codepuzzleio" target="_blank">X/Twitter</a>. 
				</div>
			</div>
		</div>
		--}}


		<div class="small text-monospace font-weight-bold">Bacs à sable / démos</div>

		<!-- BACS A SABLE -->
		<div class="pt-2 row row-cols-1 row-cols-md-3 text-monospace">
			<div class="col mb-2">
				<div class="card h-100 p-0 bas" style="background-color:#ebf1f7 !important;border:1px solid #ebf1f7;">
					<a href="/python" class="d-block rounded p-3 text-center text-dark" target="_blank" data-toggle="tooltip" data-placement="top" title="Bac à sable Python pour les élèves et les enseignants">
						<img src="{{ asset('img/python-logo.svg') }}" height="25" class="mr-3" alt="PYTHON" /><b>PYTHON</b>
					</a>
				</div>
			</div>
			<div class="col mb-2">
				<div class="card h-100 p-0 bas" style="background-color:#ebf1f7 !important;border:1px solid #ebf1f7;">
					<a href="/REPL" class="d-block rounded p-3 text-center text-dark" target="_blank" data-toggle="tooltip" data-placement="top" title="Environnement Python pour démonstrations / explications au tableau">
						<img src="{{ asset('img/python-logo.svg') }}" height="25" class="mr-3" alt="PYTHON" /><b>PYTHON</b> <small class="text-muted">pour vidéoprojecteur</small>
					</a>
				</div>
			</div>
			<div class="col mb-2">
				<div class="card h-100 p-0 bas" style="background-color:#ebf1f7 !important;border:1px solid #ebf1f7;">
					<a href="/html" class="d-block rounded p-3 text-center text-dark" target="_blank" data-toggle="tooltip" data-placement="top" title="Bac à sable HTML/CSS pour les élèves et les enseignants">
						<img src="{{ asset('img/html-css-logo.svg') }}" height="25" class="mr-3" alt="HTML/CSS" /><b>HTML / CSS</b>
					</a>
				</div>
			</div>
		</div>
		<!-- BACS A SABLE -->

		<div class="mt-4 small text-monospace font-weight-bold">Activités et devoirs pour les élèves</div>

		<!-- DEFIS -->
		<div class="row mt-2 text-monospace">

			<div class="col-md-2">
				<div class="text-center">
					<a class="btn btn-success btn-block p-2" href="{{ route('defi-creer-get')}}" role="button" data-toggle="tooltip" data-placement="top" title="créer et partager un défi">
						{!!__('DÉFI')!!}
					</a>
				</div>
				<div class="pt-2 pb-2 text-center">
					<a class="btn btn-light btn-sm d-block p-2" href="/banque-defis" role="button">
						<i class="fa-solid fa-box-archive fa-lg pt-1 pb-1 mr-2"></i>Banque de défis<br />EP NSI
					</a>
				</div>
			</div>

			<div class="col-md-10">
				<div class="p-3 border rounded">
					<div style="float:right;">
						<a class="text-secondary" href="https://code-puzzle.forge.apps.education.fr/" target="_blank" role="button" data-toggle="tooltip" data-placement="top" title="documentation"><i class="fa-solid fa-circle-info"></i></a>
					</div>
					<div class="small text-muted text-justify pr-5">
						Défis Python avec jeux de tests à valider. Écrire ou compléter un programme Python en suivant les consignes fournies et exécuter le code jusqu'à validation de l'ensemble des tests.<br />Entraînement en autonomie pour l'Épreuve Pratiques de NSI.
					</div>	
					<div class="small mt-2"><i class="fa-solid fa-caret-right"></i> <a href="https://www.codepuzzle.io/DEKRL" class="text-success">exemple 1</a></div>
					<div class="small"><i class="fa-solid fa-caret-right"></i> <a href="https://www.codepuzzle.io/DQJG6" class="text-success">exemple 2</a></div>
				</div>
			</div>
		</div>
		<!-- DEFIS -->


		<!-- SUJETS / COPIES / DEVOIRS -->
		<div class="row mt-3 text-monospace">

			<div class="col-md-2">
				<div class="text-center">
					<a class="btn btn-success btn-block p-2 pl-3 pr-3" href="{{ route('sujet-creer-get')}}" role="button" data-toggle="tooltip" data-placement="top" title="créer et partager un sujet">
						{!!__('SUJET COPIE DEVOIR')!!}
					</a>
				</div>
				<div class="pt-2 pb-2 text-center">
					<a class="btn btn-light btn-sm d-block p-2" href="/banque-sujets" role="button">
						<i class="fa-solid fa-box-archive fa-lg pt-1 pb-1 mr-2"></i>Banque de sujets<br />EP NSI 2026
					</a>
				</div>
			</div>

			<div class="col-md-10">
				<div class="p-3 border rounded">
					<div style="float:right;">
						<a class="text-secondary" href="https://code-puzzle.forge.apps.education.fr/" target="_blank" role="button" data-toggle="tooltip" data-placement="top" title="documentation"><i class="fa-solid fa-circle-info"></i></a>
					</div>
					<div class="small text-muted text-justify pr-5">
						<div class="mb-2">Activités en classe ou en autonomie dans un environnement anti-triche. Récupération automatique des travaux avec exécution du code et correction en ligne.</div>
						<div>
							<div class="text-center text-white bg-dark small" style="float:left;font-weight:bold;border-radius:50%;width:15px;padding-left:1px;margin:2px 8px 5px 0px;">1</div> Créer un sujet (exercice Python, PDF, Markdown...)
						</div>
						<div style="clear:both;">
							<div class="text-center text-white bg-dark small" style="float:left;font-weight:bold;border-radius:50%;width:15px;padding-left:1px;margin:2px 8px 5px 0px;">2</div> Modifier / dupliquer / partager le sujet
						</div>
						<div style="clear:both;">
							<div class="text-center text-white bg-dark small" style="float:left;font-weight:bold;border-radius:50%;width:15px;padding-left:1px;margin:2px 8px 5px 0px;">3</div> Proposer un environnement sujet-copie aux élèves
						</div>
						<div style="clear:both;">
							<div class="text-center text-white bg-dark small" style="float:left;font-weight:bold;border-radius:50%;width:15px;padding-left:1px;margin:2px 8px 5px 0px;">4</div> Créer un devoir / superviser / corriger / rendre
						</div>					
					</div>	
					<div class="small pl-1 mt-2" style="clear:both;">
						<i class="fa-solid fa-caret-right"></i> exemple 1: <a href="https://www.codepuzzle.io/SX46G3" class="text-success">sujet</a> | <a href="https://www.codepuzzle.io/SX46G3/copie" class="text-success">sujet-copie</a> | <a href="https://www.codepuzzle.io/devoir-console/7WAG8QURFKYES5B6?i" class="text-success">devoir</a>
					</div>
					<div class="small pl-1" style="clear:both;">
						<i class="fa-solid fa-caret-right"></i> exemple 2: <a href="https://www.codepuzzle.io/S2BGDW" class="text-success">sujet</a> | <a href="https://www.codepuzzle.io/S2BGDW/copie" class="text-success">sujet-copie</a> | <a href="https://www.codepuzzle.io/devoir-console/QBWAHREKUGT69CFM?i" class="text-success">devoir</a>
					</div>
				</div>
			</div>
		</div>
		<!-- /SUJETS / COPIES / DEVOIRS -->


		<!-- PUZZLES -->
		<div class="row mt-3 text-monospace">

			<div class="col-md-2">
				<div class="text-center">
					<a class="btn btn-success d-block p-2" href="{{ route('puzzle-creer-get')}}" role="button" data-toggle="tooltip" data-placement="top" title="créer et partager un puzzle">
						{!!__('PUZZLE')!!}
					</a>
				</div>
				<div class="pt-2 text-center">
					<a class="btn btn-light btn-sm d-block p-2" href="/banque-puzzles" role="button">
						<i class="fa-solid fa-box-archive fa-lg pt-1 pb-1 mr-2"></i>Banque de puzzles
					</a>
				</div>
			</div>

			<div class="col-md-10">
				<div class="p-3 border rounded">
					<div style="float:right;">
						<a class="text-secondary" href="https://code-puzzle.forge.apps.education.fr/" target="_blank" role="button" data-toggle="tooltip" data-placement="top" title="documentation"><i class="fa-solid fa-circle-info"></i></a>
					</div>
					<div class="small text-muted text-justify pr-5">
						Puzzles de Parsons en mode "réorganiser" ou "glisser-déposer".<br />Avec ou sans code à compléter.<br />Pour découvrir les bases du langage Python, les algorithmes classiques et commencer à apprendre à programmer sans avoir à écrire de code.
					</div>	
					<div class="small mt-2"><i class="fa-solid fa-caret-right"></i> <a href="https://www.codepuzzle.io/PZNQEJ" class="text-success">exemple 1</a></div>
					<div class="small"><i class="fa-solid fa-caret-right"></i> <a href="https://www.codepuzzle.io/PPMJU6" class="text-success">exemple 2</a></div>
				</div>
			</div>
		</div>
		<!-- /PUZZLES -->


		<div class="pt-4 small text-monospace font-weight-bold">Pour la classe</div>

		<div class="row pt-2 text-monospace">
			
			<div class="col-md-12">
				<div class="card-deck">

					<div class="card">
						<div style="position:absolute;right:10px;top:8px;">
							<a class="text-secondary" href="https://code-puzzle.forge.apps.education.fr/" target="_blank" role="button" data-toggle="tooltip" data-placement="top" title="documentation"><i class="fa-solid fa-circle-info"></i></a>
						</div>
						<div class="card-body p-0">
							<div class="text-center">
								<a class="btn btn-dark p-2 pl-3 pr-3" href="{{ route('classe-creer-get')}}" role="button" data-toggle="tooltip" data-placement="top" title="créer une classe"><i class="fas fa-chalkboard mr-2"></i>{!!__('CLASSE')!!}</a>
							</div>			
							<div class="mt-3 small text-muted text-justify">
								Créer une classe pour proposer des activités (puzzles, défis, sujets, devoirs...) aux élèves et suivre l'avancement de leur travail. Génération de codes individuels et de tableaux de bord personnels.
							</div>	
						</div>
					</div>	
				
					<div class="card">
						<div style="position:absolute;right:10px;top:8px;">
							<a class="text-secondary" href="https://code-puzzle.forge.apps.education.fr/" target="_blank" role="button" data-toggle="tooltip" data-placement="top" title="documentation"><i class="fa-solid fa-circle-info"></i></a>
						</div>
						<div class="card-body p-0">
							<div class="text-center">
								<a class="btn btn-dark p-2 pl-3 pr-3" href="{{ route('programme-creer-get')}}" role="button" data-toggle="tooltip" data-placement="top" title="ajouter un programme"><i class="fa-solid fa-gears pt-1 pb-1 mr-2"></i>{!!__('PROGRAMME')!!}</a>
							</div>			
							<div class="mt-3 small text-muted text-justify">
								Programmes à exécuter dans un environnement Python en ligne, pour des démonstrations au tableau, travailler sur des exemples en classe...
							</div>	
							<div class="small mt-2"><i class="fa-solid fa-caret-right"></i> <a href="https://www.codepuzzle.io/R63DBS" class="text-success">exemple</a></div>
						</div>
					</div>	
					
				</div>
			</div>
		</div>	
		
		<div class="row pt-5 text-monospace">
			<div class="col-md-12">
				<div class="text-center"><h2 class="p-0 m-0">ENVIRONNEMENT PYTHON EN LIGNE</h2></div>
				<div class="text-center small">Pour des démonstrations en classe ou pour s'entraîner. Cliquer sur l'image pour lancer l'environnement Python.</div>
				<div class="text-center"><a href="/REPL"><img src="{{ asset('img/REPL.png') }}" class="img-fluid" alt="REPL" data-toggle="tooltip" data-placement="top" title="Ouvrir le REPL Python" /></a></div>
			</div>		
		</div>		

	</div>


	@include('inc-footer')
	<div style="padding-bottom:200px;">&nbsp;</div>
	@include('inc-bottom-js')

	<script>
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		  return new bootstrap.Tooltip(tooltipTriggerEl)
		})
	</script>

</body>
</html>
