<div class="mb-5" style="padding:20px;border:solid 1px #DBE0E5;border-radius:4px;background-color:#f3f5f7;border-radius:4px;">

    <h2 class="p-0 m-0">{{ $sujet->titre }}</h2>

    <!-- ÉNONCÉ --> 
    <div class="mt-4 mb-1 text-monospace">{{strtoupper(__("ÉnoncÉ"))}}</div>
    <div class="mb-3 p-3" style="background-color:WHITE;border-radius:4px;">
        <div id="enonce">{{ $sujet_json->enonce }}</div>
    </div>
    <!-- /ÉNONCÉ --> 

    <!-- CODE ELEVE --> 
    <div class="mt-4 mb-1 text-monospace">{{strtoupper(__("code ÉlÈve"))}}</div>
    <div id="editor_code_eleve" style="border-radius:5px;">{{ $sujet_json->code_eleve }}</div>
    <!-- /CODE ELEVE -->

    <!-- CODE ENSEIGNANT --> 
    <div class="mt-4 text-monospace">{{strtoupper(__("code enseignant"))}}</div>
    <div id="editor_code_enseignant" style="border-radius:5px;">{{ $sujet_json->code_enseignant }}</div>
    <!-- /CODE ENSEIGNANT -->

    <!-- SOLUTION --> 
    <div class="mt-4 text-monospace">{{strtoupper(__('solution possible'))}}</div>
    <div id="editor_solution" style="border-radius:5px;">{{ $sujet_json->solution }}</div>
    <!-- /SOLUTION --> 	

</div>


@include('inc-bottom-js')

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
            //table() { return undefined },
            lheading() { return undefined },
            //paragraph() { return undefined },
            //text() { return undefined },
            //escape() { return undefined },
            tag() { return undefined },
            //link() { return undefined },
            reflink() { return undefined },
            //emStrong() { return undefined },
            //codespan() { return undefined },
            //br() { return undefined },
            //del() { return undefined }, // texte barré
            autolink() { return undefined },
            //url() { return undefined },
            //inlineText() { return undefined },           
        },      
    }) 	

    var enonce = document.getElementById('enonce');
    // Remplacement des doubles slashes par des triples dans les blocs LaTex
    var texte = enonce.textContent.replace(/\$\$(.+?)\$\$/gs, function(match) {
        return match.replace(/\\\\/g, '\\\\\\\\');
    });
    enonce.innerHTML = DOMPurify.sanitize(marked.parse(texte));

</script>

<script src="{{ asset('js/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>
<script>
    // Chargement de ace et initialisation des éditeurs.
    var editor_code;
    
    async function init_editors() {
        editor_code_eleve = ace.edit("editor_code_eleve", {
            theme: "ace/theme/puzzle_code",
            mode: "ace/mode/python",
            maxLines: 500,
            minLines: 4,
            fontSize: 14,
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
        editor_code_eleve.container.style.lineHeight = 1.5;

        editor_code_enseignant = ace.edit("editor_code_enseignant", {
            theme: "ace/theme/puzzle_code",
            mode: "ace/mode/python",
            maxLines: 500,
            minLines: 4,
            fontSize: 14,
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
        editor_code_enseignant.container.style.lineHeight = 1.5;

        var editor_solution;
        editor_solution = ace.edit("editor_solution", {
            theme: "ace/theme/puzzle_fakecode",
            mode: "ace/mode/python",
            maxLines: 500,
            minLines: 4,
            fontSize: 14,
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
        editor_solution.container.style.lineHeight = 1.5;
    }
    (async function() {
        // Chargement asynchrone de ace et initialisation des éditeurs
        const editors_initialized_promise = init_editors();
        // Pour être sur que ace est chargé et les éditeurs initialisés.
        await editors_initialized_promise;		
    })();	
</script>

<?php
/*
   IMPORTANT
   To add 20px on top and bottom :
   - modify .ace_gutter {padding-top: 20px;} and .ace_scroller {top: 20px;} see custom.css
   - modify ace.js line 18642 "(this.$extraHeight || 0)" to "(this.$extraHeight || 30)"
   OTHER MOFDIFICATION
   Add margin left and right > change line 18074 "this.setPadding(20);" instead "this.setPadding(4);"
*/
?>