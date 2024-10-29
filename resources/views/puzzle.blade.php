<?php
// recuperation du puzzle en fonction du jeton
$puzzle = App\Models\Puzzle::where('jeton', $jeton)->first();


// nettoyage du code -> code avec les bonnes reponses
$code_correct = preg_replace_callback("/\[\?(.*?)\?\]/m", function($matches){
    if (strpos($matches[1], '?')){
        $items_array = explode('?', $matches[1]);
        return $items_array[0];
    } else {
        return $matches[1];
    }
}, $puzzle->code);

// creation du fichier jupyter
$source = addcslashes($code_correct, '\"');
$source = preg_replace("/\r?\n|\r/", '\n', $source);
$ipynb = '{"cells":[{"metadata":{"trusted":true},"cell_type":"code","source":"' . $source . '","execution_count":null,"outputs":[]}],"metadata":{"celltoolbar":"Format de la Cellule Texte Brut","colab":{"name":"python4tp.ipynb","provenance":[],"toc_visible":true},"kernelspec":{"display_name":"Python 3","language":"python","name":"python3"},"toc":{"base_numbering":"0","nav_menu":{"height":"369px","width":"618.333px"},"number_sections":true,"sideBar":true,"skip_h1_title":false,"title_cell":"Table des Matières","title_sidebar":"Sommaire","toc_cell":true,"toc_position":{"height":"calc(100% - 180px)","left":"10px","top":"150px","width":"165px"},"toc_section_display":true,"toc_window_display":true}},"nbformat":4,"nbformat_minor":2}';
file_put_contents('code/' . $puzzle->uuid . '.ipynb', $ipynb);

// langue
app()->setLocale($puzzle->lang)
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

