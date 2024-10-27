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
</script>

<script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
<script>
    const markdown_editor = new EasyMDE({
        element: document.getElementById('markdown_content'),
        autofocus: true,
        minHeight: "100px",
        spellChecker: false,
        hideIcons: ["heading", "quote"],
        showIcons: ["code", "undo", "redo", "table"],
        status: false,
        //previewRender: (plainText) => DOMPurify.sanitize(marked.parse(plainText)),
        previewRender: (plainText, preview) => { // Async method
            setTimeout(() => {
                // Remplacement des doubles slashes par des triples dans les blocs LaTex
                var plainText2 = plainText.replace(/\$\$(.+?)\$\$/gs, function(match) {
                    return match.replace(/\\\\/g, '\\\\\\\\');
                });
                preview.innerHTML = DOMPurify.sanitize(marked.parse(plainText2));
                MathJax.typeset()
            }, 10);
            return "...";
        },
        autosave: {
            enabled: false,
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
            text: "> "
        },
        renderingConfig: {			
            singleLineBreaks: false,
            codeSyntaxHighlighting: true,
            sanitizerFunction: (renderedHTML) => {
                return DOMPurify.sanitize(renderedHTML)
            },
        },
    });
</script>