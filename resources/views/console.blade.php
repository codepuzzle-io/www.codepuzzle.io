<!doctype html>
<html lang="fr">
<head>
    @include('inc-meta')
    <title>{{ config('app.name') }} | {{ ucfirst(__('console')) }}</title>
</head>
<body>
    @php
		$lang_switch = '<a href="/console/lang/fr" class="kbd mr-1">fr</a><a href="/console/lang/en" class="kbd">en</a>';
	@endphp
    @include('inc-nav-console')

	<div class="container mt-4 mb-5">

		<div class="row pt-3">

            <div class="col-md-2">
                <div class="text-right mb-3">
				    <a class="btn btn-light btn-sm" href="/" role="button"><i class="fas fa-home"></i></a>
                </div>

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

			<div class="col-md-10">
                <div class="card-deck text-monospace">

                    <div class="card ml-1 mr-1 p-3">
                        <div class="card-body p-0">
                            <div class="mb-3 text-center"><a class="btn btn-light d-block" href="{{route('console-puzzles')}}" role="button"><i class="fas fa-puzzle-piece mr-2"></i>{{__('PUZZLES')}}</a></div>
                            <div class="text-center"><a class="btn btn-success btn-sm d-block" href="{{route('puzzle-creer-get')}}" role="button">{{__('nouveau puzzle')}}</a></div>
                            <div class="mt-3 small text-muted">
                                Puzzles de Parsons en mode "réorganiser" ou "glisser-déposer". Avec ou sans code à compléter. Pour apprendre Python sans écrire de code.
                            </div>
                        </div>
                    </div>

				    <div class="card ml-1 mr-1 p-3">
					    <div class="card-body p-0">
                            <div class="mb-3 text-center"><a class="btn btn-light d-block" href="{{route('console-defis')}}" role="button"><i class="fas fa-tasks mr-2"></i>{{__('DÉFIS')}}</a></div>
                            <div class="text-center"><a class="btn btn-success btn-sm d-block" href="{{route('defi-creer-get')}}" role="button">{{__('nouveau défi')}}</a></div>
                            <div class="mt-3 small text-muted">
                                Défis Python avec jeux de tests à valider. Écrire ou compléter un programme Python en suivant les consignes fournies et tester le code en ligne.
                            </div>
                        </div>
                    </div>

                    <div class="card ml-1 mr-1 p-3">
					    <div class="card-body p-0">
                            <div class="mb-3 text-center"><a class="btn btn-light d-block" href="{{route('console-programmes')}}" role="button"><i class="fas fa-code mr-2"></i>{{__('PROGRAMMES')}}</a></div>
                            <div class="text-center"><a class="btn btn-success btn-sm d-block" href="{{route('programme-creer-get')}}" role="button">{{__('nouveau programme')}}</a></div>
                            <div class="mt-3 small text-muted">
                                Programmes à partager dans un environnement Python interactif. Peuvent être modifiés et exécutés. Démonstrations au tableau, entraînements, exemples...
                            </div>
                        </div>
                    </div>

                    <div class="card ml-1 mr-1 p-3">
					    <div class="card-body p-0">
                            <div class="mb-3 text-center"><a class="btn btn-light d-block" href="{{route('console-devoirs')}}" role="button"><i class="fas fa-graduation-cap mr-2"></i>{{__('DEVOIRS')}}</a></div>
                            <div class="text-center"><a class="btn btn-success btn-sm d-block" href="{{route('devoir-creer-get')}}" role="button">{{__('nouveau devoir')}}</a></div>
                            <div class="mt-3 small text-muted">
                                Activités en classe de type examen dans un environnement anti-triche. Récupération automatique des travaux avec exécution du code et correction en ligne.
                            </div>
                        </div>    
                    </div>    

                    <div class="card ml-1 mr-1 p-3">
					    <div class="card-body p-0">
                            <div class="mb-3 text-center"><a class="btn btn-dark d-block" href="{{route('console-classes')}}" role="button"><i class="fas fa-chalkboard mr-2"></i>{{__('CLASSES')}}</a></div>
                            <div class="text-center"><a class="btn btn-success btn-sm d-block" href="{{route('classe-creer-get')}}" role="button">{{__('nouvelle classe')}}</a></div>
                            <div class="mt-3 small text-muted">
                                Créer une classe pour proposer des activités (puzzles, défis...) aux élèves et suivre l'avancement de leur travail.
                            </div>
                        </div>          
                    </div>          
              
                </div>               
            </div>

        </div>
	</div><!-- /container -->

	@include('inc-bottom-js')

</body>
</html>
