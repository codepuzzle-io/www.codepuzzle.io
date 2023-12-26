// pyodideWorker.js
self.importScripts('https://cdn.jsdelivr.net/pyodide/v0.24.1/full/pyodide.js');

var globals_keys = []
 
async function main() {
    self.pyodide = await loadPyodide();
    
    // ========================================================================
    // liste des cles de globals presentes lors de la premiere excecution
    for (const key of self.pyodide.globals.keys()) {
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
        self.pyodide.setInterruptBuffer(event.data.interruptBuffer);
        return;
    }

    // ========================================================================
    // REINITIALISATION DE GLOBALS (on supprime les cles qui n'etaient pas 
    // presentes lors de la premiere execution)
    const clesASupprimer = [];
    for (const key of self.pyodide.globals.keys()) {
        if (!globals_keys.includes(key)) {
            clesASupprimer.push(key);
        }
    }
    for (const key of clesASupprimer) {
        self.pyodide.globals.delete(key);
    }
    // ========================================================================

    // EXECUTION PYTHON
    try {
        self.postMessage({ status: 'running' });
        self.pyodide.setStdout({batched: (str) => {
            output_message += str + "\n";
        }})

        await self.pyodide.loadPackagesFromImports(code);

        let output = self.pyodide.runPython(code);

        if (typeof(output) !== 'undefined'){
            output_message += output + "\n";
        }
        
        self.postMessage({ status: 'completed' });

    // ERREUR EXECUTION PYTHON
    } catch (err) {

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

    self.postMessage({ output: output_message.trim() });

};