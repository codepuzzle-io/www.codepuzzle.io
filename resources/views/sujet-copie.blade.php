<!doctype html>
<html lang="fr">
<head>
	@include('inc-meta')
    <title>SUJET</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
    <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
    <style>
        .cellule {
            position: relative;
            margin: 5px 0px 5px 0px;
        }
        .cellule_content {
            position: relative;
            padding: 8px;
            border: 1px solid #CED4DA;
            border-radius:4px;
            background-color:white;
            overflow: hidden;
            resize: none;
        }
        .cellule_marked {
            background-color:#fafafa;
        }
        .cellule_type {
            position:absolute;
            top:3px;
            left:8px;
        }
        .control {
            position:absolute;
            bottom:0;
            right:3px;
        }
        .control_bouton {
            display:inline-block;
            width:20px;
            text-align:center;
            cursor:pointer;
            border-radius:2px;
            opacity:0.2;
        }
        .control_bouton:hover {
            background-color:#e2e6ea;
            opacity:0.8;
        }

        .ace_editor {
            border-radius:4px;    
        }


        .markedarea_icon {
            position: absolute;
            left:0;
            top:0;
            width:100%;
            height:100%;
            z-index:1000;         
        }

        .markedarea_icon i {
            position: absolute;
            top: 50%;
            left: 50%;
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            display:none;
        }

        .markedarea_icon:hover {
            cursor:text;
        }

        .markedarea_icon:hover i {
            display:inline;
        }

    </style>

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

	<div class="grid">
        <div style="overflow-y:hidden;">
            <div id="gauche">

                <h1 class="mt-2 mb-2 text-center"><a class="navbar-brand m-1" href="{{ url('/') }}"><img src="{{ asset('img/codepuzzle.png') }}" height="25" alt="CODE PUZZLE" /></a></h1>
                <div class="p-2 mb-4 mr-4 ml-4 small text-monospace rounded border border-danger text-danger">
                    Version alpha</br>
                    A faire:</br>
                    * intégration des sujets dans les devoirs avec environnement anti-triche</br>
                    * intégration des sujets / devoirs dans les classes</br>
                    * création d'une banque de sujets</br>
                    * téléchargement des copies au format PDF</br>
                    * partage des copies avec lien unique</br>
                    * ...</br>
                    Bugs/questions/commentaires:</br>
                     - https://github.com/codepuzzle-io/www.codepuzzle.io/issues</br>
                     - https://github.com/codepuzzle-io/www.codepuzzle.io/discussions</br>
                     - contact@codepuzzle.io
                </div>
                <?php
                $sujet = App\Models\Sujet::where('jeton', $jeton)->first();
                ?>
                @if ($sujet->sujet_type == 'pdf')
                    <iframe src="{{Storage::url('SUJETS/PDF/'.$sujet->jeton.'.pdf')}}" width="100%" height="800" style="border: none;"></iframe>
                @endif

            </div>
        </div>

        <div id="poignee" class="gutter-col gutter-col-1"></div>

        <div style="overflow-y:hidden;">
			<div id="droite" class="p-4">

                <div>

                    <div class="mb-4">
                        <button type="button" class="btn btn-dark btn-sm text-monospace" onclick="ajouterDiv(null, 'bas', 'text')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 16" fill="none"><g xmlns="http://www.w3.org/2000/svg"><path d="M9.25 10.0693L7.375 10.0693L7.375 8.19434C7.375 7.98809 7.20625 7.81934 7 7.81934C6.79375 7.81934 6.625 7.98809 6.625 8.19434L6.625 10.0693L4.75 10.0693C4.54375 10.0693 4.375 10.2381 4.375 10.4443C4.375 10.6506 4.54375 10.8193 4.75 10.8193L6.625 10.8193L6.625 12.6943C6.625 12.9006 6.79375 13.0693 7 13.0693C7.20625 13.0693 7.375 12.9006 7.375 12.6943L7.375 10.8193L9.25 10.8193C9.45625 10.8193 9.625 10.6506 9.625 10.4443C9.625 10.2381 9.45625 10.0693 9.25 10.0693Z" fill="#ffffff" stroke="#ffffff" stroke-width="0.7"></path></g><path fill-rule="evenodd" clip-rule="evenodd" d="M2.5 5.5L2.5 3.5L11.5 3.5L11.5 5.5L2.5 5.5ZM2 7C1.44772 7 1 6.55228 1 6L1 3C1 2.44772 1.44772 2 2 2L12 2C12.5523 2 13 2.44772 13 3L13 6C13 6.55229 12.5523 7 12 7L2 7Z" fill="#ffffff"></path></svg> texte
                        </button>
                        <button type="button" class="btn btn-dark btn-sm text-monospace" onclick="ajouterDiv(null, 'bas', 'code')"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><g xmlns="http://www.w3.org/2000/svg"><path d="M9.25 10.0693L7.375 10.0693L7.375 8.19434C7.375 7.98809 7.20625 7.81934 7 7.81934C6.79375 7.81934 6.625 7.98809 6.625 8.19434L6.625 10.0693L4.75 10.0693C4.54375 10.0693 4.375 10.2381 4.375 10.4443C4.375 10.6506 4.54375 10.8193 4.75 10.8193L6.625 10.8193L6.625 12.6943C6.625 12.9006 6.79375 13.0693 7 13.0693C7.20625 13.0693 7.375 12.9006 7.375 12.6943L7.375 10.8193L9.25 10.8193C9.45625 10.8193 9.625 10.6506 9.625 10.4443C9.625 10.2381 9.45625 10.0693 9.25 10.0693Z" fill="#ffffff" stroke="#ffffff" stroke-width="0.7"></path></g><path d="M2.5 5.5L2.5 3.5L11.5 3.5L11.5 5.5L2.5 5.5ZM2 7C1.44772 7 1 6.55228 1 6L1 3C1 2.44772 1.44772 2 2 2L12 2C12.5523 2 13 2.44772 13 3L13 6C13 6.55229 12.5523 7 12 7L2 7Z" fill="#ffffff"></path></svg> code</button>

                        <div style="float:right">
                            <a id="notebook_download" class="btn btn-outline-secondary btn-sm text-monospace" href="" role="button"><i class="fas fa-file-download"></i> notebook</a>
                        </div>
                    </div>

                    <div id="mainContainer">

                        <div class="cellule" id="div_1">

                            <div style="position:relative;padding-bottom:25px;">
                                <div class="control">

                                    <div class="control_bouton" onclick="deplacer('haut', 'div_1')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M1.52899 6.47101C1.23684 6.76316 1.23684 7.23684 1.52899 7.52899V7.52899C1.82095 7.82095 2.29426 7.82116 2.58649 7.52946L6.25 3.8725V12.25C6.25 12.6642 6.58579 13 7 13V13C7.41421 13 7.75 12.6642 7.75 12.25V3.8725L11.4027 7.53178C11.6966 7.82619 12.1736 7.82641 12.4677 7.53226V7.53226C12.7617 7.2383 12.7617 6.7617 12.4677 6.46774L7.70711 1.70711C7.31658 1.31658 6.68342 1.31658 6.29289 1.70711L1.52899 6.47101Z" fill="#000000"></path></svg></div>

                                    <div class="control_bouton" onclick="deplacer('bas', 'div_1')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M12.471 7.52899C12.7632 7.23684 12.7632 6.76316 12.471 6.47101V6.47101C12.179 6.17905 11.7057 6.17884 11.4135 6.47054L7.75 10.1275V1.75C7.75 1.33579 7.41421 1 7 1V1C6.58579 1 6.25 1.33579 6.25 1.75V10.1275L2.59726 6.46822C2.30338 6.17381 1.82641 6.17359 1.53226 6.46774V6.46774C1.2383 6.7617 1.2383 7.2383 1.53226 7.53226L6.29289 12.2929C6.68342 12.6834 7.31658 12.6834 7.70711 12.2929L12.471 7.52899Z" fill="#000000"></path></svg></div>

                                    <div class="control_bouton" onclick="ajouterDiv('div_1', 'haut', 'text')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><g xmlns="http://www.w3.org/2000/svg"><path d="M4.75 4.93066H6.625V6.80566C6.625 7.01191 6.79375 7.18066 7 7.18066C7.20625 7.18066 7.375 7.01191 7.375 6.80566V4.93066H9.25C9.45625 4.93066 9.625 4.76191 9.625 4.55566C9.625 4.34941 9.45625 4.18066 9.25 4.18066H7.375V2.30566C7.375 2.09941 7.20625 1.93066 7 1.93066C6.79375 1.93066 6.625 2.09941 6.625 2.30566V4.18066H4.75C4.54375 4.18066 4.375 4.34941 4.375 4.55566C4.375 4.76191 4.54375 4.93066 4.75 4.93066Z" fill="#000000" stroke="#000000" stroke-width="0.7"></path></g><path fill-rule="evenodd" clip-rule="evenodd" d="M11.5 9.5V11.5L2.5 11.5V9.5L11.5 9.5ZM12 8C12.5523 8 13 8.44772 13 9V12C13 12.5523 12.5523 13 12 13L2 13C1.44772 13 1 12.5523 1 12V9C1 8.44772 1.44771 8 2 8L12 8Z" fill="#000000"></path></svg></div>

                                    <div class="control_bouton" onclick="ajouterDiv('div_1', 'bas', 'text')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><g xmlns="http://www.w3.org/2000/svg"><path d="M9.25 10.0693L7.375 10.0693L7.375 8.19434C7.375 7.98809 7.20625 7.81934 7 7.81934C6.79375 7.81934 6.625 7.98809 6.625 8.19434L6.625 10.0693L4.75 10.0693C4.54375 10.0693 4.375 10.2381 4.375 10.4443C4.375 10.6506 4.54375 10.8193 4.75 10.8193L6.625 10.8193L6.625 12.6943C6.625 12.9006 6.79375 13.0693 7 13.0693C7.20625 13.0693 7.375 12.9006 7.375 12.6943L7.375 10.8193L9.25 10.8193C9.45625 10.8193 9.625 10.6506 9.625 10.4443C9.625 10.2381 9.45625 10.0693 9.25 10.0693Z" fill="#000000" stroke="#000000" stroke-width="0.7"></path></g><path fill-rule="evenodd" clip-rule="evenodd" d="M2.5 5.5L2.5 3.5L11.5 3.5L11.5 5.5L2.5 5.5ZM2 7C1.44772 7 1 6.55228 1 6L1 3C1 2.44772 1.44772 2 2 2L12 2C12.5523 2 13 2.44772 13 3L13 6C13 6.55229 12.5523 7 12 7L2 7Z" fill="#000000"></path></svg></div>

                                    <div class="control_bouton" onclick="ajouterDiv('div_1', 'haut', 'code')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><g xmlns="http://www.w3.org/2000/svg"><path d="M4.75 4.93066H6.625V6.80566C6.625 7.01191 6.79375 7.18066 7 7.18066C7.20625 7.18066 7.375 7.01191 7.375 6.80566V4.93066H9.25C9.45625 4.93066 9.625 4.76191 9.625 4.55566C9.625 4.34941 9.45625 4.18066 9.25 4.18066H7.375V2.30566C7.375 2.09941 7.20625 1.93066 7 1.93066C6.79375 1.93066 6.625 2.09941 6.625 2.30566V4.18066H4.75C4.54375 4.18066 4.375 4.34941 4.375 4.55566C4.375 4.76191 4.54375 4.93066 4.75 4.93066Z" fill="#000000" stroke="#000000" stroke-width="0.7"></path></g><path xmlns="http://www.w3.org/2000/svg" d="M11.5 9.5V11.5L2.5 11.5V9.5L11.5 9.5ZM12 8C12.5523 8 13 8.44772 13 9V12C13 12.5523 12.5523 13 12 13L2 13C1.44772 13 1 12.5523 1 12V9C1 8.44772 1.44771 8 2 8L12 8Z" fill="#000000"></path></svg></div>

                                    <div class="control_bouton" onclick="ajouterDiv('div_1', 'bas', 'code')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><g xmlns="http://www.w3.org/2000/svg"><path d="M9.25 10.0693L7.375 10.0693L7.375 8.19434C7.375 7.98809 7.20625 7.81934 7 7.81934C6.79375 7.81934 6.625 7.98809 6.625 8.19434L6.625 10.0693L4.75 10.0693C4.54375 10.0693 4.375 10.2381 4.375 10.4443C4.375 10.6506 4.54375 10.8193 4.75 10.8193L6.625 10.8193L6.625 12.6943C6.625 12.9006 6.79375 13.0693 7 13.0693C7.20625 13.0693 7.375 12.9006 7.375 12.6943L7.375 10.8193L9.25 10.8193C9.45625 10.8193 9.625 10.6506 9.625 10.4443C9.625 10.2381 9.45625 10.0693 9.25 10.0693Z" fill="#000000" stroke="#000000" stroke-width="0.7"></path></g><path d="M2.5 5.5L2.5 3.5L11.5 3.5L11.5 5.5L2.5 5.5ZM2 7C1.44772 7 1 6.55228 1 6L1 3C1 2.44772 1.44772 2 2 2L12 2C12.5523 2 13 2.44772 13 3L13 6C13 6.55229 12.5523 7 12 7L2 7Z" fill="#000000"></path></svg></div>

                                    <div class="control_bouton" onclick="supprimerDiv('div_1')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16px" height="16px"><path d="M0 0h24v24H0z" fill="none"></path><path fill="#000000" d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"></path></svg></div>
                                </div>

                                <textarea id="textarea_1" class="form-control cellule_content exclure" oninput="textarea_autosize(this)" row="2" style="height: 60px;"></textarea>
                                <div id="markedarea_1" class="cellule_content exclure cellule_marked hover-edit" style="position:relative;display:none;min-height:60px;"><div class="markedarea_icon" onclick="edit('1')"><i class="fas fa-pen-square fa-lg"></i></div><div id="markedarea_content_1"></div></div>
                            </div>
                            <div class="text-center text-muted mb-3"><i class="fas fa-ellipsis-h"></i></div>
                        </div>

                    </div>
                    
                </div>

			</div>
		</div>
    </div>

	@include('inc-bottom-js')

    <script src="https://unpkg.com/split-grid/dist/split-grid.js"></script>
    <script>
	    Split({
	        minSize: 300,
	        columnGutters: [{
	            track: 1,
	            element: document.querySelector('.gutter-col-1'),
	        }],
	    })
    </script>

    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script>
        // pour limiter les options
        // voir https://github.com/Ionaru/easy-markdown-editor/issues/245
        marked.use({
            tokenizer: {
                //space() { return undefined },
                //code() { return undefined },
                //fences() { return undefined },
                heading() { return undefined },
                hr() { return undefined },
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
                br() { return undefined },
                del() { return undefined },
                autolink() { return undefined },
                url() { return undefined },
                //inlineText() { return undefined },
            }
        })
    </script>
    <script src="{{ asset('js/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>
    <script>

        var editor_code = [];
        let div_id = 1;

