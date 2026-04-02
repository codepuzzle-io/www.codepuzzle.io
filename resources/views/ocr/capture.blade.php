<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    @php
        $description    = 'Capture code';
        $description_og = 'Capture code';
    @endphp
    @include('inc-meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Capture code</title>
    <!-- CropperJS CSS -->
    <link href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.css" rel="stylesheet"/>
    <!-- Custom handle size and styling -->
    <style>
        html,body {
          	height: 100%;
        }

        /* Agrandir et arrondir les points de réglage aux coins */
        .cropper-point.point-se,
        .cropper-point.point-e,
        .cropper-point.point-sw,
        .cropper-point.point-w,
        .cropper-point.point-ne,
        .cropper-point.point-n,
        .cropper-point.point-nw,
        .cropper-point.point-s {
            width: 30px;
            height: 30px;
            background-color: rgba(255, 255, 255, 0.8);
            border: 1px solid #aaa;
            border-radius: 50%; /* Cercle */
            margin: -15px; /* Centrer le cercle sur le coin */
        }
        .cropper-drag-box {
            border-radius:6px;
            height:100%;
        }
    </style>
</head>
<body>

    @php
    $lang = (app()->getLocale() == 'fr') ? '/':'/en';
    @endphp
    
    <div class="container pt-3">
        <div class="row no-gutters">
            <div class="col-5 text-left">
                <a href="{{ $lang }}"><img src="{{ asset('img/code-puzzle.png') }}" width="140" style="padding-top:3px;" alt="CODE PUZZLE - PYTHON" /></a>
				<span class="pl-2 text-danger text-monospace align-top small">beta</span>
            </div>
            <div class="col-7 text-right">
                <button id="btnCam"  class="btn btn-dark mr-2 pl-4 pr-4" style="display:none"><i class="fa-solid fa-camera"></i></button>
                <button id="btnFile" class="btn btn-dark pl-4 pr-4" style="display:none"><i class="fa-solid fa-image"></i></button>
            </div>
        </div>
    </div><!-- container -->
   


    <div class="container pt-3">
	
		<div id="welcome">
			<div class="d-flex flex-column justify-content-center align-items-center" style="min-height:60vh;">
				<div style="transform: translateY(-10%);" class="d-flex flex-column align-items-center">
					<button id="btnCamWelcome"  class="btn btn-dark btn-lg p-4 mb-5"><i class="fa-solid fa-camera fa-2xl pl-4 pr-4"></i></button>
					<button id="btnFileWelcome" class="btn btn-dark btn-lg p-4"><i class="fa-solid fa-image fa-2xl pl-4 pr-4"></i></button>
				</div>
			</div>
		</div>
		
        <!-- Wrapper capture uniquement -->
        <div id="captureWrapper" class="text-center">

            <div id="inputImage" class="border pl-3 pt-3 mb-2" style="border-radius:6px; display:none;">    
                <input class="form-control-file mb-3" type="file" accept="image/*" />
            </div>

            <div id="camContainer" class="mb-3" style="height:99%;position:relative;display:none;">
                <video id="video" autoplay playsinline style="border-radius:6px;width:100%;max-width:100%; border:1px solid #ddd;"></video>
                <div class="mt-2" style="position:absolute;bottom:20px;left:50%; transform: translateX(-50%);">
                    <button id="snapBtn" class="btn btn-light btn-lg"><i class="text-danger p-3 fa-solid fa-circle fa-xl"></i></button>
                </div>
            </div>

            <div class="h-100">
                <img id="imagePreview" style="max-width:100%;display:none;"/>
            </div>

            <button id="cropBtn1" class="mt-3 btn btn-light btn-lg pl-5 pr-5 text-monospace ml-1 mr-1" style="display:none;">extraction sans autocorrection du code</button>
			
            <button id="cropBtn2" class="mt-3 btn btn-light btn-lg pl-5 pr-5 text-monospace ml-1 mr-1" style="display:none;">extraction avec autocorrection du code</button>
			
        </div>
		
        <!-- Aperçu final -->
        <div id="previewWrapper" style="display:none;" class="mt-2 mb-4">
            <div class="text-monospace">CODE CAPTURÉ</div>
            <img id="finalPreview" style="border-radius:6px;width:100%" />
        </div>
		
		<div id="waiting_ia_ocr" class="mt-3 text-center" style="text-align:center;display:none"><i class="fas fa-circle-notch fa-xl fa-spin text-muted"></i></div>

        <!-- Code extrait -->
        <div id="codeContainer" class="mt-4" style="display:none;">
            <div class="text-monospace">CODE EXTRAIT</div>
            <div id="code_editor_eleve" class="mt-1 mb-2 code-editor"></div>
            <!-- Console -->
            <div class="row no-gutters">
                <div class="col-auto mr-2">
                    <div class="text-right">
                        <!-- BOUTON RUN -->
                        <button id="run" data-pyodide="run" onclick="run()" type="button" class="btn btn-primary text-center mb-1" style="width:40px;"><i class="fas fa-circle-notch fa-spin"></i></button>
                        <!-- BOUTON RESTART -->
                        <button id="restart" data-pyodide="restart" onclick="restart()" type="button" class="btn btn-dark text-center mb-1" style="width:40px;display:none;" data-toggle="tooltip" data-placement="right"  data-trigger="hover" title="{{__('Cliquer ici pour interrompre  l\'exécution du code. Python redémarrera complètement mais votre code sera conservé dans l\'éditeur. Le redémarrage peut prendre quelques secondes.')}}"><i class="fas fa-stop"></i></button>
                    </div>
                </div>
                <div id="console" class="col">
                    <div class="text-dark text-monospace" style="float:right;font-size:70%;padding:5px 12px 0px 0px">console</div>
                    <div id="output" class="text-monospace p-3 text-white bg-secondary small" style="white-space: pre-wrap;border-radius:4px;min-height:100px;height:100%;">Prêt!</div>
                </div>
            </div>
			
			<div class="bg-success text-white text-monospace rounded p-2 pl-3 mt-3 small">L'image et le code sont enregistrés sur votre compte Code Puzzle dans la section "<a href="https://www.codepuzzle.io/console/captures" class="text-light" style="text-decoration:underline" target="_blank">Captures</a>".</div>
			

            <!-- Analyze Button -->
			<div class="text-monospace mt-4 mb-1">ANALYSE</div>
            <button id="analyzeBtn" class="btn btn-ia btn-sm mb-2 text-monospace" style="display:inline"><i class="fa-solid fa-wand-magic-sparkles mr-2"></i>analyser le code</button>
			<span id="waiting_ia_txt" class="ml-2" style="vertical-align:3px;display:none"><i class="fas fa-circle-notch fa-spin text-muted"></i></span>


            <!-- Analysis Result -->
            <div id="analysisContainer" class="border p-3" style="border-radius:6px;display:none;">
                <pre id="analysisResult" style="margin-bottom:0;white-space:pre-wrap;"></pre>
            </div>			
			
        </div>

    </div><!-- container -->
	
	<br />
	<br />
	<br />
	<br />


    {{-- == Éditeur ACE ================================================== --}}
    <script src="{{ asset('js/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>
    <script>
        editor_code_eleve = ace.edit('code_editor_eleve', {
            theme: "ace/theme/puzzle_code",
            mode: "ace/mode/python",
            maxLines: 500,
            minLines: 6,
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
            tabSize: 4
        });
        editor_code_eleve.container.style.lineHeight = 1.5;
        editor_code_eleve.setValue('', -1);
    </script>
    {{-- == /Éditeur ACE ================================================= --}}


    {{-- == Webworker ======================================================== --}}
    <script>

        var pyodideWorker = new Worker("{{ asset('pyodideworker/copie-pyodideWorker.js') }}");

        // Initialisation des éditeurs
        function initializeEditors() {
            let code_editors = document.querySelectorAll('.code-editor');
            document.getElementById("output").innerText = "Initialisation...\n";
            document.getElementById("run").disabled = true;
            document.getElementById("restart").style.display = 'none';
        }

        // Mise à jour des éditeurs en fonction du statut (init, running, completed)
        function updateEditors(status) {
            let code_editors = document.querySelectorAll('.code-editor');
            if (status === 'init') {
                document.getElementById("output").innerText = "Prêt!\n";
                document.getElementById("run").style.display = 'inline-block';
                document.getElementById("run").innerHTML = '<i class="fas fa-play"></i>';
                document.getElementById("run").disabled = false;
                document.getElementById("restart").style.display = 'none';
            }
            if (status === 'completed') {
                document.getElementById("run").style.display = 'inline-block';
                document.getElementById("run").innerHTML = '<i class="fas fa-play"></i>';
                document.getElementById("run").disabled = false;
                document.getElementById("restart").style.display = 'none';
            } 
            if (status === 'running') {
                document.getElementById("run").style.display = 'none';
                document.getElementById("restart").style.display = 'inline-block';
            }           
        }

        // Gestion des messages du Web Worker
        function setupWorkerListener(worker) {
            worker.onmessage = function(event) {

                //console.log("WEBWORKER EVENT: ", event.data);

                if (typeof event.data.init !== 'undefined') {
                    updateEditors('init');
                }

                if (typeof event.data.status !== 'undefined') {
                    if (event.data.status === 'running') {
                        updateEditors('running');
                    }

                    if (event.data.status === 'completed') {
                        updateEditors('completed');
                    }
                }

                if (typeof event.data.output !== 'undefined') {
                    document.getElementById("output").innerHTML += event.data.output;
                }
            };
        }

        // Attacher les événements au Web Worker initial
        setupWorkerListener(pyodideWorker);

        // Envoi des données au Web Worker pour exécution
        function run() {
            //console.log('RUN');
            var code = "";
            code = editor_code_eleve.getValue();
            document.getElementById("output").innerHTML = "";
            pyodideWorker.postMessage({ code: code, id: '1' });
        }

        // Fonction pour redémarrer le Web Worker
        function restart() {
            if (pyodideWorker) {
                pyodideWorker.terminate();
                //console.log("Web Worker supprimé.");
            }

            // Recréer un nouveau Web Worker
            pyodideWorker = new Worker("{{ asset('pyodideworker/copie-pyodideWorker.js') }}");
            //console.log("Web Worker redémarré.");

            // Réattacher l'écouteur onmessage au nouveau worker
            setupWorkerListener(pyodideWorker);

            // Réinitialiser les boutons d'exécution et d'arrêt
            document.getElementById("run").style.display = 'inline-block';
            document.getElementById("run").innerHTML = '<i class="fas fa-circle-notch fa-spin"></i>';
            document.getElementById("run").disabled = true;
            document.getElementById("restart").style.display = 'none';
        }

        // Initialisation au chargement
        initializeEditors();

    </script>
    {{-- == /Webworker ======================================================= --}}


    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
	const welcome        = document.getElementById('welcome');
	const btnFileWelcome = document.getElementById('btnFileWelcome');
    const btnCamWelcome  = document.getElementById('btnCamWelcome');
	const btnCam         = document.getElementById('btnCam');
    const btnFile        = document.getElementById('btnFile');
    //const inputImage   = document.querySelector('#inputImage input[type="file"]');
    const inputImage     = document.getElementById('inputImage');
    const camContainer   = document.getElementById('camContainer');
    const video          = document.getElementById('video');
    const snapBtn        = document.getElementById('snapBtn');
    const imgPreview     = document.getElementById('imagePreview');
    const cropBtn1       = document.getElementById('cropBtn1');
    const cropBtn2       = document.getElementById('cropBtn2');
    const captureWrapper = document.getElementById('captureWrapper');
    const previewWrapper = document.getElementById('previewWrapper');
    const finalPreview   = document.getElementById('finalPreview');
    const codeContainer  = document.getElementById('codeContainer');
    const codeArea       = document.getElementById('extractedCode');
	const analyzeBtn     = document.getElementById('analyzeBtn');
	const analysisWrap   = document.getElementById('analysisContainer');
	const analysisRes    = document.getElementById('analysisResult');
	const waitingIaOcr   = document.getElementById('waiting_ia_ocr');
	const waitingIaTxt   = document.getElementById('waiting_ia_txt');
	let stream, cropper, captureId = null;

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    function resetToCapture() {
		captureWrapper.style.display = 'block';
        previewWrapper.style.display = 'none';
        codeContainer.style.display  = 'none';
        imgPreview.style.display     = 'none';
        cropBtn1.style.display       = 'none';
        cropBtn2.style.display       = 'none';
		waitingIaOcr.style.display   = 'none';
        stopWebcam();
        inputImage.value = null;
        if (cropper) {
			cropper.destroy();
			cropper = null;
        }
    }
	
	
	btnCamWelcome.addEventListener('click', () => {
		btnCam.click();
	});
	
	btnFileWelcome.addEventListener('click', () => {
		btnFile.click();
	});

    btnCam.addEventListener('click', async () => {
        resetToCapture();
		welcome.style.display      = 'none';
		btnCam.style.display       = 'inline';
		btnFile.style.display      = 'inline';
        inputImage.style.display   = 'none';
        camContainer.style.display = 'block';
        try {
        stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: { ideal: 'environment' } } });
        video.srcObject = stream;
        } catch (err) {
        alert('Webcam inaccessible : ' + err.message);
        }
    });

    btnFile.addEventListener('click', () => {
        resetToCapture();
		welcome.style.display      = 'none';
		btnCam.style.display       = 'inline';
		btnFile.style.display      = 'inline';
        inputImage.style.display   = 'block';
        camContainer.style.display = 'none';
    });

    inputImage.addEventListener('change', e => {
        const file = e.target.files[0];
        if (file) showImage(URL.createObjectURL(file));
    });

    snapBtn.addEventListener('click', () => {
        if (!video.videoWidth) return;
        const canvas = document.createElement('canvas');
        canvas.width  = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);
        showImage(canvas.toDataURL('image/png'));
    });
	
	
	function extractPythonCode(text) {
	  // 1) Recherche d'un bloc ```python … ```
	  const pythonRegex = /```python\s*([\s\S]*?)\s*```/i;
	  let match = pythonRegex.exec(text);
	  if (match) {
		return match[1].trim();
	  }

	  // 2) Recherche d'un bloc ``` … ```
	  const genericRegex = /```\s*([\s\S]*?)\s*```/i;
	  match = genericRegex.exec(text);
	  if (match) {
		return match[1].trim();
	  }

	  // 3) Pas de fences : on renvoie tout
	  return text;
	}	

    function showImage(src) {
        stopWebcam();
        imgPreview.src           = src;
        imgPreview.style.display = 'block';
        cropBtn1.style.display    = 'inline-block';
        cropBtn2.style.display    = 'inline-block';
        if (cropper) cropper.destroy();
        cropper = new Cropper(imgPreview, {
        viewMode: 1,
        dragMode: 'crop',
        aspectRatio: NaN,
        autoCropArea: 0.8,
        movable: true,
        scalable: true,
        zoomable: true,
        cropBoxMovable: true,
        cropBoxResizable: true,
        guides: true,
        highlight: true,
        background: true,
        minCropBoxWidth: 50,
        minCropBoxHeight: 50,
        ready() {
            const containerData = this.cropper.getContainerData();
            const insetX = containerData.width * 0.1;
            const insetY = containerData.height * 0.1;
            this.cropper.setCropBoxData({
            left: insetX,
            top: insetY,
            width: containerData.width - 2 * insetX,
            height: containerData.height - 2 * insetY
            });
        }
        });
    }

    function stopWebcam() {
        if (stream) {
        stream.getTracks().forEach(track => track.stop());
        stream = null;
        }
        camContainer.style.display = 'none';
    }
	
	

    cropBtn1.addEventListener('click', () => ocrExtract('raw'));
	cropBtn2.addEventListener('click', () => ocrExtract('corrected'));
		
	function ocrExtract(type) {
        const canvas = cropper.getCroppedCanvas();
        cropper.destroy();

        finalPreview.src             = canvas.toDataURL('image/png');
        captureWrapper.style.display = 'none';
        previewWrapper.style.display = 'block';
		waitingIaOcr.style.display   = 'block';

        canvas.toBlob(async blob => {
			const fd = new FormData();
			fd.append('type', type);
			fd.append('image', blob, 'code.png');
			try {
				const res = await fetch('{{ route('ocr.extract') }}', {
				method: 'POST',
				credentials: 'same-origin',
				headers: {
					'X-CSRF-TOKEN': csrfToken,
					'Accept': 'application/json'
				},
				body: fd
				});
				if (!res.ok) {
				const text = await res.text(); console.error(text);
				throw new Error(res.status);
				}
				const json = await res.json();
					// 1) on stocke l’ID pour l’analyse ensuite
					captureId = json.id;
					//console.log('[OCR] captureId set to', captureId);

					// 2) on affiche le code dans l’éditeur
					editor_code_eleve.setValue(extractPythonCode(json.code), -1);
					codeContainer.style.display = 'block';
					waitingIaOcr.style.display = 'none';
			} catch (err) {
				alert('Erreur OCR : ' + err.message);
			}
        });
    };
	
	// Analyze code
	analyzeBtn.addEventListener('click', async () => {
	  // 1) Log du clic et des valeurs envoyées
	  const code = editor_code_eleve.getValue();
	  //console.log('[OCR] Analyse demandée pour captureId=', captureId, 'code=', code);
	  
	  waitingIaTxt.style.display = 'inline';

	  try {
		const payload = { id: captureId, code };
		//console.log('[OCR] Payload →', payload);

		// 2) Envoi à votre route Laravel
		const res = await fetch("{{ route('ocr.analyze') }}", {
		  method: 'POST',
		  credentials: 'same-origin',
		  headers: {
			'X-CSRF-TOKEN': csrfToken,
			'Content-Type': 'application/json',
			'Accept': 'application/json',
		  },
		  body: JSON.stringify(payload),
		});
		//console.log('[OCR] HTTP status', res.status, res.statusText);

		// 3) Gestion d’un code HTTP != 200
		if (!res.ok) {
		  const text = await res.text();
		  console.error('[OCR] Corps de la réponse en erreur →', text);
		  throw new Error(`HTTP ${res.status}`);
		}

		// 4) Lecture du JSON
		const json = await res.json();
		//console.log('[OCR] Réponse JSON →', json);

		// 5) Affichage du résultat
		waitingIaTxt.style.display = 'none';
		analyzeBtn.style.display = 'none';
		analysisRes.textContent    = json.analysis;
		analysisWrap.style.display = 'block';
	  } catch (err) {
		console.error('[OCR] Erreur d’analyse →', err);
		alert('Erreur analyse : ' + err.message);
	  }
	});


    resetToCapture();
    });
    </script>

</body>
</html>