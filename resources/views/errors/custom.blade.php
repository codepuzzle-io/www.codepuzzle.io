@include('inc-top')
<!doctype html>
<html lang="fr">
<head>
	@include('inc-meta')
	<title>oups</title>
</head>
    <body>
        <div class="container">
            <div class="row mt-5">
                <div class="col-md-12 text-center text-muted mt-3"><i class="fas fa-skull-crossbones" style="font-size:200px;"></i></div>
                <div class="col-md-12 text-center text-muted mt-3 text-uppercase">@yield('code') | @yield('message')</div>
            </div>
            <div class="row mt-5">
                <div class="col text-center text-monospace small" style="color:silver">vous pouvez signaler ce problème <a href="https://github.com/codepuzzle-io/www.codepuzzle.io/issues/new/choose" target="_blank">ici</a> ou écrire à : contact@codepuzzle.io</div>
            </div>
            <div class="row mt-4">
                <div class="col text-center small" style="opacity:0.6"><a class="btn btn-outline-secondary btn-sm" href="/" role="button">retour sur le page d'accueil</a></div>
            </div>
        </div>
    </body>
</html>
