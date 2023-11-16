<?php
$devoir = App\Models\Devoir::where('jeton_secret', $jeton_secret)->first();
if (!$devoir){
    echo "<pre>Cet entraînement n'existe pas</pre>";
    exit();
}
$devoir_eleves = App\Models\Devoir_eleve::where('jeton_devoir', $devoir->jeton)->orderBy('pseudo')->get();
?>
<!doctype html>
<html lang="fr">
<head>
    @include('inc-meta')

    <title>ENTRAÎNEMENT - SUPERVISION</title>
</head>
<body>

	<div class="container-fluid mb-5">

		<div class="row pt-3">

			<div class="col-md-12">

                <div id="ecran" class="row mt-3 mb-5">
                    <div class="col-md-12">
                        @foreach($devoir_eleves as $devoir_eleve)

                            <!-- CODE ELEVE --> 
                            <div style="float:left;width:20%;padding:0px 2px 0px 2px">
                            <div class="text-monospace small">{{$devoir_eleve->pseudo}}</div>
                            <div id="editor_code_eleve_devoir-{{$loop->iteration}}" style="border-radius:5px;">{{$devoir_eleve->code_eleve}}</div>
                            </div>
                            <!-- /CODE ELEVE --> 

                        @endforeach
                    </div>
                </div>

            </div>
        </div>
	</div><!-- /container -->

    @include('inc-bottom-js')

    <script src="{{ asset('js/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>
	<script>
        var editor_code_eleve_devoir = []
        for (var i = 1; i <= {{$devoir_eleves->count() }}; i++) {

            editor_code_eleve_devoir[i] = ace.edit('editor_code_eleve_devoir-' + i, {
                theme: "ace/theme/puzzle_code",
                mode: "ace/mode/python",
                minLines: 8,
                maxLines: 8,
                fontSize: 11,
                wrap: true,
                useWorker: false,
                highlightActiveLine: false,
                highlightGutterLine: false,
                showPrintMargin: false,
                displayIndentGuides: true,
                showLineNumbers: true,
                showGutter: true,
                showFoldWidgets: false,
                useSoftTabs: true,
                navigateWithinSoftTabs: false,
                tabSize: 4,
                readOnly: true
            });
            editor_code_eleve_devoir[i].container.style.lineHeight = 1.4;
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
