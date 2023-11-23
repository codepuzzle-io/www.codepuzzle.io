<?php
$devoir = App\Models\Devoir::where('jeton_secret', $jeton_secret)->first();
if (!$devoir){
    echo "<pre>Cet entraînement n'existe pas</pre>";
    exit();
}
$devoir_eleves = App\Models\Devoir_eleve::where('jeton_devoir', $devoir->jeton)->orderBy('pseudo')->get();
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
        .ace-chrome .ace_gutter {
            background: white;
        }
        .ace-chrome .ace_gutter-active-line {
            background-color: white;
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
    <title>ENTRAÎNEMENT / DEVOIR | {{$devoir->jeton}} | IMPRIMER</title>
</head>
<body class="no-mathjax">

    <div id="header" class="container pt-3">
        <div class="row pt-3">
            <div class="col-md-2">
                <div class="text-right mb-3">
                    <a class="btn btn-light btn-sm" href="/devoir-console/{{strtoupper($jeton_secret)}}" role="button"><i class="fas fa-arrow-left"></i></a>
                </div>
            </div>
            <div class="col-md-8">
                <h1 class="text-center pt-0">COMMENTAIRES / CORRECTION / CONSEILS</h1>
                <p class="text-center"><button class="btn btn-dark" onclick="window.print();"><i class="fa-solid fa-print mr-2"></i> imprimer</button></p>
            </div>
        </div>
    </div>

    {{-- PRINT --}}
    <div id="print_content" class="container text-monospace">

        <p class="text-center mt-5" style="font-size:120%;">RÉCAPITULATIF</p>

        <!-- CONSIGNES -->
        <div class="text-monospace mt-3">{{strtoupper(__('consignes'))}}</div>
        <div class="mathjax" style="padding:10px 15px 0px 15px;border-radius:4px;border:solid 1px gray;background-color:white;">
            <?php
            include('lib/parsedownmath/ParsedownMath.php');
            $Parsedown = new ParsedownMath([
                'math' => [
                    'enabled' => true, // Write true to enable the module
                    'matchSingleDollar' => true // default false
                ]
            ]);
            echo $Parsedown->text($devoir->consignes_eleve);
            ?>
        </div>
        <!-- CONSIGNES -->                    

        <!-- SOLUTION --> 
        <div class="mt-3 text-monospace">{{strtoupper(__('solution possible'))}}</div>
        <div id="editor_code" style="border-radius:4px;border:solid 1px gray;">{{ $devoir->solution }}</div>
        <!-- /SOLUTION --> 	

        <!-- COMMENTAIRES --> 
        <div class="mt-3 text-monospace">{{strtoupper(__('commentaires'))}}</div>
        <table class="table table-borderless mt-2">
            @foreach($devoir_eleves as $devoir_eleve)
                <tr>
                    <td class="p-2 font-weight-bold text-uppercase">{{$devoir_eleve->pseudo}}</td>
                    <td style="width:100%;vertical-align:top;padding:5px 0px 5px 0px;"><div class="border border-success rounded text-dark pt-2 pb-2 pl-3 pr-3">{!! nl2br($devoir_eleve->commentaires) !!}</div></td>
                </tr>
            @endforeach
        </table>
        <!-- COMMENTAIRES --> 

        <p class="text-monospace text-center mt-1 small text-muted">comptes-rendus individuels sur les pages suivantes</p>

        <div style="page-break-after: always;">&nbsp;</div>

        
        @foreach($devoir_eleves as $devoir_eleve)

            <div class="mt-5">

                <!-- ELEVE --> 
                <div class="text-monospace font-weight-bold mt-2 text-uppercase">{{$devoir_eleve->pseudo}}</div>
                <!-- /ELEVE --> 

                <!-- CONSIGNES -->
                <div class="text-monospace mt-3">{{strtoupper(__('consignes'))}}</div>
                <div class="mathjax" style="padding:12px 15px 0px 15px;border-radius:4px;border:solid 1px gray;background-color:white;">
                    <?php
                    $Parsedown = new ParsedownMath([
                        'math' => [
                            'enabled' => true, // Write true to enable the module
                            'matchSingleDollar' => true // default false
                        ]
                    ]);
                    echo $Parsedown->text($devoir->consignes_eleve);
                    ?>
                </div>
                <!-- CONSIGNES -->                  

                <!-- CODE ELEVE --> 
                <div class="text-monospace mt-3 text-uppercase">Code élève</div>
                <div id="editor_code_eleve_devoir-{{$loop->iteration}}" style="border-radius:4px;border:solid 1px gray;">{{$devoir_eleve->code_eleve}}</div>
                <!-- /CODE ELEVE --> 

                @if ($devoir->solution)
                    <!-- SOLUTION --> 
                    <div class="mt-3 text-monospace">{{strtoupper(__('solution possible'))}}</div>
                    <div id="editor_code_solution_devoir-{{$loop->iteration}}" style="border-radius:4px;border:solid 1px gray;">{{$devoir->solution}}</div>
                    <!-- /SOLUTION --> 	
                @endif

                <!-- COMMENTAIRES / CORRECTION / CONSEILS --> 	
                <div class="mt-3 text-monospace">COMMENTAIRES / CORRECTION / CONSEILS</div>
                <div class="border border-success rounded text-dark pt-2 pb-2 pl-3 pr-3">{!! nl2br($devoir_eleve->commentaires) !!}</div>
                <div style="page-break-after: always;">&nbsp;</div>
                <!-- /COMMENTAIRES / CORRECTION / CONSEILS --> 	

            </div>

        @endforeach

    </div>
    {{-- /PRINT --}}

    @include('inc-bottom-js')

    <script>
        MathJax = {
            tex: {
                inlineMath: [['$', '$'], ['\\(', '\\)']],
                displayMath: [ ['$$','$$'], ["\\[","\\]"] ],
                processEscapes: true
            },
            options: {
                ignoreHtmlClass: "no-mathjax",
                processHtmlClass: "mathjax"
            }
        };        
    </script>     
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script type="text/javascript" id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-chtml.js"></script> 	

    
    <script src="{{ asset('js/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>
    <script>
        var editor_code = ace.edit("editor_code", {
            theme: "ace/theme/chrome",
            mode: "ace/mode/python",
            maxLines: 500,
            minLines: 1,
            fontSize: 13,
            wrap: true,
            useWorker: false,
            autoScrollEditorIntoView: true,
            highlightActiveLine: false,
            highlightSelectedWord: false,
            highlightGutterLine: true,
            showPrintMargin: false,
            displayIndentGuides: true,
            showLineNumbers: true,
            showGutter: true,
            showFoldWidgets: false,
            useSoftTabs: true,
            navigateWithinSoftTabs: false,
            readOnly: true,
            tabSize: 4
        });
        editor_code.container.style.lineHeight = 1.2;

        var editor_code_eleve_devoir = []
        var editor_code_solution_devoir = []
        for (var i = 1; i <= {{$devoir_eleves->count() }}; i++) {

            editor_code_eleve_devoir[i] = ace.edit('editor_code_eleve_devoir-' + i, {
                theme: "ace/theme/chrome",
                mode: "ace/mode/python",
                maxLines: 500,
                fontSize: 13,
                wrap: true,
                useWorker: false,
                highlightActiveLine: false,
                highlightGutterLine: false,
                showPrintMargin: false,
                displayIndentGuides: true,
                showLineNumbers: true,
                showGutter: true,
                showFoldWidgets: false,
                useSoftTabs: true,
                navigateWithinSoftTabs: false,
                tabSize: 4,
                readOnly: true
            });
            editor_code_eleve_devoir[i].container.style.lineHeight = 1.2;

            @if ($devoir->solution)
                editor_code_solution_devoir[i] = ace.edit('editor_code_solution_devoir-' + i, {
                    theme: "ace/theme/chrome",
                    mode: "ace/mode/python",
                    maxLines: 500,
                    fontSize: 13,
                    wrap: true,
                    useWorker: false,
                    highlightActiveLine: false,
                    highlightGutterLine: false,
                    showPrintMargin: false,
                    displayIndentGuides: true,
                    showLineNumbers: true,
                    showGutter: true,
                    showFoldWidgets: false,
                    useSoftTabs: true,
                    navigateWithinSoftTabs: false,
                    tabSize: 4
                });
                editor_code_solution_devoir[i].container.style.lineHeight = 1.2;
            @endif
        }    

    </script>

    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>

</body>
</html>
