<?php
$sujet = App\Models\Sujet::where('jeton', $jeton)->first();
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

	<div class="container mt-4 mb-5">

		<div class="row">

			<div class="col pl-4 pr-4">

                <h1 class="mt-2 mb-2 text-center"><a class="navbar-brand m-1" href="{{ url('/') }}"><img src="{{ asset('img/codepuzzle.png') }}" height="25" alt="CODE PUZZLE" /></a></h1>
                <div class="p-2 mb-4 mr-4 ml-4 small text-monospace rounded border border-danger text-danger">
                    Version alpha</br>
                    A faire:</br>
                    * intégration des sujets dans les devoirs avec environnement anti-triche</br>
                    * intégration des sujets / devoirs dans les classes</br>
                    * création d'une banque de sujets</br>
                    * téléchargement des copies au format PDF</br>
                    * partage des copies avec lien unique</br>
                    * ...</br>
                    Bugs/questions/commentaires:</br>
                     - https://github.com/codepuzzle-io/www.codepuzzle.io/issues</br>
                     - https://github.com/codepuzzle-io/www.codepuzzle.io/discussions</br>
                     - contact@codepuzzle.io
                </div>

                <h2 class="p-0 m-0">{{$sujet->titre_enseignant}}</h2>
                <div class="text-monospace small" style="color:silver;">{{$sujet->sous_titre_enseignant}}</div>
                <div class="text-monospace small" style="color:silver;">Titre élève: {{$sujet->titre_eleve}}</div>

                <div class="mt-3 mb-4 text-center">
                    <a class="btn btn-outline-secondary btn-sm text-monospace" href="/S{{strtoupper($sujet->jeton)}}/copie" role="button" target="_blank">ouvrir une copie</a>
                    <a class="btn btn-outline-secondary btn-sm text-monospace" href="" role="button">créer un devoir</a>
                </div>

                <div class="mt-2 mb-3 p-3" style="background-color:#f3f5f7;border-radius:5px;">
                    <div class="text-monospace text-muted mathjax consignes">{{$sujet->consignes_eleve}}</div>
                </div>

                @if ($sujet->sujet_type == 'md')
                <div id="sujet_md_checked" class="mt-4 mb-5" @if ($sujet->sujet_type !== 'md') style="display:none" @endif>
                    <div class="row no-gutters">

                        <div class="col">
                            <div id="sujet_md" class="cellule_content exclure cellule_marked hover-edit"></div>
                        </div>
                    </div>
                </div>
                @endif

                @if ($sujet->sujet_type == 'pdf')
                <div id="sujet_pdf_checked" class="mt-4 mb-5" @if ($sujet->sujet_type !== 'pdf') style="display:none" @endif>
                    <div class="row no-gutters">
  
                        <div class="col">
                            <iframe id="sujet_pdf" src="{{Storage::url('SUJETS/PDF/'.$sujet->jeton.'.pdf')}}" width="100%" height="800" style="border: none;" class="rounded"></iframe>
                        </div>
                    </div>  
                </div> 
                @endif 
    
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
        document.getElementById('sujet_md').innerHTML = marked.parse(`{{ $sujet->sujet_md }}`);
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