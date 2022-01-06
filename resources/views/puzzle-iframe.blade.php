@include('inc-top')
<!doctype html>
<html lang="fr">
<head>
	@include('inc-meta-puzzle')
    @include('inc-matomo')
    <title>Code Puzzle | Puzzle {{ $jeton }}</title>
</head>

<body oncontextmenu="return false" onselectstart="return false" ondragstart="return false">

    <?php
    $code = App\Models\Code::where('jeton', $jeton)->first();
	$source = addcslashes($code->code, '\"');
	$source = preg_replace("/\r?\n|\r/", '\n', $source);
	$ipynb = '{"cells":[{"metadata":{"trusted":true},"cell_type":"code","source":"' . $source . '","execution_count":null,"outputs":[]}],"metadata":{"celltoolbar":"Format de la Cellule Texte Brut","colab":{"name":"python4tp.ipynb","provenance":[],"toc_visible":true},"kernelspec":{"display_name":"Python 3","language":"python","name":"python3"},"toc":{"base_numbering":"0","nav_menu":{"height":"369px","width":"618.333px"},"number_sections":true,"sideBar":true,"skip_h1_title":false,"title_cell":"Table des Matières","title_sidebar":"Sommaire","toc_cell":true,"toc_position":{"height":"calc(100% - 180px)","left":"10px","top":"150px","width":"165px"},"toc_section_display":true,"toc_window_display":true}},"nbformat":4,"nbformat_minor":2}';
	file_put_contents('code/' . $code->uuid . '.ipynb', $ipynb);
    ?>

	<br />

    @if ($code->titre_eleve !== NULL OR $code->consignes_eleve !== NULL)
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="frame">
                @if ($code->titre_eleve !== NULL)
                    <div class="font-monospace small mb-1">{{ $code->titre_eleve }}</div>
                @endif
                @if ($code->consignes_eleve !== NULL)
                    <div class="font-monospace text-muted small consignes">
                        <?php
                        $Parsedown = new Parsedown();
                        echo $Parsedown->text($code->consignes_eleve);
                        ?>
                    </div>
                @endif
            </div>
        </div>
    </div><!-- row -->
    @endif

    <div class="container-fluid ps-4 pe-4">
        <div class="row">
            <div class="col-md-6 offset-md-3 text-center" style="position:relative;height:30px;">

				<!-- bouton reinitialiser -->
				<a id="reinitialiser" href="#" style="position:absolute;left:25px;top:10px;" class="text-muted" data-bs-toggle="tooltip" data-bs-placement="top"  data-bs-trigger="hover" title="réinitialiser"><i class="fas fa-sync-alt"></i></a>

				<!-- bouton verifier -->
                <button id="feedbackLink" type="button" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="vérifier" style="display:inline"><i class="fas fa-check"></i></button>

				<!-- bouton copier -->
                <span id="copyLink" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="éditer la cellule pour copier le code" style="display:none"></span>

				<!-- bouton basthon -->
                <span id="basthon" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="exécuter ce code avec Basthon" style="display:none"></span>

				<!-- bouton kaggle -->
                <span id="kaggle" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="exécuter ce code avec Kaggle" style="display:none"></span>

            </div>
        </div>

        @if ($code->fakecode !== NULL)
            <div class="row mt-3">
                <div class="col-md-6">
                    <div id="sortableTrash" class="sortable-code"></div>
                </div>
                <div class="col-md-6">
                    <div id="sortable" class="sortable-code"></div>
                    <div id="codesource" style="width:100%;margin:0px auto 0px auto;"><div id="editor_codesource" style="border-radius:5px;"></div></div>
                </div>
            </div>
        @else
            <div class="row mt-3">
                <div class="col-md-6 offset-md-3">
                    <div id="sortable" class="sortable-code"></div>
                    <div id="codesource" style="width:100%;margin:0px auto 0px auto;"><div id="editor_codesource" style="border-radius:5px;"></div></div>
                </div>
            </div>
        @endif
    </div><!-- container -->

    @include('inc-obfuscate')

    <script src="{{ asset('js/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>

    <script>
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		  return new bootstrap.Tooltip(tooltipTriggerEl)
		})
	</script>

</body>
</html>
