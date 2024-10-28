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
    <link href="{{ asset('css/dropzone-basic.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dropzone.css') }}" rel="stylesheet">
    <title>SUJET PDF | CRÉER / MODIFIER</title>
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
				<div class="mb-4 text-muted">Au format PDF</div>

                <form id="sujet_form" method="POST" action="{{ route('sujet-pdf-creer-post') }}" enctype="multipart/form-data">

                    @csrf

					<!-- TITRE -->
					<div class="text-monospace">{{strtoupper(__('titre'))}}<sup class="ml-1 text-danger small">*</sup></div>
					<input id="titre" type="text" class="form-control @error('titre') is-invalid @enderror" name="titre" value="{{ $sujet->titre ?? '' }}" autofocus>
					<div id="error_titre" class="mt-1 text-danger text-monospace" style="font-size:70%" role="alert">&nbsp;</div>
					<!-- /TITRE -->	   
                     
					<!-- ÉNONCÉ -->
					<div class="mt-4 text-monospace">{{strtoupper(__('ÉNONCÉ'))}} <span class="font-italic small" style="color:silver;">{{__("optionnel")}}</span></div>
					<textarea id="markdown_content" class="form-control" name="enonce" rows="6">{{ $sujet_json->enonce ?? '' }}</textarea>
					<!-- /ÉNONCÉ -->

                    <!-- PDF -->
                    <div class="mt-4 text-monospace">{{strtoupper(__('fichier pdf'))}}<sup class="ml-1 text-danger small">*</sup></div>
                    @if (isset($sujet_id))
                        <iframe id="sujet_pdf" src="{{Storage::url('SUJETS/sujet_'.$sujet->jeton.'.pdf')}}" width="100%" height="400" style="border: none;" class="rounded"></iframe>
                        <div class="text-monospace text-muted small text-justify mb-1">{{__('Déposer ci-dessous un fichier pdf pour remplacer le pdf actuel.')}}</div>
                    @else
                        <div class="text-monospace text-muted small text-justify mb-1">{{__('Déposer ci-dessous le sujet au format pdf')}}</div>
                    @endif
                    <div id="dropzonepdf" class="dropzone text-monospace"></div>
                    <div id="error_files" class="mt-1 text-danger text-monospace" style="font-size:70%" role="alert">&nbsp;</div>
                    <!-- /PDF -->

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
 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/min/dropzone.min.js"></script>
    <script>
        // Désactiver l'auto-découverte de Dropzone
        Dropzone.autoDiscover = false;

        // Initialiser Dropzone quand le DOM est chargé
        document.addEventListener('DOMContentLoaded', function() {
            var dropzonepdf = new Dropzone("#dropzonepdf", {
                url: "/sujet-pdf-creer",
                paramName: "sujet_pdf",
                autoProcessQueue: false,
                uploadMultiple: false, // uplaod files in a single request
                maxFilesize: 10, // MB
                maxFiles: 1,
                previewTemplate: `
                    <div class="dz-preview dz-file-preview">
                        <div class="dz-details">
                            <div class="dz-filename" data-dz-name></div>
                            <div class="dz-size" data-dz-size></div>
                        </div>
                        <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
                        <div class="dz-error-message" data-dz-errormessage></div>
                        <div id="dz-remove" class="dz-remove" data-dz-remove></div>
                    </div>
                `,
                addRemoveLinks: true,
                //disablePreviews:true,
                createImageThumbnails: false,
                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                acceptedFiles: ".pdf",
                // Language Strings
                dictFileTooBig: "Erreur: 10 Mb maximum",
                dictInvalidFileType: "Erreur: format invalide",
                dictCancelUpload: "annuler",
                dictRemoveFile: "supprimer",
                dictDefaultMessage: "déposer le fichier PDF ici ou <span class='btn btn-outline-secondary btn-sm'>parcourir</span>",
            });
        });

        // Configuration des options de Dropzone
        Dropzone.options.dropzonepdf = {
            init: function() {
                var dz = this;

                document.getElementById("dropzone_submit").addEventListener("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    var regex = /^[\p{L}0-9\s\-_.,!?()@#&%$'"]*$/u;

                    // initialisation messages erreur
                    document.getElementById('titre').classList.remove('is-invalid');
                    document.getElementById('error_titre').innerHTML = "&nbsp;";
                    document.getElementById('dropzonepdf').style.borderColor = '#2980b9';

                    // check titre
                    if (document.getElementById('titre').value.length < 8) {
                        document.getElementById('titre').classList.add('is-invalid');
                        document.getElementById('error_titre').innerHTML = "huit caractères minimum";
                    } else if (document.getElementById('titre').value.length > 60) {
                        document.getElementById('titre').classList.add('is-invalid');
                        document.getElementById('error_titre').innerHTML = "pas plus de 60 caratères";
                    } else if (regex.test(document.getElementById('titre').value) == false) {
                        document.getElementById('titre').classList.add('is-invalid');
                        document.getElementById('error_titre').innerHTML = "caractères spéciaux non autorisés";   

                    // fichier pdf
                    @if (!isset($sujet))
                    } else if (dz.files.length == 0) {
                        document.getElementById('dropzonepdf').style.borderColor = '#e3342f';
                        document.getElementById('error_files').innerHTML = "vous devez déposer un fichier pdf";                         
                    @endif

                    } else {
                        // Si aucun fichier dans Dropzone
                        if (dz.files.length === 0) {
                            // No files added, submit the form data without files
                            document.getElementById('sujet_form').submit();
                        } else {
                            // Si des fichiers sont présents, uploader avec Dropzone
                            dz.processQueue();
                        }
                    }
                });

                this.on("removedfile", function(file) {
                    document.getElementById('error_files').innerHTML = '&nbsp;';
                });
                
                this.on('addedfile', function(file) {
                    document.getElementById('error_files').innerHTML = '&nbsp;';
                    document.getElementById("dropzonepdf").style.borderColor = "#79C824";
                    document.querySelector('a.dz-remove').addEventListener("click", function(e) {
                        document.getElementById("dropzonepdf").style.borderColor="#2980b9";                       
                    });
                    if (this.files.length > 1) {
                        this.removeFile(this.files[0]);
                    }
                });

                //send all the form data along with the files:
                this.on("sending", function(data, xhr, formData) {
                    formData.append("titre", document.getElementById("titre").value);
                    formData.append("enonce", markdown_editor.value());
                });                

                // envoi reussi
                this.on("success", function(files, response) {
                    console.log('success sending');
                    console.log('success: \n', JSON.stringify(response));
                    console.log('success: \n', response);
                    window.location = JSON.parse(response).redirect;
                });

                // erreur
                this.on("error", function(files, response) {
                    console.log("DZ ERROR");
                    document.getElementById("dropzonepdf").style.borderColor = "red";
                    document.getElementById("dropzone_submit").style.display = "none";
                    document.querySelector('a.dz-remove').addEventListener("click", function(e) {
                        document.getElementById("dropzonepdf").style.borderColor="#2980b9";                        
                    }); 
                });
            }
        };
    </script> 

</body>
</html>
