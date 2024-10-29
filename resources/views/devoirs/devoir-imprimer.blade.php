<?php
$devoir = App\Models\Devoir::find(Crypt::decryptString($devoir_id));
if (!$devoir){
    echo "<pre>Ce devoir n'existe pas</pre>";
    exit();
}
$copies = App\Models\Copie::where('jeton_devoir', $devoir->jeton)->orderBy('pseudo')->get();
?>
<!doctype html>
<html lang="fr">
<head>
    @include('inc-meta')
    <style>
        body {
            line-height:1.2;
            font-size:0.8em;
        }
        @media print {
            body {
                background-color:white;
            }
            body * {
                visibility: hidden;
            }

            #print_content, #print_content * {
                visibility: visible;
            }

            #print_content {
                width:100vw !important;
            }

            #header {
                display:none;
            }
        }
    </style>  
    <link rel="stylesheet" href="{{ asset('lib/highlight/atom-one-dark.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/default.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
    <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
    <title>DEVOIR | {{$devoir->jeton}} | IMPRIMER</title>
</head>
<body class="no-mathjax">

    <div id="header" class="container pt-3">
        <div class="row pt-3">
            <div class="col-md-2">
                <div class="text-right mb-3">
                    <a class="btn btn-light btn-sm" href="/devoir-console/{{strtoupper($devoir->jeton_secret)}}" role="button"><i class="fas fa-arrow-left"></i></a>
                </div>
            </div>
            <div class="col-md-8">
                <h1 class="text-center pt-0">COMPTES RENDUS</h1>
                <p class="text-center"><button class="btn btn-dark" onclick="window.print();"><i class="fa-solid fa-print mr-2"></i> imprimer</button></p>
            </div>
        </div>
    </div>

    {{-- PRINT --}}
    <div id="print_content" class="container text-monospace">

        <p class="text-center mt-5" style="font-size:120%;">RÉCAPITULATIF</p>

        <!-- COMMENTAIRES --> 
        <div class="mt-3 text-monospace">{{strtoupper(__('commentaires'))}}</div>
        <table class="table table-borderless mt-2">
            @foreach($copies as $copie)
                <?php
                // == PRE-TRAITEMENT ==========================================================
                $commentaires ='';
                $note = '';
                // une correction existe
                if ($copie->correction_enseignant != null) {
                    foreach (json_decode($copie->correction_enseignant)->cells AS $notebook_cell) {
                        if (isset($notebook_cell->metadata->correction_name) AND $notebook_cell->metadata->correction_name == 'commentaires') $commentaires = $notebook_cell->source[0];
                        if (isset($notebook_cell->metadata->correction_name) AND $notebook_cell->metadata->correction_name == 'note') $note = $notebook_cell->source[0];
                    }
                } else {
                    $copie_cells = json_decode($copie->copie)->cells;
                }
                // == /PRE-TRAITEMENT =========================================================
                ?>
                <tr>
                    <td class="p-2 font-weight-bold text-uppercase">{{$copie->pseudo}}</td>
                    <td style="vertical-align:top;padding:5px 0px 5px 0px;"><div class="border border-success rounded text-dark mr-1 pt-2 pb-2 pl-3 pr-3">{!! nl2br($note) !!}</div></td>
                    <td style="width:100%;vertical-align:top;padding:5px 0px 5px 0px;"><div class="border border-success rounded text-dark pt-2 pb-2 pl-3 pr-3">{!! nl2br($commentaires) !!}</div></td>
                </tr>
            @endforeach
        </table>
        <!-- COMMENTAIRES --> 

        <p class="text-monospace text-center mt-1 small text-muted">comptes-rendus individuels sur les pages suivantes</p>

        <div style="page-break-after: always;">&nbsp;</div>

        
        @foreach($copies as $copie)

            <?php
            // == PRE-TRAITEMENT ==========================================================
            $commentaires ='';
            $note = '';
            // une correction existe
            if ($copie->correction_enseignant != null) {
                $copie_cells = [];
                foreach (json_decode($copie->correction_enseignant)->cells AS $notebook_cell) {
                    if (isset($notebook_cell->metadata->correction_name) AND $notebook_cell->metadata->correction_name == 'commentaires') $commentaires = $notebook_cell->source[0];
                    if (isset($notebook_cell->metadata->correction_name) AND $notebook_cell->metadata->correction_name == 'note') $note = $notebook_cell->source[0];
                    if (!isset($notebook_cell->metadata->correction_name)) $copie_cells[] = $notebook_cell;
                }
            } else {
                $copie_cells = json_decode($copie->copie)->cells;
            }
            // == /PRE-TRAITEMENT =========================================================
            ?>

            <div class="mt-5">

                <!-- PSEUDO --> 
                <div class="text-monospace font-weight-bold mt-2 mb-2 text-uppercase">{{$copie->pseudo}}</div>
                <!-- /PSEUDO --> 

                <div class="p-2" style="border:solid 1px #CED4DA;border-radius:3px;">
                    @foreach(json_decode($copie->copie)->cells AS $copie_cell)

                        @if ($copie_cell->cell_type == 'markdown')
                            <div id="markdown_{{ $loop->iteration }}" class="cellule_content cellule_marked mb-2">{{$copie_cell->source[0]}}</div>
                        @endif

                        @if ($copie_cell->cell_type == 'code') 
                            <pre id="code_{{$loop->iteration}}"><code style="height:100%;border-radius:3px;" class="language-python">{{$copie_cell->source[0]}}</code></pre>
                        @endif

                    @endforeach   
                </div>

                <!-- NOTE & COMMENTAIRES --> 	
                <table class="table table-borderless mt-2">
                    <tr>
                        @if ($note != '')
                            <td style="vertical-align:top;padding:5px 0px 5px 0px;"><div class="border border-success rounded text-dark mr-1 pt-2 pb-2 pl-3 pr-3">{!! nl2br($note) !!}</div></td>
                        @endif
                        <td style="width:100%;vertical-align:top;padding:5px 0px 5px 0px;"><div class="border border-success rounded text-dark pt-2 pb-2 pl-3 pr-3">{!! nl2br($commentaires) !!}</div></td>
                    </tr>
                </table>
                <!-- /NOTE & COMMENTAIRES --> 	

                <div style="page-break-after: always;">&nbsp;</div>

            </div>

        @endforeach

    </div>
    {{-- /PRINT --}}

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
                //heading() { return undefined },
                //hr() { return undefined },
                //blockquote() { return undefined },
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

</body>
</html>
