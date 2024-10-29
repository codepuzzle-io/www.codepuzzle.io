<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (isset($sujet_id)) {
    $sujet = App\Models\Sujet::find(Crypt::decryptString($sujet_id));
	if (!isset($sujet)) {
		echo "<pre>Ce sujet n'existe pas.</pre>";
		exit();
	} else {
		if ($sujet->user_id !== 0 && (!Auth::check() || (Auth::check() && Auth::id() !== $sujet->user_id))) {
			echo "<pre>Vous ne pouvez pas accéder à ce sujet.</pre>";
			exit();
		}    
        $sujet_json = json_decode($sujet->sujet);
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    @include('inc-meta')
	@include('markdown/inc-markdown-css')
    <title>SUJET MARKDOWN | CRÉER / MODIFIER</title>
</head>
<body>

	@if(Auth::check())
		@include('inc-nav-console')
	@else
		@include('inc-nav')
	@endif

	<div class="container mt-4 mb-5">

		<div class="row">

            <div class="col-md-2 text-right mt-1">
				@if(Auth::check())
					<a class="btn btn-light btn-sm" href="/console/sujets" role="button"><i class="fas fa-arrow-left"></i></a>
				@else
					@if (isset($jeton_secret))
						<a class="btn btn-light btn-sm" href="/sujet-console/{{ $jeton_secret }}" role="button"><i class="fas fa-arrow-left"></i></a>
					@else
						<a class="btn btn-light btn-sm" href="/sujet-creer" role="button"><i class="fas fa-arrow-left"></i></a>
					@endif
					<div class="mt-3 small text-monospace text-muted">Vous pouvez <a href="/creer-un-compte" target="_blank">créer un compte</a> pour regrouper, gérer et partager vos sujets.</div>
				@endif
			</div>

			<div class="col-md-10 pl-4 pr-4">

				<h1 class="mb-0">{{__('sujet')}}</h1>
				<div class="mb-4 text-muted">Au format Markdown</div>

                <form id="sujet_form" method="POST" action="{{ route('sujet-md-creer-post') }}" enctype="multipart/form-data">

                    @csrf

					<!-- TITRE -->
					<div class="text-monospace">{{strtoupper(__('titre'))}}<sup class="ml-1 text-danger small">*</sup></div>
					<input id="titre" type="text" class="form-control @error('titre') is-invalid @enderror" name="titre" value="{{ $sujet->titre ?? '' }}" autofocus>
					<div id="error_titre" class="mt-1 text-danger text-monospace" style="font-size:70%" role="alert">&nbsp;</div>
					<!-- /TITRE -->	   
                     
					<!-- ÉNONCÉ -->
					<div class="mt-4 text-monospace">{{strtoupper(__('ÉNONCÉ'))}}<sup class="ml-1 text-danger small">*</sup></div>
					<textarea id="markdown_content" class="form-control" name="enonce" rows="6">{{ $sujet_json->enonce ?? '' }}</textarea>
					<!-- /ÉNONCÉ -->

                    @if(isset($sujet_id))
                        <input type="hidden" name="sujet_id" value="{{Crypt::encryptString($sujet->id)}}" />
                    @endif

                    @if(isset($dupliquer))
                        <input type="hidden" name="dupliquer" value="true" />
                    @endif

                    <button type="submit" id="dropzone_submit" class="btn btn-primary mt-3 mb-5 pl-4 pr-4"><i class="fas fa-check"></i></button>

                </form>

			</div>
		</div><!-- /row -->
	</div><!-- /container -->

	@include('inc-bottom-js')
	@include('markdown/inc-markdown-editeur-js')

</body>
</html>
