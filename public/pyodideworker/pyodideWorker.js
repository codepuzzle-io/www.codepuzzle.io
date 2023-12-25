// pyodideWorker.js
self.importScripts('https://cdn.jsdelivr.net/pyodide/v0.24.1/full/pyodide.js');

var globals_keys = []
 
async function main() {
    self.pyodide = await loadPyodide();
    
    // ========================================================================
    // liste des cles de globals presentes lors de la premiere excecution
    for (const key of pyodide.globals.keys()) {
        globals_keys.push(key);
    }
    // ========================================================================

    self.postMessage({ init: true });
}

let pyodideReadyPromise = main();

self.onmessage = async (event) => {

    const code = event.data.code;
    const asserts = event.data.asserts;
    var output_message = "";

    await pyodideReadyPromise;


    if (event.data.cmd === "setInterruptBuffer") {
        pyodide.setInterruptBuffer(event.data.interruptBuffer);
        return;
    }

    // ========================================================================
    // REINITIALISATION DE GLOBALS (on supprime les cles qui n'etaient pas 
    // presentes lors de la premiere execution)
    const clesASupprimer = [];
    for (const key of pyodide.globals.keys()) {
        if (!globals_keys.includes(key)) {
            clesASupprimer.push(key);
        }
    }
    for (const key of clesASupprimer) {
        pyodide.globals.delete(key);
    }
    // ========================================================================

    // EXECUTION PYTHON
    try {
        self.postMessage({ status: 'running' });
        pyodide.setStdout({batched: (str) => {
            self.postMessage({ output2: str + "\n" });
        }})
        self.postMessage({ code: code });
        self.postMessage({ asserts: asserts });

        await self.pyodide.loadPackagesFromImports(code);

        let output2 = self.pyodide.runPython(code);

        if (typeof(output2) !== 'undefined'){
            self.postMessage({ output2: output2 + "\n" });
        }
        

        var n = 0;
        var ok = true;
        

        for (assert of asserts){

            try {
                // PYTHON OK
                // ASSERT OK

                // redirection output vers console pour ne pas afficher des print dans le div output quand on teste un assert
                pyodide.setStdout({batched: (str) => {
                    self.postMessage({ console_message: str });
                }})

                // ========================================================================
                // REINITIALISATION DE GLOBALS (on supprime les cles qui n'etaient pas 
                // presentes lors de la premiere execution)
                const clesASupprimer = [];
                for (const key of pyodide.globals.keys()) {
                    if (!globals_keys.includes(key)) {
                        clesASupprimer.push(key);
                    }
                }
                for (const key of clesASupprimer) {
                    pyodide.globals.delete(key);
                }
                // ========================================================================

                self.pyodide.runPython(code + "\n" + assert[0] + ', "' + assert[1] + '"');	

                self.postMessage({ assert_valide: n });

            } catch (err) {
                // PYTHON OK
                // ASSERT FAIL

                self.postMessage({ assert_erreur: n });
           
                output_message += "Test "+ (n+1) + ": échec\n";
                
                let errors = err.message.split("File \"<exec>\", ");
                
                errors.forEach((error) => {
                    if (typeof(error) !== 'undefined' && !error.includes('Traceback')) {
                        //output_message += error;
                        
                        // on recupere la ligne de l'erreur
                        regex = /line (\d+)/;
                        let error_line = regex.exec(error)[1];

                        // on retire la premiere ligne pour ne garder que le message
                        let error_string = error.replace(/^.*\n/, '');

                        if (code.split('\n').length) {
                            nb_code_lines = code.split('\n').length;
                        } else {
                            nb_code_lines = 0;
                        }

                        var error_info = ""
                        if (error_line <= nb_code_lines) error_info += "Erreur ligne " + error_line + "\n";
                        if (error_string) error_info += error_string;
                        if (error_info.trim()) {
                            output_message += error_info.trim() + "\n\n";
                        }
                        
                    }
                });	
                
                ok = false;
            }

            n++;			
        }
        
        if (ok) {
            output_message = "Code correct et tests validés. Bravo!";
            self.postMessage({ status: 'success' });
        } 

        self.postMessage({ status: 'completed' });


    // ERREUR EXECUTION PYTHON
    } catch (err) {

        // erreur python
        let errors = err.message.split("File \"<exec>\", ");
        errors.forEach((error) => {
            if (typeof(error) !== 'undefined' && !error.includes('Traceback')) {

                // on recupere la ligne de l'erreur
                regex = /line (\d+)/;
                let error_line = regex.exec(error)[1];

                // on retire la premiere ligne pour ne garder que le message
                let error_string = error.replace(/^.*\n/, '');

                var error_info = "";
                error_info += "Erreur ligne " + error_line + "\n";
                if (error_string) error_info += error_string;
                output_message += error_info.trim() + "\n\n";
            }
        });

        self.postMessage({ status: 'completed' });
        
    }

    self.postMessage({ output1: output_message.trim() });

};