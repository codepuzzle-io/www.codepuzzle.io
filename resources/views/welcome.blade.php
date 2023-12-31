@include('inc-top')
<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
	@php
		$description = __('Puzzles de Parsons, défis & entraînements/devoirs pour apprendre Python');
		$description_og = __('Puzzles de Parsons, défis & entraînements/devoirs pour apprendre Python');
	@endphp
	@include('inc-meta')
    <title>{{ strtoupper(config('app.name')) }} | {{__('Puzzles de Parsons & Défis')}}</title>
</head>
<body>
	@php
		$lang_switch = '<a href="lang/fr" class="kbd mr-1">fr</a><a href="lang/en" class="kbd">en</a>';
	@endphp
	@include('inc-nav')

	<div class="container-fluid mt-3 pl-3 pr-3">

		<div class="row pt-3 pl-4 pr-4">
			<div class="card-deck text-monospace">
				
				<div class="card ml-1 mr-1">
					<div class="card-body p-0">
						<h2>{!!__('Créer et partager des puzzles de Parsons', ['link' => route('about')])!!}</h2>
						<div class="mx-auto mt-3 text-center" style="width:60%">
							<a class="btn btn-success btn-sm btn-block p-2" href="{{ route('puzzle-creer-get')}}" role="button"><i class="fa-solid fa-circle-plus pt-1 pb-1 mr-2"></i>{!!__('puzzle')!!}</a>
						</div>
						<div class="mt-3 small text-muted text-justify">
							Puzzles de Parsons en mode "réorganiser" ou "glisser-déposer". Avec ou sans code à compléter. Pour apprendre Python sans écrire de code.
						</div>	
					</div>
					<div class="text-center pt-2">
						<a class="btn btn-light btn-sm pl-3 pr-3" href="#puzzle" role="button" data-toggle="tooltip" data-placement="top" title="plus d'informations"><i class="fa-solid fa-circle-info"></i></a>
					</div>
				</div>

				<div class="card ml-1 mr-1">
					<div class="card-body p-0">
						<h2>{!!__('Créer et partager des défis')!!}</h2>				
						<div class="mx-auto mt-3 text-center" style="width:60%">
							<a class="btn btn-success btn-sm btn-block p-2" href="{{ route('defi-creer-get')}}" role="button"><i class="fa-solid fa-circle-plus pt-1 pb-1 mr-2"></i>{!!__('défi')!!}</a>
						</div>
						<div class="mt-3 small text-muted text-justify">
							Défis Python avec jeux de tests à valider. Écrire ou compléter un programme Python en suivant les consignes fournies et tester le code en ligne.
						</div>	
					</div>
					<div class="text-center pt-2">
						<a class="btn btn-light btn-sm pl-3 pr-3" href="#defi" role="button" data-toggle="tooltip" data-placement="top" title="plus d'informations"><i class="fa-solid fa-circle-info"></i></a>
						<a class="btn btn-light btn-sm pl-3 pr-3" href="/defis-banque" role="button" data-toggle="tooltip" data-placement="top" title="banque de défis"><i class="fa-solid fa-box-archive"></i></a>
					</div>
				</div>

				<div class="card ml-1 mr-1">
					<div class="card-body p-0">
						<h2>{!!__('Créer et partager des programmes')!!}</h2>				
						<div class="mx-auto mt-3 text-center" style="width:60%">
							<a class="btn btn-success btn-sm btn-block p-2" href="{{ route('programme-creer-get')}}" role="button"><i class="fa-solid fa-circle-plus pt-1 pb-1 mr-2"></i>{!!__('programme')!!}</a>
						</div>
						<div class="mt-3 small text-muted text-justify">
							Programmes à partager dans un environnement Python interactif. Peuvent être modifiés et exécutés. Démonstrations au tableau, entraînements, exemples...
						</div>	
					</div>
					<div class="text-center pt-2">
						<a class="btn btn-light btn-sm pl-3 pr-3" href="#programme" role="button" data-toggle="tooltip" data-placement="top" title="plus d'informations"><i class="fa-solid fa-circle-info"></i></a>
					</div>
				</div>				

				<div class="card ml-1 mr-1">
					<div class="card-body p-0">
						<h2>{!!__('Créer et partager des devoirs')!!}</h2>				
						<div class="mx-auto mt-3 text-center" style="width:60%">
							<a class="btn btn-success btn-sm btn-block p-2" href="{{ route('devoir-creer-get')}}" role="button"><i class="fa-solid fa-circle-plus pt-1 pb-1 mr-2"></i>{!!__('devoir')!!}</a>
						</div>
						<div class="mt-3 small text-muted text-justify">
							Activités en classe de type examen dans un environnement anti-triche.<br />Récupération automatique des travaux avec exécution du code et correction en ligne.
						</div>	
					</div>
					<div class="text-center pt-2">
						<a class="btn btn-light btn-sm pl-3 pr-3" href="#devoir" role="button" data-toggle="tooltip" data-placement="top" title="plus d'informations"><i class="fa-solid fa-circle-info"></i></a>
					</div>
				</div>

				<div class="card ml-1 mr-1 pt-1 pb-0" style="border:none !important;background-color:transparent !important">
					<div class="card-body p-0">				
						<div class="mx-auto text-center" style="width:80%">
							<a class="btn btn-dark btn-sm btn-block p-2" href="{{ route('classe-creer-get')}}" role="button"><i class="fas fa-chalkboard mr-2"></i><b>{!!__('CLASSE')!!}</b> <sup class="text-danger">bêta</sup></a>
						</div>
						<div class="mt-2 small text-muted text-justify">
							Créer une classe pour proposer des activités (puzzles, défis...) aux élèves et suivre l'avancement de leur travail.

							<a class="text-dark" href="#classe" role="button" data-toggle="tooltip" data-placement="top" title="plus d'informations"><i class="fa-solid fa-circle-info"></i></a>
						</div>
						<div class="pt-5 mx-auto text-center" style="width:80%">
							<i style="font-size:70%;color:silver;">optionnel</i>
							<a class="btn btn-secondary btn-sm btn-block" style="font-size:80%;opacity:0.6" href="{{route('register')}}" role="button">{{__('créer un compte')}}</a>
						</div>
						<div class="text-center mt-1" style="line-height:1;">
							<span style="font-size:70%;color:silver;">{{__('pour créer, sauvegarder, modifier et partager les activités proposées aux élèves')}}</span>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>

	<div class="mt-4 text-center"><a href="/REPL"><img src="{{ asset('img/REPL.png') }}" class="img-fluid" alt="REPL" data-toggle="tooltip" data-placement="top" title="Ouvrir le REPL Python" /></a></div>

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

	</div><!--container -->

	@include('inc-footer')
	<div style="padding-bottom:1000px;">&nbsp;</div>
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
