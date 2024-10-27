<?php
$devoir = App\Models\Devoir::find(Crypt::decryptString($devoir_id));
if (!$devoir) {
    echo "<pre>Ce devoir n'existe pas</pre>";
    exit();
}

// toutes les copies du devoir
$copies = App\Models\Copie::where('jeton_devoir', $devoir->jeton)->orderBy('pseudo')->get();

// copie en cours de correction
$indice = $copie_num;
$copie = $copies->get($indice);

// sujet du devoir
$sujet = App\Models\Sujet::find($devoir->sujet_id);
$sujet_json = json_decode($sujet->sujet);


// == NAVIGATION ==============================================================
// copie précédente
$indice_prev = ($copie_num == 0) ? count($copies)-1 : $copie_num-1; 
$copie_prev = $copies->get($indice_prev);
// copie suivante
$indice_next = ($copie_num == count($copies)-1) ? 0 : $copie_num+1; 
$copie_next = $copies->get($indice_next);
// == /NAVIGATION =============================================================


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

/*
echo "<pre>";
print_r($sujet_json);
echo "</pre>";
echo $sujet_json->code->{1}->code_enseignant;
exit();
*/
/*
dump($copie->correction_enseignant);
dump($copie_cells);
//exit();
*/
?>
<!doctype html>
<html lang="fr">
<head>
	@include('inc-meta')
    <title>COPIES</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/default.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
    <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
    
    <style>
        html,body {
          height: 100%;
        }
        .grid {
            display: grid;
            grid-template-columns: 1fr 10px 1fr;
            height:100%;
			border:solid 0px silver;
			margin:0px;
			border-radius:0px;
        }
        .gutter-col {
            grid-row: 1/-1;
            cursor: col-resize;
            background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAeCAYAAADkftS9AAAAIklEQVQoU2M4c+bMfxAGAgYYmwGrIIiDjrELjpo5aiZeMwF+yNnOs5KSvgAAAABJRU5ErkJggg==');
            background-color: rgb(229, 231, 235);
            background-repeat: no-repeat;
            background-position: 50%;
            width:15px;
        }
        .gutter-col-1 {
            grid-column: 2;
        }
        .video {
            aspect-ratio: 16 / 9;
            width: 100%;
        }
    </style>

