<?php
if (isset($jeton_secret)) {
	$sujet = App\Models\Sujet::where('jeton_secret', $jeton_secret)->first();
	if (!$sujet) {
		echo "<pre>Ce sujet n'existe pas.</pre>";
		exit();
	} else {
		$titre_enseignant = $sujet->titre_enseignant;
		$sous_titre_enseignant = $sujet->sous_titre_enseignant;
		$titre_eleve = $sujet->titre_eleve;
		$consignes = $sujet->consignes_eleve;

		if ($sujet->user_id !== 0 && (!Auth::check() || (Auth::check() && Auth::id() !== $sujet->user_id))) {
			echo "<pre>Vous ne pouvez pas accéder à ce devoir.</pre>";
			exit();
		}
	}
}
?>
<!doctype html>
<html lang="fr">
<head>

    @include('inc-meta')

    <!-- Styles -->
    <link href="{{ asset('css/dropzone-basic.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dropzone.css') }}" rel="stylesheet">

    <title>SUJET | CRÉER / MODIFIER</title>

</head>
<body>

	@if(Auth::check())
		@include('inc-nav-console')
	@else
		@include('inc-nav')
	@endif

	<div class="container mt-4 mb-5">

		<div class="row">

			<div class="col-md-2 text-right">
				@if(Auth::check())
				    <a class="btn btn-light btn-sm" href="/console/sujets" role="button"><i class="fas fa-arrow-left"></i></a>
				@else
				    <a class="btn btn-light btn-sm" href="/sujet-console/{{$sujet->jeton_secret}}" role="button"><i class="fas fa-arrow-left"></i></a>
				@endif
			</div>

			<div class="col-md-10 pl-4 pr-4">

                @if($titre_enseignant)
                <h2 class="p-0 m-0">{{$titre_enseignant}}</h2>
                @endif

                @if($sous_titre_enseignant)
                <div class="text-monospace small" style="color:silver;">{{$sous_titre_enseignant}}</div>
                @endif

                @if($titre_eleve)
                <div class="text-monospace small" style="color:silver;">Titre élève: {{$titre_eleve}}</div>
                @endif

                @if($consignes)
                <div class="mt-2 mb-3 p-3" style="background-color:#f3f5f7;border-radius:5px;">
                    <div class="text-monospace text-muted mathjax consignes">{{$consignes}}</div>
                </div>
                @endif

                <!-- dropzone -->
                <div id="dropzonepdf" class="dropzone text-monospace"></div>

                <button type="submit" id="dropzone_submit" class="btn btn-primary mt-4 mb-5 pl-4 pr-4" style="display:none"><i class="fas fa-check"></i></button>

			</div>
		</div><!-- /row -->
	</div><!-- /container -->

 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/min/dropzone.min.js"></script>
    <script>
        // Désactiver l'auto-découverte de Dropzone
        Dropzone.autoDiscover = false;

        // Initialiser Dropzone quand le DOM est chargé
        document.addEventListener('DOMContentLoaded', function() {
            var dropzonepdf = new Dropzone("#dropzonepdf", {
                url: "/sujet-creer/{{$jeton_secret}}/pdf",
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
                    dz.processQueue();
                });
                
                this.on('addedfile', function(file) {
                    document.getElementById("dropzonepdf").style.borderColor = "#79C824";
                    document.querySelector('a.dz-remove').addEventListener("click", function(e) {
                        document.getElementById("dropzonepdf").style.borderColor="#2980b9";
                        document.getElementById("dropzone_submit").style.display = "none";                        
                    });
                    document.getElementById("dropzone_submit").style.display = "block"
                    if (this.files.length > 1) {
                        this.removeFile(this.files[0]);
                    }
                });

                //send all the form data along with the files:
                    this.on("sending", function(data, xhr, formData) {
                    formData.append("sujet_id", "{{ Crypt::encryptString($sujet->id) }}");
                    formData.append("sujet_jeton", "{{ $sujet->jeton }}");
                });                

                // envoi reussi
                this.on("success", function(files, response) {
                    console.log("DZ SUCCESS");
                    window.location = "/sujet-console/{{$jeton_secret}}";
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
