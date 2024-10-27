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
	@include('inc-nav')

	<div class="container mt-3">

		<div class="row pt-3 text-monospace">
			<div class="col-md-1 text-muted text-right small">
				Activités
			</div>
			<div class="col-md-11">
				<div class="card-deck">
					
					<div class="card ml-1 mr-1">
						<div style="position:absolute;right:10px;top:8px;">
							<a class="text-secondary" href="" role="button" data-toggle="tooltip" data-placement="top" title="plus d'informations"><i class="fa-solid fa-circle-info"></i></a>
						</div>
						<div class="card-body p-0">
							<div class="mx-auto text-center" style="width:60%">
								<a class="btn btn-success btn-sm d-block p-2" href="{{ route('puzzle-creer-get')}}" role="button" data-toggle="tooltip" data-placement="top" title="créer et partager un puzzle"><i class="fa-solid fa-circle-plus pt-1 pb-1 mr-2"></i>{!!__('PUZZLE')!!}</a>
							</div>
							<div class="mt-3 small text-muted text-justify">
								Puzzles de Parsons en mode "réorganiser" ou "glisser-déposer". Avec ou sans code à compléter. Pour apprendre les algorithmes et à programmer en Python sans écrire de code.
							</div>	
							<div class="small mt-3 mb-2"><i class="fa-solid fa-caret-right"></i> <a href="" class="text-success">exemple</a></div>
						</div>
						<div class="card-footer pt-2 text-center">
							<a class="btn btn-light btn-sm text-left pl-3 pr-3" href="/defis-banque" role="button">
								<div style="float:left;padding-right:10px;padding-top:3px;"><i class="fa-solid fa-box-archive fa-xl"></i></div> Banque de<br />puzzles
							</a>
						</div>
					</div>

					<div class="card ml-1 mr-1">
						<div style="position:absolute;right:10px;top:8px;">
							<a class="text-secondary" href="" role="button" data-toggle="tooltip" data-placement="top" title="plus d'informations"><i class="fa-solid fa-circle-info"></i></a>
						</div>
						<div class="card-body p-0">
							<div class="mx-auto text-center" style="width:60%">
								<a class="btn btn-success btn-sm btn-block p-2" href="{{ route('defi-creer-get')}}" role="button" data-toggle="tooltip" data-placement="top" title="créer et partager un défi"><i class="fa-solid fa-circle-plus pt-1 pb-1 mr-2"></i>{!!__('DÉFI')!!}</a>
							</div>
							<div class="mt-3 small text-muted text-justify">
								Défis Python avec jeux de tests à valider. Écrire ou compléter un programme Python en suivant les consignes fournies et exécuter le code en ligne en validant les jeux de tests.
							</div>	
							<div class="small mt-3 mb-2"><i class="fa-solid fa-caret-right"></i> <a href="" class="text-success">exemple</a></div>
						</div>
						<div class="card-footer pt-2 text-center">
							<a class="btn btn-light btn-sm text-left pl-3 pr-3" href="/defis-banque" role="button">
								<div style="float:left;padding-right:10px;padding-top:3px;"><i class="fa-solid fa-box-archive fa-xl"></i></div> Banque de défis<br />EP NSI 2024
							</a>
						</div>
					</div>

					<div class="card ml-1 mr-1" style="flex-grow:3;">
						<div style="position:absolute;right:10px;top:8px;">
							<a class="text-secondary" href="" role="button" data-toggle="tooltip" data-placement="top" title="plus d'informations"><i class="fa-solid fa-circle-info"></i></a>
						</div>
						<div class="card-body p-0">
							<div class="text-center">
								<a class="btn btn-success btn-sm p-2 pl-3 pr-3" href="{{ route('sujet-creer-get')}}" role="button" data-toggle="tooltip" data-placement="top" title="créer et partager un sujet"><i class="fa-solid fa-circle-plus pt-1 pb-1 mr-2"></i>{!!__('SUJET / COPIE / DEVOIR')!!}</a>
							</div>
							<div class="mt-3 small text-muted text-justify">
								<div class="mb-2">Activités en classe ou en autonomie dans un environnement anti-triche. Récupération automatique des travaux avec exécution du code et correction en ligne.</div>
								<div>
									<div class="text-center text-white bg-dark small" style="float:left;font-weight:bold;border-radius:50%;width:15px;margin:2px 8px 5px 0px;">1</div> Créer un sujet (exercice python, PDF, Markdown...)
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
							<div class="small pl-1 mt-3" style="clear:both;">
								<i class="fa-solid fa-caret-right"></i> exemple 1: <a href="" class="text-success">sujet</a> | <a href="" class="text-success">sujet-copie</a> | <a href="" class="text-success">devoir</a>
							</div>
							<div class="small pl-1 mb-2" style="clear:both;">
								<i class="fa-solid fa-caret-right"></i> exemple 2: <a href="" class="text-success">sujet</a> | <a href="" class="text-success">sujet-copie</a> | <a href="" class="text-success">devoir</a>
							</div>
						</div>
						<div class="card-footer pt-2 text-center">
							<a class="btn btn-light btn-sm text-left pl-3 pr-3" href="/defis-banque" role="button">
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
							<a class="text-secondary" href="" role="button" data-toggle="tooltip" data-placement="top" title="plus d'informations"><i class="fa-solid fa-circle-info"></i></a>
						</div>
						<div class="card-body p-0">
							<div class="text-center">
								<a class="btn btn-dark btn-sm p-2 pl-3 pr-3" href="{{ route('programme-creer-get')}}" role="button" data-toggle="tooltip" data-placement="top" title="ajouter un programme"><i class="fa-solid fa-circle-plus pt-1 pb-1 mr-2"></i>{!!__('PROGRAMME')!!}</a>
							</div>			
							<div class="mt-3 small text-muted text-justify">
								Programmes à exécuter dans un environnement Python interactif, pour des démonstrations au tableau, des entraînements, travailler sur des exemples en classe...
							</div>	
						</div>
					</div>	
					
					<div class="card ml-1 mr-1">
						<div style="position:absolute;right:10px;top:8px;">
							<a class="text-secondary" href="" role="button" data-toggle="tooltip" data-placement="top" title="plus d'informations"><i class="fa-solid fa-circle-info"></i></a>
						</div>
						<div class="card-body p-0">
							<div class="text-center">
								<a class="btn btn-dark btn-sm p-2 pl-3 pr-3" href="{{ route('classe-creer-get')}}" role="button" data-toggle="tooltip" data-placement="top" title="créer une classe"><i class="fas fa-chalkboard mr-2"></i>{!!__('CLASSE')!!}</a>
							</div>			
							<div class="mt-3 small text-muted text-justify">
								Créer une classe pour proposer des activités (puzzles, défis, sujets, devoirs...) aux élèves et suivre l'avancement de leur travail.
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

	<!--
	<div class="container mt-3">

		<a name="puzzle"></a>
		<div class="row mt-5 text-monospace">
			<div class="col-md-2">
				<div class="font-weight-bold text-success">PUZZLE DE PARSONS</div>
				<div class="font-weight-bold">Exemple 1</div>
				<div class="small text-muted">en mode "réorganiser"</div>
			</div>
			<div class="col-md-10 exemple">
				<div id="exemple1_div">
					<iframe id="exemple1_iframe" src="https://www.codepuzzle.io/IPNHVL" height="100%" width="100%" frameborder="0"></iframe>
				</div>
			</div>
		</div>

		<div class="row mt-5 text-monospace">
			<div class="col-md-2">
				<div class="font-weight-bold">Exemple 2</div>
				<div class="small text-muted">en mode "glisser-déposer" avec des lignes de code inutiles</div>
			</div>
			<div class="col-md-10 exemple">
				<div id="exemple2_div">
					<iframe id="exemple2_iframe" src="https://www.codepuzzle.io/IP39K2" height="100%" width="100%" frameborder="0"></iframe>
				</div>
			</div>
		</div>

		<div class="row mt-5 text-monospace">
			<div class="col-md-2">
				<div class="font-weight-bold">Exemple 3</div>
				<div class="small text-muted">en mode "réorganiser" avec code à compléter</div>
			</div>
			<div class="col-md-10 exemple">
				<div id="exemple3_div">
					<iframe id="exemple3_iframe" src="https://www.codepuzzle.io/IPUAH8" height="100%" width="100%" frameborder="0"></iframe>
				</div>
			</div>
		</div>

		<a name="defi"></a>
		<div class="row mt-5 text-monospace">
			<div class="col-md-2">
				<div class="font-weight-bold text-success">DÉFI</div>
				<div class="font-weight-bold">Exemple</div>
				<div class="small text-muted">avec quatre tests</div>
			</div>
			<div class="col-md-10 exemple">
				<div id="exemple4_div">
					<iframe id="exemple4_iframe" src="https://www.codepuzzle.io/IDZLB8" height="100%" width="100%" frameborder="0"></iframe>
				</div>
			</div>
		</div>	
	
		<a name="programme"></a>
		<div class="row mt-5 text-monospace">
			<div class="col-md-2">
				<div class="font-weight-bold text-success">PROGRAMMES</div>
				<div class="font-weight-bold">Mode plein écran avec commandes (taille de la police, orientation, banque de programmes...)</div>
			</div>
			<div class="col-md-10 exemple">
				<img src="{{ asset('img/programme/programmes.png') }}" class="d-block w-100" />
			</div>
		</div>		

		<a name="devoir"></a>
		<div class="row mt-5 text-monospace">
			<div class="col-md-2">
				<div class="font-weight-bold text-success">DEVOIRS</div>
				<div class="font-weight-bold">Diaporama</div>
			</div>
			<div class="col-md-10 exemple">

				<div id="carouselCaptions" class="carousel slide" data-interval="false">
					<div class="carousel-inner">
						<div class="carousel-item active" style="margin-top:100px;">
							<img src="{{ asset('img/devoir/devoir-00.png') }}" class="d-block w-100" alt="E1">
							<div class="carousel-caption d-none d-md-block text-center text-white small">
								<span class="bg-dark p-1 pl-2 pr-2">console enseignant</span>
							</div>
						</div>
						<div class="carousel-item" style="margin-top:100px;">
							<img src="{{ asset('img/devoir/devoir-01.png') }}" class="d-block w-100" alt="E1">
							<div class="carousel-caption d-none d-md-block text-center text-white small">
								<span class="bg-dark p-1 pl-2 pr-2">création d'un nouvel entraînement/devoir</span>
							</div>
						</div>
						<div class="carousel-item" style="margin-top:100px;">
							<img src="{{ asset('img/devoir/devoir-02.png') }}" class="d-block w-100" alt="E1">
							<div class="carousel-caption d-none d-md-block text-center text-white small">
								<span class="bg-dark p-1 pl-2 pr-2">correction du travail d'un élève</span>
							</div>
						</div>
						<div class="carousel-item" style="margin-top:100px;">
							<img src="{{ asset('img/devoir/devoir-03.png') }}" class="d-block w-100" alt="E1">
							<div class="carousel-caption d-none d-md-block text-center text-white small">
								<span class="bg-dark p-1 pl-2 pr-2">lancement de l'entraînement/devoir côté élève</span>
							</div>
						</div>
						<div class="carousel-item" style="margin-top:100px;">
							<img src="{{ asset('img/devoir/devoir-04.png') }}" class="d-block w-100" alt="E1">
							<div class="carousel-caption d-none d-md-block text-center text-white small">
								<span class="bg-dark p-1 pl-2 pr-2">page de l'entraînement/devoir</span>
							</div>
						</div>	
						<div class="carousel-item" style="margin-top:100px;">
							<img src="{{ asset('img/devoir/devoir-05.png') }}" class="d-block w-100" alt="E1">
							<div class="carousel-caption d-none d-md-block text-center text-white small">
								<span class="bg-dark p-1 pl-2 pr-2">page verrouillée - appel de l'enseignant</span>
							</div>
						</div>																		
					</div>
					<a class="carousel-control-prev" href="#carouselCaptions" role="button" data-slide="prev">
						<i class="fa-solid fa-circle-chevron-left text-dark fa-2xl"></i>
						<span class="sr-only">Previous</span>
					</a>
					<a class="carousel-control-next" href="#carouselCaptions" role="button" data-slide="next">
						<i class="fa-solid fa-circle-chevron-right text-dark fa-2xl"></i>
						<span class="sr-only">Next</span>
					</a>
				</div>

			</div>
		</div>	

		<a name="classe"></a>
		<div class="row mt-5 text-monospace">
			<div class="col-md-2">
				<div class="font-weight-bold text-success">CLASSE</div>
				<div class="font-weight-bold">Suivi des activités</div>
			</div>
			<div class="col-md-10 exemple">
				<img src="{{ asset('img/classe/classe_suivi.png') }}" class="d-block w-100" />
			</div>
		</div>	

	</div>
	-->

	@include('inc-footer')
	<div style="padding-bottom:400px;">&nbsp;</div>
	@include('inc-bottom-js')

	<script>
		for (var i = 1; i <= 4; i++) {
			(function(index) {
				document.getElementById('exemple' + index + '_iframe').addEventListener('load', function() {
					var iframeHeight = document.getElementById('exemple' + index + '_iframe').contentWindow.document.body.scrollHeight;
					document.getElementById('exemple' + index + '_div').style.height = iframeHeight+20 + 'px';
				});
			})(i);
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
