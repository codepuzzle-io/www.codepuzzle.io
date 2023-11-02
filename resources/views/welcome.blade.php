@include('inc-top')
<html lang="{{ app()->getLocale() }}">
<head>
	@php
		$description = __('Puzzles de Parsons & Défis pour apprendre Python');
		$description_og = __('Puzzles de Parsons & Défis pour apprendre Python');
	@endphp
	@include('inc-meta')
    <title>{{ strtoupper(config('app.name')) }} | {{__('Puzzles de Parsons & Défis')}}</title>
</head>
<body>
	@php
		$lang_switch = '<a href="lang/fr" class="kbd mr-1">fr</a><a href="lang/en" class="kbd">en</a>';
	@endphp
	@include('inc-nav')

	<div class="container mt-3">

		<div class="row pt-3">
			<div class="card-deck text-monospace">
				
				<div class="card">
					<div class="card-body p-0">
						<h2>{!!__('Créer et partager des puzzles de Parsons', ['link' => route('about')])!!}</h2>
						<div class="mx-auto mt-3 text-center" style="width:60%">
							<a class="btn btn-success btn-sm btn-block p-2" href="{{ route('puzzle-creer-get')}}" role="button"><i class="fa-solid fa-circle-plus pt-1 pb-1"></i><br />{!!__('puzzle')!!}</a>
						</div>
						<div class="mt-3 small text-muted text-justify">
							Puzzles de Parsons en mode "réorganiser" ou "glisser-déposer".<br />Avec ou sans code à compléter.
						</div>	
					</div>
					<div class="text-center pt-2">
						<a class="btn btn-light btn-sm pl-3 pr-3" href="#puzzle" role="button"><i class="fa-solid fa-circle-info"></i></a>
					</div>
				</div>

				<div class="card">
					<div class="card-body p-0">
						<h2>{!!__('Créer et partager des défis')!!}</h2>				
						<div class="mx-auto mt-3 text-center" style="width:60%">
							<a class="btn btn-success btn-sm btn-block p-2" href="{{ route('defi-creer-get')}}" role="button"><i class="fa-solid fa-circle-plus pt-1 pb-1"></i><br />{!!__('défi')!!}</a>
						</div>
						<div class="mt-3 small text-muted text-justify">
							Défis avec jeux de tests à valider.<br />Écrire une fonction en suivant les consignes fournies.
						</div>	
					</div>
					<div class="text-center pt-2">
						<a class="btn btn-light btn-sm pl-3 pr-3" href="#defi" role="button"><i class="fa-solid fa-circle-info"></i></a>
					</div>
				</div>

				<div class="card">
					<div class="card-body p-0">
						<h2>{!!__('Créer et partager des entraînements / devoirs')!!} <sup class="text-lowercase text-danger">bêta</sup></h2>				
						<div class="mx-auto mt-3 text-center" style="width:60%">
							<a class="btn btn-success btn-sm btn-block p-2" href="{{ route('devoir-creer-get')}}" role="button"><i class="fa-solid fa-circle-plus pt-1 pb-1"></i><br />{!!__('entraînement / devoir')!!}</a>
						</div>
						<div class="mt-3 small text-muted text-justify">
							Activités en classe de type examen dans un environnement anti-triche.<br />Récupération automatique des travaux avec exécution du code et correction en ligne.
						</div>	
					</div>
					<div class="text-center pt-2">
						<a class="btn btn-light btn-sm pl-3 pr-3" href="#entrainement" role="button"><i class="fa-solid fa-circle-info"></i></a>
					</div>
				</div>

			</div>
		</div>
	</div>

	<div class="container mt-3">

		<div class="row pt-3">
			<div class="col-md-2 offset-md-5 text-center">
				<a class="btn btn-secondary btn-sm btn-block mt-3 mb-1" style="font-size:80%;opacity:0.6" href="{{route('register')}}" role="button">{{__('créer un compte')}}</a>
				<span style="font-size:70%;color:silver;">{{__('pour créer, sauvegarder, modifier et partager les activités proposées aux élèves')}}</span>
			</div>
		</div>
	</div>

	<div class="container mt-3">	

		<div class="row mt-5 text-monospace">
			<div class="col-md-2">
				<a name="puzzle"></a>
				<div class="font-weight-bold text-success">PUZZLE DE PARSONS</div>
				<div class="font-weight-bold">Exemple 1</div>
				<div class="small text-muted">en mode "réorganiser"</div>
			</div>
			<div class="col-md-10">
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
			<div class="col-md-10">
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
			<div class="col-md-10">
				<div id="exemple3_div">
					<iframe id="exemple3_iframe" src="https://www.codepuzzle.io/IPUAH8" height="100%" width="100%" frameborder="0"></iframe>
				</div>
			</div>
		</div>

		<div class="row mt-5 text-monospace">
			<div class="col-md-2">
				<a name="defi"></a>
				<div class="font-weight-bold text-success">DÉFI</div>
				<div class="font-weight-bold">Exemple</div>
				<div class="small text-muted">avec quatre tests</div>
			</div>
			<div class="col-md-10">
				<div id="exemple4_div">
					<iframe id="exemple4_iframe" src="https://www.codepuzzle.io/IDZLB8" height="100%" width="100%" frameborder="0"></iframe>
				</div>
			</div>
		</div>	
		
		<div class="row mt-5 text-monospace">
			<div class="col-md-2">
				<a name="entrainement"></a>
				<div class="font-weight-bold text-success">ENTRAÎNEMENTS / DEVOIRS</div>
				<div class="font-weight-bold">Diaporama</div>
			</div>
			<div class="col-md-10">

				<div id="carouselCaptions" class="carousel slide" data-interval="false">
					<div class="carousel-inner">
						<div class="carousel-item active" style="margin-top:100px;">
							<img src="{{ asset('img/entrainement/entrainement-00.png') }}" class="d-block w-100" alt="E1">
							<div class="carousel-caption d-none d-md-block text-center text-white small">
								<span class="bg-dark p-1 pl-2 pr-2">console enseignant</span>
							</div>
						</div>
						<div class="carousel-item" style="margin-top:100px;">
							<img src="{{ asset('img/entrainement/entrainement-01.png') }}" class="d-block w-100" alt="E1">
							<div class="carousel-caption d-none d-md-block text-center text-white small">
								<span class="bg-dark p-1 pl-2 pr-2">création d'un nouvel entraînement/devoir</span>
							</div>
						</div>
						<div class="carousel-item" style="margin-top:100px;">
							<img src="{{ asset('img/entrainement/entrainement-02.png') }}" class="d-block w-100" alt="E1">
							<div class="carousel-caption d-none d-md-block text-center text-white small">
								<span class="bg-dark p-1 pl-2 pr-2">correction du travail d'un élève</span>
							</div>
						</div>
						<div class="carousel-item" style="margin-top:100px;">
							<img src="{{ asset('img/entrainement/entrainement-03.png') }}" class="d-block w-100" alt="E1">
							<div class="carousel-caption d-none d-md-block text-center text-white small">
								<span class="bg-dark p-1 pl-2 pr-2">lancement de l'entraînement/devoir côté élève</span>
							</div>
						</div>
						<div class="carousel-item" style="margin-top:100px;">
							<img src="{{ asset('img/entrainement/entrainement-04.png') }}" class="d-block w-100" alt="E1">
							<div class="carousel-caption d-none d-md-block text-center text-white small">
								<span class="bg-dark p-1 pl-2 pr-2">page de l'entraînement/devoir</span>
							</div>
						</div>	
						<div class="carousel-item" style="margin-top:100px;">
							<img src="{{ asset('img/entrainement/entrainement-05.png') }}" class="d-block w-100" alt="E1">
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


</body>
</html>
