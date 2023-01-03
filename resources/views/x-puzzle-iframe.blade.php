<?php
if (App\Models\Code::where('jeton', $jeton)->first() OR  App\Models\Site_puzzle::where('jeton', $jeton)->first()) {
	if (App\Models\Code::where('jeton', $jeton)->first()) $code = App\Models\Code::where('jeton', $jeton)->first();
	if (App\Models\Site_puzzle::where('jeton', $jeton)->first()) $code = App\Models\Site_puzzle::where('jeton', $jeton)->first();
} else {
	echo '<div class="text-center text-muted mt-3" style="font-size:200px;"><i class="fas fa-skull-crossbones"></i></div>';
	echo '<div class="text-center" style="font-size:40px;"><a href="/"><i class="fas fa-arrow-left"></i></a></div>';
	echo '</body></html>';
	exit;
}

// nettoyage du code -> code avec les bonnes reponses
$code_correct = preg_replace_callback("/\[\?(.*?)\?\]/m", function($matches){
    if (strpos($matches[1], '?')){
        $items_array = explode('?', $matches[1]);
        return $items_array[0];
    } else {
        return $matches[1];
    }
}, $code->code);

// creation du fichier jupyter
$source = addcslashes($code_correct, '\"');
$source = preg_replace("/\r?\n|\r/", '\n', $source);
$ipynb = '{"cells":[{"metadata":{"trusted":true},"cell_type":"code","source":"' . $source . '","execution_count":null,"outputs":[]}],"metadata":{"celltoolbar":"Format de la Cellule Texte Brut","colab":{"name":"python4tp.ipynb","provenance":[],"toc_visible":true},"kernelspec":{"display_name":"Python 3","language":"python","name":"python3"},"toc":{"base_numbering":"0","nav_menu":{"height":"369px","width":"618.333px"},"number_sections":true,"sideBar":true,"skip_h1_title":false,"title_cell":"Table des Matières","title_sidebar":"Sommaire","toc_cell":true,"toc_position":{"height":"calc(100% - 180px)","left":"10px","top":"150px","width":"165px"},"toc_section_display":true,"toc_window_display":true}},"nbformat":4,"nbformat_minor":2}';
file_put_contents('code/' . $code->uuid . '.ipynb', $ipynb);

// langue
app()->setLocale($code->lang)
?>

@include('inc-top')
<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
	@php
        $description = __('Générateur et gestionnaire de puzzles de Parsons') . ' | Puzzle - ' . strtoupper($jeton);
        $description_og = '| Puzzle - ' . strtoupper($jeton);
    @endphp
	@include('inc-meta-puzzle')
    <title>{{ config('app.name') }} | Puzzle - {{ $jeton }}</title>
</head>

<body oncontextmenu="return false" onselectstart="return false" ondragstart="return false">

	<br />

	@if ($code->with_chrono == 1 OR $code->with_score == 1)
	<table align="center" cellpadding="2" style="text-align:center;margin-bottom:20px;color:#bdc3c7;">
		<tr>
			@if ($code->with_chrono == 1)
			<td><i class="fas fa-clock"></i></td>
			@endif
			@if ($code->with_score == 1)
			<td><i class="fas fa-check"></i></td>
			<td><i class="fas fa-graduation-cap"></i></td>
			@endif
		</tr>
		<tr>
			@if ($code->with_chrono == 1)
			<td><span id="chrono" class="dashboard">00:00</span></td>
			@endif
			@if ($code->with_score == 1)
			<td><span id="nb_tentatives" class="dashboard">0</span></td>
			<td><span id="points" class="dashboard">0</span></td>
			@endif
		</tr>
	</table>
	@endif

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
                <button id="feedbackLink" type="button" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-trigger="hover" title="vérifier" style="display:inline"><i class="fas fa-check"></i></button>

				<!-- bouton copier -->
                <span id="copyLink" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="éditer la cellule pour copier le code" style="display:none"></span>

				<!-- bouton basthon -->
                <span id="basthon" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="exécuter ce code avec Basthon" style="display:none"></span>

				<!-- bouton kaggle -->
                <span id="kaggle" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="exécuter ce code avec Kaggle" style="display:none"></span>

            </div>
        </div>

        @if ($code->fakecode !== NULL OR $code->with_dragdrop)
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
