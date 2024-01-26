<?php
if (isset($jeton_secret)) {
	$devoir = App\Models\Devoir::where('jeton_secret', $jeton_secret)->first();
	if (!$devoir) {
		echo "<pre>Ce devoir n'existe pas.</pre>";
		exit();
	} else {
		$titre_enseignant = $devoir->titre_enseignant;
		$sous_titre_enseignant = $devoir->sous_titre_enseignant;
		$titre_eleve = $devoir->titre_eleve;
		$consignes = $devoir->consignes_eleve;
		$code_eleve = $devoir->code_eleve;
		$code_enseignant = $devoir->code_enseignant;
		$solution = $devoir->solution;
		$with_chrono = $devoir->with_chrono;
		$with_console = $devoir->with_console;

		if ($devoir->user_id !== 0 && (!Auth::check() || (Auth::check() && Auth::id() !== $devoir->user_id))) {
			echo "<pre>Vous ne pouvez pas accéder à ce devoir.</pre>";
			exit();
		}
	}
}
?>
<!doctype html>
<html lang="fr">
<head>



<script>window.texme = { renderOnLoad: false }</script>
<script src="https://cdn.jsdelivr.net/npm/texme@1.2.2"></script>
<script>
window.onload = function () {


    texme.setOption('style', 'plain')
	document.getElementById("markdown_rendu").addEventListener("click", function(){ 
				var texme_input = document.getElementById("markdown_input").value;
				document.getElementById("markdown_output").innerHTML = texme.render(texme_input);
			});
  
}
</script>















	@include('inc-meta')
    <title>SUJET | CRÉER / MODIFIER</title>
	<style>
        html,body {
          height: 100%;
        }
        .grid {
            display: grid;
            grid-template-columns: 1fr 10px 1fr;
            height:100%;
			border:solid 1px silver;
			margin:10px;
			border-radius:5px;
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
	<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />












        <style>
            .dropzone {
                overflow-y: auto;
                border: 0;
                background: transparent;
            }
            .dz-preview {
                width: 100%;
                margin: 0 !important;
                height: 100%;
                padding: 15px;
                position: absolute !important;
                top: 0;
            }
            .dz-photo {
                height: 100%;
                width: 100%;
                overflow: hidden;
                border-radius: 12px;
                background: #eae7e2;
            }
            .dz-drag-hover .dropzone-drag-area {
                border-style: solid;
                border-color: #86b7fe;;
            }
            .dz-thumbnail {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .dz-image {
                width: 90px !important;
                height: 90px !important;
                border-radius: 6px !important;
            }
            .dz-remove {
                display: none !important;
            }
            .dz-delete {
                width: 24px;
                height: 24px;
                background: rgba(0, 0, 0, 0.57);
                position: absolute;
                opacity: 0;
                transition: all 0.2s ease;
                top: 30px;
                right: 30px;
                border-radius: 100px;
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .dz-delete > svg {
                transform: scale(0.75);
                cursor: pointer;
            }
            .dz-preview:hover .dz-delete, 
            .dz-preview:hover .dz-remove-image {
                opacity: 1;
            }
            .dz-message {
                height: 100%;
                margin: 0 !important;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .dropzone-drag-area {
                height: 300px;
                position: relative;
                padding: 0 !important;
                border-radius: 10px;
                border: 3px dashed #dbdeea;
            }
            .was-validated .form-control:valid {
                border-color: #dee2e6 !important;
                background-image: none;
            }

			.icon-provider{
				display:none !important;
			}


			.editor-toolbar button {

}








        </style>







<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
<script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
















</head>
<body>

	@if(Auth::check())
		@include('inc-nav-console')
	@else
		@include('inc-nav')
	@endif

	<!-- MODAL MARKDOWN HELP -->
	<div class="modal fade" id="markdown_help" tabindex="-1" aria-labelledby="markdown_helpLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<b class="modal-title" id="exampleModalLabel">Formatage du texte</b>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<table class="table table-bordered table-hover small">
						<tr>
							<td></td>
							<td class="p-2 text-center">SYNTAXE</td>
							<td class="p-2 text-center">RENDU</td>
						</tr>
						<tr>
							<td class="p-2">CODE DANS DU TEXTE</td>
							<td class="p-2 text-monospace text-muted">Écrire la fonction `puissance(a, n)`.</td>
							<td class="p-2" style="vertical-align:top">Écrire la fonction <code>puissance(a, n)</code>.</td>
						</tr>		
						<tr>
							<td class="p-2">BLOC DE CODE</td>
							<td class="p-2 text-monospace text-muted">Soit la fonction:<br />```<br />def carre(n):<br />&nbsp;&nbsp;&nbsp;&nbsp;return n**2<br />```</td>
							<td class="p-2" style="vertical-align:top">Soit la fonction:<pre>def carre(n):<br />&nbsp;&nbsp;&nbsp;&nbsp;return n**2<br /></pre></td>
						</tr>					
						<tr>
							<td class="p-2">PARAGRAPHES</td>
							<td class="p-2 text-monospace text-muted">paragraphe<br /><br />paragraphe<p class="mt-2 mb-0" style="color:silver">Laisser une ligne vide pour marquer un nouveau paragraphe.</p></td>
							<td class="p-2" style="vertical-align:top"><p class="mb-1">paragraphe</p>paragraphe</td>
						</tr>
						<tr>
							<td class="p-2">RETOUR À LA LIGNE</td>
							<td class="p-2 text-monospace text-muted">ligne &lt;br&gt;<br />ligne<p class="mt-2 mb-0" style="color:silver">Ajouter &lt;br&gt; en bout de ligne pour forcer le retour à la ligne.</p></td>
							<td class="p-2" style="vertical-align:top">ligne<br />ligne</td>
						</tr>
						<tr>
							<td class="p-2">LISTES</td>
							<td class="p-2 text-monospace text-muted">* point 1<br />* point 2<br /></td>
							<td class="p-2" style="vertical-align:top"><ul style="padding-left:20px;margin-left:0;margin-bottom:0"><li>point 1</li><li>point 2</li></ul></td>
						</tr>
						<tr>
							<td class="p-2">ITALIQUE</td>
							<td class="p-2 text-monospace text-muted">*italique*</td>
							<td class="p-2"><em>italique</em></td>
						</tr>
						<tr>
							<td class="p-2">GRAS</td>
							<td class="p-2 text-monospace text-muted">**gras**</td>
							<td class="p-2"><b>gras</b></td>
						</tr>
						<tr>
							<td class="p-2">SOULIGNÉ</td>
							<td class="p-2 text-monospace text-muted">__souligné__</td>
							<td class="p-2"><u>souligné</u></td>
						</tr>
						<tr>
							<td class="p-2">IMAGE</td>
							<td class="p-2 text-monospace text-muted">
								<p>![](url-image)</p>
								<p class="mb-0"><i>Exemple : ![](https://www.codepuzzle.io/img/codepuzzle.png)<i></p>
							</td>
							<td class="p-2"><img src="https://www.codepuzzle.io/img/codepuzzle.png" width="160"/></td>
						</tr>
						<tr>
							<td class="p-2">LIEN</td>
							<td class="p-2 text-monospace text-muted">
								<p>[texte-cliquable](url-site)</p>
								<p class="mb-1"><i>Exemple 1 : Un [lien](https://eduscol.education.fr) vers Eduscol.</i></p>
								<p class="mb-0"><i>Exemple 2 : Un lien vers [Eduscol](https://eduscol.education.fr).</i></p>
							</td>
							<td class="p-2">
								<p><br /></p>
								<p class="mb-1">Un <a href="http://pep8online.com/" target="_blank">lien</a> vers Eduscol.</p>
								<p class="mb-0">Un lien vers <a href="http://pep8online.com/" target="_blank">Eduscol</a>.</p>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
	<!-- MODAL MARKDOWN HELP -->



	<div class="container mt-4 mb-5">

		<div class="row">

			<div class="col-md-2 text-right">
				@if(Auth::check())
				<a class="btn btn-light btn-sm" href="/console/devoirs" role="button"><i class="fas fa-arrow-left"></i></a>
				@else
				<a class="btn btn-light btn-sm" href="/" role="button"><i class="fas fa-arrow-left"></i></a>
				<div class="mt-3 small text-monospace text-muted">Vous pouvez <a href="/creer-un-compte" target="_blank">créer un compte</a> pour regrouper, gérer et partager vos sujets.</div>
				@endif
			</div>

			<div class="col-md-10 pl-4 pr-4">

				<h1>{{__('nouveau sujet')}}</h1>
				<div>Étape 1</div>
				<div>Sujet: format Markdown ou document PDF</div>

				<form method="POST" action="{{route('devoir-creer-post')}}">

					@csrf

					<!-- TITRE -->
					@if(Auth::check())
					<div class="text-monospace">{{strtoupper(__('titre'))}}<sup class="text-danger small">*</sup></div>
					<div class="text-monospace text-muted small text-justify mb-1">{{__('Visible par vous seulement')}}</div>
					<input id="titre_enseignant" type="text" class="form-control @error('titre_enseignant') is-invalid @enderror" name="titre_enseignant" value="{{ old('titre_enseignant') ?? $titre_enseignant ?? '' }}" autofocus>
					@error('titre_enseignant')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
					@endif
					<!-- /TITRE -->

					<!-- SOUS TITRE -->
					@if(Auth::check())
					<div class="mt-4 text-monospace">{{strtoupper(__('sous-titre'))}} <span class="font-italic small" style="color:silver;">{{__('optionnel')}}</span></div>
					<div class="text-monospace text-muted small text-justify mb-1">{{__('Visible par vous seulement')}}</div>
					<input id="sous_titre_enseignant" type="text" class="form-control @error('sous_titre_enseignant') is-invalid @enderror" name="sous_titre_enseignant" value="{{ old('sous_titre_enseignant') ?? $sous_titre_enseignant ?? '' }}" autofocus>
					@endif
					<!-- /SOUS TITRE -->

					<!-- TITRE ELEVE -->
					@if(Auth::check())
					<div class="mt-4 text-monospace">{{strtoupper(__('titre élève'))}} <span class="font-italic small" style="color:silver;">{{__('optionnel')}}</span></div>
					<div class="text-monospace text-muted small text-justify mb-1">{{__('Visible par l élève')}}</div>
					<input id="titre_eleve" type="text" class="form-control @error('titre_eleve') is-invalid @enderror" name="titre_eleve" value="{{ old('titre_eleve') ?? $titre_eleve ?? '' }}" autofocus>
					@endif
					<!-- /TITRE ELEVE -->	
					
					<div class="mt-4 text-monospace">{{strtoupper(__('sujet'))}}</div>
					<div class="p-4" style="border:solid #CED4DA 1px;border-radius:4px;">



					<textarea id="my-text-area"></textarea>


<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
//const easyMDE = new EasyMDE({element: document.getElementById('my-text-area')});
const editor = new EasyMDE({
	
    /*autofocus: true,*/
	showIcons: ["code", "upload-image", "undo", "redo", "table"],
    autosave: {
        enabled: true,
        uniqueId: "MyUniqueID",
        delay: 1000,
        submit_delay: 5000,
        timeFormat: {
            locale: 'en-US',
            format: {
                year: 'numeric',
                month: 'long',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
            },
        },
        text: "Autosaved: "
    },
	/*
    blockStyles: {
        bold: "__",
        italic: "_",
    },
    unorderedListStyle: "-",
    element: document.getElementById('my-text-area'),
    forceSync: true,
    hideIcons: ["guide", "heading"],
    indentWithTabs: false,
    initialValue: "Hello world!",
    insertTexts: {
        horizontalRule: ["", "\n\n-----\n\n"],
        image: ["![](http://", ")"],
        link: ["[", "](https://)"],
        table: ["", "\n\n| Column 1 | Column 2 | Column 3 |\n| -------- | -------- | -------- |\n| Text     | Text      | Text     |\n\n"],
    },
    lineWrapping: false,
    minHeight: "500px",
    parsingConfig: {
        allowAtxHeaderWithoutSpace: true,
        strikethrough: false,
        underscoresBreakWords: true,
    },
    placeholder: "Type here...",

    previewClass: "my-custom-styling",
    previewClass: ["my-custom-styling", "more-custom-styling"],

    previewRender: (plainText) => customMarkdownParser(plainText), // Returns HTML from a custom parser
    previewRender: (plainText, preview) => { // Async method
        setTimeout(() => {
            preview.innerHTML = customMarkdownParser(plainText);
        }, 250);

        // If you return null, the innerHTML of the preview will not
        // be overwritten. Useful if you control the preview node's content via
        // vdom diffing.
        // return null;

        return "Loading...";
    },
    promptURLs: true,
    promptTexts: {
        image: "Custom prompt for URL:",
        link: "Custom prompt for URL:",
    },
    renderingConfig: {
        singleLineBreaks: false,
        codeSyntaxHighlighting: true,
        sanitizerFunction: (renderedHTML) => {
            // Using DOMPurify and only allowing <b> tags
            return DOMPurify.sanitize(renderedHTML, {ALLOWED_TAGS: ['b']})
        },
    },
    shortcuts: {
        drawTable: "Cmd-Alt-T"
    },
    showIcons: ["code", "table"],
    spellChecker: false,
    status: false,
    status: ["autosave", "lines", "words", "cursor"], // Optional usage
    status: ["autosave", "lines", "words", "cursor", {
        className: "keystrokes",
        defaultValue: (el) => {
            el.setAttribute('data-keystrokes', 0);
        },
        onUpdate: (el) => {
            const keystrokes = Number(el.getAttribute('data-keystrokes')) + 1;
            el.innerHTML = `${keystrokes} Keystrokes`;
            el.setAttribute('data-keystrokes', keystrokes);
        },
    }], // Another optional usage, with a custom status bar item that counts keystrokes
    styleSelectedText: false,
    tabSize: 4,
    toolbarTips: false,
    toolbarButtonClassPrefix: "mde",
	toolbar: [
        {
            name: "bold",
            action: EasyMDE.toggleBold,
            className: "fa-solid fa-bold",
            title: "Bold",
        },
	],
	*/
});


// Voir https://github.com/Ionaru/easy-markdown-editor/issues/245
//editor.togglePreview(editor)
//console.log(editor.markdown(editor.value()));
//console.log(marked.parse(editor.value())); 
</script>


							
						<!-- SUJET MARKDOWN -->
						<div class="text-monospace">
							{{__('Consignes / instructions')}}
							<i class="fas fa-info-circle" style="cursor:pointer;color:#e74c3c;opacity:0.5" data-toggle="modal" data-target="#markdown_help"></i>
						</div>
						<div class="text-monospace text-muted small text-justify mb-1">{{__('Si vous utiliser un document PDF comme sujet, vous pouvez utiliser la zone ci-dessous pour ajouter des consignes. Sinon, laissez cet espace vide.')}}</div>
						<div class="text-monospace text-muted small text-justify mb-1">{{__('Vous pourrez compléter / modifier le sujet à l\'étape suivante.')}}</div>
						<textarea class="form-control @error('consignes_eleve') is-invalid @enderror" name="consignes_eleve" id="consignes_eleve" rows="6">{{ old('consignes_eleve') ?? $consignes ?? '' }}</textarea>
						@error('consignes_eleve')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
						@enderror
						<div id="sujet_markdown">
							consignes_eleve
						</div>
						<a id="sujet_markdown_edit" href="#">edit</a>
						<!-- /SUJET MARKDOWN -->




						<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
							<li class="nav-item" role="presentation">
								<a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">markdown</a>
							</li>
							<li class="nav-item" role="presentation">
								<a class="nav-link" id="markdown_rendu" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">rendu</a>
							</li>
						</ul>
						<div class="tab-content" id="pills-tabContent">
							<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
								<textarea class="form-control @error('consignes_eleve') is-invalid @enderror" name="consignes_eleve" id="markdown_input" rows="6">{{ old('consignes_eleve') ?? $consignes ?? '' }}</textarea>
							</div>
							<div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="markdown_rendu"><div id="markdown_output"></div></div>
						</div>













						<!-- /CONSIGNES -->

						<div class="p-3 text-center"><kbd>ou</kbd></div>

						<!-- PDF -->
						<div class="text-monospace">{{__('Sujet au format pdf')}}</div>
						<div class="dropzone-drag-area form-control" id="previews">
							<div class="dz-message text-muted opacity-50" data-dz-message>
								<span class="text-monospace">déposer le document PDF ici</span>
							</div>    
							<div class="d-none" id="dzPreviewContainer">
								<div class="dz-preview dz-file-preview">
									<div class="dz-photo">
										<img class="dz-thumbnail" data-dz-thumbnail>
									</div>
									<button class="dz-delete border-0 p-0" type="button" data-dz-remove>
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="times"><path fill="#FFFFFF" d="M13.41,12l4.3-4.29a1,1,0,1,0-1.42-1.42L12,10.59,7.71,6.29A1,1,0,0,0,6.29,7.71L10.59,12l-4.3,4.29a1,1,0,0,0,0,1.42,1,1,0,0,0,1.42,0L12,13.41l4.29,4.3a1,1,0,0,0,1.42,0,1,1,0,0,0,0-1.42Z"></path></svg>
									</button>
								</div>
							</div>
						</div>
						<div class="invalid-feedback fw-bold">Please upload an image.</div>
						<!-- /PDF -->

					</div>

					<button class="btn btn-primary fw-medium py-3 px-4 mt-3" id="formSubmit" type="submit">
						<span class="spinner-border spinner-border-sm d-none me-2" aria-hidden="true"></span>
						Submit Form
					</button>
				</form>

			</div>
		</div><!-- /row -->
	</div><!-- /container -->

        <!-- Scripts -->

<script src="https://unpkg.com/stackedit-js@1.0.7/docs/lib/stackedit.min.js"></script>
<script>
	document.getElementById("sujet_markdown_edit").addEventListener("click", function(){ 
		const el = document.getElementById('sujet_markdown');
		const stackedit = new Stackedit();

		// Open the iframe
		stackedit.openFile({
			name: 'Filename', // with an optional filename
			content: {
				text: el.innerText // and the Markdown content.
			}
		});

		// Listen to StackEdit events and apply the changes to the textarea.
		stackedit.on('fileChange', (file) => {
		el.innerHTML = file.content.html;
		});

	});
</script>

        <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/min/dropzone.min.js"></script>
        <script>
            Dropzone.autoDiscover = false;

            /**
             * Setup dropzone
             */
            $('#formDropzone').dropzone({
                previewTemplate: $('#dzPreviewContainer').html(),
                url: '/form-submit',
                addRemoveLinks: true,
                autoProcessQueue: false,       
                uploadMultiple: false,
                parallelUploads: 1,
                maxFiles: 1,
                acceptedFiles: '.pdf',
                thumbnailWidth: 900,
                thumbnailHeight: 600,
                previewsContainer: "#previews",
                timeout: 0,
                init: function() 
                {
                    myDropzone = this;

                    // when file is dragged in
                    this.on('addedfile', function(file) { 
                        $('.dropzone-drag-area').removeClass('is-invalid').next('.invalid-feedback').hide();
                    });
                },
                success: function(file, response) 
                {
                    // hide form and show success message
                    $('#formDropzone').fadeOut(600);
                    setTimeout(function() {
                        $('#successMessage').removeClass('d-none');
                    }, 600);
                }
            });

            /**
             * Form on submit
             */
            $('#formSubmit').on('click', function(event) {
                event.preventDefault();
                var $this = $(this);
                
                // show submit button spinner
                $this.children('.spinner-border').removeClass('d-none');
                
                // validate form & submit if valid
                if ($('#formDropzone')[0].checkValidity() === false) {
                    event.stopPropagation();

                    // show error messages & hide button spinner    
                    $('#formDropzone').addClass('was-validated'); 
                    $this.children('.spinner-border').addClass('d-none');

                    // if dropzone is empty show error message
                    if (!myDropzone.getQueuedFiles().length > 0) {                        
                        $('.dropzone-drag-area').addClass('is-invalid').next('.invalid-feedback').show();
                    }
                } else {

                    // if everything is ok, submit the form
                    myDropzone.processQueue();
                }
            });

        </script>





















	@include('inc-bottom-js')

</body>
</html>
