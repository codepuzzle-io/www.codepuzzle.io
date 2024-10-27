<?php
if (isset($jeton)) {
	$sujet = App\Models\Sujet::where('jeton', $jeton)->first();
	if (!$sujet) {
		echo "<pre>Ce sujet n'existe pas.</pre>";
		exit();
	} else {
		$sujet_json = json_decode($sujet->sujet);
        $page_sujet_copie = true;        
	}
}
?>
<!doctype html>
<html lang="fr">
<head>
	@include('inc-meta')
    <title>SUJET</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/default.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
    <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
    
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

</head>
<body>
	<div class="grid">
        <div style="overflow-y:hidden;position:relative">
            <div id="gauche" style="width:100%;height:100%;overflow-y:scroll;direction: rtl;">
                <div class="pl-3 pr-3" style="direction: ltr;">

                    <h1 id="sujet_entete" class="text-center m-0 pb-3 pt-2">
                        <a href="{{ url('/') }}"><img src="{{ asset('img/codepuzzle.png') }}" height="20" alt="CODE PUZZLE" /></a> 
                    </h1>

                    <!-- SUJET -->
                    @include('sujets/inc-sujet-afficher')
                    <!-- SUJET -->

                </div>
            </div>
        </div>

        <div id="poignee" class="gutter-col gutter-col-1"></div>

        <div style="overflow-y:hidden;position:relative;">
			<div id="droite" style="width:100%;height:100%;overflow-y:scroll;">

                <!-- COPIE -->
                @include('copies/inc-copie-afficher')
                <!-- /COPIE -->
      
			</div>
		</div>
    </div>

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
    <!-- /GRID -->

</body>
</html>