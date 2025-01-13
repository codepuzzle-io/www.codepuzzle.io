<?php
$devoir = App\Models\Devoir::where('jeton', Session::get('jeton_devoir'))->first();
$sujet = App\Models\Sujet::find($devoir->sujet_id);
$sujet_json = json_decode($sujet->sujet);
$copie = App\Models\Copie::where('jeton_copie', Session::get('jeton_copie'))->first();
$page_devoir = true;
?>
@include('inc-top')
<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>

	<script>
		// FOCUS
        // Événement lorsque l'utilisateur quitte la page
		window.addEventListener('blur', function() {
			window.location.replace("/devoir");
		});

        // FULLSCREEN
        document.addEventListener('fullscreenchange', exitFullscreenHandler);
        document.addEventListener('webkitfullscreenchange', exitFullscreenHandler);
        document.addEventListener('mozfullscreenchange', exitFullscreenHandler);
        document.addEventListener('MSFullscreenChange', exitFullscreenHandler);

        function exitFullscreenHandler() {
            if (document.fullscreenElement === null || // Standard de la W3C
                document.webkitFullscreenElement === null || // Anciens navigateurs Webkit
                document.mozFullScreenElement === null || // Anciens navigateurs Firefox
                document.msFullscreenElement === null) { // Anciens navigateurs Internet Explorer/Edge
                window.location.replace("/devoir");
            }
        }
    </script>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Favicon -->
	<link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<!-- Font Awesome -->
	<link href="{{ asset('lib/fontawesome/css/all.min.css') }}" rel="stylesheet">

	<!-- Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200&display=swap" rel="stylesheet">

	<!-- Styles -->
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('css/custom.css') }}" rel="stylesheet">

	<meta http-equiv="Cache-Control" content="no-cache, max-age=0, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />

    <script src="https://cdn.jsdelivr.net/pyodide/v0.24.1/full/pyodide.js"></script>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/default.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
    <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>

    <link href="{{ asset('css/highlight.css') }}" rel="stylesheet">
	<link href="{{ asset('css/easymde.css') }}" rel="stylesheet">
	<link href="{{ asset('css/easymde-custom.css') }}" rel="stylesheet">
 
    <style>
        html,body {
          height: 100%;
        }
        .grid {
            display: grid;
            grid-template-columns: 1fr 10px 1fr;
            height:100%;
			border:solid 0px silver;
			margin:0px;
			border-radius:0px;
        }
        .gutter-col {
            grid-row: 1/-1;
            cursor: col-resize;
            background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAeCAYAAADkftS9AAAAIklEQVQoU2M4c+bMfxAGAgYYmwGrIIiDjrELjpo5aiZeMwF+yNnOs5KSvgAAAABJRU5ErkJggg==');
            background-color: rgb(229, 231, 235);
            background-repeat: no-repeat;
            background-position: 50%;
        }
        .gutter-col-1 {
            grid-column: 2;
        }
        .video {
          aspect-ratio: 16 / 9;
          width: 100%;
        }
    </style>

    <title>ENTRAÎNEMENT / DEVOIR</title>

</head>