<?php
	// puzzle avec jeton eleve
	if(isset($jeton_eleve)) {
		$eleve = App\Models\Classes_eleve::where('jeton_eleve', $jeton_eleve)->first();
		if (!$eleve) {
			$segments = explode('/', $_SERVER['REQUEST_URI']);
			?>
			<div class="container font-monospace">
				<div class="row">
					<div class="col-md-6 offset-md-3 text-center mt-5 mb-4">
						CODE INDIVIDUEL
						<p class="small text-muted">Votre code individuel vous a été fourni par votre enseignant. Si vous n'avez pas de code individuel, cliquez sur le bouton "continuer sans code".</p>
					</div>
				</div>
				<div class="row">
            		<div class="col-md-5 text-end">
						<a class="btn btn-primary btn-sm ps-3 pe-3" href="/{{$segments[1]}}/" role="button">continuer sans code</a>
					</div>
					<div class="col-md-2 text-center text-muted">
						<i class="fas fa-ellipsis-v"></i>
					</div>
					<div class="col-md-5">
						<form class="row g-2">
							<div class="col-auto">
								<input id="code" type="text" class="form-control form-control-sm" placeholder="code" />
							</div>
                            <div class="col-auto">
							<a class="btn btn-primary btn-sm" href="#" role="button" onclick="
								if (document.getElementById('code').value){
									code = document.getElementById('code').value;
								} else {
									code = '@';
								}
								window.location.href = '/{{$segments[1]}}/'+code;
							"><i class="fas fa-check"></i></a>
                            </div>
						</form>
					</div>
				</div>
			</div>
			<?php
			exit();
		}
	}
	?>

    <div class="container-fluid">

        @if(!$iframe)
        <h1 class="mt-2 mb-5 text-center"><a class="navbar-brand m-1" href="{{ url('/') }}"><img src="{{ asset('img/code-puzzle.png') }}" width="100" alt="CODE PUZZLE" /></a></h1>
        @endif

		@if ($puzzle->with_chrono == 1 OR $puzzle->with_chrono == 1)
        <table align="center" cellpadding="2" style="text-align:center;margin-bottom:10px;color:#bdc3c7;">
            <tr>
                @if ($puzzle->with_chrono == 1)
                <td><i class="fas fa-clock"></i></td>
                @endif
                @if ($puzzle->with_nbverif == 1)
                <td><i class="fas fa-check"></i></td>
                @endif
            </tr>
            <tr>
                @if ($puzzle->with_chrono == 1)
                <td><span id="chrono" class="dashboard">00:00</span></td>
                @endif
                @if ($puzzle->with_nbverif == 1)
                <td><span id="nb_tentatives" class="dashboard">0</span></td>
                @endif
            </tr>
        </table>
		@endif

        <!-- annonce enregistrement reponse -->
		<div id="enregistrement_reponse" class="font-monospace small pt-4 pb-4 text-center"></div>

        @if ($puzzle->titre_eleve !== NULL OR $puzzle->consignes_eleve !== NULL)
        <div class="row" style="padding-top:10px;">
            <div class="col-md-12">
                <div class="frame">
                    @if ($puzzle->titre_eleve !== NULL)
                        <div class="font-monospace small mb-1">{{ $puzzle->titre_eleve }}</div>
                    @endif
                    @if ($puzzle->consignes_eleve !== NULL)
                        <div class="markdown_content font-monospace text-muted small consignes">{{$puzzle->consignes_eleve}}</div>
                    @endif
                </div>
            </div>
        </div><!-- row -->
        @endif

    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 text-center" style="position:relative;height:30px;">

				<!-- bouton reinitialiser -->
				<a id="reinitialiser" href="#" style="position:absolute;left:25px;top:10px;" class="text-muted" data-bs-toggle="tooltip" data-bs-placement="top"  data-bs-trigger="hover" title="{{__('réinitialiser')}}"><i class="fas fa-sync-alt"></i></a>

				<!-- bouton verifier -->
                <button id="feedbackLink" type="button" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="{{__('vérifier')}}" style="display:inline"><i class="fas fa-check"></i></button>

				<!-- bouton copier -->
                <span id="copyLink" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="{{__('éditer la cellule pour copier le code')}}" style="display:none"></span>

				@if($puzzle->lang == 'fr')
					<!-- bouton basthon -->
                	<span id="basthon" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="exécuter ce code avec Basthon" style="display:none"></span>
				@endif

				<!-- bouton kaggle -->
                <span id="kaggle" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="{{__('exécuter ce code avec Kaggle')}}" style="display:none"></span>

            </div>
        </div>
        @if ($puzzle->fakecode !== NULL OR $puzzle->with_dragdrop)
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
                <div class="col-md-12">
                    <div id="sortable" class="sortable-code"></div>
                    <div id="codesource" style="width:100%;margin:0px auto 0px auto;"><div id="editor_codesource" style="border-radius:5px;"></div></div>
                </div>
            </div>
        @endif
    </div><!-- container -->

    @include('inc-obfuscate')
	@include('markdown/inc-markdown-afficher-js')

	@if(isset($jeton_eleve))

		<script>
			function classe_activite_enregistrer() {
				var formData = new URLSearchParams();
				formData.append('jeton_activite', '{{ Crypt::encryptString('P'.$jeton) }}');
				formData.append('jeton_eleve', '{{ Crypt::encryptString($jeton_eleve) }}');				
				fetch('/classe-activite-enregistrer', {
					method: 'POST',
					mode: "cors",
					headers: {"Content-Type": "application/x-www-form-urlencoded", "X-CSRF-Token": "{{ csrf_token() }}"},
					body: formData
				})
				.then(response => {
					if (response.ok) {
						// le serveur a repondu avec succes
						document.getElementById('enregistrement_reponse').innerHTML = "<span class='text-success'>Réponse enregistrée. Retour à la <a href='/@/{{ $jeton_eleve }}'>console</a>.<span>";
					} else {
						document.getElementById('enregistrement_reponse').innerHTML = "<span class='text-danger'>Erreur lors de l'enregistrement - renvoyer la réponse</span>";
					}
				})
				.then(data => {
					console.log(data);
				})
				.catch(error => {
					// erreur lors de la requete
					document.getElementById('enregistrement_reponse').innerHTML = "<span class='text-danger'>Erreur lors de l'enregistrement - renvoyer la réponse</span>";
					console.error(error);
				});
				
			};
		</script>	
	@endif

    <script src="{{ asset('js/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>

    <script>
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		  return new bootstrap.Tooltip(tooltipTriggerEl)
		})
	</script>

</body>
</html>
