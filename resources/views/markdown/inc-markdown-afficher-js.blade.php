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
    marked.use({
        tokenizer: {
            //space() { return undefined },
            //code() { return undefined },
            //fences() { return undefined },
            //heading() { return undefined },
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





    // markdown_content
    document.querySelectorAll('.markdown_content').forEach(el => {

        // Remplacement des doubles slashes par des triples dans les blocs LaTex
        var texte = el.textContent.replace(/\$\$(.+?)\$\$/gs, function(match) {
            return match.replace(/\\\\/g, '\\\\\\\\');
        });

        // Markdown
        el.innerHTML = DOMPurify.sanitize(marked.parse(texte));
        

        // Latex
        document.addEventListener("DOMContentLoaded", function() {
            // Vérifie si MathJax est chargé et que typesetPromise est disponible
            if (typeof MathJax !== "undefined" && MathJax.typesetPromise) {
                // Cible tous les éléments ayant la classe markdown_content
                const elements = document.querySelectorAll(".markdown_content");

                // Attendre le démarrage de MathJax et typer chaque élément
                MathJax.startup.promise = MathJax.startup.promise || Promise.resolve();
                MathJax.startup.promise.then(() => {
                    MathJax.typesetPromise(Array.from(elements)).then(() => {
                        console.log("MathJax a rendu les équations pour tous les éléments !");
                    }).catch((err) => console.error("Erreur lors du rendu avec MathJax:", err));
                });
            } else {
                console.error("MathJax n'est pas disponible ou la fonction typesetPromise n'existe pas.");
            }
        });

    });  
    
    // coloration syntaxique
    document.querySelectorAll('.markdown_content pre code').forEach(el => {
        el.classList.add('language-python');
        hljs.highlightElement(el);
    });

</script>