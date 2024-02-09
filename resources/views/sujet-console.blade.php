<?php
if (isset($jeton_secret)) {
	$sujet = App\Models\Sujet::where('jeton_secret', $jeton_secret)->first();
	if (!$sujet) {
		echo "<pre>Ce devoir n'existe pas.</pre>";
		exit();
	} else {
		$titre_enseignant = $sujet->titre_enseignant;
		$sous_titre_enseignant = $sujet->sous_titre_enseignant;
		$titre_eleve = $sujet->titre_eleve;
		$consignes = $sujet->consignes_eleve;
		$sujet_md = $sujet->sujet_md;
		$sujet_pdf = $sujet->sujet_pdf;

		if ($sujet->user_id !== 0 && (!Auth::check() || (Auth::check() && Auth::id() !== $sujet->user_id))) {
			echo "<pre>Vous ne pouvez pas accéder à ce sujet.</pre>";
			exit();
		}
	}
}
?>
<!doctype html>
<html lang="fr">
<head>
	@include('inc-meta')
    <title>SUJET</title>

    <style>
        .cellule {
            position: relative;
            margin: 5px 0px 5px 0px;
        }
        .cellule_content {
            position: relative;
            padding: 8px;
            border: 1px solid #CED4DA;
            border-radius:4px;
            background-color:white;
            overflow: hidden;
            resize: none;
        }
        .cellule_marked {
            background-color:#fafafa;
        }
        .cellule_type {
            position:absolute;
            top:3px;
            left:8px;
        }
        .control {
            position:absolute;
            bottom:0;
            right:3px;
        }
        .control_bouton {
            display:inline-block;
            width:20px;
            text-align:center;
            cursor:pointer;
            border-radius:2px;
            opacity:0.2;
        }
        .control_bouton:hover {
            background-color:#e2e6ea;
            opacity:0.8;
        }

        .ace_editor {
            border-radius:4px;    
        }


        .markedarea_icon {
            position: absolute;
            left:0;
            top:0;
            width:100%;
            height:100%;
            z-index:1000;         
        }

        .markedarea_icon i {
            position: absolute;
            top: 50%;
            left: 50%;
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            display:none;
        }

        .markedarea_icon:hover {
            cursor:text;
        }

        .markedarea_icon:hover i {
            display:inline;
        }

    </style>

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
				    <a class="btn btn-light btn-sm" href="/" role="button"><i class="fas fa-arrow-left"></i></a>
				    <div class="mt-3 small text-monospace text-muted">Vous pouvez <a href="/creer-un-compte" target="_blank">créer un compte</a> pour regrouper, gérer et partager vos sujets.</div>
				@endif
			</div>

			<div class="col-md-10 pl-4 pr-4">

                @if($sujet->user_id == 0 OR !Auth::check())
                <div id="frame" class="frame">
                    <div class="row">
                        <div class="col-md-8 offset-md-2 text-monospace pt-3 pb-3">
                            @if(isset($_GET['i']))
                                <div class="text-danger text-center font-weight-bold m-2">SAUVEGARDEZ LES INFORMATIONS CI-DESSOUS AVANT DE QUITTER CETTE PAGE</div>
                            @endif
                            <div class="text-center font-weight-bold">lien secret</div>
                            <div class="text-center p-2 text-break align-middle border border-danger rounded"><a href="/sujet-console/{{strtoupper($sujet->jeton_secret)}}" target="_blank" class="text-danger">www.codepuzzle.io/sujet-console/{{strtoupper($sujet->jeton_secret)}}</a></div>
                            <div class="small text-muted p-1"><span class="text-danger"><i class="fas fa-exclamation-circle"></i> Ne pas partager ce lien. </span> Il permet de revenir sur cette page.</div>
                        </div>
                    </div>
                </div>
                @endif

                <h2 class="p-0 m-0">{{$titre_enseignant}}</h2>
                <div class="text-monospace small" style="color:silver;">{{$sous_titre_enseignant}}</div>
                <div class="text-monospace small" style="color:silver;">Titre élève: {{$titre_eleve}}</div>

                <div class="mt-3 mb-4 text-center">
                    <a class="btn btn-dark btn-sm text-monospace" href="/sujet-creer/{{$jeton_secret}}" role="button"><i class="fa-solid fa-pen mr-2"></i>modifier</a>
                    <a class="btn btn-outline-secondary btn-sm text-monospace" href="/S{{strtoupper($sujet->jeton)}}" role="button" target="_blank">ouvrir une copie</a>
                    <a class="btn btn-outline-secondary btn-sm text-monospace" href="/sujet-creer/{{$jeton_secret}}" role="button">créer un devoir</a>
                </div>

                <div class="mt-2 mb-3 p-3" style="background-color:#f3f5f7;border-radius:5px;">
                    <div class="text-monospace text-muted mathjax consignes">{{$consignes}}</div>
                </div>

                @if ($sujet->sujet_md !== NULL AND $sujet->sujet_pdf !== NULL)
                    Sujet à afficher:
                    <div class="pl-2">
                        <div class="custom-control custom-radio">
                            <input type="radio" id="radio_md" name="customRadio" class="custom-control-input" @if ($sujet->sujet_type == 'md') checked @endif>
                            <label class="custom-control-label" for="radio_md">Markdown</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" id="radio_pdf" name="customRadio" class="custom-control-input" @if ($sujet->sujet_type == 'pdf') checked @endif>
                            <label class="custom-control-label" for="radio_pdf">PDF</label>
                        </div>
                    </div>
                @endif


                <div id="sujet_md_checked" class="mt-4 mb-5" @if ($sujet->sujet_type !== 'md') style="display:none" @endif>
                    <div class="row no-gutters">
                        <div class="col-auto mr-2">
                            <div><a class="btn btn-dark btn-sm text-monospace" href="/sujet-creer/{{$jeton_secret}}/md" role="button"><i class="fa-solid fa-pen"></i></a></div>
                        </div>
                        <div class="col">
                            <div id="sujet_md" class="cellule_content exclure cellule_marked hover-edit"></div>
                        </div>
                    </div>
                </div>
                
                <div id="sujet_pdf_checked" class="mt-4 mb-5" @if ($sujet->sujet_type !== 'pdf') style="display:none" @endif>
                    <div class="row no-gutters">
                        <div class="col-auto mr-2">
                            <div><a class="btn btn-dark btn-sm text-monospace" href="/sujet-creer/{{$jeton_secret}}/pdf" role="button"><i class="fa-solid fa-pen"></i></a></div>
                        </div>
                        <div class="col">
                            <iframe id="sujet_pdf" src="{{Storage::url('SUJETS/PDF/'.$sujet->jeton.'.pdf')}}" width="100%" height="800" style="border: none;" class="rounded"></iframe>
                        </div>
                    </div>  
                </div>  
    
			</div>

		</div><!-- /row -->
	</div><!-- /container -->

	@include('inc-bottom-js')
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script>
        // pour limiter les options
        // voir https://github.com/Ionaru/easy-markdown-editor/issues/245
        marked.use({
            tokenizer: {
                //space() { return undefined },
                //code() { return undefined },
                //fences() { return undefined },
                heading() { return undefined },
                hr() { return undefined },
                blockquote() { return undefined },
                //list() { return undefined },
                html() { return undefined },
                //def() { return undefined },
                table() { return undefined },
                lheading() { return undefined },
                //paragraph() { return undefined },
                //text() { return undefined },
                //escape() { return undefined },
                tag() { return undefined },
                link() { return undefined },
                reflink() { return undefined },
                //emStrong() { return undefined },
                //codespan() { return undefined },
                br() { return undefined },
                del() { return undefined },
                autolink() { return undefined },
                url() { return undefined },
                //inlineText() { return undefined },
            }
        });
        document.getElementById('sujet_md').innerHTML = marked.parse(`{{ $sujet_md }}`);
    </script>

    <script>
        document.getElementById("radio_md").addEventListener("change", function() {
            console.log('md');
            document.getElementById("sujet_md_checked").style.display = this.checked ? "block" : "none";
            document.getElementById("sujet_pdf_checked").style.display = "none";
            postData('/sujet-change-type', { sujet_id: '{{ Crypt::encryptString($sujet->id) }}', sujet_type:'md' })
        });

        document.getElementById("radio_pdf").addEventListener("change", function() {
            console.log('pdf');
            document.getElementById("sujet_pdf_checked").style.display = this.checked ? "block" : "none";
            document.getElementById("sujet_md_checked").style.display = "none";
            postData('/sujet-change-type', { sujet_id: '{{ Crypt::encryptString($sujet->id) }}', sujet_type:'pdf' })
        });
    </script>

    <script>
        async function postData(url = '', data = {}) {
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', "X-CSRF-Token": "{{ csrf_token() }}"},
                    body: JSON.stringify(data)
                });
                if (!response.ok) {
                    throw new Error(`Erreur HTTP ! statut: ${response.status}`);
                }
                return await response.json();

            } catch (error) {
                console.error('Il y a eu un problème avec la requête POST: ', error);
            }
        }
    </script>


</body>
</html>