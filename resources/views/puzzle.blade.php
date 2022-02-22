<?php
ob_start("minifier");
function minifier($code) {
    $search = array(

        // Remove whitespaces after tags
        '/\>[^\S ]+/s',

        // Remove whitespaces before tags
        '/[^\S ]+\</s',

        // Remove multiple whitespace sequences
        '/(\s)+/s',

        // Removes comments
        '/<!--(.|\s)*?-->/'
    );
    $replace = array('>', '<', '\\1');
    $code = preg_replace($search, $replace, $code);
    return $code;
}


if (App\Models\Auth_puzzle::where('jeton', $jeton)->first() OR  App\Models\Guest_puzzle::where('jeton', $jeton)->first()) {
	if (App\Models\Auth_puzzle::where('jeton', $jeton)->first()) $puzzle = App\Models\Auth_puzzle::where('jeton', $jeton)->first();
	if (App\Models\Guest_puzzle::where('jeton', $jeton)->first()) $puzzle = App\Models\Guest_puzzle::where('jeton', $jeton)->first();
} else {
	echo '<div class="text-center text-muted mt-3" style="font-size:200px;"><i class="fas fa-skull-crossbones"></i></div>';
	echo '<div class="text-center" style="font-size:40px;"><a href="/"><i class="fas fa-arrow-left"></i></a></div>';
	echo '</body></html>';
	exit;
}

$puzzle_cleaned = preg_replace_callback("/\[\?(.*?)\?\]/m", function($matches){
    if (strpos($matches[1], '?')){
        $items_array = explode('?', $matches[1]);
        return $items_array[0];
    } else {
        return $matches[1];
    }
}, $puzzle->puzzle);

app()->setLocale($puzzle->lang)
?>
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

<body oncontextmenu="return false" onselectstart="return false" ondragstart="return false" style="padding-bottom:800px;">

    <div class="container">

		<h1 class="mt-2 mb-5 text-center"><a class="navbar-brand m-1" href="{{ url('/') }}"><img src="{{ asset('img/txtpuzzle.png') }}" width="150" alt="TXTPUZZLE" /></a></h1>

		@if ($puzzle->with_chrono == 1 OR $puzzle->with_score == 1)
		<table align="center" cellpadding="2" style="text-align:center;margin-bottom:20px;color:#bdc3c7;">
			<tr>
				@if ($puzzle->with_chrono == 1)
				<td><i class="fas fa-clock"></i></td>
				@endif
				@if ($puzzle->with_score == 1)
				<td><i class="fas fa-check"></i></td>
				<td><i class="fas fa-graduation-cap"></i></td>
				@endif
			</tr>
			<tr>
				@if ($puzzle->with_chrono == 1)
				<td><span id="chrono" class="dashboard">00:00</span></td>
				@endif
				@if ($puzzle->with_score == 1)
				<td><span id="nb_tentatives" class="dashboard">0</span></td>
				<td><span id="points" class="dashboard">0</span></td>
				@endif
			</tr>
		</table>
		@endif

        @if ($puzzle->titre_eleve !== NULL OR $puzzle->consignes_eleve !== NULL)
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="frame">
                    @if ($puzzle->titre_eleve !== NULL)
                        <div class="font-monospace small mb-1">{{ $puzzle->titre_eleve }}</div>
                    @endif
                    @if ($puzzle->consignes_eleve !== NULL)
                        <div class="font-monospace text-muted small consignes">
                            <?php
                            $Parsedown = new Parsedown();
                            echo $Parsedown->text($puzzle->consignes_eleve);
                            ?>
                        </div>
                    @endif
                </div>
            </div>
        </div><!-- row -->
        @endif

    </div>

    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-md-6 offset-md-3 text-center" style="position:relative;height:30px;">

				<!-- bouton reinitialiser -->
				<a id="reinitialiser" href="#" style="position:absolute;left:25px;top:10px;" class="text-muted" data-bs-toggle="tooltip" data-bs-placement="top"  data-bs-trigger="hover" title="{{__('réinitialiser')}}"><i class="fas fa-sync-alt"></i></a>

				<!-- bouton verifier -->
                <button id="feedbackLink" type="button" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="{{__('vérifier')}}" style="display:inline"><i class="fas fa-check"></i></button>

            </div>
        </div>

        <div class="row mt-3 mb-5">
            <div class="col-md-6 offset-md-3">
                <div id="sortable" class="sortable-code"></div>
            </div>
        </div>

    </div><!-- container -->

    @include('inc-obfuscate')

    <script>
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		  return new bootstrap.Tooltip(tooltipTriggerEl)
		})
	</script>

</body>
</html>
<?php
ob_end_flush();
?>