<body class="no-mathjax" oncontextmenu="return false" onselectstart="return false" ondragstart="return false">

	<!-- Écran de démarrage -->
    <div id="demarrer" class="demarrer" style="z-index:10000">
		<div id="commencer">
			<i class="fas fa-exclamation-triangle text-danger"></i>
			<br />
			<div class="text-monospace text-danger text-left" style="width:320px;margin:0px auto 0px auto;">
			&#8226; ne pas quitter le mode plein écran<br />
			&#8226; ne pas revenir en arrière<br />
			&#8226; ne pas quitter la page<br />
			&#8226; ne pas recharger la page<br />
			&#8226; ne pas cliquer en-dehors de la page
			</div>
			<br/>
			<button onclick="commencer()" type="button" class="btn btn-primary btn-lg text-monospace" style="width:80px;font-size:100%;"><i class="fas fa-check"></i></button>
		</div>
    </div>
    <!-- /Écran de démarrage -->
	
	<div id="devoir_entete">

        <div class="bg-danger text-white p-2 text-monospace text-center">
            ne pas quitter cette page - ne pas recharger cette page - ne pas cliquer en-dehors de cette page - ne pas quitter le mode plein écran
        </div>
    
        <div class="container-fluid" style="background-color:#e5e7eb">
            <div class="row">
                <div class="col-4 p-3">
                    <img src="{{ asset('img/codepuzzle.png') }}" height="16" alt="CODE PUZZLE" />
                </div>
                <div class="col-4 p-2 text-center">
                    <button class="btn btn-light" type="button" disabled><i class="fas fa-clock"></i>&nbsp;&nbsp;<span id="chrono">00:00</span></button>
                </div>
                <div class="col-4 p-2 text-right text-monospace">

                    <!-- rendre -->
                    <div class="text-right pr-4" style="float:right;">
                        <button id="rendre_button" onclick="showConfirm('rendre_button', 'rendre_confirm')" class="btn btn-success" type="button">rendre</button>
                        <span id="rendre_confirm" style="display:none">
                            <button id="rendre" onclick="rendre()" type="button" class='btn btn-danger text-white mr-1' role='button'>{{__('confirmer')}}</button>
                            <button id="rendre_cancel" onclick="hideConfirm('rendre_button', 'rendre_confirm')" class="btn btn-dark" type="button"><i class="fa-solid fa-xmark"></i></button>
                            <span class="pl-2 pr-2"><i class="fas fa-chevron-left"></i></span>
                        </span>
                    </div>

                </div>
            </div>
        </div>  

    </div>

    <div id="grid" class="grid">
        <div style="overflow-y:hidden;position:relative">
            <div id="gauche" style="width:100%;height:100%;overflow-y:scroll;direction: rtl;">
                <div class="p-3" style="direction: ltr;">

                    @if ($devoir->consignes_eleve != '')
                        <!-- CONSIGNES -->
                        <div class="pt-2 pb-3">{{$devoir->consignes_eleve}}</div>
                        <!-- /CONSIGNES -->
                    @endif

                    <!-- SUJET -->
                    @include('sujets/inc-sujet-afficher')
                    <!-- SUJET -->

                </div>
            </div>
        </div>

        <div id="poignee" class="gutter-col gutter-col-1"></div>

        <div style="overflow-y:hidden;position:relative">
            <div id="grid_droite" style="width:100%;height:100%;overflow-y:scroll;">

                <!-- COPIE -->
                @include('copies/inc-copie-afficher')
                <!-- /COPIE -->                
    
            </div>
        </div>
    </div>


    <script>
        function commencer() {
            if (document.documentElement.requestFullscreen) {
                document.documentElement.requestFullscreen(); // Méthode pour les navigateurs récents
            } else if (document.documentElement.mozRequestFullScreen) {
                document.documentElement.mozRequestFullScreen(); // Méthode pour Firefox
            } else if (document.documentElement.webkitRequestFullscreen) {
                document.documentElement.webkitRequestFullscreen(); // Méthode pour Chrome, Safari et Opera
            } else if (document.documentElement.msRequestFullscreen) {
                document.documentElement.msRequestFullscreen(); // Méthode pour Internet Explorer/Edge
            }
            document.getElementById('demarrer').remove();
        }
    </script>

    <script>
        // CHRONO
        var count = {{ $copie->chrono }};
        var intervalRef = null;
        var chrono = {
            start: function () {
                let start = new Date();
                intervalRef = setInterval(_ => {
                    let current = new Date();
                    count = {{ $copie->chrono }} + +current - +start;
                    let s = Math.floor((count /  1000)) % 60;
                    let m = Math.floor((count / 60000)) % 60;
                    if (s < 10) {
                        s_display = '0' + s;
                    } else {
                        s_display = s;
                    }
                    if (m < 10) {
                        m_display = '0' + m;
                    } else {
                        m_display = m;
                    }
                    $('#chrono').text(m_display + ":" + s_display);
                }, 1000);
            },
            stop: function () {
                clearInterval(intervalRef);
                delete intervalRef;
            },
        }
        chrono.start();	
    </script>

    @include('inc-bottom-js')
    @include('sujets/inc-sujet-afficher-js')
    @include('copies/inc-copie-afficher-js')

    <!-- GRID -->
    <script src="{{ asset('lib/split-grid/split-grid.js') }}" type="text/javascript" charset="utf-8"></script>
    <script>
	    Split({
	        minSize: 200,
	        columnGutters: [{
	            track: 1,
	            element: document.querySelector('.gutter-col-1'),
	        }],
	    })
    </script>
    <script>
        function hauteur_grid() {
            var hauteur_devoir_entete = document.getElementById('devoir_entete').offsetHeight;
            var hauteur_page = document.body.scrollHeight;
            document.getElementById('grid').style.height = (hauteur_page - hauteur_devoir_entete) + 'px';;
        }
        window.onload = hauteur_grid;
        window.onresize = hauteur_grid;
    </script>
    <!-- GRID -->

</body>
</html>