</head>
<body>

    <div id="entete">

        <?php
        $secondes = floor($copie->chrono/1000);
        $heures = gmdate("H", $secondes);
        $minutes = gmdate("i", $secondes);
        $secondes = gmdate("s", $secondes);
        $chrono = "{$heures}h {$minutes}m {$secondes}s";
        ?>

        <div class="container-fluid" style="background-color:#e5e7eb">
            <div class="row">
                <div class="col-3 p-3">
                    <a class="btn btn-dark btn-sm" href="/devoir-console/{{ strtoupper($devoir->jeton_secret) }}" role="button"><i class="fas fa-arrow-left"></i></a>
                </div>
                <div class="col-6 p-2 text-center text-monospace">
                    <a class="btn btn-secondary" href="/devoir-corriger/{{ Crypt::encryptString($devoir->id) }}/{{ $indice_prev }}" role="button"><i class="mr-2 fas fa-chevron-left"></i>{{ $indice_prev+1 }}. {{$copie_prev->pseudo}}</a>
                    <button class="btn btn-dark" type="button">{{$indice+1}}. {{$copie->pseudo}}</button>
                    <a class="btn btn-secondary" href="/devoir-corriger/{{ Crypt::encryptString($devoir->id) }}/{{ $indice_next }}" role="button">{{ $indice_next+1 }}. {{$copie_next->pseudo}}<i class="ml-2 fas fa-chevron-right"></i></a>
                </div>
                <div class="col-3 p-2 text-right text-monospace">
                    <div class="small text-muted pr-3 mt-2" data-toggle="tooltip" data-placement="top" title="temps" style="cursor:help"><i class="mr-2 fa-solid fa-stopwatch"></i>{{$chrono}}</div>
                </div>
            </div>
        </div>  

    </div>


    <div id="grid" class="grid">
        <div style="overflow-y:hidden;position:relative">
            <div id="gauche" style="width:100%;height:100%;overflow-y:scroll;direction: rtl;">
                <div class="pl-3 pr-3" style="direction: ltr;">

                    <div class="mt-3 mb-3">

                        <div class="text-monospace text-success font-weight-bold mt-2">Commentaires</div>
                        
                        <textarea id="commentaires" class="form-control border border-success text-success mb-3" rows="6">{{ $commentaires }}</textarea>
                        
                        <div class="form-inline mb-1">
                            <span class="text-monospace text-success font-weight-bold">Note<sup style="padding-left:2px;font-size:70%;color:silver;">*</sup></span>
                            <input id="note" type="text" class="form-control border border-success text-success ml-2 mr-3" value="{{ $note }}" style="width:80px;" />
                            <button id="save" onclick="correction_sauvegarder('{{ Crypt::encryptString($copie->id) }}')" type="button" class="btn btn-danger" style="display:none;"><i class="fas fa-save"></i></button>
                        </div>
                        <div><span style="font-size:70%;color:silver;"><sup>*</sup>champ optionnel</span></div>

                    </div>

                    <div class="text-muted mt-2">SUJET</div>
                    <div class="mb-3" style="padding:20px;border:solid 1px #DBE0E5;border-radius:4px;background-color:#f3f5f7;border-radius:4px;">

                        <!-- SUJET -->
                        @include('sujets/inc-sujet-afficher')
                        <!-- SUJET -->

                    </div>
                </div>

            </div>
        </div>

        <div id="poignee" class="gutter-col gutter-col-1"></div>

        <div style="overflow-y:hidden;position:relative">
            <div id="grid_droite" style="width:100%;height:100%;overflow-y:scroll;">

                <!-- COPIE -->
                @include('copies/inc-copie-correction-afficher')
                <!-- COPIE -->

            </div>
        </div>
    </div>

	@include('inc-bottom-js')
    @include('sujets/inc-sujet-afficher-js')
    @include('copies/inc-copie-correction-afficher-js')

    <!-- GRID -->
    <script src="{{ asset('lib/split-grid/split-grid.js') }}" type="text/javascript" charset="utf-8"></script>
    <script>
	    Split({
	        minSize: 200,
	        columnGutters: [{
	            track: 1,
	            element: document.querySelector('.gutter-col-1'),
	        }],
	    })
    </script>
    <script>
        function hauteur_grid() {
            var hauteur_entete = document.getElementById('entete').offsetHeight;
            var hauteur_page = document.body.offsetHeight;
            document.getElementById('grid').style.height = (hauteur_page - hauteur_entete) + 'px';;
        }
        window.onload = hauteur_grid;
        window.onresize = hauteur_grid;
    </script>

    <!-- GRID -->

    {{-- == Récupération du contenu de la correction ============================== --}}
    <script>
        // Get correction enseignant
        function get_correction_enseignant() {
            var container = document.getElementById("correction");
            var children = container.children;
            let notebook = {}
            notebook = {
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
            notebook.cells.push({
                cell_type: "markdown",
                metadata: {correction_name:'lien_sujet'},
                source: ['SUJET: www.codepuzzle.io/S{{strtoupper($sujet->jeton)}}']
            });
            notebook.cells.push({
                cell_type: "markdown",
                metadata: {correction_name:'commentaires'},
                source: [document.getElementById('commentaires').value]
            });
            notebook.cells.push({
                cell_type: "markdown",
                metadata: {correction_name:'note'},
                source: [document.getElementById('note').value]
            });
            for (var i = 0; i < children.length; i++) {
                var id = children[i].id.substring(children[i].id.lastIndexOf('_') + 1);
                if (document.getElementById('markdown_'+id)) {
                    notebook.cells.push({
                        cell_type: "markdown",
                        metadata: {},
                        source: [document.getElementById('markdown_content_'+id).textContent]
                    });
                }
                if (document.getElementById('code_editor_eleve_'+id)) {
                    notebook.cells.push({
                        cell_type: "code",
                        execution_count: null,
                        metadata: {},
                        outputs: [],
                        source: [editor_code_eleve[id].getValue(), editor_code_enseignant[id].getValue()]
                    });
                    
                }
            }
            return JSON.stringify(notebook, null, 2);
        }

        // Get correction enseignant
        function get_correction_eleve() {
            var container = document.getElementById("correction");
            var children = container.children;
            let notebook = {}
            notebook = {
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
            notebook.cells.push({
                cell_type: "markdown",
                metadata: {correction_name:'lien_sujet'},
                source: ['SUJET: www.codepuzzle.io/S{{strtoupper($sujet->jeton)}}']
            });
            notebook.cells.push({
                cell_type: "markdown",
                metadata: {correction_name:'commentaires'},
                source: [document.getElementById('commentaires').value]
            });
            notebook.cells.push({
                cell_type: "markdown",
                metadata: {correction_name:'note'},
                source: [document.getElementById('note').value]
            });
            for (var i = 0; i < children.length; i++) {
                var id = children[i].id.substring(children[i].id.lastIndexOf('_') + 1);
                if (document.getElementById('markdown_'+id)) {
                    notebook.cells.push({
                        cell_type: "markdown",
                        metadata: {},
                        source: [document.getElementById('markdown_content_'+id).textContent]
                    });
                }
                if (document.getElementById('code_editor_eleve_'+id)) {
                    notebook.cells.push({
                        cell_type: "code",
                        execution_count: null,
                        metadata: {},
                        outputs: [],
                        source: [editor_code_eleve[id].getValue()]
                    });
                    
                }
            }
            return JSON.stringify(notebook, null, 2);
        }        
    </script>
    {{-- == /Récupération du contenu de la correction ============================= --}}


    <script>
        function correction_sauvegarder(copie_id) {
            console.log(get_correction_eleve());
            console.log(get_correction_enseignant());
            // Créer l'objet formData
            var formData = new URLSearchParams();
            formData.append('copie_id', copie_id);
            formData.append('correction_eleve', encodeURIComponent(get_correction_eleve()));
            formData.append('correction_enseignant', encodeURIComponent(get_correction_enseignant()));
            // Effectuer la requête fetch
            return fetch('/devoir-correction-sauvegarder', {
                method: 'POST',
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded", 
                    "X-CSRF-Token": "{{ csrf_token() }}"
                },
                body: formData
            })
            .then(function(response) {
                // Renvoie la réponse du serveur (peut contenir un message de confirmation)
                if (!response.ok) {
                    throw new Error('Erreur HTTP, statut : ' + response.status);
                }
                // confirmation de l'enregistrement
                document.getElementById('commentaires').style.setProperty('border-color', '#71bb22', 'important');
                document.getElementById('note').style.setProperty('border-color', '#71bb22', 'important');
                document.getElementById('save').style.display = "none";
                return response.text();
                
            })
            .then(function(data) {
                // Affiche la réponse du serveur dans la console
                console.log('Réponse du serveur:', data); 
            })
            .catch(function(error) {
                // Gère les erreurs liées à la requête Fetch
                console.error('Erreur:', error); 
                // Relancer l'erreur pour que la fonction appelante sache qu'il y a eu un problème
                throw error; 
            });
        }
    </script>

    <script>
        var commentaires = document.getElementById('commentaires');
        var note = document.getElementById('note');

        // Fonction à exécuter lorsqu'il y a une modification
        function siModification(event) {
            console.log("Changement détecté :", event.target.value);
            event.target.style.setProperty('border-color', '#E3342F', 'important');
            document.getElementById('save').style.display = "inline";
        }

        // Ajouter l'écouteur d'événement 'input'
        commentaires.addEventListener('input', siModification);
        note.addEventListener('input', siModification);
    </script>

</body>
</html>