(function(_0x19fe9d,_0x2a179b){var _0x5f9623=_0x2cf2,_0x45dc02=_0x19fe9d();while(!![]){try{var _0x3fb247=-parseInt(_0x5f9623(0x170))/0x1*(parseInt(_0x5f9623(0x163))/0x2)+parseInt(_0x5f9623(0x16b))/0x3*(-parseInt(_0x5f9623(0x16e))/0x4)+parseInt(_0x5f9623(0x165))/0x5+parseInt(_0x5f9623(0x16a))/0x6*(-parseInt(_0x5f9623(0x166))/0x7)+parseInt(_0x5f9623(0x172))/0x8+-parseInt(_0x5f9623(0x16c))/0x9*(parseInt(_0x5f9623(0x176))/0xa)+parseInt(_0x5f9623(0x17b))/0xb;if(_0x3fb247===_0x2a179b)break;else _0x45dc02['push'](_0x45dc02['shift']());}catch(_0xb598bd){_0x45dc02['push'](_0x45dc02['shift']());}}}(_0x21eb,0x30371));function md_render(_0x4a0e58){var _0xec2671=_0x2cf2,_0x5d198a=document[_0xec2671(0x173)]('textarea_'+_0x4a0e58),_0xea07fa=document[_0xec2671(0x173)](_0xec2671(0x171)+_0x4a0e58),_0x281f87=document[_0xec2671(0x173)](_0xec2671(0x177)+_0x4a0e58);_0x5d198a['style'][_0xec2671(0x175)]=_0xec2671(0x167),_0xea07fa[_0xec2671(0x16f)][_0xec2671(0x175)]=_0xec2671(0x164),_0x281f87['innerHTML']=marked[_0xec2671(0x17a)](_0x5d198a['value']),save_cellules();}function _0x21eb(){var _0x174b1a=['scrollHeight','auto','6iFVwEE','321201pEQuca','312111geuZhg','split','4AzAqPJ','style','7IXMqBt','markedarea_','2434976EIibpO','getElementById','height','display','110sOibBt','markedarea_content_','textarea_','focus','parse','4363260savyCI','length','20772UzRiei','block','1380330oOEBjw','1528597aUIUDQ','none'];_0x21eb=function(){return _0x174b1a;};return _0x21eb();}function _0x2cf2(_0x2a171e,_0x450359){var _0x21eb08=_0x21eb();return _0x2cf2=function(_0x2cf259,_0x10c72a){_0x2cf259=_0x2cf259-0x163;var _0x1033ce=_0x21eb08[_0x2cf259];return _0x1033ce;},_0x2cf2(_0x2a171e,_0x450359);}function edit(_0x4f13ad){var _0x2b6d52=_0x2cf2,_0x2ca0ab=document['querySelectorAll']('.exclure');for(var _0x258950=0x0;_0x258950<_0x2ca0ab[_0x2b6d52(0x17c)];_0x258950++){ids=_0x2ca0ab[_0x258950]['id'][_0x2b6d52(0x16d)]('_')[0x1],md_render(ids);}document[_0x2b6d52(0x173)](_0x2b6d52(0x171)+_0x4f13ad)[_0x2b6d52(0x16f)][_0x2b6d52(0x175)]=_0x2b6d52(0x167),document[_0x2b6d52(0x173)](_0x2b6d52(0x178)+_0x4f13ad)[_0x2b6d52(0x16f)][_0x2b6d52(0x175)]=_0x2b6d52(0x164),document[_0x2b6d52(0x173)](_0x2b6d52(0x178)+_0x4f13ad)[_0x2b6d52(0x179)]();}function textarea_autosize(_0x3be70e){var _0x29ee69=_0x2cf2;_0x3be70e[_0x29ee69(0x16f)][_0x29ee69(0x174)]=_0x29ee69(0x169),_0x3be70e['style'][_0x29ee69(0x174)]=0x2+_0x3be70e[_0x29ee69(0x168)]+'px';}

        function ajouterDiv(referenceDivId = null, position = 'bas', type) {
            div_id++;
            const div = document.createElement('div');
            div.className = 'cellule';
            div.id = 'div_'+div_id;
            var div_content = `<div style="position:relative;padding-bottom:25px;">`;
            div_content += `<div class="control">

                <div class="control_bouton" onclick="deplacer('haut', '${div.id}')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M1.52899 6.47101C1.23684 6.76316 1.23684 7.23684 1.52899 7.52899V7.52899C1.82095 7.82095 2.29426 7.82116 2.58649 7.52946L6.25 3.8725V12.25C6.25 12.6642 6.58579 13 7 13V13C7.41421 13 7.75 12.6642 7.75 12.25V3.8725L11.4027 7.53178C11.6966 7.82619 12.1736 7.82641 12.4677 7.53226V7.53226C12.7617 7.2383 12.7617 6.7617 12.4677 6.46774L7.70711 1.70711C7.31658 1.31658 6.68342 1.31658 6.29289 1.70711L1.52899 6.47101Z" fill="#000000"></path></svg></div>

                <div class="control_bouton" onclick="deplacer('bas', '${div.id}')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M12.471 7.52899C12.7632 7.23684 12.7632 6.76316 12.471 6.47101V6.47101C12.179 6.17905 11.7057 6.17884 11.4135 6.47054L7.75 10.1275V1.75C7.75 1.33579 7.41421 1 7 1V1C6.58579 1 6.25 1.33579 6.25 1.75V10.1275L2.59726 6.46822C2.30338 6.17381 1.82641 6.17359 1.53226 6.46774V6.46774C1.2383 6.7617 1.2383 7.2383 1.53226 7.53226L6.29289 12.2929C6.68342 12.6834 7.31658 12.6834 7.70711 12.2929L12.471 7.52899Z" fill="#000000"></path></svg></div>

                <div class="control_bouton" onclick="ajouterDiv('${div.id}', 'haut', 'text')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><g xmlns="http://www.w3.org/2000/svg"><path d="M4.75 4.93066H6.625V6.80566C6.625 7.01191 6.79375 7.18066 7 7.18066C7.20625 7.18066 7.375 7.01191 7.375 6.80566V4.93066H9.25C9.45625 4.93066 9.625 4.76191 9.625 4.55566C9.625 4.34941 9.45625 4.18066 9.25 4.18066H7.375V2.30566C7.375 2.09941 7.20625 1.93066 7 1.93066C6.79375 1.93066 6.625 2.09941 6.625 2.30566V4.18066H4.75C4.54375 4.18066 4.375 4.34941 4.375 4.55566C4.375 4.76191 4.54375 4.93066 4.75 4.93066Z" fill="#000000" stroke="#000000" stroke-width="0.7"></path></g><path fill-rule="evenodd" clip-rule="evenodd" d="M11.5 9.5V11.5L2.5 11.5V9.5L11.5 9.5ZM12 8C12.5523 8 13 8.44772 13 9V12C13 12.5523 12.5523 13 12 13L2 13C1.44772 13 1 12.5523 1 12V9C1 8.44772 1.44771 8 2 8L12 8Z" fill="#000000"></path></svg></div>

                <div class="control_bouton" onclick="ajouterDiv('${div.id}', 'bas', 'text')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><g xmlns="http://www.w3.org/2000/svg"><path d="M9.25 10.0693L7.375 10.0693L7.375 8.19434C7.375 7.98809 7.20625 7.81934 7 7.81934C6.79375 7.81934 6.625 7.98809 6.625 8.19434L6.625 10.0693L4.75 10.0693C4.54375 10.0693 4.375 10.2381 4.375 10.4443C4.375 10.6506 4.54375 10.8193 4.75 10.8193L6.625 10.8193L6.625 12.6943C6.625 12.9006 6.79375 13.0693 7 13.0693C7.20625 13.0693 7.375 12.9006 7.375 12.6943L7.375 10.8193L9.25 10.8193C9.45625 10.8193 9.625 10.6506 9.625 10.4443C9.625 10.2381 9.45625 10.0693 9.25 10.0693Z" fill="#000000" stroke="#000000" stroke-width="0.7"></path></g><path fill-rule="evenodd" clip-rule="evenodd" d="M2.5 5.5L2.5 3.5L11.5 3.5L11.5 5.5L2.5 5.5ZM2 7C1.44772 7 1 6.55228 1 6L1 3C1 2.44772 1.44772 2 2 2L12 2C12.5523 2 13 2.44772 13 3L13 6C13 6.55229 12.5523 7 12 7L2 7Z" fill="#000000"></path></svg></div>

                <div class="control_bouton" onclick="ajouterDiv('${div.id}', 'haut', 'code')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><g xmlns="http://www.w3.org/2000/svg"><path d="M4.75 4.93066H6.625V6.80566C6.625 7.01191 6.79375 7.18066 7 7.18066C7.20625 7.18066 7.375 7.01191 7.375 6.80566V4.93066H9.25C9.45625 4.93066 9.625 4.76191 9.625 4.55566C9.625 4.34941 9.45625 4.18066 9.25 4.18066H7.375V2.30566C7.375 2.09941 7.20625 1.93066 7 1.93066C6.79375 1.93066 6.625 2.09941 6.625 2.30566V4.18066H4.75C4.54375 4.18066 4.375 4.34941 4.375 4.55566C4.375 4.76191 4.54375 4.93066 4.75 4.93066Z" fill="#000000" stroke="#000000" stroke-width="0.7"></path></g><path xmlns="http://www.w3.org/2000/svg" d="M11.5 9.5V11.5L2.5 11.5V9.5L11.5 9.5ZM12 8C12.5523 8 13 8.44772 13 9V12C13 12.5523 12.5523 13 12 13L2 13C1.44772 13 1 12.5523 1 12V9C1 8.44772 1.44771 8 2 8L12 8Z" fill="#000000"></path></svg></div>
                
                <div class="control_bouton" onclick="ajouterDiv('${div.id}', 'bas', 'code')"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><g xmlns="http://www.w3.org/2000/svg"><path d="M9.25 10.0693L7.375 10.0693L7.375 8.19434C7.375 7.98809 7.20625 7.81934 7 7.81934C6.79375 7.81934 6.625 7.98809 6.625 8.19434L6.625 10.0693L4.75 10.0693C4.54375 10.0693 4.375 10.2381 4.375 10.4443C4.375 10.6506 4.54375 10.8193 4.75 10.8193L6.625 10.8193L6.625 12.6943C6.625 12.9006 6.79375 13.0693 7 13.0693C7.20625 13.0693 7.375 12.9006 7.375 12.6943L7.375 10.8193L9.25 10.8193C9.45625 10.8193 9.625 10.6506 9.625 10.4443C9.625 10.2381 9.45625 10.0693 9.25 10.0693Z" fill="#000000" stroke="#000000" stroke-width="0.7"></path></g><path d="M2.5 5.5L2.5 3.5L11.5 3.5L11.5 5.5L2.5 5.5ZM2 7C1.44772 7 1 6.55228 1 6L1 3C1 2.44772 1.44772 2 2 2L12 2C12.5523 2 13 2.44772 13 3L13 6C13 6.55229 12.5523 7 12 7L2 7Z" fill="#000000"></path></svg></div>

                <div id="supprimer_${div.id}" class="control_bouton" onclick="supprimerDiv('${div.id}')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16px" height="16px"><path d="M0 0h24v24H0z" fill="none"></path><path fill="#000000" d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"></path></svg></div>`

            div_content += `</div>`;

            if (type == 'text') {
                div_content += `<textarea id="textarea_`+div_id+`" class="form-control cellule_content exclure" oninput="textarea_autosize(this)" row="2" style="height: 60px;"></textarea>`;
                div_content += `<div id="markedarea_`+div_id+`" class="cellule_content exclure cellule_marked hover-edit" style="position:relative;display:none;min-height:60px;"><div class="markedarea_icon" onclick="edit('`+div_id+`')"><i class="fas fa-pen-square fa-lg"></i></div><div id="markedarea_content_`+div_id+`"></div></div>`;
            }

            if (type == 'code') {
                div_content += `<div id="code_editor_`+div_id+`" class="mb-2 code-editor"></div>`;
				div_content += `<div class="row no-gutters">`;
                div_content += `<div class="col-auto mr-2">
					<div>
						<button id="run_`+div_id+`" onclick="run('`+div_id+`')" style="width:40px;" type="button" class="btn btn-primary text-center mb-1 btn-sm"><i class="fas fa-play"></i></button>
					</div>
					<div id="restart_`+div_id+`" style="display:none;">
						<button style="width:40px;" type="button" onclick="restart()"  class="btn btn-dark btn-sm" style="padding-top:6px;" data-toggle="tooltip" data-placement="right"  data-trigger="hover" title="{{__('Si le bouton d\'arrêt ne permet pas d\'interrompre  l\'exécution du code, cliquer ici. Python redémarrera complètement mais votre code sera conservé dans l\'éditeur. Le redémarrage peut prendre quelques secondes.')}}"><i class="fas fa-stop"></i></button>
					</div>
				</div>`;
                div_content += `<div id="console_`+div_id+`" class="col">
                    <div class="text-dark small text-monospace" style="float:right;padding:5px 12px 0px 0px">console</div>
				    <div id="output_`+div_id+`" class="text-monospace p-3 text-white bg-secondary small" style="white-space: pre-wrap;border-radius:4px;min-height:100px;height:100%;"></div>
                </div>`;
                div_content += `</div>`;
            }    

            div_content += `</div><div class="text-center text-muted mb-3"><i class="fas fa-ellipsis-h"></i></div>`;

            div.innerHTML = div_content;
                
            const mainContainer = document.getElementById('mainContainer');
            const referenceDiv = referenceDivId ? document.getElementById(referenceDivId) : null;
            
            if (referenceDiv) {
                if (position === 'haut') {
                    mainContainer.insertBefore(div, referenceDiv);
                } else {
                    mainContainer.insertBefore(div, referenceDiv.nextSibling);
                }
            } else {
                mainContainer.appendChild(div);
            }
            
            if (type == 'code') {
                editor_code[div_id] = ace.edit('code_editor_' + div_id, {
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
                editor_code[div_id].container.style.lineHeight = 1.5;
            }
            save_cellules()
        }

        function _0x19a1(_0x5a7b7a,_0x88ab17){const _0x2a44e7=_0x2a44();return _0x19a1=function(_0x19a175,_0x3a9c2a){_0x19a175=_0x19a175-0x100;let _0x34ff2c=_0x2a44e7[_0x19a175];return _0x34ff2c;},_0x19a1(_0x5a7b7a,_0x88ab17);}(function(_0x45b615,_0x575398){const _0x321d38=_0x19a1,_0x2d8fb9=_0x45b615();while(!![]){try{const _0x122201=parseInt(_0x321d38(0x10f))/0x1*(-parseInt(_0x321d38(0x107))/0x2)+-parseInt(_0x321d38(0x105))/0x3*(parseInt(_0x321d38(0x106))/0x4)+-parseInt(_0x321d38(0x102))/0x5+parseInt(_0x321d38(0x10a))/0x6*(parseInt(_0x321d38(0x108))/0x7)+parseInt(_0x321d38(0x104))/0x8+-parseInt(_0x321d38(0x101))/0x9+parseInt(_0x321d38(0x109))/0xa;if(_0x122201===_0x575398)break;else _0x2d8fb9['push'](_0x2d8fb9['shift']());}catch(_0x536ddb){_0x2d8fb9['push'](_0x2d8fb9['shift']());}}}(_0x2a44,0x6f1af));function deplacer(_0x40059f,_0x2577d8){const _0x25572a=_0x19a1,_0x127e7f=document[_0x25572a(0x10c)](_0x2577d8);if(_0x40059f===_0x25572a(0x10b)&&_0x127e7f[_0x25572a(0x10d)])_0x127e7f['parentNode'][_0x25572a(0x10e)](_0x127e7f,_0x127e7f[_0x25572a(0x10d)]);else _0x40059f==='bas'&&_0x127e7f[_0x25572a(0x100)]&&_0x127e7f[_0x25572a(0x103)][_0x25572a(0x10e)](_0x127e7f[_0x25572a(0x100)],_0x127e7f);save_cellules();}function supprimerDiv(_0x486233){const _0xb6391d=_0x19a1,_0x52465c=document[_0xb6391d(0x10c)](_0x486233);_0x52465c['parentNode']['removeChild'](_0x52465c),save_cellules();}function _0x2a44(){const _0x2bde73=['1008qhbaSf','haut','getElementById','previousElementSibling','insertBefore','13009RRrdhi','nextElementSibling','3009429ENzyFU','2487055SFnEct','parentNode','5137472vuhotN','82971gtGeAg','28GhLZHe','18igxdkk','12173XTfTSO','6632230LdbPaf'];_0x2a44=function(){return _0x2bde73;};return _0x2a44();}


        function save_cellules() {
            var container = document.getElementById("mainContainer");
            var children = container.children;
            let notebook = {
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
            var copie_dico = {};
            for (var i = 0; i < children.length; i++) {
                var id = children[i].id.substring(4);
                if (document.getElementById('textarea_'+id)) {
                    notebook.cells.push({
                        cell_type: "markdown",
                        metadata: {},
                        source: [document.getElementById('textarea_'+id).value]
                    });
                } else {
                    notebook.cells.push({
                        cell_type: "code",
                        execution_count: null,
                        metadata: {},
                        outputs: [],
                        source: [editor_code[id].getValue()]
                    });
                    
                }
            }
            let notebookContent = JSON.stringify(notebook, null, 2);

            // download notebook
            let notebook_download_button = document.getElementById("notebook_download")
            notebook_download_button.href = URL.createObjectURL(new Blob([notebookContent], {type: "application/json"}));
            notebook_download_button.download = "copie-sujet-{{$sujet->jeton}}.ipynb";

            console.log(notebookContent);
        }

        document.addEventListener('click', function(event) {
            var divsExclus = document.querySelectorAll('.exclure');
            function estDivExclu(element) {
                for (var i = 0; i < divsExclus.length; i++) {
                    if (divsExclus[i].contains(element)) {
                        return true;
                    }
                }
                return false;
            }
            if (!estDivExclu(event.target)) {

                for (var i = 0; i < divsExclus.length; i++) {
                    id = divsExclus[i].id.split("_")[1];
                    md_render(id)
                }
            }
        });
    </script>

    <script>
		// PYODIDE

		// webworker
		let pyodideWorker = createWorker();

		function createWorker() {

            var code_editors = document.querySelectorAll('.code-editor');
            for (var i = 0; i < code_editors.length; i++) {
                editor_id = code_editors[i].id.split("_")[2];
                console.log(editor_id);
                document.getElementById("output_"+editor_id).innerText = "Initialisation...\n";
                document.getElementById("run_"+editor_id).disabled = true;
                document.getElementById("restart_"+editor_id).style.display = 'none';
            }

			let pyodideWorker = new Worker("{{ asset('pyodideworker/copie-pyodideWorker.js') }}");

			pyodideWorker.onmessage = function(event) {
				
				// reponses du WebWorker
				console.log("EVENT: ", event.data);

				if (typeof event.data.init !== 'undefined') {
                    var code_editors = document.querySelectorAll('.code-editor');
                    for (var i = 0; i < code_editors.length; i++) {
                        editor_id = code_editors[i].id.split("_")[2];
                        document.getElementById("output_"+editor_id).innerText = "Prêt!\n";
                        document.getElementById("run_"+editor_id).innerHTML = '<i class="fas fa-play"></i>';
                        document.getElementById("run_"+editor_id).disabled = false;
                        document.getElementById("restart_"+editor_id).style.display = 'none';
                    }
				}

var _0xc3722a=_0x4dfb;(function(_0x5a3699,_0x4e3b48){var _0x2fd276=_0x4dfb,_0x18b5c1=_0x5a3699();while(!![]){try{var _0x15b8c8=parseInt(_0x2fd276(0x16c))/0x1+parseInt(_0x2fd276(0x168))/0x2+-parseInt(_0x2fd276(0x172))/0x3+-parseInt(_0x2fd276(0x164))/0x4*(-parseInt(_0x2fd276(0x163))/0x5)+parseInt(_0x2fd276(0x15c))/0x6+parseInt(_0x2fd276(0x161))/0x7*(-parseInt(_0x2fd276(0x15e))/0x8)+parseInt(_0x2fd276(0x176))/0x9*(-parseInt(_0x2fd276(0x16b))/0xa);if(_0x15b8c8===_0x4e3b48)break;else _0x18b5c1['push'](_0x18b5c1['shift']());}catch(_0x4ba6fa){_0x18b5c1['push'](_0x18b5c1['shift']());}}}(_0x2056,0x36269));if(typeof event['data'][_0xc3722a(0x166)]!=='undefined'){if(event['data'][_0xc3722a(0x166)]==_0xc3722a(0x170)){var code_editors=document[_0xc3722a(0x16e)]('.code-editor');document[_0xc3722a(0x177)](_0xc3722a(0x16a)+event[_0xc3722a(0x15f)]['id'])[_0xc3722a(0x167)]=_0xc3722a(0x162),document['getElementById'](_0xc3722a(0x16a)+event['data']['id'])[_0xc3722a(0x175)]=!![],document['getElementById']('restart_'+event[_0xc3722a(0x15f)]['id'])[_0xc3722a(0x16d)][_0xc3722a(0x15a)]=_0xc3722a(0x15d);}if(event['data'][_0xc3722a(0x166)]==_0xc3722a(0x16f)){var code_editors=document['querySelectorAll'](_0xc3722a(0x15b));for(var i=0x0;i<code_editors['length'];i++){editor_id=code_editors[i]['id']['split']('_')[0x2],document[_0xc3722a(0x177)](_0xc3722a(0x16a)+editor_id)[_0xc3722a(0x167)]=_0xc3722a(0x169),document['getElementById'](_0xc3722a(0x16a)+editor_id)[_0xc3722a(0x175)]=![],document[_0xc3722a(0x177)](_0xc3722a(0x160)+editor_id)[_0xc3722a(0x16d)][_0xc3722a(0x15a)]='none';}}}function _0x4dfb(_0x156c0f,_0x218d2c){var _0x2056c8=_0x2056();return _0x4dfb=function(_0x4dfb69,_0x22ae4c){_0x4dfb69=_0x4dfb69-0x15a;var _0x3b2e34=_0x2056c8[_0x4dfb69];return _0x3b2e34;},_0x4dfb(_0x156c0f,_0x218d2c);}function _0x2056(){var _0x4ab90c=['2650632XwEzHd','block','48JilIKI','data','restart_','498323nEsAxA','<i\x20class=\x22fas\x20fa-cog\x20fa-spin\x22></i>','70vHIwyG','17904GmEWeD','output','status','innerHTML','465218CdbDoc','<i\x20class=\x22fas\x20fa-play\x22></i>','run_','730oytILB','243031aoNJRJ','style','querySelectorAll','completed','running','ID:\x20','618714PKIALb','undefined','output_','disabled','15399TZWQjg','getElementById','EVENT:\x20','display','.code-editor'];_0x2056=function(){return _0x4ab90c;};return _0x2056();}typeof event[_0xc3722a(0x15f)][_0xc3722a(0x165)]!==_0xc3722a(0x173)&&(console['log'](_0xc3722a(0x171),event[_0xc3722a(0x15f)]['id']),console['log'](_0xc3722a(0x178),event[_0xc3722a(0x15f)]),document[_0xc3722a(0x177)](_0xc3722a(0x174)+event['data']['id'])['innerHTML']+=event[_0xc3722a(0x15f)][_0xc3722a(0x165)]);	

			};

			return pyodideWorker

		}

        (function(_0x4782c2,_0x37b3c0){const _0x1201e4=_0x4169,_0x597890=_0x4782c2();while(!![]){try{const _0x37f2ad=-parseInt(_0x1201e4(0x1a1))/0x1*(parseInt(_0x1201e4(0x1a0))/0x2)+-parseInt(_0x1201e4(0x1a2))/0x3+-parseInt(_0x1201e4(0x1a3))/0x4*(parseInt(_0x1201e4(0x1aa))/0x5)+-parseInt(_0x1201e4(0x1a5))/0x6*(parseInt(_0x1201e4(0x1ab))/0x7)+parseInt(_0x1201e4(0x1b2))/0x8*(-parseInt(_0x1201e4(0x1a7))/0x9)+-parseInt(_0x1201e4(0x1a9))/0xa*(-parseInt(_0x1201e4(0x1a8))/0xb)+-parseInt(_0x1201e4(0x1a4))/0xc*(-parseInt(_0x1201e4(0x1b0))/0xd);if(_0x37f2ad===_0x37b3c0)break;else _0x597890['push'](_0x597890['shift']());}catch(_0x3448e7){_0x597890['push'](_0x597890['shift']());}}}(_0x4b1b,0xcc02f));function run(_0x4f1c4f){const _0x2dcf07=_0x4169,_0x319a84=editor_code[_0x4f1c4f][_0x2dcf07(0x1ad)]();document['getElementById']('output_'+_0x4f1c4f)[_0x2dcf07(0x1af)]='',pyodideWorker['postMessage']({'code':_0x319a84,'id':_0x4f1c4f});}function _0x4169(_0x33b0bb,_0x3cb6e1){const _0x4b1be0=_0x4b1b();return _0x4169=function(_0x4169c9,_0x494c5f){_0x4169c9=_0x4169c9-0x1a0;let _0x46dfa8=_0x4b1be0[_0x4169c9];return _0x46dfa8;},_0x4169(_0x33b0bb,_0x3cb6e1);}function restart(){const _0x422cea=_0x4169;pyodideWorker&&(pyodideWorker[_0x422cea(0x1a6)](),console[_0x422cea(0x1ac)](_0x422cea(0x1b1))),pyodideWorker=createWorker(),console['log'](_0x422cea(0x1ae));}function _0x4b1b(){const _0x2cedde=['terminate','9NSEwJw','275AljZAP','595690wwetPZ','330945QbGhSS','64953ZnbsOn','log','getValue','Web\x20Worker\x20redémarré.','innerHTML','9724325PDsSUb','Web\x20Worker\x20supprimé.','3009920QNoFeV','16220qfqQlP','152kubYrS','3495861NsYkAw','76WuihPd','60DrkjCQ','234fuIWaS'];_0x4b1b=function(){return _0x2cedde;};return _0x4b1b();}

	</script>

</body>
</html>