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

	<div class="container mt-3 mb-5">

		<div class="row">

            <div class="col-md-2">
                <div class="text-right mb-3">
				    <a class="btn btn-light btn-sm" href="/" role="button"><i class="fas fa-home"></i></a>
                </div>

                <div class="mb-1 text-center"><a class="btn btn-light btn-sm d-block" href="{{route('console-puzzles')}}" role="button">{{__('PUZZLES')}}</a></div>
                <div class="mb-1 text-center"><a class="btn btn-light btn-sm d-block" href="{{route('console-defis')}}" role="button">{{__('DÉFIS')}}</a></div>
                <div class="mb-1 text-center"><a class="btn btn-light btn-sm d-block" href="{{route('console-sujets')}}" role="button">{{__('SUJETS')}}</a></div>
                <div class="mb-1 text-center"><a class="btn btn-light btn-sm d-block" href="{{route('console-devoirs')}}" role="button">{{__('DEVOIRS')}}</a></div>
                <div class="mb-1 text-center"><a class="btn btn-light btn-sm d-block" href="{{route('console-programmes')}}" role="button">{{__('PROGRAMMES')}}</a></div>
                <div class="mb-3 text-center"><a class="btn btn-light btn-sm d-block" href="{{route('console-classes')}}" role="button">{{__('CLASSES')}}</a></div>

                <div class="mt-5 text-muted text-monospace small" style="text-align:justify">
                    <i class="fas fa-comment-alt" style="float:left;margin:3px 8px 5px 0px;"></i>
                    <a href="https://github.com/codepuzzle-io/www.codepuzzle.io/discussions" target="_blank"> {{__('discussions')}} & {{__('annonces')}}</a>
                </div>

                <div class="mt-1 text-muted text-monospace small" style="text-align:justify">
                    <i class="fas fa-bug" style="float:left;margin:3px 8px 5px 0px;"></i>
                    <a href="https://github.com/codepuzzle-io/www.codepuzzle.io/issues/new/choose" target="_blank">{{__('signalement de bogues')}} & {{__('questions techniques')}}</a>
                </div>

                <div class="mt-1 text-muted text-monospace small">
                    <i class="fa fa-envelope" style="float:left;margin:3px 8px 5px 0px;"></i>
                    contact@codepuzzle.io
                </div>

            </div>

			<div class="col-md-10 text-monospace">

                <div class="small text-muted mb-1">
                    Activités
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="text-center"><a class="btn btn-success d-block" href="{{route('puzzle-creer-get')}}" role="button">nouveau<br />puzzle</a></div>
                   </div>
                    <div class="col-md-2">
                        <div class="text-center"><a class="btn btn-success d-block" href="{{route('defi-creer-get')}}" role="button">nouveau<br />défi</a></div>
                    </div>
                    <div class="col-md-2">
                        <div class="text-center"><a class="btn btn-success d-block" href="{{route('sujet-creer-get')}}" role="button">nouveau<br />sujet</a></div>
                    </div>    
                </div>  

                <div class="text-monospace small text-muted mt-4 mb-1">      
                    Pour la classe
                </div>  
                <div class="row">
                    <div class="col-md-2">
                        <div class="text-center"><a class="btn btn-dark d-block" href="{{route('programme-creer-get')}}" role="button">nouveau<br />programme</a></div>
                    </div>
  

                    <div class="col-md-2">
                        <div class="text-center"><a class="btn btn-dark d-block" href="{{route('classe-creer-get')}}" role="button">nouvelle<br />classe</a></div>      
                    </div>          
                </div>  

            </div>

        </div>
	</div><!-- /container -->

	@include('inc-bottom-js')

</body>
</html>
