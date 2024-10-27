<?php
if (isset($copie_id)) {
    $copie = App\Models\Copie::find(Crypt::decryptString($copie_id));
	if (!isset($copie)) {
		echo "<pre>Cette copie n'existe pas.</pre>";
		exit();
	}
} else {
	echo "<pre>Erreur</pre>";
	exit();
}
?>
<!doctype html>
<html lang="fr">
<head>
	@include('inc-meta')
    <title>COPIE</title>
    <link rel="stylesheet" href="{{ asset('lib/highlight/atom-one-dark.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/default.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
    <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
</head>
<body>

    <div style="padding:60px 15px 0 15px;">

        <div id="boutons" class="mb-4" style="position:absolute;top:0;right:25px;padding:10px 10px 10px 40px;width:100%;z-index:1000;background-color:#F8FAFC;">  
            <div id="boutons" class="text-center">
                <a onclick="download_copie_text(this)" class="btn btn-outline-secondary btn-sm text-monospace" role="button" data-container="#boutons" data-toggle="tooltip" data-placement="auto" title="télécharger la copie au format texte (.txt)"><i class="fas fa-file-download"></i> texte</a>
                <a onclick="download_copie_ipynb(this)" class="btn btn-outline-secondary btn-sm text-monospace" role="button" data-container="#boutons" data-toggle="tooltip" data-placement="auto" title="télécharger la copie au format notebook (.ipynb)"><i class="fas fa-file-download"></i> notebook</a>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-1">
                    <a class="btn btn-light btn-sm" href="{{ url()->previous() }}" role="button"><i class="fas fa-arrow-left"></i></a>
                </div>
                <div class="col-md-10">
                    <div id="copie_content">
                        @foreach(json_decode($copie->copie)->cells AS $copie_cell)

                            @if ($copie_cell->cell_type == 'markdown')
                                <div id="markdown_{{ $loop->iteration }}" class="cellule_content cellule_marked mb-2">{{$copie_cell->source[0]}}</div>
                            @endif

                            @if ($copie_cell->cell_type == 'code') 
                                <pre id="code_{{$loop->iteration}}"><code style="height:100%;border-radius:3px;" class="language-python">{{$copie_cell->source[0]}}</code></pre>
                            @endif

                        @endforeach   
                    </div>      
                </div>
            </div><!-- row -->
        </div><!-- container -->


    </div>

    @include('inc-bottom-js')

    {{-- == Markdown + MathJax + coloration ============================== --}}
    <script src="https://cdn.jsdelivr.net/npm/dompurify/dist/purify.min.js"></script>
    <script>
        MathJax = {
            tex: {
                inlineMath: [["$","$"]], 
                displayMath: [["$$","$$"]], 
            },
            svg: {
                fontCache: 'global'
            }
        };
    </script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/python.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script>
        // Markdown Options - pour limiter les options voir https://github.com/Ionaru/easy-markdown-editor/issues/245
        marked.use({
            tokenizer: {
                //space() { return undefined },
                //code() { return undefined },
                //fences() { return undefined },
                heading() { return undefined },
                //hr() { return undefined },
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
                //br() { return undefined },
                del() { return undefined },
                autolink() { return undefined },
                url() { return undefined },
                //inlineText() { return undefined },              
            },       
        })     

        // Rendu des cellules
        function processPage() {
            var divsExclus = document.querySelectorAll('.exclure');
            
            function estDivExclu(element) {
                for (var i = 0; i < divsExclus.length; i++) {
                    if (divsExclus[i].contains(element)) {
                        return true;
                    }
                }
                return false;
            }

            // Logique liée au clic
            if (!estDivExclu(event ? event.target : null)) {
                for (var i = 0; i < divsExclus.length; i++) {
                    var id = divsExclus[i].id.split("_")[1];
                    md_render(id);
                }
            }

            // Coloration syntaxique
            document.querySelectorAll('pre code').forEach(el => {
                el.classList.add('language-python');
                hljs.highlightElement(el);
            });

            // LaTeX
            document.querySelectorAll('div.cellule_marked').forEach(el => {
                MathJax.typesetPromise([el]).then(() => {
                    console.log('Mathématiques rendues.');
                }).catch((err) => console.log('Erreur de rendu MathJax : ', err));
            });
        }

        // Déclencher au clic
        document.addEventListener('click', processPage);

        // Déclencher au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            processPage(); // Appel de la fonction lors du chargement de la page
        });

    </script>
    {{-- == /Markdown + MathJax + coloration ============================= --}}


    {{-- == Récupération du contenu de la copie ============================== --}}
    <script>
        // Get copie au format ipynb
        function get_copie_ipynb() {
            var container = document.getElementById("copie_content");
            var children = container.children;
            let copie = {
                metadata: {
                    kernelspec:{
                        name:"python3",
                        display_name:"Python 3",
                        language:"python"
                    }
                },
                nbformat:4,
                nbformat_minor:2,
                cells: []
            };
            for (var i = 0; i < children.length; i++) {
                var id = children[i].id.substring(children[i].id.lastIndexOf('_') + 1);
                if (document.getElementById('markdown_'+id)) {
                    copie.cells.push({
                        cell_type: "markdown",
                        metadata: {},
                        source: [document.getElementById('markdown_'+id).textContent]
                    });
                }
                if (document.getElementById('code_'+id)) {
                    copie.cells.push({
                        cell_type: "code",
                        execution_count: null,
                        metadata: {},
                        outputs: [],
                        source: [document.getElementById('code_'+id).textContent]
                    });
                    
                }
            }
            return JSON.stringify(copie, null, 2);
        } 

        // Get copie au format text
        function get_copie_text() {
            var container = document.getElementById("copie_content");
            var children = container.children;
            let copie = '';
            for (var i = 0; i < children.length; i++) {
                var id = children[i].id.substring(children[i].id.lastIndexOf('_') + 1);
                if (document.getElementById('markdown_'+id)) {
                    copie += document.getElementById('markdown_'+id).textContent + "\n\n";
                }
                if (document.getElementById('code_'+id)) {
                    copie += "--------\n" + document.getElementById('code_'+id).textContent + "\n--------\n\n";
                }
            }
            return copie;
        }
    </script>
    {{-- == /Récupération du contenu de la copie ============================= --}}



    {{-- == Téléchargement de la copie =================================== --}}
    <script>
        // Téléchargement de la copie au format ipynb
        function download_copie_ipynb(el) {
            console.log(get_copie_ipynb())
            el.href = URL.createObjectURL(new Blob([get_copie_ipynb()], {type: "application/json"}));
            el.download = "copie.ipynb";
        }

        // Téléchargement de la copie au format ipynb
        function download_copie_text(el) {
            console.log(get_copie_text())
            el.href = URL.createObjectURL(new Blob([get_copie_text()], {type: "text/plain"}));
            el.download = "copie.txt";
        }
    </script>       

    {{-- == /Téléchargement de la copie ================================== --}}


</body>
</html>