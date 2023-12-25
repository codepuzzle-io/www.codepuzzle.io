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

                <div class="row mb-3"> 
                    <div class="col-md-4 offset-md-2"> 
                        <div class="mb-3 text-center"><a class="btn btn-light" href="{{route('console-puzzles')}}" role="button" style="width:60%;"><i class="fas fa-folder-open mr-2"></i>{{__('PUZZLES')}}</a></div>
                        <div class="mb-4 text-center"><a class="btn btn-success btn-sm text-monospace" href="{{route('puzzle-creer-get')}}" role="button" style="width:60%;">{{__('nouveau puzzle')}}</a></div>
                    </div>
                    <div class="col-md-4"> 
                        <div class="mb-3 text-center"><a class="btn btn-light" href="{{route('console-defis')}}" role="button" style="width:60%;"><i class="fas fa-folder-open mr-2"></i>{{__('DÉFIS')}}</a></div>
                        <div class="mb-4 text-center"><a class="btn btn-success btn-sm text-monospace" href="{{route('defi-creer-get')}}" role="button" style="width:60%;">{{__('nouveau défi')}}</a></div>
                    </div>
                </div>               
            </div>

        </div>
	</div><!-- /container -->

	@include('inc-bottom-js')

</body>
</html>
