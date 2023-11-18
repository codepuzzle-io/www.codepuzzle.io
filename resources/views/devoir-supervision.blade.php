<?php
$devoir = App\Models\Devoir::where('jeton_secret', $jeton_secret)->first();
if (!$devoir){
    echo "<pre>Cet entraînement n'existe pas</pre>";
    exit();
}
$devoir_eleves = App\Models\Devoir_eleve::where('jeton_devoir', $devoir->jeton)->orderBy('pseudo')->get();
$is_locked = App\Models\Devoir_eleve::where('locked', 1)->exists();
?>
<!doctype html>
<html lang="fr">
<head>
    @include('inc-meta')

    <!-- EnlighterJS !-->
    <link rel="stylesheet" href="{{ asset('lib/highlight/atom-one-dark.min.css') }}" />

    <title>ENTRAÎNEMENT - SUPERVISION</title>
    <style>
        .hljs {
            padding:0px;
            font-size:10px;
        }
        .card {
            border-radius: 0px;
            border: 0px solid #eef0f2 !important;
            padding: 0px;
            background-color: transparent !important;
        }
        .col-xl, .col-xl-auto, .col-xl-12, .col-xl-11, .col-xl-10, .col-xl-9, .col-xl-8, .col-xl-7, .col-xl-6, .col-xl-5, .col-xl-4, .col-xl-3, .col-xl-2, .col-xl-1, .col-lg, .col-lg-auto, .col-lg-12, .col-lg-11, .col-lg-10, .col-lg-9, .col-lg-8, .col-lg-7, .col-lg-6, .col-lg-5, .col-lg-4, .col-lg-3, .col-lg-2, .col-lg-1, .col-md, .col-md-auto, .col-md-12, .col-md-11, .col-md-10, .col-md-9, .col-md-8, .col-md-7, .col-md-6, .col-md-5, .col-md-4, .col-md-3, .col-md-2, .col-md-1, .col-sm, .col-sm-auto, .col-sm-12, .col-sm-11, .col-sm-10, .col-sm-9, .col-sm-8, .col-sm-7, .col-sm-6, .col-sm-5, .col-sm-4, .col-sm-3, .col-sm-2, .col-sm-1, .col, .col-auto, .col-12, .col-11, .col-10, .col-9, .col-8, .col-7, .col-6, .col-5, .col-4, .col-3, .col-2, .col-1 {
            position: relative;
            width: 100%;
            padding-right: 5px;
            padding-left: 5px;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -5px;
            margin-left: -5px;
        }
        </style>
</head>
<body>

	<div class="container-fluid mb-5">
        <div class="text-monospace small text-muted text-center pt-1">page rafraîchie toutes les 10 secondes</div>
        <div class="row pt-3">
            <div class="col-md-2 text-left">
				<a class="btn btn-light btn-sm" href="/devoir-console/{{$jeton_secret}}" role="button"><i class="fas fa-arrow-left"></i></a>
			</div>
            <div class="col-md-8">
                @if ($is_locked)
                    <div class="bg-danger h-100 text-white p-2 text-center text-monospace small" style="border-radius:3px;">
                        au moins un devoir de verrouillé | un mot secret pour déverrouiller: <b>{{$devoir->mot_secret}}</b>
                    </div>
                @else
                    <div class="bg-success h-100 text-white p-2 text-center text-monospace" style="border-radius:3px;">
                        tous les devoirs sont actifs
                    </div>
                @endif
            </div>
        </div>

		<div class="row pt-2">

			<div class="col-md-12">

                <div id="ecran" class="row mt-3 mb-5">
                    <div class="col-md-12">

                        <div class="row row-cols-1 row-cols-md-4">
                        @foreach($devoir_eleves as $devoir_eleve)

                        <div class="col mb-4">
                            <div class="card p-0 h-100">
                                <div class="card-body p-0">
                                    <div class="text-monospace small">{{substr(Crypt::encrypt($devoir_eleve->pseudo), 10)}}</div>
                                    @if($devoir_eleve->locked == 0)
                                        <!-- CODE ELEVE --> 
                                        <pre id="code_eleve-{{$loop->iteration}}" style="min-height:80px;height:100%;"><code style="height:100%;border-radius:3px;" class="language-python">{{$devoir_eleve->code_eleve}}</code></pre>
                                        <!-- /CODE ELEVE --> 
                                    @else
                                        <div class="h-100 bg-danger text-white text-center" style="min-height:80px;border-radius:3px;display:flex;justify-content:center;align-items:center;"><i class="fa-solid fa-lock" style="opacity:0.5"></i></div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @endforeach
                        </div>

                    </div>
                </div>

            </div>
        </div>
	</div><!-- /container -->

    @include('inc-bottom-js')

    <script>
        setTimeout(function(){
            window.location.reload(1);
        }, 10000);
    </script>    

    <script type="text/javascript" src="{{ asset('lib/highlight/highlight.min.js') }}"></script>
    <script>
        hljs.highlightAll();
    </script>

    <script>
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		  return new bootstrap.Tooltip(tooltipTriggerEl)
		})
	</script>

</body>
</html>
