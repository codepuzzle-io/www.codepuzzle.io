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

	<div class="container mt-3">

		<div class="row pt-3 text-monospace">
			<div class="col-md-1 text-muted text-right small">
				Activités
			</div>
			<div class="col-md-11">
				<div class="card-deck">
					
					<div class="card ml-1 mr-1">
						<div style="position:absolute;right:10px;top:8px;">
							<a class="text-secondary" href="" role="button" data-toggle="tooltip" data-placement="top" title="documentation"><i class="fa-solid fa-circle-info"></i></a>
						</div>
						<div class="card-body p-0">
							<div class="mx-auto text-center" style="width:60%">
								<a class="btn btn-success d-block p-2" href="{{ route('puzzle-creer-get')}}" role="button" data-toggle="tooltip" data-placement="top" title="créer et partager un puzzle"><i class="fa-solid fa-circle-plus pt-1 pb-1 mr-2"></i>{!!__('PUZZLE')!!}</a>
							</div>
							<div class="mt-3 small text-muted text-justify">
								Puzzles de Parsons en mode "réorganiser" ou "glisser-déposer".<br />Avec ou sans code à compléter.<br />Pour découvrir les bases du langage Python, les algorithmes classiques et commencer à apprendre à programmer sans avoir à écrire de code.
							</div>	
							<div class="small mt-2"><i class="fa-solid fa-caret-right"></i> <a href="" class="text-success">exemple 1</a></div>
							<div class="small mb-2"><i class="fa-solid fa-caret-right"></i> <a href="" class="text-success">exemple 2</a></div>
						</div>
						<div class="card-footer pt-2 text-center">
							<a class="btn btn-light btn-sm text-left pl-3 pr-3" href="/banque-puzzles" role="button">
								<div style="float:left;padding-right:10px;padding-top:3px;"><i class="fa-solid fa-box-archive fa-xl"></i></div> Banque de<br />puzzles
							</a>
						</div>
					</div>

					<div class="card ml-1 mr-1">
						<div style="position:absolute;right:10px;top:8px;">
							<a class="text-secondary" href="" role="button" data-toggle="tooltip" data-placement="top" title="documentation"><i class="fa-solid fa-circle-info"></i></a>
						</div>
						<div class="card-body p-0">
							<div class="mx-auto text-center" style="width:60%">
								<a class="btn btn-success btn-block p-2" href="{{ route('defi-creer-get')}}" role="button" data-toggle="tooltip" data-placement="top" title="créer et partager un défi"><i class="fa-solid fa-circle-plus pt-1 pb-1 mr-2"></i>{!!__('DÉFI')!!}</a>
							</div>
							<div class="mt-3 small text-muted text-justify">
								Défis Python avec jeux de tests à valider. Écrire ou compléter un programme Python en suivant les consignes fournies et exécuter le code jusqu'à validation de l'ensemble des tests.<br />Entraînement en autonomie pour l'Épreuve Pratiques de NSI.
							</div>	
							<div class="small mt-2"><i class="fa-solid fa-caret-right"></i> <a href="" class="text-success">exemple 1</a></div>
							<div class="small mb-2"><i class="fa-solid fa-caret-right"></i> <a href="" class="text-success">exemple 2</a></div>
						</div>
						<div class="card-footer pt-2 text-center">
							<a class="btn btn-light btn-sm text-left pl-3 pr-3" href="/banque-defis" role="button">
								<div style="float:left;padding-right:10px;padding-top:3px;"><i class="fa-solid fa-box-archive fa-xl"></i></div> Banque de défis<br />EP NSI 2024
							</a>
						</div>
					</div>

					<div class="card ml-1 mr-1" style="flex-grow:2;">
						<div style="position:absolute;right:10px;top:8px;">
							<a class="text-secondary" href="" role="button" data-toggle="tooltip" data-placement="top" title="documentation"><i class="fa-solid fa-circle-info"></i></a>
						</div>
						<div class="card-body p-0">
							<div class="text-center">
								<a class="btn btn-success p-2 pl-3 pr-3" href="{{ route('sujet-creer-get')}}" role="button" data-toggle="tooltip" data-placement="top" title="créer et partager un sujet"><i class="fa-solid fa-circle-plus pt-1 pb-1 mr-2"></i>{!!__('SUJET / COPIE / DEVOIR')!!}</a>
							</div>
							<div class="mt-3 small text-muted text-justify">
								<div class="mb-2">Activités en classe ou en autonomie dans un environnement anti-triche. Récupération automatique des travaux avec exécution du code et correction en ligne.</div>
								<div>
									<div class="text-center text-white bg-dark small" style="float:left;font-weight:bold;border-radius:50%;width:15px;margin:2px 8px 5px 0px;">1</div> Créer un sujet (exercice Python, PDF, Markdown...)
								</div>
								<div style="clear:both;">
									<div class="text-center text-white bg-dark small" style="float:left;font-weight:bold;border-radius:50%;width:15px;margin:2px 8px 5px 0px;">2</div> Modifier / duplier / partager le sujet
								</div>
								<div style="clear:both;">
									<div class="text-center text-white bg-dark small" style="float:left;font-weight:bold;border-radius:50%;width:15px;margin:2px 8px 5px 0px;">3</div> Proposer un environnement sujet-copie aux élèves
								</div>
								<div style="clear:both;">
									<div class="text-center text-white bg-dark small" style="float:left;font-weight:bold;border-radius:50%;width:15px;margin:2px 8px 5px 0px;">4</div> Créer un devoir / superviser / corriger / rendre
								</div>					
							</div>	
							<div class="small pl-1 mt-2" style="clear:both;">
								<i class="fa-solid fa-caret-right"></i> exemple 1: <a href="" class="text-success">sujet</a> | <a href="" class="text-success">sujet-copie</a> | <a href="" class="text-success">devoir</a>
							</div>
							<div class="small pl-1 mb-2" style="clear:both;">
								<i class="fa-solid fa-caret-right"></i> exemple 2: <a href="" class="text-success">sujet</a> | <a href="" class="text-success">sujet-copie</a> | <a href="" class="text-success">devoir</a>
							</div>
						</div>
						<div class="card-footer pt-2 text-center">
							<a class="btn btn-light btn-sm text-left pl-3 pr-3" href="/banque-sujets" role="button">
								<div style="float:left;padding-right:12px;padding-top:3px;"><i class="fa-solid fa-box-archive fa-xl"></i></div> Banque de<br />sujets
							</a>
						</div>
					</div>

				</div>
			</div>
		</div>


		<div class="row pt-5 text-monospace">
			<div class="col-md-1 text-muted text-right small">
				Pour la classe
			</div>
			<div class="col-md-11">
				<div class="card-deck">
				
					<div class="card ml-1 mr-1">
						<div style="position:absolute;right:10px;top:8px;">
							<a class="text-secondary" href="" role="button" data-toggle="tooltip" data-placement="top" title="documentation"><i class="fa-solid fa-circle-info"></i></a>
						</div>
						<div class="card-body p-0">
							<div class="text-center">
								<a class="btn btn-dark p-2 pl-3 pr-3" href="{{ route('programme-creer-get')}}" role="button" data-toggle="tooltip" data-placement="top" title="ajouter un programme"><i class="fa-solid fa-gears pt-1 pb-1 mr-2"></i>{!!__('PROGRAMME')!!}</a>
							</div>			
							<div class="mt-3 small text-muted text-justify">
								Programmes à exécuter dans un environnement Python en ligne, pour des démonstrations au tableau, travailler sur des exemples en classe...
							</div>	
							<div class="small mt-2"><i class="fa-solid fa-caret-right"></i> <a href="" class="text-success">exemple</a></div>
						</div>
					</div>	
					
					<div class="card ml-1 mr-1">
						<div style="position:absolute;right:10px;top:8px;">
							<a class="text-secondary" href="" role="button" data-toggle="tooltip" data-placement="top" title="documentation"><i class="fa-solid fa-circle-info"></i></a>
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

				</div>
			</div>
		</div>		


	</div>

	<div class="container mt-3">
		<div class="row text-monospace">
			<div class="col-md-11 offset-md-1">
				<div class="text-center"><h2>ENVIRONNEMENT PYTHON EN LIGNE</h2></div>
				<div class="mt-2 text-center"><a href="/REPL"><img src="{{ asset('img/REPL.png') }}" class="img-fluid" alt="REPL" data-toggle="tooltip" data-placement="top" title="Ouvrir le REPL Python" /></a></div>
			</div>
		</div>
	</div>

	@include('inc-footer')
	<div style="padding-bottom:400px;">&nbsp;</div>
	@include('inc-bottom-js')

	<script>
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		  return new bootstrap.Tooltip(tooltipTriggerEl)
		})
	</script>

</body>
</html>
